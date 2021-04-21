<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\authclient\widgets\AuthChoice;
use yii\helpers\Json;
use common\models\Sitesettings;
use common\models\Users;
use yii\data\Pagination;
use common\models\Followers;
use common\models\Logs;
use common\models\Exchanges;
use common\models\Products;
use common\models\Promotions;
use common\models\Promotiontransaction;
use common\models\Favorites;
use common\models\Reviews;
use common\models\Userdevices;
use common\models\Messages;
use common\models\Chats;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use common\models\LoginForm;
use yii\data\ArrayDataProvider;
use yii\helpers\HtmlPurifier;
use yii\web\Response;
use yii\helpers\Html;
use yii\data\DataProviderInterface;
use yii\data\BaseDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use common\components\HybridAuthIdentity;
use common\models\Banners;
error_reporting(0);

Html::csrfMetaTags();
class UserController extends \yii\web\Controller
{	
    const ACCEPT = 1;
	const DECLINE = 2;
	const CANCEL = 3;
	const SUCCESS = 4;
	const FAILED = 5;
	const SOLDOUT = 6;

	public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

        	$settings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one(); 
    	if ($settings->site_maintenance_mode == '1') {
    		return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/sitemaintenance');
    	}


			   $this->enableCsrfValidation = false;
            if(!Yii::$app->user->isGuest){
                $User = Users::find()->where(['userId' => Yii::$app->user->id])->one(); 
                if($User->userstatus == 0){
					Yii::$app->user->logout();
					Yii::$app->session->setFlash('error', Yii::t('app','Your account has been disabled by the Administrator')); 
					return $this->goHome();
                 }
            }
        }
        return true;
	}

	
    public function actionIndex()
	{  
        return $this->render('index');
    }



    public function actionProfile() {

		if (Yii::$app->user->isGuest) {            
    		return $this->goHome();          
    	}
		$id = Yii::$app->user->id;
		//$model->nowid = $id;
		$model = Users::find()->where(['userId'=>$id])->one();
	//	$model->setScenario('profile');

		$user = Users::find()->where(['userId'=>$id])->one();

	
		if(isset($_POST['User'])) {
			$model->name=$_POST['User']['name'];
			$model->username=$_POST['User']['username'];
			$model->email=$_POST['User']['email'];
			$model->phonevisible=$_POST['User']['phonevisible'];
			
			if($model->save()) {
				// if(!is_null($userImg)) {
				// 	$userImg->saveAs('media/user/'.$model->userImage);
				// }
				Yii::$app->session->setFlash('success',Yii::t('app','User Profile updated successfully'));
			//	$this->redirect($_SERVER['HTTP_REFERER']);
				
			}
        }
        return $this->render('profile', ['model'=>$model,'user'=>$user]);
	}

	public function actionMobileverificationstatus() {

		$_SESSION["code"] = $_POST["code"];
		$_SESSION["csrf_nonce"] = $_POST["csrf_nonce"];
		//echo "code ".$_POST["code"]; echo "nonce ".$_POST["csrf_nonce"];
		$ch = curl_init();
      

	  $sitedetails = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
	  $fb_appid = $sitedetails->fb_appid;
	  $fb_secret = $sitedetails->fb_secret;

  
		// Set url elements
		$fb_app_id = $fb_appid;
		$ak_secret = $fb_secret;
		$token = 'AA|'.$fb_app_id.'|'.$ak_secret;
		// Get access token
		$url = 'https://graph.accountkit.com/v1.0/access_token?grant_type=authorization_code&code='.$_POST["code"].'&access_token='.$token;

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);

		$info = Json::decode($result);
	    print_r($info);

		// Get account information
		$url = 'https://graph.accountkit.com/v1.0/me/?access_token='.$info['access_token'];

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		$final = Json::decode($result);

	  if(!empty($final['id'])) {
		   $id = Yii::$app->user->id;
		   $loguserdetails = Users::find()->where(['userId'=>$id])->one(); 
		  $loguserdetails->sms_country_code = $final['phone']['country_prefix'];
		  $loguserdetails->phone = $final['phone']['national_number'];
		  $loguserdetails->mobile_status = '1';
		  $loguserdetails->save(false);


		  echo '1'; die;
  
		  }
		  else
		  {
			  echo '0'; die;
		  }
	  }



	  //notification

	  public function actionNotification(){
	  	if (!Yii::$app->user->id) {            
               return $this->redirect(['site/login']);    
            }

		
	    $userId = Yii::$app->user->id;
		$model = Users::find()->where(['userId'=>$userId])->one();
		$user = Users::find()->where(['userId'=>$userId])->one();

		$userCreatedDate = $model->created_at;

		if($model->unreadNotification != 0){
			$model->unreadNotification = 0;
			$model->save(false);
		}

		$followersModel = Followers::find()->where(['userId'=>$userId])->all();
		$followers = array();

		foreach ($followersModel as $follower){
			$followers[] = $follower->follow_userId;
			 $follower->follow_userId;
		}
        
		 $logModel = Logs::find()->where(['userid'=>$followers])
		->andWhere(['like', 'type', 'add'])
		->orWhere(['notifyto'=>$userId])
		->orWhere(['type' => 'admin'])
		->andWhere(['>', 'createddate', $userCreatedDate])
		->orderBy(['id' => SORT_DESC])
		->limit(32)
		->all();

		//echo 'get exchange product';
		//echo '<pre>'; print_r($logModel); 
		//exit;
        
	    return $this->render('notification',['logModel'=>$logModel, 'model'=>$model,'user'=>$user]);
	}

	public function actionNotificationloadmore(){

		if (isset($_GET['notifyLimit'])) {
		$limit=$_GET['notifyLimit'];
	}
	if (isset($_GET['notifyOffset'])) {
		$offset=$_GET['notifyOffset'];
	}
	
		$userId = Yii::$app->user->id;
		$model = Users::find()->where(['userId'=>$userId])->one();
		$userCreatedDate = $model->created_at;

		$followersModel = Followers::find()->where(['userId'=>$userId])->all();
		$followers = array();

		foreach ($followersModel as $follower){
			$followers[] = $follower->follow_userId;
		}

		$logModel = Logs::find()
		->andWhere(['userid'=>$followers])
		->andWhere(['like', 'type', 'add'])
		->orWhere(['notifyto'=>$userId])
		->orWhere(['type' => 'admin'])
		->andWhere(['>', 'createddate', $userCreatedDate])
		->orderBy(['id' => SORT_DESC])
		->limit($limit)
		->offset($offset)
		->all();
	

		if(!empty($logModel)){

			 return $this->renderPartial('notificationloadmore',['logModel'=>$logModel]);
		}else{
			echo 0;
		}
	}


	//exchanges Action
		public function actionExchanges($type)
	{

			if (!Yii::$app->user->id) {            
               return $this->redirect(['site/login']);    
            }


		$userId = Yii::$app->user->id;
		$model =  Users::find()->where(['userId'=>$userId])->one();

		if (isset($_SESSION['exchange_message'])){
		Yii::$app->session->setFlash('warning', $_SESSION['exchange_message']);
	//	echo $_SESSION['exchange_message'];
			unset($_SESSION['exchange_message']);
		}
		$user =  Users::find()->where(['userId'=>$userId])->one();
		
		if($type == 'incoming') {
			$incriteria = Exchanges::find();
			$incriteria->where(['requestTo'=>$userId]);
			$incriteria->andWhere(['or',['status'=>0],['status'=>1]]);
			$incriteria->orderBy(['date' => SORT_DESC]);
			$count = $incriteria->count();
			$pages = new Pagination(['totalCount' => $count,'pageSize'=>8,'route'=>'user/exchanges']);
			
			$incriteria->offset($pages->offset);
			$incriteria->limit(8);
           $exchanges = $incriteria->all();
             $count =count($exchanges);
             //'pages'=>$pages


			return $this->render('exchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]);
		}

		if($type == 'outgoing') {
			$outcriteria = Exchanges::find();
			$outcriteria->where(['requestFrom'=>$userId]);
			$outcriteria->andWhere(['or',['status'=>0],['status'=>1]]);
			$outcriteria->orderBy(['date' => SORT_DESC]);
			$count = $outcriteria->count();
			$pages = new Pagination(['totalCount' => $count,'pageSize'=>8,'route'=>'user/exchanges']);
		
			$outcriteria->offset($pages->offset);
			$outcriteria->limit(8);
         $exchanges = $outcriteria->all();
             $count =count($exchanges);
               //'pages'=>$pages,
			//$this->render('index',compact('model','exchanges','pages','type','userId','user'));
             	return $this->render('exchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]);
		}
		if($type == 'success') {
			$scriteria = Exchanges::find();
			$scriteria->where(['requestTo'=>$userId]);
			$scriteria->orWhere(['requestFrom'=>$userId]);
			$scriteria->andWhere(['status'=>self::SUCCESS]);
			$scriteria->orderBy(['date' => SORT_DESC]);
			$count = $scriteria->count();
			$pages = new Pagination(['totalCount' => $count,'pageSize'=>8,'route'=>'user/exchanges']);
			$scriteria->offset($pages->offset);
			$scriteria->limit(8);
         $exchanges = $scriteria->all();
             $count =count($exchanges);

			return $this->render('exchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]);
		}
		if($type == 'failed') {
			$fcriteria = Exchanges::find();
			$decline = self::DECLINE;
			$cancel = self::CANCEL;
			$failed = self::FAILED;
			$soldout = self::SOLDOUT;
			$fcriteria->andWhere(['=','requestTo',$userId]);
			$fcriteria->orWhere(['requestFrom'=>$userId]);
			$fcriteria->andWhere(['IN','status',[$decline,$failed,$cancel,$soldout]]);
			$fcriteria->orderBy(['date' => SORT_DESC]);

           
			$count = $fcriteria->count();
			$pages = new Pagination(['totalCount' => $count,'pageSize'=>8,'route'=>'user/exchanges']);
			
			$fcriteria->offset($pages->offset);
			$fcriteria->limit(8);
			$exchanges = $fcriteria->all();
		
			return $this->render('exchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]);
		}
		if($type != 'incoming'){ if($type!= 'outgoing') { if($type != 'success') { if($type != 'failed') {
			return $this->redirect(array('exchanges','type' => 'incoming'));
		} }}}
	}



		public function actionGetexchanges($type)
	{
           $this->layout="main";
			if (!Yii::$app->user->id) {            
               return $this->redirect(['site/login']);    
            }


		$userId = Yii::$app->user->id;
		$model =  Users::find()->where(['userId'=>$userId])->one();

		if (isset($_SESSION['exchange_message'])){
		Yii::$app->session->setFlash('warning', $_SESSION['exchange_message']);
	//	echo $_SESSION['exchange_message'];
			unset($_SESSION['exchange_message']);
		}
		$user =  Users::find()->where(['userId'=>$userId])->one();
		
		if($type == 'incoming') {
			$incriteria = Exchanges::find();
			$incriteria->where(['requestTo'=>$userId]);
			$incriteria->andWhere(['or',['status'=>0],['status'=>1]]);
			$incriteria->orderBy(['date' => SORT_DESC]);
			$count = $incriteria->count();
			$pages = new Pagination(['totalCount' => $count,'pageSize'=>8,'route'=>'user/exchanges']);
			
			$incriteria->offset($pages->offset);
			$incriteria->limit(8);
           $exchanges = $incriteria->all();
             $count =count($exchanges);
             //'pages'=>$pages


			return $this->renderPartial('getexchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]);
		}

		if($type == 'outgoing') {
			$outcriteria = Exchanges::find();
			$outcriteria->where(['requestFrom'=>$userId]);
			$outcriteria->andWhere(['or',['status'=>0],['status'=>1]]);
			$outcriteria->orderBy(['date' => SORT_DESC]);
			$count = $outcriteria->count();
			$pages = new Pagination(['totalCount' => $count,'pageSize'=>2,'route'=>'user/exchanges']);
			
			$outcriteria->offset($pages->offset);
			$outcriteria->limit(2);
         $exchanges = $outcriteria->all();
             $count =count($exchanges);
      

			return $this->renderPartial('getexchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]);
		}
		if($type == 'success') {
			$scriteria = Exchanges::find();
			$scriteria->where(['requestTo'=>$userId]);
			$scriteria->orWhere(['requestFrom'=>$userId]);
			$scriteria->andWhere(['status'=>self::SUCCESS]);
			$scriteria->orderBy(['date' => SORT_DESC]);
			$count = $scriteria->count();
			$pages = new Pagination(['totalCount' => $count,'pageSize'=>8,'route'=>'user/exchanges']);
			
			$scriteria->offset($pages->offset);
			$scriteria->limit(8);
         $exchanges = $scriteria->all();
             $count =count($exchanges);

		

			return $this->renderPartial('getexchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]);
		}
		if($type == 'failed') {
			$fcriteria = Exchanges::find();
			$decline = self::DECLINE;
			$cancel = self::CANCEL;
			$failed = self::FAILED;
			$soldout = self::SOLDOUT;
			$fcriteria->andWhere(['=','requestTo',$userId]);
			$fcriteria->orWhere(['requestFrom'=>$userId]);
			$fcriteria->andWhere(['IN','status',[$decline,$failed,$cancel,$soldout]]);
			$fcriteria->orderBy(['date' => SORT_DESC]);

          	$count = $fcriteria->count();
			$pages = new Pagination(['totalCount' => $count,'pageSize'=>8,'route'=>'user/exchanges']);
			
			$fcriteria->offset($pages->offset);
			$fcriteria->limit(8);
			$exchanges = $fcriteria->all();
			

			return $this->renderPartial('getexchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]);
		}

	}



	public function actionPromotions($limit = null,$offset = null,$id=null) {

		
		
		if (isset($_POST['limit'])) {
			$limit=$_POST['limit'];
		}
		if (isset($_POST['offset'])) {
			$offset=$_POST['offset'];
		}

		if (isset($_POST['id'])) {
				$id=$_POST['id'];
			}
		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			 $id = Yii::$app->user->id;

		}

		$id = Yii::$app->user->id;

	
		$model = Users::find()->where(['userId'=>$id])->one();
		$user = Users::find()->where(['userId'=>$id])->one();
	
		$products = Products::find()->where(['userId'=>$id])
		           ->andWhere(['promotionType'=>2]) 
		           ->orderBy(['productId' => SORT_DESC])
		           ->limit(8)
		           ->offset($offset)
		           ->all();

		if(Yii::$app->request->isAjax) {
		return	$this->renderPartial('loadpromotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user]);
		} else {
		 return	$this->render('promotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user]);

		}

	}




	public function actionAdvertisepromotions($limit = null,$offset = null,$id=null) {

		
		if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}
	

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}
		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				return $this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}

		$id = Yii::$app->user->id;
	//	$model->nowid = $id;
		$model = Users::find()->where(['userId'=>$id])->one();
		$user = Users::find()->where(['userId'=>$id])->one();

		$promotionDetails = Promotions::find()->all();

		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$urgentPrice = $siteSettings->urgentPrice;
		$promotionCurrency = $siteSettings->promotionCurrency;

		$existproducts = Products::find()->all();

		foreach ($existproducts as $key => $value) {
				$existproductIds[] = $value->productId;
			}

     $products = Promotiontransaction::find()->where(['userId'=>$id])
		           ->andWhere(['promotionName'=>'adds']) 
		           ->andWhere(['status'=>'Live']) 
		           ->andWhere(['in','productId',$existproductIds])
		           ->orderBy(['id' => SORT_DESC])
		           //->groupBy(['productId'])
		           ->limit(8)
		           ->offset($offset)
		         //  ->joinWith('product')
		           ->all();

			if(Yii::$app->request->isAjax) {
		return	$this->renderPartial('loadpromotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user]);
		} else {
		 return	$this->render('promotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user]);

		}
	}


		public function actionExpiredpromotions($limit = null,$offset = null,$id=null) {

		
				if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}
		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}

		//$id = Yii::$app->user->id;
	//	$model->nowid = $id;
		$model = Users::find()->where(['userId'=>$id])->one();
		$user = Users::find()->where(['userId'=>$id])->one();

		$promotionDetails = Promotions::find()->all();

		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$urgentPrice = $siteSettings->urgentPrice;
		$promotionCurrency = $siteSettings->promotionCurrency;

	
		  $liveproducts = Promotiontransaction::find()->where(['userId'=>$id])
		           ->andWhere(['status'=>'Live']) 
		           ->orderBy(['id' => SORT_DESC])
		           //->groupBy(['productId'])
		        //   ->limit(8)
		          // ->offset($offset)
		           //  ->joinWith('product')
		           ->all();



		foreach ($liveproducts as $key => $value) {
				$productIds[] = $value->productId;
			}

				$existproducts = Products::find()->all();

		foreach ($existproducts as $key => $value) {
				$existproductIds[] = $value->productId;
			}
			if(count($productIds)==0)
			{
				
				   $products = Promotiontransaction::find()->where(['userId'=>$id])
		           ->andWhere(['status'=>'Expired']) 
		            ->andWhere(['in','productId',$existproductIds])
		           ->orderBy(['id' => SORT_DESC])
		           //->groupBy(['productId'])
		           ->limit(8)
					->offset($offset)
					
		         //  ->joinWith('product')
		            ->select('productId')
                    ->distinct()->all(); 
			}
			else{

   		$products = Promotiontransaction::find()->where(['userId'=>$id])
		           ->andWhere(['status'=>'Expired']) 
		            ->andWhere(['not in','productId',$productIds])
		             ->andWhere(['in','productId',$existproductIds])
		           //->andWhere('!=', 'productId', $productIds) 
		           ->orderBy(['id' => SORT_DESC])
		           //->groupBy(['productId'])
		           ->limit(8)
					->offset($offset)
					
		         //  ->joinWith('product')
		            ->select('productId')
                    ->distinct()->all(); 
                }
                

              /* foreach ($expiryproducts as $key => $value) {
				$expiredproductIds[] = $value->productId;
				}

		           if(count($productIds)>0)
		       			$products = array_diff($expiredproductIds, $productIds);
		           else
		         		$products=$expiredproductIds;


		

		         	$products = array_diff($expiredproductIds, $productIds);*/
		         


		  

		if(Yii::$app->request->isAjax) {
		return	$this->renderPartial('loadpromotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user]);
		} else {
		 return	$this->render('promotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user]);

		}

	}





	public function actionLiked($limit = null,$offset = null,$id=null) {


	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}

		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}
		//echo $id;die;
		$user = Users::find()->where(['userId'=>$id])->one();
	
		$favorites = Favorites::find()->where(['userId'=>$id])->all();
		         

		$criteria = Products::find();
		$productIds = array();
		if(!empty($favorites)) {
			foreach($favorites as $favorite):
			$productIds[] = $favorite->productId;
			endforeach;
		}

		$userid = $id;
		if(!empty($userid)){
			
	    $follower = Followers::find()->where(['userId'=>$userid])->all();
		$followerIds = array();
		if(!empty($follower)) {
			foreach($follower as $follower):
			$followerIds[] = $follower->follow_userId;
			endforeach;
		}
		}
		else
		{
			$follower="";
			$followerIds="";
		}

	
       $review = Reviews::find()->where(['receiverId'=>$id])->all();


		$count = count($review);


		$criteria->where(['productId'=>$productIds]);
		$criteria->orderBy(['productId' => SORT_DESC]);
		$limit = 15;
		$criteria->limit($limit);
		if(isset($offset)) {
			$criteria->offset($offset);
		}
		$forlikes = '1';
		// $action = 'liked';
		$products = $criteria->all();


  			
	  $promotionDetails = Promotions::find()->all();
	  $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
	  $urgentPrice = $siteSettings->urgentPrice;
	  $promotionCurrency = $siteSettings->promotionCurrency;


		if(Yii::$app->request->isAjax) {
		return	$this->renderPartial('loadliked',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'count'=>$count,'follower'=>$follower,'followerIds'=>$followerIds,'promotionDetails'=>$promotionDetails,
		'promotionCurrency'=>$promotionCurrency]);
		} else {

			return $this->render('profiles',['user'=>$user,'products'=>$products,'promotionCurrency'=>$promotionCurrency,
			'productIds'=>$productIds,'limit'=>$limit,'offset'=>$offset,'count'=>$count,'follower'=>$follower,'followerIds'=>$followerIds,'promotionDetails'=>$promotionDetails]);
		}

	}






// 	///following & followers

	public function actionFollower($limit = 15,$offset = 0,$id=null) {
		//echo $id;


	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}
		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}


		//echo $id;die;
		$user = Users::find()->where(['userId'=>$id])->one();


		$favorites = Favorites::find()->where(['userId'=>$id])->all();

		$productIds = array();
		if(!empty($favorites)) {
			foreach($favorites as $favorite):
			$productIds[] = $favorite->productId;
			endforeach;
		}


		$userid = $id;

		$productss = Products::find()->where(['productId'=>$productIds])
		           ->orderBy(['productId' => SORT_DESC])
		           ->limit(15)
		           ->offset($offset)
				   ->all();

		if(!empty($userid)){
		
			  $follower = Followers::find()->where(['userId'=>$userid])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();


			$followerIds = array();
			if(!empty($follower)) {
				foreach($follower as $follower):
				$followerIds[] = $follower->follow_userId;
				endforeach;
			}
		}
		else
		{
			$follower="";
			$followerIds="";
		}

				
		$review = Reviews::find()->where(['receiverId'=>$id])->all();
		$count = count($review);

		
        $followerlist = Followers::find()->where(['follow_userId'=>$id])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();

		$followerlistIds = array();
		$productIds = array();
		$products = Products::find()->where(['productId'=>$productIds])
		->orderBy(['productId' => SORT_DESC])
		->limit(15)
		->all();
		$promotionDetails = Promotions::find()->all();
		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$urgentPrice = $siteSettings->urgentPrice;
		$promotionCurrency = $siteSettings->promotionCurrency;


        
		if(Yii::$app->request->isAjax) {
		return $this->renderPartial('follower',['user'=>$user,'followerlist'=>$followerlist,'followerIds'=>$followerIds,'products'=>$products,'promotionDetails'=>$promotionDetails,'promotionCurrency'=>$promotionCurrency]);
		} else {
		 return	$this->render('profiles',['user'=>$user,'followerlist'=>$followerlist,'followerIds'=>$followerIds,'products'=>$productss,'promotionDetails'=>$promotionDetails,'promotionCurrency'=>$promotionCurrency]);
		}

	
	}



	

	public function actionFollowing($limit = 15,$offset = 0,$id=null) {
		//print_r($offset);
if(isset($_POST['id'])){
			$id = $_POST['id'];
		}

		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];

		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}
		//echo $id;die;
		$user = Users::find()->where(['userId'=>$id])->one();
		$userid = $id;

		if(isset($_POST['limit'])){
			$limit = $_POST['limit'];
		}

		if(isset($_POST['offset'])){
			$offset = $_POST['offset'];
		}

		$favorites = Favorites::find()->where(['userId'=>$id])->all();

		$productIds = array();
		if(!empty($favorites)) {
			foreach($favorites as $favorite):
			$productIds[] = $favorite->productId;
			endforeach;
		}


		$userid = $id;

		$productss = Products::find()->where(['productId'=>$productIds])
		           ->orderBy(['productId' => SORT_DESC])
		           ->limit(15)
		           ->offset($offset)
				   ->all();


		if(!empty($userid)){
			

          $follower = Followers::find()->where(['userId'=>$userid])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();

			$followerIds = array();
			if(!empty($follower)) {
				foreach($follower as $follower):
				$followerIds[] = $follower->follow_userId;
				endforeach;
			}
		}
		else
		{
			$follower="";
			$followerIds="";
		}

		$review = Reviews::find()->where(['receiverId'=>$id])->all();
		$count = count($review);


	    $followerlist = Followers::find()->where(['userId'=>$id])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();

		$followerlistIds = array();

		$productIds = array();
		$products = Products::find()->where(['productId'=>$productIds])
		->orderBy(['productId' => SORT_DESC])
		->limit(15)
		->all();
		$promotionDetails = Promotions::find()->all();
		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$urgentPrice = $siteSettings->urgentPrice;
		$promotionCurrency = $siteSettings->promotionCurrency;


		if(Yii::$app->request->isAjax) {
			return $this->renderPartial('following',['user'=>$user,'followerlist'=>$followerlist,'followerIds'=>$followerIds,
			'products'=>$products,'promotionDetails'=>$promotionDetails,'promotionCurrency'=>$promotionCurrency]);
		} else {
			return $this->render('profiles',['user'=>$user,'followerlist'=>$followerlist,'followerIds'=>$followerIds,'products'=>$productss,'promotionDetails'=>$promotionDetails,'promotionCurrency'=>$promotionCurrency]);
		}


	}

	public function actionGetfollow() {

		$follow_user = yii::$app->Myclass->checkPostvalue($_POST['fuserid']) ? $_POST['fuserid'] : "";

		$id = Yii::$app->user->id;
		$userdetails = Users::find()->where(['userId'=>$id])->one();
		$fusername = $userdetails->name;
		$follow_user = $_POST['fuserid'];
		$followerdetail = Users::find()->where(['userId'=>$follow_user])->one();
		//Users::findOne($follow_user);
		$curentusername = $followerdetail->name;
		$emailTo = $followerdetail->email;
		//echo $_POST['userid'];die;
		if(!empty($follow_user)){
			$getfollowmodel = Followers::find()
			                  ->where(['userId'=>$id])
			                  ->andWhere(['follow_userId'=>$follow_user])->all();
			if(empty($getfollowmodel)){
				$model = new Followers();
				$model->userId = $id;
				$model->follow_userId = $follow_user;
				$model->followedOn = date ("Y-m-d H:i:s");
				if($model->save())
				{ 
					echo "1"; 
				}else{  
				  echo "0"; 
				}
				

				$notifyMessage = 'start Following you';
				yii::$app->Myclass->addLogs("follow", $id, $follow_user, $model->id, 0, $notifyMessage);

				$userid = $follow_user;
				
                $userdevicedet = Userdevices::find()->where(['user_id'=>$userid])->all();

                /*
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
								$msg = yii::$app->Myclass->push_lang($lang);
								$messages =$fusername." ".Yii::t('app','start Following you');
								yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
							}
						}
					}
				*/
				$siteSettings =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
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
     			$followersModel = new Followers();
				$followersModel->sendEmail($emailTo,$fusername,$curentusername);
					}
					catch(\Swift_TransportException $exception)
					{
						//Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
					return $this->redirect($_SERVER['HTTP_REFERER']);
					}
			}
			else{
				echo "0";
			}
		}else{
			echo "0";
		}

		//Yii::app()->end();
	}


	public function actionDeletefollow() {
           
			$follow_user = yii::$app->Myclass->checkPostvalue($_POST['userid']) ? $_POST['userid'] : "";
			$id = Yii::$app->user->id;
			$follow_user = $_POST['userid'];
			//echo $_POST['userid'];die;
			if(!empty($follow_user)){
				$getfollowmodel = Followers::find()->where(['userId'=>$id])
				                 ->andWhere(['follow_userId'=>$follow_user])->one();
				if(!empty($getfollowmodel)){
					$followId = $getfollowmodel->id;
               

               Followers::deleteAll(['userId' =>$id, 'follow_userId' => $follow_user]);
               echo "1";
				
                   /*  $logsModel = Logs::find()->where(['like','type','follow'])
                               ->andWhere(['sourceId'=>$followId])->all();
					if($logsModel->delete())
					{
						echo "1";
					}
					else
					{
						echo "0";
					}*/
				}else{
					echo "0";
				}
			}else{
				echo "0";
			}

			//Yii::app()->end();
		}
