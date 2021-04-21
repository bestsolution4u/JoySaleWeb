<?php

namespace backend\controllers;

use Yii;
use common\models\Reportproducts;
use backend\models\ReportitemSearch;
use common\models\Products;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Sitesettings;
use common\models\Photos;

class ReportitemController extends Controller
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                  //  'delete' => ['POST'],
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
    {   $this->layout="page";
        $searchModel = new ReportitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $dataProvider->pagination->pageSize=10;
//print_r($dataProvider);exit;
       // $items=Products::find()->where(['reportCount' => !0])->all();
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
           
        ]);
    }

  
    public function actionView($id)
    {

        if (Yii::$app->user->isGuest) {            
            return $this->goHome();          
        }
            $models = new Photos();
            $getPhotos = Photos::find()->where(['productId'=>$id])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'getPhotos' => $getPhotos,  
        ]);
    }

    public function actionCreate()
    {
        $model = new Reportproducts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->productId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

  
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->productId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

 
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
 Yii::$app->session->setFlash('success',Yii::t('app','Items Deleted'));

        return $this->redirect(['index']);
    }

 
    protected function findModel($id)
    {
        if (($model = Reportproducts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
