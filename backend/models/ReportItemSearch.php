<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Reportproducts;

/**
 * ReportItemSearch represents the model behind the search form of `common\models\Reportproducts`.
 */
class ReportItemSearch extends Reportproducts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'userId', 'category', 'subCategory', 'quantity', 'createdDate', 'likeCount', 'commentCount', 'chatAndBuy', 'exchangeToBuy', 'instantBuy', 'myoffer', 'shippingcountry', 'soldItem', 'likes', 'views', 'reportCount', 'approvedStatus', 'Initial_approve'], 'integer'],
            [['name', 'description', 'currency', 'sizeOptions', 'productCondition', 'paypalid', 'shippingTime', 'location', 'reports', 'promotionType'], 'safe'],
            [['price', 'shippingCost', 'latitude', 'longitude'], 'number'],
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
        $query = Reportproducts::find();

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
            'productId' => $this->productId,
            'userId' => $this->userId,
            'category' => $this->category,
            'subCategory' => $this->subCategory,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'createdDate' => $this->createdDate,
            'likeCount' => $this->likeCount,
            'commentCount' => $this->commentCount,
            'chatAndBuy' => $this->chatAndBuy,
            'exchangeToBuy' => $this->exchangeToBuy,
            'instantBuy' => $this->instantBuy,
            'myoffer' => $this->myoffer,
            'shippingcountry' => $this->shippingcountry,
            'shippingCost' => $this->shippingCost,
            'soldItem' => $this->soldItem,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'likes' => $this->likes,
            'views' => $this->views,
            'reportCount' => $this->reportCount,
            'approvedStatus' => $this->approvedStatus,
            'Initial_approve' => $this->Initial_approve,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'sizeOptions', $this->sizeOptions])
            ->andFilterWhere(['like', 'productCondition', $this->productCondition])
            ->andFilterWhere(['like', 'paypalid', $this->paypalid])
            ->andFilterWhere(['like', 'shippingTime', $this->shippingTime])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'reports', $this->reports])
            ->andFilterWhere(['like', 'createdDate:date', $this->createdDate]);

        return $dataProvider;
    }
}
