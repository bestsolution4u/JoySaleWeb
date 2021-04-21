<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Filter;

/**
 * FilterSearch represents the model behind the search form of `common\models\Filter`.
 */
class FilterSearch extends Filter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'categoryID', 'subcategoryID', 'isRequired', 'status'], 'integer'],
            [['name', 'type', 'value'], 'safe'],
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
        $query = Filter::find();

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
            'categoryID' => $this->categoryID,
            'subcategoryID' => $this->subcategoryID,
            'isRequired' => $this->isRequired,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
