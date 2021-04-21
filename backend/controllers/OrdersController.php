<?php

namespace backend\controllers;

use Yii;
use common\models\Orders;
use common\models\Users;
use common\models\Orderitems;
use common\models\Shippingaddresses;
use common\models\Trackingdetails;
use common\models\Commissions;
use common\models\Sitesettings;
use backend\models\UsersSearch;
use backend\models\OrderitemsSearch;
use backend\models\InvoicesSearch;
use backend\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\models\Products;
use common\models\Userdevices;
use Braintree;


class OrdersController extends Controller
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
    
    function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

   
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->orderId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


   public function actionScroworders($status = null)
    { 
   $this->layout="page";
        $model=new OrdersSearch();
        $dataProvider = $model->search(Yii::$app->request->queryParams);
               $dataProvider->pagination->pageSize=10;

        //  $searchModel=new OrdersSearch();

        // $dataProvider1 = $searchModel->search(Yii::$app->request->queryParams);
    // print_r($dataProvider1);exit;
       // $model->unsetAttributes();  // clear any default values

        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
      // print_r($siteSettings);exit;
        $commissionStatus = $siteSettings->commission_status;
       // print_r($commissionStatus);exit;
        if(isset($_GET['Orders']))
        $model->attributes=$_GET['Orders'];

        //echo $status; exit;

        if($status == 'approved') {
         //   print_r("expression");exit;
            //  http://localhost/classified/admin/orders/mobileorders/status/approved?tx=0MA61440J37479330&st=Completed&amt=100.00&cc=USD&cm=demo4%40happysale.com-_-2-_--_-vOBiFgPC&item_number=38
            if(Yii::$app->getRequest()->getQueryParam('tx')) {

                $transactionId = Yii::$app->getRequest()->getQueryParam('tx');
                $status = Yii::$app->getRequest()->getQueryParam('st');
                $amount =  Yii::$app->getRequest()->getQueryParam('amt');
                $currency = Yii::$app->getRequest()->getQueryParam('cc');
                $memo = Yii::$app->getRequest()->getQueryParam('cm');

                $details = explode('-_-',$memo);
              //  
                $buyerEmail = $details[0];
                $orderId = $details[1];
                // print_r($details);exit;
                $itemNumber = Yii::$app->getRequest()->getQueryParam('item_number');

                $order = Orders::find()->where(['orderId'=>$orderId])->one();


                if($status == 'Completed') {
                    $order->trackPayment = 'paid';
                    $order->status = 'paid';
                    $order->save(false);

                    $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
                    $check = Users::find()->where(['userId' => $order->sellerId])->one();

                    $mailer = Yii::$app->mailer->setTransport([
                        'class' => 'Swift_SmtpTransport',
                         'host' => $siteSettings['smtpHost'],  
                         'username' => $siteSettings['smtpEmail'],
                         'password' => $siteSettings['smtpPassword'],
                         'port' => $siteSettings['smtpPort'], 
                         'encryption' =>  'tls', 
                   ]);

                  
                     try
                   {
                   $order->sendApprovedMail($check->email,$check->name,$orderId);
                 
                
                   
                }

                catch(\Swift_TransportException $exception)
                {
                    Yii::$app->session->setFlash('warning', 'Sorry, SMTP Connection error check email setting');
                    return $this->redirect(['index']);
                }
                   //  $mail = new YiiMailer();
                   //  if($siteSettings->smtpEnable == 1) {
                   //      //$mail->IsSMTP();                         // Set mailer to use SMTP
                   //      $mail->Mailer = 'smtp';                         // Set mailer to use SMTP
                   //      $mail->Host = $siteSettings->smtpHost;  // Specify main and backup server
                   //      $mail->SMTPAuth = true;                               // Enable SMTP authentication
                   //      $mail->Username = $siteSettings->smtpEmail;                            // SMTP username
                   //      $mail->Password = $siteSettings->smtpPassword;                           // SMTP password
                   //      if($siteSettings->smtpSSL == 1)
                   //          $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
                   //      $mail->Port = $siteSettings->smtpPort;
                   //  }
                   //  $mail->setView('approvedmail');
                   //  $mail->setData(array('name' => $check->name,
                   //                      'siteSettings' => $siteSettings,
                   //                      'orderId'=>$orderId));
                   //  $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
                   //  $mail->setTo($check->email);
                   //  $mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Amount Paid Mail'));
                   //  $mail->send();

                     $notifyMessage = 'paid the amount for your order. Order Id :'.$order->orderId;
                     yii::$app->Myclass->addLogs("adminpayment", 0, $order->sellerId, 0, 0, $notifyMessage);

                   Yii::$app->session->setFlash('success',Yii::t('app','Payment Successfully..!'));
                    return $this->redirect(['scroworders','status' => 'approved']);
                } else {
                 //   echo $status; exit;


                    Yii::$app->session->setFlash('warning',Yii::t('app','Error during transaction.Please try again..!'));
                    //return $this->redirect(['scroworders','status' => 'delivered']);
                }

            }

        }
        if(Yii::$app->request->isAjax) {
            $this->renderPartial('scroworders',[
            'model'=>$model,'status' => $status,'commissionStatus' => $commissionStatus,'dataProvider'=> $dataProvider,'dataProvider1'=> $dataProvider1
            ]);
            Yii::app()->end();
        } else {
            return $this->render('mobileindex',[
            'model'=>$model,'status' => $status,'commissionStatus' => $commissionStatus,'dataProvider'=> $dataProvider
            ]);
        }
    }

 
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->orderId]);
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
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


        public function actionApprove($id) {
        if(!empty($id)) {
            $token = $_POST['token']; 
            $orderId = $id;
            //$orders = Orders::find()->with('orderitems','user','seller')->where(['orderId' => $orderId])->one();
            $orders = Orders::find()->where(['orderId'=>$id])->one();
            $sellerdata=Users::find()->where(['userId'=>$orders->sellerId])->one();
            $userdata=Users::find()->where(['userId'=>$orders->userId])->one();
            $orderitem=Orderitems::find()->where(['orderId'=>$orders->orderId])->one();
              $sellerstripeinfo = Json::decode($sellerdata->stripe_details,true);
              $secretkey = $sellerstripeinfo['stripe_privatekey'];

            $productId = $orderitem['productId'];
            $productModel['name'] = $orderitem['itemName'];
            $productModel['currency'] = $orders['currency'];
            $productModel['productId'] = $productId;
            $productModel['sellerEmail'] = $sellerdata['email'];
            $productModel['sellerpaypalId'] = $orders['sellerPaypalId'];
            $unitPrice = $orderitem['itemunitPrice'];
            $quantity =  $orderitem['itemQuantity'];
//print_r($orders['shippingAddress']);exit;
            $shippingAddressesModel = Shippingaddresses::find()->where([
                    'shippingaddressId'=>$orders['shippingAddress']])->one();
            $totalCost = $orders['totalCost'];
            $shipping = $orders['totalShipping'];
            $discount = $orders['discount'];

            $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
            $commissionStatus = $siteSettings->commission_status;
            if(!empty($discount)) {
                $productPrice = $unitPrice * $quantity;
                $productPrice = $productPrice - $discount;
            }

            if($commissionStatus == 1) {
           
                $comissionModel = Commissions::find()->where(['<','minRate',$unitPrice])
            ->andWhere(['>','maxRate',$unitPrice])->andWhere(['=','status','1'])->orderBy(['id' => SORT_DESC])->one();

           // print_r($comissionModel);exit;
                if(!empty($comissionModel)) {
                    $percentage = $comissionModel->percentage;

                    if(empty($discount)) {
                        $adminCommission = ($unitPrice * ($percentage/100));
                        $itemPrice = $unitPrice - $adminCommission;
                        $adminCommission = $adminCommission * $quantity;
                      
                    } else {
                        $adminCommission = ($productPrice * ($percentage/100));
                        $itemPrice = $productPrice - $adminCommission;
                      
                    }

                } else {
                    $itemPrice = $unitPrice;
                    $adminCommission = 0;
                }
            } else {
                 $itemPrice = $unitPrice;
                $adminCommission = 0;
            }
         //  print_r($discount);exit;

            if(empty($discount)) {
            
                $finalPrice = (int)$itemPrice * (int)$quantity;
                $finalPrice = (int)$finalPrice - (int)$discount;

            } else {
             
                $finalPrice = $itemPrice;
            }


            $price = $finalPrice + $shipping;


        $url = 'https://api.stripe.com/v1/charges';
        $data = array('amount' => $_POST['amt'], 'currency' => $_POST['currency'], 'source' => $token, 'description' => 'Approve payment');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$secretkey,'Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($result,true);
              if( isset($output['error'])) 
            {

                Yii::$app->session->setFlash('error',Yii::t('app',"Amount not credited. Please login into the stripe and check transaction status"));
                return $this->redirect(['scroworders','status' => 'delivered']);

            }else{
        if($output['status'] == 'succeeded') 
            {
                  $order = Orders::find()->where(['orderId'=>$orderId])->one();


               
                    $order->trackPayment = 'paid';
                    $order->status = 'paid';
                    $order->save(false);

                    $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
                    $check = Users::find()->where(['userId' => $order->sellerId])->one();

                    $mailer = Yii::$app->mailer->setTransport([
                        'class' => 'Swift_SmtpTransport',
                         'host' => $siteSettings['smtpHost'],  
                         'username' => $siteSettings['smtpEmail'],
                         'password' => $siteSettings['smtpPassword'],
                         'port' => $siteSettings['smtpPort'], 
                         'encryption' =>  'tls', 
                   ]);

                  
                     try
                   {
                   $order->sendApprovedMail($check->email,$check->name,$orderId);
                 
                
                   
                }

                catch(\Swift_TransportException $exception)
                {
                    Yii::$app->session->setFlash('warning', 'Sorry, SMTP Connection error check email setting');
                    return $this->redirect(['index']);
                }
                   //  $mail = new YiiMailer();
                   //  if($siteSettings->smtpEnable == 1) {
                   //      //$mail->IsSMTP();                         // Set mailer to use SMTP
                   //      $mail->Mailer = 'smtp';                         // Set mailer to use SMTP
                   //      $mail->Host = $siteSettings->smtpHost;  // Specify main and backup server
                   //      $mail->SMTPAuth = true;                               // Enable SMTP authentication
                   //      $mail->Username = $siteSettings->smtpEmail;                            // SMTP username
                   //      $mail->Password = $siteSettings->smtpPassword;                           // SMTP password
                   //      if($siteSettings->smtpSSL == 1)
                   //          $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
                   //      $mail->Port = $siteSettings->smtpPort;
                   //  }
                   //  $mail->setView('approvedmail');
                   //  $mail->setData(array('name' => $check->name,
                   //                      'siteSettings' => $siteSettings,
                   //                      'orderId'=>$orderId));
                   //  $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
                   //  $mail->setTo($check->email);
                   //  $mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Amount Paid Mail'));
                   //  $mail->send();

                     $notifyMessage = 'paid the amount for your order. Order Id :'.$order->orderId;
                     yii::$app->Myclass->addLogs("adminpayment", 0, $order->sellerId, 0, 0, $notifyMessage);

                   Yii::$app->session->setFlash('success',Yii::t('app','Payment Successfully..!'));
                    return $this->redirect(['scroworders','status' => 'approved']);

            }
            else
            {
                 return $this->redirect(['scroworders?status=delivered']);
            }
          }

        //     $paypalSettings = json_decode($siteSettings->paypal_settings, true);
        //     $productModel['paypalId'] = $paypalSettings['paypalEmailId'];

        // return $this->renderPartial('mobilepayment',['orders'=>$orders,'paypalSettings'=>$paypalSettings,'userModel'=> $sellerdata,'productModel' => $productModel,'price' => $price,'shippingAddresses' => $shippingAddressesModel,'orderitem' => $orderitem]);



        }
    }

    public function actionDecline($id) {
        $order = Orders::find()->where(['orderId'=>$id])->one();
        $order->status = "shipped";
        $order->trackPayment = "pending";
        $order->save(false);
        Yii::$app->session->setFlash('success',Yii::t('app','Claim has been Declined successfully..!'));
        return $this->redirect(['scroworders?status=shipped']);
    }

    public function actionView($id)
    {

        $model =  Orders::find()->where(['orderId'=>$id])->one();

        if(empty($model))
        {
            Yii::$app->session->setFlash('success',Yii::t('app','Invalid order Id..'));
            return $this->redirect(['orders/index']);
        }


        $userdata=Users::find()->where(['userId'=>$model->userId])->one();
          $orderitem=Orderitems::find()->where(['orderId'=>$model->orderId])->one();
        $shipping = Shippingaddresses::find()->where(['shippingaddressId' => $model->shippingAddress])->one();
        $trackingDetails = Trackingdetails::find()->where(['orderId' => $id])->one();
        return $this->render('view',[
            'model'=>$model,'shipping' => $shipping,'trackingDetails' => $trackingDetails,'userdata' => $userdata,'orderitem' => $orderitem
        ]);
    }
