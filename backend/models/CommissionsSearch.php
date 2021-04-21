<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Commissions;

/**
 * CommissionsSearch represents the model behind the search form of `common\models\Commissions`.
 */
class CommissionsSearch extends Commissions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'date'], 'integer'],
            [['percentage', 'minRate', 'maxRate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Commissions::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'percentage', $this->percentage])
            ->andFilterWhere(['like', 'minRate', $this->minRate])
            ->andFilterWhere(['like', 'maxRate', $this->maxRate])
            ->orderBY(['date' => SORT_DESC]);

        return $dataProvider;
    }
}
