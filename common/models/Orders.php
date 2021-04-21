<?php

namespace common\models;

use Yii;


class Orders extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'hts_orders';
    }

    public function rules()
    {
        return [
            [['userId', 'sellerId', 'admincommission', 'discount', 'discountSource', 'sellerPaypalId', 'statusDate', 'trackPayment', 'reviewFlag'], 'required'],
            [['userId', 'sellerId', 'orderDate', 'shippingAddress', 'statusDate', 'reviewFlag'], 'integer'],
            [['totalCost', 'totalShipping', 'admincommission'], 'string', 'max' => 18],
            [['discount'], 'string', 'max' => 15],
            [['discountSource'], 'string', 'max' => 50],
            [['currency'], 'string', 'max' => 3],
            [['sellerPaypalId'], 'string', 'max' => 150],
            [['status'], 'string', 'max' => 20],
            [['trackPayment'], 'string', 'max' => 100],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['userId' => 'userId']],
            [['sellerId'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['sellerId' => 'userId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orderId' => 'Order ID',
            'userId' => 'User ID',
            'sellerId' => 'Seller ID',
            'totalCost' => 'Total Cost',
            'totalShipping' => 'Total Shipping',
            'admincommission' => 'Admincommission',
            'discount' => 'Discount',
            'discountSource' => 'Discount Source',
            'orderDate' => 'Order Date',
            'shippingAddress' => 'Shipping Address',
            'currency' => 'Currency',
            'sellerPaypalId' => 'Seller Paypal ID',
            'status' => 'Status',
            'statusDate' => 'Status Date',
            'trackPayment' => 'Track Payment',
            'reviewFlag' => 'Review Flag',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   public function getInvoices()
    {
        return $this->hasMany(Invoices::className(), ['orderId' => 'orderId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderitems()
    {
        return $this->hasMany(Orderitems::className(), ['orderId' => 'orderId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['userId' => 'userId']);
    }

     public function getTrackingdetails()
    {
        return $this->hasOne(Trackingdetails::className(), ['orderid' => 'orderId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Users::className(), ['userId' => 'sellerId']);
    }
    function getCreatedDate()
    {
        if ($this->orderDate===null)
        return;

        return date("d-m-Y",$this->orderDate);
    }

    function getUserName()
    {
        if ($this->userId===null)
        return;

        return yii::$app->Myclass->getUserDetailss($this->userId)->username;
    }
    function getSellerName()
    {
        if ($this->sellerId===null)
        return;

        return yii::$app->Myclass->getUserDetailss($this->sellerId)->username;
    }
    function getTotalAmount() { 

        $total = (($this->totalCost) - ((!empty($this->discount) && $this->discount > 0)? $this->discount: 0)); 
        //return $total.' '.$this->currency;
        return $total; 
    }
      function getSellerAmount() {
        $sellerAmount = $this->getTotalAmount() - $this->getCommission();
        return round($sellerAmount,2).' '.$this->currency;
    }
     function getItemcost()
    {
   
    $itemcost=$this->totalCost-$this->totalShipping;
     

        return $itemcost;
    }
       function getCommission() {
       $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

      //  print_r($orderitems);exit;

         $orderitems = Orderitems::find()->where(['orderId' => $this->orderId])->one();
       // print_r($siteSettings);exit;
        $commissionStatus = $siteSettings->commission_status;



        if(!empty($this->discount)) {
            $finalPrice = ($orderitems->itemunitPrice) * ($orderitems->itemQuantity);
            $finalPrice = ($finalPrice - ($this->discount));
        } else {
            $finalPrice = $orderitems->itemunitPrice;
        }

       //print_r($finalPrice);exit;
        if($commissionStatus == 1) {
     
            $comissionModel = Commissions::find()->andWhere(['<=','minRate',$finalPrice]) ->andWhere(['>=','maxRate',$finalPrice])->andWhere(['=','status','1'])
           ->orderBy(['id' => SORT_DESC])->one();

        //      $commis = "SELECT * FROM `hts_commissions` where `minRate` <= '$finalPrice' AND `maxRate` >= '$finalPrice' AND `status` = '1' order by id DESC";
        // $comissionModel = Commissions::findBySql($commis)->one();

            

            if(!empty($comissionModel)) {
                $percentage = $comissionModel->percentage;
                if(empty($discount)) {
                    $adminCommission = ($finalPrice * ($percentage/100));
                    $adminCommission = $adminCommission * $orderitems->itemQuantity;
                } else {
                    $adminCommission = ($finalPrice * ($percentage/100));
                }
            } else {
                $adminCommission = 0;
            }
        } else {

            $adminCommission = 0;
        }

        //return $adminCommission.' '.$this->currency;
        return $adminCommission; 
    }


 function getCommissionOrder($orderId) {
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
 
       //  print_r($orderitems);exit;
 
          $orderitems = Orderitems::find()->where(['orderId' => $orderId])->one();
        // print_r($siteSettings);exit;
         $commissionStatus = $siteSettings->commission_status;
 
 
 
         if(!empty($this->discount)) {
             $finalPrice = ($orderitems->itemunitPrice) * ($orderitems->itemQuantity);
             $finalPrice = ($finalPrice - ($this->discount));
         } else {
             $finalPrice = $orderitems->itemunitPrice;
         }
 
        //print_r($finalPrice);exit;
         if($commissionStatus == 1) {
      
             $comissionModel = Commissions::find()->andWhere(['<=','minRate',$finalPrice]) ->andWhere(['>=','maxRate',$finalPrice])->andWhere(['=','status','1'])
            ->orderBy(['id' => SORT_DESC])->one();
 
         //      $commis = "SELECT * FROM `hts_commissions` where `minRate` <= '$finalPrice' AND `maxRate` >= '$finalPrice' AND `status` = '1' order by id DESC";
         // $comissionModel = Commissions::findBySql($commis)->one();
 
             
 
             if(!empty($comissionModel)) {
                 $percentage = $comissionModel->percentage;
                 if(empty($discount)) {
                     $adminCommission = ($finalPrice * ($percentage/100));
                     $adminCommission = $adminCommission * $orderitems->itemQuantity;
                 } else {
                     $adminCommission = ($finalPrice * ($percentage/100));
                 }
             } else {
                 $adminCommission = 0;
             }
         } else {
 
             $adminCommission = 0;
         }
 
         return $adminCommission.' '.$this->currency;
     }

   

    public function sendEmail($buyeremail,$subject,$siteSettings,$message,
    $shipping,$buyerModel,$orderid,$logusername){

       
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'shippingintimation-html', 'text' => 'shippingintimation-text'],
                ['siteSettings' => $siteSettings,'subject' => $subject,
                    'message' => $message,'tempShippingModel' => $shipping,'userModel' => $buyerModel,
                    'orderId' => $orderid,'sellerName' => $logusername]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($email)
            ->setSubject($siteSettings->sitename.' '.Yii::t('app','Shipping Confirmation Mail'))
            ->send();
    }

    public function sendOrderEmail($email,$name,$orderid){

        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'deliveredmail-html', 'text' => 'deliveredmail-text'],
                ['siteSettings' => $siteSettings,'name' => $name,
                    'orderId'=>$orderid]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($email)
            ->setSubject($siteSettings->sitename.' '.Yii::t('app','Delivered Mail'))
            ->send();
    }


    public function sendCancelEmail($email,$name,$orderid){
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'cancelledmail-html', 'text' => 'cancelledmail-text'],
                ['siteSettings' => $siteSettings,'name' => $name,
                'orderId'=>$orderid]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($email)
            ->setSubject($siteSettings->sitename.' '.Yii::t('app','Cancelled Mail'))
            ->send();
    }


    public function sendApprovedMail($email,$name,$order){
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'approvedmail-html', 'text' => 'approvedmail-text'],
                ['siteSettings' => $siteSettings,'name' => $name,
                'orderId'=>$order]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($email)
            ->setSubject($siteSettings->sitename.' '.Yii::t('app','Amount Paid Mail'))
            ->send();

    }
      public function sendRefundedMail($email,$name,$order){
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'refunded-html', 'text' => 'refunded-text'],
                ['siteSettings' => $siteSettings,'name' => $name,
                'orderId'=>$order]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($email)
            ->setSubject($siteSettings->sitename.' '.Yii::t('app','Amount Refund Mail'))
            ->send();

    }

}