// FollowerUser Profile View 
		public function actionProfiles($limit = null,$offset = null,$id=null) {

	

	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

   if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}
	
		if(!empty($id)) {
			 $dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			 $id = $spl[0];
			
		} else {
			$user = Yii::$app->user->id;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}
		

      
		$promotionDetails = Promotions::find()->all();

		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$urgentPrice = $siteSettings->urgentPrice;
		$promotionCurrency = $siteSettings->promotionCurrency;

		$review = Reviews::find()->where(['receiverId'=>$id])->all();
        
 
  
		$count = count($review);


		$user = Users::find()->where(['userId'=>$id])->one();

	

		$userid = $id;
		if(!empty($userid)){
	

		$follower = Followers::find()->where(['userId'=>$userid])->all();
		//echo "<pre>";print_r($follower);die;
		$followerIds = array();
		if(!empty($follower)) {
			foreach($follower as $follower):
			$followerIds[] = $follower->follow_userId;
			endforeach;
		}
		}
		else
		{
			$follower="";
			$followerIds="";
		}

		$criteria = Products::find();
		$criteria->andWhere(['userId'=>$id]);
			if($id != Yii::$app->user->id){
			$criteria->andWhere(['approvedStatus' => 1]);
		}
		$criteria->orderBy(['productId' => SORT_DESC]);
		$criteria->limit(15);
		if(isset($offset)) {
			$criteria->offset($offset);
		}
		//$action = 'profiles';
		$products = $criteria->all();
    
   
		if(Yii::$app->controller->action->id == 'review') {
		return	$this->render('review',['reviews'=>$reviews]);
		}
		else if(Yii::$app->request->isAjax) {

		return	$this->renderPartial('loadresults',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'count'=>$count,'follower'=>$follower,'followerIds'=>$followerIds,'promotionCurrency'=>$promotionCurrency, 'urgentPrice'=>$urgentPrice, 'promotionDetails'=>$promotionDetails]);
		} else {
			
		 return	$this->render('profiles',['user'=>$user,'products'=>$products,'limit'=>$limit,'offset'=>$offset,'count'=>$count,'follower'=>$follower,'followerIds'=>$followerIds,'promotionCurrency'=>$promotionCurrency, 'urgentPrice'=>$urgentPrice, 'promotionDetails'=>$promotionDetails]);

		}

	}



	//exchanges Accept DEcline
	public function actionAccept($id) {
		$user_Id = Yii::$app->user->id;
		$status = $this->loadModel($id);
		if($status->status == 0){
			$status->status = self::ACCEPT;
			$status->save(false);
			
			$exchanges = Exchanges::find()->where(['id'=>$id])->one();
			if(isset($exchanges)){
				$userid = $exchanges->requestFrom;
				$senderid = $exchanges->requestTo;
				
				$notifyTo = $userid;
				$notifyItem = $exchanges->mainProductId;
				if($user_Id == $userid){
					$notifyTo = $senderid;
					$notifyItem = $exchanges->exchangeProductId;
				}

				$notifyMessage = 'accepted your exchange request on';
				yii::$app->Myclass->addLogs("exchange", $user_Id, $notifyTo, $id, $notifyItem, $notifyMessage);

				$pushsender = $user_Id;
				$pushuser = $notifyTo;
				
				$sellerDetails = yii::$app->Myclass->getUserDetailss($notifyTo);
				$c_username = $sellerDetails->name;
				$emailTo = $sellerDetails->email;
				$userDetails = yii::$app->Myclass->getUserDetailss($user_Id);
				$r_username = $userDetails->name;
				// $criteria = new CDbCriteria;
				// $criteria->addCondition('user_id = "'.$pushuser.'"');
				$userdevicedet = Userdevices::find()->where(['user_id'=>$pushuser])->all();
              	$productRecord = Products::findOne($exchanges->exchangeProductId);
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
						$messages = $r_username." ".Yii::t('app','accepted your exchange request on')." ".$productRecord->name;
						yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
					}
				  }
				}
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
		        $mailLayout = "exchangeaccept";
				$mailSubject = 'Exchange Request with your product was Accepted';
				$productModels = new Products();
				$productModels->sendExchangeProductMail($emailTo,$c_username, $r_username,
				$mailLayout,$mailSubject);
	}
	catch(\Swift_TransportException $exception)
	{
		//Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
		//return $this->redirect($_SERVER['HTTP_REFERER']);
	}
	 Yii::$app->session->setFlash('success', Yii::t('app','Accepted successfully'));
	 $this->redirect(['exchanges','type'=>'incoming']);

		}else{
			if ($status->status == 1){
				$_SESSION['exchange_message'] = 'Exchange already marked as Accept';
				$redirectType = 'incoming';
				$ajaxOutput = 1;
			}else if ($status->status == 2 || $status->status == 3){
				$_SESSION['exchange_message'] = 'Exchange already marked as Canceled or Declined';
				$redirectType = 'failed';
				$ajaxOutput = 0;
			}

			if(!Yii::$app->request->isAjax){
				return $this->redirect(['exchanges','type'=>$redirectType]);
			}else{
				return $this->redirect(['exchanges','type'=>$redirectType]);
			}
		
		}
	}
	public function actionDecline($id) {
		$user_Id = Yii::$app->user->id;
		$status = $this->loadModel($id);
		if($status->status == 0){
			$status->status = self::DECLINE;
			$user = Yii::$app->user->id;
			$history = array();
			if(!empty($status->exchangeHistory)) {
				$history = Json::decode($status->exchangeHistory,true);
			}
			$history[] = array('status' =>'declined','date'=>time(),'user'=>$user);
			$status->exchangeHistory = json_encode($history);
			$status->save(false);
			if (!isset($_POST['ajax'])){
				$this->redirect(array('exchanges','type'=>'failed'));
			}else{
				echo 1;
			}

			// $decriteria = new CDbCriteria;
			// $decriteria->addCondition("id = $id");
			$exchanges = Exchanges::find()->where(['id'=>$id])->one();
			if(isset($exchanges)){
				$userid = $exchanges->requestFrom;
				$senderid = $exchanges->requestTo;

				$notifyTo = $userid;
				$notifyItem = $exchanges->mainProductId;
				if($user_Id == $userid){
					$notifyTo = $senderid;
					$notifyItem = $exchanges->exchangeProductId;
				}

				$notifyMessage = 'declined your exchange request on';
				yii::$app->Myclass->addLogs("exchange", $user_Id, $notifyTo, $id, $notifyItem, $notifyMessage);

				$pushsender = $user_Id;
				$pushuser = $notifyTo;
				
				$sellerDetails = yii::$app->Myclass->getUserDetailss($notifyTo);
				$c_username = $sellerDetails->name;
				$emailTo = $sellerDetails->email;
				$userDetails = yii::$app->Myclass->getUserDetailss($user_Id);
				$r_username = $userDetails->name;
			
				$userdevicedet = Userdevices::find()->where(['user_id'=>$pushuser])->all();
				
				$productRecord = Products::findOne($exchanges->exchangeProductId);
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
						$messages = $r_username." ".Yii::t('app','declined your exchange request on')." ".$productRecord->name;
						yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
					}
					}
				}
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
			  
				

				$mailLayout = "exchangedecline";
				$mailSubject = 'Exchange Request with your product was Declined';

				try
					{
     				$productModels = new Products();
					 $productModels->sendExchangeProductMail($emailTo,$c_username, $r_username,
				  	$mailLayout,$mailSubject);
					}
					catch(\Swift_TransportException $exception)
					{
						//Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
					//return $this->redirect($_SERVER['HTTP_REFERER']);
					}

					Yii::$app->session->setFlash('success', Yii::t('app','Declined successfully'));
					$this->redirect(array('exchanges','type'=>'failed'));
		}else{
			if ($status->status == 1){
				$_SESSION['exchange_message'] = Yii::t('app','Exchange already marked as Accept');
				$redirectType = 'incoming';
				$ajaxOutput = 0;
			}else if ($status->status == 2 || $status->status == 3){
				$_SESSION['exchange_message'] = Yii::t('app','Exchange already marked as Canceled or Declined');
				$redirectType = 'failed';
				$ajaxOutput = 1;
			}

			if(!isset($_POST['ajax'])){
				$this->redirect(array('exchanges','type'=>$redirectType));
			}else{
				echo $ajaxOutput;
			}
		
		}

	}

	public function actionCancelexchange($id) {
		$status = $this->loadModel($id);
		$status->blockExchange = '1';
		$status->save(false);
	}
	public function actionAllowexchange($id) {
		$status = $this->loadModel($id);
		$status->blockExchange = '0';
		$status->save(false);
	}

	//action Success

	public function actionSuccess($id) {
		
		$user_Id = Yii::$app->user->id;
		$status = $this->loadModel($id);
		if($status->status == 1){
			$status->status = self::SUCCESS;
			$user = Yii::$app->user->id;
			$history = array();
			if(!empty($status->exchangeHistory)) {
				$history = Json::decode($status->exchangeHistory,true);
			}
			$history[] = array('status' =>'success','date'=>time(),'user'=>$user);
			$status->reviewFlagSender = 1;
			$status->reviewFlagReceiver = 1;
			$status->exchangeHistory = Json::encode($history);
			$status->save(false);

			$mainProduct = Products::findOne($status->mainProductId);

			$mainProduct->soldItem = 1;
			if($mainProduct->promotionType != 3){
	
				$promotionModel = Promotiontransaction::find()->where(['productId'=>$mainProduct->productId])
				->andWhere(['like', 'status', 'live'])
	            ->one(); 


				if(!empty($promotionModel)){
					if($promotionModel->promotionName != 'urgent'){
					
						$previousPromotion = Promotiontransaction::find()->where(['productId'=>$promotionModel->productId])
						->andWhere(['like', 'status', 'Expired'])
						->all(); 

						if(!empty($previousPromotion)){
							$previousPromotion->status = "Canceled";
							$previousPromotion->save(false);
						}
					}
					$promotionModel->status = "Expired";
					$promotionModel->save(false);
				}
			}
			$mainProduct->promotionType = 3;

			$mainProduct->quantity--;
			$mainProduct->save(false);

			$exProduct = Products::findOne($status->exchangeProductId);
			$exProduct->soldItem = 1;

			if($exProduct->promotionType != 3){
			
				$promotionModel = Promotiontransaction::find()->where(['productId'=>$exProduct->productId])
				->andWhere(['like', 'status', 'live'])
				->one(); 

				if(!empty($promotionModel)){
					if($promotionModel->promotionName != 'urgent'){
					
						$previousPromotion = Promotiontransaction::find()->where(['productId'=>$promotionModel->productId])
						->andWhere(['like', 'status', 'Expired'])
						->all(); 


						if(!empty($previousPromotion)){
							$previousPromotion->status = "Canceled";
							$previousPromotion->save(false);
						}
					}
					$promotionModel->status = "Expired";
					$promotionModel->save(false);
				}
			}
			$exProduct->promotionType = 3;

			$exProduct->quantity--;
			$exProduct->save(false);

			// $sucriteria = new CDbCriteria;
			// $sucriteria->addCondition("id = $id");
			$exchanges = Exchanges::find()->where(['id'=>$id])->one();
			if(isset($exchanges)){
			$userid = $exchanges->requestFrom;
			$senderid = $exchanges->requestTo;

			$notifyTo = $userid;
			$notifyItem = $exchanges->mainProductId;
			if($user_Id == $userid){
				$notifyTo = $senderid;
				$notifyItem = $exchanges->exchangeProductId;
			}
			$notifyMessage = 'successed your exchange request on';
			yii::$app->Myclass->addLogs("exchange", $user_Id, $notifyTo, $id, $notifyItem, $notifyMessage);

			$pushsender = $user_Id;
			$pushuser = $notifyTo;
			/*if($user_Id == $userid){
				$pushuser = $senderid;
				$pushsender = $userid;
			}*/
			$sellerDetails = yii::$app->Myclass->getUserDetailss($notifyTo);
			$c_username = $sellerDetails->name;
			$emailTo = $sellerDetails->email;
			$userDetails = yii::$app->Myclass->getUserDetailss($user_Id);
			$r_username = $userDetails->name;

			// $criteria = new CDbCriteria;
			// $criteria->addCondition('user_id = "'.$pushuser.'"');
			$userdevicedet = Userdevices::find()->where(['user_id'=>$pushuser])->all();

			if($notifyTo==$exchanges->requestFrom)
				$productRecord = Products::findOne($exchanges->exchangeProductId);
			else if($notifyTo==$exchanges->requestTo)
				$productRecord = Products::findOne($exchanges->mainProductId);
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
					$messages = $r_username." ".Yii::t('app','successed your exchange request on')." ".$productRecord->name;
					yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
				}
				}
			}
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
			 
			
				$mailLayout = "exchangesuccess";
				$mailSubject = Yii::t('app','Exchange Request with your product was Successed');

				try
					{
     		$productModels = new Products();
				 $productModels->sendExchangeProductMail($emailTo,$c_username, $r_username,
				  $mailLayout,$mailSubject);
					}
					catch(\Swift_TransportException $exception)
					{
						//Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
					return $this->redirect(array('exchanges','type'=>'success'));
					}

 	//Testing-MailFunction
				
 
		 return	$this->redirect(array('exchanges','type'=>'success'));
		 Yii::$app->session->setFlash('success', Yii::t('app','Exchange is marked as Success'));
		}else{
			if ($status->status == 4){
				$_SESSION['exchange_message'] = 'Exchange already marked as Success';
				$redirectType = 'incoming';
				$ajaxOutput = 0;
			}else if ($status->status == 5){
				$_SESSION['exchange_message'] = 'Exchange already marked as Failed';
				$redirectType = 'failed';
				$ajaxOutput = 1;
			}

			if(!isset($_POST['ajax'])){
				return	$this->redirect(array('exchanges','type'=>$redirectType));
			}else{
				echo $ajaxOutput;
			}
			/* $_SESSION['exchange_message'] = Yii::t('app','Exchange already marked as Failed');
			$this->redirect(array('index','type'=>'failed')); */
		}
	}

	public function actionSold($id) {
		$user_Id = Yii::$app->user->id;
		$status = $this->loadModel($id);
		$status->status = self::SOLDOUT;
		$user = Yii::$app->user->id;
		$history = array();
		if(!empty($status->exchangeHistory)) {
				$history = Json::decode($status->exchangeHistory,true);
			}
		$history[] = array('status' =>'failed','date'=>time(),'user'=>$user);
			$status->exchangeHistory = Json::encode($history);
		$status->save(false);

			$socriteria = Exchanges::find();
			$socriteria->where(["id" => $id]);
			$exchanges = $socriteria->one();
			if(isset($exchanges)){
			$userid = $exchanges->requestFrom;
			$senderid = $exchanges->requestTo;

			$notifyTo = $userid;
			$notifyItem = $exchanges->mainProductId;
			if($user_Id == $userid){
				$notifyTo = $senderid;
				$notifyItem = $exchanges->exchangeProductId;
			}
			$notifyMessage = 'failed your exchange request on';
			yii::$app->Myclass->addLogs("exchange", $user_Id, $notifyTo, $id, $notifyItem, $notifyMessage);

			$pushsender = $senderid;
			$pushuser = $userid;
			if($user_Id == $userid){
				$pushuser = $senderid;
				$pushsender = $userid;
			}
			$sellerDetails = yii::$app->Myclass->getUserDetailss($pushsender);
			$userdevicedet = Userdevices::find()->where(['user_id'=>$pushuser])->all();

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
					$messages =  Yii::t('app','Exchange Request sold out from')." ".$sellerDetails->username;
					yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
				}
				}
			}
			}

		return $this->redirect(['exchanges','type' => 'failed']);
	}
	public function actionFailed($id) {
		$user_Id = Yii::$app->user->id;
		$status = $this->loadModel($id);
		if($status->status == 1){
			$status->status = self::FAILED;
			$user = Yii::$app->user->id;
			$history = array();
			if(!empty($status->exchangeHistory)) {
				$history = Json::decode($status->exchangeHistory,true);
			}
			$history[] = array('status' =>'failed','date'=>time(),'user'=>$user);
			$status->exchangeHistory = Json::encode($history);

			$status->save(false);

		
			$exchanges = Exchanges::find()->where(['id'=>$id])->one();

			if(isset($exchanges)){
			$userid = $exchanges->requestTo;
			$senderid = $exchanges->requestFrom;

			$notifyTo = $userid;
			$notifyItem = $exchanges->mainProductId;
			if($user_Id == $userid){
				$notifyTo = $senderid;
				$notifyItem = $exchanges->exchangeProductId;
			}
			$notifyMessage = 'failed your exchange request on';
			yii::$app->Myclass->addLogs("exchange", $user_Id, $notifyTo, $id, $notifyItem, $notifyMessage);

			$pushsender = $user_Id;
			$pushuser = $notifyTo;
			/*if($user_Id == $userid){
				$pushuser = $senderid;
				$pushsender = $userid;
			}*/
			$sellerDetails =yii::$app->Myclass->getUserDetailss($notifyTo);
			$c_username = $sellerDetails->name;
			$emailTo = $sellerDetails->email;
			$userDetails = yii::$app->Myclass->getUserDetailss($user_Id);
			$r_username = $userDetails->name;
			// $criteria = new CDbCriteria;
			// $criteria->addCondition('user_id = "'.$pushuser.'"');
			$userdevicedet = Userdevices::find()->where(['user_id'=>$pushuser])->all();


			if($notifyTo==$exchanges->requestFrom)
				$productRecord = Products::findOne($exchanges->exchangeProductId);
			else if($notifyTo==$exchanges->requestTo)
				$productRecord = Products::findOne($exchanges->mainProductId);
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
					$messages = $r_username." ".Yii::t('app','failed your exchange request on')." ".$productRecord->name;
					yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
				}
			}
		}
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
	


			$mailLayout = "exchangefailed";
			$mailSubject = 'Exchange Request with your product was Failed';

			try
					{
     		$productModels = new Products();
				 $productModels->sendExchangeProductMail($emailTo,$c_username, $r_username,$mailLayout,$mailSubject);
					}
					catch(\Swift_TransportException $exception)
					{
						//Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
					return $this->redirect(array('exchanges','type'=>'failed'));
					}
	//Testing-MailFunction
				
				//  $mailLayout,$mailSubject);
 
			return $this->redirect(array('exchanges','type' => 'failed'));
		}else{
			$_SESSION['exchange_message'] = 'Exchange already marked as Success';
			return $this->redirect(array('exchanges','type'=>'success'));
		}

	}


	//action Cancel
	public function actionCancel($id) {
		$user_Id = Yii::$app->user->id;
		$status = $this->loadModel($id);
		if($status->status == 0){
			$status->status = self::CANCEL;
			$user = Yii::$app->user->id;
			$history = array();
			if(!empty($status->exchangeHistory)) {
				$history = Json::decode($status->exchangeHistory,true);
			}
			$history[] = array('status' =>'cancelled','date'=>time(),'user'=>$user);
			$status->exchangeHistory = Json::encode($history);
			$status->save(false);
			if (!isset($_POST['ajax'])){
				$this->redirect(array('exchanges','type'=>'failed'));
			}else{
				echo 1;
			}

		

			$exchanges = Exchanges::find()->where(['id'=>$id])->one();

			if(isset($exchanges)){
			$userid = $exchanges->requestFrom;
			$senderid = $exchanges->requestTo;

			$notifyTo = $userid;
			$notifyItem = $exchanges->mainProductId;
			if($user_Id == $userid){
				$notifyTo = $senderid;
				$notifyItem = $exchanges->exchangeProductId;
			}
			$notifyMessage = 'canceled your exchange request on';
			yii::$app->Myclass->addLogs("exchange", $user_Id, $notifyTo, $id, $notifyItem, $notifyMessage);

			$pushsender = $user_Id;
			$pushuser = $notifyTo;
			
			$sellerDetails = yii::$app->Myclass->getUserDetailss($notifyTo);
			$c_username = $sellerDetails->name;
			$emailTo = $sellerDetails->email;
			$userDetails = yii::$app->Myclass->getUserDetailss($user_Id);
			$r_username = $userDetails->name;

			$userdevicedet = Userdevices::find()->where(['user_id'=>$pushuser])->all();


			$productRecord = Products::findOne($exchanges->mainProductId);
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
					$messages = $r_username." ".Yii::t('app','canceled your exchange request on')." ".$productRecord->name;
					yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
				}
			}
			}
		}


				$siteSettings =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				
				$mailer = Yii::$app->mailer->setTransport([
					'class' => 'Swift_SmtpTransport',
					 'host' => $siteSettings['smtpHost'],  
					 'username' => $siteSettings['smtpEmail'],
					 'password' => $siteSettings['smtpPassword'],
					 'port' => $siteSettings['smtpPort'], 
					 'encryption' =>  'tls', 
			   ]);

			   $mailLayout = "exchangecancel";
			   $mailSubject = Yii::t('app','cancelled Exchange Request with your product');

			   try
					{
     		$productModels = new Products();
					 $productModels->sendExchangeProductMail($emailTo,$c_username, $r_username,
					  $mailLayout,$mailSubject);
					}
					catch(\Swift_TransportException $exception)
					{
						//Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
					return $this->redirect(array('exchanges','type'=>'failed'));
					}
		//Testing-MailFunction
					

		}else{
			if ($status->status == 1){
				$_SESSION['exchange_message'] = 'Exchange already marked as Accept';
				$redirectType = 'outgoing';
				$ajaxOutput = 0;
			}else if ($status->status == 2 || $status->status == 3 || $status->status == 5){
				$_SESSION['exchange_message'] = 'Exchange already marked as Declined or Cancled';
				$redirectType = 'failed';
				$ajaxOutput = 1;
			}else if ($status->status == 4){
				$_SESSION['exchange_message'] = 'Exchange already marked as Success';
				$redirectType = 'success';
				$ajaxOutput = 2;
			}

			if(!isset($_POST['ajax'])){
				return $this->redirect(array('exchanges','type'=>$redirectType));
			}else{
				echo $ajaxOutput;
			}
		}

	}
	public function loadModel($id)
	{
		yii::$app->Myclass->checkPostvalue($id);
		$model=Exchanges::findOne($id);
		if($model===null)
		throw new HttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionPromotiondetails() {
		
		
		$productId = yii::$app->Myclass->checkPostvalue($_POST['id']) ? $_POST['id'] : "";
		$productId = $_POST['id'];

				
		$promot_detail = Promotiontransaction::find()->where(['productId'=>$productId])
		                ->orderBy(['id' => SORT_DESC])->one();

		// $condition = new CDbCriteria;
		//$condition->addCondition("productId = $productId");
		$product_detail = Products::find()->where(['productId'=>$productId])->one();
       
		return $this->renderPartial('promotiondetails',['promot_detail'=>$promot_detail,'product_detail'=>$product_detail]);
	}


	//edit profile

	public function actionEditprofile() {
	    $id = Yii::$app->user->id;

		if (Yii::$app->user->isGuest)            
    		return $this->goHome();          
    	
		$model = Users::find()->where(['userId'=>$id])->one();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

				$locations = $_POST['Users']['geolocationDetails'];
				$splitAddress = explode(',', $locations);
				$city = $_POST['Users']['city'];
				$state = $_POST['Users']['state'];
				$country = $_POST['Users']['country'];

				//echo $city. " ". $state. " ". $country; die;

				//echo "<pre>"; print_r($splitAddress);die;
				$getLocarray = array(
					'longitude'=>$_POST['Users']['longitude'],
					'latitude'=>$_POST['Users']['latitude'],
					'place'=>$splitAddress[0].', '.$splitAddress[1].' ,'.$splitAddress[2]);

			 	$stripe_privatekey = $_POST['Users']['stripeprivatekey'];
				$stripe_publickey = $_POST['Users']['stripepublickey'];

				if (isset($stripe_privatekey) && trim($stripe_privatekey) != ''&& isset($stripe_publickey) && trim($stripe_publickey) != '') {
			
				
							$stripe_details['stripe_privatekey'] = $stripe_privatekey;
							$stripe_details['stripe_publickey'] = $stripe_publickey;
							$model->stripe_details = Json::encode($stripe_details);
							
					
				}
				else
				{
					$model->stripe_details = "";
				}

				$model->name = $_POST['Users']['name'];  
				$model->username = $_POST['Users']['username'];
				$model->email = $_POST['Users']['email'];
				/*$model->city = $splitAddress[0];
				$model->state = $splitAddress[1];
				$model->country = $splitAddress[2];*/
				$model->city = $city;
				$model->state = $state;
				$model->country = $country;
				$model->geolocationDetails = json_encode($getLocarray);
				$model->save(false);

				Yii::$app->session->setFlash('success',Yii::t('app','User Updated successfully'));
				return $this->redirect($_SERVER['HTTP_REFERER']);
			}

				if($model->stripe_details != "" && $model->stripe_details != null){
					   $stripedetails = Json::decode($model->stripe_details,true);
					   $model->stripeprivatekey = $stripedetails['stripe_privatekey'];
					   $model->stripepublickey = $stripedetails['stripe_publickey'];
					}
				
		return $this->render('editprofile',['model'=>$model]);
	}
  
	public function actionPhonevisible()
   {
	  // $userid = yii::$app->Myclass->checkPostvalue($_POST['userid']) ? $_POST['userid'] : "";
		$userid = $_POST['userid'];
		$model = Users::find()->where(['userId'=>$userid])->one();
		$model->phonevisible = $_POST['enablestatus'];
		$model->save(false);
		return "success";
   }	
