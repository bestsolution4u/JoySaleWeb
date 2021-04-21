<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Currencies;

/**
 * CurrenciesSearch represents the model behind the search form of `common\models\Currencies`.
 */
class CurrenciesSearch extends Currencies
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['currency_name', 'currency_shortcode', 'currency_image', 'currency_symbol','currency_mode','currency_position'], 'safe'],
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
        $query = Currencies::find();

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
        ]);

        $query->andFilterWhere(['like', 'currency_name', $this->currency_name])
            ->andFilterWhere(['like', 'currency_shortcode', $this->currency_shortcode])
            ->andFilterWhere(['like', 'currency_image', $this->currency_image])
            ->andFilterWhere(['like', 'currency_symbol', $this->currency_symbol]);

        return $dataProvider;
    }
}
