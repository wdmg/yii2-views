<?php

namespace wdmg\views\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wdmg\views\models\Views;

/**
 * ViewsSearch represents the model behind the search form of `wdmg\views\models\Views`.
 */
class ViewsSearch extends Views
{

    public $range;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['context', 'target', 'range'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Views::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'counter' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Group and sum counters
        $query->select('id, user_id, context, target, created_at, updated_at, sum(counter) as views');

        // Grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'context' => $this->context,
            'target' => $this->target,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);


        // Filter by range
        if ($this->range !== "*") {
            switch ($this->range) {
                case '< 1000' :
                    $query->having(['<', 'sum(counter)', 1000]);
                    break;

                case '>= 1000' :
                    $query->having(['>=', 'sum(counter)', 1000]);
                    break;

                case '>= 10000' :
                    $query->having(['>=', 'sum(counter)', (1000 * 10)]);
                    break;

                case '> 100000' :
                    $query->having(['>', 'sum(counter)', (1000 * 100)]);
                    break;

                case '> 1000000' :
                    $query->having(['>', 'sum(counter)', (1000 * 1000)]);
                    break;

                case '> 10000000' :
                    $query->having(['>', 'sum(counter)', (1000 * 1000 * 10)]);
                    break;
            }
        }

        $query->groupBy(['context', 'target']);

        /*$dataProvider->setSort([
            'attributes' => [
                'id',
                'fullName' => [
                    'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                    'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                    'label' => 'Full Name',
                    'default' => SORT_ASC
                ],
                'country_id'
            ]
        ]);*/

        return $dataProvider;
    }
}
