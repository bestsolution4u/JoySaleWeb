<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Invoices;

/**
 * InvoicesSearch represents the model behind the search form of `common\models\Invoices`.
 */
class InvoicesSearch extends Invoices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoiceId', 'orderId', 'invoiceDate'], 'integer'],
            [['invoiceNo', 'invoiceStatus', 'paymentMethod', 'paymentTranxid'], 'safe'],
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
        $query = Invoices::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort'=> ['defaultOrder' => ['invoiceId'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

          if (isset($params['invoiceDate'])) {
           
            $dat=$params['invoiceDate'];
        }
        else
        {
           //  print_r($this->createdDate);exit;
            $dat=$this->invoiceDate;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'invoiceId' => $this->invoiceId,
            'orderId' => $this->orderId,
            'invoiceDate' => $this->invoiceDate,
        ]);

        $query->andFilterWhere(['like', 'invoiceNo', $this->invoiceNo])
            ->andFilterWhere(['like', 'invoiceStatus', $this->invoiceStatus])
            ->andFilterWhere(['like', 'paymentMethod', $this->paymentMethod])
            ->andFilterWhere(['like', 'paymentTranxid', $this->paymentTranxid])
             ->andFilterWhere(['=', "date_format(FROM_UNIXTIME(`invoiceDate`), '%d-%m-%Y' )", $dat]);
            //$query->orderBy(['invoiceId' => SORT_DESC]);
        return $dataProvider;
    }
}
