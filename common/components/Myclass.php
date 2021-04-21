<?php
namespace common\components;
use Yii;
use yii\base\Component;
use common\models\Sitesettings;
use common\models\Currencies;
use common\models\Country;
use common\models\Userviews;
use yii\helpers\Json;
use common\models\Users;
use common\models\Filter;
use common\models\Categories;
use common\models\Followers;
use common\models\Banners;
use yii\helpers\ArrayHelper;
use common\models\Reviews;
use yii\helpers\Url;
use common\models\Helppages;
use common\models\Products;
use common\models\Logs;
use common\models\Promotiontransaction;
use common\models\Exchanges;
use common\models\Messages;
use common\models\Invoices;
use common\models\Photos;
use common\models\Chats;
use common\models\Userdevices;
use common\models\Orderitems;
use common\models\Orders;
use common\models\Records;
class Myclass extends Component {
	public static function encrypt($string) {
		return substr(hash('sha256',$string),0,8);
	}
	public static function getLogo() {
		$id = 1;
		$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		return $setting->logo;
	}
	public static function getSitePaymentModes() {
		$id = 1;
		$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$paymentModes = "";
		if(!empty($setting->sitepaymentmodes))
			$paymentModes = Json::decode($setting->sitepaymentmodes, true);
		return $paymentModes;
	}
	public static function getLogoDarkVersion() {
		$id = 1;
		$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		return $setting->logoDarkVersion;
	}
	public static function getWatermark() {
		$id = 1;
		$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		return $setting->watermark;
	}
	public static function getDefaultUser() {
		$id = 1;
		$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		return $setting->default_userimage;
	}
	public static function getFooterLinks() {
		$footerLinksModel = Helppages::find()->all();
		//echo '<pre>'; print_r($footerLinksModel); exit;
		return $footerLinksModel;
	}
	public static function getOrderItemDetails($id) {
		$orderItem =  Orderitems::find()->where(['orderId' => $id])->one();
		if(!empty($orderItem))
			return $orderItem;
	}
	public static function getBanners() {
		$all_banners = Banners::find()->all();
		return $all_banners;
	}
	public static function getTermsSlug() {
		$footerLinksModel = Helppages::find()->where(['id'=>1])->one();
		return $footerLinksModel['slug'];
	}
	public static function getcurrentUserdetail() {
		$userId = Yii::$app->user->id;
		$userdetail = Users::find()->where(['userId' => $userId]);
		return $userdetail;
	}
	public static function getReviewcount($id) {
		$reviewcount =  Reviews::find()->where(['receiverId' => $id])->all();
		return count($reviewcount);
	}
	public static function filterCount($id){
		$filterCount = Categories::find()->where(['categoryId'=>$id])->one();
		$array[]=json::decode($filterCount->filters,true);
		return count($array[0]);
	}
	public static function getUserbyemail($email) {
		$userdetail = Users::find()->where(['email' => $email])->one();
		return $userdetail;
	}
	public static function getInvoiceDetails($id) {
		$invoiceItem =  Invoices::find()->where(['orderId' => $id])->one();
		if(!empty($invoiceItem))
			return $invoiceItem;
	}
	public static function getMetaData(){
		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$metaData = json_decode($siteSettings->metaData, true);
		if(!empty($metaData)){
			$metaContent['title'] = $metaData['metaTitle'];
			$metaContent['description'] = $metaData['metaDescription'];
			$metaContent['metaKeywords'] = $metaData['metaKeywords'];
		}
		$metaContent['sitename'] = $siteSettings->sitename;
		return $metaContent;
	}
	public static function getFooterSettings() {
		$id = 1;
		$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$details = array();
		if(!empty($setting->footer_settings)){
			$footerSettings = json_decode($setting->footer_settings, true);
			$footerSettings = $footerSettings['footerDetails'];
			$details['socialLinks'] = array();$details['appLinks'] = array();
			if(!empty($footerSettings['facebooklink'])){
				$details['socialLinks']['facebook'] = $footerSettings['facebooklink'];
			}
			if(!empty($footerSettings['googlelink'])){
				$details['socialLinks']['google'] = $footerSettings['googlelink'];
			}
			if(!empty($footerSettings['twitterlink'])){
				$details['socialLinks']['twitter'] = $footerSettings['twitterlink'];
			}
			if(!empty($footerSettings['androidlink'])){
				$details['appLinks']['android'] = $footerSettings['androidlink'];
			}
			if(!empty($footerSettings['ioslink'])){
				$details['appLinks']['ios'] = $footerSettings['ioslink'];
			}
			$details['footerCopyRightsDetails'] = $footerSettings['footerCopyRightsDetails'];
			$details['socialloginheading'] = $footerSettings['socialloginheading'];
			$details['applinkheading'] = $footerSettings['applinkheading'];
			$details['generaltextguest'] = $footerSettings['generaltextguest'];
			$details['generaltextuser'] = $footerSettings['generaltextuser'];
		}
		$details['analytics'] = $setting->tracking_code;
		return $details;
	}
///start new changes
	public static function SubCategoryCount($id){
		$subcategory = Categories::find()->where(['parentCategory'=>$id])->all();
		return count($subcategory);
	}
	public static function getProductcondition() {
		$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$productModes = "";
		if(!empty($setting->productCondition))
			$productModes = Json::decode($setting->productCondition, true);
		return $productModes;
	}
//revenue
	public static function getPromotionRevDaily($date) {	
		$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date' and `promotionName`='adds'";
		$promotions = Promotiontransaction::findBySql($sql)->all();	
		foreach($promotions as $promotion)	{
			$total = $total +   $promotion->promotionPrice;		
		}      
		$data=$total;	
		return $data;
	}
	public static function getPromotionRevMonthly($date) {	
		$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y-%m')='$date' and `promotionName`='adds'";
		$promotions = Promotiontransaction::findBySql($sql)->all();	
		foreach($promotions as $promotion)	{
			$total = $total +   $promotion->promotionPrice;		
		}      
		$data=$total;	
		return $data;
	}
	public static function getPromotionRevYearly($date) {	
		$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y')='$date' and `promotionName`='adds'";
		$promotions = Promotiontransaction::findBySql($sql)->all();	
		foreach($promotions as $promotion)	{
			$total = $total +   $promotion->promotionPrice;		
		}      
		$data=$total;	
		return $data;
	}
  //Urgent Promotion Graph
	public static function getPromotionUrgent($date) {	
		$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date' and `promotionName`='urgent'";
		$promotions = Promotiontransaction::findBySql($sql)->all();	
		foreach($promotions as $promotion)	{
			$total = $total +   $promotion->promotionPrice;		
		}      
		$data=$total;	
		return $data;
	}
	public static function getPromotionUrgentMonthly($date) {	
		$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y-%m')='$date' and `promotionName`='urgent'";
		$promotions = Promotiontransaction::findBySql($sql)->all();	
		foreach($promotions as $promotion)	{
			$total = $total +   $promotion->promotionPrice;		
		}      
		$data=$total;	
		return $data;
	}
	public static function getPromotionUrgentYearly($date) {	
		$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y')='$date' and `promotionName`='urgent'";
		$promotions = Promotiontransaction::findBySql($sql)->all();	
		foreach($promotions as $promotion)	{
			$total = $total +   $promotion->promotionPrice;		
		}      
		$data=$total;	
		return $data;
	}
///end
//Daily Income Chart records
// public static function getDailyRevenue($date) {
//     //buynow
// 	$order = new Orders();
// 	$sql = "SELECT * FROM `hts_orders` where from_unixtime(`orderDate`, '%d-%m-%Y')='$date' and (`status`='delivered' or `status`='paid')";
// 	$getRevenue = Orders::findBySql($sql)->all();
// 	foreach($getRevenue as $order) {
// 		 $getAmt = $order->getCommissionOrder($order->orderId);
// 		 $total = $total + $getAmt;
// 		}
// 		//promotion
//      $sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date'";
// 		$promotions = Promotiontransaction::findBySql($sql)->all();	
// 		  foreach($promotions as $promotion)	{
// 		  $Prototal = $Prototal +   $promotion->promotionPrice;		
// 	   }   
// 	   $totalAmt = $total + $Prototal;
// 	return $totalAmt;
// }
	public static function getDailyRevenue($date) {
//buynow
		$order = new Orders();
		$sql = "SELECT * FROM `hts_orders` where from_unixtime(`orderDate`, '%d-%m-%Y')='$date' and (`status`='delivered' or `status`='paid')";
		$getRevenue = Orders::findBySql($sql)->all();
		foreach($getRevenue as $order) {
			$getAmt = $order->getCommissionOrder($order->orderId);
			$total = $total + $getAmt;
		}
	//ad promotion
		$asql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date' AND `promotionName` = 'adds'";
		$adspromotion = Promotiontransaction::findBySql($asql)->all();	
		foreach($adspromotion as $promotion)	{
			$adtotal = $adtotal +   $promotion->promotionPrice;		
		}   
   //urgent promotion
		$usql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date' AND `promotionName` = 'urgent'";
		$urgpromotion = Promotiontransaction::findBySql($usql)->all();	
		foreach($urgpromotion as $promotion)	{
			$urtotal = $urtotal +   $promotion->promotionPrice;		
		}   
		if ($total == '') {
			$total = 0;
		}
		if ($adtotal == '') {
			$adtotal = 0;
		}
		if ($urtotal == '') {
			$urtotal = 0;
		}
		$final_total = $total+$adtotal+$urtotal;
		$siteSetting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$sitePaymentMode = json_decode($siteSetting->sitepaymentmodes);
		if($sitePaymentMode->buynowPaymentMode == 1) 
			return $urtotal.','.$adtotal.','.$total.','.$final_total;
		else
			return $urtotal.','.$adtotal.','.$final_total;
	}
	public static function setUserlanguage($userId){
		$user = Users::findOne($userId);
		$user->user_lang = $_SESSION['language'];
		$user->save(false);
	}
	public static function getBuynowDaily($date)
	{
		$order = new Orders();
		$sql = "SELECT * FROM `hts_orders` where from_unixtime(`orderDate`, '%d-%m-%Y')='$date' and (`status`='delivered' or `status`='paid')";
		$getRevenue = Orders::findBySql($sql)->all();
		foreach($getRevenue as $order) {
			$getAmt = $order->getCommissionOrder($order->orderId);
			$total = $total + $getAmt;
		}
		return $total;
	}
	public static function getMonthlyRevenue($date) {
//buynow
		$order = new Orders();
		$sql = "SELECT * FROM `hts_orders` where from_unixtime(`orderDate`, '%Y-%m')='$date' and (`status`='delivered' or `status`='paid')";
		$getRevenue = Orders::findBySql($sql)->all();
		foreach($getRevenue as $order) {
			$getAmt = $order->getCommissionOrder($order->orderId);
			$total = $total + $getAmt;
		}
	//promotion
		$adsql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y-%m')='$date' AND `promotionName` = 'adds'";
		$adpromotions = Promotiontransaction::findBySql($adsql)->all();	
		foreach($adpromotions as $promotion)	{
			$adtotal = $adtotal +   $promotion->promotionPrice;		
		}   
		$ursql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y-%m')='$date' AND `promotionName` = 'urgent'";
		$promotions = Promotiontransaction::findBySql($ursql)->all();	
		foreach($promotions as $promotion)	{
			$urtotal = $urtotal +   $promotion->promotionPrice;		
		}   
  // $totalAmt = $total + $Prototal;
		if ($total == '') {
			$total = 0;
		}
		if ($adtotal == '') {
			$adtotal = 0;
		}
		if ($urtotal == '') {
			$urtotal = 0;
		}
		$final_total = $total+$adtotal+$urtotal;
		$siteSetting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$sitePaymentMode = json_decode($siteSetting->sitepaymentmodes);
		if($sitePaymentMode->buynowPaymentMode == 1) 
			return $urtotal.','.$adtotal.','.$total.','.$final_total;
		else
			return $urtotal.','.$adtotal.','.$final_total;
	}
	public static function getYearlyRevenue($date) {
//buynow
		$order = new Orders();
		$sql = "SELECT * FROM `hts_orders` where from_unixtime(`orderDate`, '%Y')='$date' and (`status`='delivered' or `status`='paid')";
		$getRevenue = Orders::findBySql($sql)->all();
		foreach($getRevenue as $order) {
			$getAmt = $order->getCommissionOrder($order->orderId);
			$total = $total + $getAmt;
		}
	//promotion
		$adsql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y')='$date' AND `promotionName` = 'adds'";
		$adpromotions = Promotiontransaction::findBySql($adsql)->all();	
		foreach($adpromotions as $promotion)	{
			$adtotal = $adtotal +   $promotion->promotionPrice;		
		}   
		$ursql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y')='$date' AND `promotionName` = 'urgent'";
		$promotions = Promotiontransaction::findBySql($ursql)->all();	
		foreach($promotions as $promotion)	{
			$urtotal = $urtotal +   $promotion->promotionPrice;		
		}   
		if ($total == '') {
			$total = 0;
		}
		if ($adtotal == '') {
			$adtotal = 0;
		}
		if ($urtotal == '') {
			$urtotal = 0;
		}
		$final_total = $total+$adtotal+$urtotal;
		$siteSetting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$sitePaymentMode = json_decode($siteSetting->sitepaymentmodes);
		if($sitePaymentMode->buynowPaymentMode == 1) 
			return $urtotal.','.$adtotal.','.$total.','.$final_total;
		else
			return $urtotal.','.$adtotal.','.$final_total;
	}
//end
//revenu Graph
	public static function getrevenueTotal($date) {
		$order = new Orders();
		$sql = "SELECT * FROM `hts_orders` where from_unixtime(`statusDate`, '%d-%m-%Y')='$date' and (`status`='delivered' or `status`='paid')";
		$getRevenue = Orders::findBySql($sql)->all();
		foreach($getRevenue as $order) {
			$getAmt = $order->getCommissionOrder($order->orderId);
			$total = $total + $getAmt;
		}
		return $total;
	}
	public static function getItemsAddedMonthly($month) {
		$sql = "SELECT * FROM `hts_products` where from_unixtime(`createdDate`, '%Y-%m')='$month'";
		$count = Products::findBySql($sql)->count();		return $count;	}
		public static function getItemsAddedYearly($year) {
			$sql = "SELECT * FROM `hts_products` where from_unixtime(`createdDate`, '%Y')='$year'";
			$count = Products::findBySql($sql)->count();		return $count;	}
			public static function getrevenueTotalMonthly($date) {
				$order = new Orders();
				$sql = "SELECT * FROM `hts_orders` where from_unixtime(`orderDate`, '%Y-%m')='$date' and (`status`='delivered' or `status`='paid')";
				$getRevenue = Orders::findBySql($sql)->all();
				foreach($getRevenue as $order) {
					$getAmt = $order->getCommissionOrder($order->orderId);
					$total = $total + $getAmt;
				}
				return $total;
			}
			public static function getrevenueTotalYearly($date) {
				$sql = "SELECT * FROM `hts_orders` where from_unixtime(`orderDate`, '%Y')='$date' and (`status`='delivered' or `status`='paid')";
				$getRevenue = Orders::findBySql($sql)->all();
				foreach($getRevenue as $order) {
					$getAmt = $order->getCommissionOrder($order->orderId);
					$total = $total + $getAmt;
				}
				return $total;
			}
//end
///end new changes
			public static function getProductImage($id) {
				if($id != null)
					$images = Photos::find()->where(["productId" => $id])->one();
		//echo "<pre>"; print_r($images);die;
				if(!empty($images)){
			//echo $images->name;die;
					return $images->name;
				}
			}
			public static function getCountryId($countryCode){
				$countryModel = Country::find()->where(['code' => $countryCode])->one();
		//print_r($countryModel);exit;
				return $countryModel['countryId'];
			}
			public static function getCountryCode($countryId){
				$countryModel = Country::find()->where(['countryId' => $countryId])->one();
				return $countryModel['code'];
			}
			public static function getProductDetails($id) {
				$product =  Products::find()->where(['productId' => $id])->one();
				if(!empty($product))
					return $product;
			}
			public static function getProductURL($productModel){
				$productURL = Yii::app()->createAbsoluteUrl('products/view',array('id' => $this->safe_b64encode(
					$productModel->productId.'-'.rand(0,999)))).'/'.$this->productSlug($productModel->name);
				return $productURL;
			}
			public static function getUserProductDetails($id,$limit) {
				$product =  Products::find()->where(['userId' => $id])->limit($limit)->all();
				if(!empty($product))
					return $product;
			}
			public static function getUserDetails($id) {
				$user =  Users::find()->where(['userId' => $id])->all();
		//print_r($user);exit;
				if(!empty($user))
					return $user;
			}
			public static function getUserDetailss($id) {
				$user =  Users::find()->where(['userId' => $id])->one();
		//print_r($user);exit;
				if(!empty($user))
					return $user;
			}
			public static function getUsername($id) {
		//print_r($id);exit;
				$user =  Users::find()->where(['userId' => $id])->one();
		//print_r($user);exit;
				if(!empty($user))
					return $user->username;
			}
			public static function getCategory() {
				$category = Categories::find()->where(['parentCategory' => 0])->all();
				return $category;
			}
			public static function getCategoryName($categorySlug) {
				$category = Categories::find()->where(['slug'=>strtolower($categorySlug)])->one();
				if(!empty($category))
					return $category->name;
				else
					return "";
			}
			public static function getCategoryId($categorySlug) {
				$category = Categories::find()->where(['slug'=>strtolower($categorySlug)])->one();
				if(!empty($category))
					return $category->categoryId;
				else
					return "";
			}
			public static function getCategorydetbyslug($categorySlug) {
				$category = Categories::find()->where(['slug'=>strtolower($categorySlug)])->one();
				if(!empty($category))
					return $category;
				else
					return "";
			}
			public static function getCategoryDet($id) {
				$category = Categories::find()->where(['categoryId'=>$id])->one();
				if(!empty($category))
					return $category;
				else
					return "";
			}
	/*public static function slug($str) {
		$str = strtolower(trim($str));
		$str = preg_replace('/[^a-z0-9-]/', '-', $str);
		$str = preg_replace('/-+/', "-", $str);
		return $str;
	}*/
	public static function slug($str) {
		if(preg_match("/[a-z]/i", $str)){
              //print "it has alphabet!";
			$str = strtolower(trim($str));
			$str = preg_replace('/[^a-z0-9-]/', '-', $str);
			$str = preg_replace('/-+/', "-", $str);
			return $str;
		}
		else
		{
			return $str;
		}
	}
	public static function trimSpace($str) {
		$str = str_replace(' ', '-',$str);
		return $str;
	}
	// public static function productSlug($str) {
	// 	$old = $str;
	// 	$str = strtolower(trim($str));
	// 	//preg_replace('/[^أ-يA-Za-z0-9 ]/ui', '', $str);
	// 	$str = preg_replace('/[^أ-يA-Za-z0-9-]/', '', $str);
	// 	//$str = preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/','', $str);
	// 	$str = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
 // '|[\x00-\x7F][\x80-\xBF]+'.
 // '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
 // '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
 // '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
 // '?', $str );
	// 	$str = preg_replace('/(?:^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$)/u', '', $str);
	// //	$str= preg_replace('/\\s/','',$str);
	// 	$str = preg_replace('/-+/', "", $str);
	// 	$str = substr($str, 0, 50);
	// 	if(!empty($str))
	// 		return $str;
	// 	else return trim($old);
	// }
	public static function productSlug($str) {
		$old = $str;
		$str = strtolower(trim($str));
		$str = preg_replace('/[^a-z0-9-]/', '', $str);
		$str = preg_replace('/-+/', "", $str);
		$str = substr($str, 0, 10);
		if(!empty($str))
			return $str;
		else 
			$str = base64_encode($old);
		$str = strtolower(trim($str));
		$str = preg_replace('/[^a-z0-9-]/', '', $str);
		$str = preg_replace('/-+/', "", $str);
		$str = substr($str, 0, 10);
		return trim($str);
	}
	
