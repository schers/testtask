<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cards;

/**
 * CardsSearch represents the model behind the search form about `app\models\Cards`.
 */
class CardsSearch extends Cards
{
    public function rules()
    {
        return [
            [['id', 'series', 'card_num', 'status'], 'integer'],
            [['date_release', 'date_end_activity', 'creator.username'], 'safe'],
            [['sum'], 'number'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['creator.username']);
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Cards::find();

        $query->joinWith(['creator' => function($query) { $query->from(['creator' => 'user']); }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'series',
                    'card_num',
                    'date_release',
                    'date_end_activity',
                    'sum',
                    'status',
                ],
            ],
        ]);

        $dataProvider->sort->attributes['creator.username'] = [
            'asc' => ['creator.username' => SORT_ASC],
            'desc' => ['creator.username' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'series' => $this->series,
            'card_num' => $this->card_num,
            'sum' => $this->sum,
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['LIKE', 'creator.username', $this->getAttribute('creator.username')])
            ->andFilterWhere(['LIKE', 'date_release', $this->date_release])
            ->andFilterWhere(['LIKE', 'date_end_activity', $this->date_end_activity]);

        //var_dump($query); exit;

        return $dataProvider;
    }
}
