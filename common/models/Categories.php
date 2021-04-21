<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "hts_categories".
 *
 * @property int $categoryId
 * @property string $name
 * @property int $parentCategory
 * @property string $image
 * @property string $categoryProperty
 * @property int $subcategoryVisible 0-not visble,1- visible
 * @property string $slug
 * @property string $createdDate
 * @property string $filters
 */
class Categories extends \yii\db\ActiveRecord
{
   
      public $file;
    public $catImage;
    public $itemCondition;
    public $exchangetoBuy;
    public $buyNow;
    public $myOffer;
    public $contactSeller;

    public static function tableName()
    {
        return 'hts_categories';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parentCategory', 'subcategoryVisible'], 'integer'],
            [['categoryProperty','filters'], 'string'],
            [['createdDate'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 50],
            [['image'], 'string', 'max' => 60],
          //  ['name', 'unique', 'targetClass' => '\common\models\Categories', 'message' => 'Category name already exists.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categoryId' => 'Category',
            'name' => 'Name',
            'parentCategory' => 'Parent Category',
            'image' => 'Image',
            'categoryAttributes'=>'Category Attributes',
            'categoryProperty' => 'Category Property',
            'subcategoryVisible' => 'Subcategory Visible',
            'slug' => 'Slug',
            'createdDate' => 'Created Date',
            'itemCondition'=>Yii::t('app','Item Condition'),
            'exchangetoBuy'=>Yii::t('app','Exchange To Buy'),
            'buyNow'=>Yii::t('app','Buy Now'),
            'myOffer'=>Yii::t('app','My Offer'),
        ];
    }

        function getModDate()
    {
        if ($this->createdDate===null)
        return;

        return date("d-m-Y",strtotime($this->createdDate));
    }
    function getCatName() {
        if($this->parentCategory == 0) {
            return 'NIL';
        } else {
            $cat =  Categories::findone($this->parentCategory)->name;
            return $cat;
        }
    }
    function getCategoryproperty() {

     //   print_r($this->parentCategory);exit;

            $cat = Categories::findone($this->parentCategory)['categoryProperty'];
        
            return $cat;
    }
    //  public function search()
    // {
    //     // @todo Please modify the following code to remove attributes that should not be searched.

    //     $criteria=new CDbCriteria;

    //     $criteria->compare('categoryId',$this->categoryId);
    //     $criteria->compare('name',$this->name,true);
    //     $criteria->compare('parentCategory',$this->parentCategory);
    //     $criteria->compare("DATE_FORMAT(`createdDate`, '%d-%m-%Y')",$this->createdDate,true);
    //     /*if(!empty($this->createdDate))
    //      $criteria->condition = "DATE_FORMAT(`createdDate`, '%d-%m-%Y') = '$this->createdDate'";
    //      else
    //      $criteria->compare('createdDate',$this->createdDate); */
    //     //$criteria->order = 'categoryId DESC';
    //     return new CActiveDataProvider($this, array(
    //         'criteria'=>$criteria,
    //         'sort'=>array(
    //            'defaultOrder'=>'createdDate DESC',
    //     )
    //     ));
    // }
    //   public static function model($className=__CLASS__)
    // {
    //     return parent::model($className);
    // }

    //  public function beforeSave() {
    //     $this->slug =yii::$app->Myclass->productSlug($this->name);
    //     $this->slug = str_replace(' ', '',$this->slug);
    //     // print_r($this->slug);exit;
    //     if(is_null($this->parentCategory)) {
    //         $this->parentCategory = '0';
    //     }
    //     return parent::beforeSave(1);
    // }

}
