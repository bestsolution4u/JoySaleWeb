<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_trackingdetails".
 *
 * @property int $id
 * @property int $orderid
 * @property string $status
 * @property int $merchantid
 * @property string $buyername
 * @property string $buyeraddress
 * @property int $shippingdate
 * @property string $couriername
 * @property string $courierservice
 * @property string $trackingid
 * @property string $notes
 */
class Trackingdetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_trackingdetails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderid', 'status', 'merchantid', 'buyername', 'buyeraddress', 'shippingdate', 'couriername', 'trackingid'], 'required'],
            [['orderid', 'merchantid', 'shippingdate'], 'integer'],
            [['buyeraddress', 'notes'], 'string'],
            [['status'], 'string', 'max' => 150],
            [['buyername', 'couriername', 'courierservice', 'trackingid'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderid' => 'Orderid',
            'status' => 'Status',
            'merchantid' => 'Merchantid',
            'buyername' => 'Buyername',
            'buyeraddress' => 'Buyeraddress',
            'shippingdate' => 'Shippingdate',
            'couriername' => 'Couriername',
            'courierservice' => 'Courierservice',
            'trackingid' => 'Trackingid',
            'notes' => 'Notes',
        ];
    }
}
