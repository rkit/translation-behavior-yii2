<?php

namespace tests;

use Yii;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use tests\DatabaseCaseTrait;
use tests\models\Post;

class TranslationBehaviorTest extends TestCase
{
    use TestCaseTrait;
    use DatabaseCaseTrait;
    
    private $model;

    /**
     * @return PHPUnit\DbUnit\DataSet\IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(Yii::getAlias('@tests/fixtures/data.xml'));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage The "attributes" property must be set.
     */
    public function testWrongConfig()
    {
        $model = new \yii\base\Model();
        $model->attachBehavior(
            'translationBehavior',
            [
                'class' => 'rkit\translation\behavior\TranslationBehavior',
            ]
        );
    }

    public function testLoad()
    {
        $model = new Post();
        $model->loadTranslations(['PostTranslation' => [
            'en' => ['title' => 'example'],
            'ru' => ['title' => 'пример'],
        ]]);
        $model->save();

        $this->assertCount(2, $model->translations);
    
        $model = Post::find()->with('translations')->where(['id' => $model->id])->one();

        $this->assertEquals($model->translate('en')->title, 'example');
        $this->assertEquals($model->translate('ru')->title, 'пример');

        $this->assertCount(2, $model->translations);
    }

    public function testValidation()
    {
        $model = new Post();

        $model->loadTranslations(['PostTranslation' => [
            'en' => ['title' => 'example'],
        ]]);
        
        $this->assertEquals($model->validateTranslations(), true);

        $model->loadTranslations(['PostTranslation' => [
            'en' => ['title' => 'example_1234567890'],
        ]]);
        
        $this->assertEquals($model->validateTranslations(), false);
    }

    public function testDefaultTranslation()
    {
        $model = new Post();
        $model->loadTranslations(['PostTranslation' => [
            'en' => ['title' => 'example'],
            'ru' => ['title' => 'пример'],
        ]]);
        $model->save();
    
        Yii::$app->language = 'en';
        $model = Post::find()->with('translation')->where(['id' => $model->id])->one();

        $this->assertEquals($model->title, 'example');
        $this->assertEquals($model->translate()->title, 'example');
        $this->assertEquals($model->translate('en')->title, 'example');

        Yii::$app->language = 'ru';
        $model = Post::find()->with('translation')->where(['id' => $model->id])->one();

        $this->assertEquals($model->title, 'пример');
        $this->assertEquals($model->translate()->title, 'пример');
        $this->assertEquals($model->translate('ru')->title, 'пример');
    }

    public function testDelete()
    {
        $model = new Post();
        $model->loadTranslations(['PostTranslation' => [
            'en' => ['title' => 'example'],
            'ru' => ['title' => 'пример2'],
        ]]);
        $model->save();

        $this->assertCount(2, $model->translations);

        $model = Post::find()->with('translations')->where(['id' => $model->id])->one();
        $model->loadTranslations(['PostTranslation' => [
            'en' => ['title' => 'example1'],
        ]]);
        $model->save();

        $model = Post::find()->with('translations')->where(['id' => $model->id])->one();

        $this->assertCount(1, $model->translations);
    }
}