	public static function getMessageCount($id){
		$chatModel = Chats::find()->where(['lastToRead'=>$id])->all();
		return count($chatModel);
	}
	public static function getNotificationCount($id){
		$userModel = Users::findOne($id);
		return $userModel->unreadNotification;
	}
	public static function getElapsedTime($timestamp) {
		$time = time() - $timestamp;
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);
		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			if($numberOfUnits>1) {
				$text = $text.'s';
			}
			$text = Yii::t('app',$text);
			return $numberOfUnits.' '.$text;
		}
	}
	public static function cart_encrypt($text, $salt)
	{
		//return trim(Myclass::safe_b64encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
		return trim(yii::$app->Myclass->safe_b64encode($text));
	}
	public static function cart_decrypt($text, $salt)
	{
		//return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, Myclass::safe_b64decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		return trim(yii::$app->Myclass->safe_b64decode($text));
	}
	public static function safe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}
	public static function safe_b64decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}
	public static function getCatName($id) {
		$category =   Categories::find()->where(['categoryId' => $id])->one();
		if(!empty($category))
			return $category->name;
		else
			return Yii::t('app','NIL');
	}
	public static function getCatDetails($id) {
		$category = Categories::find()->where(['categoryId' => $id])->one();
		if(!empty($category))
			return $category;
	}
	public static function getDefaultShippingAddress($userId){
		$userAddress = Users::find()->where(['userId' => $userId])->one();
		if(!empty($userAddress))
			return $userAddress->defaultshipping;
	}
	public static function getRandomString($length) {
		$charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$charshuffle = str_shuffle($charset);
		return substr($charshuffle,0,$length);
		return $randomString;
	}
	public static function checkSoldOut($id) {
		$productCriteria = new CDbCriteria;
		$productCriteria->addCondition("productId = '$id'");
		$productCriteria->addCondition("quantity != '0'");
		$products = Products::model()->find($productCriteria);
		return $products;
	}
	public static function getImagefromURL($imageUrl, $type = 'user'){
		if ($type == "item"){
			$user_image_path = "media/items/";
		}else{
			$user_image_path = "profile/";
		}
		$newname = time().".jpg";
		$finalPath = $user_image_path;
		/* while ($out == 0) {
			$i = file_get_contents($imageurl);
			if ($i != false){
			$out = 1;
			}
		} */
		$imageUrl = urldecode($imageUrl);
		$raw = file_get_contents($imageUrl);
		if ($raw == false){
			$ch = curl_init ($imageUrl);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			$raw=curl_exec($ch);
			curl_close ($ch);
		}
		/* if(file_exists($saveto)){
			unlink($saveto);
			}
			$fp = fopen($saveto,'x');
			fwrite($fp, $raw);
			fclose($fp); */
			$fori = fopen($finalPath.$newname,'wb');
			fwrite($fori,$raw);
			fclose($fori);
			chmod($finalPath.$newname, 0666);
			return $newname;
		}
		public static function getShippingCost($pid,$cid) {
			$criteria = new CDbCriteria;
			$criteria->addCondition("productId = $pid");
			$criteria->addCondition("countryId = $cid");
			$shippingCost = Shipping::model()->find($criteria);
			if(!empty($shippingCost))
				return $shippingCost->shippingCost;
			else {
				return '0';
			}
		}
		public static function getLastProductPaypalId($userId){
		// $condition = new CDbCriteria;
		// $condition->addCondition('userId = "'.$userId.'"');
		// $condition->addCondition('paypalid != ""');
		// $condition->order = '`productId` DESC';
			$productModel = Products::find()->where(['userId' => $userId])->andWhere(['<>','paypalid',''])->orderBy(['productId' => SORT_DESC])->one();
			if(!empty($productModel)){
				return $productModel->paypalid;
			}else{
				return "";
			}
		}
		public static function allproducts() {
			$products=Products::find()->where(['approvedStatus' => 1])->orderBy(['productId' => SORT_DESC])->limit(32)->all();
			return $products;
		}
		public static function searchproducts($name) {
			$product =  Products::find()->where(['like','name',$name])->all();
			if(!empty($product))
				return $product;
		}
		public static function getCurrency($str) {
			$str = explode("-",$str);
			return $str[1];
		}
		public static function getCurrencySymbol($str)
		{
			$currencycode = trim($str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $currencycode])->one();
			$currency_symbol = $currencies->currency_symbol;
			return $currency_symbol;
		}
		public static function getFormattingCurrency($str,$amt) {
			$str = explode("-",$str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $str[1]])->one();
			if($currencies->currency_mode == "symbol")
				$currency_format = $str[0];
			else
				$currency_format = $str[1];
			if($currencies->currency_position == "postfix")
				return '<span style="margin-right:5px;">'.$amt.'</span><span>'.$currency_format.'</span>';
			else
				return '<span style="margin-right:5px;">'.$currency_format.'</span><span>'.$amt.'</span>';
			// return $str[1];
		}
		public static function getArabicFormattingCurrency($str,$amt) {
			$str = explode("-",$str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $str[1]])->one();
			if($currencies->currency_mode == "symbol")
				$currency_format = $str[0];
			else
				$currency_format = $str[1];
			if($currencies->currency_position == "postfix")
				return '<span style="margin-right:5px;">'.$currency_format.'</span><span>'.$amt.'</span>';
			else
				return '<div style="text-align:right; direction:ltr;"><span style="margin-right:5px;">'.$amt.'</span><span style="direction:ltr !important;">'.$currency_format.'</span></div>';
				 // return '<span>'.$amt.'</span><span>'.$currency_format.'</span>';
			// return $str[1];
		}
		public static function getCurrencyFormats($str) {
			$str = explode("-",$str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $str[1]])->one();
			$currencyformats = [$currencies->currency_mode,$currencies->currency_position];
			return $currencyformats;
			// return $str[1];
		}
		public static function getCurrencyFormat($str) {
			//echo $str;die;
			$currencycode = trim($str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $currencycode])->one();
			$currencyformats = [$currencies->currency_mode,$currencies->currency_position];
			//echo "<pre>"; print_r($currencyformats);die;
			return $currencyformats;
			// return $str[1];
		}
		public static function getFormattingCurrencyapi($str,$amt) {
			$str = explode("-",$str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $str[1]])->one();
			if($currencies->currency_mode == "symbol")
				$currency_format = $str[0];
			else
				$currency_format = $str[1];
			if($currencies->currency_position == "postfix")
				return $amt.' '.$currency_format;
			else
				return $currency_format.' '.$amt;
			// return $str[1];
		}
		public static function convertFormattingCurrencyapi($str,$amt) {
			$str = trim($str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $str])->one();
			if($currencies->currency_mode == "symbol")
				$currency_format = $currencies->currency_symbol;
			else
				$currency_format =  $currencies->currency_shortcode;
			if($currencies->currency_position == "postfix")
				return $amt.' '.$currency_format;
			else
				return $currency_format.' '.$amt;
		}

		public static function arabicgetFormattingCurrencyapi($str,$amt) {
			$str = explode("-",$str);
		
			$currencies = Currencies::find()->where(['currency_shortcode' => $str[1]])->one();
	
			if($currencies->currency_mode == "symbol")
				$currency_format = $str[0];
			else
				$currency_format = $str[1];
			if($currencies->currency_position == "postfix")
				
				return $currency_format.' '.$amt;
			else
				return $amt.' '.$currency_format;
			// return $str[1];
		}

		public static function arabicconvertFormattingCurrencyapi($str,$amt) {
			$currencies = Currencies::find()->where(['currency_shortcode' => $str])->one();
			if($currencies->currency_mode == "symbol")
				$currency_format = $currencies->currency_symbol;
			else
				$currency_format =  $currencies->currency_shortcode;
			if($currencies->currency_position == "postfix")
				return $currency_format.' '.$amt;
			else
				return $amt.' '.$currency_format;
			// return $str[1];
		}
		public static function convertFormattingCurrency($str,$amt) {
			$str = trim($str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $str])->one();
			$currency_format = "";
			//echo "<pre>"; print_r($currencies);die;
			if($currencies->currency_mode == "symbol"){
				$currency_format = $currencies->currency_symbol;
			}
			else{
				$currency_format =  $currencies->currency_shortcode;
			}
              //echo $currency_format;die;
			if($currencies->currency_position == "postfix"){
				return '<span style="margin-right:5px;">'.$amt.'</span><span>'.$currency_format.'</span>';	
			}
			else
				return '<span style="margin-right:5px;">'.$currency_format.'</span><span>'.$amt.'</span>';
			// return $str[1];
		}
		public static function convertArabicFormattingCurrency($str,$amt) {
			$str = trim($str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $str])->one();
			if($currencies->currency_mode == "symbol")
				$currency_format = $currencies->currency_symbol;
			else
				$currency_format =  $currencies->currency_shortcode;
			if($currencies->currency_position == "postfix")
				return '<span style="margin-right:5px;">'.$currency_format.'</span><span>'.$amt.'</span>';
			else
				return '<div style="text-align:right; direction:ltr;"><span style="margin-right:5px;">'.$amt.'</span><span style="direction:ltr !important;">'.$currency_format.'</span></div>';
			// return $str[1];
		}
		public static function convertArabicPopupFormattingCurrency($str,$amt) 
		{
			$str = trim($str);
			$currencies = Currencies::find()->where(['currency_shortcode' => $str])->one();
			if($currencies->currency_mode == "symbol")
				$currency_format = $currencies->currency_symbol;
			else
				$currency_format =  $currencies->currency_shortcode;
			if($currencies->currency_position == "postfix")
				return '<span style="margin-right:5px;">'.$currency_format.'</span><span>'.$amt.'</span>';
			else
				return '<p style="text-align:center; direction:ltr;"><span style="margin-right:5px;">'.$amt.'</span><span style="direction:ltr !important;">'.$currency_format.'</span></p>';
		}
		public static function getPromotionCurrency() {
			$siteSetting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$str = explode("-",$siteSetting->promotionCurrency);
			return $str[1];
		}
		public static function getCurrencyData(){
			$currencyList = Currencies::find()->all();
			return $currencyList;
		}
		public static function getNewUsers() {
			$criteria = new CDbCriteria;
			$date = date("d-m-Y",time());
			$criteria->condition = "from_unixtime(`createdDate`, '%d-%m-%Y') = '$date'";
			return Users::model()->count($criteria);
		}
		public static function getNewItems() {
			$criteria = new CDbCriteria;
			$date = date("d-m-Y",time());
			$criteria->condition = "from_unixtime(`createdDate`, '%d-%m-%Y') = '$date'";
			return Products::find()->count($criteria);
		}
		public static function getTotalOrders() {
			return Orders::find()->count();
		}
		public static function getTotalPromotions() {
			return Promotiontransaction::find()->count();
		}
		public static function getTotalExchanges() {
			return Exchanges::find()->count();
		}
		public static function getTotalUsers() {
			return  Users::find()->count();
		}
		public static function getTotalItems() {
			return Products::find()->count();
		}
		public static function getSoldTotalItems() {
			return Products::find()->where(['soldItem'=>1])->count();
		}
		public static function getGivingAwayCount() {
			return Products::find()->where(['price'=>0])->count();
		}
		public static function getChatBuyCount() {
			$messages = Messages::find()
			->select('chatId')
			->andWhere(['messageType' => 'normal'])
			->andWhere(['sourceId' => !0])
			->distinct()->count();
			return $messages;
		}
		public static function getExchangeBuyCount() {
			$date = date("d-m-Y",time());
			$messages = Exchanges::find()
			->andWhere([from_unixtime(`date`, '%d-%m-%Y') => $date])
			->andWhere(['status' => 4])
			->count();
			return $messages;
		}
		public static function getInstantBuyCount() {
			$date = date("d-m-Y",time());
			$messages = Invoices::find()
			->andWhere([from_unixtime(`invoiceDate`, '%d-%m-%Y') => $date])
			->andWhere(['status' => 4])
			->count();
			return $messages;
		}
		public static function getExchangeBuyLog($date) {
			$criteria = new CDbCriteria;
			$criteria->condition = "from_unixtime(`date`, '%d-%m-%Y') = '$date'";
			$criteria->addCondition("status = 4");
			return Exchanges::model()->count($criteria);
		}
		public static function getPromotionsAddsCount() {
			$date = date("d-m-Y",time());
			$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date' and `promotionName`='adds'";
			$messages = Promotiontransaction::findBySql($sql)->count();
			return $messages;
		}
		public static function getPromotionsUrgentCount() {
			$date = date("d-m-Y",time());
			$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date' and `promotionName`='urgent'";
			$messages = Promotiontransaction::findBySql($sql)->count();
			return $messages;
		}
		public static function getPromotionsAdds($date) {
			$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date' and `promotionName`='adds'";
			$messages = Promotiontransaction::findBySql($sql)->count();
			return $messages;
		}
		public static function getPromotionsUrgent($date) { 
			$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date' and `promotionName`='urgent'";
			$messages = Promotiontransaction::findBySql($sql)->count();
			return $messages;
		}
		public static function getPromotionsAddsMonthly($date) {
			$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y-%m')='$date' and `promotionName`='adds'";
			$messages = Promotiontransaction::findBySql($sql)->count();
			return $messages;
		}
		public static function getPromotionsUrgentMonthly($date) { 
			$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y-%m')='$date' and `promotionName`='urgent'";
			$messages = Promotiontransaction::findBySql($sql)->count();
			return $messages;
		}
		public static function getPromotionsAddsYearly($date) {
			$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y')='$date' and `promotionName`='adds'";
			$messages = Promotiontransaction::findBySql($sql)->count();
			return $messages;
		}
		public static function getPromotionsUrgentYearly($date) { 
			$sql = "SELECT * FROM `hts_promotiontransaction` where from_unixtime(`createdDate`, '%Y')='$date' and `promotionName`='urgent'";
			$messages = Promotiontransaction::findBySql($sql)->count();
			return $messages;
		}
		public static function getInstantBuyLog($date) {
			$criteria = new CDbCriteria;
			$criteria->condition = "from_unixtime(`invoiceDate`, '%d-%m-%Y') = '$date'";
			return Invoices::model()->count($criteria);
		}
		public static function getRegisteredUsers($date = null) {
			if(empty($date)) {
				$date = date("d-m-Y",time());
			//print_r($date);exit;
			}
			$sql = "SELECT * FROM `users` where from_unixtime(`created_at`, '%d-%m-%Y')='$date'";
			$messages = Users::findBySql($sql)->count();
			return $messages;
		}
		public static function getLoggedUsers($date = null) {
			if(empty($date)) {
				$date = date("d-m-Y",time());
			}
			$sql = "SELECT * FROM `users` where from_unixtime(`lastLoginDate`, '%d-%m-%Y')='$date'";
			$messages = Users::findBySql($sql)->count();
			return $messages;
		}
		public static function getActiveUsers() {
			if(empty($date)) {
				$date = date("d-m-Y",time());
			}
			$sql = "SELECT * FROM `users` where userstatus =1 and activationStatus =1";
			$messages = Users::findBySql($sql)->count();
			return $messages;
		}
		public static function getRegisteredUsersMonthly($date) {
			$sql = "SELECT * FROM `users` where from_unixtime(`created_at`, '%Y-%m')='$date'";
			$messages = Users::findBySql($sql)->count();
			return $messages;
		}
		public static function getLoggedUsersMonthly($date) {
			$sql = "SELECT * FROM `users` where from_unixtime(`lastLoginDate`, '%Y-%m')='$date'";
			$messages = Users::findBySql($sql)->count();
			return $messages;
		}
		public static function getRegisteredUsersYearly($date) {
			$sql = "SELECT * FROM `users` where from_unixtime(`created_at`, '%Y')='$date'";
			$messages = Users::findBySql($sql)->count();
			return $messages;
		}
		public static function getLoggedUsersYearly($date) {
			$sql = "SELECT * FROM `users` where from_unixtime(`lastLoginDate`, '%Y')='$date'";
			$messages = Users::findBySql($sql)->count();
			return $messages;
		}
		public static function getItemsAdded($date) {
			$sql = "SELECT * FROM `hts_products` where from_unixtime(`createdDate`, '%d-%m-%Y')='$date'	";
			$messages = Products::findBySql($sql)->count();
			return $messages;
		}
		public static function getUserId($username) {
			$user = Users::model()->findByAttributes(array('username'=>$username));
			if(!empty($user))
			{
				return $user->userId;
			}
		}
		public static function exchangeProductExist($mid,$exid,$fromUser,$toUser) {
		// $criteria = new CDbCriteria;
		// $criteria->condition = "(`mainProductId` = '$mid' AND `exchangeProductId` = '$exid' OR `mainProductId` = '$exid' AND `exchangeProductId` = '$mid') AND (`requestFrom` = '$fromUser' AND `requestTo` = '$toUser' OR `requestFrom` = '$toUser' AND `requestTo` = '$fromUser')";
			$sql = "SELECT * FROM `hts_exchanges` where (`mainProductId` = '$mid' AND `exchangeProductId` = '$exid' OR `mainProductId` = '$exid' AND `exchangeProductId` = '$mid') AND (`requestFrom` = '$fromUser' AND `requestTo` = '$toUser' OR `requestFrom` = '$toUser' AND `requestTo` = '$fromUser')";
			$exCheck = Exchanges::findBySql($sql)->one();
			if(!empty($exCheck)) {
				return $exCheck;
			}
		}
		public static function getCurrencyList($cur = null) {
			$currency =  array('$-Australian Dollar' => 'AUD', 'R$-Brazilian Rea' => 'BRL', 'C$-Canadian Dollar' => 'CAD', 'Kč-Czech Koruna' => 'CZK', 'kr.-Danish Krone' => 'DKK', '€-Euro' => 'EUR', 'HK$-Hong Kong Dollar' => 'HKD', 'Ft-Hungarian Forint' => 'HUF', '₪-Israeli New Sheqel' => 'ILS', '¥-Japanese Yen' => 'JPY', 'RM-Malaysian Ringgit' => 'MYR', 'Mex$-Mexican Peso' => 'MXN', 'kr-Norwegian Krone' => 'NOK', '$-New Zealand Dollar' => 'NZD', '₱-Philippine Peso' => 'PHP', 'zł-Polish Zloty' => 'PLN', '£-Pound Sterling' => 'GBP', 'руб-Russian Ruble' => 'RUB', 'S$-Singapore Dollar' => 'SGD', 'kr-Swedish Krona' => 'SEK', '₣-Swiss Franc' => 'CHF', 'NT$-Taiwan New Dolla' => 'TWD', '฿-Thai Baht' => 'THB', '₺-Turkish Lira' => 'TRY', '$-U.S. Dollar' => 'USD' ); 
			if(!empty($cur)) {
				return $currency[$cur];
			} else {
				return $currency;
			}
		}
		public static function getDbCurrencyList($cur = null) {
			$currencyModel =  Currencies::find()->all(); 
			foreach($currencyModel as $value):
				$key = trim($value->currency_symbol)."-".trim($value->currency_name);
				$currency[$key] =  $value->currency_shortcode;
			endforeach;
			if(!empty($cur)) {
				return $currency[$cur];
			} else {
				return $currency;
			}
		}
		public static function checkWhetherProductSold($productId) {
			$product = Products::find()->where(['productId' => $productId])->one();
			if(($product['soldItem'] == 1) || ($product['quantity'] == 0)) {
				return $product;
			} else {
				return 0;
			}
		}
		public static function checkChatExists($user1,$user2) {
			$sql = "SELECT * FROM `hts_chats` where `user1` = '$user1' AND `user2` = '$user2' OR `user1` = '$user2' AND `user2` = '$user1'";
			$chatCheck = Chats::findBySql($sql)->one();
		// $criteria = new CDbCriteria;
		// $criteria->condition = "(`user1` = '$user1' AND `user2` = '$user2' OR `user1` = '$user2' AND `user2` = '$user1')";
		//$chatCheck = Chats::find()->where('user1' => $user1);
			if(!empty($chatCheck)) {
				return $chatCheck->chatId;
			}
		}
		public static function getSiteName() {
			$siteSetting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			return $siteSetting->sitename;
		}
	/**
	 * To add the logs to the database
	 *
	 * @param string $type
	 * @param integer $userid
	 * @param integer $notifyto
	 * @param integer $sourceid
	 * @param integer $itemid
	 * @param string $notifymessage
	 * @param integer $notificationId
	 * @param string $message
	 */
	public static function addLogs($type, $userid, $notifyto = 0, $sourceid = 0, $itemid = 0,$notifymessage = null, $notificationId = 0, $message = null){
		if($notifyto || $notifyto == 0 || $userid == 0){
			$logsModel = new Logs();
			$logsModel->type = $type;
			$logsModel->userid = $userid;
			$logsModel->notifyto = $notifyto;
			$logsModel->sourceid = $sourceid;
			$logsModel->itemid = $itemid;
			$logsModel->notifymessage = $notifymessage;
			$logsModel->notification_id = $notificationId;
			$logsModel->message = $message;
			$logsModel->createddate = time();
			$logsModel->save(false);
			if($notifyto != 0){
				$userModel = Users::find()->where(['userId' => $notifyto])->one();
				if(!empty($userModel)){
					$userModel->unreadNotification += 1;
					$userModel->save(false);
				}
			}
			else if($notifyto == 0 && $type == "admin")
			{
				if ($userid!=0) {
					$followersModel = Followers::find()->where(['follow_userId' => $userid])->all();
					foreach ($followersModel as $follower){
						$followerId = $follower->userId;
						$userModel = Users::find()->where(['userId' => $notifyto])->one();
						if(!empty($userModel)){
							$userModel->unreadNotification += 1;
							$userModel->save(false);
						}
					}
				}
				else
				{
					$userModel = Users::find()->all();
					foreach ($userModel as $user){
						$user->unreadNotification += 1;
						$user->save(false);
					}
				}
				//Users::model()->updateCounters(array("unreadNotification"=>"1"));
			}
		}
	}
	/**
	 * To removed the logs based on
	 * the Product Id
	 *
	 * @param string $itemId
	 */
	public static function removeItemLogs($itemId){
		Logs::deleteAll(['itemid' => $itemId]);
		return true;
	}
	/*public static function pushnot($deviceToken = NULL, $message = NULL, $badge = NULL,$notifytype="notification"){
		$criteria = new CDbCriteria;
		$criteria->addCondition('deviceToken = "'.$deviceToken.'"');
		$userdevicedatas = Userdevices::model()->find($criteria);
		if($userdevicedatas->type == 0){
			include_once('certificate/PushNotification.php');
				if($userdevicedatas->mode == 1){
							$certifcUrl =  'certificate/joysaleDev.pem';
							$push = new PushNotification("sandbox",$certifcUrl);
				}else{
							$certifcUrl =  'certificate/joysalePro.pem';
							$push = new PushNotification("production",$certifcUrl);
				}
				$push->setDeviceToken($deviceToken);
				$push->setPassPhrase("");
				$push->setBadge($badge);
				$push->setNotifytype($notifytype);
				$push->setMessageBody($message);
				$push->sendNotification();
		}else{
				Myclass::send_push_notification($deviceToken, $message,$notifytype);
		}
	}*/
	public static function pushnot($deviceToken = NULL, $message = NULL, $badge = NULL, $notifytype="notification")
	{
		$userdevicedatas = Userdevices::find()->where(['deviceToken' => $deviceToken])->one();
		if($userdevicedatas->type == 0) {
			yii::$app->Myclass->sendall_push_notification($deviceToken,$message,$notifytype,$userdevicedatas->type);
		} else {
			yii::$app->Myclass->sendall_push_notification($deviceToken,$message,$notifytype,$userdevicedatas->type);
		}
	}
	public static function sendall_push_notification($registatoin_ids, $message, $notifytype, $device_type) {
		$fcm_url = 'https://fcm.googleapis.com/fcm/send';
		$id = 1;
		$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$registatoin_ids = array($registatoin_ids);
		if($device_type == 0) {
			$fcmMsg = array(
				'body' => $message,
				'sound' => "default",
				"type"=>$notifytype
			);
			$fcmFields = array(
				'registration_ids' => $registatoin_ids,
				'priority' => 'high',
				'notification' => $fcmMsg,	
			);
		} elseif ($device_type == 1) {
			$messageToBeSent = array();
			$messageToBeSent['data']['message'] = json_encode($message, JSON_UNESCAPED_UNICODE,true);
			$messageToBeSent['data']['type'] = $notifytype;
			$fcmFields = array(
				'registration_ids' => $registatoin_ids,
				'data' => $messageToBeSent
			);
		}
		$headers = array(
			'Authorization: key=' . $setting->androidkey,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, $fcm_url );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
		$result = json_decode(curl_exec($ch));
		curl_close( $ch );
		//if($result->success == 1) {
		//}
	}
	public static function push_lang($lang){
		Yii::$app->language = $lang;
		return;
	}
	/*public static function send_push_notification($registatoin_ids, $message,$notifytype){
			$url = 'https://android.googleapis.com/gcm/send';
			$registatoin_ids = array($registatoin_ids);
			$message = array("price" => $message,'type'=>$notifytype);
			$fields = array(
					'registration_ids' => $registatoin_ids,
					'data' => $message,
			);
			$id = 1;
			$setting = Sitesettings::model()->findByPk($id);
			$headers = array(
					'Authorization: key='.$setting->androidkey.'',
					'Content-Type: application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
			}
			$errormsg = curl_error($ch);
			curl_close($ch);
		}*/
		public static function getCategoryPriority() {
			$id = 1;
			$setting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$details = array();
			if(!empty($setting->category_priority)){
				$categorypriority = Json::decode($setting->category_priority, true);
			/*$categorypriority1 = $categorypriority['categorypriority'];
			$details['socialLinks'] = array();$details['appLinks'] = array();
			if(!empty($categorypriority['facebooklink'])){
				$details['socialLinks']['facebook'] = $categorypriority['facebooklink'];
			}
			if(!empty($categorypriority['googlelink'])){
				$details['socialLinks']['google'] = $categorypriority['googlelink'];
			}
			if(!empty($categorypriority['twitterlink'])){
				$details['socialLinks']['twitter'] = $categorypriority['twitterlink'];
			}
			if(!empty($categorypriority['androidlink'])){
				$details['appLinks']['android'] = $categorypriority['androidlink'];
			}
			if(!empty($categorypriority['ioslink'])){
				$details['appLinks']['ios'] = $footerSettings['ioslink'];
			}*/
		}
		return $categorypriority;
	}
	public static function getCategoryfull() {
		$id = 1;
		$setting = Categories::find()->where(['parentCategory' => '0'])->all();
		$details = array();
		if(!empty($setting->category_priority)){
			$categorypriority = Json::decode($setting->category_priority, true);
			/*$categorypriority1 = $categorypriority['categorypriority'];
			$details['socialLinks'] = array();$details['appLinks'] = array();
			if(!empty($categorypriority['facebooklink'])){
				$details['socialLinks']['facebook'] = $categorypriority['facebooklink'];
			}
			if(!empty($categorypriority['googlelink'])){
				$details['socialLinks']['google'] = $categorypriority['googlelink'];
			}
			if(!empty($categorypriority['twitterlink'])){
				$details['socialLinks']['twitter'] = $categorypriority['twitterlink'];
			}
			if(!empty($categorypriority['androidlink'])){
				$details['appLinks']['android'] = $categorypriority['androidlink'];
			}
			if(!empty($categorypriority['ioslink'])){
				$details['appLinks']['ios'] = $footerSettings['ioslink'];
			}*/
		}
		return $setting;
	}
	public static function getSubCategory($id) {
		$subCategory =  Categories::find()->where(['parentCategory'=>$id])->all();
		$subCategory =  ArrayHelper::map($subCategory, 'categoryId', 'name');
		return $subCategory;
	}
	public static function getCatImage($id) {
		$category = Categories::find()->where(['categoryId' => $id])->one();
		if(!empty($category))
			return $category->image;
		else
			return Yii::t('app','NIL');
	}
	public static function getsocialLoginDetails() {
		$id = 1;
		$siteSettingsModel = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$socialLogin = Json::decode($siteSettingsModel->socialLoginDetails, true);
		return $socialLogin;
	}
	public static function getSitesettings()
	{
		$id = 1;
		$siteSettingsModel = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		return $siteSettingsModel;
	}
	public static function checkPostvalue($val)
	{
		if (preg_match('/[\'\"^£$%&*()}{@#~?><>;":,.|=_+¬-]/', $val))
		{
			throw new CHttpException(500,'Malicious Activity');
		}
		else
		{
			return true;
		}
	}
	public static function Change_chatUser_status($callValue, $currentID, $ChatUserID)
	{
		$ChatID = yii::$app->Myclass->checkChatExists($currentID, $ChatUserID);
		if($ChatID) {
			$blockedUserValue = yii::$app->Myclass->getChatBlockValue($ChatID);
			if($callValue == "unblock" && $blockedUserValue == $ChatUserID){
				$default = Chats::findOne($ChatID);
				$default->blockedUser = 0;
				if($default->save(false)){
					$blockedUserValue = base64_encode($default->blockedUser);
					return "unblocked~#~".$blockedUserValue;
				}
			} elseif ($callValue == "block" && $blockedUserValue == 0) {
				$default = Chats::findOne($ChatID);
				$default->blockedUser = $ChatUserID;
				if($default->save(false)){
					$blockedUserValue = base64_encode($default->blockedUser);
					return "blocked~#~".$blockedUserValue;
				}
			} elseif ($callValue == "block" && $blockedUserValue == $currentID) {
				$blockedUserValue = base64_encode($blockedUserValue);
				return "currentblocked~#~".$blockedUserValue;
			}
		}
		return "false";
	}
	public static function getChatBlockValue($ChatID) {
	//	$criteria = new CDbCriteria;
	//	$criteria->condition = "(`chatId` = '$ChatID')";
		$chatCheck = Chats::find()->where(['chatId'=>$ChatID])->one();
		if(!empty($chatCheck)) {
			return $chatCheck->blockedUser;
		}
	}
	public static function getbraintreemerchantid($paycurrency) {
		$site_datas = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$sitepaystatus = Json::decode($site_datas->braintree_merchant_ids,true);
		if($sitepaystatus!="")
		{
			if (array_key_exists($paycurrency, $sitepaystatus)) {
				foreach ($sitepaystatus as $key => $value) {
					if($key == $paycurrency) {
						$data =  $value['merchant_account_id'];
					}
				}
			}
			else
			{
				$data="";
			}
		}else
		{
			$data="";
		}
		return $data;         
		//return $sitepaystatus;
	}
	public static function getDeviceName() {
		$iPod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
		if($iPad||$iPhone||$iPod){
			return 'ios';
		} else if($android) {
			return 'android';
		} else {
			return 'pc';
		}
	}
	public static function getWhosBlock($currentId,$receiverId) {
		$ChatID = yii::$app->Myclass->checkChatExists($currentId, $receiverId);
		if(!empty($ChatID)){
			$blockedUserValue = yii::$app->Myclass->getChatBlockValue($ChatID);
		if($blockedUserValue==0) { return 0; /* No block found */ }
		else{
		if($currentId==$blockedUserValue) { return 1; /* current user is blocked by receiver */ }
	else { return 2; /* receiver  is blocked by current user */ }
}
}
else { return 0; /* No block found */ }
}
 //Get Date Records
