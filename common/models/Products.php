<?php

namespace common\models;

use Yii;


class Products extends \yii\db\ActiveRecord
{
    public $cnt;
    public static function tableName()
    {
        return 'hts_products';
    }

 
    public function rules()
    {
        return [

            [['userId', 'category', 'subCategory', 'quantity', 'createdDate', 'likeCount', 'commentCount', 'chatAndBuy', 'exchangeToBuy', 'instantBuy', 'myoffer', 'shippingcountry', 'soldItem', 'likes', 'views', 'reportCount', 'approvedStatus', 'Initial_approve'], 'integer'],
            [['description', 'sizeOptions', 'promotionType','filters'], 'string'],
            [['price', 'shippingCost', 'latitude', 'longitude'], 'number'],
            [['name'], 'string', 'max' => 70],
            [['currency'], 'string', 'max' => 10],
            [['productCondition'], 'string', 'max' => 100],
            [['city'], 'string', 'max' => 50],
            [['country'], 'string', 'max' => 50],
            [['paypalid'], 'string', 'max' => 150],
            [['shippingTime'], 'string', 'max' => 60],
            [['location'], 'string', 'max' => 255],
            [['reports'], 'string', 'max' => 250],
            [['insightUsers','videoUrl','filters','radioFilter','checkFilter','silderFilter'], 'string'],
            [['exchangeRequest','offerRequest'], 'integer'],
            [['category'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category' => 'categoryId']],
            [['subCategory'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['subCategory' => 'categoryId']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['userId' => 'userId']],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productId' => 'Product ID',
            'userId' => 'User ID',
            'name' => Yii::t('app','Name'),
            'description' => 'Description',
            'category' => 'Category',
            'subCategory' => 'Sub Category',
            'price' => Yii::t('app','Price'),
            'currency' => 'Currency',
            'quantity' => 'Quantity',
            'sizeOptions' =>Yii::t('app','Size Options'),
            'productCondition' => 'Product Condition',
            'createdDate' => Yii::t('app','Created Date'),
            'likeCount' => 'Like Count',
            'commentCount' => 'Comment Count',
            'chatAndBuy' => 'Chat And Buy',
            'exchangeToBuy' => Yii::t('app','Exchange To Buy'),
            'instantBuy' => 'Instant Buy',
            'myoffer' => 'Myoffer',
            'paypalid' => Yii::t('app','Paypal ID'),
            'shippingTime' => 'Shipping Time',
            'shippingcountry' => 'Shippingcountry',
            'shippingCost' => Yii::t('app','Shipping Cost'),
            'soldItem' => 'Sold Item',
            'location' => 'Location',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'likes' => 'Likes',
            'views' => 'Views',
            'reports' => 'Reports',
            'reportCount' => Yii::t('app','Report Count'),
            'promotionType' => 'Promotion Type',
            'approvedStatus' => 'Approved Status',
            'Initial_approve' => 'Initial Approve',
            'city' => 'City',
            'country' => 'Country',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getHtsCarts()
    // {
    //     return $this->hasMany(HtsCarts::className(), ['productId' => 'productId']);
    // }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['productId' => 'productId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExchanges()
    {
        return $this->hasMany(Exchanges::className(), ['mainProductId' => 'productId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExchanges0()
    {
        return $this->hasMany(Exchanges::className(), ['exchangeProductId' => 'productId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory0()
    {
        return $this->hasOne(Categories::className(), ['categoryId' => 'category']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCategory0()
    {
        return $this->hasOne(Categories::className(), ['categoryId' => 'subCategory']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSub_subCategory0()
    {
        return $this->hasOne(Categories::className(), ['categoryId' => 'sub_subCategory']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['userId' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShippings()
    {
        return $this->hasMany(Shipping::className(), ['productId' => 'productId']);
    }
     public function getPhotos()
    {
        return $this->hasMany(Photos::className(), ['productId' => 'productId']);
    }
      public function getShipping()
    {
        return $this->hasMany(Shipping::className(), ['productId' => 'productId']);
    }
    
    public function sendEmail($sellerEmail,$userModel,$productModel,$productModelName, $sellerName){
       
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'promotionsuccessintimation-html', 'text' => 'promotionsuccessintimation-text'],
                ['siteSettings' => $siteSettings,'userModel' => $userModel,
                'productModel'=>$productModel,'productName'=>$productModelName, 
                'sellerName' => $sellerName]
            )
             ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($sellerEmail)
            ->setSubject($siteSettings->sitename.' '.Yii::t('app','Offer Intimation Mail'))
            ->send();
    }


    public function sendExchangeProductMail($emailTo,$receivername, $sellername,
    $mailLayout,$mailSubject)
    {
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => $mailLayout.'-html', 'text' => $mailLayout.'-text'],
                ['siteSettings' => $siteSettings,'c_username' => $receivername, 'r_username' => $sellername]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($emailTo)
            ->setSubject($sellername.''. $mailSubject)
            ->send();
    }

	
    public function sendPromotionMail($sellerEmail,$userModel,$productModel,$name,$sellerName){
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'promotionsuccessintimation-html', 'text' => 'promotionsuccessintimation-text'],
                ['siteSettings' => $siteSettings,'userModel' => $userModel,'productModel'=>$productModel,'productName'=>$name, 'sellerName' => $sellerName]
            )
       

            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($sellerEmail)
            ->setSubject($siteSettings->sitename.Yii::t('app',' Welcome Mail'))
            ->send();
    }

    public function sendExchangeProductEmail($emailTo,$seller,$receiver){

       
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'exchangecreated-html', 'text' => 'exchangecreated-text'],
                ['c_username' => $seller, 'r_username' => $receiver,
                 'siteSettings' => $siteSettings]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($emailTo)
            ->setSubject($seller.Yii::t('app','sent Exchange Request with your product'))
            ->send();
    }
                           
}
