<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Banners;


class BannerapprovedSearch extends Banners
{
    public $showdate;
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['bannerimage', 'appbannerimage', 'bannerurl','startdate', 'enddate', 'totaldays', 'amount', 'paidstatus', 'status','tranxId','createdDate','paymentMethod','currency','trackPayment','status'], 'safe'],
        ];
    }

 
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

  
    public function search($params)
    {
        echo 'd'; die;
        $query = Banners::find()->where(['status'=>"approved"]);

       // echo  $dat= date("m-d-Y", strtotime($params['showdate']));


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



   /*    if (isset($params['showdate']) && $params['showdate']!="") {

          
        $dat= date("Y-m-d", strtotime($params['showdate']));
   
    }
    else
    {

        $dat= "";
    }*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
     /*   if(isset($params['createdDate']) || $params['createdDate']!="" || isset($params['enddate']) || $params['enddate']!=""){
              $query->andFilterWhere(['>=',"date_format(FROM_UNIXTIME(`createdDate`), '%Y-%m-%d')",$dat])
            ->andFilterWhere(['<=',"date_format(FROM_UNIXTIME(`createdDate`), '%Y-%m-%d')",$enddate]); 
        }
        else */
               // grid filtering conditions
     /*   $query->andFilterWhere([
         //   'id' => $this->id,
            'startdate' >=> $this->startdate,
            'enddate' <=> $this->enddate,
           // 'totaldays' => $this->totaldays,
            //'totalCost' => $this->totalCost,
            //'paidstatus' => $this->paidstatus,
            //'createdDate' => $this->createdDate,
        ]);*/

        $query->andFilterWhere(['>=', 'startdate', $this->startdate])
        //    ->andFilterWhere(['like', 'appbannerimage', $this->appbannerimage])
            ->andFilterWhere(['<=', 'enddate', $this->enddate]);

        return $dataProvider;

      
    }
}
