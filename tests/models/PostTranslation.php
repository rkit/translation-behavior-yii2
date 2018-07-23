<?php

namespace tests\models;

class PostTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_translation}}';
    }

    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 10],
            [['title'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new \yii\db\ActiveQuery(get_called_class());
    }
}