public function actionMakephonevisible()
	{   
		//print_r("expression");exit;
		//return "success";
	   $userid = yii::$app->Myclass->checkPostvalue($_POST['userid']) ? $_POST['userid'] : "";
		$userid = $_POST['userid'];
		$model = Users::findOne($userid);
		$model->phonevisible = $_POST['enablestatus'];
		$model->save(false);
		return "success";
	}

// image Upload

public function actionImageupload()
{  
echo "hi" ;
exit;
	$id=Yii::$app->user->id;
	$model = $this->findModel($id);

    $imageFile = UploadedFile::getInstance($model, 'userImage');

    $directory = Yii::getAlias('@frontend/web/profile/');
    
    if ($imageFile) {
        $uid = uniqid(time(), true);
        $fileName = $uid . '.' . $imageFile->extension;
        $filePath = $directory . $fileName;
            $update = $this->findModel($id);
            $update->userImage =$fileName;
            $update->save(false);

        if ($imageFile->saveAs($filePath)) {
            $path = '/profile/' . $fileName;
			$this->redirect($_SERVER['HTTP_REFERER']);
	
        }
    }

  //  return $this->render('imageupload',['model'=>$model]);
}

public function actionImageDelete($name)
{
    $directory = Yii::getAlias('@frontend/web/img/temp') . DIRECTORY_SEPARATOR . Yii::$app->session->id;
    if (is_file($directory . DIRECTORY_SEPARATOR . $name)) {
        unlink($directory . DIRECTORY_SEPARATOR . $name);
    }

    $files = FileHelper::findFiles($directory);
    $output = [];
    foreach ($files as $file) {
        $fileName = basename($file);
        $path = '/img/temp/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
        $output['files'][] = [
            'name' => $fileName,
            'size' => filesize($file),
            'url' => $path,
            'thumbnailUrl' => $path,
            'deleteUrl' => 'image-delete?name=' . $fileName,
            'deleteType' => 'POST',
        ];
    }
    return Json::encode($output);
}



