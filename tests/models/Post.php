<?php

namespace tests\models;

class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translationBehavior' => [
                'class' => 'rkit\translation\behavior\TranslationBehavior',
                'relationOne' => 'translation',
                'relationMany' => 'translations',
                'languageAttribute' => 'language',
                'defaultLanguage' => 'en',
                'attributes' => [
                    'title',
                ],

            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation()
    {
        return $this
            ->hasOne(PostTranslation::class, ['post_id' => 'id'])
            ->andWhere(['language' => \Yii::$app->language]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PostTranslation::class, ['post_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new \yii\db\ActiveQuery(get_called_class());
    }
}