public static function getDaterecordsWeekly($date,$id) {
	$getViews = Userviews::find()
	->select('created_at')
	->where(['product_id'=>$id])
	->all();
	$arrayCount =  array();
	foreach ($getViews as $key=>$value) {
		$data = date("Y-m-d", strtotime($value->created_at));
		$cdate = date("Y-m-d", strtotime($date));
		if(strtotime($data) == strtotime($cdate))
		{
			$arrayCount[] = $value;
		}
	}
	return count($arrayCount);
		/*
		$dateRecords =Records::findOne(['productId' => $id]);
		$records = Json::decode($dateRecords->records);
		 $count = $records[$date]; 
		if($count=="")
		{
			$value = 0;
		}
		else
		{
			$value = $count;
		}
		return $value;
		*/
	}
	public static function getDaterecordsMontly($month,$id) {
		$getViews = Userviews::find()
		->select('created_at')
		->where(['product_id'=>$id])
		->all();
		$arrayCount =  array();
		foreach ($getViews as $key=>$value) {
			$data = date("Y/m", strtotime($value->created_at));
			if($data == $month)
			{
				$arrayCount[] = $value;
			}
		}
		return count($arrayCount);
	}
	public static function getDaterecordsYearly($year,$id) {
		$getViews = Userviews::find()
		->select('created_at')
		->where(['product_id'=>$id])
		->all();
		$arrayCount =  array();
		foreach ($getViews as $key=>$value) {
			$data = date("Y", strtotime($value->created_at));
			if($data == $year)
			{
				$arrayCount[] = $value;
			}
		}
		return count($arrayCount);
	}
	public static function getFilterdata($filterId) {
		$getFilters = Filter::find()->where(['id'=>$filterId])->one();
		return $getFilters;
	}
	public static function checkproductexist($id) {
		$product =  Products::find()->where(['category'=>$id])->count();
		if(!empty($product))
			return $product;
	}
}
?>