public function actionUpload(){
	$id=Yii::$app->user->id;
	$model = $this->findModel($id);
	return $this->render('upload',['model'=>$model]);
}

public function actionVerify($details){
	//http://localhost:9002/classified/verify/ZGVtbzdAaGFwcHlzYWxlLmNvbQ==
	if($details != ""){
		$email = base64_decode($details);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		  $emailErr = "Invalid email format";
		  throw new HttpException(500,'Malicious Activity');
		}
		$userModel = Users::find()->where(['email'=>$email])->one();
		$loginUrl = Yii::$app->urlManager->createAbsoluteUrl('login');
		$homeUrl = Yii::$app->getUrlManager()->getBaseUrl().'/';
		if(!empty($userModel)) {
			if($userModel->activationStatus == 1) {
				$userId = Yii::$app->user->id;
				if(!empty($userId)) {
					if(Yii::$app->user->id == $userModel->userId) {
						Yii::$app->session->setFlash("success",Yii::t('app','Account already verified'));
						return $this->redirect($homeUrl);
					} else {
						Yii::$app->session->setFlash("error",Yii::t('app','Account Mismatch.Please try later.'));
						return $this->redirect($homeUrl);
					}
				} else {
					Yii::$app->session->setFlash("info",Yii::t('app','Account already verified, Please Login'));
					return $this->redirect($loginUrl);
				}
			} else {
				$userModel->activationStatus = 1;
				$userModel->save();
				$model=new LoginForm;
				$model->username = $userModel->email;
				$model->password = base64_decode($userModel->password_encrypt);

				//print_r("expression1");
				if($model->login()) {
					//print_r("expression");exit;
					Yii::$app->session->setFlash("success",Yii::t('app','Your account has been verified.'));
					return $this->redirect($homeUrl);
				}
			}
		}else{
			Yii::$app->session->setFlash("error",Yii::t('app','Access denied...!'));
			return $this->redirect($loginUrl);
		}
	}else{
		Yii::$app->session->setFlash("error",Yii::t('app','Access denied...!'));
		return $this->redirect($loginUrl);
	}
}

