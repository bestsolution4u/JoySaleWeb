<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_orderitems".
 *
 * @property int $orderitemId
 * @property int $orderId
 * @property int $productId
 * @property string $itemName
 * @property string $itemPrice
 * @property string $itemSize
 * @property int $itemQuantity
 * @property string $itemunitPrice
 * @property string $shippingPrice
 *
 * @property HtsOrders $order
 */
class Orderitems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_orderitems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'productId'], 'required'],
            [['orderId', 'productId', 'itemQuantity'], 'integer'],
            [['itemName'], 'string', 'max' => 150],
            [['itemPrice', 'itemunitPrice', 'shippingPrice'], 'string', 'max' => 18],
            [['itemSize'], 'string', 'max' => 30],
            [['orderId'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['orderId' => 'orderId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orderitemId' => 'Orderitem ID',
            'orderId' => 'Order ID',
            'productId' => 'Product ID',
            'itemName' => 'Item Name',
            'itemPrice' => 'Item Price',
            'itemSize' => 'Item Size',
            'itemQuantity' => 'Item Quantity',
            'itemunitPrice' => 'Itemunit Price',
            'shippingPrice' => 'Shipping Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['orderId' => 'orderId']);
    }
     public function getProduct()
    {
        return $this->hasOne(Products::className(), ['productId' => 'productId']);
    }
}
