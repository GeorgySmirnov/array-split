<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190602_115156_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'access_token' => $this->string(32)->notNull()->unique(),
        ]);

        $this->createIndex(
            'idx-user-access_token',
            '{{%user}}',
            'access_token'
        );

        $this->insert('{{%user}}', [
            'access_token' => '00000000000000000000000000000000'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user-access_token', '{{%user}}');
        
        $this->dropTable('{{%user}}');
    }
}
