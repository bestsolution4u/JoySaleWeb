<?php

namespace backend\controllers;

use Yii;
use common\models\Admin;
use backend\models\ModeratorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Roles;
class ModeratorController extends Controller
{
    /**
     * @inheritdoc
     */
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

   
    public function actionIndex()
    {
        $searchModel = new ModeratorSearch();
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
        // $model = new Admin();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);

        $model = new Admin();
        $roles = Roles::find()->all(); 
        if ($model->load(Yii::$app->request->post() )) {
        
            if($model->validate()) {             
                $model->username =$_POST['Admin']['email'];
                $model->email = $_POST['Admin']['email'];
                $model->name = $_POST['Admin']['name'];
                $model->role = $_POST['role'];
                $model->setPassword($_POST['Admin']['password']);
                $model->password_encrypt = base64_encode($_POST['Admin']['password']);
                $model->generateAuthKey();
                $model->save();
              
                Yii::$app->session->setFlash('success',Yii::t('app','Added Successfully'));
                return $this->redirect('index');

    }

}

         return $this->render('create', ['model' => $model,'roles'=>$roles]);
    }
 
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $roles = Roles::find()->all(); 
        $password = $model->password_encrypt;
        if ($model->load(Yii::$app->request->post())) {
            $model->username =$_POST['Admin']['email'];
            $model->email = $_POST['Admin']['email'];
            $model->role = $_POST['role'];
            $check = Admin::find()->where(['id'=>$id])->one();

            if($check['password_encrypt']!=$_POST['Admin']['password']) {
                $model->setPassword($_POST['Admin']['password']);
                $model->password_encrypt = base64_encode($_POST['Admin']['password']);
                $model->generateAuthKey();
            }
        

             if($model->save()){
                 Yii::$app->session->setFlash('success',Yii::t('app','Updated Successfully'));
                return $this->redirect(['index']);
            }
            else{
                Yii::$app->session->setFlash('success',Yii::t('app','Not Updated.Please try again'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,'roles'=>$roles,'password'=>$password]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success',Yii::t('app','Deleted Successfully'));
        return $this->redirect(['index']);
    }

  
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
    public function actionCancel()
    {
        return $this->redirect(['index']);
    }
}