public function actionIpnprocess() {

  }

	public function actionCancelapprove($id)
	{
    //Get invoice datas..
		$invoiceData = Orders::find()->with('invoices')->where(['orderId'=>$id])->one();

        //echo '<pre>'; print_r($invoiceData); exit;
    if(empty($invoiceData))
    {
        Yii::$app->session->setFlash('success',Yii::t('app','Invalid order Id..'));
        return $this->redirect(['orders/index']);
    }

    //Payment transaction details..
		$tx = $invoiceData['invoices'][0]['paymentTranxid'];
		$paytype = $invoiceData['invoices'][0]['paymentMethod'];

		$curr = $invoiceData['currency'];//echo $curr;die;
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
            else if($paytype == "Stripe")
        {  
            $this->cancelstripe($id);
            return;
        }
		$amt = $invoiceData->totalCost;

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
		//$result = json_decode($result);
		parse_str( $result, $parsed_result );
		//$result = json_decode($result);

		if ($parsed_result['ACK'] == 'Success') {

			Yii::$app->session->setFlash('success',Yii::t('app','Refund Successfully credited'));
			//$this->redirect('/');
			$order = Orders::model()->with('orderitems')->findByPk($id);
			$order->status = "cancelled";
			$order->trackPayment = "refunded";
			$order->save(false);

			$productid = $order['orderitems'][0]['productId'];
			$productdata = Products::findOne($productid);
			$productdata->quantity = 1;
			$productdata->soldItem = 0;
			$productdata->save(false);

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
      $check = Users::find()->where(['userId' => $order->userId])->one();

		/*	$mail = new YiiMailer();
			if($siteSettings->smtpEnable == 1) {
				//$mail->IsSMTP();                         // Set mailer to use SMTP
				$mail->Mailer = 'smtp';                         // Set mailer to use SMTP
				$mail->Host = $siteSettings->smtpHost;  // Specify main and backup server
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = $siteSettings->smtpEmail;                            // SMTP username
				$mail->Password = $siteSettings->smtpPassword;                           // SMTP password
				if($siteSettings->smtpSSL == 1)
					$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
				$mail->Port = $siteSettings->smtpPort;
			}

			$mail->setView('refundedmail');
			$mail->setData(array('name' => $check->name,
								'siteSettings' => $siteSettings,
								'orderId'=>$id));
			$mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
			$mail->setTo($check->email);
			$mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Amount Refunded Mail'));
			$mail->send();
            */

     
			$notifyMessage = 'refunded the amount for your order. Order Id :'.$order->orderId;
			yii::$app->Myclass->addLogs("adminpayment", 0, $order->userId, 0, 0, $notifyMessage);
			return $this->redirect(['scroworders','status' => 'refunded']);
		}
		else
		{
            Yii::$app->session->setFlash('error',Yii::t('app','Unfortunately Refund is not credited'));
			return $this->redirect($_SERVER['HTTP_REFERER']);
		//	$this->redirect('/admin/merchant_payment_sub_cancelled/');
		}

    }
    
    public function canceladaptive($id)
	{

		$invoiceData = Orders::find()->with('invoices')->where(['orderId'=>$id])->one();
		$tx = $invoiceData['invoices'][0]['paymentTranxid'];
		$amt = $invoiceData->totalCost;
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

					//$info = json_encode($info);

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
		//curl_setopt($curl, CURLOPT_POSTFIELDS, $packet);
		curl_setopt ( $curl , CURLOPT_SSLVERSION , CURL_SSLVERSION_TLSv1 ) ;
		curl_setopt ( $curl , CURLOPT_SSL_CIPHER_LIST , ' TLSv1 ' ) ;
		curl_setopt($curl, CURLOPT_HTTPHEADER, $info);

		$result = curl_exec($curl);
		//$result = json_decode($result);
		//parse_str( $result, $parsed_result );
		$result = json_decode($result,true);
		//print_r($result);die;
		if ($result['responseEnvelope']['ack'] == 'Success') {

			Yii::$app->session->setFlash('success',Yii::t('app','Refund Successfully credited'));
			//$this->redirect('/');
			$order = Orders::find()->with('orderitems')->where(['orderId'=>$id])->one();
			$order->status = "cancelled";
			$order->trackPayment = "refunded";
			$order->save(false);

			$productid = $order['orderitems'][0]['productId'];
			$productdata = Products::findOne($productid);
			$productdata->quantity = 1;
			$productdata->soldItem = 0;
			$productdata->save(false);
			return $this->redirect(['scroworders','status' => 'refunded']);
		}
		else
		{
			Yii::$app->session->setFlash('error',Yii::t('app','Unfortunately Refund is not credited'));
			return $this->redirect($_SERVER['HTTP_REFERER']);
		//	$this->redirect('/admin/merchant_payment_sub_cancelled/');
		}
		//echo "<pre>";print_r($result);
		//$this->redirect($_SERVER['HTTP_REFERER']);
    }
    public function cancelbraintree($id)
	{
		//	Yii::setPathOfAlias('Braintree',Yii::getPathOfAlias('application.vendors.Braintree.Braintree'));
      
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
            
         

			$invoiceData = Orders::find()->with('invoices')->where(['orderId'=>$id])->one();
			$tx = $invoiceData['invoices'][0]['paymentTranxid'];

			$amt = $invoiceData->totalCost;
			$result = Braintree\Transaction::refund($tx);



     
			if(isset($result->success))
			{


         if (strcmp($result->message,"Cannot refund transaction unless it is settled.") == 0) {

         // print_r("expression");exit;
        
        Yii::$app->session->setFlash('info',Yii::t('app','Cannot refund a transaction unless it is settled.'));
        return $this->redirect(['scroworders','status' => 'cancelled']);

      }
      // else if (strcmp($result->message,"Transaction has already been completely refunded.") == 0) {

      //    // print_r("expression");exit;
        
      //   Yii::$app->session->setFlash('info',Yii::t('app','Transaction has already been completely refunded.'));
      //   return $this->redirect(['scroworders','status' => 'refunded']);

      // }
      else
      {
         //print_r($result->message);exit;
				$order = Orders::find()->with('orderitems')->where(['orderId'=>$id])->one();
				$order->status = "cancelled";
				$order->trackPayment = "refunded";
				$order->save(false);

				$productid = $order['orderitems'][0]['productId'];
				$productdata = Products::findOne($productid);
				$productdata->quantity = 1;
				$productdata->soldItem = 0;
				$productdata->save(false);

				$siteSettings =Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				$check = Users::findOne($order->userId);
			/*	$mail = new YiiMailer();
				if($siteSettings->smtpEnable == 1) {
					//$mail->IsSMTP();                         // Set mailer to use SMTP
					$mail->Mailer = 'smtp';                         // Set mailer to use SMTP
					$mail->Host = $siteSettings->smtpHost;  // Specify main and backup server
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = $siteSettings->smtpEmail;                            // SMTP username
					$mail->Password = $siteSettings->smtpPassword;                           // SMTP password
					if($siteSettings->smtpSSL == 1)
						$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
					$mail->Port = $siteSettings->smtpPort;
				}
				
				$mail->setView('refundedmail');
				$mail->setData(array('name' => $check->name,
									'siteSettings' => $siteSettings,
									'orderId'=>$id));
				$mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
				$mail->setTo($check->email);
				$mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Amount Refunded Mail'));
				$mail->send();
                */    
          $mailer = Yii::$app->mailer->setTransport([
            'class' => 'Swift_SmtpTransport',
             'host' => $siteSettings['smtpHost'],  
             'username' => $siteSettings['smtpEmail'],
             'password' => $siteSettings['smtpPassword'],
             'port' => $siteSettings['smtpPort'], 
             'encryption' =>  'tls', 
           ]);

              try
          {
          $order->sendRefundedMail($check->email,$check->name,$id);
         
          }
          catch(\Swift_TransportException $exception)
          {
            //Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
           return $this->redirect($_SERVER['HTTP_REFERER']);
          }
				$notifyMessage = 'refunded the amount for your order. Order Id :'.$order->orderId;
                yii::$app->Myclass->addLogs("adminpayment", 0, $order->userId, 0, 0, $notifyMessage);
                Yii::$app->session->setFlash('success',Yii::t('app','Refunded successfully'));
				return $this->redirect(['scroworders','status' => 'refunded']);
      }
			
				
			//	$this->redirect(['scroworders','status' => 'refunded']);
			}
			else
      {
          Yii::$app->session->setFlash('warning',Yii::t('app','Amount not credited. Please login into the braintree and check transaction status'));
        return $this->redirect(['scroworders','status' => 'cancelled']);
      }

			
			
			}

            public function actionNotifyseller($id)
            {



           
            $sellerData = Users::findOne($id);
            $sellerEmail = $sellerData->email;
            $sellerName = $sellerData->name;

            $notifyMessage = "Still You didn't add the stripe credentials. Please add it for getting the amount.";
             
             yii::$app->Myclass->addLogs("adminpayment", 0, $id, 0, 0, $notifyMessage);


                $userdata=Users::find()->where(['userId'=>$id])->one();
                    $userdata->unreadNotification+=1;
                    $userdata->save(false);
                  
                    $criteria = Userdevices::find();
                    $criteria->andWhere(['user_id' => $id]);
                    $userdevicedet = $criteria->all();
                    if(count($userdevicedet) > 0){
                        foreach($userdevicedet as $userdevice){
                            $deviceToken = $userdevice->deviceToken;
                            $lang = $userdevice->lang_type;
                            $badge = $userdevice->badge;
                            $badge +=1;
                            $userdevice->badge = $badge;
                            $userdevice->deviceToken = $deviceToken;
                            $userdevice->save(false);
                            if(isset($deviceToken)){
                                yii::$app->Myclass->push_lang($lang);
                                if (isset($userdata)) {
                                    $messages = Yii::t('app',"Still You didn't add the stripe credentials. Please add it for getting the amount.");
                                yii::$app->Myclass->pushnot($deviceToken,$messages,$badge);
                                }
                            }
                        }
                    }

             Yii::$app->session->setFlash('success',Yii::t('app','Notification send successfully'));
            return $this->redirect(['scroworders','status' => 'delivered']);

            }


            public function cancelstripe($id)
    {
        //  Yii::setPathOfAlias('Braintree',Yii::getPathOfAlias('application.vendors.Braintree.Braintree'));
      
            $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
            $stripeSettings = json_decode($siteSettings->stripe_settings, true);

            $paymenttype = "sandbox";
            if($stripeSettings['stripeType'] == 1){
                $paymenttype = "live";
            }
            $paymenttype = "sandbox";

         

            $invoiceData = Orders::find()->with('invoices')->where(['orderId'=>$id])->one();
            $tx = $invoiceData['invoices'][0]['paymentTranxid'];

            $amt = $invoiceData->totalCost;


             
             $secretkey=$stripeSettings['stripePrivateKey']; 


             $url = 'https://api.stripe.com/v1/charges/'.$tx.'/refund';
             $data = array('reason' => 'requested_by_customer');

                    $ch = curl_init();
                    //print_r($data);die;
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    // //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $secretkey,'Content-Type: application/x-www-form-urlencoded'));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $result = curl_exec($ch);
                    curl_close($ch);
                    $output = json_decode($result,true);
 

            if( isset($output['error'])) 
            {

                Yii::$app->session->setFlash('error',Yii::t('app',"Amount not credited. Please login into the stripe and check transaction status"));
                return $this->redirect(['scroworders','status' => 'cancelled']);

            }else{


           if($output['status'] == 'succeeded') 
            {

         // if (strcmp($result->message,"Cannot refund transaction unless it is settled.") == 0) {

         // print_r("expression");exit;
        
      //   Yii::$app->session->setFlash('info',Yii::t('app','Cannot refund a transaction unless it is settled.'));
      //   return $this->redirect(['scroworders','status' => 'cancelled']);

      // }
      // else if (strcmp($result->message,"Transaction has already been completely refunded.") == 0) {

      //    // print_r("expression");exit;
        
      //   Yii::$app->session->setFlash('info',Yii::t('app','Transaction has already been completely refunded.'));
      //   return $this->redirect(['scroworders','status' => 'refunded']);

      // }
      // else
      // {
         //print_r($result->message);exit;
                $order = Orders::find()->where(['orderId'=>$id])->one();
                $order->status = "cancelled";
                $order->trackPayment = "refunded";
                $order->save(false);

                $productid = $order['orderitems'][0]['productId'];
                $productdata = Products::findOne($productid);
                $productdata->quantity = 1;
                $productdata->soldItem = 0;
                $productdata->save(false);

                $siteSettings =Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
                $check = Users::findOne($order->userId);
            /*  $mail = new YiiMailer();
                if($siteSettings->smtpEnable == 1) {
                    //$mail->IsSMTP();                         // Set mailer to use SMTP
                    $mail->Mailer = 'smtp';                         // Set mailer to use SMTP
                    $mail->Host = $siteSettings->smtpHost;  // Specify main and backup server
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = $siteSettings->smtpEmail;                            // SMTP username
                    $mail->Password = $siteSettings->smtpPassword;                           // SMTP password
                    if($siteSettings->smtpSSL == 1)
                        $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
                    $mail->Port = $siteSettings->smtpPort;
                }
                
                $mail->setView('refundedmail');
                $mail->setData(array('name' => $check->name,
                                    'siteSettings' => $siteSettings,
                                    'orderId'=>$id));
                $mail->setFrom($siteSettings->smtpEmail, $siteSettings->sitename);
                $mail->setTo($check->email);
                $mail->setSubject($siteSettings->sitename.' '.Yii::t('app','Amount Refunded Mail'));
                $mail->send();
          //       */    
          // $mailer = Yii::$app->mailer->setTransport([
          //   'class' => 'Swift_SmtpTransport',
          //    'host' => $siteSettings['smtpHost'],  
          //    'username' => $siteSettings['smtpEmail'],
          //    'password' => $siteSettings['smtpPassword'],
          //    'port' => $siteSettings['smtpPort'], 
          //    'encryption' =>  'tls', 
          //  ]);

          //     try
          // {
          // $order->sendRefundedMail($check->email,$check->name,$id);
         
          // }
          // catch(\Swift_TransportException $exception)
          // {
          //   //Yii::$app->session->setFlash('error', Yii::t('app','Sorry, SMTP Connection error check email setting'));
          //  return $this->redirect($_SERVER['HTTP_REFERER']);
          // }
                $notifyMessage = 'refunded the amount for your order. Order Id :'.$order->orderId;
                yii::$app->Myclass->addLogs("adminpayment", 0, $order->userId, 0, 0, $notifyMessage);
                Yii::$app->session->setFlash('success',Yii::t('app','Refunded successfully'));
                return $this->redirect(['scroworders','status' => 'refunded']);
      // }
            
                
            //  $this->redirect(['scroworders','status' => 'refunded']);
            }
            else
      {

          Yii::$app->session->setFlash('warning',Yii::t('app','Amount not credited. Please login into the stripe and check transaction status'));
        return $this->redirect(['scroworders','status' => 'cancelled']);
      }
    }

            
            
            }


public function actionStripeapprove()
{
     \Stripe\Stripe::setApiKey('sk_test_HEXmUeqhpqDvOEBPLWqE1eDx00DpxXVYFJ');

                        $refund = \Stripe\Refund::create([
                           'charge' => 'ch_1GIru4HRGC4reM8QDHap4YHF'
                        ]);
                        $striperesult = $refund->jsonSerialize();
                        echo "<pre>"; print_r($striperesult); die;
}



}