public function actionUploadfile() {
	$id = Yii::$app->user->id;
	$model = Users::find()->where(['userId'=>$id])->one();
	$user =  Users::find()->where(['userId'=>$id])->one();
	$oldUserImage = "";

	if(!is_null(trim($model->userImage)) && !empty(trim($model->userImage))) { 
		$oldUserImage = $model->userImage;
	} 
	
	if(isset($_POST['Users'])) {   
		$model->attributes = $_POST['Users'];
	
		$model->userImage = UploadedFile::getInstance($model,'userImage');
		$ext = pathinfo($model->userImage, PATHINFO_EXTENSION);
		$allowedExtensions = array("jpg","jpeg","png");
		$uploadFlag = 0;

		if(!is_null($model->userImage) && in_array($ext, $allowedExtensions) && $model->validate()) {
			$usrImage = rand(00000,99999).'_'.yii::$app->Myclass->productSlug($model->userImage);
			$model->userImage->saveAs('profile/'.$usrImage);
			$user->userImage = $usrImage;
			$uploadFlag = 1;
		} else {
			$user->userImage = $oldUserImage; 
		}  

		if($user->save(false)) {  
			if($uploadFlag == 1) {
				Yii::$app->session->setFlash('success',Yii::t('app','User Profile updated successfully'));
			} else {
				Yii::$app->session->setFlash('error',Yii::t('app','Please upload only image file'));
			}			
		}
		return $this->redirect($_SERVER['HTTP_REFERER']); 
	}
}


protected function findModel($id)
{
	if (($model = Users::find()->where(['userId'=>$id])) !== null) {
		return $model;
	}

	throw new NotFoundHttpException('The requested page does not exist.');
}




