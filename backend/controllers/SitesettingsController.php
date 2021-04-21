<?php

namespace backend\controllers;

use Yii;
use common\models\Sitesettings;
use backend\models\SitesettingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Json;
use common\models\Users;
class SitesettingsController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
            if (Yii::$app->user->isGuest) {            
                return $this->goHome();          
            }
            return true;
    }

    public function actions()
    {
     
         $model = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
         if(isset($model->sitename)) {
         Yii::$app->view->title =  $model->sitename;     
        }
        else
        {
                     Yii::$app->view->title =  "Joysale";     

        }

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
   
    public function actionIndex()
    {
        $searchModel = new SitesettingsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 $dataProvider->pagination->pageSize=10;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
        public function actionRestorapikey() {
       
        $model=$this->findModel();


        if (isset($model->id)){

            $apiDetails = json_decode($model->api_settings, true);
            $defaultUsername = $apiDetails['apicredential']['default']['username'];
            $defaultPassword = $apiDetails['apicredential']['default']['password'];
            $apiDetails['apicredential']['current']['username'] = $defaultUsername;
            $apiDetails['apicredential']['current']['password'] = $defaultPassword;

            $model->api_settings = Json::encode($apiDetails);
            if($model->save(false)) {
                Yii::$app->session->setFlash('success',Yii::t('app','API Credentials updated to default successfully'));
               return $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            Yii::$app->session->setFlash('error',Yii::t('app','Something went wrong, please try again later'));
           return $this->redirect($_SERVER['HTTP_REFERER']);
        }
    }

        public function actionSociallogin()
    {
      
        $model=$this->findModel();
        //$siteSettingsModel = Sitesettings::model()->findAllByAttributes(array('id'=>1));
        if (isset($model->id)){
            //$id = $siteSettingsModel->id;
            //$model=$this->loadModel($id);
            $socialLoginSettings = json_decode($model->socialLoginDetails, true);
            if ($socialLoginSettings['facebook']['status'] == 'enable'){
                $model->facebookstatus = '1';
            }else{
                $model->facebookstatus = '0';
            }
            $model->facebookappid = $socialLoginSettings['facebook']['appid'];
            $model->facebooksecret = $socialLoginSettings['facebook']['secret'];
            if ($socialLoginSettings['twitter']['status'] == 'enable'){
                $model->twitterstatus = '1';
            }else{
                $model->twitterstatus = '0';
            }
            $model->twitterappid = $socialLoginSettings['twitter']['appid'];
            $model->twittersecret = $socialLoginSettings['twitter']['secret'];
            if ($socialLoginSettings['google']['status'] == 'enable'){
                $model->googlestatus = '1';
            }else{
                $model->googlestatus = '0';
            }
            $model->googleappid = $socialLoginSettings['google']['appid'];
            $model->googlesecret = $socialLoginSettings['google']['secret'];
        }else{
            $model=new Sitesettings;
        }
        $model->setScenario('sociallogin');

        // Uncomment the following line if AJAX validation is needed
      //  $this->performAjaxValidation($model);

        if(isset($_POST['Sitesettings']))
        {
            $model->attributes=$_POST['Sitesettings'];
            $socialLoginSettings = array();
            if ($model->facebookstatus == '1'){
                $socialLoginSettings['facebook']['status'] = 'enable';
            }else{
                $socialLoginSettings['facebook']['status'] = 'disable';
            }
            $socialLoginSettings['facebook']['appid'] = $model->facebookappid;
            $socialLoginSettings['facebook']['secret'] = $model->facebooksecret;
            if ($model->twitterstatus == '1'){
                $socialLoginSettings['twitter']['status'] = 'enable';
            }else{
                $socialLoginSettings['twitter']['status'] = 'disable';
            }
            $socialLoginSettings['twitter']['appid'] = $model->twitterappid;
            $socialLoginSettings['twitter']['secret'] = $model->twittersecret;
            if ($model->googlestatus == '1'){
                $socialLoginSettings['google']['status'] = 'enable';
            }else{
                $socialLoginSettings['google']['status'] = 'disable';
            }
            $socialLoginSettings['google']['appid'] = $model->googleappid;
            $socialLoginSettings['google']['secret'] = $model->googlesecret;

            $model->socialLoginDetails = json_encode($socialLoginSettings);
            if(isset($_POST['Sitesettings']['facebookshare']) && $_POST['Sitesettings']['facebookshare'] == 1)
                $model->facebookshare = "1";
            else
                $model->facebookshare = "0";
            if($model->save(false)) {
                Yii::$app->session->setFlash('success',Yii::t('app','Social settings updated'));
             //   $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }

          return $this->render('sociallogin', [
            'model'=>$model,'scenario'=>'sociallogin'
        ]);

       
    }

        public function actionFootersettings() {
       
        $model=$this->loadModel();
        $makeDefault = 0;

        if (isset($model->id)){
            $footerDetails = json_decode($model->footer_settings, true);

            $model->facebookFooterLink = $footerDetails['footerDetails']['facebooklink'];
            $model->googleFooterLink = $footerDetails['footerDetails']['googlelink'];
            $model->twitterFooterLink = $footerDetails['footerDetails']['twitterlink'];
            $model->androidFooterLink = $footerDetails['footerDetails']['androidlink'];
            $model->iosFooterLink = $footerDetails['footerDetails']['ioslink'];
            $model->socialloginheading = $footerDetails['footerDetails']['socialloginheading'];
            $model->applinkheading = $footerDetails['footerDetails']['applinkheading'];
            $model->generaltextguest = $footerDetails['footerDetails']['generaltextguest'];
            $model->generaltextuser = $footerDetails['footerDetails']['generaltextuser'];
            $model->footerCopyRightsDetails = $footerDetails['footerDetails']['footerCopyRightsDetails'];
        }else{
            $model=new Sitesettings;
        }
        //$this->performAjaxValidation($model);

        if(isset($_POST['Sitesettings']))
        {
            //echo "<pre>";print_r($_POST['Sitesettings']);die;
            $model->attributes = $_POST['Sitesettings'];

            $footerDetails['footerDetails']['facebooklink'] = $_POST['Sitesettings']['facebookFooterLink'];
            $footerDetails['footerDetails']['googlelink'] = $_POST['Sitesettings']['googleFooterLink'];
            $footerDetails['footerDetails']['twitterlink'] = $_POST['Sitesettings']['twitterFooterLink'];
            $footerDetails['footerDetails']['androidlink'] = $_POST['Sitesettings']['androidFooterLink'];
            $footerDetails['footerDetails']['ioslink'] = $_POST['Sitesettings']['iosFooterLink'];
            $footerDetails['footerDetails']['socialloginheading'] = $_POST['Sitesettings']['socialloginheading'];
            $footerDetails['footerDetails']['applinkheading'] = $_POST['Sitesettings']['applinkheading'];
            $footerDetails['footerDetails']['generaltextguest'] = $_POST['Sitesettings']['generaltextguest'];
            $footerDetails['footerDetails']['generaltextuser'] = $_POST['Sitesettings']['generaltextuser'];
            $footerDetails['footerDetails']['footerCopyRightsDetails'] = $_POST['Sitesettings']['footerCopyRightsDetails'];

            $model->footer_settings = json_encode($footerDetails);

            if($model->save(false)) {
               Yii::$app->session->setFlash('success',Yii::t('app','Footer Settings updated'));
             //   $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }

        return $this->render('footersettings', [
            'model'=>$model
        ]);
        
    }


    public function actionSmtpsettings() {
       
        $model=$this->findModel();
        $model->setScenario('smtp');
        //print_r($model);exit;
        //$this->performAjaxValidation($model);

        if(isset($_POST['Sitesettings']))
        {
            $model->attributes=$_POST['Sitesettings'];
             if($model->save(false)) {
                Yii::$app->session->setFlash('success',Yii::t('app','Footer Settings updated'));
            //    $this->redirect($_SERVER['HTTP_REFERER']);
            }
            Yii::$app->session->setFlash('success',Yii::t('app','SMTP settings updated'));
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        return $this->render('smtpsettings', [
            'model'=>$model
        ]);
    }

    public function actionMessagesettings() 
    {
        
        $model=Sitesettings::find()->where(['id' => 1])->one();
        if(isset($_POST['Sitesettings']))
        {
            $model->attributes = $_POST['Sitesettings'];
            $model->fb_appid = $_POST['Sitesettings']['fb_appid'];
            // $model->fb_secret = $_POST['Sitesettings']['fb_secret'];
            $model->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','Message settings updated'));
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        return $this->render('messagesettings', [
            'model'=>$model
        ]);
        
    }

    public function actionDefaultsettings() {
      
        $model=$this->findModel();
        $pricerange = [];
        if (isset($model->id)){
            if(!empty($model->pricerange)){
                $model->pricerange = json_decode($model->pricerange, true);
              
            }  

            if(isset($_POST['Sitesettings']))
            {
               
                $model->attributes=$_POST['Sitesettings'];
                                       
                if(trim($_POST['Sitesettings']['searchList']) == "") { 
                  $model->searchList = 100;
                } 
                    if(trim($_POST['Sitesettings']['pricerange']['before_decimal_notation']) != "") { 
                    $pricerange['before_decimal_notation'] = $_POST['Sitesettings']['pricerange']['before_decimal_notation'];
                } 
                    if(trim($_POST['Sitesettings']['pricerange']['after_decimal_notation']) != "") { 
                    $pricerange['after_decimal_notation'] = $_POST['Sitesettings']['pricerange']['after_decimal_notation'];
                } 
                $model->pricerange = json_encode($pricerange);
                if($model->save(false)) {
                    //print_r("expression");exit;
                    Yii::$app->session->setFlash('success',Yii::t('app','Default settings updated'));
                   return $this->redirect($_SERVER['HTTP_REFERER']);
                }
            }
        } else {
            $model = new Sitesettings;
        }


        return $this->render('defaultsettings', [
            'model' => $model,
        ]);
       
    }


      
  

   public function actionLogo() {
      
      $model=$this->findModel();
      $model->setScenario('defaultsettings');
      $oldSite = $model->sitename;
      $oldLogo = $model->logo;
      $oldWatermark = $model->watermark;
      $oldLogoDark = $model->logoDarkVersion;
      $oldUser = $model->default_userimage;
      $oldProduct = $model->default_productimage;
      $favicon = $model->favicon;

      $extensionarray = array('jpg', 'png', 'jpeg');
      $watermarkextensionarray = array('png');
      $faviconextensionarray = array('png'); 
      $alerttext = "";
      if (isset($model->id)) {
         if(isset($_POST['Sitesettings'])) {
            $model->attributes=$_POST['Sitesettings'];

            if(is_null($model->sitename))
               $model->sitename = $oldSite;             
            
            $model->logo = $oldLogo;
            $model->watermark = $oldWatermark;
            $model->logoDarkVersion = $oldLogoDark;
            $model->default_userimage = $oldUser;
            $model->default_productimage = $oldProduct;
            $path1 = realpath(Yii::$app->basePath.'/../');
            $path = realpath($path1.'/frontend/web/media/logo').'/';
            $productpath = realpath($path1.'/frontend/web/media/item').'/';
            $logoUpload = UploadedFile::getInstance($model,'logo');
            $logoDarkUpload = UploadedFile::getInstance($model,'logoDarkVersion');
            
            $userUpload = UploadedFile::getInstance($model,'default_userimage');
            $productUpload = UploadedFile::getInstance($model,'default_productimage');
            $faviconUpload = UploadedFile::getInstance($model,'favicon');
            $watermarkUpload = UploadedFile::getInstance($model,'watermark');

            if(!is_null($logoUpload)) {
               $extension=$logoUpload->extension;
               $logoUploadValues = array();
               $logoUploadValues = getimagesize($logoUpload->tempName);
             
               if (in_array($extension, $extensionarray) && $logoUploadValues[0] > "0" && $logoUploadValues[1] > "0"  && (end($logoUploadValues) == "image/jpeg" || end($logoUploadValues) == "image/png") && count($logoUploadValues) >= 6) {
                  $model->logo = str_replace(" ","-",rand(0000,9999).'_'.$logoUpload);
                     if(!is_null($logoUpload)){
                        if (!empty($oldLogo)) {
                           $oldLogocheck = $path.str_replace(" ","-",$oldLogo); 
                           if (file_exists($oldLogocheck)) {
                              unlink($oldLogocheck);  
                        } 
                     }
                     $logoUpload->saveAs($path.$model->logo);
                     $alerttext = "Logo Image";
                  }
               } else {
                  $model->logo = $oldLogo;
                  $model->save(false);
                  Yii::$app->session->setFlash('error',Yii::t('app','Please upload jpg/jpeg/png for Logo Image'));
                  return $this->redirect($_SERVER['HTTP_REFERER']);
               }
            } else {
               $model->logo = $oldLogo;
               $model->save(false);
              
            }

            if(!is_null($watermarkUpload)) {
           
               $extension = $watermarkUpload->extension;
               $logoUploadValues = array();
               $logoUploadValues = getimagesize($watermarkUpload->tempName);

               if (in_array($extension, $watermarkextensionarray) && $logoUploadValues[0] > "0" && $logoUploadValues[1] > "0"  && (end($logoUploadValues) == "image/jpeg" || end($logoUploadValues) == "image/png") && count($logoUploadValues) >= 6) { 
                  $model->watermark = str_replace(" ","-",rand(0000,9999).'_'.$watermarkUpload); 
                  if(!is_null($watermarkUpload)) {
                     if (!empty($oldWatermark)) {
                        $oldWatermarkcheck = $path.str_replace(" ","-",$oldWatermark); 
                        if (file_exists($oldWatermarkcheck)) {
                           unlink($oldWatermarkcheck); 
                        }
                     } 
                     $watermarkUpload->saveAs($path.$model->watermark);
                     $alerttext = "Watermark Image";
                  }
               } else {
                  $model->watermark = $oldWatermark;
                  $model->save(false);
                 
                  Yii::$app->session->setFlash('error',Yii::t('app','Please upload only png image for Watermark'));
                  return $this->redirect($_SERVER['HTTP_REFERER']);
               } 
            } else {
               
               $model->watermark = $oldWatermark;
               $model->save(false);  
               
            } 
                
            if(!is_null($logoDarkUpload)) {
               $extension=$logoDarkUpload->extension;
               $logoUploadValues = array();
               $logoUploadValues = getimagesize($logoDarkUpload->tempName);

               if (in_array($extension, $extensionarray) && $logoUploadValues[0] > "0" && $logoUploadValues[1] > "0"  && (end($logoUploadValues) == "image/jpeg" || end($logoUploadValues) == "image/png") && count($logoUploadValues) >= 6) {
                  $model->logoDarkVersion = str_replace(" ","-",rand(0000,9999).'_'.$logoDarkUpload);
                  if(!is_null($logoDarkUpload)) {
                     if (!empty($oldLogoDark)) {
                        $oldLogoDarkcheck = $path.str_replace(" ","-",$oldLogoDark);
                        if (file_exists($oldLogoDarkcheck)) {
                           unlink($oldLogoDarkcheck);
                        } 
                     }
                     $logoDarkUpload->saveAs($path.$model->logoDarkVersion);
                     $alerttext = "Logo Dark Version Image";
                  }
               } else {
                  $model->logoDarkVersion = $oldLogoDark;
                  $model->save(false);
                  Yii::$app->session->setFlash('error',Yii::t('app','Please upload jpg/jpeg/png for Logo Dark Version Image'));
                  return $this->redirect($_SERVER['HTTP_REFERER']);
               }
            } else {
               $model->logoDarkVersion = $oldLogoDark;
               $model->save(false);
               
            }
            
            if(!is_null($userUpload)) {
               $extension=$userUpload->extension;
               $logoUploadValues = array();
               $logoUploadValues = getimagesize($userUpload->tempName); 

               if (in_array($extension, $extensionarray) && $logoUploadValues[0] > "0" && $logoUploadValues[1] > "0"  && (end($logoUploadValues) == "image/jpeg" || end($logoUploadValues) == "image/png") && count($logoUploadValues) >= 6){
                  $model->default_userimage = str_replace(" ","-",rand(0000,9999).'_'.$userUpload);
                  if(!is_null($userUpload)) {
                     if (!empty($oldUser)) {
                        $oldUsercheck = $path.str_replace(" ","-",$oldUser);
                        if (file_exists($oldUsercheck)) {
                           unlink($oldUsercheck); 
                        } 
                     }
                     $userUpload->saveAs($path.$model->default_userimage);
                     $alerttext = "User Default Image";
                  }
               } else {
                  $model->default_userimage = $oldUser;
                  $model->save(false);
                  Yii::$app->session->setFlash('error',Yii::t('app','Please upload jpg/jpeg/png for Default User Image'));

                  return $this->redirect($_SERVER['HTTP_REFERER']);
               }
            } else {
               $model->default_userimage = $oldUser;
               $model->save(false); 
               
            }

             function compress_image($source_url, $destination_url, $quality) {

      $info = getimagesize($source_url);
   //   echo json_encode($info); die;

          if ($info['mime'] == 'image/jpeg')
          $image = imagecreatefromjpeg($source_url);

          elseif ($info['mime'] == 'image/png')
          $image = imagecreatefrompng($source_url);

          imagejpeg($image, $destination_url, $quality);
          return $destination_url;
        }


                if(!is_null($productUpload)) {

               $extension=$productUpload->extension;
               $logoUploadValues = array();
               $logoUploadValues = getimagesize($productUpload->tempName); 

               if (in_array($extension, $extensionarray) && $logoUploadValues[0] > "0" && $logoUploadValues[1] > "0"  && (end($logoUploadValues) == "image/jpeg" || end($logoUploadValues) == "image/png") && count($logoUploadValues) >= 6){
                  $model->default_productimage ='default.'.$extension;
                  $url = $productpath.'default.jpg';
                  $filename = compress_image($productUpload->tempName, $url, 80);
                  if(!is_null($productUpload)) {
                     if (!empty($oldProduct)) {
                        $oldProductcheck = $productpath.str_replace(" ","-",$oldProduct);
                        if (file_exists($oldProductcheck)) {
                           unlink($oldProductcheck); 
                        } 
                     }
                     $productUpload->saveAs($productpath.$model->default_productimage);
                      $alerttext = "Product Default Image";
                    // move_uploaded_file( $_FILES["images"]["tmp_name"],$url);
                  }
               } else {
                  $model->default_productimage = $oldProduct;
                  $model->save(false);
                  Yii::$app->session->setFlash('error',Yii::t('app','Please upload jpg/jpeg/png for Default Product Image'));

                  return $this->redirect($_SERVER['HTTP_REFERER']);
               }
            } else {
               $model->default_productimage = $oldProduct;
               $model->save(false); 
              
            }

            if(!is_null($faviconUpload)) {
                
               $model->favicon = 'favicon.png';

               $extension = $faviconUpload->extension;
               $logoUploadValues = array();
               $logoUploadValues = getimagesize($faviconUpload->tempName);

               if (!is_null($faviconUpload) && in_array($extension, $faviconextensionarray) && $logoUploadValues[0] > "0" && $logoUploadValues[1] > "0"  && end($logoUploadValues) == "image/png" && count($logoUploadValues) >= 6) {   
                  $faviconUpload->saveAs($path.'favicon.png');
                  $alerttext = "Favicon";
               } else {
                  $model->save(false);
                  Yii::$app->session->setFlash('error',Yii::t('app','Please upload png for Favicon'));
                  //return $this->redirect($_SERVER['HTTP_REFERER']);
                 return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/admin/sitesettings/logo'); 
               }
            } 
            else { 
               $model->favicon = 'favicon.png';
               $model->save(false);
               // Yii::$app->session->setFlash('error',Yii::t('app','Please upload png for Favicon'));   
               // return $this->redirect($_SERVER['HTTP_REFERER']);
            } 
            if($alerttext == "")
                $alerttext = "Settings";
                
            /*if($model->save(false)) {
               Yii::$app->session->setFlash('success',Yii::t('app','Logo updated'));
               return $this->redirect($_SERVER['HTTP_REFERER']);
            }*/
         }
      } else {
         $model = new Sitesettings;
      }


      return $this->render('logo', [
        'model' => $model,
      ]);       
   }  


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function loadModel()
    {
        $model=Sitesettings::find()->where(['id' => 1])->one();
       
        if($model===null)
        throw new CHttpException(404,'The requested page does not exist.');
    
        return $model;
    }

     public function actionBraintreesettings()
    {
     $model=$this->findModel();

     $model->setScenario('braintreesettings');
    // print_r($model->id);exit;
            if (isset($model->id) && !isset($_POST['Sitesettings'])){
            if(!empty($model->braintree_settings)){
                $braintreeSetting = json_decode($model->braintree_settings, true);
                $model->brainTreeType = $braintreeSetting['brainTreeType'];
                $model->brainTreeMerchantId = $braintreeSetting['brainTreeMerchantId'];
                $model->brainTreePublicKey = $braintreeSetting['brainTreePublicKey'];
                $model->brainTreePrivateKey = $braintreeSetting['brainTreePrivateKey'];
            }
        }
     // $model->validation($model);

        if(isset($_POST['Sitesettings'])){
            //echo "<pre>";print_r($_POST['Sitesettings']);die;
            $model->attributes=$_POST['Sitesettings'];
            //print_r($model->attributes);exit;
           $model->braintree_settings = json_encode(
            array(
                'brainTreeType'=> $_POST['Sitesettings']['brainTreeType'],
                'brainTreeMerchantId'=> $_POST['Sitesettings']['brainTreeMerchantId'],
                'brainTreePublicKey'=> $_POST['Sitesettings']['brainTreePublicKey'],
                'brainTreePrivateKey'=> $_POST['Sitesettings']['brainTreePrivateKey'],
            ));
          //  print_r($model->braintree_settings);exit;
            $setngsModel=$this->findModel();
           //  print_r($setngsModel);exit;
            $braintreeSetting = Json::decode($setngsModel->braintree_settings, true);
            if(($braintreeSetting['brainTreeMerchantId'] !=  $_POST['Sitesettings']['brainTreeMerchantId']) || ($braintreeSetting['brainTreePublicKey'] !=  $_POST['Sitesettings']['brainTreePublicKey']) || ($braintreeSetting['brainTreePrivateKey'] !=  $_POST['Sitesettings']['brainTreePrivateKey'])) {
                $model->braintree_merchant_ids = ""; 
                $userModel = new Users();
                Users::updateAll(['braintree_cid' => ""]);

            }


             if($model->save(false)){
               
                if(!empty($model->braintree_settings)){

                    $braintreeSetting = Json::decode($model->braintree_settings, true);
                    $model->brainTreeType = $braintreeSetting['brainTreeType'];
                    $model->brainTreeMerchantId = $braintreeSetting['brainTreeMerchantId'];
                    $model->brainTreePublicKey = $braintreeSetting['brainTreePublicKey'];
                    $model->brainTreePrivateKey = $braintreeSetting['brainTreePrivateKey'];
                }
                Yii::$app->session->setFlash('success',Yii::t('app','Braintree Settings updated'));
               return $this->redirect($_SERVER['HTTP_REFERER']);
            }
           
            


        }
        return $this->render('braintreesettings', [
            'model' => $model,
        ]);
    }

        public function actionApidetails() {
       
        $model=$this->findModel();
        $makeDefault = 0;

        if (isset($model->id)){
            $apiDetails = json_decode($model->api_settings, true);
            $model->apiUsername = $apiDetails['apicredential']['current']['username'];
            $model->apiPassword = $apiDetails['apicredential']['current']['password'];

           // print_r($model->apiPassword);exit;
            if(!isset($apiDetails['apicredential']['default']['username']) ||
            $apiDetails['apicredential']['default']['username'] == ""){
                $makeDefault = 1;
            }
        }else{
            $model=new Sitesettings;
        }
        //$this->performAjaxValidation($model);

        if(isset($_POST['Sitesettings']))
        {
            //echo "<pre>";print_r($_POST['Sitesettings']);die;
            $model->attributes = $_POST['Sitesettings'];
            if($makeDefault == 1){
                $apiDetails['apicredential']['default']['username'] = $_POST['Sitesettings']['apiUsername'];
                $apiDetails['apicredential']['default']['password'] = $_POST['Sitesettings']['apiPassword'];
            }
            $apiDetails['apicredential']['current']['username'] = $_POST['Sitesettings']['apiUsername'];
            $apiDetails['apicredential']['current']['password'] = $_POST['Sitesettings']['apiPassword'];

            $model->api_settings = json_encode($apiDetails);
            if($model->save(false)) {
                Yii::$app->session->setFlash('success',Yii::t('app','API Credentials updated'));
        return $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }

        return $this->render('apidetails', [
            'model' => $model,'makeDefault' => $makeDefault
        ]);
    }

  
    public function actionCreate()
    {
        $model = new Sitesettings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

  
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

 
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }



      public function actionSitepaymentmodes() {
        $id = 1;
        $model=$this->findModel($id);
        $model->setScenario('paymentmodes');
        if (isset($model->id) && !isset($_POST['Sitesettings'])){
            if(!empty($model->sitepaymentmodes)){
                $sitePaymentMode = json_decode($model->sitepaymentmodes, true);
                $model->exchangePaymentMode = $sitePaymentMode['exchangePaymentMode'];
                $model->buynowPaymentMode = $sitePaymentMode['buynowPaymentMode'];
                $model->cancelEnableStatus = $sitePaymentMode['cancelEnableStatus'];
                $model->sellerClimbEnableDays = $sitePaymentMode['sellerClimbEnableDays'];
                $model->scrowPaymentMode = $sitePaymentMode['scrowPaymentMode'];
                $model->bannerPaymenttype = $sitePaymentMode['bannerPaymenttype'];

            }
        }

        //$this->performAjaxValidation($model);

        if(isset($_POST['Sitesettings']))
        {
            $model->attributes=$_POST['Sitesettings'];



            $sitePaymentMode = json_decode($model->sitepaymentmodes, true);
         
            $sitePaymentMode['exchangePaymentMode'] =  $sitePaymentMode['exchangePaymentMode'];
            $sitePaymentMode['buynowPaymentMode'] = $sitePaymentMode['buynowPaymentMode'];
            $sitePaymentMode['cancelEnableStatus'] = $_POST['Sitesettings']['cancelEnableStatus'];
            $sitePaymentMode['sellerClimbEnableDays'] = $_POST['Sitesettings']['sellerClimbEnableDays'];
            $sitePaymentMode['scrowPaymentMode'] = $_POST['Sitesettings']['scrowPaymentMode'];
            $sitePaymentMode['bannerPaymenttype'] = $_POST['Sitesettings']['bannerPaymenttype'];
            $model->sitepaymentmodes = json_encode($sitePaymentMode);

            //print_r($model->sitepaymentmodes);exit;
            
            if($model->save(false)){
                if(!empty($model->sitepaymentmodes)){
                    $sitePaymentMode = json_decode($model->sitepaymentmodes, true);
                    $model->exchangePaymentMode = $sitePaymentMode['exchangePaymentMode'];
                    $model->buynowPaymentMode = $sitePaymentMode['buynowPaymentMode'];
                    $model->cancelEnableStatus = $sitePaymentMode['cancelEnableStatus'];
                    $model->sellerClimbEnableDays = $sitePaymentMode['sellerClimbEnableDays'];
                    $model->scrowPaymentMode = $sitePaymentMode['scrowPaymentMode'];
                    $model->bannerPaymenttype = $sitePaymentMode['bannerPaymenttype'];

                }
                Yii::$app->session->setFlash('success',Yii::t('app','Site Payment Modes updated'));
                return $this->redirect(['sitepaymentmodes']);
            }
        }
        return $this->render('sitepaymentmodes',['model'=>$model]);
    }

    public function actionManagemodule()
    { 
        $settings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        $model=$this->findModel();
       
        // if(!empty($model->sitepaymentmodes)){
        //     $sitePaymentMode = json_decode($settings->sitepaymentmodes, true);
        //     $model->exchangePaymentMode = $sitePaymentMode['exchangePaymentMode'];
        //     $model->buynowPaymentMode = $sitePaymentMode['buynowPaymentMode'];
        //  }
         if(isset($_POST['Sitesettings']))
         {
           
             $model->attributes=$_POST['Sitesettings'];
           
            // $exchangepaymentmode = $_REQUEST['Sitesettings']['exchangePaymentMode'];
             $sitePaymentMode = json::decode($settings->sitepaymentmodes, true);
             $sitePaymentMode['exchangePaymentMode'] =  $_POST['Sitesettings']['exchangePaymentMode'];
            
            

             $model->givingaway = $model->givingaway;
             $model->promotionStatus = $model->promotionStatus;
             $model->paidbannerstatus = $model->paidbannerstatus;
             $model->sitepaymentmodes = json::encode($sitePaymentMode);

             $model->site_maintenance_mode = $model->site_maintenance_mode;
             $model->maintenance_text = $model->maintenance_text;



             $model->save(false);
             Yii::$app->session->setFlash('success',Yii::t('app','Modules Updated'));
         }
       
        return $this->render('managemodule',['model'=>$model]);
    }



    //seo setting

    public function actionSeosetting() {
      
        $model=$this->findModel();
        $model->setScenario('defaultsettings');
        $oldSite = $model->sitename;
        $oldLogo = $model->logo;
        $oldLogoDark = $model->logoDarkVersion;
        
        $oldUser = $model->default_userimage;
        $favicon = $model->favicon;
        $extensionarray = array('jpg', 'png', 'jpeg');
        if (isset($model->id)){
            if(!isset($_POST['Sitesettings'])){
                $metaData = json_decode($model->metaData, true);
                $model->metaTitle = $metaData['metaTitle'];
                $model->metaDescription = $metaData['metaDescription'];
                $model->metaKeywords = $metaData['metaKeywords'];
              //  $model->googleapikey = $model->googleapikey;
                //$model->staticMapApiKey = $model->staticMapApiKey;
                $model->givingaway = $model->givingaway;
                $model->tracking_code =$model->tracking_code;

               // print_r($model);
               // print_r($model->googleapikey);exit;
            }
            
            if(isset($_POST['Sitesettings']))
            {
                //echo "<pre>";print_r($_POST['Sitesettings']);die;
                $model->attributes=$_POST['Sitesettings'];

                 if(is_null($model->sitename))
                $model->sitename = $oldSite;
                
                $metaData['metaTitle'] = $_POST['Sitesettings']['metaTitle'];
                $metaData['metaDescription'] = $_POST['Sitesettings']['metaDescription'];
                $metaData['metaKeywords'] = $_POST['Sitesettings']['metaKeywords'];
                $model->metaData = json_encode($metaData);
             //   $model->googleapikey = $_POST['Sitesettings']['googleapikey'];
               // $model->staticMapApiKey = $_POST['Sitesettings']['staticMapApiKey'];
                $model->tracking_code = $_POST['Sitesettings']['tracking_code'];


                
                if($model->save(false)) {
                   Yii::$app->session->setFlash('success',Yii::t('app','SEO settings updated'));
                   return $this->redirect($_SERVER['HTTP_REFERER']);
                }
            }
        } else {
            $model = new Sitesettings;
        }


        return $this->render('seosetting', [
            'model' => $model,
        ]);
       
    }


public function actionAds()
{ 
  $model=$this->findModel(); 


  $currencySymbols = explode("-", $model->bannerCurrency);
  $currencySymbol = trim($currencySymbols[0]);
  if(isset($_POST['Sitesettings']))
  { 
    
    $oldadImage = $model->ad_image;
    $favicon = $model->favicon;
    $extensionarray = array('jpg', 'png', 'jpeg');
    $path1 = realpath(Yii::$app->basePath.'/../');
    $path = realpath($path1.'/frontend/web/media/logo').'/';

    $adUpload = UploadedFile::getInstance($model,'ad_image');
    
    if(!is_null($adUpload)) {
      $extension=$adUpload->extension; 
      $adUploadValues = array();
      $adUploadValues = getimagesize($adUpload->tempName); // MIME PURELY VALIDATED
      
      if (in_array($extension, $extensionarray) && $adUploadValues[0] > "0" && $adUploadValues[1] > "0"  && (end($adUploadValues) == "image/jpeg" || end($adUploadValues) == "image/png") && count($adUploadValues) >= 6) {  
          $model->ad_image = rand(0000,9999).'_'.$adUpload;    
          if (!empty($oldadImage)) {
             //unlink($path.$oldadImage);
            $oldadImagecheck = $path.$oldadImage;
            if (file_exists($oldadImagecheck)) {
                unlink($path.$oldadImage);
            } 
          }
          $adUpload->saveAs($path.str_replace(" ","-",$model->ad_image));
      } else {
        $model->ad_image = $oldadImage;

        Yii::$app->session->setFlash('error',Yii::t('app','Please upload jpg/jpeg/png for advertisement page'));
        return $this->redirect($_SERVER['HTTP_REFERER']);
      }
    } else {
      $model->ad_image = $oldadImage;
    }
      $ad_lang =  $_POST['Sitesettings']['ad_lang'];  
      $adcontent = Json::decode($model->ad_content,true);
        if ($adcontent!="") {
             
         if (array_key_exists($ad_lang, $adcontent)) {
            unset($adcontent[$ad_lang]);
            $postadcontent['content'] = $_POST['Sitesettings']['adcontent'];
            $model->ad_content = Json::encode(array_merge($adcontent,array($ad_lang=>$postadcontent)));
         } else {
            $postadcontent['content'] =$_POST['Sitesettings']['adcontent'];
                if($adcontent=='')
               $model->ad_content = Json::encode(array($ad_lang=>$postadcontent));  
            else
               $model->ad_content = Json::encode(array_merge($adcontent,array($ad_lang=>$postadcontent)));  
         }
     }
     else
     {
           $postadcontent['content'] = $_POST['Sitesettings']['adcontent'];
            $model->ad_content = Json::encode(array_merge(array($ad_lang=>$postadcontent)));
     }


   

    $model->ad_title = $_POST['Sitesettings']['ad_title'];
    // $model->ad_content =json_encode($ad_data);
    $model->ad_price = $_POST['Sitesettings']['ad_price'];
    $model->ad_limit = $_POST['Sitesettings']['ad_limit'];
    $model->save(false);
    Yii::$app->session->setFlash('success',Yii::t('app','Advertisement updated'));
  }
     if (($model->ad_content!="")) {
     $adcontentarr = Json::decode($model->ad_content,true);
     $firstelem = array_keys($adcontentarr)[0];
     $model->adcontent = $adcontentarr[$firstelem]['content'];
     $model->adlang = $firstelem;



    }
    return $this->render('ads',['model'=>$model,'selectedcurrency'=>$currencySymbol,]);
}

public function actionGetadcontent()
{
  
         $model=$this->findModel(); 
         $ad_lang =  $_POST['selectedlanguage'];  
         $adcontent = Json::decode($model->ad_content,true);
        if ($adcontent!="") {
             
         if (array_key_exists($ad_lang, $adcontent)) {
      
            echo $adcontent[$ad_lang]['content'];
         } 

     }
   


}

    // public function actionAds()
    // { 
    //     $model=$this->findModel();
    //     if(isset($_POST['Sitesettings']))
    //     {
    //         print_r($_POST);
    //         $model->attributes=$_POST['Sitesettings'];
    //       echo  $logoUpload = UploadedFile::getInstance($model,'ad_image');
    //         exit;
    //     }
    //     return $this->render('ads',['model'=>$model]);
    // }
    //end seo setting
    protected function findModel()
    {
        $model=Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        if($model===null)
        throw new CHttpException(404,'The requested page does not exist.');
    
        return $model;
    }



         public function actionStripesettings()
    {
     $model=$this->findModel();

     $model->setScenario('stripesettings');
    // print_r($model->id);exit;
            if (isset($model->id) && !isset($_POST['Sitesettings'])){
            if(!empty($model->stripe_settings)){
                $stripesetting = json_decode($model->stripe_settings, true);
                $model->stripeType = $stripesetting['stripeType'];
                $model->stripePublicKey = $stripesetting['stripePublicKey'];
                $model->stripePrivateKey = $stripesetting['stripePrivateKey'];
            }
        }
     // $model->validation($model);

        if(isset($_POST['Sitesettings'])){
         
            $model->attributes=$_POST['Sitesettings'];
            //print_r($model->attributes);exit;
           $model->stripe_settings = json_encode(
            array(
                'stripeType'=> $_POST['Sitesettings']['stripeType'],
                'stripePublicKey'=> $_POST['Sitesettings']['stripePublicKey'],
                'stripePrivateKey'=> $_POST['Sitesettings']['stripePrivateKey'],
            ));
          //  print_r($model->braintree_settings);exit;
            $setngsModel=$this->findModel();
           //  print_r($setngsModel);exit;
            $stripesetting = json_decode($model->stripe_settings, true);

            // if(($stripesetting['brainTreePublicKey'] !=  $_POST['Sitesettings']['stripePublicKey']) || ($stripesetting['brainTreePrivateKey'] !=  $_POST['Sitesettings']['stripePrivateKey'])) {
            
            //     $userModel = new Users();
            //     Users::updateAll(['braintree_cid' => ""]);

            // }


             if($model->save(false)){
            
                if(!empty($model->stripe_settings)){

                    $stripesetting = Json::decode($model->stripe_settings, true);
                    $model->stripeType = $stripesetting['stripeType'];
                    $model->stripePublicKey = $stripesetting['stripePublicKey'];
                    $model->stripePrivateKey = $stripesetting['stripePrivateKey'];
                }
                Yii::$app->session->setFlash('success',Yii::t('app','Stripe Settings updated'));
               return $this->redirect($_SERVER['HTTP_REFERER']);
            }
           
            


        }
        return $this->render('stripesettings', [
            'model' => $model,
        ]);
    }

  

}
