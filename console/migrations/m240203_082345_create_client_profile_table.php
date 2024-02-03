<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client_profile}}`.
 */
class m240203_082345_create_client_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client_profile}}', [
            'id' => $this->primaryKey(),
            'uuid' => $this->string()->notNull(),
            'profile_id' => $this->integer()->notNull(),
            'client_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
        ]);

        $this->addForeignKey('fk-client_profile-profile_id-profiles-id', '{{%client_profile}}', 'profile_id', '{{%profiles}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-client_profile-client_id-clients-id', '{{%client_profile}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-client_profile-profile_id-profiles-id', '{{%client_profile}}');
        $this->dropForeignKey('fk-client_profile-client_id-clients-id', '{{%client_profile}}');
        $this->dropTable('{{%client_profile}}');
    }
}
