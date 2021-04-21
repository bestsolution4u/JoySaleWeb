<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Categories;

/**
 * CategoriesSearch represents the model behind the search form of `common\models\Categories`.
 */
class CategoriesSearch extends Categories
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryId', 'parentCategory', 'subcategoryVisible'], 'integer'],
            [['name', 'image', 'categoryProperty', 'slug', 'createdDate'], 'safe'],
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
        $query = Categories::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	     'sort'=> ['defaultOrder' => ['categoryId'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

         if (isset($params['createdDate'])) {
           //$stt=Yii::$app->formatter->asDateTime($params['createdDate']);
         // print_r($params['createdDate']); exit;
            $dat=$params['createdDate'];
        }
        else
        {
           //  print_r($this->createdDate);exit;
            $dat=$this->createdDate;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'categoryId' => $this->categoryId,
            'parentCategory' => $this->parentCategory,
            'subcategoryVisible' => $this->subcategoryVisible,
            //'createdDate' => $this->createdDate,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'categoryProperty', $this->categoryProperty])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['=', "DATE_FORMAT(`createdDate`, '%d-%m-%Y' )", $dat]);

        return $dataProvider;
    }
}
