<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_exchanges".
 *
 * @property int $id
 * @property int $mainProductId
 * @property int $exchangeProductId
 * @property int $status
 * @property int $date
 * @property string $slug
 * @property int $blockExchange
 * @property string $exchangeHistory
 * @property int $reviewFlagSender
 * @property int $reviewFlagReceiver
 *
 * @property HtsProducts $mainProduct
 * @property HtsProducts $exchangeProduct
 */
class Exchanges extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_exchanges';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requestFrom', 'requestTo', 'mainProductId', 'exchangeProductId', 'status', 'date', 'slug', 'exchangeHistory', 'reviewFlagSender', 'reviewFlagReceiver'], 'required'],
            [['requestFrom', 'requestTo', 'mainProductId', 'exchangeProductId', 'status', 'date', 'blockExchange', 'reviewFlagSender', 'reviewFlagReceiver'], 'integer'],
            [['exchangeHistory'], 'string'],
            [['slug'], 'string', 'max' => 8],
            [['mainProductId'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['mainProductId' => 'productId']],
            [['exchangeProductId'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['exchangeProductId' => 'productId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'requestFrom' => 'Request From',
            'requestTo' => 'Request To',
            'mainProductId' => 'Main Product ID',
            'exchangeProductId' => 'Exchange Product ID',
            'status' => 'Status',
            'date' => 'Date',
            'slug' => 'Slug',
            'blockExchange' => 'Block Exchange',
            'exchangeHistory' => 'Exchange History',
            'reviewFlagSender' => 'Review Flag Sender',
            'reviewFlagReceiver' => 'Review Flag Receiver',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainProduct()
    {
        return $this->hasOne(Products::className(), ['productId' => 'mainProductId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExchangeProduct()
    {
        return $this->hasOne(Products::className(), ['productId' => 'exchangeProductId']);
    }

    public function sendExchangeProductEmail($email,$seller,$receiver){               $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
            return Yii::$app            
            ->mailer            
            ->compose(                
                ['html' => 'exchangecreated-html', 'text' => 'exchangecreated-text'],                
                ['c_username' => $receiver,                 'r_username' => $seller, 'siteSettings' => $siteSettings]            
                )            
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)            ->setTo($email)            
            ->setSubject($seller.' sent exchange request to your product')            ->send();    }
}
