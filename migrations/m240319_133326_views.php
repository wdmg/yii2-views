<?php

use yii\db\Migration;

/**
 * Class m240319_133326_views
 */
class m240319_133326_views extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%views}}', [
            'id'=> $this->bigPrimaryKey(20),
            'user_id' => $this->integer()->null(),
            'condition' => $this->string(64)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
            'session' => $this->string(32)->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_views_user','{{%views}}', ['user_id'],false);
        $this->createIndex('idx_views_condition','{{%views}}', ['condition'],false);
        $this->createIndex('idx_views_session','{{%views}}', ['session'],false);
        $this->createIndex('idx_views_published','{{%views}}', ['is_published'],false);

        // If exist module `Users` set foreign key `user_id` to `users.id`
        if(class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $userTable = \wdmg\users\models\Users::tableName();
            $this->addForeignKey(
                'fk_views_to_users',
                '{{%views}}',
                'user_id',
                $userTable,
                'id',
                'NO ACTION',
                'CASCADE'
            );
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_views_user', '{{%views}}');
        $this->dropIndex('idx_views_condition', '{{%views}}');
        $this->dropIndex('idx_views_session', '{{%views}}');
        $this->dropIndex('idx_views_published', '{{%views}}');

        if(class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $userTable = \wdmg\users\models\Users::tableName();
            if (!(Yii::$app->db->getTableSchema($userTable, true) === null)) {
                $this->dropForeignKey(
                    'fk_views_to_users',
                    '{{%views}}'
                );
            }
        }

        $this->truncateTable('{{%views}}');
        $this->dropTable('{{%views}}');
    }

}