<?php

namespace backend\controllers;

use Yii;
use common\models\Users;
use backend\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Sitesettings;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use backend\models\SignupForm;
use common\models\Userdevices;
error_reporting(0);
class UsersController extends Controller
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
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;

       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
          
        ]);
    }

    public function actionView($id)
    {
        $userdevicedet = Userdevices::find()->where(['user_id' => $id])->orderBy(['id' => SORT_DESC])->one();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'userdevicemodel' => $userdevicedet,
        ]);

    }

   public function actionCreate()
    {
        $model = new SignupForm();
        //$model->scenario = 'create';
        if ($model->load(Yii::$app->request->post() )) {
            // $model->activationStatus = 1;
            // $model->save(false);
            // return $this->redirect(['index']);
            // $model->attributes=$_POST['Users'];
           //  $model->username = str_replace(" ",'',$_POST['Users']['username']);
        //   $password = $_POST['Users']['password'];
          
            if($model->validate()) {

                $user = new Users();
                $user->userstatus = 1;
                $user->activationStatus = 1;
                $user->username = $_POST['SignupForm']['username'];
                $user->email = $_POST['SignupForm']['email'];
                $user->name = $_POST['SignupForm']['name'];
                $user->setPassword($_POST['SignupForm']['password']);
                $user->password_encrypt = base64_encode($_POST['SignupForm']['password']);

                $user->generateAuthKey();
                $user->save();

               // $model->userstatus = 1;
                // $model->activationStatus = 1;
                // $model->password = $userModel->setPassword($_POST['SignupForm']['password']);
                // $model->save(false);
               
                $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
                $mailer = Yii::$app->mailer->setTransport([
                    'class' => 'Swift_SmtpTransport',
                     'host' => $siteSettings['smtpHost'],  
                     'username' => $siteSettings['smtpEmail'],
                     'password' => $siteSettings['smtpPassword'],
                     'port' => $siteSettings['smtpPort'], 
                     'encryption' =>  'tls', 
               ]);
                /*
                $user->sendAdminEmail($_POST['SignupForm']['email'],$_POST['SignupForm']['name'],$_POST['SignupForm']['password']);
                */
               Yii::$app->session->setFlash('success',Yii::t('app','User Created'));
               return $this->redirect(['index']);
            //  $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
            //  if($siteSettings->smtpEnable == 1) {
            //      //$mail->IsSMTP();                        
            //      // $mail->Mailer = 'smtp';                        
            //      // $mail->Host = $siteSettings->smtpHost;  ]
            //      // $mail->SMTPAuth = true;                             
            //      // $mail->Username = $siteSettings->smtpEmail;                          
            //      // $mail->Password = $siteSettings->smtpPassword;                         
            //      // if($siteSettings->smtpSSL == 1)
            //      //      $mail->SMTPSecure = 'ssl';                          
            //         // $mail->Port = $siteSettings->smtpPort;
                    
            //         $mailer = Yii::$app->mailer->setTransport([
            //             'class' => 'Swift_SmtpTransport',
            //    'host' => 'smtp.gmail.com',  
            //    'username' => 'livzastream@gmail.com',
            //    'password' => 'livza123',
            //    'port' => '25', 
            //    'encryption' => 'tls', 
            //  ]);

    

            //     }
                
            //     if ($model->sendEmail('jeyalakshmibe86@gmail.com')) {
            //         Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            //         return $this->refresh();
            //     } else {
            //         Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            //         return $this->refresh();
            //     }
            //  // $mail->setView('adminsignup');
            //  // $mail->setData(array('name' => $model->name,'useremail' => $model->email,'password'=>$password,
            //  //          'siteSettings' => $siteSettings));
            //  // $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
            //  // $mail->setTo($model->email);
            //  // $mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Login Credentials'));
            //  // $mail->send();
            //  // Yii::app()->user->setFlash('success',Yii::t('admin','User Created Successfully'));
            //  // $this->redirect(array('admin'));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
   
   /* public function actionCreate()
    {
        $model = new Users();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post() )) {
            // $model->activationStatus = 1;
            // $model->save(false);
            // return $this->redirect(['index']);
            // $model->attributes=$_POST['Users'];
           //  $model->username = str_replace(" ",'',$_POST['Users']['username']);
			 $password = $_POST['Users']['password'];
			
			if($model->validate()) {
                $model->userstatus = 1;
                $model->activationStatus = 1;
				$model->password = base64_encode($password);
                $model->save(false);
                Yii::$app->session->setFlash('success','User Created successfully');
                return $this->redirect(['index']);
			// 	$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			// 	if($siteSettings->smtpEnable == 1) {
			// 		//$mail->IsSMTP();                        
			// 		// $mail->Mailer = 'smtp';                        
			// 		// $mail->Host = $siteSettings->smtpHost;  
			// 		// $mail->SMTPAuth = true;                             
			// 		// $mail->Username = $siteSettings->smtpEmail;                          
			// 		// $mail->Password = $siteSettings->smtpPassword;                         
			// 		// if($siteSettings->smtpSSL == 1)
			// 		// 		$mail->SMTPSecure = 'ssl';                          
            //         // $mail->Port = $siteSettings->smtpPort;
                    
            //         $mailer = Yii::$app->mailer->setTransport([
            //             'class' => 'Swift_SmtpTransport',
            //    'host' => 'smtp.gmail.com',  
            //    'username' => 'livzastream@gmail.com',
            //    'password' => 'livza123',
            //    'port' => '25', 
            //    'encryption' => 'tls', 
            //  ]);

    

            //     }
                
            //     if ($model->sendEmail('jeyalakshmibe86@gmail.com')) {
            //         Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            //         return $this->refresh();
            //     } else {
            //         Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            //         return $this->refresh();
            //     }
			// 	// $mail->setView('adminsignup');
			// 	// $mail->setData(array('name' => $model->name,'useremail' => $model->email,'password'=>$password,
			// 	// 			'siteSettings' => $siteSettings));
			// 	// $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
			// 	// $mail->setTo($model->email);
			// 	// $mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Login Credentials'));
			// 	// $mail->send();
			// 	// Yii::app()->user->setFlash('success',Yii::t('admin','User Created Successfully'));
			// 	// $this->redirect(array('admin'));
			}
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
 */
   
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$model->scenario = 'updateuser'; 
        if ($model->load(Yii::$app->request->post())) {
         if($model->validate()) {
                //Insert value into users.
                $sModel = Users::findOne($id);
                $sModel->name = $_POST['Users']['name'];
                $sModel->save(false);

                Yii::$app->session->setFlash('success',Yii::t('app','User Updated'));
                return $this->redirect(['index']);
        }
    }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

  
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success',Yii::t('app','User Deleted'));

        return $this->redirect(['index']);
    }

       
    public function actionStatus($id)
    {
        $model = $this->findModel($id);

        if($_GET['status']=='inactive')
        {
            $model->userstatus = 0;
            $model->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','User Deactivated'));
        }
        else if($_GET['status']=='active')
        {
            $model->userstatus = 1;
            $model->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','User Activated'));
        }
      
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

     return $this->redirect(Yii::$app->request->referrer);
       
    }

    public function actionResend($id){
               $user = $this->findModel($id);
                $emailTo = $user->email;
                $link = Yii::$app->urlManager->createAbsoluteUrl('/verify/'.base64_encode($emailTo));
                $verifyLink = str_replace("/admin","",$link);
                $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

                $mailer = Yii::$app->mailer->setTransport([
                    'class' => 'Swift_SmtpTransport',
                     'host' => $siteSettings['smtpHost'],  
                     'username' => $siteSettings['smtpEmail'],
                     'password' => $siteSettings['smtpPassword'],
                     'port' => $siteSettings['smtpPort'], 
                     'encryption' =>  'tls', 
               ]);

               try {
                   $userModel = new Users();
                if($userModel->reverifyEmail($user['email'],$verifyLink,$user['name'])) { 
                    Yii::$app->session->setFlash("success",Yii::t('app','User Reverfication mail has been sent Successfully'));
                  }
                } 

                catch(\Swift_TransportException $exception)
                {
                    Yii::$app->session->setFlash('error', 'Sorry, Email verify mail not send, SMTP Connection error check email setting');
                }
       return $this->redirect(Yii::$app->request->referrer);
    }
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCancel()
    {
        return $this->redirect(['index']);
    }
}
