<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_adverister".
 *
 * @property int $id
 * @property int $userid
 * @property string $webbanner
 * @property string $appbanner
 * @property string $bannerlink
 * @property string $startdate
 * @property string $enddate
 * @property int $totaldays
 * @property int $amount
 * @property int $paidstatus
 * @property string $status
 * @property string $tranxId
 * @property string $createdDate
 * @property string $paymentMethod
 * @property string $currency
 * @property string $trackPayment
 */
class Adverister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_adverister';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'webbanner', 'appbanner', 'bannerlink', 'startdate', 'enddate', 'totaldays', 'amount', 'paidstatus', 'status','tranxId','createdDate','paymentMethod','currency','trackPayment','status'], 'required'],
            [['userid', 'totaldays', 'amount', 'paidstatus'], 'integer'],
            [['bannerlink', 'startdate', 'enddate'], 'safe'],
            [['webbanner', 'appbanner'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'webbanner' => 'Webbanner',
            'appbanner' => 'Appbanner',
            'bannerlink' => 'Bannerlink',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'totaldays' => 'Totaldays',
            'amount' => 'Amount',
            'paidstatus' => 'Paidstatus',
            'status' => 'Status',
        ];
    }
}
