<?php

use yii\db\Migration;

/**
 * Class m200513_111432_views
 */
class m200513_111432_views extends Migration
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
            'id'=> $this->bigInteger(),
            'context' => $this->string(32)->notNull(),
            'target' => $this->string(128)->notNull(),
            'counter' => $this->integer()->defaultValue(1),
            'params' => $this->text()->notNull(),
            'user_id' => $this->integer(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_views_user','{{%views}}', ['user_id'],false);
        $this->createIndex('idx_views_condition','{{%views}}', ['context', 'target'],false);

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