public function actionChangepassword()
{
	$id = \Yii::$app->user->id;
	$user = Users::find()->where(['userId'=>$id])->one();
	try {
		$model = new \frontend\models\PasswordForm($id);
	} catch (InvalidParamException $e) {
		throw new \yii\web\BadRequestHttpException($e->getMessage());
	}
 
	if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->changePassword()) {
		Yii::$app->session->setFlash('success', Yii::t('app','Password Changed!'));
		return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('user/editprofile'));
	}
 
	return $this->render('changepassword', [
		'model' => $model, 'user'=>$user,
	]);
}

	public function actionExchangeview($id)
	{

		$userId = Yii::$app->user->id;
		$model = Users::find()->where(['userId' => $userId])->one();
		$user =  Users::find()->where(['userId' => $userId])->one();

		yii::$app->Myclass->checkPostvalue($id);

		$exchange = Exchanges::find()->where(["slug" => $id])->one();
		//print_r($id);exit;
		$mainProduct = Products::find()->where(['productId' => $exchange->mainProductId])->one();
		$exchangeProduct = Products::find()->where(['productId' => $exchange->exchangeProductId])->one();
		if($exchange->status == self::ACCEPT || $exchange->status == 0) {
			if($exchange->status != self::SUCCESS) {
				if($mainProduct->quantity == 0 || $exchangeProduct->quantity == 0) {
					$exchange->status = self::SOLDOUT;
					$exchange->save(false);
				}
			}
			return $this->render('exchange_view',['model' => $model,'exchange' => $exchange,'mainProduct' => $mainProduct,'exchangeProduct' => $exchangeProduct,'user' => $user]);
		}else{
			$_SESSION['exchange_message'] = Yii::t('app','Exchange marked as Success or Failed');
			return $this->redirect(array('exchanges','type'=>'incoming'));
		}
	}


		public function actionMessage($id = "") {

			if (isset($_GET['from'])) {
				$from=$_GET['from'];
			}
			if (isset($_GET['to'])) {
				$to=$_GET['to'];
			}
			if (isset($_GET['sourceId'])) {
				$sourceId=$_GET['sourceId'];
			}
		if($from == Yii::$app->user->id) {
			$userId = $from;
		} else {
			$userId = $to;
		}

		$userDetails = yii::$app->Myclass->getUserDetailss(Yii::$app->user->id);


 	$condition = "SELECT * FROM `hts_chats` where (`user1` = '$from' AND `user2` = '$to') OR (`user1` = '$to' AND `user2` = '$from')  order by lastContacted DESC";
 		//$criteria->condition = "user1 = '$userId' OR user2 = '$userId' order by lastContacted DESC";
		$chatedUser = Chats::findBySql($condition)->one();


		$firstChat = "";

		if (empty($chatedUser)){
			$chat = new Chats;
			$chat->user1 = $from;
			$chat->user2 = $to;
			$chat->lastContacted = time();
			$chat->save(false);
			$chatId = $chat->chatId;

			$chatedUser = Chats::findBySql($condition)->one();
		}
		$chattingUsers = array();
		if ($chatedUser->user1 != $userId){
			$chattingUsers = $chatedUser->user1;

		}elseif($chatedUser->user2 != $userId){
			$chattingUsers = $chatedUser->user2;

		}


		//$firstChat = $firstChat == '' ? $chatedUser->chatId : $firstChat;
		$chatUser = Users::find()->where(['userId'=>$chattingUsers])->one();
		//var_dump($chatUserModel); exit;
		$chatId = $chatedUser->chatId;
		$messageModel = Messages::find()->where(['chatId'=>$chatedUser->chatId,
				'messageType'=>'exchange','sourceId' => $sourceId])->all();

		$currentChatUserImage = $chatUser->userImage;
		if(!empty($currentChatUserImage)) {
			$currentChatUserImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$currentChatUserImage);
		} else {
			$currentChatUserImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
		}
		if(!empty($userDetails->userImage)) {
			$currentUserImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userDetails->userImage);
		} else {
			$currentUserImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
		}

		// echo "<pre>";print_r($chattingUsers);print_r($messageModel);die;
		return $this->renderPartial('message',['currentUserDetails'=>$userDetails, 'chattingUsers'=>$chattingUsers,
				 'messageModel'=>$messageModel,'sourceId' => $sourceId, 'currentChatUserImage'=>$currentChatUserImage,
				'currentUserImage'=>$currentUserImage,'chatUser' => $chatUser,'chatId' =>$chatId]);
	}

	public function actionHistoryview() {

		if(isset($_POST['exchangeId']))
			$slug = $_POST['exchangeId'];
		else
			$slug = Yii::$app->request->getParam('exchangeId');

		if(isset($_POST['timeMinutes']))
			$timeMinutes = $_POST['timeMinutes'];
		else
			$timeMinutes = Yii::$app->request->getParam('timeMinutes'); 


		$timezone_name = timezone_name_from_abbr("", $timeMinutes*60, false); 

		yii::$app->Myclass->checkPostvalue($slug);

		$exchange = Exchanges::find()->where(["slug"=>$slug])->one();

		$history =  Json::decode($exchange->exchangeHistory,true);

		$count = count($history);

	$pages = new Pagination(['totalCount'=>$count,'pageSize' => 10]);

		//$pages->setPageSize(10);
		 $dataProvider=new ArrayDataProvider([
		 	'allModels' => $history,
		 	'sort'=> [
		 		'defaultOrder' => [
		 			'date'=>SORT_DESC
		 		]
		 	],
		 	'pagination' => [
		 	 		'pageSize' => 10
		 	]
		]);

		return $this->renderPartial('historyview',['history'=>$history,'pages'=>$pages,	'slug'=>$slug,'dataProvider'=>$dataProvider,'timezoneName'=>$timezone_name]);       

	}

