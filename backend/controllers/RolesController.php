<?php

namespace backend\controllers;

use Yii;
use backend\models\Roles;
use backend\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Admin;

class RolesController extends Controller
{
 
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                 //   'delete' => ['POST'],
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

  
    public function actionIndex()
    {
        $searchModel = new RolesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
        $model = new Roles();

        if ($model->load(Yii::$app->request->post()) ) {
         
            // if(!isset($_POST['roles']))
            // {
            //     Yii::$app->getSession()->setFlash('warning','Please assign roles');
            //     return $this->render('create', [
            //                 'model' => $model,
            //             ]);
            // }

            //Post Values
            // if(empty($_POST['priviliges'])){
            //     Yii::$app->session->setFlash('error',Yii::t('app','Please assign role'));
               
            // }
            if($model->validate()) {
              if(empty($_POST['priviliges'])){
                      Yii::$app->session->setFlash('error',Yii::t('app','Please assign role'));
                       
               } else
               {


            $rolename = $_POST['Roles']['name'];
            $priviliges = json_encode($_POST['priviliges']);
           
            $reportData = Roles::find()->where(['name'=>$rolename])->one();
            if(count($reportData)==0)
            {
                //echo '<pre>'; print_r($model); exit;
                $model->name = $rolename;
                $model->comments = $_POST['Roles']['comments'];
                $model->priviliges = $priviliges;
                $model->created_date = date('Y-m-d h:i:s');
                $model->save(false);
                Yii::$app->session->setFlash('success',Yii::t('app','Added Successfully'));
                return $this->redirect(['index']);
            }
            else
            {
                Yii::$app->session->setFlash('error',Yii::t('app','Rolename already exists.'));
                return $this->render('create', [
                            'model' => $model,
                        ]); 
            } 
        }
         
        }
    }   
        return $this->render('create', [
            'model' => $model,
        ]);
    }

   
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $priviliges =json_decode($model->priviliges);



        if ($model->load(Yii::$app->request->post())) {

            $rolename = $_POST['Roles']['name'];
            $priviliges = json_encode($_POST['priviliges']);
            $model->name = $_POST['Roles']['name'];
            $model->comments = $_POST['Roles']['comments'];
            $model->priviliges = $priviliges;
            $model->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','Updated Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,'priviliges'=>$priviliges
        ]);
    }

 
    public function actionDelete($id)
    {
         $adminPr = Admin::find()->where(['role'=> $id])->one();
         if(count($adminPr)<=0){

        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success',Yii::t('app','Deleted Successfully'));
        return $this->redirect(['index']);}
        else
        {
            Yii::$app->session->setFlash('success',Yii::t('app','Sorry..!,Role is assigned for moderator'));
            return $this->redirect(['index']); 
        }
    }

 
    protected function findModel($id)
    {
        if (($model = Roles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
