<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Adverister;

/**
 * AdveristerSearch represents the model behind the search form of `common\models\Adverister`.
 */
class AdveristerSearch extends Adverister
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'totaldays', 'totalCost', 'paidstatus', 'status'], 'integer'],
            [['webbanner', 'appbanner', 'bannerlink', 'startdate', 'enddate', 'tranxId', 'createdDate'], 'safe'],
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
        $query = Adverister::find()->where(['status'=>"pending"]);

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
