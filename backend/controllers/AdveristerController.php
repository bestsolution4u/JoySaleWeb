<?php

namespace backend\controllers;

use Yii;
use common\models\Adverister;
use backend\models\AdveristerSearch;
use backend\models\AdveristerapprovedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Sitesettings;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;
use yii\db\Expression;
use Braintree;
class AdveristerController extends Controller
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


    
 public function actionIndex()
    {
        $this->layout="page";
        $searchModel = new AdveristerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	public function actionApproved()
    {
        $this->layout="page";
        $searchModel = new AdveristerapprovedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('approved', [
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
        $model = new Adverister();

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

  
    protected function findModel($id)
    {
        if (($model = Adverister::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

  
    public function actionRefund($id)
	{
      
		$paymentData = Adverister::find()->where(['id'=>$id])->one();
	     $tx = $paymentData->tranxId;
	     $paytype = $paymentData->paymentMethod;
       
		$curr = $paymentData['currency'];//echo $curr;die;
		if($paytype == "Paypal Adaptive")
        {  
			$this->canceladaptive($id);
			return;
		}
		else if($paytype == "Braintree")
		{  
			$this->cancelbraintree($id);
			return;
		}
		$amt = $paymentData->totalCost;

		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$paymentsettings = Json::decode($siteSettings->paypal_settings,true);
		$paymenttype = $paymentsettings['paypalType'];
		$apiuserid = $paymentsettings['paypalApiUserId'];
		$apipassword = $paymentsettings['paypalApiPassword'];
		$apisignature = $paymentsettings['paypalApiSignature'];
		$apiappid = $paymentsettings['paypalAppId'];

			$info = array(
			'USER' => $apiuserid,
		    'PWD' => $apipassword,
		    'SIGNATURE' => $apisignature,
		    'Version' => '94',
		    'METHOD' => 'RefundTransaction',
		    'TransactionId' => $tx,
		    'REFUNDTYPE' => 'Partial',
			'AMT' => $amt,
			'CurrencyCode' => $curr
					);

					//$info = json_encode($info);

		if($paymenttype == '2') {

			$apipoint = 'https://api-3t.sandbox.paypal.com/nvp';

		}
		else
		{
			$apipoint = 'https://api-3t.paypal.com/nvp';
		}
		//$apipoint = 'https://api-3t.sandbox.paypal.com/nvp';
		$apiEndpoint = $apipoint;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $apiEndpoint );
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query ($info));
		//curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_POST, true);

		$result = curl_exec($curl);
	//	$result = json_decode($result);
		parse_str( $result, $parsed_result );
	 //   $result = json_decode($result);
      
 
		if ($parsed_result['ACK'] == 'Success') {

			Yii::$app->session->setFlash('success',Yii::t('app','Refund Successfully credited'));
	
			$adModel = Adverister::findOne($id);
			$adModel->status = "cancelled";
			$adModel->trackPayment = "refunded";
			$adModel->save(false);


	     $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
         $check = Users::find()->where(['userId' => $adModel->userid])->one();	   	
		return $this->redirect(['index']);
		}
		else
		{
            Yii::$app->session->setFlash('error',Yii::t('app','Unfortunately Refund is not credited'));
			return $this->redirect($_SERVER['HTTP_REFERER']);
		//	$this->redirect('/admin/merchant_payment_sub_cancelled/');
		}

    }
	
	
	public function actionAccept($id)
	{
		$adModel = Adverister::find()->where(['id'=>$id])->one();
		$adModel->status = "approved";
		$adModel->save(false);
		Yii::$app->session->setFlash('success',Yii::t('app','Banner approved'));
		return $this->redirect(['index']);
	}
    public function canceladaptive($id)
	{
		$paymentData = Adverister::find()->where(['id'=>$id])->one();
		$tx = $paymentData->tranxId;
		$amt = $paymentData->totalCost;
		$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$paymentsettings = Json::decode($siteSettings->paypal_settings,true);
		$paymenttype = $paymentsettings['paypalType'];
		$apiuserid = $paymentsettings['paypalApiUserId'];
		$apipassword = $paymentsettings['paypalApiPassword'];
		$apisignature = $paymentsettings['paypalApiSignature'];
		$apiappid = $paymentsettings['paypalAppId'];

			$info = array(
					"X-PAYPAL-SECURITY-USERID:".$apiuserid."",
					"X-PAYPAL-SECURITY-PASSWORD:".$apipassword."",
					"X-PAYPAL-SECURITY-SIGNATURE:".$apisignature."",
					"X-PAYPAL-APPLICATION-ID:".$apiappid."",
					"X-PAYPAL-REQUEST-DATA-FORMAT:NV",
					"X-PAYPAL-RESPONSE-DATA-FORMAT:JSON"
					);

		    $requestEnvelope = [
					'errorLanguage' =>"en_US",
					"detailLevel" => "ReturnAll"
            ];

			$packet = [
					"requestEnvelope" => $requestEnvelope,
					"payKey" => $tx
            ];

		if($paymenttype == '2') {

			$apipoint = 'https://svcs.sandbox.paypal.com/AdaptivePayments/Refund?payKey='.$tx.'&requestEnvelope.errorLanguage=en_US';

		}
		else
		{
			$apipoint = 'https://svcs.paypal.com/AdaptivePayments/Refund';
		}
		//$apipoint = 'https://svcs.sandbox.paypal.com/AdaptivePayments/Refund';
		$apiEndpoint = $apipoint;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $apiEndpoint );
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ( $curl , CURLOPT_SSLVERSION , CURL_SSLVERSION_TLSv1 ) ;
		curl_setopt ( $curl , CURLOPT_SSL_CIPHER_LIST , ' TLSv1 ' ) ;
		curl_setopt($curl, CURLOPT_HTTPHEADER, $info);
		$result = curl_exec($curl);	
		$result = json_decode($result,true);
	     print_r($result);
		if ($result['responseEnvelope']['ack'] == 'success') {

			Yii::$app->session->setFlash('success',Yii::t('app','Refund Successfully credited'));
			//$this->redirect('/');
			$adModel = Adverister::find()->where(['id'=>$id])->one();
			$adModel->status = "cancelled";
			$adModel->trackPayment = "refunded";
			$adModel->save(false);

			return $this->redirect(['index']);
		}
		else
		{
			Yii::$app->session->setFlash('error',Yii::t('app','Unfortunately Refund is not credited'));
			return $this->redirect($_SERVER['HTTP_REFERER']);

		}
	
    }
    public function cancelbraintree($id)
	{
      
			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$brainTreeSettings = json_decode($siteSettings->braintree_settings, true);
		
			$paymenttype = "sandbox";
			if($brainTreeSettings['brainTreeType'] == 1){
				$paymenttype = "live";
			}
			$paymenttype = "sandbox";
			$merchantid = $brainTreeSettings['brainTreeMerchantId'];
			$publickey = $brainTreeSettings['brainTreePublicKey'];
			$privatekey = $brainTreeSettings['brainTreePrivateKey'];
			$params = array(
				"testmode"   => $paymenttype,
				"merchantid" => $merchantid,
				"publickey"  => $publickey,
				"privatekey" => $privatekey,
			);
			Braintree\Configuration::environment($paymenttype);
			Braintree\Configuration::merchantId($merchantid);
			Braintree\Configuration::publicKey($publickey);
            Braintree\Configuration::privateKey($privatekey);                
			$paymentData = Adverister::find()->where(['id'=>$id])->one();
		    $tx = $paymentData->tranxId;
			$amt = $paymentData->totalCost;

			$result = Braintree\Transaction::refund($tx);
			
			if(isset($result->success))
			{
				  if (strcmp($result->message,"Cannot refund transaction unless it is settled.") == 0) {         
					Yii::$app->session->setFlash('info',Yii::t('app','Cannot refund a transaction unless it is settled.'));
					return $this->redirect(['index']);
				}
      
  		$adModel = Adverister::find()->where(['id'=>$id])->one();
				 $adModel->status = "cancelled";
				 $adModel->trackPayment = "refunded";
				$adModel->save(false);

				$siteSettings =Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				//$check =  Users::findOne($adModel->userid);
		 
        //   $mailer = Yii::$app->mailer->setTransport([
        //     'class' => 'Swift_SmtpTransport',
        //      'host' => $siteSettings['smtpHost'],  
        //      'username' => $siteSettings['smtpEmail'],
        //      'password' => $siteSettings['smtpPassword'],
        //      'port' => $siteSettings['smtpPort'], 
        //      'encryption' =>  'tls', 
        //    ]);

        //       try
        //   {
        //   $order->sendRefundedMail($check->email,$check->name,$id);
         
        //   }
        //   catch(\Swift_TransportException $exception)
        //   {
        //     return $this->redirect($_SERVER['HTTP_REFERER']);
        //   }
			
                 Yii::$app->session->setFlash('success',Yii::t('app','Refunded successfully'));
				return $this->redirect(['index']);
		
	       	
			}
			else
      {
          Yii::$app->session->setFlash('warning',Yii::t('app','Amount not credited. Please login into the braintree and check transaction status'));
        return $this->redirect(['index']);
      }

			
			
			}


}
