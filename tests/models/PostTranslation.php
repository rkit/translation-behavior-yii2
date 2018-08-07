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

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new \yii\db\ActiveQuery(get_called_class());
    }
}
