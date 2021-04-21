<?php
namespace frontend\controllers;
use Yii;
use common\models\Products;
use common\models\Photos;
use common\models\Comments;
use common\models\Chats;
use common\models\Userviews;
use common\models\Logs;
use common\models\Filter;
use common\models\Filtervalues;
use common\models\Help;
use common\models\Productfilters;
use common\models\Favorites;
use common\models\Promotiontransaction;
use common\models\Adspromotiondetails;
use common\models\Banners;
use common\models\Tempaddresses;

use common\models\MyOfferForm;
use Braintree;
use yii\db\Expression;

use common\models\Categories;
use common\models\Country;
use common\models\Promotions;
use common\models\Sitesettings;
use common\models\Productconditions;
use common\components\Myclass;
use common\models\Exchanges;

use common\models\Shipping;
use common\models\Userdevices;
use common\models\Followers;
use common\models\Currencies;
use common\models\Messages;
use common\models\Users;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\imagine\Image;
use Imagine\Image\Box;
error_reporting(0);

$baseUrl = Yii::$app->request->baseUrl;

class ProductsController extends \yii\web\Controller
{
	public function actionIndex()
	{
		return $this->render('index');
	}

	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		if (parent::beforeAction($action)) {
			if(!Yii::$app->user->isGuest){
				$User = Users::find()->where(['userId' => Yii::$app->user->id])->one(); 
				if($User->userstatus == 0){
					Yii::$app->session->setFlash('error', Yii::t('app','Your account has been disabled by the Administrator'));
					Yii::$app->user->logout();
					return $this->goHome();
				}
			}
		}
		return true;

	}
	
	public function actionCreate()
	{
		
		if (!Yii::$app->user->id) {            
			return $this->redirect(['site/login']);    
		}

		$model=new Products;

		$parentCategory = array();
		$parentCategory = Categories::find()->where(['parentCategory'=>0])->all();
		if (!empty($parentCategory)){
			$parentCategory = ArrayHelper::map($parentCategory, 'categoryId', 'name');
		}

		
		$subCategory = array();

		$shippingTime['1 business day'] = '1 business day';
		$shippingTime['1-2 business day'] = '1-2 business day';
		$shippingTime['2-3 business day'] = '2-3 business day';
		$shippingTime['3-5 business day'] = '3-5 business day';
		$shippingTime['1-2 weeks'] = '1-2 weeks';
		$shippingTime['2-4 weeks'] = '2-4 weeks';
		$shippingTime['5-8 weeks'] = '5-8 weeks';

		$countryModel = array();
		$countryList = Country::find()->where(['!=','countryId',0])->all();
		
		if (!empty($countryList)){
			//$countryModel = CHtml::listData($countryList, 'countryId', 'country');
			foreach ($countryList as $country){
				$countryKey = $country->countryId."-".$country->country;
				$countryModel[$countryKey] = $country->country;
			}
		}

		$promotionDetails = Promotions::find()->all();

		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$urgentPrice = $siteSettings->urgentPrice;
		$promotionCurrency = $siteSettings->promotionCurrency;

		$user = Yii::$app->user->id;
		
		$userModel = Users::find()->where(['userId'=>$user])->one();
		$geoLocationDetails = "";
		$shipping_country_code = "";

		if($userModel->geolocationDetails != ""){
			$geoLocationDetails = Json::decode($userModel->geolocationDetails, true);

			$place = $geoLocationDetails['place'];
			$places = explode(",",$place);
			$countryname = trim(end($places));
			$countrylist = Country::find()->where(['like','country',$countryname])->one();
			$shipping_country_code = $countrylist->code;
		}
		// Uncomment the following line if AJAX validation is neededuse yii\helpers\Json;
		//$this->performAjaxValidation($model);



		if(isset($_POST['Products']))
		{
			
			$productData = $_POST['Products'];
			//echo '<pre>'; print_r($productData); exit;

			$model->attributes=$_POST['Products'];
			
			$model->name = htmlentities($model->name);
			$model->description = htmlentities($model->description);
			$model->userId = Yii::$app->user->id;

			$model->createdDate = time();
			
			if(isset($_POST['Products']['sub_subCategory'])){
				$model->sub_subCategory  = $_POST['Products']['sub_subCategory'];
			}



			$model->filters = json_encode($_POST['Products']['attributes']);

			/*
			$parentfieldOption = Categories::find()->where(['categoryId' => $_POST['Products']['category']])->one();
			$subcatfieldOption = Categories::find()->where(['categoryId' => $_POST['Products']['subCategory']])->one();

			//Get category attributes.
			
			if($parentfieldOption->categoryAttributes != '' && $subcatfieldOption->categoryAttributes != '')
			{
				$getcateAttributes = explode(',', $parentfieldOption->categoryAttributes);
				$getsubcateAttributes = explode(',', $subcatfieldOption->categoryAttributes);
				$getAttributes = array_unique(array_merge($getcateAttributes, $getsubcateAttributes));
			}elseif($parentfieldOption->categoryAttributes != '' && $subcatfieldOption->categoryAttributes == '')
			{
				$getAttributes = explode(',', $parentfieldOption->categoryAttributes);
			}elseif($parentfieldOption->categoryAttributes == '' && $subcatfieldOption->categoryAttributes != '')
			{
				$getAttributes = explode(',', $subcatfieldOption->categoryAttributes);
			}

			$addArray = array();
			foreach($getAttributes as $attributeKey=>$attributeVal)
			{
				$addArray[] = Filter::find()->where(['id'=>$attributeVal, 'inputtype'=>'text'])->one();
			}
			*/
			//echo '<pre>'; print_r($addArray); exit;
			
			//echo json_encode($_POST['Products']['attributes']); exit;

			$model->exchangeToBuy = 0;
			if(isset($_POST['Products']['exchangeToBuy']))
				$model->exchangeToBuy = $_POST['Products']['exchangeToBuy'];

			if(isset($_POST['giving_away'])) {
				$model->price = 0;
			}

			if (isset($_POST['Products']['shippingcountry']) && $_POST['Products']['shippingcountry'] != '') {
				$model->shippingcountry = yii::$app->Myclass->getCountryId($_POST['Products']['shippingcountry']);
			}
			
			
			$model->instantBuy = 0;
			if(isset($_POST['Products']['instantBuy']) && (isset($_POST['giving_away'])=="" || isset($_POST['giving_away']) == '0')) {
				$model->instantBuy = $_POST['Products']['instantBuy'];
				$model->shippingcountry = yii::$app->Myclass->getCountryId($_POST['Products']['shippingcountry']);
				$model->shippingCost = $_POST['Products']['shippingCost'];
			}

			$model->myoffer = 0;
			
			if(isset($_POST['Products']['myoffer']))
			{
				$model->myoffer = $_POST['Products']['myoffer'];
				$model->currency  = $_POST['Products']['currency'];
				$model->subCategory  = $_POST['Products']['subCategory'];
			}
			
			if (isset($productData['productOptions'])){
				$model->sizeOptions = Json::encode($productData['productOptions']);
				$quantity = 0;
				$optionPrice = 0;
				foreach($productData['productOptions'] as $options){
					$quantity += $options['quantity'];
					$optionPrice = $optionPrice == 0 && !empty($options['price']) ? $options['price'] : $optionPrice;
				}
				$model->quantity = $quantity;
				$model->price = $optionPrice != 0 ? $optionPrice : $model->price;
			}
			$model->quantity = 1;

			if($siteSettings->product_autoapprove==1)
			{
				$model->approvedStatus = 1;
				$model->Initial_approve = 1;
			}
			else
			{
				$model->approvedStatus = 0;
				$model->Initial_approve = 0;
				Yii::$app->session->setFlash('success',Yii::t('app','Your product is submitted & waiting for admin approval'));

			}
			
			if($model->save(false)){

				foreach ($productData['shipping'] as $key => $shipping){
					if($shipping != ""){
						$shippingModel = new Shipping();
						$shippingModel->productId = $model->productId;
						$shippingModel->countryId = $key;
						$shippingModel->shippingCost = $shipping;
						$shippingModel->createdDate = time();
						$shippingModel->save();
					}
				}


			//removing files
				$rmfilenames = $_POST['removefiles'];
				$rmtemp = explode(',', $rmfilenames);
				foreach ($rmtemp as $value) {
					$photosModel = Photos::find()->where(['name' =>$value])->one();
					$path = Yii::$app->basePath ."/web/media/item/".$model->productId."/"."/";
					$file = $path.$value;
					if( is_file( $file ) ) {
						unlink( $file );
					}
					if(!empty($photosModel))
						$photosModel->delete();
				}

			//Uploading images

				

				$filenames = json_decode($_POST['uploadedfiles'],true);
				
				for($i=0;$i<count($filenames);$i++)
				{
					$photos = new Photos();
					$photodata = $photos::find()->where(['name'=>$filenames[$i]])->one();
					if(!$photodata)	{						
						$path = realpath ( Yii::$app->basePath . "/web/media/item/" )."/".$model->productId . "/";
						$tmp_path = realpath ( Yii::$app->basePath . "/web/media/item/tmp/" )."/".$filenames[$i];
						
						if( !is_dir( $path ) ) {
							
							FileHelper::createDirectory($path);
							chmod( $path, 0777 );
						}

						if( is_file( $tmp_path ) ) {
							
							if( rename( $tmp_path, $path.$filenames[$i] ) ) {

								chmod( $path.$filenames[$i], 0777 );
								$watermark = yii::$app->Myclass->getWatermark();

								$watermarkImage = Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark);
								$image = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$model->productId.'/'.$filenames[$i]);
								list($widthh,$heightt) = getimagesize($image);

								$imagine = Image::getImagine();
								$imagine = $imagine->open(Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark));
								$sizes = getimagesize(Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark));

								if($sizes[0] > $sizes[1]) {
									if($heightt > $widthh) {
										$ratioBrand = $sizes[1] / $sizes[0]; 
										$width = $widthh * 0.3;    
										$height = $width / 4;  
									} else {
										$ratioBrand = $sizes[1] / $sizes[0];  
										$height = $heightt * 0.07;     
										$width = $height / 0.25;     
									} 
								} else { 
									if($heightt > $widthh) {
										$ratioBrand = $sizes[1] / $sizes[0]; 
										$width = $widthh * 0.2; 
										$height = $width / $ratioBrand; 
									} else {
										$ratioBrand = $sizes[1] / $sizes[0];  
										$height = $heightt * 0.15;   
										$width = $height / $ratioBrand;   
									}  
								}

								$imagine = $imagine->resize(new Box($width, $height))->save(Yii::getAlias('@webroot/media/item/'.$model->productId.'/watermark.png', ['quality' => 60]));
								
								$watermarkfile=Yii::getAlias('@webroot/media/item/'.$model->productId.'/watermark.png');

								$dest_x = intval($widthh - $width - 25); 
								$dest_y = intval($heightt - $height - 25);   
								$position = array($dest_x,$dest_y);

								$newImage = Image::watermark($image, $watermarkfile, $position);
								$newImage->save(Yii::getAlias('@webroot/media/item/'.$model->productId.'/'.$filenames[$i], ['quality' => 60]));
								
								unlink($watermarkfile); 

								chmod( $path.$filenames[$i], 0777 );
								$photos->productId = $model->productId;
								$photos->name = $filenames[$i]; 
								$photos->createdDate = time();
								$photos->save(false);     
							}

						}
					}
				}
				//echo 'ss'; exit;
				$userdetail = yii::$app->Myclass->getcurrentUserdetail();



				if($siteSettings->product_autoapprove==1)
				{
					$notifyMessage = 'added a product';
					
					yii::$app->Myclass->addLogs("add", $model->userId, 0, $model->productId, $model->productId, $notifyMessage);
					


					$userid = $model->userId;
					$userdata = Users::find()->where(['userId'=>$userid])->one();
					
					$currentusername = $userdata->name;
					
					$followers = Followers::find()->where(['follow_userId'=>$userid ])->all();

					
					foreach ($followers as $follower) {
						$followuserid = $follower->userId;
						
						$userdevicedet = Userdevices::find()->where(['user_id'=>$followuserid])->all();
						if(count($userdevicedet) > 0){
							foreach($userdevicedet as $userdevice){
								$deviceToken = $userdevice->deviceToken;
								$lang = $userdevice->lang_type;
								$badge = $userdevice->badge;
								$badge +=1;
								$userdevice->badge = $badge;
								$userdevice->deviceToken = $deviceToken;
								$userdevice->save(false);
								if(isset($deviceToken)){
									$messages =$currentusername.'added a product'.$model->name;
								//syii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
								}
							}
						}
					}
				}

				//if(isset($_POST['facebook_share'])) {
				

					//$photo_detail = Photos::find()->where(["productId" => $model->productId]);

				
				//}

				if(isset($_POST['Products']['promotion']['type']) && $_POST['Products']['promotion']['type'] != ""){
					$promotionType = $_POST['Products']['promotion']['type'];
					Yii::$app->session['promotionType'] = $promotionType;
					if($promotionType == "adds"){
						Yii::$app->session['addspromotionType'] = $_POST['Products']['promotion']['addtype'];
					}
					Yii::$app->session['productId'] = $model->productId;
					$redirectUrl = Yii::$app->urlManager->createAbsoluteUrl('promotionpayment');
					return $this->redirect($redirectUrl);
					
				}else{
					$baseUrl = Yii::$app->request->baseUrl;

					$sitesetting = yii::$app->Myclass->getSitesettings();
					$redirectUrl = Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($model->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($model->name);

					
					$getPostattributes = $_POST['Products']['attributes'];
					foreach($getPostattributes as $attrKey=>$attrVal)
					{
						if(empty($attrKey))
							continue;
						if($attrKey != 'multilevel')
						{

							$filterGet = Filter::find()->where(['id'=>$attrKey])->one();
							$productvals = Filtervalues::find()->where(['id'=>$attrVal])->one();

							if($filterGet->type == 'dropdown')
							{
								$levelOne = $attrKey;
								$levelTwo = $attrVal;
								$levelThree = 0;
								$pro_value = $productvals->name;
							}elseif($filterGet->type == 'range')
							{
								$levelOne = $attrKey;
								$levelTwo = $attrVal;
								$levelThree = 0;
								$pro_value = $attrVal;
							}elseif($filterGet->type == 'multilevel')
							{
								$levelOne = $attrKey;
								$levelTwo = $attrVal;
								$levelThree = $getPostattributes['multilevel'][$levelTwo];

									//echo '<pre>'; print_r($levelTwo); exit;
								$getlevel2val = Filtervalues::find()->where(['id'=>$levelTwo])->one();

								$getlevel3val = Filtervalues::find()->where(['id'=>$levelThree])->one();

								$pro_value = $getlevel2val->name.', '.$getlevel3val->name;
							}

							$productAttribute = new Productfilters;
							$productAttribute->product_id = $model->productId;
							$productAttribute->category_id = $_POST['Products']['category'];
							$productAttribute->subcategory_id = ($_POST['Products']['subCategory'] == '') ? '0' : $_POST['Products']['subCategory'];
							$productAttribute->sub_subcategory_id = ($_POST['Products']['sub_subCategory'] == '') ? '0' : $_POST['Products']['sub_subCategory'];
							$productAttribute->filter_id = $attrKey;
							$productAttribute->level_one = $levelOne;
							$productAttribute->level_two = $levelTwo;
							$productAttribute->level_three = $levelThree;
							$productAttribute->filter_name = $filterGet->name;
							$productAttribute->filter_type = $filterGet->type;
							$productAttribute->filter_values = $pro_value;
							$productAttribute->filtervalue_id = 0;
							$productAttribute->save(false);
						}
					}
					if($sitesetting->promotionStatus == 1)
					{
						echo $model->productId."-_-".$redirectUrl;
						die;
					}
					else
					{
						echo "0-_-".$redirectUrl;
						die;
					}
					

				}
			}else{
				Yii::$app->session->setFlash('error',Yii::t('app','Not Saved'));
			}

		}

		$currencies = Currencies::find()->all();
		$currencyPrority=Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$topFiveCur = $currencyPrority->currency_priority;
		$givingawaydata = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$givingaway=$givingawaydata->givingaway;
		$pricerange=json_decode($siteSettings->pricerange);

		$topFive = Json::decode($topFiveCur);
		foreach($topFive as $top):
			$topCurs[] = Currencies::find()->where(["id" => $top]);
		endforeach;
		

		
		return $this->render('create',[
			'model'=>$model,
			'parentCategory'=>$parentCategory,
			'subCategory'=>$subCategory,
			'shippingTime' => $shippingTime, 
			'countryModel' => $countryModel,
			'topCurs' => $topCurs,
			'currencies' => $currencies, 
			'promotionCurrency'=>$promotionCurrency,
			'urgentPrice'=>$urgentPrice, 
			'promotionDetails'=>$promotionDetails, 
			'userModel'=>$userModel,
			'geoLocationDetails' => $geoLocationDetails,
			'shipping_country_code'=>$shipping_country_code,
			'givingaway' => $givingaway,
			'pricerange' =>$pricerange,
		]);
	}
	
	public function actionProductproperty(){
		if(isset($_POST)) {
			
			$categoryId = yii::$app->Myclass->checkPostvalue($_POST['selectedCategory']) ? $_POST['selectedCategory'] : "";
			$categoryModel = Categories::find()->where(['categoryId'=>$categoryId])->all();
			$categoryProperty = Json::decode($categoryModel[0]['categoryProperty'], true);
			$itemCondition = "";
			$itemConditionFlag = 0;
			$sitePaymentModes = yii::$app->Myclass->getSitePaymentModes();
			
			if(isset($_POST['productId']) && $_POST['productId'] != ""){
				$productModel = yii::$app->Myclass->getProductDetails($_POST['productId']);
				if(!empty($productModel) && $productModel->category == $_POST['selectedCategory']){
					$itemStatus = $productModel->productCondition;
					$exchangeToBuy = $productModel->exchangeToBuy;
					$myOffers = $productModel->myoffer;
					$instantBuy = $productModel->instantBuy;
				}
			}
			
			if ($categoryProperty['itemCondition'] == 'enable')
			{
				$itemCondition .= '<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<div class="form-group col-xs-12 col-sm-12 col-md-5 col-lg-5 no-hor-padding">
				<label class="Category-select-box-heading">'.Yii::t('app','Product Condition').'<span class="required">*</span></label>';
				
				
				$productConditions = Productconditions::find()->all();
				$itemCondition .= '<select id="Products_productCondition" class="form-control select-box-down-arrow" name="Products[productCondition]">';
				$itemCondition .= '<option value="">'.Yii::t('app','Select Product Condition').'</option>';
				foreach ($productConditions as $productCondition){
					
					if(isset($itemStatus) && $itemStatus == $productCondition->condition)
					{
						$select1 = "selected";
						$itemCondition .= '<option value="'.$productCondition->condition.'" '.$select1.'>'.Yii::t('app',$productCondition->condition).'</option>';
					}
					else
					{
						$select1 = "";	
						$itemCondition .= '<option value="'.$productCondition->condition.'" '.$select1.'>'.Yii::t('app',$productCondition->condition).'</option>';
					}
				}
				$itemCondition .= '</select>
				<div id="Products_productCondition_em_" class="errorMessage" style="display:none"></div>
				</div>
				</div>';
				$itemConditionFlag = 1;
			}
			else
			{
				$itemCondition .= '<input type="hidden" name="Products[productCondition]" value="" />';
			}
			
			if ($categoryProperty['exchangetoBuy'] == 'enable' && $sitePaymentModes['exchangePaymentMode'] == 1){
				$itemCondition .= '<div class="switch-box col-xs-4 col-sm-3 col-md-3 col-lg-4">
				<label class="Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">'.Yii::t('app','Exchange to buy').'</label>
				<div class="switch col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
				if(isset($exchangeToBuy) && $exchangeToBuy == 1){
					$itemCondition .= '
					<input id="Products_exchangeToBuy" class="cmn-toggle cmn-toggle-round" checked="checked" type="checkbox" name="Products[exchangeToBuy]" value="1">
					<label for="Products_exchangeToBuy"></label>
					</div>
					</div>';
				}else{
					$itemCondition .= '
					<input id="Products_exchangeToBuy" class="cmn-toggle cmn-toggle-round" type="checkbox" name="Products[exchangeToBuy]" value="1">
					<label for="Products_exchangeToBuy"></label>
					</div>
					</div>';
				}
				$itemConditionFlag = 1;
			}else{
				$itemCondition .= '<input type="hidden" name="Products[exchangeToBuy]" value="0" />';
			}
			
			if($_POST['givingAway'] == 0){
				if ($categoryProperty['myOffer'] == 'enable'){
					$itemCondition .= '<div class="switch-box col-xs-4 col-sm-3 col-md-3 col-lg-4">
					<label class="Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">'.Yii::t('app','Fixed Price').'</label>
					<div class="switch col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
					if(isset($myOffers) && $myOffers == 1){
						$itemCondition .= '
						<input id="Products_myoffer" class="cmn-toggle cmn-toggle-round" checked="checked" type="checkbox" name="Products[myoffer]" value="1">
						<label for="Products_myoffer"></label>
						</div>
						</div>';
					}else{
						$itemCondition .= '
						<input id="Products_myoffer" class="cmn-toggle cmn-toggle-round" type="checkbox" name="Products[myoffer]" value="1">
						<label for="Products_myoffer"></label>
						</div>
						</div>';
					}
					$itemConditionFlag = 1;
				}else{
					$itemCondition .= '<input type="hidden" name="Products[myoffer]" value="2" />';
				}
			} else {
				$itemCondition .= '<input type="hidden" name="Products[myoffer]" value="2" />';
			}
			
			if($_POST['givingAway'] == 0){
				if ($sitePaymentModes['buynowPaymentMode'] == 1 && $categoryProperty['buyNow'] == 'enable'){
					$itemCondition .= '<div class="switch-box col-xs-4 col-sm-3 col-md-3 col-lg-4">
					<label class="Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">'.Yii::t('app','Instant Buy').'</label>
					<div class="switch col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
					if(isset($instantBuy) && $instantBuy == 1){
						$itemCondition .= '
						<input id="Products_instantBuy" class="cmn-toggle cmn-toggle-round" checked="checked" type="checkbox" name="Products[instantBuy]" value="1">
						<label for="Products_instantBuy"></label>
						</div>
						</div>';
					}else{
						$itemCondition .= '
						<input id="Products_instantBuy" class="cmn-toggle cmn-toggle-round" type="checkbox" name="Products[instantBuy]" value="1">
						<label for="Products_instantBuy"></label>
						</div>
						</div>';
					}
					$itemConditionFlag = 1;
				}else if($sitePaymentModes['buynowPaymentMode'] == 1){
					$itemCondition .= '<input type="hidden" name="Products[instantBuy]" value="0" />';
				}
			} else {
				$itemCondition .= '<input type="hidden" name="Products[instantBuy]" value="0" />';
			}
			
			$subCategoryModel =  Categories::find()->where(['parentCategory'=>$categoryId])->all();
			$subCategory =  ArrayHelper::map($subCategoryModel, 'categoryId', 'name');
			
			
			
			
			$subCategoryOptions = "<option value=''>".Yii::t('app','Select Subcategory')."</option>";
			foreach ($subCategory as $key => $category){
				$subCategoryOptions .= "<option value='".$key."'>".Yii::t('app',$category)."</option>";
			}
			
			$sub_subCategoryOptions = "<option value=''>".Yii::t('app','Select child category')."</option>";
			
			$propertyData[] = $itemConditionFlag;
			$propertyData[] = $itemCondition;
			$propertyData[] = $subCategoryOptions;
			$propertyData[] = $sub_subCategoryOptions;
			if(count($subCategory) > 0)
			{
				$propertyData[] = 1;
			}else{
				$propertyData[] = 0;
			}
			$propertyDetails = Json::encode($propertyData);
			echo $propertyDetails;exit;
		}
	}
	



	public function actionSolditem() {
		if(isset($_POST)) {

			
			$id = $_POST['id'];
			$value = $_POST['value'];
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];

			if($value == 1) {
				$product = $this->loadModel($id);
				
				if($product->promotionType != 3){
					
					$promotionModel = Promotiontransaction::find()->where(['productId' => $id, 'status' => 'live'])->one();
					if(!empty($promotionModel)){
						if($promotionModel->promotionName != 'urgent'){
							
							$previousPromotion = Promotiontransaction::find()->where(['productId' => $id,'status' => 'Expired'])->one();
							if(!empty($previousPromotion)){
								$previousPromotion->status = "Canceled";
								$previousPromotion->save(false);
							}
						}
						$promotionModel->status = "Expired";
						$promotionModel->save(false);
					}
					$product->promotionType = 3;
				}

				$product->soldItem = 1;
				$product->save(false);
			} else {
				$product = $this->loadModel($id);
				$product->soldItem = 0;
				$product->quantity = 1;
				$product->save(false);
			}
			echo $id;
		}
	}


	public function actionView($id)
	{
		//echo '<pre>'; print_r($_SESSION); exit;
		//$ss = Productfilters::find()->where(['id'=>'1'])->one();
		//echo '<pre>'; print_r($ss); exit;
		$dec =  yii::$app->Myclass->safe_b64decode($id);
		$user = Yii::$app->user->id;
		$visitorDetails = yii::$app->Myclass->getUserDetailss($user);

		$spl = explode('-',$dec);
		$id = $spl[0];

		unset($_SESSION['deletefile'][$id]);
		unset($_SESSION['frontend_images']);


		$itemModel = $this->loadModel($id);
		if(isset($user) && $itemModel->userId != $user)
		{
			//Get insights by visitors.
			$insight_exists = json_decode($itemModel->insightUsers,true);
			$insightUsers[] = $user;

			if(!empty($insight_exists))
			{
				if(!in_array($user,$insight_exists)) {
					
					$real_insight = array_merge($insightUsers,$insight_exists);
					//$insightdata  = array_slice($real_insight,0,5);
					$insightdetl  = json_encode($real_insight);

					$itemModel->views++;
					$itemModel->insightUsers = $insightdetl;
					$itemModel->save(false);	
				}else{
					$itemModel->views++;
					$itemModel->save(false);
				}
			}else{
				$itemModel->views++;
				$itemModel->insightUsers = json_encode($insightUsers);
				$itemModel->save(false);
			}

			//echo '<pre>'; print_r($itemModel); exit;
			//$getFiltervalues = Productfilters::find()->where(['product_id'=>$itemModel])->one();
			//echo '<pre>'; print_r($visitorDetails); exit;

			//Insert visitor details.
			$userViewmodel = new Userviews;
			$userViewmodel->product_id = $id;
			$userViewmodel->user_id = $user;
			$userViewmodel->seller_id = $itemModel->userId;
			$userViewmodel->city = ($visitorDetails->city == '' || $visitorDetails->city == null) ? '': $visitorDetails->city;
			$userViewmodel->created_at = date('Y-m-d');
			$userViewmodel->save(false);	
		}
		
		$photoModel = Photos::find()->where(['productId' => $id])->all();
		$categoryModel = $itemModel->category;
		$subcategoryModel = $itemModel->subCategory;
		$userId = $itemModel->userId;

		if($itemModel->approvedStatus == 0)
		{
			if(!isset($user) && $user!=$itemModel->userId)
			{
				$homeUrl = Yii::$app->getUrlManager()->getBaseUrl().'/';
				Yii::$app->session->setFlash('info',Yii::t('app','Product is waiting for admin approval'));
				return $this->redirect($homeUrl);
			}
			else if(isset($user) && $user!=$itemModel->userId)
			{
				$homeUrl = Yii::$app->getUrlManager()->getBaseUrl().'/';
				Yii::$app->session->setFlash('info',Yii::t('app','Product is waiting for admin approval'));
				return $this->redirect($homeUrl);
			}
		}
		$commentModel = Comments::find()->where(['productId' => $id])->orderBy(['commentId' => SORT_DESC])->all();
		
		$totalItem = Products::find()->where(['userId' => $userId])->all();
		$followings = Followers::find()->where(['userId' => $userId])->all();
		$followers = Followers::find()->where(['follow_userId' => $userId])->all();
		$loguser = Yii::$app->user->id;
		$checkFollow = Followers::find()->where(['follow_userId' => $userId,'userId' => $loguser])->all();
		$popularadditems = Products::find()->where(['promotionType' => 1,'approvedStatus' => 1])->andWhere(['<>','productId',$id])->orderBy(new Expression('rand()'))->all();
		$count_popularadditems = count($popularadditems);
		$popularitems =  Products::find()->andWhere(['<>','promotionType',1])->andWhere(['=','approvedStatus',1])->andWhere(['<>','productId',$id])->orderBy(['likes' => SORT_DESC])->limit(4)->all();


		$recentlyprodcts = array();
		if($loguser) {
			
			$curruserdetails = Users::find()->where(['userId' => $loguser])->one();
			$loguserdetails = Users::find()->where(['userId' => $loguser])->one();
			
			if(empty($loguserdetails->recently_view_product)) {
				$prodctdata[] = $id;
				$prodctdetl = json_encode($prodctdata);
				$loguserdetails->recently_view_product = $prodctdetl;
				$loguserdetails->save(false);
			} else {
				$product_exists = json_decode($loguserdetails->recently_view_product,true);
				if(!in_array($id,$product_exists)) {
					$new_product[] = $id;
					$real_products = array_merge($new_product,$product_exists);
					$prodctdata = array_slice($real_products,0,5);
					$prodctdetl = json_encode($prodctdata);
					$loguserdetails->recently_view_product = $prodctdetl;
					$loguserdetails->save(false);
				}
			}
			$prodctIds = json_decode($curruserdetails->recently_view_product,true);
			$product_ids = array_diff($prodctIds,[$id]);
			$recentlyprodcts = Products::find()->where(['productId'=>$product_ids,'approvedStatus'=>'1'])->limit(4)->all();
		}

		$userModel = Users::find()->where(['userId'=>$userId])->one();
		$chatModel = Chats::find()->where(['user1' => $userModel->userId,'user2' => $user])->orWhere(['user1' => $user,'user2' => $userModel->userId])->one();
		
		$fav = array();
		$ownItems = array();
		$user = "";
		if(!Yii::$app->user->isGuest) {
			$user = Yii::$app->user->id;
			$fav = Favorites::find()->where(['userId' => $user,'productId' => $id])->one();
			$ownItems = Products::find()->andWhere(['=','userId',$loguser])->andWhere(['>','quantity',0])->andWhere(['=','soldItem',0])->andWhere(['=','approvedStatus',1])->orderBy(['productId' => SORT_DESC])->all();
		}
		/*
		$sameUserItems = Products::find()->with('photos')->select('productId','name','
			price','currency','quantity','soldItem')->andWhere(['=','userId',$itemModel->userId])->orderBy(['productId' => SORT_DESC])->all();
		*/

			$sameUserItems = Products::find()->with('photos')->where(['userId'=>$itemModel->userId])->orderBy(['productId' => SORT_DESC])->limit(0,8)->all();
			$prodids = [];
			
			if($itemModel->sub_subCategory != 0 || !empty($itemModel->sub_subCategory))
			{
				$thirdlevel_category = Products::find()->where([ 'sub_subCategory' =>$itemModel->sub_subCategory ])->andWhere(['=','approvedStatus',1])->andWhere(['<>','productId', $id])->orderBy(['productId' => SORT_DESC])->limit(8)->offset(0)->all();

				foreach ($thirdlevel_category as $key => $sub_subvalue) {
					$prodids[] = $sub_subvalue['productId'];
				}
			}


			if(empty($thirdlevel_category) || count($thirdlevel_category) < 8){
				if($itemModel->subCategory != 0 || !empty($itemModel->subCategory))
				{
					$secondlevel_category = Products::find()->where([ 'subCategory' =>$itemModel->subCategory ])->andWhere(['=','approvedStatus',1])->andWhere(['<>','productId', $id])->orderBy(['productId' => SORT_DESC])->limit(8)->offset(0)->all();
		}/*else{
			$secondlevel_category = Products::find()->where([ 'category' =>$itemModel->category ])->andWhere(['=','approvedStatus',1])->andWhere(['<>','productId', $id])->orderBy(['productId' => SORT_DESC])->limit(8)->offset(0)->all();
		}*/
		foreach ($secondlevel_category as $key => $subvalue) {
			if(count($prodids) < 8){
				if(count($prodids)>0){
					if (!in_array( $subvalue['productId'], $prodids)) 
						$prodids[] = $subvalue['productId'];
				}
				else
				{
					$prodids[] = $subvalue['productId'];
				}
			}
		}
	}

	
	if(empty($secondlevel_category) || count($secondlevel_category) < 8){
		$parentlevel_category = Products::find()->where([ 'category' =>$itemModel->category ])->andWhere(['=','approvedStatus',1])->andWhere(['<>','productId', $id])->orderBy(['productId' => SORT_DESC])->limit(8)->offset(0)->all();
		foreach ($parentlevel_category as $key => $parentvalue) {
			if(count($prodids) < 8){
				if(count($prodids)>0){
					if (!in_array( $parentvalue['productId'], $prodids)) 
						$prodids[] = $parentvalue['productId'];
				}
				else
				{
					$prodids[] = $parentvalue['productId'];
				}
			}
		}
	}
	$interestModel = Products::find()->where(['IN', 'productId', $prodids])->all();
	
	$offerModel = new MyOfferForm;
		//echo 'test'; exit;

		//echo '<pre>'; print_r($itemModel); exit;
	$getFiltervalues = Productfilters::find()->where(['product_id'=>$itemModel->productId])->all();

		//echo '<pre>'; print_r($getFiltervalues); exit;
	return $this->render('view',array(
		'model'=>$itemModel, 
		'photoModel'=>$photoModel, 
		'categoryModel'=>$categoryModel,
		'subcategoryModel'=>$subcategoryModel, 
		'userModel'=>$userModel, 
		'ownItems' => $ownItems,
		'commentModel'=>$commentModel, 
				//'newcommentModel'=>new Comments(),
		'filterValues'=>$getFiltervalues,
		'fav' => $fav,
		'offerModel' => $offerModel, 
		'sameUserItems' => $sameUserItems,
		'userinterested'=>$interestModel,
		'user' => $user,
		'itemCount' => count($totalItem), 
		'followingCount' => count($followings),
		'followerCount' => count($followers), 
		'checkFollow' => $checkFollow,
		'popularitems' => $popularitems, 
		'popularadditems' => $popularadditems,
		'count_popularadditems' => $count_popularadditems, 
		'recentlyprodcts' => $recentlyprodcts, 
		'chatModel' => $chatModel
	));
}



