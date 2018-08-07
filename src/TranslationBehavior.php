<?php

/**
 * @link https://github.com/rkit/translation-behavior-yii2
 * @copyright Copyright (c) 2018 Igor Romanov
 * @license [MIT](http://opensource.org/licenses/MIT)
 */

namespace rkit\translation\behavior;

use yii\base\{Behavior, InvalidConfigException}; // phpcs:ignore
use yii\db\ActiveRecord;

class TranslationBehavior extends Behavior
{
    /**
     * @var string The name of the has-one relation.
     */
    public $relationOne = 'translation';
    /**
     * @var string The name of the has-many relation.
     */
    public $relationMany = 'translations';
    /**
     * @var string The language attribute name
     */
    public $languageAttribute = 'language';
    /**
     * @var int|string The default language.
     */
    public $defaultLanguage = null;
    /**
     * @var string[] The attributes for translation.
     */
    public $attributes = null;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->attributes === null) {
            throw new InvalidConfigException('The "attributes" property must be set.');
        }

        $this->attributes[] = $this->languageAttribute;
    }

    /**
     * @inheritdoc
     */
    public function afterSave()
    {
        $translations = $this->owner->{$this->relationMany};
        $this->owner->unlinkAll($this->relationMany, true);

        foreach ($translations as $translation) {
            $this->owner->link($this->relationMany, $translation);
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return in_array($name, $this->attributes) ?: parent::canGetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return in_array($name, $this->attributes) ?: parent::canSetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        return $this->translate()->getAttribute($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        $translation = $this->translate();
        $translation->setAttribute($name, $value);
    }

    public function loadTranslations($data)
    {
        $this->owner->populateRelation($this->relationMany, []);

        foreach ($data as $language => $items) {
            foreach ($items as $attribute => $translation) {
                $this->owner->translate($language)->$attribute = $translation;
            }
        }

        return true;
    }

    /**
     * Returns the translation model for the specified language.
     * 
     * @param string|int|null $language
     * @return ActiveRecord
     */
    public function translate($language = null)
    {
        if ($this->owner->isRelationPopulated($this->relationOne) && $this->owner->{$this->relationOne}) {
            return $this->owner->{$this->relationOne};
        }

        $translations = $this->owner->{$this->relationMany};

        if ($language !== null) {
            foreach ($translations as $translation) {
                if ($translation->getAttribute($this->languageAttribute) === $language) {
                    return $translation;
                }
            }
        }

        $language = $language === null ? $this->defaultLanguage : $language;

        $translation = $this->createTranslation($language);

        $translations[] = $translation;
        $this->owner->populateRelation($this->relationMany, $translations);

        return $translation;
    }

    private function createTranslation($language)
    {
        $translationClass = $this->owner->getRelation($this->relationMany)->modelClass;

        $translation = new $translationClass();
        $translation->setAttribute($this->languageAttribute, $language);

        return $translation;
    }
}
