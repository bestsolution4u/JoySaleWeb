<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_filter".
 *
 * @property int $id
 * @property int $categoryID
 * @property int $subcategoryID
 * @property string $name
 * @property string $type
 * @property string $value
 * @property int $isRequired
 * @property int $status
 */
class Productfilters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_productfilters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'category_id', 'subcategory_id', 'filter_id', 'filter_values'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'subcategory_id' => 'SubCategory ID',
            'category_id'=>'Category Id',
            'filter_id'=>'Filter Id',
            'filter_values'=>'Filter values',
            'name' => 'Name',
            'type' => 'Type',
            'inputtype' => 'Input Type',
            'value' => 'Value',
            'isRequired' => 'Is Required',
            'status' => 'Status',
        ];
    }
}
