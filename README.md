# Translation Behavior for Yii2

[![Build Status](https://travis-ci.org/rkit/translation-behavior-yii2.svg?branch=master)](https://travis-ci.org/rkit/translation-behavior-yii2)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rkit/translation-behavior-yii2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rkit/translation-behavior-yii2/?branch=master)

## Requirements

PHP 7

## Installation

```
composer require rkit/translation-behavior-yii2
```

## Configuration

For example, we have a `Post` model and we want to add translation capability.  
Let's do it.

1. Add a `post_translation` table and a `PostTranslation` model for the translation

```php
$this->createTable('{{%post_translation}}', [
    'id' => $this->primaryKey(),
    'post_id' => $this->integer()->notNull()->defaultValue(0),
    'language' => $this->string(2)->notNull()->defaultValue(''),
    'title' => $this->string()->notNull()->defaultValue(''),
]);
```

2. Add a `TranslationBehavior` behavior to the `Post` model

```php
public function behaviors()
{
    return [
        'translationBehavior' => [
            'class' => 'rkit\translation\behavior\TranslationBehavior',
            'relationOne' => 'translation',
            'relationMany' => 'translations',
            'languageAttribute' => 'language',
            'defaultLanguage' => 'en',
            'attributes' => [ // attributes for translation
                'title',
            ],

        ],
    ];
}
```

3. Add `translation` and `translations` relations (see `relationOne` and `relationMany` options in the behavior)

```php
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
```

## Usage

### Load translation

```php
$model = new Post();
$model->loadTranslations([
    'en' => ['title' => 'example'],
    'ru' => ['title' => 'пример'],
]);
$model->save();
```

### Get translation

#### For current language
```php
$model = Post::find()->with('translation')->where(['id' => $id])->one();

echo $model->title;
```

#### All translation
```php
$model = Post::find()->with('translations')->where(['id' => $id])->one();

echo $model->translate('en')->title;
echo $model->translate('ru')->title;
```

### Remove translation

```php
$model = new Post();
$model->loadTranslations([]);
$model->save();
```

## Tests

- [See docs](/tests/#tests)

## Coding Standard

- PHP Code Sniffer ([phpcs.xml](./phpcs.xml))
