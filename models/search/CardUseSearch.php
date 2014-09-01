<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CardUse;

/**
 * CardUseSearch represents the model behind the search form about `app\models\CardUse`.
 */
class CardUseSearch extends CardUse
{
    public function rules()
    {
        return [
            [['id', 'card_id'], 'integer'],
            [['date_use', 'description'], 'safe'],
            [['cost'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CardUse::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date_use' => $this->date_use,
            'cost' => $this->cost,
            'card_id' => $this->card_id,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