public function loadModel($id)
{
	$model=Products::find()->where(['productId' => $id])->one();
	
	if($model===null)
		throw new HttpException(404,'The requested page does not exist.');
	return $model;
}

public function actionStartfileupload()
{
	$image = array(); 
	$baseUrl = Yii::$app->request->baseUrl;
	$tot_cnt = count($_FILES["images"]["name"]);

	$cnt = 0;
	function compress($source, $destination, $quality) {

		$info = getimagesize($source);
		$compressFlag = 0;

		if ($info['mime'] == 'image/jpeg') {
			$image = imagecreatefromjpeg($source); 
			$compressFlag = 1;
		} elseif ($info['mime'] == 'image/png') {
			$image = imagecreatefrompng($source);
			$compressFlag = 1;
		}

		if($compressFlag == 1) {
			imagejpeg($image, $destination, $quality);
		} 
		return $compressFlag;
	}

	foreach ($_FILES["images"]["error"] as $key => $error) {
		if ($tocnt >= 5) {
			exit;
		} else {
			if ($error == UPLOAD_ERR_OK) {
				$name = $_FILES["images"]["name"][$key];

				$max_upload = 20971520;
				$filesize = filesize($_FILES["images"]["tmp_name"][$key]);
				if($filesize<=$max_upload)
				{
					$ext = strrchr($name, '.');
					$userid = Yii::$app->user->id;
					$random= rand(10, 1000);
					$newname = $random.time().$ext;

					$source_img = $_FILES["images"]["tmp_name"][$key];

					$path = Yii::$app->basePath . "/web/media/item/tmp" . "/";
					$destination_img = Yii::$app->basePath . "/web/media/item/tmp" . "/".$newname;

					if(!is_file($path .$newname )) { 
						$uploadFlag = 0;
						if($filesize>=50000)
						{
							$uploadFlag = compress($source_img, $destination_img, 70);
						} else {
							$info = getimagesize($source_img); 

							if (($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/png') && count($info) >=6) {  
								move_uploaded_file( $_FILES["images"]["tmp_name"][$key], $path .$newname);
								$uploadFlag = 1;
							}
						} 

						if($uploadFlag == 1) {
							chmod( $path .$newname, 0777 );
							array_push($image,$newname); 

							echo '<div class="uploaded_img align_middle margin_left10" style="float: inherit;"><img src="'.$baseUrl.'/media/item/tmp/'.$newname.'"" class="img-responsive"><button type="button" class="close post_img_cls" data-dismiss="modal" aria-label="Close" onclick="remove_images(this,\''.$newname.'\')"><span aria-hidden="true">×</span></button></div>';
						}
					}
					$tocnt++;
				} else if($filesize>$max_upload) {
					$cnt++;
				}
			} else {
				$cnt++; 
			}
		}
	}
	if($cnt==0 && count($image) > 0) 
	{
		echo "***"; echo json_encode($image);
	} else {
		echo "error";
	}
	return false; 	
}



function return_bytes($val) 
{
	$val = trim($val);
	$last = strtolower($val[strlen($val)-1]);
	switch($last) 
	{
		case 'g':
		$val *= 1024;
		case 'm':
		$val *= 1024;
		case 'k':
		$val *= 1024;
	}
	return $val;
}



public function actionPromotionstatus() {
	
	$userId = Yii::$app->user->id;


	$promotionType = $_POST['promotionType'];

	$productPromotionStatus = Products::findOne($_POST['productId']);
	if(empty($productPromotionStatus) || $productPromotionStatus->promotionType != 3){
		echo 0;die;
	}elseif ($productPromotionStatus->soldItem == 1){
		echo 1;die;
	} else {
		echo 2; die;
	}
}

public function actionPaymentprocess()
{

	$nonce = $_POST['payment_method_nonce'];
	$userId = $_POST['userId'];
	$userModel = Users::find()->where(['userId'=>$userId])->one();


	$siteSettings = Sitesettings::find()->where(['id'=>'1'])->one();
	$sitepaystatus = json_decode($siteSettings->braintree_settings,true);

	$bannerCurrency = $siteSettings->bannerCurrency;
	$currencyDetails = explode('-', $bannerCurrency);
	$bannerCurrency = trim($currencyDetails[0]);
	if($sitepaystatus['brainTreeType'] == 2)
		$payMode = "sandbox";
	else
		$payMode = "production";

	$params = array(
		"testmode"   => $payMode,
		"merchantid" => $sitepaystatus['brainTreeMerchantId'],
		"publickey"  => $sitepaystatus['brainTreePublicKey'],
		"privatekey" => $sitepaystatus['brainTreePrivateKey'],
	);
	
	Braintree\Configuration::environment($params["testmode"]);
	Braintree\Configuration::merchantId($params["merchantid"]);
	Braintree\Configuration::publicKey($params["publickey"]);
	Braintree\Configuration::privateKey($params["privatekey"]);

	$merchantAccountId = yii::$app->Myclass->getbraintreemerchantid($bannerCurrency);

	$totalCostValue = $_POST['amount']; 

	$merchant_account_id = yii::$app->Myclass->getbraintreemerchantid($_POST['currency_code']); 

	if(empty($userModel->braintree_cid)) {
		$result1 = Braintree\Customer::create([
			'firstName' => $userModel->name,
			'paymentMethodNonce' => $nonce
		]);
		
		$customer_id = $result1->customer->id;
		$result = Braintree\Transaction::sale([
			'paymentMethodToken' => $result1->customer->paymentMethods[0]->token,
			'amount' => $totalCostValue,
			'merchantAccountId' => $merchant_account_id,
			'options' => [
				'submitForSettlement' => True
			]
		]);
	} else {
		$customer_id = $userModel->braintree_cid;
		$result = \Braintree\Transaction::sale([
			'amount' => $totalCostValue,
			'merchantAccountId' => $merchant_account_id,
			'paymentMethodNonce' => $nonce,
			'options' => [
				'submitForSettlement' => True,
				'threeDSecure' => [
					'required' => true
				]
			]
		]);
	}				

	/** SUCCESS RESULT * */
	if ($result->success == '1' && !empty($customer_id)) { 

		if(empty($userModel->braintree_cid)) {							
			$userModel->braintree_cid = $customer_id;
			$userModel->save(false);
		}  

		$transaction = $result->transaction;
		$bannerModel = Banners::find()->where(['id'=> $_POST['item_number']])->one();
		$bannerModel->paidstatus=1;
		$bannerModel->paymentMethod ="Braintree";
		$bannerModel->tranxId = $transaction->id;
		$bannerModel->trackPayment = "Paid"; 
		$bannerModel->save(false);
		Yii::$app->session->setFlash('success', Yii::t('app','Banner added successfully.'));
		return $this->goHome();
	}
	else
	{
		Yii::$app->session->setFlash('success', Yii::t('app','Payment Failed, Please try again..')); 
		return $this->goHome();
	}

	/* new changes for offer status update process */
		//Yii::$app->session->setFlash('success', Yii::t('app','Banner added successfully..'));
        //return $this->goHome();
}

public function actionPromotionpaymentprocess(){

	$user = Yii::$app->user->id;
	
	if(Yii::$app->user->isGuest) {
		Yii::$app->session->setFlash("error",Yii::t('app','Access denied...!'));
		$this->redirect(array('/'));
	} else {

		$userId = Yii::$app->user->id;
		$promotionType = $_POST['BPromotionType'];
		
		$siteSettings = Sitesettings::find()->where(['id'=>'1'])->one();
		if($promotionType == "urgent" || $promotionType == "adds") {
			
			$productPromotionStatus = Products::find()->where(['productId'=>$_POST['BPromotionProductid']])->one();

			if(!empty($productPromotionStatus) && 
				$productPromotionStatus->promotionType == 3 &&
				$productPromotionStatus->soldItem == 0
			) {
				$promotionCurrency = $siteSettings->promotionCurrency;
			$currencyDetails = explode('-', $promotionCurrency);
			$promotionCurrency = trim($currencyDetails[0]);
			if ($promotionType == 'urgent'){
				$price = $siteSettings->urgentPrice;
				$customField = $promotionType."-_-".$promotionCurrency."-_-0-_-".$price."-_-".$userId;
				$customField = yii::$app->Myclass->cart_encrypt($customField, "pr0m0tion-det@ils");
			}else{
				$promotionId = $_POST['BPromotionid'];
				$promotionDetails = Promotions::find()->where(['id'=>$promotionId])->one(); 
				$customField = $promotionType."-_-".$promotionCurrency."-_-".$promotionDetails->days."-_-".$promotionDetails->price."-_-".$userId;
				$customField = yii::$app->Myclass->cart_encrypt($customField, "pr0m0tion-det@ils");
				$price = $promotionDetails->price;
			}
			$productCurrency = explode('-',$productPromotionStatus->currency);
			$userModel =Users::find()->where(['userId'=>$userId])->one();
			$sitepaystatus = json_decode($siteSettings->braintree_settings,true);

			

			if($sitepaystatus['brainTreeType'] == 2)
				$payMode = "sandbox";
			else
				$payMode = "production";

			$params = array(
				"testmode"   => $payMode,
				"merchantid" => $sitepaystatus['brainTreeMerchantId'],
				"publickey"  => $sitepaystatus['brainTreePublicKey'],
				"privatekey" => $sitepaystatus['brainTreePrivateKey'],
			);
			
			Braintree\Configuration::environment($params["testmode"]);
			Braintree\Configuration::merchantId($params["merchantid"]);
			Braintree\Configuration::publicKey($params["publickey"]);
			Braintree\Configuration::privateKey($params["privatekey"]);

			$merchantAccountId = yii::$app->Myclass->getbraintreemerchantid($promotionCurrency);
			
			if(empty($merchantAccountId)) {

				Yii::$app->session->setFlash("error",Yii::t('app','Something went wrong, please try again'));
				return $this->redirect(array('/user/profiles'));
			}

			try {
				if(empty($userModel->braintree_cid)) {
					$clientToken = Braintree\ClientToken::generate([
						"merchantAccountId" => $merchantAccountId 
					]);
				} else {
					$clientToken = Braintree\ClientToken::generate([
						"customerId" => $userModel->braintree_cid,
						"merchantAccountId" => $merchantAccountId 
					]);
				}
			} catch(Braintree_Exception_Authentication $e){
				
				Yii::$app->session->setFlash("error",Yii::t('app','Something went wrong, please try again'));
				return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
			}  catch(Exception $e) {
				Yii::$app->session->setFlash("error",Yii::t('app','Something went wrong or Admin payment credentialr, please try again'));
				return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
			} 
			
			$baseUrl = Yii::$app->request->baseUrl;

			
			return $this->renderPartial('promotionpaymentprocess', ['price'=>$price, 'promotionCurrency'=>$promotionCurrency, 'customField'=>$customField, 'baseUrl'=> $baseUrl, 'clienttoken'=> $clientToken, 'productId'=>$productPromotionStatus->productId, 'userId'=>$userId]);
		} else {
			Yii::$app->session->setFlash("error",Yii::t('app','Product not found.'));
			return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
		}
	} else {
				/*
				if($siteSettings->product_autoapprove==1)
			{
				Yii::$app->session->setFlash('success',Yii::t('app','Item promoted.'));
				return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
			}
			else
			{
				Yii::$app->session->setFlash('success',Yii::t('app','Item is promoted.'));
				return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
			}
				*/
			Yii::$app->session->setFlash("error",Yii::t('app','Access denied !'));
			return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
		}
	}
}

	/**
	 * Method to process the ipn from
	 * the paypal for the promotion payment
	 * process
	 *
	 */
	public function actionPromotionipnprocess(){
		$user = Yii::$app->user->id;
		if(Yii::$app->user->isGuest || count($_POST) < 6) {
			Yii::$app->session->setFlash("error",Yii::t('app','Access denied...!'));
			return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
		} else {
			$checkUID = Yii::$app->user->id;
			$userId = trim($_POST['userId']);

			if($checkUID == $userId) {
				

				$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

				$sitepaystatus = json_decode($siteSettings->braintree_settings,true);

				if($sitepaystatus['brainTreeType'] == 2)
					$payMode = "sandbox";
				else
					$payMode = "production";

				$params = array(
					"testmode"   => $payMode,
					"merchantid" => $sitepaystatus['brainTreeMerchantId'],
					"publickey"  => $sitepaystatus['brainTreePublicKey'],
					"privatekey" => $sitepaystatus['brainTreePrivateKey'],
				);

				/* POSTED VALUES */
				$custom = yii::$app->Myclass->cart_decrypt($_POST['custom'], "pr0m0tion-det@ils");
				$custom = explode('-_-', $custom);

				$nonce = $_POST["payment_method_nonce"];
				$currencyCode = trim($_POST['currency_code']);
				$totalCostValue = $custom[3];
				$itemId = trim($_POST['item_number']);
				
				$userModel = Users::find()->where(['userId'=>$userId])->one();
				
				Braintree\Configuration::environment($params["testmode"]);
				Braintree\Configuration::merchantId($params["merchantid"]);
				Braintree\Configuration::publicKey($params["publickey"]);
				Braintree\Configuration::privateKey($params["privatekey"]);

				$productModel = Products::find()->where(['productId'=>$itemId])->one();

				$productCurrency = explode('-',$productModel->currency);
				$merchant_account_id = yii::$app->Myclass->getbraintreemerchantid(trim($custom[1]));
				
				if(empty($merchant_account_id)) {
					Yii::$app->session->setFlash("success",Yii::t('app','Something went wrong, please try again'));
					return $this->redirect(array('/user/profiles'));
				}

				if(empty($userModel->braintree_cid)) {
					$result1 = Braintree\Customer::create([
						'firstName' => $userModel->name,
						'paymentMethodNonce' => $nonce
					]);
					
					$customer_id = $result1->customer->id;
					$result = Braintree\Transaction::sale([
						'paymentMethodToken' => $result1->customer->paymentMethods[0]->token,
						'amount' => $totalCostValue,
						'merchantAccountId' => $merchant_account_id,
						'options' => [
							'submitForSettlement' => True
						]
					]);
				} else {
					$customer_id = $userModel->braintree_cid;
					$result = \Braintree\Transaction::sale([
						'amount' => $totalCostValue,
						'merchantAccountId' => $merchant_account_id,
						'paymentMethodNonce' => $nonce,
						'options' => [
							'submitForSettlement' => True,
							'threeDSecure' => [
								'required' => true
							]
						]
					]);
				}				
				
				
				/** SUCCESS RESULT * */
				if ($result->success == '1' && !empty($customer_id)) {

					$transaction = $result->transaction;
					$createdDate = time();

					/*
					$getPromotion = Promotiontransaction::find()
					               ->where(['promotionName'=>$custom[0],'productId'=>$itemId])
					               ->one();
					*/
					               $promotionTranxModel = new Promotiontransaction();
					               $promotionTranxModel->promotionName = $custom[0];
					               $promotionTranxModel->promotionPrice = $custom[3];
					               $promotionTranxModel->promotionTime = $custom[2];
					               $promotionTranxModel->promotionCurrency = $currencyCode;
					               $promotionTranxModel->userId = $custom[4];
					               $promotionTranxModel->productId = $itemId;
					               $promotionTranxModel->tranxId = $transaction->id;
					               if($siteSettings->product_autoapprove==1)
					               {
					               	$promotionTranxModel->approvedStatus = 1;
					               	$promotionTranxModel->initial_check = 1;
					               	$promotionTranxModel->createdDate = $createdDate;
					               }
					               else
					               {
					               	$promotionTranxModel->approvedStatus = 0;
					               	$promotionTranxModel->initial_check = 0;
					               	$promotionTranxModel->createdDate = $createdDate;
					               }

					               $promotionTranxModel->save(false);
					               $promotionTranxId = $promotionTranxModel->id;

					               if($custom[0] != "urgent"){
					               	$adsPromotionDetailsModel = new Adspromotiondetails();
					               	$adsPromotionDetailsModel->productId = $itemId;
					               	$adsPromotionDetailsModel->promotionTime = $custom[2];
					               	$adsPromotionDetailsModel->promotionTranxId = $promotionTranxId;
					               	$adsPromotionDetailsModel->createdDate = $createdDate;

					               	$adsPromotionDetailsModel->save(false);
					               }

					               if($custom[0] == "urgent"){
					               	$productModel->promotionType = 2;
					               }else{
					               	$productModel->promotionType = 1;
					               }

					               $productModel->save(false);

					               $siteSettings = Sitesettings::find()->where(['id'=>'1'])->one();
					               $userModel = yii::$app->Myclass->getUserDetailss($productModel->userId);
					               $sellerEmail = $userModel->email;
					               $sellerName = $userModel->name;
					               
					               
					               
					               $mailer = Yii::$app->mailer->setTransport([
					               	'class' => 'Swift_SmtpTransport',
					               	'host' => $siteSettings['smtpHost'],  
					               	'username' => $siteSettings['smtpEmail'],
					               	'password' => $siteSettings['smtpPassword'],
					               	'port' => $siteSettings['smtpPort'], 
					               	'encryption' =>  'tls', 
					               ]);

					//jeya subha mail

					               try
					               {
					               	$ProductsMail = new Products();
					               	$ProductsMail->sendPromotionMail($sellerEmail,$userModel,
					               		$productModel,$productModel->name,$sellerName);
					               }

					               catch(\Swift_TransportException $exception)
					               {
						//Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
					               	return $this->redirect($_SERVER['HTTP_REFERER']);
					               }
					// $mail = new YiiMailer();
					// if($siteSettings->smtpEnable == 1) {
					// 	$mail->Mailer = 'smtp';                         
					// 	$mail->Host = $siteSettings->smtpHost; 
					// 	$mail->SMTPAuth = true;                         
					// 	$mail->Username = $siteSettings->smtpEmail;
					// 	$mail->Password = $siteSettings->smtpPassword;                          
					// 	if($siteSettings->smtpSSL == 1)
					// 		$mail->SMTPSecure = 'ssl';                            
					// 	$mail->Port = $siteSettings->smtpPort;
					// }
					// $mail->setView('promotionsuccessintimation');
					// $mail->setData(array('siteSettings' => $siteSettings,
					// 		'userModel' => $userModel,'productModel'=>$productModel,'productName'=>$productModel->name, 'sellerName' => $sellerName,
					// 		));
					// $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
					// $mail->setTo($sellerEmail);
					// $mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Your product promoted'));
					// $mail->send();

					               
					               

					               $userid = $productModel->userId;
					               
					               $userdevicedet = Userdevices::find()->where(['user_id'=>$userid])->all();
					               if(count($userdevicedet) > 0){
					               	foreach($userdevicedet as $userdevice){
					               		$deviceToken = $userdevice->deviceToken;
					               		$lang = $userdevice->lang_type;
					               		$badge = $userdevice->badge;
					               		$badge +=1;
					               		$userdevice->badge = $badge;
					               		$userdevice->deviceToken = $deviceToken;
					               		$userdevice->save(false);
					               		if(isset($deviceToken)){
					               			yii::$app->Myclass->push_lang($lang);
					               			if($custom[0] == "urgent"){
					               				$messages =  Yii::t('app','You have promoted your product')." ".$productModel->name." ".Yii::t('app','by')." ".$currencyCode.$custom[3];
					               			}else{
					               				$messages =  Yii::t('app','You have promoted your product')." ".$productModel->name." ".Yii::t('app','by')." ".$currencyCode.$custom[3]." for ".$custom[2]." ".Yii::t('app','days');
					               			}
					               			yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
					               		}
					               	}
					               }
					               if($productModel->approvedStatus==1)
					               {
					               	Yii::$app->session->setFlash("success",Yii::t('app','You have successfully promoted your product'));
					               }else{
					               	Yii::$app->session->setFlash("success",Yii::t('app','You have successfully promoted your product and waiting for admin approval'));
					               }

					               return $this->redirect(['/user/profiles']);
					               
					               return true;
					           } else { 
					           	return $this->redirect(['/checkout/Canceled']);
					           	return true;
					           } 
					       } else {
					       	Yii::$app->session->setFlash("error",Yii::t('app','Something went wrong, please try again'));
					       	return $this->redirect(['/']);
					       }
					   }
					}


					public function actionOfferstatus()
					{
						$encode=$_POST['messageId'];
						$encode1=base64_decode($encode);
		$str=base64_decode($encode1); //Get message id
		$arr=explode('@#@',$str);
		$msgId=$arr[0];
		$status=$arr[1];
		
		if($msgId!=0 && ($status=='accept' || $status=='decline'))
		{
			
			if($status=='accept')
			{
				$offerStatus=1;
				$message=Yii::t('app',"successfully Accepted this offer");
				$content="accepted";
			}
			else
			{
				$offerStatus=2;
				$message=Yii::t('app',"declined this offer");
				$content="declined";
			}

			$offerReceived = Messages::find()->where(['messageId' => $msgId])->one();
			$senderId=$offerReceived->senderId;
				$productId=$offerReceived->sourceId;//product Id
				$chatId=$offerReceived->chatId;//chatId
				$msg = json_decode($offerReceived->message, true);
				$offStatus=$msg['offerstatus'];
				$productModel = Products::find()->where(['productId' => $productId])->one();
				$receiverId = $productModel->userId;
				$checkBlockStatus = yii::$app->Myclass->getWhosBlock($senderId, $receiverId); //new changes
				if($checkBlockStatus==0){
					if($offStatus==0)
					{

						$offerMessage['message'] = $msg['message'];
						$offerMessage['price'] = $msg['price'];
						$offerMessage['currency'] = $msg['currency'];
				// New keys for my offer section
				$offerMessage['offerstatus'] = $offerStatus;// 0- pending,1- accept,2 -declined
				$offerMessage['type'] = 'sendreceive'; // sendreceive,accept,decline
				$offerMessage['msgsourceid'] = 0;
				$offerMessage['buynowstatus'] = 0; //0-pending,1 - buyed

				
				$offerMessage = json_encode($offerMessage);
				$offerReceived->message=$offerMessage;
				$offerReceived->save(false);
					// end my offer section

				$offerAccept['message'] = $msg['message'];
				$offerAccept['price'] = $msg['price'];
				$offerAccept['currency'] = $msg['currency'];
				// New keys for my offer section
				$offerAccept['offerstatus'] = $offerStatus;// 0- pending,1- accept,2 -declined
				$offerAccept['type'] = $status; // sendreceive,accept,decline
				$offerAccept['msgsourceid'] = $msgId;
				$offerAccept['buynowstatus'] = 0; //0-pending,1 - buyed
				$acceptEncode = json_encode($offerAccept);


				$messageModel = new Messages();
				$messageModel->message = $acceptEncode;
				$messageModel->messageType = "offer";
				$messageModel->senderId = $senderId;
				$messageModel->sourceId = $productId;
				$messageModel->chatId = $chatId;
				$messageModel->createdDate = time();
				$messageModel->save();
				//echo '1';
				echo $messageModel->messageId;//encode value


				
				$offPrice=yii::$app->Myclass->getFormattingCurrencyapi($msg['currency'],$msg['price']);
				$senderDetails = yii::$app->Myclass->getProductDetails($senderId);
				$siteSettings = Sitesettings::find()->where(['id' => SORT_DESC])->one();

					// notification  section 
				$notifyMessage = $content.' '.'your offer request on ' .$offPrice;
				$empty=0;
				$type="myoffer";
				
				$a=yii::$app->Myclass->addLogs($type, $receiverId, $senderId, $empty, $productId, $notifyMessage);
					//print_r($a);exit;
				/* notificaiton end */

				/* push notification */
				
				$userdevicedet = Userdevices::find()->where(['user_id' => $senderId])->all();
					$userdata = Users::find()->where(['userId' => $sellerId])->one();//seller id
					$currentusername = $userdata->name;
					
					if(count($userdevicedet) > 0){
						foreach($userdevicedet as $userdevice){
							$deviceToken = $userdevice->deviceToken;
							$lang = $userdevice->lang_type;
							$badge = $userdevice->badge;
							$badge +=1;
							$userdevice->badge = $badge;
							$userdevice->deviceToken = $deviceToken;
							$userdevice->save(false);
							if(isset($deviceToken)){
								yii::$app->Myclass->push_lang($lang);
								$messages = $currentusername." ".Yii::t('app',$content).' '.'your offer request on'." ".$offPrice." ".$productModel->name;
								yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
							}
						}
					}
					/* end push notification */

					

					
				}
				else
				{
					echo '0';
				}
				//new changes
			} else
			{	if($checkBlockStatus==1)
						echo "B11"; //Seller is blocked by you
						else
						echo "B12"; //You blocked this seller
				}  // new changes


			}
			else
			{
				echo '0';
			}
			
	//	echo $acceptEncode;

		}


		public function actionLike($id) {
			$userId = Yii::$app->user->id;
			$model = new Favorites();
			$model->userId = $userId;
			$model->productId = $id;
			if($model->save()) {
				$product = Products::find()->where(['productId' => $id])->one();
				$product->likes++;
				$product->save(false);

				$notifyMessage = 'liked your product';
				yii::$app->Myclass->addLogs("like", $userId, $product->userId, $model->id, $id, $notifyMessage);

				$userid = $product->userId;
				$userdata = Users::find()->where(['userId' => $userId])->one();
				$currentusername = $userdata->name;
				
				$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
				if(count($userdevicedet) > 0){
					foreach($userdevicedet as $userdevice){
						$deviceToken = $userdevice->deviceToken;
						$lang = $userdevice->lang_type;
						$badge = $userdevice->badge;
						$badge +=1;
						$userdevice->badge = $badge;
						$userdevice->deviceToken = $deviceToken;
						$userdevice->save(false);
						if(isset($deviceToken)){
							yii::$app->Myclass->push_lang($lang);
							$messages =$currentusername.' '.Yii::t('app','liked your product').' '.$product->name;
							yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
						}
					}
				}

			//  $logsModel = new Logs();
			// $logsModel->type = "like";
			// $logsModel->userid = Yii::$app->user->id;
			// $logsModel->notifyto = $product->userId;
			// $logsModel->notifymessage = $notifyMessage;
			// $logsModel->sourceid = $model->id;
			// $logsModel->createddate = time();
			// $logsModel->save(false); 

			/* $userid = $product->userId;
			$criteria = new CDbCriteria;
			$criteria->addCondition('user_id = "'.$userid.'"');
			$userdevicedet = Userdevices::model()->findAll($criteria);

			$userModel = Users::model()->findByPk($model->userId);
			if(count($userdevicedet) > 0){
			foreach($userdevicedet as $userdevice){
			$deviceToken = $userdevice->deviceToken;
			$badge = $userdevice->badge;
			$badge +=1;
			$userdevice->badge = $badge;
			$userdevice->deviceToken = $deviceToken;
			$userdevice->save(false);
			if(isset($deviceToken)){
				$messages = $userModel->username." liked on the product ".$product->name;
				Myclass::pushnot($deviceToken,$messages,$badge);
			}
		}
	} */

			//	Yii::app()->user->setFlash("success",'Item added to your favorites');
			//$this->redirect($_SERVER['HTTP_REFERER']);
	echo "1";
}
else
{
	echo "0";
}

		//echo $model->productId;
}

public function actionDislike($id) {
	$user = Yii::$app->user->id;
	
	$model = Favorites::find()->where(['userId' => $user,'productId' => $id])->one();
	$favouriteId = $model->id;
	$model->delete();

	$product = Products::find()->where(['productId' => $id])->one();
	$product->likes--;
	if($product->save(false))
	{
		
		//$logsModel = Logs::find()->where(['type'=>'like'])->andWhere(['sourceid' => $favouriteId])->one();
		//$logsModel->delete();
		echo "1";
	}
	else
	{
		echo "0";
	}
}

public function actionInitiatechat() {

	if (isset($_POST)){
		$senderId = yii::$app->Myclass->checkPostvalue($_POST['sender']) ? $_POST['sender'] : "";
		$receiverId = yii::$app->Myclass->checkPostvalue($_POST['receiver']) ? $_POST['receiver'] : "";
		$messageType = yii::$app->Myclass->checkPostvalue($_POST['messageType']) ? $_POST['messageType'] : "";
		$sourceId = yii::$app->Myclass->checkPostvalue($_POST['sourceId']) ? $_POST['sourceId'] : "";
		$timeUpdate = time();
		$message = $_POST['message'];
		$Products = Products::find()->where(['productId' => $sourceId])->one();
		if(isset($Products) && $Products->approvedStatus == 0)
		{
			echo "error";
		}
		else
		{

			$chatModel = Chats::find()->where(['user1' => $senderId,'user2' => $receiverId])->orWhere(['user1' => $receiverId,'user2' => $senderId])->one();

			$encodeMsg = urlencode($message);

			if (empty($chatModel)){
				$newChat = new Chats();
				$newChat->user1 = $senderId;
				$newChat->user2 = $receiverId;
				$newChat->lastMessage = $encodeMsg;
				$newChat->lastToRead = $receiverId;
				$newChat->lastContacted = $timeUpdate;

				$newChat->save(false);

				$chatModel = Chats::find()->where(['user1' => $senderId,'user2' => $receiverId])->orWhere(['user1' => $receiverId,'user2' => $senderId])->one();
			}
			$chatModel->lastContacted = $timeUpdate;
			if ($chatModel->user1 == $senderId){
				$chatModel->lastToRead = $chatModel->user2;
			}else{
				$chatModel->lastToRead = $chatModel->user1;
			}
			$chatModel->lastMessage = $encodeMsg;
			$chatModel->save(false);

			$messageModel = new Messages();
			$messageModel->message = $encodeMsg;
			$messageModel->messageType = $messageType;
			$messageModel->senderId = $senderId;
			$messageModel->sourceId = $sourceId;
			$messageModel->chatId = $chatModel->chatId;
			$messageModel->createdDate = $timeUpdate;
			$messageModel->save(false);


			$notifyMessage = 'contacted you on your product';
			yii::$app->Myclass->addLogs("myoffer", $senderId, $receiverId, $sourceId, $sourceId, $notifyMessage);

			$userid = $receiverId;
			$sellerDetails = yii::$app->Myclass->getUserDetailss($senderId);
			
			$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();


			if(count($userdevicedet) > 0){
				foreach($userdevicedet as $userdevice){
					$deviceToken = $userdevice->deviceToken;
					$badge = $userdevice->badge;
					$badge +=1;
					$userdevice->badge = $badge;
					$userdevice->deviceToken = $deviceToken;
					$userdevice->save(false);

					if(isset($deviceToken)){
						$messages = $sellerDetails->name." : ".$message;
						yii::$app->Myclass->pushnot($deviceToken,$messages,$badge,"message");
					}
				}
			}

			echo "success";
		}
	}
	else
	{
		echo "failed";
	}
}


public function actionSavecomment() {
	$model = new Comments();
	$userId = Yii::$app->user->id;
	$model->userId = $userId;
	$model->createdDate = time();
	$model->productId = $_POST['itemId'];
	$model->comment = $_POST['comment'];
	if ($model->save(false)){
		$userDetails = yii::$app->Myclass->getUserDetailss($model->userId);
		if(!empty($userDetails->userImage)) {
			$userImage = $userDetails->userImage;
		} else {
			$userImage = 'default/'.yii::$app->Myclass->getDefaultUser();
		}
		$productModel = Products::find()->where(['productId' => $_POST['itemId']])->one();
		$productModel->commentCount = $productModel->commentCount + 1;
		$productModel->save(false);
		if($userId != $productModel->userId)
		{
			$notifyMessage = 'comment on your product';
			yii::$app->Myclass->addLogs("comment", $userId, $productModel->userId, $model->commentId, $productModel->productId, $notifyMessage);
		}

		$userid = $productModel->userId;
		$userdata = Users::find()->where(['userId' => $userId])->one();
		$currentusername = $userdata->name;
		
		$userdevicedet = Userdevices::find()->where(['user_id' => $productModel->userId])->all();

		if(count($userdevicedet) > 0){
			foreach($userdevicedet as $userdevice){
				$deviceToken = $userdevice->deviceToken;
				$lang = $userdevice->lang_type;
				$badge = $userdevice->badge;
				$badge +=1;
				$userdevice->badge = $badge;
				$userdevice->deviceToken = $deviceToken;
				$userdevice->save(false);
				if(isset($deviceToken)){
					yii::$app->Myclass->push_lang($lang);
					$messages =$currentusername.' '.Yii::t('app','comment on your product').' '.$productModel->name;
					if($userId != $productModel->userId)
					{
						yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
					}
				}
			}
		}

			/* $logsModel = new Logs();
			$logsModel->type = "comment";
			$logsModel->userId = Yii::app()->user->id;
			$logsModel->notifyTo = $productModel->userId;
			$logsModel->notifyAbout = $productModel->productId;
			$logsModel->sourceId = $model->commentId;
			$logsModel->createdDate = time();
			$logsModel->save(false); */
			$count = strlen($userDetails->name);
			if($count > 20){
				$userName = substr($userDetails->name,0,20).'...';
			} else {
				$userName = $userDetails->name;
			}
			//print_r($userImage);exit;
			// echo '<li>
			// 		<div class="comment-box">
			// 			<div class="comment-image">'.Html::img(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userImage),['alt' => $userDetails->username]).'</div>
			// 			<div class="comment-user"><div class="comment-username"><a class="userNameLink" href="'.Yii::$app->urlManager->createAbsoluteUrl('user/profiles',array('id' => yii::$app->Myclass->safe_b64encode($userDetails->userId.'-'.rand(0,999)))).'">'.$userName.'
			// 				</a></div>
			// 				<div class="comment-time"><i class="fa fa-clock-o"></i> 0 seconds ago'.'
			// 				</div>
			// 			</div>
			// 			<div class="comment-message">'.$model->comment.'</div>
			// 		</div>
			// 	  </li>';

			$lang = $_SESSION['language'];
			yii::$app->Myclass->push_lang($lang);
			echo '<div class="comment col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="cmt-'.$model->commentId.'">';
			if(!empty($userDetails->userImage)) {
				$user_profile = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userDetails->userImage);
			} else {
				$user_profile = Yii::$app->urlManager->createAbsoluteUrl('media/logo/' . yii::$app->Myclass->getDefaultUser());
			}

			echo '<a href="'.Yii::$app->urlManager->createAbsoluteUrl('user/profiles',array('id' => yii::$app->Myclass->safe_b64encode($userDetails->userId.'-'.rand(0,999)))).'"><div class="comment-profile-default icon col-xs-2 col-sm-2 col-md-1 col-lg-1 no-hor-padding" style="background: rgba(0, 0, 0, 0) url('.$user_profile.') no-repeat scroll center center / cover; border-radius:20px; "></div></a>
			<div class="comment-content icon col-xs-10 col-sm-10 col-md-11 col-lg-11 no-hor-padding">
			<div class="comment-user-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><a href="'.Yii::$app->urlManager->createAbsoluteUrl('user/profiles',array('id' => yii::$app->Myclass->safe_b64encode($userDetails->userId.'-'.rand(0,999)))).'">'.$userDetails->name.'</a>
			<a class="pull-right" href="javascript:void(0);" onclick="deletecomment('.$model->commentId.');">'.Yii::t('app','Delete').'</a>
			</div>';
			echo '<div class="comment-text col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<p>'.$model->comment.'</p>
			<div class="comment-timing-detail col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<p>0  seconds'.'</p>
			</div>
			</div>
			</div>
			</div>';die;


			/* $userid = $productModel->userId;
			$criteria = new CDbCriteria;
			$criteria->addCondition('user_id = "'.$userid.'"');
			$userdevicedet = Userdevices::model()->findAll($criteria);

			if(count($userdevicedet) > 0){
					foreach($userdevicedet as $userdevice){
			$deviceToken = $userdevice->deviceToken;
			$badge = $userdevice->badge;
			$badge +=1;
			$userdevice->badge = $badge;
			$userdevice->deviceToken = $deviceToken;
			$userdevice->save(false);
			if(isset($deviceToken)){
				$messages = $userDetails->username." commented on the product - ".$productModel->name;
				Myclass::pushnot($deviceToken,$messages,$badge);
			}
			}
		} */
	}else{
			//echo "false";
	}
	//	echo 0;
}


