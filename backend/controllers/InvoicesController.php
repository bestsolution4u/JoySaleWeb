<?php

namespace backend\controllers;

use Yii;
use common\models\Invoices;
use common\models\Orders;
use common\models\Users;
use common\models\Orderitems;
use common\models\Shippingaddresses;
use backend\models\InvoicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Sitesettings;

class InvoicesController extends Controller
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
          $this->layout="page";
        $searchModel = new InvoicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 $dataProvider->pagination->pageSize=10;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionGetinvoicedata() 
       {

        if(isset($_POST['invoiceId'])) {
            $id=$_POST['invoiceId'];
           // exit;
           $orderData = Orders::find()->where(['orderId'=>$id])->one();
            $invoicedata=Invoices::find()->where(['orderId'=>$id])->one();
            $userdata=Users::find()->where(['userId'=>$orderData->userId])->one();
            $orderitem=Orderitems::find()->where(['orderId'=>$orderData->orderId])->one();
          $shipping = Shippingaddresses::find()->where(['shippingaddressId'=>$orderData->shippingAddress])->one();
         return $this->renderPartial('viewinvoice',['invoiceData' => $invoicedata,'orderData' => $orderData,'userdata' => $userdata,'shipping' => $shipping,'orderitem' => $orderitem]);
        }
     //return $_POST['invoiceId'];
    }

  
    public function actionView($id)
    {
       if(isset($id)) {
            $orderData = Orders::find()->where(['orderId'=>$id])->one();
            $invoicedata=Invoices::find()->where(['orderId'=>$id])->one();
            $userdata=Users::find()->where(['userId'=>$orderData->userId])->one();
            $orderitem=Orderitems::find()->where(['orderId'=>$orderData->orderId])->one();
         // print_r($invoiceData->shippingAddress);exit;
            $shipping = Shippingaddresses::find()->where(['shippingaddressId'=>$orderData->shippingAddress])->one();
           // print_r($shipping);exit;
           return $this->render('view',['invoiceData' => $invoicedata,'orderData' => $orderData,'userdata' => $userdata,'shipping' => $shipping,'orderitem' => $orderitem]);
        }
    }


    public function actionCreate()
    {
        $model = new Invoices();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'invoiceId' => $model->invoiceId, 'orderId' => $model->orderId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($invoiceId, $orderId)
    {
        $model = $this->findModel($invoiceId, $orderId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'invoiceId' => $model->invoiceId, 'orderId' => $model->orderId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($invoiceId, $orderId)
    {
        $this->findModel($invoiceId, $orderId)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($invoiceId, $orderId)
    {
        if (($model = Invoices::findOne(['invoiceId' => $invoiceId, 'orderId' => $orderId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
