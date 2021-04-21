<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Adverister;


class AdveristerapprovedSearch extends Adverister
{
    
    public function rules()
    {
        return [
            [['id', 'userid', 'totaldays', 'totalCost', 'paidstatus', 'status'], 'integer'],
            [['webbanner', 'appbanner', 'bannerlink', 'startdate', 'enddate', 'tranxId', 'createdDate'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

 
    public function search($params)
    {
        $query = Adverister::find()->where(['status'=>"approved"]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
                return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'userid' => $this->userid,
            'bannerlink' => $this->bannerlink,
            'startdate' => $this->startdate,
            'enddate' => $this->enddate,
            'totaldays' => $this->totaldays,
            'totalCost' => $this->totalCost,
            'paidstatus' => $this->paidstatus,
          //  'status' => $this->status,
            'createdDate' => $this->createdDate,
        ]);

        $query->andFilterWhere(['like', 'webbanner', $this->webbanner])
            ->andFilterWhere(['like', 'appbanner', $this->appbanner])
            ->andFilterWhere(['like', 'tranxId', $this->tranxId]);

        return $dataProvider;
    }
}
