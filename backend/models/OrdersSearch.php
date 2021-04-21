<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders;


class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'userId', 'sellerId', 'orderDate', 'shippingAddress', 'statusDate', 'reviewFlag'], 'integer'],
            [['totalCost', 'totalShipping', 'admincommission', 'discount', 'discountSource', 'currency', 'sellerPaypalId', 'status', 'trackPayment'], 'safe'],
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

        $query = Orders::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['orderId'=>SORT_DESC]]
        ]);

        $this->load($params);

       // print_r($this->orderId);exit;

        if (isset($params['orderDate'])) {
         //  $stt=Yii::$app->formatter->asDateTime($params['createdDate']);
          //print_r($stt); exit;
            $dat=$params['orderDate'];
        }
        else
        {
           //  print_r($this->createdDate);exit;
            $dat=$this->orderDate;
        }

        if (isset($params['status'])) {
       
            $status=$params['status'];
            $status1='';
            if ($status == 'approved') {
               $status='paid';
            }
            elseif ($status == 'refunded') {
               $status='cancelled';
               $status1='refunded';
            }
             elseif ($status == 'cancelled') {
               $status='cancelled';
               $status1='pending';
            }
            else
            {
               
            }
           // print_r($status);exit;
        }
        else
        {
           //  print_r($this->createdDate);exit;
            $status=$this->status;
             $status1=$this->trackPayment;
        }




        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
         
        }

       // print_r($status);exit;
        // grid filtering conditions
        $query->andFilterWhere([
            'orderId' => $this->orderId,
            'userId' => $this->userId,
            'sellerId' => $this->sellerId,
            //'orderDate' => $dat,
            'shippingAddress' => $this->shippingAddress,
            'statusDate' => $this->statusDate,
            'reviewFlag' => $this->reviewFlag,
        ]);

        $query->andFilterWhere(['like', 'totalCost', $this->totalCost])
            ->andFilterWhere(['like', 'totalShipping', $this->totalShipping])
            ->andFilterWhere(['like', 'admincommission', $this->admincommission])
            ->andFilterWhere(['like', 'discount', $this->discount])
            ->andFilterWhere(['like', 'discountSource', $this->discountSource])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'sellerPaypalId', $this->sellerPaypalId])
            ->andFilterWhere(['like','status', $status])
            ->andFilterWhere(['like', 'trackPayment', $status1])
            ->andFilterWhere(['=', "date_format(FROM_UNIXTIME(`orderDate`), '%d-%m-%Y')", $dat]);


        return $dataProvider;
    }
}
