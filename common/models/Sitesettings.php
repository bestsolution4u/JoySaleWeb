<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_sitesettings".
 *
 * @property int $id
 * @property string $smtpEmail
 * @property string $smtpPassword
 * @property string $smtpPort
 * @property string $smtpHost
 * @property int $smtpEnable
 * @property int $smtpSSL
 * @property string $signup_active
 * @property string $givingaway
 * @property string $socialLoginDetails
 * @property string $logo
 * @property string $logoDarkVersion
 * @property string $sitename
 * @property string $metaData
 * @property string $default_userimage
 * @property string $default_productimage
 * @property string $favicon
 * @property string $currency_priority
 * @property string $category_priority
 * @property string $promotionCurrency
 * @property int $urgentPrice
 * @property int $searchDistance
 * @property string $searchType
 * @property string $searchList
 * @property string $sitepaymentmodes
 * @property int $commission_status
 * @property string $paypal_settings
 * @property string $braintree_settings
 * @property string $braintree_merchant_ids
 * @property string $api_settings
 * @property string $footer_settings
 * @property string $tracking_code
 * @property string $googleapikey
 * @property string $staticMapApiKey
 * @property string $account_sid
 * @property string $auth_token
 * @property string $sms_number
 * @property string $fb_appid
 * @property string $fb_secret
 * @property int $facebookshare
 * @property int $bannerstatus
 * @property int $promotionStatus
 * @property int $product_autoapprove
 * @property string $androidkey
 * @property int $bannervideoStatus
 * @property string $bannervideo
 * @property string $bannervideoposter
 * @property string $bannerText
 * @property int $appbannerStatus
 * @property string $bannerCurrency
 * @property string $ad_title
 * @property string $ad_content
 * @property string $ad_image
 * @property int $ad_price
 * @property string $ad_limit
 * @property int $paidbannerstatus
 */
class Sitesettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

        public $facebookstatus;
    public $facebookappid;
    public $facebooksecret;
    public $twitterstatus;
    public $twitterappid;
    public $twittersecret;
    public $googlestatus;
    public $googleappid;
    public $googlesecret;
    public $defaultlogo;
    public $defaultuser;
    public $defaultproduct;
    public $paypalType;
    public $paypalEmailId;
    public $paypalAppId;
    public $paypalApiUserId;
    public $paypalApiPassword;
    public $paypalApiSignature;
    public $paypalCcStatus;
    public $paypalCcClientId;
    public $paypalCcSecret;
    public $apiUsername;
    public $apiPassword;
    public $facebookFooterLink;
    public $googleFooterLink;
    public $twitterFooterLink;
    public $androidFooterLink;
    public $iosFooterLink;
    public $socialloginheading;
    public $applinkheading;
    public $generaltextguest;
    public $generaltextuser;
    public $footerCopyRightsDetails;
    public $exchangePaymentMode;
    public $buynowPaymentMode;
    public $scrowPaymentMode;
    public $cancelEnableStatus;
    public $bannerPaymenttype;
    public $sellerClimbEnableDays;
    public $brainTreeType;
    public $brainTreeMerchantId;
    public $brainTreePublicKey;
    public $brainTreePrivateKey;
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords; 
    public $stripeType;
    public $stripePublicKey;
    public $stripePrivateKey;
    public $adcontent;
    public $adlang;
    
    public static function tableName()
    {
        return 'hts_sitesettings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'smtpEmail', 'smtpPassword', 'smtpPort', 'smtpHost', 'smtpEnable', 'smtpSSL', 'socialLoginDetails', 'sitename', 'metaData', 'currency_priority', 'category_priority', 'promotionCurrency', 'bannerCurrency', 'urgentPrice', 'searchDistance', 'searchType', 'searchList','sitepaymentmodes', 'commission_status', 'paypal_settings', 'braintree_settings','stripe_settings', 'braintree_merchant_ids', 'api_settings', 'footer_settings', 'tracking_code', 'googleapikey', 'staticMapApiKey', 'account_sid', 'auth_token', 'sms_number', 'fb_appid', 'fb_secret', 'facebookshare', 'bannerstatus', 'promotionStatus', 'product_autoapprove', 'androidkey', 'bannervideoStatus','appbannerStatus','ad_price','ad_limit','paidbannerstatus','site_maintenance_mode','maintenance_text'], 'required'],

            ['pricerange', 'required', 'message' => 'Price range cannot be blank.'],
            [['pricerange'],'number','tooSmall' => 'Price range must be 1 or greater 1.','min'=>1, 'message' => '{attribute} must be 1 or greater 1'],
            [['id', 'smtpEnable', 'smtpSSL', 'urgentPrice', 'searchDistance', 'commission_status', 'facebookshare', 'bannerstatus', 'promotionStatus', 'product_autoapprove', 'bannervideoStatus'], 'integer'],
            [['signup_active', 'givingaway', 'socialLoginDetails', 'metaData', 'currency_priority', 'category_priority', 'promotionCurrency','bannerCurrency', 'searchType', 'paypal_settings', 'braintree_merchant_ids', 'api_settings', 'footer_settings', 'tracking_code','site_maintenance_mode','maintenance_text'], 'string'],
            //[['cancelEnableStatus', 'sellerClimbEnableDays', 'buynowRequired'],  'on'=>'paymentmodes'],
            [['brainTreeType','brainTreeMerchantId', 'brainTreePublicKey', 'brainTreePrivateKey'], 'required', 'on'=>'braintreesettings'],
            [['facebookstatus', 'facebookappid', 'facebooksecret'], 'required', 'on'=>'sociallogin'],
            [['twitterstatus', 'twitterappid', 'twittersecret'], 'required', 'on'=>'sociallogin'],
            [['googlestatus', 'googleappid', 'googlesecret'], 'required', 'on'=>'sociallogin'],
            [['smtpSSL', 'smtpEmail', 'smtpPassword', 'smtpEnable', 'smtpPort', 'smtpHost'], 'required', 'on'=>'smtp'],
            [['sitename', 'metaTitle', 'metaDescription', 'metaKeywords', 'googleapikey', 'staticMapApiKey', 'signup_active','givingaway'],'required','on'=>'defaultsettings'],
            [['logo,default_userimage','default_productimage','watermark'],'file', 'extensions' => ['png', 'jpg', 'gif', 'bmp', 'jpeg'],'on' => 'defaultsettings'],
          //  [['fb_appid', 'fb_secret'], 'required', 'on'=>'sms'],
            [['smtpPassword', 'sms_number'], 'string', 'max' => 50],
            [['smtpPort'], 'string', 'max' => 10],
            [['logo', 'logoDarkVersion', 'default_userimage','default_productimage' ,'watermark'], 'string', 'max' => 60],
            [['sitename', 'fb_appid', 'fb_secret'], 'string', 'max' => 40],
           // [['favicon'], 'string', 'max' => 15],
            [['searchList'], 'integer', 'min' =>15],
            [['sitepaymentmodes', 'braintree_settings','paidbannerstatus','site_maintenance_mode','maintenance_text'], 'string', 'max' => 250],
            [['googleapikey', 'staticMapApiKey', 'account_sid', 'auth_token'], 'string', 'max' => 100],
            [['androidkey'], 'string', 'max' => 255],
          //  [['ad_title','ad_content'],'string'],
          //  [['searchDistance'], 'integer', 'max' => 13],
             [['ad_content', 'ad_title','ad_price','ad_limit'],'required','on'=>'ads'],
             [['ad_image'],'file', 'extensions' => ['png', 'jpg', 'gif', 'bmp', 'jpeg'],'on' => 'ads'],
             [['paidbannerstatus'], 'integer'],
             [['ad_limit'],'string'],
             [['ad_price'],'string'],
             
            

           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'smtpEmail' => 'Smtp Email',
            'smtpPassword' => 'Smtp Password',
            'smtpPort' => 'Smtp Port',
            'smtpHost' => 'Smtp Host',
            'smtpEnable' => 'Smtp Enable',
            'smtpSSL' => 'Smtp Ssl',
            'signup_active' => 'Signup Active',
            'givingaway' => 'Givingaway',
            'socialLoginDetails' => 'Social Login Details',
            'logo' => 'Logo',
            'logoDarkVersion' => 'Logo Dark Version',
            'watermark' => 'Watermark',
            'sitename' => 'Sitename',
            'metaData' => 'Meta Data',
            'default_userimage' => 'Default Userimage',
            'default_productimage' => 'Default Productimage',
            'favicon' => 'Favicon',
            'currency_priority' => 'Currency Priority',
            'category_priority' => 'Category Priority',
            'promotionCurrency' => 'Promotion Currency',
            'bannerCurrency'=>'Banner Currency',
            'urgentPrice' => 'Urgent Price',
            'searchDistance' => 'Search Distance',
            'searchType' => 'Search Type',
            'searchList' => 'Search List',
            'sitepaymentmodes' => 'Sitepaymentmodes',
            'commission_status' => 'Commission Status',
            'paypal_settings' => 'Paypal Settings',
            'braintree_settings' => 'Braintree Settings',
            'stripe_settings' => 'Stripe Settings',
            'braintree_merchant_ids' => 'Braintree Merchant Ids',
            'api_settings' => 'Api Settings',
            'footer_settings' => 'Footer Settings',
            'tracking_code' => 'Tracking Code',
            'googleapikey' => 'Googleapikey',
            'staticMapApiKey' => 'Static Map Api Key',
            'account_sid' => 'Account Sid',
            'auth_token' => 'Auth Token',
            'sms_number' => 'Sms Number',
            'fb_appid' => 'Fb Appid',
            'fb_secret' => 'Fb Secret',
            'facebookshare' => 'Facebookshare',
            'bannerstatus' => 'Bannerstatus',
            'promotionStatus' => 'Promotion Status',
            'product_autoapprove' => 'Product Autoapprove',
            'androidkey' => 'Androidkey',
            'bannervideoStatus' => 'Bannervideo Status',
            'bannervideo' => 'Bannervideo',
            'bannervideoposter' => 'Bannervideoposter',
            'bannerText' => 'Banner Text',
            'ad_title'=>'Advertisement Title',
            'ad_content'=>'Advertisement Content',
            'ad_image'=>'Advertisement Image',
            'ad_price'=>'Advertisement Price',
            'ad_limit'=>'Advertisement Limit',
            'pricerange'=>'Price range',
        ];
    }
    

    public function buynowRequired($attribute){
		if($this->buynowPaymentMode == 1){
			if($this->cancelEnableStatus == ""){
				$this->addError('cancelEnableStatus', 'Select a Status upto which the Cancel is available');
			}
			if($this->sellerClimbEnableDays == ""){
				$this->addError('sellerClimbEnableDays', 'Enter no. of days after when the Claim button should be enable for Seller');
			}
		}
    }
    
    //
    public function facebookRequired($attribute)
	{
		if($this->facebookstatus == 1){
			if($this->facebookappid == ''){
				$this->addError('facebookappid', 'Facebook Appid cannot be empty');
			}/* else{
			$this->clearErrors('facebookappid');
			} */
			if($this->facebooksecret == ''){
				$this->addError('facebooksecret', 'Facebook Secret Key cannot be empty');
			}
		}
    }
    
    public function paypalCcRequired($attribute)
	{
		if($this->paypalCcStatus == 1){
			if($this->paypalCcClientId == ''){
				$this->addError('paypalCcClientId', 'Paypal App Client Id cannot be empty');
			}/* else{
			$this->clearErrors('facebookappid');
			} */
			if($this->paypalCcSecret == ''){
				$this->addError('paypalCcSecret', 'Paypal App Secret Key cannot be empty');
			}
		}
    }
    
    public function twitterRequired($attribute)
	{
		if($this->twitterstatus == 1){
			if($this->twitterappid == ''){
				$this->addError('twitterappid', 'Twitter Appid cannot be empty');
			}
			if($this->twittersecret == ''){
				$this->addError('twittersecret', 'Twitter Secret Key cannot be empty');
			}
		}
	}

    public function googleRequired($attribute)
	{
		if($this->googlestatus == 1){
			if($this->googleappid == ''){
				$this->addError('googleappid','Google Appid cannot be empty');
			}
			if($this->googlesecret == ''){
				$this->addError('googlesecret', 'Google Secret Key cannot be empty');
			}
		}
	}
}
