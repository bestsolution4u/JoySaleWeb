<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_shipping".
 *
 * @property int $shippingId
 * @property int $productId
 * @property int $countryId
 * @property string $shippingCost
 * @property string $createdDate
 */
class Shipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_shipping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'countryId'], 'integer'],
            [['shippingCost', 'createdDate'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shippingId' => 'Shipping ID',
            'productId' => 'Product ID',
            'countryId' => 'Country ID',
            'shippingCost' => 'Shipping Cost',
            'createdDate' => 'Created Date',
        ];
    }

    public function getProduct()
    {
        return $this->hasMany(Products::className(), ['productId' => 'productId']);
    }
}
