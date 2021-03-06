<?php

namespace wdmg\views\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%views}}".
 *
 * @property int $id
 * @property string $context
 * @property string $target
 * @property int $counter
 * @property string $params
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Users $user
 */
class Views extends \yii\db\ActiveRecord
{
    public $views;

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
            [['counter', 'user_id'], 'integer'],
            [['context', 'target'], 'required'],
            [['context'], 'string', 'max' => 32],
            [['target'], 'string', 'max' => 128],
            [['counter', 'created_at', 'updated_at'], 'safe'],
        ];

        if(class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $rules[] = [['user_id'], 'required'];
            $rules[] = [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \wdmg\users\models\Users::class, 'targetAttribute' => ['user_id' => 'id']];
        }
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/modules/views', 'ID'),
            'context' => Yii::t('app/modules/views', 'Context'),
            'target' => Yii::t('app/modules/views', 'Target'),
            'counter' => Yii::t('app/modules/views', 'Counter'),
            'params' => Yii::t('app/modules/views', 'Params'),
            'views' => Yii::t('app/modules/views', 'Views'),
            'user_id' => Yii::t('app/modules/views', 'User ID'),
            'created_at' => Yii::t('app/modules/views', 'Created'),
            'updated_at' => Yii::t('app/modules/views', 'Updated'),
        ];
    }

    public function beforeValidate()
    {
        if (is_array($this->params))
            $this->params = serialize($this->params);

        return parent::beforeValidate();
    }

    public function afterFind()
    {
        if (is_string($this->params))
            $this->params = unserialize($this->params);

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->counter++;
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        if(class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users']))
            return $this->hasOne(\wdmg\users\models\Users::class, ['id' => 'user_id']);
        else
            return null;
    }

    /**
     * @param bool $allRanges
     * @return array
     */
    public function getViewsRangeList($allRanges = false)
    {
        $list = [];
        if ($allRanges) {
            $list = [
                '*' => Yii::t('app/modules/views', 'All ranges')
            ];
        }

        $list = ArrayHelper::merge($list, [
            '< 1000' => Yii::t('app/modules/views', 'Less than 1K views'),
            '>= 1000' => Yii::t('app/modules/views', 'Over 1K views'),
            '>= 10000' => Yii::t('app/modules/views', 'Over 10K views'),
            '> 100000' => Yii::t('app/modules/views', 'Over 100K views'),
            '> 1000000' => Yii::t('app/modules/views', 'More than 1M views'),
            '> 10000000' => Yii::t('app/modules/views', 'More than 10M views'),
        ]);

        return $list;
    }
}