public function actionPostmessage(){
	$BlockedUser = yii::$app->Myclass->getChatBlockValue(trim($_POST['chatId']));

     //   return $BlockedUser;
		if(($BlockedUser == 0)) {

		//	return $_POST['messageContent']; 
			if($_POST['messageContent']==1){
				//return $_POST['messageContent']; 

			    // Normal message
			
				if (isset($_POST)){
			
					//$message = yii::$app->Myclass->checkPostvalue($_POST['message']) ? $_POST['message'] : "";
					$message = $_POST['message'];
					$offerId = $_POST['offerId'];
                    
				
					$senderId = yii::$app->Myclass->checkPostvalue($_POST['senderId']) ? $_POST['senderId'] : "";
					$messageType = yii::$app->Myclass->checkPostvalue($_POST['senderId']) ? $_POST['messageType'] : "";
					$sourceId = isset($_POST['sourceId']) && $_POST['sourceId'] != "" ? $_POST['sourceId'] : 0;
					$chatId = yii::$app->Myclass->checkPostvalue($_POST['chatId']) ? $_POST['chatId'] : "";
					$timeUpdate = time();

					//$p = new CHtmlPurifier();
					$message = HtmlPurifier::process($message);
					 if($offerId!=0 && $offerId!="")
					{
							$offerADType = $_POST['offerADType'];

							$offerReceived = Messages::findOne($offerId);
							
							$senderId=$offerReceived->senderId;
							$productId=$offerReceived->sourceId;//product Id
							$productDetails = yii::$app->Myclass->getProductDetails($productId);
							$productImage =yii::$app->Myclass->getProductImage($productId);
							if($productImage!=""){
								$proImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/item/'.$productId.'/'.$productImage);	
							}
							else{
								 $proImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/item/default.jpg');
							}
							$msg = Json::decode($offerReceived->message, true);
							$offerCurrency = explode('-', $msg['currency']);
							$mkeOfferPrice=$msg['price'];
							$cartDataURL = yii::$app->Myclass->cart_encrypt($productId."-0-".$mkeOfferPrice."-".$offerId, 'joy*ccart');
							$buynow_URL=Yii::$app->urlManager->createAbsoluteUrl('revieworder2/'.$cartDataURL);
							$sitePaymentModes = yii::$app->Myclass->getSitePaymentModes();

							$userImage = yii::$app->Myclass->getUserDetailss($senderId);
							$outputData = array();
							if(!empty($userImage->userImage)) {
							//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userImage->userImage),$userImage->username);
							$outputData['userName'] = $userImage->username;
							$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userImage->userImage);
							} else {
							//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userImage->username);
							$outputData['userName'] = $userImage->username;
							$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
							}
								$date=date_create(date('Y-m-d', $timeUpdate));
				
							$outputData['chatURL'] = yii::$app->urlManagerfrontEnd->baseUrl."/message/".yii::$app->Myclass->safe_b64encode($userImage->userId.'-0');
							$outputData['chatTime'] = $timeUpdate;//date('M jS Y', $timeUpdate);
							$outputData['chatTimeWeb'] = $date;
							$outputData['message'] = $message;
							$outputData['messageContent'] = $_POST['messageContent'];

							$outputData['type'] = 'offer';
							$outputData['view_url'] = '';
							$outputData['lat'] = '';
							$outputData['lon'] = '';
						
						/* new for offer section */
								$outputData['offer_id'] = $offerId;
								$outputData['offer_type'] = $msg['type'];// acept,decline,sendreceive
								$outputData['offer_price'] = $msg['price'];
								$outputData['offer_currency'] = $offerCurrency[0];
								$outputData['offer_status'] = $msg['offerstatus'];// 0-pending,1-accept,2-decline
								$outputData['buynow_status'] = $msg['buynowstatus'];	// offer is buy or not ,0-pending,1-alreadybought
								$outputData['instant_buy'] = $productDetails->instantBuy; // 1-buy available,0-not avaiable
								$outputData['sold_item'] = $productDetails->soldItem;//1- sold,0-available
								$outputData['site_buynowPaymentMode'] = $sitePaymentModes['buynowPaymentMode'];
								$outputData['item_image'] = $proImageUrl;
								$outputData['item_id'] = $productDetails->productId;
								
								$outputData['buynow_url'] = $buynow_URL;
								$sellerDtls = yii::$app->Myclass->getUserDetailss($productDetails->userId);
								$outputData['seller_name'] = $sellerDtls->username;
								

										 
						

						echo Json::encode($outputData);


					}

					else if ($message != ""){
                      //   return $message;
						$messageModel = new Messages();
						$messageModel->message = urlencode($message);
						$messageModel->messageType = $messageType;
						$messageModel->senderId = $senderId;
						$messageModel->sourceId = $sourceId;
						$messageModel->chatId = $chatId;
						$messageModel->messageContent = $_POST['messageContent'];
						$messageModel->createdDate = $timeUpdate;
						$messageModel->save(false);

						if($sourceId == 0){
							$chatModel = Chats::findOne($chatId);

							$chatModel->lastContacted = $timeUpdate;
							if ($chatModel->user1 == $senderId){
								$chatModel->lastToRead = $chatModel->user2;
							}else{
								$chatModel->lastToRead = $chatModel->user1;
							}
							$chatModel->lastMessage = urlencode($message);
							$chatModel->save();
						}

						$userImage = yii::$app->Myclass->getUserDetailss($senderId);
						$outputData = array();
						if(!empty($userImage->userImage)) {
							//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userImage->userImage),$userImage->username);
							$outputData['userName'] = $userImage->username;
							$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userImage->userImage);
						} else {
							//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userImage->username);
							$outputData['userName'] = $userImage->username;
							$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
						}
						$outputData['chatURL'] = yii::$app->urlManagerfrontEnd->baseUrl."/message/".yii::$app->Myclass->safe_b64encode($userImage->userId.'-0');
						$outputData['chatTime'] = $timeUpdate;//date('M jS Y', $timeUpdate);
						$outputData['message'] = $message;
						$outputData['messageContent'] = $_POST['messageContent'];

						$outputData['type'] = 'normal';
						$outputData['view_url'] = '';
						$outputData['lat'] = '';
						$outputData['lon'] = '';
						$outputData['offer_id'] = '';
											 
						if (isset($chatModel)) {
							$userid = $chatModel->lastToRead;
						}
					
						echo Json::encode($outputData);

					}else{
						echo "";
					}
				}
			}
			 else if($_POST['messageContent']==2){
				
				//image sharing
				$path ="../web/images/message/";
				if( !is_dir( $path ) ) {
					mkdir( $path );
					chmod( $path, 0777 );
				}
				
				$senderId = yii::$app->Myclass->checkPostvalue($_POST['sendingsource']) ? $_POST['sendingsource'] : "";
				$messageType = yii::$app->Myclass->checkPostvalue($_POST['sendingsource']) ? $_POST['sourccetype'] : "";
				$sourceId = isset($_POST['sourceId']) && $_POST['sourceId'] != "" ? $_POST['sourceId'] : 0;
				$chatId = yii::$app->Myclass->checkPostvalue($_POST['sourcce']) ? $_POST['sourcce'] : ""; 
				$timeUpdate = time();
				$allowedExts = array("gif", "jpeg", "jpg", "png");
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				
				    if ($_FILES["file"]["error"] > 0) {
				        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
				    }else {
						$ran_num=rand(100,1000);
				        $filename = $ran_num.'-'.$senderId.$_FILES["file"]["name"];
				      
			     
			            move_uploaded_file($_FILES["file"]["tmp_name"],
			            "../web/images/message/".$filename);
					   chmod("../web/images/message/".$filename, 0666);
				            //echo "Stored in: " . Yii::getPathOfAlias('webroot')."/media/message/" . $filename;
					    	
			            $messageModel = new Messages();
			            $messageModel->message = $filename;
			            $messageModel->messageType = $messageType;
						$messageModel->senderId = $senderId;
						$messageModel->messageContent=2;
						//echo $senderId;
						$messageModel->sourceId = $sourceId;
						//echo $sourceId;
						$messageModel->chatId = $chatId;
						//echo $chatId;
						$messageModel->createdDate = $timeUpdate;
						//echo $timeUpdate;
						$messageModel->save(false);

							
						  	
						if($sourceId == 0){
							$chatModel = Chats::findOne($chatId);

							$chatModel->lastContacted = $timeUpdate;
							if ($chatModel->user1 == $senderId){
								$chatModel->lastToRead = $chatModel->user2;
							}else{
								$chatModel->lastToRead = $chatModel->user1;
							}
							$chatModel->lastMessage = "Share an Image";
							$chatModel->save();
						}
						$userImage = yii::$app->Myclass->getUserDetailss($senderId);
						$outputData = array();
						if(!empty($userImage->userImage)) {
							$outputData['userName'] = $userImage->username;
							$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userImage->userImage);
						} else {
							$outputData['userName'] = $userImage->username;
							$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
						}
						$outputData['chatURL'] = yii::$app->urlManagerfrontEnd->baseUrl."/message/".yii::$app->Myclass->safe_b64encode($userImage->userId.'-0');
						$outputData['chatTime'] = $timeUpdate;//date('M jS Y', $timeUpdate);
						//$outputData['message'] = $filename;
						$outputData['message'] = Yii::$app->urlManager->createAbsoluteUrl('frontend/web/images/message/'.$filename);

						$outputData['messageContent'] = $_POST['messageContent'];
						$outputData['saveimage'] =$filename;
						$url=Yii::$app->urlManager->createAbsoluteUrl('frontend/web/images/message/'.$filename);
						$outputData['type'] = 'image';
						$outputData['view_url'] = $url;
						$outputData['lat'] = '';
						$outputData['lon'] = '';
						$outputData['offer_id'] = '';
					if (isset($chatModel)) {
							$userid = $chatModel->lastToRead;
					}
						
						
						echo Json::encode($outputData);
				    }
				}
				else if($_POST['messageContent']==3){

			
				// Location sharing
				if (isset($_POST)){
					//$message = yii::$app->Myclass->checkPostvalue($_POST['message']) ? $_POST['message'] : "";
					$message = $_POST['message'];
					$senderId = yii::$app->Myclass->checkPostvalue($_POST['senderId']) ? $_POST['senderId'] : "";
					$messageType = yii::$app->Myclass->checkPostvalue($_POST['senderId']) ? $_POST['messageType'] : "";
					$sourceId = isset($_POST['sourceId']) && $_POST['sourceId'] != "" ? $_POST['sourceId'] : 0;
					$chatId = yii::$app->Myclass->checkPostvalue($_POST['chatId']) ? $_POST['chatId'] : "";
					$timeUpdate = time();

					$message = HtmlPurifier::process($message);
					if ($message != ""){

						$messageModel = new Messages();
						//$messageModel->message = urlencode($message);
						$messageModel->message = $message;
						$messageModel->messageType = $messageType;
						$messageModel->senderId = $senderId;
						$messageModel->sourceId = $sourceId;
						$messageModel->chatId = $chatId;
						$messageModel->messageContent = $_POST['messageContent'];
						$messageModel->createdDate = $timeUpdate;
						$messageModel->save(false);

						if($sourceId == 0){
							$chatModel = Chats::findOne($chatId);

							$chatModel->lastContacted = $timeUpdate;
							if ($chatModel->user1 == $senderId){
								$chatModel->lastToRead = $chatModel->user2;
							}else{
								$chatModel->lastToRead = $chatModel->user1;
							}
							$chatModel->lastMessage = "Share an Location";
							$chatModel->save();
						}

						$userImage = yii::$app->Myclass->getUserDetailss($senderId);
						$outputData = array();
						if(!empty($userImage->userImage)) {
							//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userImage->userImage),$userImage->username);
							$outputData['userName'] = $userImage->username;
							$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userImage->userImage);
						} else {
							//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userImage->username);
							$outputData['userName'] = $userImage->username;
							$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
						}
						$outputData['chatURL'] = yii::$app->urlManagerfrontEnd->baseUrl."/message/".yii::$app->Myclass->safe_b64encode($userImage->userId.'-0');
						$outputData['chatTime'] = $timeUpdate;//date('M jS Y', $timeUpdate);
						$outputData['message'] = $message;
						$outputData['messageContent'] = $_POST['messageContent'];
							$latlon=explode("@#@",$message);
						$outputData['type'] = 'share_location';
						$outputData['view_url'] = '';
						$outputData['lat'] = $latlon[0];
						$outputData['lon'] = $latlon[1];
						$outputData['offer_id'] = '';
					
						
						if (isset($chatModel)) {
							$userid = $chatModel->lastToRead;
						}
					
						echo Json::encode($outputData);

					}else{
						echo "";
					}
				}
			}
		} else {
			$userId = Yii::$app->user->id;
			if($BlockedUser == $userId) {
				echo "blocked~#~defined";
			} else {
				echo "blocked~#~undefined";
		  	}			
		  }
	//	echo $senderId;
				//echo $offerId;
	}

	public function actionSociallogin($type = NULL)
	{
		if (!isset($_GET['provider']) && $type == NULL){
			$this->redirect(array('/site/login'));
			return;
		}

		try{
			if(isset($_GET['provider'])){
				$type = $_GET['provider'];
			}
			$_SESSION['provider'] = $type;
			//Yii::import('ext.components.HybridAuthIdentity');
			$haComp = new HybridAuthIdentity();

			if (!$haComp->validateProviderName($type))
			throw new HttpException ('500', 'Invalid Action. Please try again.');
			//echo "<pre>";print_r($haComp->validateProviderName($_GET['provider']));die;
			$haComp->adapter = $haComp->hybridAuth->authenticate($type);

			$haComp->userProfile = $haComp->adapter->getUserProfile();
			//echo '<pre>'; var_dump($haComp->userProfile); exit;

			if($haComp->adapter->id == 'Twitter') {
				$userStatus = $haComp->twitLogin();
			} else {
				$userStatus = $haComp->login();
			}

			if($userStatus === true) {
				Yii::app()->user->setFlash('success',Yii::t('app','You have successfully logged in.'));
				$this->redirect(Yii::app()->homeUrl);
			}elseif ($userStatus == "disabled"){
				Yii::app()->user->setFlash('success',Yii::t('app','Your account has been disabled by the Administrator.'));
				$haComp->hybridAuth->logoutAllProviders();
				$this->redirect(Yii::app()->homeUrl);
			}elseif ($userStatus == "no-email"){
				Yii::app()->user->setFlash('success',Yii::t('app','Unable to retrive your email, Please check with your social login provider'));
				$haComp->hybridAuth->logoutAllProviders();
				$this->redirect(Yii::app()->homeUrl);
			}else {
				$this->actionSocialsignup($userStatus);
			}

		}
		catch (Exception $e)
		{
			//process error message as required or as mentioned in the HybridAuth 'Simple Sign-in script' documentation
			$this->redirect(array('login'));
			return;
		}
	}
	public function actionGetsocialaccess()
	{
		if (isset($_GET['provider']))
		{
			return $this->redirect(['/site/auth','authclient'=>'facebook']);
		}

		$facebookdetails =  yii::$app->Myclass->getsocialLoginDetails();

		$userId = Yii::$app->user->id;
	
		$userdetails = Users::find()->where(['userId'=>$userId])->one();


		try
		{
			$haComp = new AuthChoice();
			//print_r($haComp);exit;

			$hybridauth_session_data = $haComp->hybridAuth->getSessionData();

			if (!$haComp->validateProviderName($_GET['provider']))
			throw new HttpException ('500', 'Invalid Action. Please try again.');

			//echo "<pre>";print_r($haComp->validateProviderName($_GET['provider'])); die;
			$haComp->adapter = $haComp->hybridAuth->authenticate($_GET['provider']);
			//echo "<pre>";print_r($haComp->adapter); die;
			$haComp->userProfile = $haComp->adapter->getUserProfile();
			//echo '<pre>'; var_dump($haComp->userProfile);  echo 'hi';
			$facebookId =  $haComp->userProfile->identifier;
			$fbdetails['email'] =  $haComp->userProfile->email;
			$fbdetails['firstName'] =  $haComp->userProfile->firstName;
			$fbdetails['lastName'] =  $haComp->userProfile->lastName;
			$fbdetails['email'] =  $haComp->userProfile->email;
			$fbdetails['phone'] =  $haComp->userProfile->phone;
			$fbdetails['profileURL'] =  $haComp->userProfile->profileURL;
			$fb_detail = Json::encode($fbdetails);

			if($haComp->userProfile->email != '' || $haComp->userProfile->phone != '') {

				$userdetails->facebookId = $facebookId;
				$userdetails->fbdetails = $fb_detail;
				$userdetails->facebook_session = $hybridauth_session_data;
				$userdetails->save(false);
				echo "<script type='text/javascript'>
						window.opener.$('.facebook-verification').show();
						window.opener.$('#fb_verify').hide();
						window.opener.$('.fb-verification').attr('id','verified');
						window.opener.$('.fb-verification').attr('title','Facebook account Verified!');
						window.opener.$('.fb-verification').attr('data-original-title','Facebook account Verified!');
						window.close();
						</script>";
			}else{
				echo "<script type='text/javascript'>
						window.opener.$('.facebook-verification-failure').show();
						window.close();
						</script>";
			}

		}
		catch (Exception $e)
		{
			//process error message as required or as mentioned in the HybridAuth 'Simple Sign-in script' documentation
			return $this->redirect(['site/login']);
			//return;
		}

	}

	public function actionVerify_mail($id){
		$userId = yii::$app->Myclass->checkPostvalue($_GET['id']) ? $_GET['id'] : "";
		$userId = $_GET['id'];

		
		$userdetails = Users::find()->where(['userId' => $userId])->one();
	
		$emailTo = $userdetails->email;
		$verifyLink = Yii::$app->getUrlManager()->getBaseUrl()."/user/";
				
		$siteSettings = Sitesettings::find()->where(['id' => SORT_DESC])->one();
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
					$reverifymail = new Users();
					$reverifymail->UserreverifyEmail($emailTo, $verifyLink,$userdetails->name);
					 Yii::$app->session->setFlash('success', Yii::t('app','Please verify your account by the mail sent to your email.'));
					  return $this->redirect(['site/login']);
					
					}
					catch(\Swift_TransportException $exception)
					{
						Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
					return $this->redirect(['site/login']);
					}

	}


	//recent activities

	public function actionMoreliked($limit = 15,$offset = 0,$id=null) {
	
	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}


		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}


		//echo $id;die;
		$user = Users::find()->where(['userId'=>$id])->one();

		$userid = Yii::$app->user->id;

		$favorites = Favorites::find()->where(['userId'=>$id])->all();

		$productIds = array();
		if(!empty($favorites)) {
			foreach($favorites as $favorite):
			$productIds[] = $favorite->productId;
			endforeach;
		}

 
		$products = Products::find()->where(['productId'=>$productIds])
		->orderBy(['productId' => SORT_DESC])
		->limit(15)
		->all();


		 return	$this->renderPartial('loadliked',['user'=>$user,'products'=>$products,'productIds'=>$productIds]);


		
	}

	public function actionMoreloadliked($limit = null,$offset = null,$id=null) {


	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}

		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}
		//echo $id;die;
		$user = Users::find()->where(['userId'=>$id])->one();
		
		$favorites = Favorites::find()->where(['userId'=>$id])->all();

		$productIds = array();
		if(!empty($favorites)) {
			foreach($favorites as $favorite):
			$productIds[] = $favorite->productId;
			endforeach;
		}

		$userid = $id;

		$products = Products::find()->where(['productId'=>$productIds])
		           ->orderBy(['productId' => SORT_DESC])
		           ->limit(15)
		           ->offset($offset)
				   ->all();

		return	$this->renderPartial('moreloadliked',['products'=>$products,'limit'=>$limit,'offset'=>$offset]);


	}



		public function actionMorefollower($limit = 15,$offset = 0,$id=null) {
	
	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}


		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}


		//echo $id;die;
		$user = Users::find()->where(['userId'=>$id])->one();

		$userid = $id;

		 $follower = Followers::find()->where(['follow_userId'=>$userid])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();

	     $followere = Followers::find()->where(['userId'=>$userid])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();
		$followerIds = array();
		if(!empty($followere)) {
			foreach($followere as $followerr):
			$followerIds[] = $followerr->follow_userId;
			endforeach;
		}
	
		 return	$this->renderPartial('follower',['user'=>$user,'followerlist'=>$follower,'followerIds'=>$followerIds]);


		
	}

	public function actionMoreloadfollower($limit = null,$offset = null,$id=null) {


	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}

		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}
	
		$user = Users::find()->where(['userId'=>$id])->one();
        $userid = $id;
		if(!empty($userid)){
			
	    $follower = Followers::find()->where(['follow_userId'=>$userid])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();

		$followerIds = array();
		if(!empty($follower)) {
			foreach($follower as $followerr):
			$followerIds[] = $followerr->follow_userId;
			endforeach;
		}
		}
		else
		{
			$follower="";
			$followerIds="";
		}
		
		

		return	$this->renderPartial('moreloadfollower',['user'=>$user,'followerlist'=>$follower,'followerIds'=>$followerIds,'limit'=>$limit,'offset'=>$offset]);


	}


		public function actionMorefollowing($limit = 15,$offset = 0,$id=null) {
	
	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}


		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}

		$user = Users::find()->where(['userId'=>$id])->one();

		$userid = $id;

		
	   if(!empty($userid)){
				
	    $following = Followers::find()->where(['userid'=>$userid])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();

		$followingIds = array();
		if(!empty($following)) {
			foreach($following as $followerr):
			$followingIds[] = $followerr->follow_userId;
			endforeach;
		}
		}
		else
		{
			$following="";
			$followingIds="";
		}

		 return	$this->renderPartial('following',['user'=>$user,'followinglist'=>$following,'followingIds'=>$followingIds,'limit'=>$limit,'offset'=>$offset]);
		
	}

	public function actionMoreloadfollowing($limit = null,$offset = null,$id=null) {


	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}

		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}
	
		$user = Users::find()->where(['userId'=>$id])->one();
