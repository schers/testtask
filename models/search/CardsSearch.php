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
            [['date_release', 'date_end_activity'], 'safe'],
            [['sum'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Cards::find();

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

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'series' => $this->series,
            'card_num' => $this->card_num,
            'date_release' => $this->date_release,
            'date_end_activity' => $this->date_end_activity,
            'sum' => $this->sum,
            'status' => $this->status,
            'creator.username' => $this->creator->username,
        ]);

        return $dataProvider;
    }
}
