<?php

class m180206_070211_create_post_translation extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%post_translation}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull()->defaultValue(0),
            'language' => $this->string(2)->notNull()->defaultValue(''),
            'title' => $this->string()->notNull()->defaultValue(''),
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%post_translation}}');
    }
}
