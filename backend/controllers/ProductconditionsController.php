<?php

namespace backend\controllers;

use Yii;
use common\models\Productconditions;
use backend\models\ProductconditionsSearch;
use common\models\Products; 
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Sitesettings;

class ProductconditionsController extends Controller
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
        $searchModel = new ProductconditionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 $dataProvider->pagination->pageSize=10;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $model = new Productconditions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success',Yii::t('app','Product condition created'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

  
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success',Yii::t('app','Product condition updated'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

   
    public function actionDelete($id) 
    {
        $model = $this->findModel($id);  
        $productCount = Products::find()->where(['productCondition' => $model->condition])->count(); 
        if($productCount == 0) {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success',Yii::t('app', 'Product condition deleted'));
        } else {
            Yii::$app->session->setFlash('success',Yii::t('app', 'Product condition already exist in products')); 
        }
        return $this->redirect(['index']);
    } 

  
    protected function findModel($id)
    {
        if (($model = Productconditions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCancel()
    {
        return $this->redirect(['index']);
    }
}
