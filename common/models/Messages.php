<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_messages".
 *
 * @property int $messageId
 * @property int $chatId
 * @property int $senderId
 * @property int $messageContent 1 - normal, 2 - image, 3 - share location
 * @property string $message
 * @property int $sourceId
 * @property string $messageType
 * @property int $createdDate
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chatId', 'senderId', 'message', 'sourceId', 'messageType', 'createdDate'], 'required'],
            [['chatId', 'senderId', 'sourceId', 'createdDate'], 'integer'],
            [['message', 'messageType'], 'string'],
            [['messageContent'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'messageId' => 'Message ID',
            'chatId' => 'Chat ID',
            'senderId' => 'Sender ID',
            'messageContent' => 'Message Content',
            'message' => 'Message',
            'sourceId' => 'Source ID',
            'messageType' => 'Message Type',
            'createdDate' => 'Created Date',
        ];
    }

      public function sendEmail($sellerEmail,$name,$email,$phone,$offerRate, $message, $sellerName,
    $currency, $productURL){
       
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

             

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'myofferintimation-html', 'text' => 'myofferintimation-text'],
                ['siteSettings' => $siteSettings,'name' => $name, 'email' => $email,'phone' => $phone,
                    'offerRate' => $offerRate, 'message'=> $message, 'sellerName' => $sellerName,
                    'siteSettings' => $siteSettings,'currency' => $currency, 'productURL'=>$productURL]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($sellerEmail)
            ->setSubject($siteSettings->sitename.' Offer Intimation Mail')
            ->send();
    }
}