$userid = $id;
		if(!empty($userid)){
			
	    $following = Followers::find()->where(['userid'=>$userid])
		           ->limit($limit)
		           ->offset($offset)
				   ->all();

		$followingIds = array();
		if(!empty($following)) {
			foreach($following as $followerr):
			$followingIds[] = $followerr->follow_userId;
			endforeach;
		}
		}
		else
		{
			$following="";
			$followingIds="";
		}
		
		

		return	$this->renderPartial('moreloadfollowing',['user'=>$user,'followinglist'=>$following,'followingIds'=>$followingIds,'limit'=>$limit,'offset'=>$offset]);


	}

// promotions
		public function actionGeturgent($limit = 15,$offset = 0,$id=null) {
	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}
		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			 $id = Yii::$app->user->id;

		}

		$id = Yii::$app->user->id;

	
		$model = Users::find()->where(['userId'=>$id])->one();
		$user = Users::find()->where(['userId'=>$id])->one();
	
		$products = Products::find()->where(['userId'=>$id])
		           ->andWhere(['promotionType'=>2]) 
		           ->orderBy(['productId' => SORT_DESC])
		           ->limit(8)
		           ->offset($offset)
		           ->all();
	
		 return	$this->renderPartial('urgentpromotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user]);


		
	}


	public function actionGetad($limit = null,$offset = null,$id=null) {

	if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}
	

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}
		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				return $this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}

		$id = Yii::$app->user->id;
	//	$model->nowid = $id;
		$model = Users::find()->where(['userId'=>$id])->one();
		$user = Users::find()->where(['userId'=>$id])->one();

		$promotionDetails = Promotions::find()->all();

		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$urgentPrice = $siteSettings->urgentPrice;
		$promotionCurrency = $siteSettings->promotionCurrency;

		$existproducts = Products::find()->all();

		foreach ($existproducts as $key => $value) {
				$existproductIds[] = $value->productId;
			}

     $products = Promotiontransaction::find()->where(['userId'=>$id])
		           ->andWhere(['promotionName'=>'adds']) 
		           ->andWhere(['status'=>'Live'])
		           ->andWhere(['in','productId',$existproductIds]) 
		           ->orderBy(['id' => SORT_DESC])
		           //->groupBy(['productId'])
		           ->limit(8)
		           ->offset($offset)
		         //  ->joinWith('product')
		           ->all();

		return	$this->renderPartial('advertisepromotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user,'promotionCurrency'=>$promotionCurrency,
		 'urgentPrice'=>$urgentPrice, 'promotionDetails'=>$promotionDetails]);

	}

	public function actionGetexpired($limit = null,$offset = null,$id=null) {
		if (isset($_POST['limit'])) {
		$limit=$_POST['limit'];
	}
	if (isset($_POST['offset'])) {
		$offset=$_POST['offset'];
	}

if (isset($_POST['id'])) {
		$id=$_POST['id'];
	}
		if(!empty($id)) {
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		} else {
			$user = Yii::$app->user;
			if(Yii::$app->user->isGuest) {
				$this->redirect(array('/site/login'));
				return false;
			}
			$id = Yii::$app->user->id;
		}

		//$id = Yii::$app->user->id;
	//	$model->nowid = $id;
		$model = Users::find()->where(['userId'=>$id])->one();
		$user = Users::find()->where(['userId'=>$id])->one();

		$promotionDetails = Promotions::find()->all();

		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$urgentPrice = $siteSettings->urgentPrice;
		$promotionCurrency = $siteSettings->promotionCurrency;

	
		  $liveproducts = Promotiontransaction::find()->where(['userId'=>$id])
		           ->andWhere(['status'=>'Live']) 
		           ->orderBy(['id' => SORT_DESC])
		           //->groupBy(['productId'])
		         //  ->limit(8)
		           //->offset($offset)
		           //  ->joinWith('product')
		           ->all();



		foreach ($liveproducts as $key => $value) {
				$productIds[] = $value->productId;
			}


  		$existproducts = Products::find()->all();

		foreach ($existproducts as $key => $value) {
				$existproductIds[] = $value->productId;
			}




			if(count($productIds)==0)
			{
				
				   $products = Promotiontransaction::find()->where(['userId'=>$id])
		           ->andWhere(['status'=>'Expired']) 
		           ->andWhere(['in','productId',$existproductIds])
		           ->orderBy(['id' => SORT_DESC])
		           //->groupBy(['productId'])
		           ->limit(8)
					->offset($offset)
					
		         //  ->joinWith('product')
		            ->select('productId')
                    ->distinct()->all(); 
			}else{
	
      	 		$products = Promotiontransaction::find()->where(['userId'=>$id])
		           ->andWhere(['status'=>'Expired']) 
		            ->andWhere(['not in','productId',$productIds])
		             ->andWhere(['in','productId',$existproductIds])

		           //->andWhere('!=', 'productId', $productIds) 
		           ->orderBy(['id' => SORT_DESC])
		           //->groupBy(['productId'])
		           ->limit(8)
					->offset($offset)
					
		         //  ->joinWith('product')
		            ->select('productId')
                    ->distinct()->all();
                }

              /* foreach ($expiryproducts as $key => $value) {
				$expiredproductIds[] = $value->productId;
				}

		           if(count($productIds)>0)
		       			$products = array_diff($expiredproductIds, $productIds);
		           else
		         		$products=$expiredproductIds;


		

		         	$products = array_diff($expiredproductIds, $productIds);*/
	


		  return $this->renderPartial('expiredpromotions',['products'=>$products,'limit'=>$limit,'offset'=>$offset,'model'=>$model,'user'=>$user,'promotionCurrency'=>$promotionCurrency, 
				'urgentPrice'=>$urgentPrice, 'promotionDetails'=>$promotionDetails]);

	}

	public function actionAdvertise123()
	{
		$models = Adverister::find()->where(['userid' => Yii::$app->user->id])->one(); 
		$setting = Sitesettings::find()->where(['id'=>1])->one();
		   $setting->bannerCurrency;
		   echo  explode('-', $setting->bannerCurrency);
		  exit;
echo	$bannerCurrency = $setting->bannerCurrency;
		$currencyDetails = explode('-', $bannerCurrency);
		$bannerCurrency = trim($currencyDetails[0]);

		return $this->render('advertise',['models'=>$models,$currenyCode=>$bannerCurrency]);
	}
	public function actionAdvertise()
	{
		
		$models = Banners::find()->where(['userid' => Yii::$app->user->id])
		->andWhere(['!=','paidstatus','0'])->orderBy(['id' => SORT_DESC])->all(); 
		
		$setting1 = Sitesettings::find()->where(['id'=>1])->one();
		$bannerCurrency = $setting1->promotionCurrency;
		$currencyDetails = explode('-', $bannerCurrency);
		 $code = trim($currencyDetails[0]); 
		 $user = Users::find()->where(['userId'=>Yii::$app->user->id])->one();
		 
		return $this->render('advertise',['models'=>$models,'code'=>$code,'user'=>$user]);
	}

	public function actionBanneradvertise()
	{ 
		$models = Banners::find()->where(['userid' => Yii::$app->user->id])->orderBy(['id' => SORT_DESC])->all(); 
		$setting1 = Sitesettings::find()->where(['id'=>1])->one();
		$bannerCurrency = $setting1->bannerCurrency;
		$currencyDetails = explode('-', $bannerCurrency);
		 $code = trim($currencyDetails[1]);
		 $user = Users::find()->where(['userId'=>Yii::$app->user->id])->one();
		 
		return $this->render('advertise',['models'=>$models,'code'=>$code,'user'=>$user]);
	}

	public function actionAdsview($id)
	{
		$user = Users::find()->where(['userId'=>Yii::$app->user->id])->one();
		$adId =  yii::$app->Myclass->safe_b64decode($_GET['id']);
		$model = Banners::find()->where(['id' => $adId])->one(); 
		$setting1 = Sitesettings::find()->where(['id'=>1])->one();
		$bannerCurrency = $setting1->bannerCurrency;
		$currencyDetails = explode('-', $bannerCurrency);
		 $code = trim($currencyDetails[1]);
		return $this->render('adsview',['model'=>$model,'code'=>$code,'user'=>$user]);
	}

	public function actionMobileverificationstatusfirebase() {

	  $sitedetails = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
	  $pno = $_POST["phone_no"];

	  if(!empty($pno)) {
		    $id = Yii::$app->user->id;
		    $loguserdetails = Users::find()->where(['userId'=>$id])->one(); 
			$loguserdetails->phone = $pno;
			$loguserdetails->mobile_status = '1';
			$loguserdetails->save(false);
			echo '1'; die;
		}
		else
		{
		   echo '0'; die;
		}
	  }

}


	
