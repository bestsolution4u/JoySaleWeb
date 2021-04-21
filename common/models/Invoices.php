<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_invoices".
 *
 * @property int $invoiceId
 * @property int $orderId
 * @property string $invoiceNo
 * @property int $invoiceDate
 * @property string $invoiceStatus
 * @property string $paymentMethod
 * @property string $paymentTranxid
 *
 * @property HtsOrders $order
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_invoices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId'], 'required'],
            [['orderId', 'invoiceDate'], 'integer'],
            [['paymentTranxid'], 'string'],
            [['invoiceNo', 'invoiceStatus'], 'string', 'max' => 20],
            [['paymentMethod'], 'string', 'max' => 100],
            [['orderId'], 'exist', 'skipOnError' => true, 'targetClass' => HtsOrders::className(), 'targetAttribute' => ['orderId' => 'orderId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceId' => 'Invoice ID',
            'orderId' => 'Order ID',
            'invoiceNo' => 'Invoice No',
            'invoiceDate' => 'Invoice Date',
            'invoiceStatus' => 'Invoice Status',
            'paymentMethod' => 'Payment Method',
            'paymentTranxid' => 'Payment Tranxid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(HtsOrders::className(), ['orderId' => 'orderId']);
    }
}
