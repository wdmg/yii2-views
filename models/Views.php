<?php

namespace wdmg\views\models;

use Yii;

/**
 * This is the model class for table "{{%views}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $condition
 * @property string $created_at
 * @property string $updated_at
 * @property string $session
 *
 * @property Users $user
 */
class Views extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%views}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['user_id'], 'integer'],
            [['condition', 'session'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['condition'], 'string', 'max' => 64],
            [['session'], 'string', 'max' => 32],
        ];

        if(class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users']))
            $rules[] = [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \wdmg\users\models\Users::className(), 'targetAttribute' => ['user_id' => 'id']];

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/modules/views', 'ID'),
            'user_id' => Yii::t('app/modules/views', 'User ID'),
            'condition' => Yii::t('app/modules/views', 'Condition'),
            'created_at' => Yii::t('app/modules/views', 'Created At'),
            'updated_at' => Yii::t('app/modules/views', 'Updated At'),
            'session' => Yii::t('app/modules/views', 'Session'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
