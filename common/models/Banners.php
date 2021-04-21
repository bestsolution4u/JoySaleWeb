<?php

namespace common\models;

use Yii;


class Banners extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'hts_banners';
    }
  
    public function rules()
    {
        return [
            // [['bannerimage', 'appbannerimage', 'bannerurl'], 'required'],
            // [['bannerurl'], 'string'],
            // [['bannerimage', 'appbannerimage'], 'string', 'max' => 60],

            [['userid', 'bannerurl','appurl', 'startdate', 'enddate', 'totaldays', 'amount', 'paidstatus', 'status','tranxId','createdDate','paymentMethod','currency','trackPayment','status'], 'required'],
            [['userid', 'totaldays', 'amount', 'paidstatus'], 'integer'],
            [['bannerurl', 'startdate', 'enddate'], 'safe'],
             
            //[['bannerimage', 'appbannerimage'], 'file', 'skipOnEmpty' => false],
         
        ];
    }

  
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bannerimage' => Yii::t('app','Banner Image'),
            'appbannerimage' => Yii::t('app','App Banner Image'),
            'bannerurl' => Yii::t('app','Banner URL'),
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'totaldays' => 'Totaldays',
            'amount' => 'Amount',
            'paidstatus' => 'Paidstatus',
            'status' => 'Status',
            'appurl'=>'App URL'
        ];
    }
}