public function actionDeletecomment()
{
	$commentId = $_POST['commentId'];
		//echo $commentId;
	
	$commentModel = Comments::find()->where(['commentId' => $commentId])->one();
	$commentModel->delete();die;

	
	
	/*		$logsModel = Logs::find()->where(['type' => 'comment','sourceId' => $commentId])->one();

if (!empty($logsModel)) {
			if(!empty($logsModel))
			{
		$logsModel->delete();
	}
}*/

}


public function actionMyoffer() {

		//echo "0";
	$model = new MyOfferForm;



	if(isset($_POST)) {
		$offerRate = $_POST['offerRate'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['message'];
		$phone = $_POST['phone'];
		$productId = $_POST['productId'];
		$sellerDetails = yii::$app->Myclass->getUserDetailss($_POST['sellerId']);
		$sellerEmail = $sellerDetails->email;
		$sellerName = $sellerDetails->name;
			//$userModel = Myclass::getUserDetails($_POST['buyerId']);
		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$productModel = Products::find()->where(['productId' => $productId])->one();

		if(isset($productModel) && $productModel->approvedStatus == 0)
		{
			echo "error";
		}
		else
		{

			$senderId = Yii::$app->user->id;
			$receiverId = $_POST['sellerId'];
				//echo $receiverId;
					$checkBlockStatus = yii::$app->Myclass->getWhosBlock($senderId, $receiverId); //new changes
					if($checkBlockStatus==0){

						$productURL = Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($productModel->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($productModel->name);
						
					// Yii::$app->urlManager->createAbsoluteUrl('products/view',array(
					// 'id' => yii::$app->Myclass->safe_b64encode($productModel->productId.'-'.rand(0,999)))).'/'.
					// yii::$app->Myclass->productSlug($productModel->name);
						

						if(!Yii::$app->user->isGuest) {
							
							$timeUpdate = time();
				// $criteria = new CDbCriteria;
				// $criteria->condition = "(user1 = '$senderId' AND user2 = '$receiverId') OR (user1 = '$receiverId' AND user2 = '$senderId')";

							$chatModel =  Chats::find()->where(['user1' => $senderId,'user2' => $receiverId])->orWhere(['user1' => $receiverId,'user2' => $senderId])->one();

							

							if (empty($chatModel)){
								$senderDetails = yii::$app->Myclass->getUserDetailss($senderId);
								$newChat = new Chats();
								$newChat->user1 = $senderId;
								$newChat->user2 = $receiverId;
								$newChat->lastMessage = "Offer from ".$senderDetails->name;
								$newChat->lastToRead = $receiverId;
								$newChat->lastContacted = $timeUpdate;

								$newChat->save(false);

					// $criteria = new CDbCriteria;
					// $criteria->condition = "(user1 = '$senderId' AND user2 = '$receiverId') OR (user1 = '$receiverId' AND user2 = '$senderId')";

								$chatModel = Chats::find()->where(['user1' => $senderId,'user2' => $receiverId])->orwhere(['user1' => $receiverId,'user2' => $senderId])->one();
								
							}
							$chatModel->lastContacted = $timeUpdate;
							if ($chatModel->user1 == $senderId){
								$chatModel->lastToRead = $chatModel->user2;
							}else{
								$chatModel->lastToRead = $chatModel->user1;
							}
							$chatModel->lastMessage = $message;
							$chatModel->save(false);
				//exit;

							$offerMessage['message'] = $message;
							$offerMessage['price'] = $offerRate;
							$offerMessage['currency'] = $productModel->currency;
				// New keys for my offer section
							$offerMessage['offerstatus'] = 0;
				$offerMessage['type'] = 'sendreceive'; // sendreceive,accept,decline
				$offerMessage['msgsourceid'] = 0;
				$offerMessage['buynowstatus'] = 0; //0-pending,1 - buyed

				// end my offer section
				$offerMessage = json_encode($offerMessage);

				$messageModel = new Messages();
				$messageModel->message = $offerMessage;
				$messageModel->messageType = "offer";
				$messageModel->senderId = $senderId;
				$messageModel->sourceId = $productId;
				$messageModel->chatId = $chatModel->chatId;
				$messageModel->createdDate = $timeUpdate;
				$messageModel->save(false);
				$notifyofferprice = yii::$app->Myclass->getFormattingCurrencyapi($productModel->currency,$offerRate);

				$notifyMessage = 'sent offer request '.$notifyofferprice.' on your product';
				yii::$app->Myclass->addLogs("myoffer", $senderId, $receiverId, 0, $productId, $notifyMessage);
			}

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

			$mailer = Yii::$app->mailer->setTransport([
				'class' => 'Swift_SmtpTransport',
				'host' => $siteSettings['smtpHost'],  
				'username' => $siteSettings['smtpEmail'],
				'password' => $siteSettings['smtpPassword'],
				'port' => $siteSettings['smtpPort'], 
				'encryption' =>  'tls', 
			]);
			try
			{
				$messageModel = new Messages();
				$messageModel->sendEmail($sellerEmail,$name,$email,$phone,$offerRate, $message, $sellerName,
					$_POST['currency'], $productURL);
			}

			catch(\Swift_TransportException $exception)
			{
			   //Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
		   		//hideby207
			  //return $this->redirect($_SERVER['HTTP_REFERER']);
			}
			// $mail = new YiiMailer();
			// if($siteSettings->smtpEnable == 1) {
			// 	//$mail->IsSMTP();                         // Set mailer to use SMTP
			// 	$mail->Mailer = 'smtp';                         // Set mailer to use SMTP
			// 	$mail->Host = $siteSettings->smtpHost;  // Specify main and backup server
			// 	$mail->SMTPAuth = true;                               // Enable SMTP authentication
			// 	$mail->Username = $siteSettings->smtpEmail;                            // SMTP username
			// 	$mail->Password = $siteSettings->smtpPassword;                           // SMTP password
			// 	if($siteSettings->smtpSSL == 1)
			// 		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
			// 	$mail->Port = $siteSettings->smtpPort;
			// }
			// $mail->setView('myofferintimation');
			// $mail->setData(array('name' => $name, 'email' => $email,'phone' => $phone,
			// 		'offerRate' => $offerRate, 'message'=> $message, 'sellerName' => $sellerName,
			// 	'siteSettings' => $siteSettings,'currency' => $_POST['currency'], 'productURL'=>$productURL));
			// $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
			// $mail->setTo($sellerEmail);
			// $mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Offer Intimation Mail'));
			$userid = $_POST['sellerId'];
			
			//$criteria->addCondition('user_id = "'.$userid.'"');
			$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
			$userdata = Users::find()->where(['userId' => $senderId])->one();
			$currentusername = $userdata->name;
			if(count($userdevicedet) > 0){
				foreach($userdevicedet as $userdevice){
					$deviceToken = $userdevice->deviceToken;
					$lang = $userdevice->lang_type;
					$badge = $userdevice->badge;
					$badge +=1;
					$userdevice->badge = $badge;
					$userdevice->deviceToken = $deviceToken;
					$userdevice->save(false);

					if(isset($deviceToken)){
						yii::$app->Myclass->push_lang($lang);
						$messages = $currentusername." ".Yii::t('app','sent offer request')." ".$notifyofferprice." ".Yii::t('app','on your product')." ".$productModel->name;
						yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
					}
				}
			}

			 // if ($mail->send()) {
			 // 	return true;
			 // } else {
			 // 	echo $mail->getError();die;
			 // }
			/* start new */
		} 
		else
			{	if($checkBlockStatus==1)
						echo "11"; //Seller is blocked by you
						else
						echo "12"; //You blocked this seller
				}
				/* end new */
			}// else end
		}
	}


	public function actionUpdate($id)
	{
		//print_r($id);
		$dec = yii::$app->Myclass->safe_b64decode($id);
		$spl = explode('-',$dec);
		$id = $spl[0];
		if(count($spl) <= 1){
			return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
		}
		// Yii::import( "xupload.models.XUploadForm" );
		$models = new Photos();
		$photos = Photos::find()->where(['productId' => $id])->all();
		$plen = count($photos);

		$model=$this->loadModel($id);

		$userId = Yii::$app->user->id;
		if($model->userId != $userId) {
			return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/');
		}
		$parentCategory = array();
		$parentCategory = Categories::find()->where(['parentCategory'=>0])->all();
		if (!empty($parentCategory)){
			$parentCategory = ArrayHelper::map($parentCategory, 'categoryId', 'name');
		}

		$getFilters = Productfilters::find()->where(['product_id'=>$id])->all();
		//echo '<pre>'; print_r($getFilters); exit;
		//	$subCategory = array();
		$subCategory = Categories::find()->where(['parentCategory'=>$model->category])->all();
		if (!empty($subCategory)){
			$subCategory = ArrayHelper::map($subCategory, 'categoryId', 'name');
		}
		
		$sub_subCategory = Categories::find()->where(['parentCategory'=>$model->subCategory])->all();
		if (!empty($sub_subCategory)){
			$sub_subCategory = ArrayHelper::map($sub_subCategory, 'categoryId', 'name');
		}

//print_r($subCategory);exit;
		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

		$shippingTime['1 business day'] = '1 business day';
		$shippingTime['1-2 business day'] = '1-2 business day';
		$shippingTime['2-3 business day'] = '2-3 business day';
		$shippingTime['3-5 business day'] = '3-5 business day';
		$shippingTime['1-2 weeks'] = '1-2 weeks';
		$shippingTime['2-4 weeks'] = '2-4 weeks';
		$shippingTime['5-8 weeks'] = '5-8 weeks';

		$countryModel = array();
		$countryList = Country::find()->where(['!=','countryId',0])->all();
		
		if (!empty($countryList)){
			//$countryModel = CHtml::listData($countryList, 'countryId', 'country');
			foreach ($countryList as $country){
				$countryKey = $country->countryId."-".$country->country;
				$countryModel[$countryKey] = $country->country;
			}
		}

		$shipping_country_code = "";
		if($model->instantBuy == "1")
		{
			$shipping_country_code = yii::$app->Myclass->getCountryCode($model->shippingcountry);

		}else{
			if(isset($model->shippingcountry) && $model->shippingcountry != 0)
			{
				$shipping_country_code = yii::$app->Myclass->getCountryCode($model->shippingcountry);
			}
			else
			{
				$place = $model->location;
				$places = explode(",",$place);
				$countryname = trim(end($places));
				//print_r($countryname);exit;
				// $countrydata = new CDbCriteria;
				// $countrydata->addCondition("country LIKE '$countryname'");
				$countrylist = Country::find()->where(['=','country',$countryname])->one();
				//print_r($countrylist);exit;
				$shipping_country_code = $countrylist->code;
			//print_r($shipping_country_code);exit;
			}
		}
		
		$options = array();
		if (!empty($model->sizeOptions)){
			$options = json_decode($model->sizeOptions, true);
		}

		// $shippingModel = $model->shippings;
		// $jsShippingDetails = '';
		// $itemShipping = array();
		// foreach ($shippingModel as $shippingDetail) {
		// 	$itemShipping[$shippingDetail->countryId] = $shippingDetail->shippingCost;
		// 	if(empty($jsShippingDetails)){
		// 		$jsShippingDetails .= '"'.$shippingDetail->countryId.'"';
		// 	}else{
		// 		$jsShippingDetails .= ',"'.$shippingDetail->countryId.'"';
		// 	}
		// }

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Products']))
		{
			
			$productData = $_POST['Products'];
			$model->attributes=$_POST['Products'];
			//echo "<pre>";print_r($model->attributes);die;
			$model->name = htmlentities($model->name);
			$model->description = htmlentities($model->description);
			$model->filters = json_encode($_POST['Products']['attributes']);

			//$model->chatAndBuy = $_POST['Products']['chatAndBuy'];
			$model->exchangeToBuy = 0;
			if(isset($_POST['Products']['exchangeToBuy']))
				$model->exchangeToBuy = $_POST['Products']['exchangeToBuy'];

			if(isset($_POST['giving_away'])) {
				$model->price = 0;
			}

			if (isset($_POST['Products']['shippingcountry']) && $_POST['Products']['shippingcountry'] != '') {
				$model->shippingcountry = yii::$app->Myclass->getCountryId($_POST['Products']['shippingcountry']);
			}
			
		//	print_r($_POST['Products']['shippingcountry']);exit;
			$model->instantBuy = 0;
			if(isset($_POST['Products']['instantBuy']) && (isset($_POST['giving_away'])=="" || isset($_POST['giving_away']) == '0')) {
				$model->instantBuy = $_POST['Products']['instantBuy'];

				$model->shippingcountry = yii::$app->Myclass->getCountryId($_POST['Products']['shippingcountry']);
				//print_r($model->shippingcountry);exit;
				$model->shippingCost = $_POST['Products']['shippingCost'];
			}

			$model->myoffer = 0;
			if(isset($_POST['Products']['myoffer']))
				$model->myoffer = $_POST['Products']['myoffer'];
			//$model->shippingTime = $_POST['Products']['shippingTime'];
			$model->currency = $_POST['Products']['currency'];
			$model->subCategory = $_POST['Products']['subCategory'];
			$model->sub_subCategory = $_POST['Products']['sub_subCategory'];

			//$model->sessionId = $_POST['Products']['uploadSessionId'];
			//echo $model->sessionId."<pre>";print_r($_SESSION);print_r($model->attributes);die;

			if($siteSettings->product_autoapprove==1)
			{
				$model->approvedStatus = 1;
				Yii::$app->session->setFlash('success',Yii::t('app','Product Updated successfully'));
			}
			else
			{
				$model->approvedStatus = 0;
				Yii::$app->session->setFlash('success',Yii::t('app','Information updated, It is waiting for admin approval'));
			}

			if (isset($productData['productOptions'])){
				$model->sizeOptions = json_encode($productData['productOptions']);
				$quantity = 0;
				$optionPrice = 0;
				foreach($productData['productOptions'] as $options){
					$quantity += $options['quantity'];
					$optionPrice = $optionPrice == 0 && !empty($options['price']) ? $options['price'] : $optionPrice;
				}
				$model->quantity = $quantity;
				$model->price = $optionPrice != 0 ? $optionPrice : $model->price;
			} else {
				$model->sizeOptions = '';
			}

			//removing files

			$rmfilenames = $_POST['removefiles'];
			$rmtemp = explode(',', $rmfilenames);
			foreach ($rmtemp as $value) {
				$photosModel = Photos::find()->where(['name' =>$value])->one();
				$path = Yii::$app->basePath ."/web/media/item/".$model->productId."/"."/";
				$file = $path.$value;
				if( is_file( $file ) ) {
					unlink( $file );
				}
				if(!empty($photosModel))
					$photosModel->delete();
			}

			//Uploading images

			$filenames = json_decode($_POST['uploadedfiles'],true);
			
			//print_r($filenames);exit;
			
			for($i=0;$i<count($filenames);$i++)
			{
				$photoss = new Photos();
				$photodata = $photoss::find()->where(['name'=>$filenames[$i]])->one();
				if(!$photodata)	{						
					$path = realpath ( Yii::$app->basePath . "/web/media/item/" )."/".$model->productId . "/";
					$tmp_path = realpath ( Yii::$app->basePath . "/web/media/item/tmp/" )."/".$filenames[$i];
					if( !is_dir( $path ) ) {
						FileHelper::createDirectory($path);
						chmod( $path, 0777 );
					}
					if( is_file( $tmp_path ) ) {
							//chmod( $tmp_path, 0777 );
						if( rename( $tmp_path, $path.$filenames[$i] ) ) {

							$watermark = yii::$app->Myclass->getWatermark();

							$watermarkImage = Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark);
							$image = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$model->productId.'/'.$filenames[$i]);
							list($widthh,$heightt) = getimagesize($image);

							$imagine = Image::getImagine();
							$imagine = $imagine->open(Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark));
							$sizes = getimagesize(Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark));

							
							
                       $imageHeightBild = $heightt; //height of the image to watermark
                       $imageWidthBild = $widthh; //height of the image to watermark
                       
                       if($widthh <= $heightt) {
                       	$ratioBrand = $sizes[1] / $sizes[0];
                       	//Portrait
                       	$imageHeightTmpBranding = $imageHeightBild * 0.08; 
                       	//height of watermark, preserve aspect ratio
                       	$imageWidthTmpBranding = $imageHeightTmpBranding / $ratioBrand; 
                       } else {  
                       	$ratioBrand = $sizes[0] / $sizes[1];
                       	$imageWidthTmpBranding = $imageWidthBild * 0.05; //the watermark width, but only 20% size of the image to watermark
                       	$imageHeightTmpBranding = $imageWidthTmpBranding / $ratioBrand;
                       	//height of watermark, preserve aspect ratio
                       }
                       

                       $imagine = $imagine->resize(new Box($imageWidthTmpBranding, $imageHeightTmpBranding))->save(Yii::getAlias('@webroot/media/item/'.$model->productId.'/watermark.png', ['quality' => 60]));
                       
                       $watermarkfile=Yii::getAlias('@webroot/media/item/'.$model->productId.'/watermark.png');

                       $dest_x = intval($imageWidthBild - $imageWidthTmpBranding - 25); 
                       $dest_y = intval($imageHeightBild - $imageHeightTmpBranding - 25);   
                       $position = array($dest_x,$dest_y);

                       $newImage = Image::watermark($image, $watermarkfile, $position);
                       $newImage->save(Yii::getAlias('@webroot/media/item/'.$model->productId.'/'.$filenames[$i], ['quality' => 60]));
                       
                       unlink($watermarkfile); 

                       chmod( $path.$filenames[$i], 0777 );
                       $photoss->productId = $model->productId;
                       $photoss->name = $filenames[$i]; 
                       $photoss->createdDate = time();
                       $photoss->save(false);
                   }
               }
           }
       }
       


			//Shipping::find()->deleteAllByAttributes(array('productId'=>$model->productId));
       if(isset($productData['shipping'])) {
       	foreach ($productData['shipping'] as $key => $shipping){
       		if($shipping != ""){
       			$shippingModel = new Shipping();
       			$shippingModel->productId = $model->productId;
       			$shippingModel->countryId = $key;
       			$shippingModel->shippingCost = $shipping;
       			$shippingModel->createdDate = time();
       			$shippingModel->save();
       		}
       	}
       }
       if($model->save(false)) {

       	$lin=yii::$app->Myclass->safe_b64encode($model->productId.'-'.rand(0,999));

       	$redirectUrl = Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.$lin.'/'.yii::$app->Myclass->productSlug($model->name);
       	


				//Delete product filters by using product id.
       	Productfilters::deleteAll(['product_id' => $model->productId]);

       	$getPostattributes = $_POST['Products']['attributes'];
						//echo '<pre>'; print_r($getPostattributes); exit;
       	foreach($getPostattributes as $attrKey=>$attrVal)
       	{
       		if(empty($attrKey))
       			continue;
       		if($attrKey != 'multilevel')
       		{

       			$filterGet = Filter::find()->where(['id'=>$attrKey])->one();
       			$productvals = Filtervalues::find()->where(['id'=>$attrVal])->one();

       			if($filterGet->type == 'dropdown')
       			{
       				$levelOne = $attrKey;
       				$levelTwo = $attrVal;
       				$levelThree = 0;
       				$pro_value = $productvals->name;
       			}elseif($filterGet->type == 'range')
       			{
       				$levelOne = $attrKey;
       				$levelTwo = $attrVal;
       				$levelThree = 0;
       				$pro_value = $attrVal;
       			}elseif($filterGet->type == 'multilevel')
       			{
       				$levelOne = $attrKey;
       				$levelTwo = $attrVal;
       				$levelThree = $getPostattributes['multilevel'][$levelTwo];

									//echo '<pre>'; print_r($levelTwo); exit;
       				$getlevel2val = Filtervalues::find()->where(['id'=>$levelTwo])->one();

       				$getlevel3val = Filtervalues::find()->where(['id'=>$levelThree])->one();

       				$pro_value = $getlevel2val->name.', '.$getlevel3val->name;
       			}

       			$productAttribute = new Productfilters;
       			$productAttribute->product_id = $model->productId;
       			$productAttribute->category_id = $_POST['Products']['category'];
       			$productAttribute->subcategory_id = ($_POST['Products']['subCategory'] == '') ? '0' : $_POST['Products']['subCategory'];
       			$productAttribute->sub_subcategory_id = ($_POST['Products']['sub_subCategory'] == '') ? '0' : $_POST['Products']['sub_subCategory'];
       			$productAttribute->filter_id = $attrKey;
       			$productAttribute->level_one = $levelOne;
       			$productAttribute->level_two = $levelTwo;
       			$productAttribute->level_three = $levelThree;
       			$productAttribute->filter_name = $filterGet->name;
       			$productAttribute->filter_type = $filterGet->type;
       			$productAttribute->filter_values = $pro_value;
       			$productAttribute->filtervalue_id = 0;
       			
       			$productAttribute->save(false);
									//echo 'tcsest'; die;
       			
       		}
       		
       	}
       	
       	return $this->redirect($redirectUrl);
       }
       else
       {
       	Yii::$app->session->setFlash('error',Yii::t('app','Something went wrong.'));
       }
   }else{
   	unset($_SESSION['deletefile'][$id]);
   	
   }
   $currencies = Currencies::find()->all();
   $currencyPrority=Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
   $topFiveCur = $currencyPrority->currency_priority;
   $givingawaydata = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
   $givingaway=$givingawaydata->givingaway;
   $pricerange=json_decode($siteSettings->pricerange);
   $topFive = Json::decode($topFiveCur);
   foreach($topFive as $top):
   	$topCurs[] = Currencies::find()->where(["id" => $top]);
   endforeach;


   $model->name = html_entity_decode($model->name);
   $model->description = html_entity_decode($model->description);
   if(isset($_POST['Products']['instantBuy'])){
   	$model->shippingcountry = yii::$app->Myclass->getCountryCode($model->shippingcountry);
   }else{
   	$model->shippingcountry = "0";
   }

   $promotionDetails = Promotions::find()->all();

   $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
   $urgentPrice = $siteSettings->urgentPrice;
   $promotionCurrency = $siteSettings->promotionCurrency;



		//, 'itemShipping' => $itemShipping, 'jsShippingDetails' => $jsShippingDetails,'shippingCountry'=>$shippingCountry,

		//$loadFilter = Categories::find()->where(['categoryId'=>$model->category])->one();
		//$loadAttributes = explode(',',  $loadFilter->categoryAttributes);

   $parentfieldOption = Categories::find()->where(['categoryId' => $model->category])->one();
   $subcatfieldOption = Categories::find()->where(['categoryId' => $model->subCategory])->one();
   $sub_subcatfieldOption = Categories::find()->where(['categoryId' => $model->sub_subCategory])->one();

   $sub_cat_name = '';
   if(!empty($sub_subcatfieldOption)){
   	$sub_cat_name = $sub_subcatfieldOption->name;
   }


   $getcateAttributes = explode(',', $parentfieldOption->categoryAttributes);
   $getsubcateAttributes = explode(',', $subcatfieldOption->categoryAttributes);
   $getsub_subcateAttributes = explode(',', $sub_subcatfieldOption->categoryAttributes);

   $filterValues = array_filter(array_merge($getcateAttributes, $getsubcateAttributes, $getsub_subcateAttributes));

   
		//$getAttributes = explode(',',array_unique(array_merge($getcateAttributes, $getsubcateAttributes)));
		//$getAttributes = explode(',', $getAttributes);

		//Get category attributes.
		/*
		if($parentfieldOption->categoryAttributes != '' && $subcatfieldOption->categoryAttributes != '')
		{
			$getcateAttributes = explode(',', $parentfieldOption->categoryAttributes);
			$getsubcateAttributes = explode(',', $subcatfieldOption->categoryAttributes);
			$getAttributes = array_unique(array_merge($getcateAttributes, $getsubcateAttributes));
			$getAttributes = explode(',', $getAttributes);
		}elseif($parentfieldOption->categoryAttributes != '' && $subcatfieldOption->categoryAttributes == '')
		{
			$getAttributes = explode(',', $parentfieldOption->categoryAttributes);
		}elseif($parentfieldOption->categoryAttributes == '' && $subcatfieldOption->categoryAttributes != '')
		{
			$getAttributes = explode(',', $subcatfieldOption->categoryAttributes);
		}
		*/
		return $this->render('update',array(
			'model'=>$model, 
			'parentCategory'=>$parentCategory,
			'subCategory'=>$subCategory,
			'sub_subCategory' => $sub_subCategory,
				//'filtermodel'=>$filterValues,
			'attributes'=>$filterValues,
			'photos' => $photos,
			'options'=>$options, 
			'shippingTime' => $shippingTime,
			'countryModel' => $countryModel,
			'topCurs' => $topCurs,
			'currencies' => $currencies,
			'shipping_country_code'=>$shipping_country_code,
			'givingaway'=>$givingaway, 
			'promotionCurrency'=>$promotionCurrency,
			'urgentPrice'=>$urgentPrice, 
			'promotionDetails'=>$promotionDetails,
			'plen' => $plen,
			'sub_cat_name' => $sub_cat_name,
			'pricerange' => $pricerange
		));
	}

	public function actionRemove_blogimage()
	{
		$image = $_POST['image'];
		$photosModel = Photos::find()->where(['name' =>$image])->one();
			//echo $photosModel['name'];die;
		$path = Yii::$app->basePath ."/web/media/item/tmp/"."/";
		$file = $path.$image;
		if( is_file( $file ) ) {
			unlink( $file );
		}
		if(!empty($photosModel))
			$photosModel->delete();
		
	}

	/*
		Get filter by product categories..
	*/
		public function actionGetfilter()
		{
		//$subcat = $_POST['subcat'];
			$category = $_POST['cat'];
			$productId = (isset($_POST['productId'])) ? $_POST['productId'] : '0';

			$parentfieldOption = Categories::find()->where(['categoryId' => $category])->one();
		//$subcatfieldOption = Categories::find()->where(['categoryId' => $subcat])->one();

		//Get category attributes.
		/*if($parentfieldOption->categoryAttributes != '' && $subcatfieldOption->categoryAttributes != '')
		{
			$getcateAttributes = explode(',', $parentfieldOption->categoryAttributes);
			$getsubcateAttributes = explode(',', $subcatfieldOption->categoryAttributes);
			$getAttributes = array_unique(array_merge($getcateAttributes, $getsubcateAttributes));
		}*/if($parentfieldOption->categoryAttributes != '')
		{
			$getAttributes = explode(',', $parentfieldOption->categoryAttributes);
		}/*elseif($parentfieldOption->categoryAttributes == '' && $subcatfieldOption->categoryAttributes != '')
		{
			$getAttributes = explode(',', $subcatfieldOption->categoryAttributes);
		}*/

		//$options = array();
		$options = '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';

		/*$sub_subcatfieldOption = Categories::find()->where(['parentCategory' => $subcat])->all();

		if(!empty($sub_subcatfieldOption)){

			$options .='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';

			$options .= '<label class="Category-select-box-heading required" for="Products_category" id="Products_sub_subCategory_head"> '.Yii::t('app','Select child category for').' '.Yii::t('app',ucfirst($subcatfieldOption->name)).' <span class="required">*</span></label>';
			
					
			$options.=' <select id="Products_sub_subCategory" class="form-control select-box-down-arrow productattributes" name="Products[sub_subCategory]" >';
					
			$options .='<option value="">'.Yii::t('app','Select child category for').' '.Yii::t('app',ucfirst($subcatfieldOption->name)).'</option>';

			$sub_sub_cat = Categories::find()->where(['parentCategory' => $subcat])->all();
						
			foreach($sub_sub_cat as $sub_data) {
				$selectedDrop = ($filterVal != '' && $sub_data->categoryId == $filterVal) ? 'selected="selected"' : '';

				$options .='<option value="'.$sub_data->categoryId.'" '.$selectedDrop.'>'.Yii::t('app',$sub_data->name).'</option>';
			}

			$options .= '</select>';
			$options .= '</div>';
			$options .= '<div id="Products_sub_subCategory_em_" class="errorMessage"></div>';
			$options .= '</div>';
		}else{*/
			/*$options .='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';

			$options .= '<label class="Category-select-box-heading required" for="Products_category" id="Products_sub_subCategory_head">'.Yii::t('app','Select child category').' </label>';
			
					
			$options.=' <select id="Products_sub_subCategory" class="form-control select-box-down-arrow" name="Products[sub_subCategory]" >';
					
			$options .='<option value="">'.Yii::t('app','Select child category').' </option>';

			$options .= '</select>';
			$options .= '</div>';
			$options .= '</div>';*/
		//}

		//$options .='<div id="showsubfield">';
			
			$multilevelvalues = array();
			foreach($getAttributes as $key=>$val)
			{
				$filterModel = Filter::find()->where(['id'=>$val])->one();


				if($filterModel->type == 'dropdown'){

					$filtervalueModel = Filtervalues::find()->where(['filter_id'=>$filterModel->id])->one();

					$getProductfilters = Productfilters::find()->where([
						'product_id'=>$productId,
						'filter_id'=>$filterModel->id
					])->one();

					if(isset($getProductfilters))
						$filterVal = $getProductfilters->level_two;
					else
						$filterVal = '';

					$options .='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
					$options .= '<label class="Category-select-box-heading required" for="Products_category">'.Yii::t('app',ucfirst($filterModel->name)).'</label>';
					$options.=' <select id="product_attributes_'.$filterModel->id.'" class="form-control select-box-down-arrow productattributes" name="Products[attributes]['.$filterModel->id.']" >';
					
					$options .='<option value="">'.Yii::t('app','Select').' '.Yii::t('app',ucfirst($filterModel->name)).'</option>';
					$getchildvals = Filtervalues::find()->where(['parentid'=>$filtervalueModel->id, 'parentlevel'=>'1'])->all();
					
					foreach($getchildvals as $cData) {
						$selectedDrop = ($filterVal != '' && $cData->id == $filterVal) ? 'selected="selected"' : '';
						$options .='<option value="'.$cData->id.'" '.$selectedDrop.'>'.Yii::t('app',$cData->name).'</option>';
					}
					
					$options .= '</select>';
					$options .= '<div class="product_attributes_'.$filterModel->id.' errorMessage"></div>';
					$options .= '</div>';
					$options .= '</div>';
				}elseif($filterModel->type == 'range')
				{
					$getProductfilters = Productfilters::find()->where([
						'product_id'=>$productId,
						'filter_id'=>$filterModel->id
					])->one();

					if(isset($getProductfilters))
						$filterVal = $getProductfilters->filter_values;
					else
						$filterVal = '';

					$fieldname = str_replace(' ', '_', strtolower($filterModel->id));
					$filterrangeval = explode(";",$filterModel->value);
				//$splitval = explode('-', $filterModel->value);
					
					$options .='<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding location-container">';
					$options .= "<label class='Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding'>".Yii::t('app',ucfirst($filterModel->name))."</label>";
					$options.='<input  type="text" id="product_attributes_'.$filterModel->id.'" class="form-control productattributerange" value="'.$filterVal.'" name="Products[attributes]['.$fieldname.']" placeholder = "'.Yii::t('app','Values between').''.$filterrangeval[0].' - '.$filterrangeval[1].'">';
					$options.= '<input type="hidden" id="product_attributes_'.$filterModel->id.'_values" class="form-control" value="'.$filterModel->value.'" name="range_values">';
					$options .= '<div class="product_attributes_'.$filterModel->id.' errorMessage"></div>';
					$options.= '<input type="hidden" id="'.$fieldname.'" value="'.$filterModel->value.'" />';
					$options.= '</div>';
					
				}elseif($filterModel->type == 'multilevel')
				{
					$getFiltervals = Filtervalues::find()->where(['filter_id'=>$filterModel->id,
						'type'=>'multilevel'])->one();
					$getparentlevel = Filtervalues::find()->where(['parentid'=>$getFiltervals->id,
						'parentlevel'=>'3'])->all();

					$getProductfilters = Productfilters::find()->where([
						'product_id'=>$productId,
						'filter_id'=>$filterModel->id
					])->one();

					if(isset($getProductfilters)){
						$filterVal = $getProductfilters->level_two;
						$filterVal2 = $getProductfilters->level_three;
					}
					else{
						$filterVal = '';
						$filterVal2 = '';
					}

					$options.='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="multilevelss_'.$filterModel->id.'">
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
					$options.= '<label class="Category-select-box-heading required" for="Products_category">'.Yii::t('app',$filterModel->name).'</label>';
					$options.=' <select id="multilevel_'.$filterModel->id.'" class="form-control select-box-down-arrow productattributes" name="Products[attributes]['.$filterModel->id.']" onchange="getval(this);" >';
					$options.= '<option value="">'.Yii::t('app','Select parent option').'</option>';
					foreach($getparentlevel as $parentvalues)
					{
						$selectedDrop = ($filterVal != '' && $cData->id == $filterVal) ? 'selected="selected"' : '';
						$options.= '<option value="'.$parentvalues->id.'" '.$selectedDrop.'>'.Yii::t('app',$parentvalues->name).'</option>';
					}
					$options.= '</select>';
					$options .= '<div class="multilevel_'.$filterModel->id.' errorMessage"></div>';
					$options.= '</div>';

					$options.='<div id="multilevel_'.$filterModel->id.'"></div>';
					$options.= '</div>';
					
				}
			}
			$options .= '</div>';
		//$options .= '</div>';


			return $options;
			exit;
		}
		public function actionGetsubcategory(){

			$subcat = $_POST['subcat'];
			$subcatfieldOption = Categories::find()->where(['categoryId' => $subcat])->one();
			$sub_subcatfieldOption = Categories::find()->where(['parentCategory' => $subcat])->all();
		//echo "<pre>";print_r($sub_subcatfieldOption);die;

			$options .='<div id="showsubfield">';
			if(!empty($sub_subcatfieldOption)){

				$options ='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';

				$options .= '<label class="Category-select-box-heading required" for="Products_category" id="Products_sub_subCategory_head"> '.Yii::t('app','Select child category for').' '.Yii::t('app',ucfirst($subcatfieldOption->name)).' <span class="required">*</span></label>';
				
				
				$options.=' <select id="Products_sub_subCategory" class="form-control select-box-down-arrow productattributes" name="Products[sub_subCategory]" >';
				
				$options .='<option value="">'.Yii::t('app','Select child category for').' '.Yii::t('app',ucfirst($subcatfieldOption->name)).'</option>';

				$sub_sub_cat = Categories::find()->where(['parentCategory' => $subcat])->all();
				
				foreach($sub_sub_cat as $sub_data) {
					$selectedDrop = ($filterVal != '' && $sub_data->categoryId == $filterVal) ? 'selected="selected"' : '';

					$options .='<option value="'.$sub_data->categoryId.'" '.$selectedDrop.'>'.Yii::t('app',$sub_data->name).'</option>';
				}

				$options .= '</select>';
				$options .= '</div>';
				$options .= '<div id="Products_sub_subCategory_em_" class="errorMessage"></div>';
				$options .= '</div>';
			}
			$options .= '</div>';
			return $options;
			exit;
		}

	/*
		Get filter by product subcategories..
	*/
		public function actionGetsubfilter()
		{
			
			$subcat = $_POST['subcat'];
			$category = $_POST['cat'];
			$sub_subcat = $_POST['sub_subcat'];
			$productId = (isset($_POST['productId'])) ? $_POST['productId'] : '0';

			$parentfieldOption = Categories::find()->where(['categoryId' => $category])->one();
			$subcatfieldOption = Categories::find()->where(['categoryId' => $subcat])->one();
			$sub_subcatfieldOption = Categories::find()->where(['categoryId' => $sub_subcat])->one();

		//Get category attributes.
		//if($parentfieldOption->categoryAttributes != '' && $subcatfieldOption->categoryAttributes != '' && $sub_subcatfieldOption != '')
		//{
			$getcateAttributes = explode(',', $parentfieldOption->categoryAttributes);
			$getsubcateAttributes = explode(',', $subcatfieldOption->categoryAttributes);
			$getsub_subcateAttributes = explode(',', $sub_subcatfieldOption->categoryAttributes);

			$getAttributes = array_unique(array_merge($getcateAttributes, $getsubcateAttributes,$getsub_subcateAttributes));
		//}
		// elseif($parentfieldOption->categoryAttributes != '' && $subcatfieldOption->categoryAttributes == '' && $sub_subcatfieldOption == '')
		// {
		// 	$getAttributes = explode(',', $parentfieldOption->categoryAttributes);
		// }elseif($parentfieldOption->categoryAttributes == '' && $subcatfieldOption->categoryAttributes != '' && $sub_subcatfieldOption == '')
		// {
		// 	$getAttributes = explode(',', $subcatfieldOption->categoryAttributes);
		// }elseif($parentfieldOption->categoryAttributes == '' && $subcatfieldOption->categoryAttributes == '' && $sub_subcatfieldOption != '')
		// {
		// 	$getAttributes = explode(',', $sub_subcatfieldOption->categoryAttributes);
		// }

		//print_r($getAttributes);exit;

		//$options = array();
			$options = '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
			$multilevelvalues = array();

			foreach($getAttributes as $key=>$val)
			{
				$filterModel = Filter::find()->where(['id'=>$val])->one();


				if($filterModel->type == 'dropdown'){

					$filtervalueModel = Filtervalues::find()->where(['filter_id'=>$filterModel->id])->one();

					$getProductfilters = Productfilters::find()->where([
						'product_id'=>$productId,
						'filter_id'=>$filterModel->id
					])->one();

					if(isset($getProductfilters))
						$filterVal = $getProductfilters->level_two;
					else
						$filterVal = '';

					$options .='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
					$options .= '<label class="Category-select-box-heading required" for="Products_category">'.Yii::t('app',ucfirst($filterModel->name)).'</label>';
					$options.=' <select id="product_attributes_'.$filterModel->id.'" class="form-control select-box-down-arrow productattributes" name="Products[attributes]['.$filterModel->id.']" >';
					
					$options .='<option value="">'.Yii::t('app','Select').' '.Yii::t('app',ucfirst($filterModel->name)).'</option>';
					$getchildvals = Filtervalues::find()->where(['parentid'=>$filtervalueModel->id, 'parentlevel'=>'1'])->all();
					
					foreach($getchildvals as $cData) {
						$selectedDrop = ($filterVal != '' && $cData->id == $filterVal) ? 'selected="selected"' : '';
						$options .='<option value="'.$cData->id.'" '.$selectedDrop.'>'.Yii::t('app',$cData->name).'</option>';
					}
					
					$options .= '</select>';
					$options .= '<div class="product_attributes_'.$filterModel->id.' errorMessage"></div>';
					$options .= '</div>';
					$options .= '</div>';
				}elseif($filterModel->type == 'range')
				{
					$getProductfilters = Productfilters::find()->where([
						'product_id'=>$productId,
						'filter_id'=>$filterModel->id
					])->one();

					if(isset($getProductfilters))
						$filterVal = $getProductfilters->filter_values;
					else
						$filterVal = '';

					$fieldname = str_replace(' ', '_', strtolower($filterModel->id));
				//$splitval = explode('-', $filterModel->value);

					$filterrangeval = explode(";",$filterModel->value);
				//$splitval = explode('-', $filterModel->value);
					
					

					$options .='<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding location-container">';
					$options .= "<label class='Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding'>".Yii::t('app',ucfirst($filterModel->name))."</label>";
					$options.='<input  type="text" id="product_attributes_'.$filterModel->id.'" class="form-control productattributerange" value="'.$filterVal.'" name="Products[attributes]['.$fieldname.']" placeholder = "'.Yii::t('app','Values between').' '.$filterrangeval[0].' - '.$filterrangeval[1].'">';
					$options.= '<input type="hidden" id="product_attributes_'.$filterModel->id.'_values" class="form-control" value="'.$filterModel->value.'" name="range_values">';
					$options .= '<div class="product_attributes_'.$filterModel->id.' errorMessage"></div>';
					$options.= '<input type="hidden" id="'.$fieldname.'" value="'.$filterModel->value.'" />';
					$options.= '</div>';
					
				}elseif($filterModel->type == 'multilevel')
				{
					$getFiltervals = Filtervalues::find()->where(['filter_id'=>$filterModel->id,
						'type'=>'multilevel'])->one();
					$getparentlevel = Filtervalues::find()->where(['parentid'=>$getFiltervals->id,
						'parentlevel'=>'3'])->all();

					$getProductfilters = Productfilters::find()->where([
						'product_id'=>$productId,
						'filter_id'=>$filterModel->id
					])->one();

					if(isset($getProductfilters)){
						$filterVal = $getProductfilters->level_two;
						$filterVal2 = $getProductfilters->level_three;
					}
					else{
						$filterVal = '';
						$filterVal2 = '';
					}

					$options.='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="multilevelss_'.$filterModel->id.'">
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
					$options.= '<label class="Category-select-box-heading required" for="Products_category">'.Yii::t('app',$filterModel->name).'</label>';
					$options.=' <select id="multilevel_'.$filterModel->id.'" class="form-control select-box-down-arrow productattributes" name="Products[attributes]['.$filterModel->id.']" onchange="getval(this);" >';
					$options.= '<option value="">'.Yii::t('app','Select parent option').'</option>';
					foreach($getparentlevel as $parentvalues)
					{
						$selectedDrop = ($filterVal != '' && $cData->id == $filterVal) ? 'selected="selected"' : '';
						$options.= '<option value="'.$parentvalues->id.'" '.$selectedDrop.'>'.Yii::t('app',$parentvalues->name).'</option>';
					}
					$options.= '</select>';
					$options .= '<div class="multilevel_'.$filterModel->id.' errorMessage"></div>';
					$options.= '</div>';

					$options.='<div id="multilevel_'.$filterModel->id.'"></div>';
					$options.= '</div>';
					
				}
			}
			$options .= '</div>';


			return $options;
			exit;
		}


	// 	public function actionRemove_blogimage1()
	// {
	// 		$image = $_POST['image'];
	// 		$pid=$_POST['productId'];
	// 		$photosModel = Photos::find()->where(['name' =>$image])->one();
	// 		//echo Yii::$app->basePath;die;
	// 		$path = Yii::$app->basePath ."/web/media/item/".$pid."/"."/";
	// 		$file = $path.$image;
	// 		if( is_file( $file ) ) {
	// 			unlink( $file );
	// 		}
	// 		if(!empty($photosModel))
	// 		$photosModel->delete();
		
	// }


		public function actionDelete($id)
		{
			
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
			$this->loadModel($id)->delete();

			$path = Yii::$app->basePath ."/web/media/item/".$id."/";
			
			if( is_dir( $path ) ) {
				FileHelper::removeDirectory($path);
			}

			Photos::deleteAll(['productId'=>$id]);
			Adspromotiondetails::deleteAll(['productId'=>$id]);
			Promotiontransaction::deleteAll(['productId'=>$id]);

			yii::$app->Myclass->removeItemLogs($id);

	//	if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax'])){
				Yii::$app->session->setFlash("success",Yii::t('app','Product Deleted successfully'));
				return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('/user/profiles'));
			}
		}


		public function actionReportitem(){
			$id = Yii::$app->user->id;
			if (isset($_GET['itemid']) && isset($_GET['userid'])){
				$itemid = yii::$app->Myclass->checkPostvalue($_GET['itemid']) ? $_GET['itemid'] : "";
				$userid = yii::$app->Myclass->checkPostvalue($_GET['userid']) ? $_GET['userid'] : "";
				$itemModel = $this->loadModel($itemid);
				if (!empty($itemModel->reports)){
					$reportFlag = json_decode($itemModel->reports,true);
					$reportFlag[] = $id;
					$itemModel->reports = json_encode($reportFlag);
					$itemModel->productId = $itemid;
				}else{
					$reportFlag[] = $id;
					$itemModel->reports = json_encode($reportFlag);
					$itemModel->productId = $itemid;
				}
				$itemModel->reportCount += 1;
				$itemModel->save(false);
				echo true;
			}
		}
		public function actionUndoreport(){
			$id = Yii::$app->user->id;
			if (isset($_GET['itemid']) && isset($_GET['userid'])){
				$itemid = yii::$app->Myclass->checkPostvalue($_GET['itemid']) ? $_GET['itemid'] : "";
				$userid = yii::$app->Myclass->checkPostvalue($_GET['itemid']) ? $_GET['userid'] : "";
				$itemModel = $this->loadModel($itemid);
				if (!empty($itemModel->reports)){
					$reportFlag = json_decode($itemModel->reports,true);
					$newreportflag = array();
					foreach ($reportFlag as $flag){
						if ($flag != $id){
							$newreportflag[] = $flag;
						}
					}
					if (!empty($newreportflag)){
						$itemModel->reports = json_encode($newreportflag);
						$itemModel->productId = $itemid;
					}else{
						$itemModel->reports = '';
						$itemModel->productId = $itemid;
					}
				}
				$itemModel->reportCount -= 1;
				$itemModel->save(false);
				echo true;
			}
		}

		public function actionRequestexchange() {
			
			$user_Id = Yii::$app->user->id;
			$exchange = new Exchanges();
			if(isset($_POST['mainProductId']) && isset($_POST['exchangeProductId'])  && isset($_POST['requestTo'])) {



				$Products = Products::find()->where(['productId' => $_POST['mainProductId']])->one();
				if(isset($Products) && $Products->approvedStatus == 0)
				{
					return "error";
				}
				else
				{

					$exchange->mainProductId = $_POST['mainProductId'];
					$exchange->exchangeProductId = $_POST['exchangeProductId'];
					$exchange->requestFrom = Yii::$app->user->id;
					$exchange->requestTo = $_POST['requestTo'];
					$exchange->date = time();
					$exchange->slug = yii::$app->Myclass->getRandomString(8);
					$exchange->status = 0;
					$mainProductModel = yii::$app->Myclass->getProductDetails($_POST['mainProductId']);
					$exchangeProductModel = yii::$app->Myclass->getProductDetails($_POST['exchangeProductId']);
					if($mainProductModel->quantity < 1 || $mainProductModel->soldItem != 0){
						return "sold";
					}elseif($exchangeProductModel->quantity < 1 || $exchangeProductModel->soldItem != 0){
						return "exchangesold";
					}else{ 

						$check = Yii::$app->Myclass->exchangeProductExist($exchange->mainProductId,$exchange->exchangeProductId,$exchange->requestFrom,$exchange->requestTo);

						
						if(!empty($check)) {
							
							if($check->blockExchange == 1) {
								return 'blocked';
							} else {

								$productsModel = $this->loadModel($check->mainProductId);
								if($check->status != 0 && $check->status != 1) 
								{
									$check->requestFrom = Yii::$app->user->id;
									$check->requestTo = $_POST['requestTo'];
									$check->status = 0;
									$check->date = time();
									$history = array();
									if(!empty($check->exchangeHistory)) {
										$history = Json::decode($check->exchangeHistory,true);
									}
									$history[] = array('status' =>'created','date'=>$check->date,'user'=>$check->requestFrom);
									$check->exchangeHistory = Json::encode($history);
									$check->save(false);

							//$userid = $check->requestFrom;
									$userid =  Yii::$app->user->id;
									$senderid = $check->requestTo;

									$notifyTo = $userid;
									$notifyItem = $check->mainProductId;
									if( $user_Id == $userid ){
										$notifyTo 	= $senderid;
										$notifyItem = $check->exchangeProductId;
									}
									if($user_Id == $userid)
										$notifyTo = $senderid;
									$notifyMessage = 'sent exchange request to your product';
									Yii::$app->Myclass->addLogs("exchange", $user_Id, $notifyTo, $check->id, $notifyItem, $notifyMessage);

									$pushsender = $senderid;
									$pushuser = $userid;
									if($user_Id == $userid){
										$pushuser = $senderid;
										$pushsender = $userid;
									}
									$sellerDetails = yii::$app->Myclass->getUserDetailss($user_Id);
									$userdevicedet = Userdevices::find()->where(['user_id' => $notifyTo])->one();

									
									$productRecord = Products::find()->where(['productId' => $check->mainProductId])->one();

							//Update exchange request.
									
							//$productsModel->exchangeRequest++;
							//$productsModel->save(false);

									if(count($userdevicedet) > 0){
										foreach($userdevicedet as $userdevice){
											$deviceToken = $userdevice->deviceToken;
											$lang = $userdevice->lang_type;
											$badge = $userdevice->badge;
											$badge +=1;
											$userdevice->badge = $badge;
											$userdevice->deviceToken = $deviceToken;
											$userdevice->save(false);
											if(isset($deviceToken)){
												yii::$app->Myclass->push_lang($lang);
												$messages = $sellerDetails->username." ".Yii::t('app','sent exchange request to your product')." ".$productRecord->name;
												yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
											}
										}
									}

									$Products = new Products();
									$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
									$mailer = Yii::$app->mailer->setTransport([
										'class' => 'Swift_SmtpTransport',
										'host' => $siteSettings['smtpHost'],  
										'username' => $siteSettings['smtpEmail'],
										'password' => $siteSettings['smtpPassword'],
										'port' => $siteSettings['smtpPort'], 
										'encryption' =>  'tls', 
									]);
									
									try
									{
										$sellerDetails = yii::$app->Myclass->getUserDetailss($notifyTo);
										$c_username = $sellerDetails->name;
										$emailTo = $sellerDetails->email;
										$userDetails = yii::$app->Myclass->getUserDetailss($user_Id);
										$r_username = $userDetails->name;
										$Products->sendExchangeProductEmail($emailTo,$sellerDetails->name,$userDetails->name);
									}

									catch(\Swift_TransportException $exception)
									{
							  //Yii::$app->session->setFlash('error', Yii::t('app','Exchange request has been sent. SMTP Connection error check email setting'));
							  //hideby217
							  //return $this->redirect($_SERVER['HTTP_REFERER']);
									}
									
					       // $siteSettings = Sitesettings::model()->find();
							// $mail = new YiiMailer();
							// $sellerDetails = yii::$app->Myclass->getUserDetails($notifyTo);
							// $c_username = $sellerDetails->name;
							// $emailTo = $sellerDetails->email;
							// $userDetails = yii::$app->Myclass->getUserDetails($user_Id);
							// $r_username = $userDetails->name;
							// if($siteSettings->smtpEnable == 1) {
							// 	//$mail->IsSMTP();                         // Set mailer to use SMTP
							// 	$mail->Mailer = 'smtp';                         // Set mailer to use SMTP
							// 	$mail->Host = $siteSettings->smtpHost;  // Specify main and backup server
							// 	$mail->SMTPAuth = true;                               // Enable SMTP authentication
							// 	$mail->Username = $siteSettings->smtpEmail;                            // SMTP username
							// 	$mail->Password = $siteSettings->smtpPassword;                           // SMTP password
							// 	if($siteSettings->smtpSSL == 1)
							// 		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
							// 	$mail->Port = $siteSettings->smtpPort;
							// }

							// $mail->setView('exchangecreated');
							// $mail->setData(array('c_username' => $c_username, 'r_username' => $r_username,
							// 		'siteSettings' => $siteSettings));
							// $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
							// $mail->setTo($emailTo);
							// $mail->setSubject($r_username.' '.Yii::t('app','sent Exchange Request with your product'));
							// $mail->send();

									return 'success';
								} else {
									return 'sent';
								}
							}
						} else {
							
							if($exchange) {
								
								$history = array();
								if(!empty($exchange->exchangeHistory)) {
									$history = json_decode($exchange->exchangeHistory,true);
								}
								$history[] = array('status' =>'created','date'=>$exchange->date,'user'=>$exchange->requestFrom);
								$exchange->exchangeHistory = json_encode($history);
								$exchange->save(false);

								$userid = $exchange->requestFrom;
								$senderid = $exchange->requestTo;

								$notifyTo = $userid;
								$notifyItem = $exchange->mainProductId;
								if($user_Id == $userid){
									$notifyTo = $senderid;
									$notifyItem = $exchange->exchangeProductId;
								}
								if($user_Id == $userid)
									$notifyTo = $senderid;
								$notifyMessage = 'sent exchange request to your product';
								yii::$app->Myclass->addLogs("exchange", $user_Id, $notifyTo, $exchange->id, $notifyItem, $notifyMessage);

								$pushsender = $senderid;
								$pushuser = $userid;
								if($user_Id == $userid){
									$pushuser = $senderid;
									$pushsender = $userid;
								}
								$sellerDetails = yii::$app->Myclass->getUserDetailss($user_Id);
						// $criteria = new CDbCriteria;
						// $criteria->addCondition('user_id = "'.$notifyTo.'"');
								$userdevicedet = Userdevices::find()->where(['user_id' => $notifyTo])->all();
								$productRecord = Products::find()->where(['productId' => $exchange->mainProductId])->one();
								if(count($userdevicedet) > 0){
									foreach($userdevicedet as $userdevice){
										$deviceToken = $userdevice->deviceToken;
										$lang = $userdevice->lang_type;
										$badge = $userdevice->badge;
										$badge +=1;
										$userdevice->badge = $badge;
										$userdevice->deviceToken = $deviceToken;
										$userdevice->save(false);
										if(isset($deviceToken)){
											yii::$app->Myclass->push_lang($lang);
											$messages = $sellerDetails->username." ".Yii::t('app','sent exchange request to your product')." ".$productRecord->name;
											yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
										}
									}
								}

								$Products = new Products();
								$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

								$mailer = Yii::$app->mailer->setTransport([
									'class' => 'Swift_SmtpTransport',
									'host' => $siteSettings['smtpHost'],  
									'username' => $siteSettings['smtpEmail'],
									'password' => $siteSettings['smtpPassword'],
									'port' => $siteSettings['smtpPort'], 
									'encryption' =>  'tls', 
								]);
								$sellerDetails = yii::$app->Myclass->getUserDetailss($notifyTo);
								$c_username = $sellerDetails->name;
								$emailTo = $sellerDetails->email;
								$userDetails = yii::$app->Myclass->getUserDetailss($user_Id);
								$r_username = $userDetails->name;

								try
								{
									$Products->sendExchangeProductEmail($emailTo,$sellerDetails->name,$userDetails->name);
								}
								catch(\Swift_TransportException $exception)
								{
							  	//Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
						  		//hideby217
								//return $this->redirect($_SERVER['HTTP_REFERER']);
								}
								
							//$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
							// $mail = new YiiMailer();
							// $sellerDetails = Myclass::getUserDetails($notifyTo);
							// $c_username = $sellerDetails->name;
							// $emailTo = $sellerDetails->email;
							// $userDetails = Myclass::getUserDetails($user_Id);
							// $r_username = $userDetails->name;
							// if($siteSettings->smtpEnable == 1) {
							// 	//$mail->IsSMTP();                         // Set mailer to use SMTP
							// 	$mail->Mailer = 'smtp';                         // Set mailer to use SMTP
							// 	$mail->Host = $siteSettings->smtpHost;  // Specify main and backup server
							// 	$mail->SMTPAuth = true;                               // Enable SMTP authentication
							// 	$mail->Username = $siteSettings->smtpEmail;                            // SMTP username
							// 	$mail->Password = $siteSettings->smtpPassword;                           // SMTP password
							// 	if($siteSettings->smtpSSL == 1)
							// 		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
							// 	$mail->Port = $siteSettings->smtpPort;
							// }

							// $mail->setView('exchangecreated');
							// $mail->setData(array('c_username' => $c_username, 'r_username' => $r_username,
							// 		'siteSettings' => $siteSettings));
							// $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
							// $mail->setTo($emailTo);
							// $mail->setSubject($r_username.' '.Yii::t('app','sent Exchange Request with your product'));
							// $mail->send();

								return 'success';
							}
						}
					}
				}
			}
		}

	/*
		Custom codes.
	*/
		public function actionInsights($id)
		{   
			$productID = yii::$app->Myclass->safe_b64decode($id);
			$commentModel = Comments::find()->where(['productId' => $productID])->orderBy(['commentId' => SORT_DESC])->all();

			$product = Products::find()
			->select('hts_products.name,hts_products.soldItem,hts_products.price,hts_products.currency,hts_products.productId,hts_products.insightUsers,hts_products.commentCount,hts_products.userId,hts_products.likes,hts_products.offerRequest,hts_products.exchangeRequest,hts_products.productId,hts_products.views,users.userId,users.country')
			->leftJoin('users', 'users.userId=hts_products.userId')
			->where(['productId' => $productID])
			->one();


			if(trim($product->userId) != trim(Yii::$app->user->id)) {
				Yii::$app->session->setFlash("error",Yii::t('app','Access denied...!'));
				$homeUrl = Yii::$app->getUrlManager()->getBaseUrl().'/';
				return $this->redirect($homeUrl); 
			}

			$insightUser = json_decode($product->insightUsers);		
			$items = array();
			foreach (array_unique($insightUser) as $key => $value) 
			{
				$country= Users::find()->select('city')->where(['userId' => $value])->one();
				$items[] =$country->city; 	 	
			}

			$getComments = Comments::find()->where(['productId'=>$productID])->count();

			$country = array_count_values($items);
			arsort($country, SORT_NUMERIC); 
			$country = array_slice($country, 0,5); 

			$totalCount =count($country);
			$unquie_view=count(json_decode($product->insightUsers));
			$comments = count($commentModel);
			$user_id = $product->userId;

			$getcountryCount = Users::find()->where(['country' => $product->country])->count();
			$percentage = ($unquie_view/$getcountryCount)*100;


			$getUniqueviews = Userviews::find()->where(['product_id'=>$productID])->distinct('product_id')->count();
		    //echo '<pre>'; print_r($getUniqueviews); exit;

		   //Engagements

			$getOfferRequest = (new yii\db\Query())
			->select(['userId'])
			->from('hts_favorites')
			->where(['productId'=>$productID])
			->all();

			$getExchangescount = Exchanges::find()->where(['mainProductId'=>$productID])->count();

			$gethelppagecontent = Help::find()->where(['id'=>4])->one();
			$sendofferRequestcnt=0;
			$sendofferRequest = Messages::find()->where(['messageType' =>'offer','sourceId'=>$productID])->all();
			if(count($sendofferRequest) > 0){
				foreach ($sendofferRequest as $key => $sendofferRequests) {
					$offerRequestType = json_decode($sendofferRequests->message, true);
					if($offerRequestType['type']=="sendreceive")
					{
						$sendofferRequestcnt = $sendofferRequestcnt + 1;
					}
				}
				

			}
			$likes = $product->likes;
			$myofferRequest = $product->offerRequest;
			$exchangeRequest = $product->exchangeRequest;

			$totalengagements = (int)$comments + (int)$likes+(int)$myofferRequest+(int)$exchangeRequest;
			$calcPercentage = ($totalengagements/$getcountryCount)*100;
			
			//dateRecords
			//$getRecords = Records::find()->select('records')->where(['productId' => $productID])->one();
			//$dateRecords=Json::decode($getRecords->records);
			//$dateRecords = Userviews::find()->select('created_at')->distinct()->all();
			$dateRecords = (new yii\db\Query())
			->select(['product_id','user_id','seller_id','city'])
			->from('hts_userviews')
			->distinct('created_at')
			->where(['product_id'=>$productID])
			->all();

			for($i=7;$i>=0; $i--) {

				  		//echo $product->productId;
				$weekDates = date("Y-m-d",strtotime("-".$i."days"));
				$getViewcount = Userviews::find()->where([
					'product_id'=>$product->productId,
					'created_at'=>$weekDates])->groupBy('user_id')->count();

					   	//$count=yii::$app->Myclass->getDaterecordsWeekly(date("Y/m/d",strtotime("-".$i."days")),$product->productId);
				$prosub[]=[$weekDates,(int)$getViewcount];
			}

			//echo '<pre>'; print_r($prosub); 
			//exit;
			$prosub1[]=['Weekly', 'count'];
			$prosubdata=array_merge($prosub1,$prosub);

			$getvisitedcities = Yii::$app->db->createCommand("SELECT city, COUNT(*) As cnt FROM hts_userviews WHERE product_id = ".$product->productId." AND city <> '' GROUP BY city")->queryAll();
			$getmaximumcities = Yii::$app->db->createCommand("SELECT  MAX(city) As cities FROM hts_userviews WHERE product_id = ".$product->productId."")->queryAll();

			$getPromotiondetails = Promotiontransaction::find()->where(['productId'=>$product->productId,'status'=>'Live'])->orderBy(['id' => SORT_DESC])->one();

			if(!empty($getPromotiondetails))
				$promotionStatus = 'disabled';
			else
				$promotionStatus = 'enabled';

			$promotionDetails = Promotions::find()->all();

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$urgentPrice = $siteSettings->urgentPrice;
			$promotionCurrency = $siteSettings->promotionCurrency;
			
			
			return $this->render('insights',['model'=>$product,
				'promotionCurrency'=>$promotionCurrency, 
				'urgentPrice'=>$urgentPrice, 
				'promotionDetails'=>$promotionDetails,
				'unquie_view'=>$unquie_view,
				'exchangeCount'=>$getExchangescount,
				'offerRequest'=>$getOfferRequest,
				'offerRequestcnt'=> $sendofferRequestcnt,
				'comments'=>$comments,
				'reachcontent'=>$gethelppagecontent,
				'totalCount'=>$totalCount,
				'promotionStatus'=>$promotionStatus,
				'country'=>$country,
				'per'=>$percentage,
				'total_visitedcity'=> count($getvisitedcities),
				'most_visitedcity'=> count($citiesArray),
				'percentageEnga'=>$calcPercentage,
				'dateRecords'=>$dateRecords,
				'prosubdata'=>$prosubdata]);
		}

		public function actionFiltervalues($id)
		{
			$category = $id;
			$getcateAttribute = Categories::find()->where(['categoryId'=>$category])->one();
			$categoryattibutes = explode(',', $getcateAttribute->categoryAttributes);
			$attributeData = array();
			foreach($categoryattibutes as $key=>$attribute)
			{
				$getFilterData = Filter::find()
				->where(['id'=>$attribute,'type'=>'range'])
				->one();
				if(!empty($getFilterData))
				{
					$attributeData[$key]['id'] = $getFilterData->id;
					$attributeData[$key]['name'] = $getFilterData->name;
				}
			}
			return $attributeData;
		}


		public function actionItemsdata()
		{
			$type = $_GET['items'];
			$productId= $_GET['productId'];
			
			if($type=='weekly') {
				for($i=7;$i>=0; $i--) {
					$mystring = date("Y/m/d",strtotime("-".$i."days"));
					$count=yii::$app->Myclass->getDaterecordsWeekly(date("Y/m/d",strtotime("-".$i."days")),$productId);
					$prosub[]=[$mystring,(int)$count];
				}
				
				$prosub1[]=['Weekly', 'count'];
				$prosublist = $prosub;
				$prosubdata=array_merge($prosub1,$prosublist);
				
			}else if($type=='monthly') {
				for($i=7;$i>=0; $i--) {
					$mystring = date('Y/m', strtotime('-'.$i.' month', time()));
					$count=yii::$app->Myclass->getDaterecordsMontly(date('Y/m', strtotime('-'.$i.' month', time())),$productId);
					$prosub[]=[$mystring,(int)$count];
				}
				$prosub1[]=['Monthly', 'count'];
				$prosublist = $prosub;
				$prosubdata=array_merge($prosub1,$prosublist);
			}else if($type=='year') {
				for($i=7;$i>=0; $i--) {
					$mystring = date('Y', strtotime('-'.$i.' year', time()));
					$count=Yii::$app->Myclass->getDaterecordsYearly(date('Y', strtotime('-'.$i.' year', time())),$productId);
					$prosub[]=[$mystring,(int)$count];
				}
				$prosub1[]=['Yearly', 'count'];
				$prosublist = $prosub;
				$prosubdata=array_merge($prosub1,$prosublist);
			}
			echo json_encode($prosubdata,JSON_NUMERIC_CHECK);
		}

		public function actionGetchildlevel($id)
		{

			$loadFilter = Filtervalues::find()->where(['parentid'=>$_GET['id'],'parentlevel'=>'4'])->all();

			if(empty($loadFilter))
				return false;

			//$explodeVal = explode(',', $loadFilter->value);
			$options = '<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding childlevelattr '.$_GET['id'].'">
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding '.$_GET['id'].'">';
			//$options.= '<label class="Category-select-box-heading required" for="Products_category">Child Level</label>';
			$options.=' <select id="childattribute_'.$_GET['id'].'" class="form-control productattributes" name="Products[attributes][multilevel]['.$_GET['id'].']">';
			$options.= '<option value="">'.Yii::t('app','Select Child value').'</option>';
			foreach( $loadFilter as $key=>$value )
			{
				$options.= '<option value="'.$value->id.'">'.$value->name.'</option>';
			}
			$options.= '</select>';
			$options .= '<div class="childattribute_'.$_GET['id'].' errorMessage"></div>';
			$options.= '</div>';
			$options.= '</div>';
			return $options; 
			//exit;
		}

		public function actionGetrangefilter()
		{
	    	//$rangevalues = "109, 329";
			$rangevalues = $_POST['rangevalues'];
			$getRange = explode(',', $rangevalues);
			$rangeVal = array();
			foreach($getRange as $key=>$val)
			{
				$filterVals = Filtervalues::find()->where(['id'=>$val])->one();
				$filterRangevalue = Filter::find()->where(['id'=>$filterVals->filter_id])->one();
				$rangeVal[$key]['id'] = $filterVals->filter_id;
				$rangeVal[$key]['name'] = str_replace(' ', '_', $filterVals->name);
				$rangeVal[$key]['range'] = $filterRangevalue->value;
			}
			return json_encode($rangeVal);
		}

		public function actionGetlonlat(){

			$address = $_POST['address'];

			
	//$url  = "https://maps.google.com/maps/api/geocode/json?address=".$address."&sensor=false&key=AIzaSyBqN_3wtob65prYXsWPGtvaMNzntAzpnes";
			$json = @file_get_contents($url);

			$data = json_decode($json);

			echo '<pre>'; print_r($data); exit;

			if (!empty($data)) {
				$status = $data->status;
			}
			else
			{
				$status = '';
			}
			
			$address = '';
			
			$session = Yii::$app->session;
			if($status == "OK"){
				$address = $data->results[0]->formatted_address;
				$result = explode(",",$address);
				echo $result;
			}


		}
		public function actionPromotionstripepaymentprocess()
		{
			$stripeform = Yii::$app->request->post();

			
			$promotionId = $stripeform['promotionids'];
			$promotiontypee = $stripeform['promotiontypee'];
			$promotiontype = $stripeform['promotiontype'];
			
			$promotionDetails = Promotions::find()->where(['id'=>$promotionId])->one(); 

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$stripeSetting = json_decode($siteSettings->stripe_settings, true);
			if(isset($stripeform['customField'])) 
			{
				$customField = $stripeform['customField'];
			}
			else{
				$customField = $stripeform['customFieldd'];
			}

			if(isset($stripeform['totalPrice']))
				$totalprice = $stripeform['totalPrice'];
			else {
				$price = $promotionDetails->price;
				$totalprice = $price * 100; 
			}
			if(isset($stripeform['currency']))
				$currency = trim($stripeform['currency']);
			else
				$currency = trim($stripeform['currencyy']);

			$token = $stripeform['stripeToken'];
			if(isset($stripeform['itemids']))
				$itemid = $stripeform['itemids'];
			else
				$itemid = $stripeform['itemide'];

			if(isset($stripeform['name']))
				$name = $stripeform['name']; 
			
			$productModel = Products::find()->where(['productId'=>$itemid])->one(); 
			$userModel = Users::find()->where(['userId'=>$productModel->userId])->one();

			
			$secretkey=$stripeSetting['stripePrivateKey']; 
			$url = 'https://api.stripe.com/v1/charges';
			$data = array('amount' => $totalprice, 'currency' => $currency, 'source' => $token, 'description' => 'Urgent Promotion');
			$ch = curl_init();
					//print_r($data);die;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
					//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $secretkey,'Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			$output = json_decode($result,true);
			
			/** SUCCESS RESULT * */

			if($output['status'] == 'succeeded') 
			{
				
				$keyarray['custom'] = yii::$app->Myclass->cart_decrypt($customField, "pr0m0tion-det@ils");
					$custom = explode('-_-', $keyarray['custom']);//print_r($custom);die;
					
					$currencyCode = $currency;
					
					


					$createdDate = time(); 
					$promotionTranxModel = new Promotiontransaction();
					$promotionTranxModel->promotionName = $custom[0];//echo  $custom[0];die;
					if($custom[0] == "urgent")
						$promotionTranxModel->promotionPrice = $custom[3] / 100;
					else
						$promotionTranxModel->promotionPrice = $promotionDetails->price;
					
					if($custom[0] == "urgent")
						$promotionTranxModel->promotionTime = $custom[2];
					else
						$promotionTranxModel->promotionTime = $promotionDetails->days;
					
					$promotionTranxModel->userId = $custom[4];
					$promotionTranxModel->productId = $itemid;
					$promotionTranxModel->tranxId = $output['balance_transaction'];
					if($siteSettings->product_autoapprove==1)
					{
						$promotionTranxModel->approvedStatus = 1;
						$promotionTranxModel->initial_check = 1;
						$promotionTranxModel->createdDate = $createdDate;
					}
					else
					{
						$promotionTranxModel->approvedStatus = 0;
						$promotionTranxModel->initial_check = 0;
						$promotionTranxModel->createdDate = $createdDate;
					}
					$promotionTranxModel->promotionCurrency = $currency;
					$promotionTranxModel->save(false);
					$promotionTranxId = $promotionTranxModel->id;

					if($custom[0] != "urgent"){
						$adsPromotionDetailsModel = new Adspromotiondetails();
						$adsPromotionDetailsModel->productId = $itemid;
						$adsPromotionDetailsModel->promotionTime = $promotionDetails->days;
						$adsPromotionDetailsModel->promotionTranxId = $promotionTranxId;
						$adsPromotionDetailsModel->createdDate = $createdDate;

						$adsPromotionDetailsModel->save(false);
					}
					$productModel = Products::find()->where(['productId'=>$itemid])->one(); 

					if($custom[0] == "urgent"){
						$productModel->promotionType = 2;
					}else{
						$productModel->promotionType = 1;
					}
					$productModel->save(false);
					$siteSettings = Sitesettings::find()->where(['id'=>'1'])->one();
					$userModel = yii::$app->Myclass->getUserDetailss($productModel->userId);
					$sellerEmail = $userModel->email;
					$sellerName = $userModel->name;
					
					$userid = $productModel->userId;
					
					$userdevicedet = Userdevices::find()->where(['user_id'=>$userid])->all();
					if(count($userdevicedet) > 0){
						foreach($userdevicedet as $userdevice){
							$deviceToken = $userdevice->deviceToken;
							$lang = $userdevice->lang_type;
							$badge = $userdevice->badge;
							$badge +=1;
							$userdevice->badge = $badge;
							$userdevice->deviceToken = $deviceToken;
							$userdevice->save(false);
							if(isset($deviceToken)){
								yii::$app->Myclass->push_lang($lang);
								if($custom[0] == "urgent"){
									$messages =  Yii::t('app','You have promoted your product')." ".$productModel->name." ".Yii::t('app','by')." ".$currencyCode.$custom[3];
								}else{
									$messages =  Yii::t('app','You have promoted your product')." ".$productModel->name." ".Yii::t('app','by')." ".$currencyCode.$custom[3]." for ".$custom[2]." ".Yii::t('app','days');
								}
								yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
							}
						}
					}
					if($productModel->approvedStatus==1)
					{
						Yii::$app->session->setFlash("success",Yii::t('app','You have successfully promoted your product'));
					}else{
						Yii::$app->session->setFlash("success",Yii::t('app','You have successfully promoted your product and waiting for admin approval'));
					}
					return $this->redirect(['/user/profiles']);
					
					return true;
				} else { 
					return $this->redirect(['/checkout/Canceled']);
					return true;
				}



			}  

		}
