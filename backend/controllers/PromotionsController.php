<?php

namespace backend\controllers;

use Yii;
use common\models\Promotions;
use backend\models\PromotionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Sitesettings;
use yii\helpers\Json;

class PromotionsController extends Controller
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
    

public function beforeAction($action) {
            if (Yii::$app->user->isGuest) {            
                return $this->goHome();          
            }
            return true;
    }

    public function actionIndex()
    {  $this->layout="page";
        $searchModel = new PromotionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 $dataProvider->pagination->pageSize=10;


        $siteSettingsModel = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        $currencySymbols = explode("-", $siteSettingsModel->promotionCurrency);
        $currencySymbol = trim($currencySymbols[0]);

       // print_r($currencySymbol);exit;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider, 'selectedcurrency'=>$currencySymbol,
        ]);
    }

   
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

 
    public function actionCreate()
    {
        $model = new Promotions();
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$promotionCurrency = $siteSettings->promotionCurrency;
        $placeholder = explode("-",$promotionCurrency); 
      
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(); 
          //   Yii::$app->end();
            // Yii::$app->session->setFlash('success',Yii::t('app','Promotions created'));
            // return $this->redirect(['index']);
          
            Yii::$app->response->redirect(['promotions/index']);
            Yii::$app->end();
        }

        return $this->render('create', [
            'model' => $model,'placeholder'=>$placeholder
        ]);
    }

  
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$promotionCurrency = $siteSettings->promotionCurrency;
        $placeholder = explode("-",$promotionCurrency); 

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success',Yii::t('app','Promotions updated'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,'placeholder'=>$placeholder
        ]);
    }

  
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success',Yii::t('app','Promotion deleted'));
        return $this->redirect(['index']);
    }

    public function actionPromotioncurrencies(){
        echo "Daata Hi";
		$currency = $_GET['currency'];
		$str = explode("-",$currency);
        $currency = $str[2].'-'.$str[0];
        echo $currency;
		
        $siteSettingsModel = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        
		
		$siteSettingsModel->promotionCurrency = $currency;
		$siteSettingsModel->save(false);
		$siteSettingsModels = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		echo  $siteSettingsModels->promotionCurrency;
		
	}


    // public function actionUrgentpromotion() {
	// //	$urgentprice = $_REQUEST['urgentprice'];

	// 	$settings =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
	// 	$promotionCurrency = $settings->promotionCurrency;
	// 	$promotionCurrency = explode('-', $promotionCurrency);
	// 	if(isset($_POST['urgentprice'])) {
	// 		//echo "fskdjh";die;
	// 		$settings->urgentPrice = $urgentprice;
	// 		$settings->save(false);
	// 	//	Yii::$app->session->setFlash('success',Yii::t('admin','Urgent promotion\'s Price updated successfully.'));
	// 	}

	// 	$this->render('urgentpromotion',compact('settings','urgentprice','promotionCurrency'));
	// }

    public function actionUrgentpromotion(){
       // $urgentprice = $_REQUEST['urgentprice'];
        $settings =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        $promotionCurrency = $settings->promotionCurrency;
        $promotionCurrency = explode('-', $promotionCurrency);
		if(isset($_POST['urgentprice'])) {
			//echo "fskdjh";die;
			$settings->urgentPrice =$_POST['urgentprice'];
			$settings->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','Urgent promotion Price updated'));
           return $this->redirect(['index']);

		}
     return $this->render('urgentpromotion',['settings'=>$settings,'promotionCurrency'=>$promotionCurrency]);
     }
  
    public function actionPromotionsettings(){
		$settings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

		$model=Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$model->setScenario('paymentmodes');

		if (isset($model->id) && !isset($_POST['Sitesettings'])){
			if(!empty($model->sitepaymentmodes)){
				$sitePaymentMode = json::decode($model->sitepaymentmodes, true);
				$model->exchangePaymentMode = $sitePaymentMode['exchangePaymentMode'];
				$model->buynowPaymentMode = $sitePaymentMode['buynowPaymentMode'];
				$model->cancelEnableStatus = $sitePaymentMode['cancelEnableStatus'];
				$model->sellerClimbEnableDays = $sitePaymentMode['sellerClimbEnableDays'];
				$model->scrowPaymentMode = $sitePaymentMode['scrowPaymentMode'];
			}
		}

		if(isset($_POST['Sitesettings'])){
			//echo "<pre>";print_r($_POST);die;
			$exchangepaymentmode = $_REQUEST['Sitesettings']['exchangePaymentMode'];

			$sitePaymentMode = json::decode($settings->sitepaymentmodes, true);
			$sitePaymentMode['exchangePaymentMode'] =  $_POST['Sitesettings']['exchangePaymentMode'];
			$sitePaymentMode['buynowPaymentMode'] = $sitePaymentMode['buynowPaymentMode'];
			$sitePaymentMode['cancelEnableStatus'] = $sitePaymentMode['cancelEnableStatus'];
			$sitePaymentMode['sellerClimbEnableDays'] = $sitePaymentMode['sellerClimbEnableDays'];
			$sitePaymentMode['scrowPaymentMode'] = $sitePaymentMode['scrowPaymentMode'];
			$settings->sitepaymentmodes = json::encode($sitePaymentMode);

			$promotionStatus = $_REQUEST['Sitesettings']['promotionStatus'];
			$settings->promotionStatus = $promotionStatus;
			$settings->save(false);

				if(!empty($model->sitepaymentmodes)){
					$sitePaymentMode = json::decode($model->sitepaymentmodes, true);
					$model->exchangePaymentMode = $sitePaymentMode['exchangePaymentMode'];
					$model->buynowPaymentMode = $sitePaymentMode['buynowPaymentMode'];
					$model->cancelEnableStatus = $sitePaymentMode['cancelEnableStatus'];
					$model->sellerClimbEnableDays = $sitePaymentMode['sellerClimbEnableDays'];
					$model->scrowPaymentMode = $sitePaymentMode['scrowPaymentMode'];
				}
                Yii::$app->session->setFlash('success',Yii::t('app','Promotion status updated'));
                 return $this->redirect(['promotionsettings']);

		}else{
			$promotionStatus = $settings->promotionStatus;
		}
      $model=Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        return $this->render('promotionsettings',['settings'=>$settings,'promotionStatus'=>$promotionStatus,'model'=>$model]);

    }
    


   
    protected function findModel($id)
    {
        if (($model = Promotions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCancel()
    {
        return $this->redirect(['index']);
    }
}
