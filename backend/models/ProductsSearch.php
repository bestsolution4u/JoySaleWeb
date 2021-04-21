<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Products;
use common\models\Users;

/**
 * ProductsSearch represents the model behind the search form of `common\models\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'category', 'subCategory', 'quantity', 'createdDate', 'likeCount', 'commentCount', 'chatAndBuy', 'exchangeToBuy', 'instantBuy', 'myoffer', 'shippingcountry', 'soldItem', 'likes', 'views', 'reportCount', 'approvedStatus', 'Initial_approve'], 'integer'],
            [['name', 'description', 'currency', 'sizeOptions', 'productCondition', 'paypalid', 'shippingTime', 'location', 'reports', 'promotionType'], 'safe'],
            [['price', 'shippingCost', 'latitude', 'longitude'], 'number'],
             [['userId'], 'default'],

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
        $query = Products::find();

       // $users =  Users::find()->select('userId')->where(['LIKE', 'name', $this->name])->all();


        // add conditions that should always apply here
      //print_r($params);exit;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	    'sort'=> ['defaultOrder' => ['productId'=>SORT_DESC]]
        ]);
       
        if (isset($params['createdDate'])) {
           $stt=Yii::$app->formatter->asDateTime($params['createdDate']);
          //print_r($stt); exit;
            $dat=$params['createdDate'];
        }
        else
        {
           //  print_r($this->createdDate);exit;
            $dat=$this->createdDate;
        }


        $this->load($params);


           if (isset($params['ProductsSearch']['userId'])) {

            $users =  Users::find()->select('userId')->where(['LIKE', 'name', $params['ProductsSearch']['userId']])->all();

            if(count($users)>0 ){
             foreach ($users as $key => $value) {
                $userIds[] = $value->userId;
            }
               $query->andFilterWhere(['in','userId',$userIds]);
            }

           }
     


      //  echo "<pre>"; print_r($params['ProductsSearch']['userId']); echo "<pre>"; die;
        /*if (isset($this->price) && is_numeric($this->price)) {  

            if($this->price == (int)$this->price) {
                $priceCeil = ($this->price + 1) - 0.01;
                $priceFloor = $this->price;
                $query->andFilterWhere(['>=', 'price', $priceFloor]);
                $query->andFilterWhere(['<=', 'price', $priceCeil]);
            } else {
                $query->andFilterWhere(['price' => $this->price]); 
            }
        }*/

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;

        }
       
      //  $crate=Yii::$app->formatter->asDatetime($dat);
    //    print_r($params);
      //  print_r($params['createdDate']);exit;
        // grid filtering conditions
        $query->andFilterWhere([
            'productId' => $this->productId,
         //   'userId' => $this->userId,
            'category' => $this->category,
            'subCategory' => $this->subCategory,
            //'price' => $priceValue,
            'price' => $this->price, 
            'quantity' => $this->quantity,
            //'createdDate' => $dat,
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
            ->andFilterWhere(['like', 'promotionType', $this->promotionType])
            ->andFilterWhere(['=', "date_format(FROM_UNIXTIME(`createdDate`), '%d-%m-%Y' )", $dat]);

        return $dataProvider;
        
    }
}
