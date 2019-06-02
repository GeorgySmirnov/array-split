<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%array_split}}`.
 */
class m190602_121119_create_array_split_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%array_split}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'number_n' => $this->integer()->notNull(),
            'array' => $this->json()->notNull(),
            'split_index' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-array_split-user_id',
            '{{%array_split}}',
            'user_id'
        );

        $this->addForeignKey(
            'fk-array_split-user_id',
            '{{%array_split}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-array_split-user_id', '{{%array_split}}');

        $this->dropIndex('idx-array_split-user_id', '{{%array_split}}');
        
        $this->dropTable('{{%array_split}}');
    }
}
