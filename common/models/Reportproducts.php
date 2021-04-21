<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_products".
 *
 * @property int $productId
 * @property int $userId
 * @property string $name
 * @property string $description
 * @property int $category
 * @property int $subCategory
 * @property double $price
 * @property string $currency
 * @property int $quantity
 * @property string $sizeOptions
 * @property string $productCondition
 * @property int $likeCount
 * @property int $commentCount
 * @property int $chatAndBuy
 * @property int $exchangeToBuy
 * @property int $instantBuy
 * @property int $myoffer
 * @property string $paypalid
 * @property string $shippingTime
 * @property int $shippingcountry
 * @property double $shippingCost
 * @property int $soldItem
 * @property string $location
 * @property double $latitude
 * @property double $longitude
 * @property int $likes
 * @property int $views
 * @property string $reports
 * @property int $reportCount
 * @property string $promotionType
 * @property int $approvedStatus
 * @property int $Initial_approve
 */
class Reportproducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'price', 'currency', 'chatAndBuy', 'exchangeToBuy', 'instantBuy', 'myoffer', 'paypalid', 'shippingTime', 'shippingcountry', 'shippingCost', 'soldItem', 'location', 'latitude', 'longitude', 'likes', 'views', 'reports', 'reportCount', 'approvedStatus', 'Initial_approve'], 'required'],
            [['userId', 'category', 'subCategory', 'quantity', 'likeCount', 'commentCount', 'chatAndBuy', 'exchangeToBuy', 'instantBuy', 'myoffer', 'shippingcountry', 'soldItem', 'likes', 'views', 'reportCount', 'approvedStatus', 'Initial_approve'], 'integer'],
            [['description', 'sizeOptions', 'promotionType'], 'string'],
            [['price', 'shippingCost', 'latitude', 'longitude'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['currency'], 'string', 'max' => 10],
            [['productCondition'], 'string', 'max' => 100],
            [['paypalid'], 'string', 'max' => 150],
            [['shippingTime'], 'string', 'max' => 60],
            [['location'], 'string', 'max' => 255],
            [['reports'], 'string', 'max' => 250],
          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productId' =>  Yii::t('app','Product ID'),
            'userId' => 'User ID',
            'name' =>  Yii::t('app','Name'),
            'description' => 'Description',
            'category' => 'Category',
            'subCategory' => 'Sub Category',
            'price' =>  Yii::t('app','Price'),
            'currency' => 'Currency',
            'quantity' => Yii::t('app','Quantity'),
            'sizeOptions' => Yii::t('app','Size Options'),
            'productCondition' => 'Product Condition',
            'createdDate' => Yii::t('app','Created Date'),
            'likeCount' => 'Like Count',
            'commentCount' => 'Comment Count',
            'chatAndBuy' => 'Chat And Buy',
            'exchangeToBuy' => 'Exchange To Buy',
            'instantBuy' => 'Instant Buy',
            'myoffer' => 'Myoffer',
            'paypalid' => Yii::t('app','Paypal ID'),
            'shippingTime' => 'Shipping Time',
            'shippingcountry' => 'Shippingcountry',
            'shippingCost' => 'Shipping Cost',
            'soldItem' => 'Sold Item',
            'location' => 'Location',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'likes' => Yii::t('app','Likes'),
            'views' => Yii::t('app','Views'),
            'reports' => 'Reports',
            'reportCount' => Yii::t('app','Report Count'),
            'promotionType' => 'Promotion Type',
            'approvedStatus' => 'Approved Status',
            'Initial_approve' => 'Initial Approve',
        ];
    }
}
