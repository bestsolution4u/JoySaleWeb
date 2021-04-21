<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Promotiontransaction;
use dosamigos\datepicker\DatePicker;
class PromotiontransactionSearch extends Promotiontransaction
{
    public $enddate;
    public function rules()
    {
        return [
           [['productId', 'userId', 'promotionPrice', 'promotionTime', 'createdDate', 'initial_check', 'approvedStatus'], 'integer'],
            [['status','promotionName'], 'string'],
            [['search'], 'safe'],
        ];
    }

    
    public function scenarios()
    {
       return Model::scenarios();
    }

    public function search($params)
    {
        $query = Promotiontransaction::find();

    //    echo  $dat= date("m-d-Y", strtotime($params['createdDate']));
    //    echo  $enddate= date("m-d-Y", strtotime($params['enddate']));


        if (isset($params['createdDate']) && $params['createdDate']!="") {

          
            $dat= date("Y-m-d", strtotime($params['createdDate']));
       
        }
        else
        {

            $dat= "";
        }

        if (isset($params['enddate']) && $params['enddate']!="") {
       
           // $enddate=$params['enddate'];
             $enddate= date("Y-m-d", strtotime($params['enddate']));
          
        }
        else
        {
            $enddate ="";
        }

         if (isset($params['type']) && $params['type']!="") {
       
           // $enddate=$params['enddate'];
             $type= $params['type'];

             if ($type == 'all') {
                 $type ="";
             }
          
        }
        else
        {
            $type ="";
        }
     

       $dataProvider = new ActiveDataProvider([
        'query' => $query,
      'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
         
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'productId' => $this->productId,
            'userId' => $this->userId,
        ]);

        $query->andFilterWhere(['like', 'search', $this->productId])
        ->andFilterWhere(['like', 'promotionName', $type])
              //->andFilterWhere(['between', "date_format(FROM_UNIXTIME(`createdDate`), '%m-%d-%Y')",$dat,$enddate]);
        ->andFilterWhere(['>=',"date_format(FROM_UNIXTIME(`createdDate`), '%Y-%m-%d')",$dat])
            ->andFilterWhere(['<=',"date_format(FROM_UNIXTIME(`createdDate`), '%Y-%m-%d')",$enddate]);


        return $dataProvider;
    }
}
