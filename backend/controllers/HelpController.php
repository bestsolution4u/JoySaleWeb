<?php

namespace backend\controllers;

use Yii;
use common\models\Help;
use backend\models\HelpSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\growl\Growl;
use common\models\Sitesettings;
use yii\helpers\Json;

class HelpController extends Controller
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
        $this->layout="page";
        $searchModel = new HelpSearch();
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
        $model = new Help();
       
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $model->slug= yii::$app->Myclass->slug($_POST['Help']['page']);
        $help_lang =  $_POST['Help']['help_lang'];  
        $posthelpcontent['content'] = $_POST['Help']['pageContent'];
        $model->pageContent = Json::encode(array_merge(array($help_lang=>$posthelpcontent)));
   

         $model->save();
            Yii::$app->session->setFlash('success',Yii::t('app', 'Help Pages added'));
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
  
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->slug = yii::$app->Myclass->slug($_POST['Help']['page']);

          $help_lang =  $_POST['Help']['help_lang'];  
          $helpcontent = Json::decode($model->pageContent,true);
            if ($helpcontent!="") {
                 
             if (array_key_exists($help_lang, $helpcontent)) {
                unset($helpcontent[$help_lang]);
                $posthelpcontent['content'] = $_POST['Help']['helppageContent'];
                $model->pageContent = Json::encode(array_merge($helpcontent,array($help_lang=>$posthelpcontent)));
             } else {
                $posthelpcontent['content'] =$_POST['Help']['helppageContent'];
                    if($helpcontent=='')
                   $model->pageContent = Json::encode(array($help_lang=>$posthelpcontent));  
                else
                   $model->pageContent = Json::encode(array_merge($helpcontent,array($help_lang=>$posthelpcontent)));  
             }


         }
         else
         {
               $posthelpcontent['content'] = $_POST['Help']['helppageContent'];
                $model->pageContent = Json::encode(array_merge(array($help_lang=>$posthelpcontent)));
         }




            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app','Help Pages updated'));



            return $this->redirect(['index', 'id' => $model->id]);
        }
               if (($model->pageContent!="")) {
                $helpcontentarr = Json::decode($model->pageContent,true);
                $firstelem = array_keys($helpcontentarr)[0];
                $model->helppageContent = $helpcontentarr[$firstelem]['content'];
                $model->helppagelang =$firstelem;

          
                 }
        return $this->render('update', [
            'model' => $model,


        ]);
    }


public function actionGethelpcontent()
{
        $id = $_POST['id'];
      
         $model = $this->findModel($id);
         $help_lang =  $_POST['selectedlanguage'];  
         $helpcontent = Json::decode($model->pageContent,true);
        if ($helpcontent!="") {
             
         if (array_key_exists($help_lang, $helpcontent)) {
      
            echo $helpcontent[$help_lang]['content'];
         } 

     }
   


}


  
    public function actionDelete($id)
    {
        //echo $id; exit;
        if($id == '1' || $id == '2' || $id == '3' || $id == '4')
        {
            Yii::$app->session->setFlash('success', Yii::t('app','Access denied.'));
            return $this->redirect(['index']);
        }else{
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', Yii::t('app','Help pages deleted.'));
            return $this->redirect(['index']);
        }
    }
    
    public function actionCancel()
    {
        return $this->redirect(['index']);
    }
 
    protected function findModel($id)
    {
        if (($model = Help::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
