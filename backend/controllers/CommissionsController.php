<?php

namespace backend\controllers;

use Yii;
use common\models\Commissions;
use common\models\Sitesettings;
use backend\models\CommissionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class CommissionsController extends Controller
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
    {
        $searchModel = new CommissionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $dataProvider->pagination->pageSize=10;
    //    print_r($dataProvider);exit;
        $commissionSetting = Sitesettings::find()->select('commission_status')->orderBy(['id' => SORT_DESC])->one(); 
       $commission_status=$commissionSetting['commission_status'];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'commissionSetting' => $commission_status,
        ]);
    }

public function actionChangestatus($id){
        $model = $this->findModel($id);
        if($model->status == 1) {
            $model->status = 0;
        } else {
            $model->status = 1;
        }
        $model->save(false);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }



    public function actionStatus(){
        
        $model = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one(); 
        if(empty($model))
        {
            Yii::$app->session->setFlash('success',Yii::t('app','Invalid input'));
            return $this->redirect(['commissions/index']);
        }


        if($model->commission_status == 1) {
            $model->commission_status = 0;
            Yii::$app->session->setFlash('success',Yii::t('app','All Commissions disabled'));
        } else {
            $model->commission_status = 1;
            Yii::$app->session->setFlash('success',Yii::t('app','All Commissions Enabled'));
        }
        $model->save(false);
        
        //Check HTTP Referer.
        if(isset($_SERVER['HTTP_REFERER']))
        {
            return $this->redirect($_SERVER['HTTP_REFERER']);    
        }else{
            return $this->redirect(['commissions/index']);
        }
    }

 
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


     public function actionCreate()
    {
        $model = new Commissions();

        if($model->load(Yii::$app->request->post()))
        {
            $model->attributes=$_POST['Commissions'];
            $model->status=1;
            $model->date=strtotime(date('d-m-Y'));
            if($model->validate()) {
            if (Commissions::find()->andWhere(['minRate' => $_POST['Commissions']['minRate']])->andWhere(['maxRate' => $_POST['Commissions']['maxRate']])->exists()) {

                  Yii::$app->session->setFlash('warning',Yii::t('app','Commissions for this range has already been added'));
                return $this->redirect(['index']);
                  
             } 
             else
             {

                if($model->save(false)) {
              //  print_r($model->attributes);exit;
                Yii::$app->session->setFlash('success',Yii::t('app','New commissions added'));
                return $this->redirect(['index']);
            }
        }
                
             }
          
           
        }


        return $this->render('create',[
            'model'=>$model,
        ]);
    }

 
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success',Yii::t('app','Commissions updated'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success',Yii::t('app','Commissions deleted'));
        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Commissions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCancel()
    {
        return $this->redirect(['index']);
    }
}
