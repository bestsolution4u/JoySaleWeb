<?php

namespace frontend\controllers;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use common\models\Chats;
use common\models\Users;
use common\models\Messages;
use common\models\Helppages;
use common\models\Userdevices;
use common\models\Sitesettings;
use common\models\Products;
use common\models\Currencies;
use yii\helpers\HtmlPurifier;
use yii\web\Response;
use yii\helpers\Html;
Html::csrfMetaTags();

class MessageController extends \yii\web\Controller
{
	
	public function beforeAction($action)
	{

		$settings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one(); 
    	if ($settings->site_maintenance_mode == '1') {
    		return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/sitemaintenance');
    	}
		 
		$this->enableCsrfValidation = false;
		if (!parent::beforeAction($action)) {
			

			if(!Yii::$app->user->isGuest){
                $User = Users::find()->where(['userId' => Yii::$app->user->id])->one(); 
                if($User->userstatus == 0){
					Yii::$app->user->logout();
					Yii::$app->session->setFlash('error', Yii::t('app','Your account has been disabled by the Administrator')); 
					return $this->goHome();
                 }
            }
		}
		$allowedActions = array('help');
		$user = Yii::$app->user;
		if($user->isGuest && !in_array(Yii::$app->controller->action->id, $allowedActions)) {
			$this->redirect(['/site/login']);
			return false;
		}

		return true;
	}


	public function actionIndex($id = ""){
          
	    $this->layout = 'chat';
		$userId = Yii::$app->user->id;

		$userDetails = yii::$app->Myclass->getUserDetailss(Yii::$app->user->id);
        
		if(!empty($id)) {
	
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];

		//	$chatCriteria->condition = "user1 = '$userId' AND user2 = '$id' OR user1 = '$id' AND user2 = '$userId'";
        //  $chat = Chats::find()->where([('user1' = $userId) AND ('user2' = $id) OR user1 = '$id' AND user2 = '$userId'])
            $sql = "SELECT * FROM `hts_chats` where `user1` = '$userId' AND `user2` = '$id' OR `user1` = '$id' AND `user2` = '$userId'";
            $chat = Chats::findBySql($sql)->one();
           
			if(empty($chat)) {
				$newChat = new Chats();
				$newChat->user1 = $userId;
				$newChat->user2 = $id;
				$newChat->lastContacted = time();
				$newChat->save(false);
				$messageChatId = $newChat->chatId;
			} else {
				$messageChatId = $chat->chatId;
			}
		}

        //$criteria = new CDbCriteria;
        $chart2 = "SELECT * FROM `hts_chats` where `user1` = '$userId' OR `user2` = '$userId' order by lastContacted DESC";
 		//$criteria->condition = "user1 = '$userId' OR user2 = '$userId' order by lastContacted DESC";
		$chatedUsers = Chats::findBySql($chart2)->all();


		
    	$firstChat = "";
		$currentChatUser = $id;
		$firstLastReadCheck = 0;
		$chattingUsers = array();
		// echo ($chatedUsers);
		// exit;
		 	if (count($chatedUsers) > 0){
			$lastMessages = array();
			foreach ($chatedUsers as $chatedUser){
				$chatUserkkey = 0;
				$firstLastReadCheck = 0;
				if ($chatedUser->user1 != $userId){
					$chattingUsers[] = $chatedUser->user1;
					$lastMessages[$chatedUser->user1]['message'] = $chatedUser->lastMessage;
					$lastMessages[$chatedUser->user1]['time'] = $chatedUser->lastContacted;
					$lastMessages[$chatedUser->user1]['blockedUser'] = $chatedUser->blockedUser;

			//		print_r($chatedUser);
					//$currentChatUser = $currentChatUser == '' ? $chatedUser->user1 : $currentChatUser;
					if ($currentChatUser == ""){
						$currentChatUser = $chatedUser->user1;
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}elseif ($currentChatUser == $chatedUser->user1){
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}
					$chatUserkkey = $chatedUser->user1;
					//print_r($chatUserkkey);
				}elseif($chatedUser->user2 != $userId){	
					$chattingUsers[] = $chatedUser->user2;
					$lastMessages[$chatedUser->user2]['message'] = $chatedUser->lastMessage;
					$lastMessages[$chatedUser->user2]['time'] = $chatedUser->lastContacted;
					$lastMessages[$chatedUser->user2]['blockedUser'] = $chatedUser->blockedUser;
					//$currentChatUser = $currentChatUser == '' ? $chatedUser->user2 : $currentChatUser;
					if ($currentChatUser == ""){
						$currentChatUser = $chatedUser->user2;
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
				    	}elseif ($currentChatUser == $chatedUser->user2){
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}
					$chatUserkkey = $chatedUser->user2;
				//	print_r($chatUserkkey);
				}
				if($chatedUser->lastToRead == $userId && $firstLastReadCheck != 1){
				//	print_r($chatedUser->lastToRead);
				
					$lastMessages[$chatUserkkey]['messaggeMarker'] = '<div class="message-unread-count"></div>';//"<i class='fa fa-circle green-dot'></i>";
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = "";
				}elseif($chatedUser->lastToRead != 0 && $firstLastReadCheck != 1){
				//	print_r($chatedUser->lastToRead);
				
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = '<img alt="send-icon" src="'.Yii::$app->urlManager->createAbsoluteUrl('images/reply.png').'">';//"<i class='fa fa-mail-reply'></i>";
					$lastMessages[$chatUserkkey]['messaggeMarker'] = "";
				}else{
				
				//	print_r($chatedUser->lastToRead);
					$lastMessages[$chatUserkkey]['messaggeMarker'] = "";
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = "";
				}
				//$firstChat = $firstChat == '' ? $chatedUser->chatId : $firstChat;
            }
 			$firstChatModel = Chats::findOne($firstChat);
			if($firstChatModel['lastToRead'] != 0 && $firstChatModel['lastToRead'] == $userId){
				$firstChatModel->lastToRead = 0;
				$firstChatModel->save(false);
			}

        	$chatUserModel = Users::find()->where(['userId'=>$chattingUsers])->all();
        	foreach ($chatUserModel as $chatModel){
				$chatUser[$chatModel->userId] = $chatModel;
			}
			//print_r($chatUserModel);exit;

        //	$criteria = new CDbCriteria;
            $messageChart = "SELECT * FROM `hts_messages` where `chatId` = '$firstChat' AND `messageType` NOT LIKE 'exchange'";
		//	$criteria->condition = "chatId = '$firstChat' AND messageType NOT LIKE 'exchange'";
			$messageModel = Messages::findBySql($messageChart)->all();
         //print_r($messageModel);exit;
          
			//$messageModel = Messages::model()->findAllByAttributes(array('chatId'=>$firstChat,
			//		'messageType NOTLIKE'=>'exchange'));
			$messageChatId = $firstChat;
			$currentChatUserImage = $chatUser[$currentChatUser]->userImage;
			if(!empty($currentChatUserImage)) {
				//$currentChatUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$currentChatUserImage),$chatUser[$currentChatUser]->username);
				$currentChatUserImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$currentChatUserImage);
			} else {
				//$currentChatUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$chatUser[$currentChatUser]->username);
				$currentChatUserImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
			}
			if(!empty($userDetails->userImage)) {
				//$currentUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userDetails->userImage),$userDetails->username);
				$currentUserImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userDetails->userImage);
			} else {
				//$currentUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userDetails->username);
				$currentUserImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
			}
			//echo "<pre>";print_r($chattingUsers);print_r($messageModel);die;

			$safetytipsModel = Helppages::find()->where(['id'=>3])->one(); 	
			if(Yii::$app->request->isAjax) {


				return $this->render('index',['currentUserDetails'=>$userDetails, 'chattingUsers'=>$chattingUsers,
				'chatUser'=>$chatUser, 'messageModel'=>$messageModel, 'lastMessages'=>$lastMessages,
				'currentChatUser'=>$currentChatUser, 'currentChatUserImage'=>$currentChatUserImage,
				'currentUserImage'=>$currentUserImage,'messageChatId' => $messageChatId, 'ajaxChat' => 1, 'safetytipsModel' => $safetytipsModel]);

				}else{
				
				return $this->render('index',['currentUserDetails'=>$userDetails, 'chattingUsers'=>$chattingUsers,
				'chatUser'=>$chatUser, 'messageModel'=>$messageModel, 'lastMessages'=>$lastMessages,
				'currentChatUser'=>$currentChatUser, 'currentChatUserImage'=>$currentChatUserImage,
				'currentUserImage'=>$currentUserImage,'messageChatId' => $messageChatId, 'ajaxChat' => 0, 'safetytipsModel' => $safetytipsModel]);

				}
		}else{
			
			return $this->render('index',['currentUserDetails'=>$userDetails,
			'chattingUsers'=>$chattingUsers]);
			
        }
       
	}


	public function actionMessage($id){
             $this->layout = 'chat';
	
		$userId = Yii::$app->user->id;
		$userDetails = yii::$app->Myclass->getUserDetailss(Yii::$app->user->id);

		if(!empty($id)) {
	
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		//	$chatCriteria->condition = "user1 = '$userId' AND user2 = '$id' OR user1 = '$id' AND user2 = '$userId'";
        //  $chat = Chats::find()->where([('user1' = $userId) AND ('user2' = $id) OR user1 = '$id' AND user2 = '$userId'])
            $sql = "SELECT * FROM `hts_chats` where `user1` = '$userId' AND `user2` = '$id' OR `user1` = '$id' AND `user2` = '$userId'";
            $chat = Chats::findBySql($sql)->all();
                    
            
			if(empty($chat)) {
				$newChat = new Chats();
				$newChat->user1 = $userId;
				$newChat->user2 = $id;
				$newChat->lastContacted = time();
				$newChat->save(false);
				$messageChatId = $newChat->chatId;
			} else {
				$messageChatId = $chat->chatId;
			}
		}
        //$criteria = new CDbCriteria;
        $chart2 = "SELECT * FROM `hts_chats` where `user1` = '$userId' OR `user2` = '$userId' order by lastContacted DESC";
 		//$criteria->condition = "user1 = '$userId' OR user2 = '$userId' order by lastContacted DESC";
		$chatedUsers = Chats::findBySql($chart2)->all();
		
    	$firstChat = "";
		$currentChatUser = $id;
		$firstLastReadCheck = 0;
		$chattingUsers = array();
	
		 	if (count($chatedUsers) > 0){
			$lastMessages = array();
			foreach ($chatedUsers as $chatedUser){
				$chatUserkkey = 0;
				$firstLastReadCheck = 0;
				if ($chatedUser->user1 != $userId){
					$chattingUsers[] = $chatedUser->user1;
					$lastMessages[$chatedUser->user1]['message'] = $chatedUser->lastMessage;
					$lastMessages[$chatedUser->user1]['time'] = $chatedUser->lastContacted;
					$lastMessages[$chatedUser->user1]['blockedUser'] = $chatedUser->blockedUser;

			//		print_r($chatedUser);
			//$currentChatUser = $currentChatUser == '' ? $chatedUser->user1 : $currentChatUser;
					if ($currentChatUser == ""){
						$currentChatUser = $chatedUser->user1;
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}elseif ($currentChatUser == $chatedUser->user1){
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}
					$chatUserkkey = $chatedUser->user1;
					//print_r($chatUserkkey);
				}elseif($chatedUser->user2 != $userId){	
					$chattingUsers[] = $chatedUser->user2;
					$lastMessages[$chatedUser->user2]['message'] = $chatedUser->lastMessage;
					$lastMessages[$chatedUser->user2]['time'] = $chatedUser->lastContacted;
					$lastMessages[$chatedUser->user2]['blockedUser'] = $chatedUser->blockedUser;
					//$currentChatUser = $currentChatUser == '' ? $chatedUser->user2 : $currentChatUser;
					if ($currentChatUser == ""){
						$currentChatUser = $chatedUser->user2;
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
				    	}elseif ($currentChatUser == $chatedUser->user2){
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}
					$chatUserkkey = $chatedUser->user2;
				//	print_r($chatUserkkey);
				}
				if($chatedUser->lastToRead == $userId && $firstLastReadCheck != 1){
				//	print_r($chatedUser->lastToRead);
				
					$lastMessages[$chatUserkkey]['messaggeMarker'] = '<div class="message-unread-count"></div>';//"<i class='fa fa-circle green-dot'></i>";
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = "";
				}elseif($chatedUser->lastToRead != 0 && $firstLastReadCheck != 1){
				//	print_r($chatedUser->lastToRead);
				
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = '<img alt="send-icon" src="'.Yii::$app->urlManager->createAbsoluteUrl('images/reply.png').'">';//"<i class='fa fa-mail-reply'></i>";
					$lastMessages[$chatUserkkey]['messaggeMarker'] = "";
				}else{
				
				//	print_r($chatedUser->lastToRead);
					$lastMessages[$chatUserkkey]['messaggeMarker'] = "";
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = "";
				}
				//$firstChat = $firstChat == '' ? $chatedUser->chatId : $firstChat;
            }
 			$firstChatModel = Chats::findOne($firstChat);
			if($firstChatModel->lastToRead != 0 && $firstChatModel->lastToRead == $userId){
				$firstChatModel->lastToRead = 0;
				$firstChatModel->save(false);
			}

        	$chatUserModel = Users::find()->where(['userId'=>$chattingUsers])->all();
        	foreach ($chatUserModel as $chatModel){
				$chatUser[$chatModel->userId] = $chatModel;
			}
		//	print_r($chatUserModel);

        //	$criteria = new CDbCriteria;
            $messageChart = "SELECT * FROM `hts_messages` where `chatId` = '$firstChat' AND `messageType` NOT LIKE 'exchange'";
		//	$criteria->condition = "chatId = '$firstChat' AND messageType NOT LIKE 'exchange'";
			$messageModel = Messages::findBySql($messageChart)->all();
         //  print_r($messageModel);
          
			//$messageModel = Messages::model()->findAllByAttributes(array('chatId'=>$firstChat,
			//		'messageType NOTLIKE'=>'exchange'));
			$messageChatId = $firstChat;
			$currentChatUserImage = $chatUser[$currentChatUser]->userImage;
			if(!empty($currentChatUserImage)) {
				//$currentChatUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$currentChatUserImage),$chatUser[$currentChatUser]->username);
				$currentChatUserImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$currentChatUserImage);
			} else {
				//$currentChatUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$chatUser[$currentChatUser]->username);
				$currentChatUserImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
			}
			if(!empty($userDetails->userImage)) {
				//$currentUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userDetails->userImage),$userDetails->username);
				$currentUserImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userDetails->userImage);
			} else {
				//$currentUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userDetails->username);
				$currentUserImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
			}
			//echo "<pre>";print_r($chattingUsers);print_r($messageModel);die;

			$safetytipsModel = Helppages::find()->where(['id'=>3])->one();
			if(Yii::$app->request->isAjax) {

				return $this->render('index',['currentUserDetails'=>$userDetails, 'chattingUsers'=>$chattingUsers,
				'chatUser'=>$chatUser, 'messageModel'=>$messageModel, 'lastMessages'=>$lastMessages,
				'currentChatUser'=>$currentChatUser, 'currentChatUserImage'=>$currentChatUserImage,
				'currentUserImage'=>$currentUserImage,'messageChatId' => $messageChatId, 'ajaxChat' => 1, 'safetytipsModel' => $safetytipsModel]);

				}else{
				
					return $this->render('index',['currentUserDetails'=>$userDetails, 'chattingUsers'=>$chattingUsers,
					'chatUser'=>$chatUser, 'messageModel'=>$messageModel, 'lastMessages'=>$lastMessages,
					'currentChatUser'=>$currentChatUser, 'currentChatUserImage'=>$currentChatUserImage,
					'currentUserImage'=>$currentUserImage,'messageChatId' => $messageChatId, 'ajaxChat' => 0, 'safetytipsModel' => $safetytipsModel]);

				}
		}else{
		
			return $this->render('index',['currentUserDetails'=>$userDetails,
			'chattingUsers'=>$chattingUsers]);
			
        }
       
	}
	public function actionUpdatechat(){
		   $this->layout = 'chat';

		if(isset($_POST)){
			if($_POST['type'] == 'getcount'){
				$userName = yii::$app->Myclass->checkPostvalue($_POST['userName']) ? $_POST['userName'] : "";
				$userDetails = Users::find()->where(['username'=>$userName])->one();
				if(!empty($userDetails)){
					$userId = $userDetails->userId;
					$messageCount = yii::$app->Myclass->getMessageCount($userId);
					echo $messageCount;
					//return "success";
				//	Yii::$app->session->setFlash('success', "this is new message"); 

				}else{
				//	Yii::$app->session->setFlash('success', "this is new message"); 

					return 0;
				}
		    	}elseif($_POST['type'] == 'markread'){
				$senderName = yii::$app->Myclass->checkPostvalue($_POST['sender']) ? $_POST['sender'] : "";
				$receiverName = yii::$app->Myclass->checkPostvalue($_POST['receiver']) ? $_POST['receiver'] : "";

				$senderDetails = Users::find()->where(['username'=>$senderName])->one();
				$receiverDetails = Users::find()->where(['username'=>$receiverName])->one();
				if(!empty($senderDetails) && !empty($receiverDetails)){
					$senderId = $senderDetails->userId;
					$receiverId = $receiverDetails->userId;

					$condition = "SELECT * FROM `hts_chats` where (`user1` = '$senderId' AND `user2` = '$receiverId') OR (`user1` = '$receiverId' AND `user2` = '$senderId')";
				    $chatModel = Chats::findBySql($condition)->one();

					if (!empty($chatModel)){
						$chatModel->lastToRead = 0;
						$chatModel->save();
					}
				}
			//	Yii::$app->session->setFlash('success', "this is new message"); 

				return 0;
			}
		}
	}

	public function actionPostmessage123(){
		return "suucess";
	}

	public function actionPostmessage(){
		$BlockedUser = yii::$app->Myclass->getChatBlockValue(trim($_POST['chatId']));
	
		 //   return $BlockedUser;
			if(($BlockedUser == 0)) {
	
			//	return $_POST['messageContent']; 
				if($_POST['messageContent']==1){
					//return $_POST['messageContent']; 
	
					// Normal message
				
					if (isset($_POST)){
				
						//$message = yii::$app->Myclass->checkPostvalue($_POST['message']) ? $_POST['message'] : "";
						$message = $_POST['message'];
						$offerId = $_POST['offerId'];
						
					
						$senderId = yii::$app->Myclass->checkPostvalue($_POST['senderId']) ? $_POST['senderId'] : "";
						$messageType = yii::$app->Myclass->checkPostvalue($_POST['senderId']) ? $_POST['messageType'] : "";
						$sourceId = isset($_POST['sourceId']) && $_POST['sourceId'] != "" ? $_POST['sourceId'] : 0;
						$chatId = yii::$app->Myclass->checkPostvalue($_POST['chatId']) ? $_POST['chatId'] : "";
						$timeUpdate = time();
	
						//$p = new CHtmlPurifier();
						$message = HtmlPurifier::process($message);
						 if($offerId!=0 && $offerId!="")
						{
								$offerADType = $_POST['offerADType'];
	
								$offerReceived = Messages::findOne($offerId);
								
								$senderId=$offerReceived->senderId;
								$senderDets = Users::findOne($senderId);
								$senderlang = $senderDets->user_lang;
								$productId=$offerReceived->sourceId;//product Id
								$productDetails = yii::$app->Myclass->getProductDetails($productId);
								$receiverid = $productDetails->userId;
								$receiverDets = Users::findOne($receiverid);
								$receiverlang = $receiverDets->user_lang;

								$productImage =yii::$app->Myclass->getProductImage($productId);
								if($productImage!=""){
									$proImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/item/'.$productId.'/'.$productImage);	
								}
								else{
									 $proImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/item/default.jpg');
								}
								$msg = Json::decode($offerReceived->message, true);
								$offerCurrency = explode('-', $msg['currency']);
								$offcurrencyModel = Currencies::find()->where(['currency_shortcode' => $offerCurrency[1]])->one();
								$currency_mode = $offcurrencyModel->currency_mode;
								$currency_position = $offcurrencyModel->currency_position;
								$mkeOfferPrice=$msg['price'];
								$cartDataURL = yii::$app->Myclass->cart_encrypt($productId."-0-".$mkeOfferPrice."-".$offerId, 'joy*ccart');
								$buynow_URL=Yii::$app->urlManager->createAbsoluteUrl('revieworder2/'.$cartDataURL);
								$sitePaymentModes = yii::$app->Myclass->getSitePaymentModes();
	
								$userImage = yii::$app->Myclass->getUserDetailss($senderId);
								$outputData = array();
								if(!empty($userImage->userImage)) {
								//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userImage->userImage),$userImage->username);
								$outputData['userName'] = $userImage->username;
								$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userImage->userImage);
								} else {
								//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userImage->username);
								$outputData['userName'] = $userImage->username;
								$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
								}
									$date=date('Y-m-d', $timeUpdate);
							
								$outputData['chatURL'] = Yii::$app->getUrlManager()->getBaseUrl()."/message/".yii::$app->Myclass->safe_b64encode($userImage->userId.'-0');
								$outputData['chatTime'] = $timeUpdate;//date('M jS Y', $timeUpdate);
								$outputData['chatTimeWeb'] = $date;
								$outputData['message'] = $message;
								$outputData['messageContent'] = $_POST['messageContent'];
	
								$outputData['type'] = 'offer';
								$outputData['view_url'] = '';
								$outputData['lat'] = '';
								$outputData['lon'] = '';
							
							/* new for offer section */
									$outputData['offer_id'] = $offerId;
									$outputData['offer_type'] = $msg['type'];// acept,decline,sendreceive
									$outputData['offer_price'] = $msg['price'];
									if($currency_mode == "symbol"){
										$outputData['offer_currency'] = $offerCurrency[0];	
									}else
									{
										$outputData['offer_currency'] = $offerCurrency[1];
									}
									
									$outputData['offer_currency_mode'] = $currency_mode;
									$outputData['offer_currency_position'] = $currency_position;
									/*if(Yii::$app->user->id != $senderId){*/
										$outputData['sender_lang'] = $senderlang;
										$outputData['receiver_lang'] = $receiverlang;
									//}
									
									$outputData['offer_status'] = $msg['offerstatus'];// 0-pending,1-accept,2-decline
									$outputData['buynow_status'] = $msg['buynowstatus'];	// offer is buy or not ,0-pending,1-alreadybought
									$outputData['instant_buy'] = $productDetails->instantBuy; // 1-buy available,0-not avaiable
									$outputData['sold_item'] = $productDetails->soldItem;//1- sold,0-available
									$outputData['site_buynowPaymentMode'] = $sitePaymentModes['buynowPaymentMode'];
									$outputData['item_image'] = $proImageUrl;
									$outputData['item_id'] = $productDetails->productId;
									$outputData['buynow_url'] = $buynow_URL;
									$sellerDtls = yii::$app->Myclass->getUserDetailss($productDetails->userId);
									$outputData['seller_name'] = $sellerDtls->username;
									
	
							
							return Json::encode($outputData);
	
	
						}
	
						else if ($message != ""){
						  //   return $message;
							$messageModel = new Messages();
							$messageModel->message = urlencode($message);
							$messageModel->messageType = $messageType;
							$messageModel->senderId = $senderId;
							$messageModel->sourceId = $sourceId;
							$messageModel->chatId = $chatId;
							$messageModel->messageContent = $_POST['messageContent'];
							$messageModel->createdDate = $timeUpdate;
							$messageModel->save(false);
	
							if($sourceId == 0){
								$chatModel = Chats::findOne($chatId);
	
								$chatModel->lastContacted = $timeUpdate;
								if ($chatModel->user1 == $senderId){
									$chatModel->lastToRead = $chatModel->user2;
								}else{
									$chatModel->lastToRead = $chatModel->user1;
								}
								$chatModel->lastMessage = urlencode($message);
								$chatModel->save();
							}
	
							$userImage = yii::$app->Myclass->getUserDetailss($senderId);
							$outputData = array();
							if(!empty($userImage->userImage)) {
								//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userImage->userImage),$userImage->username);
								$outputData['userName'] = $userImage->username;
								$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userImage->userImage);
							} else {
								//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userImage->username);
								$outputData['userName'] = $userImage->username;
								$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
							}
							$outputData['chatURL'] = Yii::$app->getUrlManager()->getBaseUrl()."/message/".yii::$app->Myclass->safe_b64encode($userImage->userId.'-0');
							$outputData['chatTime'] = $timeUpdate;//date('M jS Y', $timeUpdate);
							$outputData['message'] = $message;
							$outputData['messageContent'] = $_POST['messageContent'];
	
							$outputData['type'] = 'normal';
							$outputData['view_url'] = '';
							$outputData['lat'] = '';
							$outputData['lon'] = '';
							$outputData['offer_id'] = '';
												 
							$userid = $chatModel->lastToRead;
							// $criteria = new CDbCriteria;
							// $criteria->addCondition('user_id = "'.$userid.'"');
							$userdevicedet = Userdevices::find()->where(['user_id'=>$userid])->all();
	
							if(count($userdevicedet) > 0){
								foreach($userdevicedet as $userdevice){
								$deviceToken = $userdevice->deviceToken;
								$badge = $userdevice->badge;
								$badge +=1;
								$userdevice->badge = $badge;
								$userdevice->deviceToken = $deviceToken;
								$userdevice->save(false);
								if(isset($deviceToken)){
									$messages = $userImage->name." : ".$message;
									yii::$app->Myclass->pushnot($deviceToken,$messages,$badge,"message");
								}
								}
							} 
	
							return Json::encode($outputData);
	
						}else{
							return "";
						}
					}
				}
				 else if($_POST['messageContent']==2){
					
					//image sharing
					$path ="../web/images/message/";
					if( !is_dir( $path ) ) {
						mkdir( $path );
						chmod( $path, 0777 );
					}
					
					$senderId = yii::$app->Myclass->checkPostvalue($_POST['sendingsource']) ? $_POST['sendingsource'] : "";
					$messageType = yii::$app->Myclass->checkPostvalue($_POST['sendingsource']) ? $_POST['sourccetype'] : "";
					$sourceId = isset($_POST['sourceId']) && $_POST['sourceId'] != "" ? $_POST['sourceId'] : 0;
					$chatId = yii::$app->Myclass->checkPostvalue($_POST['sourcce']) ? $_POST['sourcce'] : ""; 
					$timeUpdate = time();
					// $allowedExts = array("gif", "jpeg", "jpg", "png");
					$allowedExts = array("jpeg", "jpg", "png"); 
					$temp = explode(".", $_FILES["file"]["name"]);
					$extension = end($temp);
					$imageInfo = getimagesize($_FILES["file"]["tmp_name"]); 
					
						if ($_FILES["file"]["error"] > 0) {
							echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
						} else if(trim($_POST['shareMap']) == "Sh@^*M@#" || (in_array($extension, $allowedExts) && ($imageInfo['mime'] == 'image/jpeg' || $imageInfo['mime'] == 'image/png'))) {  
							$ran_num=rand(100,1000);
							$filename = $ran_num.'-'.$senderId.$_FILES["file"]["name"];
						
							move_uploaded_file($_FILES["file"]["tmp_name"],
							"../web/images/message/".$filename);
						   chmod("../web/images/message/".$filename, 0666);
								//echo "Stored in: " . Yii::getPathOfAlias('webroot')."/media/message/" . $filename;
								
							$messageModel = new Messages();
							$messageModel->message = $filename;
							$messageModel->messageType = $messageType;
							$messageModel->senderId = $senderId;
							$messageModel->messageContent=2;
							//echo $senderId;
							$messageModel->sourceId = $sourceId;
							//echo $sourceId;
							$messageModel->chatId = $chatId;
							//echo $chatId;
							$messageModel->createdDate = $timeUpdate;
							//echo $timeUpdate;
							$messageModel->save(false);
	
								
								  
							if($sourceId == 0){
								$chatModel = Chats::findOne($chatId);
	
								$chatModel->lastContacted = $timeUpdate;
								if ($chatModel->user1 == $senderId){
									$chatModel->lastToRead = $chatModel->user2;
								}else{
									$chatModel->lastToRead = $chatModel->user1;
								}
								$chatModel->lastMessage = "Share an Image";
								$chatModel->save();
							}
							$userImage = yii::$app->Myclass->getUserDetailss($senderId);
							$outputData = array();
							if(!empty($userImage->userImage)) {
								$outputData['userName'] = $userImage->username;
								$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userImage->userImage);
							} else {
								$outputData['userName'] = $userImage->username;
								$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
							}
							$outputData['chatURL'] = Yii::$app->getUrlManager()->getBaseUrl()."/message/".yii::$app->Myclass->safe_b64encode($userImage->userId.'-0');
							$outputData['chatTime'] = $timeUpdate;//date('M jS Y', $timeUpdate);
							//$outputData['message'] = $filename;
							$outputData['message'] = Yii::$app->urlManager->createAbsoluteUrl('frontend/web/images/message/'.$filename);
	
							$outputData['messageContent'] = $_POST['messageContent'];
							$outputData['saveimage'] =$filename;
							$url=Yii::$app->urlManager->createAbsoluteUrl('frontend/web/images/message/'.$filename);
							$outputData['type'] = 'image';
							$outputData['view_url'] = $url;
							$outputData['lat'] = '';
							$outputData['lon'] = '';
							$outputData['offer_id'] = '';
							$userid = $chatModel->lastToRead;
							
							// $criteria = new CDbCriteria;
							// $criteria->addCondition('user_id = "'.$userid.'"');
							$userdevicedet = Userdevices::find()->where(['user_id'=>$userid])->all();
							if(count($userdevicedet) > 0){
								foreach($userdevicedet as $userdevice){
									$deviceToken = $userdevice->deviceToken;
									$badge = $userdevice->badge;
									$badge +=1;
									$userdevice->badge = $badge;
									$userdevice->deviceToken = $deviceToken;
									$userdevice->save(false);
									if(isset($deviceToken)){
										$messages = $userImage->name." : Sent an Image";
										yii::$app->Myclass->pushnot($deviceToken,$messages,$badge,"message");
									}
								}
							} 
							return Json::encode($outputData);
						} else {
							echo "Return Code: Image format not supported. <br>";
						}
					}
					else if($_POST['messageContent']==3){
	
				
					// Location sharing
					if (isset($_POST)){
						//$message = yii::$app->Myclass->checkPostvalue($_POST['message']) ? $_POST['message'] : "";
						$message = $_POST['message'];
						$senderId = yii::$app->Myclass->checkPostvalue($_POST['senderId']) ? $_POST['senderId'] : "";
						$messageType = yii::$app->Myclass->checkPostvalue($_POST['senderId']) ? $_POST['messageType'] : "";
						$sourceId = isset($_POST['sourceId']) && $_POST['sourceId'] != "" ? $_POST['sourceId'] : 0;
						$chatId = yii::$app->Myclass->checkPostvalue($_POST['chatId']) ? $_POST['chatId'] : "";
						$timeUpdate = time();
	
						$message = HtmlPurifier::process($message);
						if ($message != ""){
	
							$messageModel = new Messages();
							//$messageModel->message = urlencode($message);
							$messageModel->message = $message;
							$messageModel->messageType = $messageType;
							$messageModel->senderId = $senderId;
							$messageModel->sourceId = $sourceId;
							$messageModel->chatId = $chatId;
							$messageModel->messageContent = $_POST['messageContent'];
							$messageModel->createdDate = $timeUpdate;
							$messageModel->save(false);
	
							if($sourceId == 0){
								$chatModel = Chats::findOne($chatId);
	
								$chatModel->lastContacted = $timeUpdate;
								if ($chatModel->user1 == $senderId){
									$chatModel->lastToRead = $chatModel->user2;
								}else{
									$chatModel->lastToRead = $chatModel->user1;
								}
								$chatModel->lastMessage = "Share an Location";
								$chatModel->save();
							}
	
							$userImage = yii::$app->Myclass->getUserDetailss($senderId);
							$outputData = array();
							if(!empty($userImage->userImage)) {
								//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userImage->userImage),$userImage->username);
								$outputData['userName'] = $userImage->username;
								$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userImage->userImage);
							} else {
								//$userImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userImage->username);
								$outputData['userName'] = $userImage->username;
								$outputData['userImage'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
							}
							$outputData['chatURL'] = Yii::$app->getUrlManager()->getBaseUrl()."/message/".yii::$app->Myclass->safe_b64encode($userImage->userId.'-0');
							$outputData['chatTime'] = $timeUpdate;//date('M jS Y', $timeUpdate);
							$outputData['message'] = $message;
							$outputData['messageContent'] = $_POST['messageContent'];
								$latlon=explode("@#@",$message);
							$outputData['type'] = 'share_location';
							$outputData['view_url'] = '';
							$outputData['lat'] = $latlon[0];
							$outputData['lon'] = $latlon[1];
							$outputData['offer_id'] = '';
							/* echo '<div class="chat-grid-userimage">'.$userImage.'</div><div class="chat-grid-details">
							 <p class="chat-grid-msgs">'.$message.'</p><p class="chat-grid-time">'.date('M jS Y', $timeUpdate).'</p>
							 </div>'; */
							
							$userid = $chatModel->lastToRead;
							// $criteria = new CDbCriteria;
							// $criteria->addCondition('user_id = "'.$userid.'"');
							$userdevicedet = Userdevices::find()->where(['user_id'=>$userid])->all();
	
							if(count($userdevicedet) > 0){
								foreach($userdevicedet as $userdevice){
								$deviceToken = $userdevice->deviceToken;
								$badge = $userdevice->badge;
								$badge +=1;
								$userdevice->badge = $badge;
								$userdevice->deviceToken = $deviceToken;
								$userdevice->save(false);
								if(isset($deviceToken)){
									$messages = $userImage->name." : share a location with you";
									yii::$app->Myclass->pushnot($deviceToken,$messages,$badge,"message");
								}
								}
							}
	
							return Json::encode($outputData);
	
						}else{
							return "";
						}
					}
				}
			} else {
				$userId = Yii::$app->user->id;
				if($BlockedUser == $userId) {
					return "blocked~#~defined";
				} else {
					return "blocked~#~undefined";
				  }			
			  }
		//	echo $senderId;
					//echo $offerId;
		}
	



	public function actionInitiatechat() {

		if (isset($_POST)){
			//$message = yii::$app->Myclass->checkPostvalue($_POST['message']) ? $_POST['message'] : "";
			$senderId = yii::$app->Myclass->checkPostvalue($_POST['sender']) ? $_POST['sender'] : "";
			$receiverId = yii::$app->Myclass->checkPostvalue($_POST['receiver']) ? $_POST['receiver'] : "";
			$messageType = yii::$app->Myclass->checkPostvalue($_POST['messageType']) ? $_POST['messageType'] : "";
			$sourceId = yii::$app->Myclass->checkPostvalue($_POST['sourceId']) ? $_POST['sourceId'] : "";
			$timeUpdate = time();
			$message = $_POST['message'];
			$Products = Products::findone($sourceId);
			if(isset($Products) && $Products->approvedStatus == 0)
			{
				echo "error";
			}
			else
			{

			    $criteria = "SELECT * FROM `hts_chats` where (`user1` = '$senderId' AND `user2` = '$receiverId') OR (`user1` = '$receiverId' AND `user2` = '$senderId')";
            //    $messages = Promotiontransaction::findBySql($sql)->count();	
				
			// 	$criteria = new CDbCriteria;
			// 	$criteria->condition = "(user1 = '$senderId' AND user2 = '$receiverId') OR (user1 = '$receiverId' AND user2 = '$senderId')";

				$chatModel = Chats::findBySql($criteria)->all();

				$encodeMsg = urlencode($message);

				if (empty($chatModel)){
					$newChat = new Chats();
					$newChat->user1 = $senderId;
					$newChat->user2 = $receiverId;
					$newChat->lastMessage = $encodeMsg;
					$newChat->lastToRead = $receiverId;
					$newChat->lastContacted = $timeUpdate;

					$newChat->save(false);



					//$criteria = new CDbCriteria;
				//	$criteria->condition = "(user1 = '$senderId' AND user2 = '$receiverId') OR (user1 = '$receiverId' AND user2 = '$senderId')";
			       $criteria = "SELECT * FROM `hts_chats` where (`user1` = '$senderId' AND `user2` = '$receiverId') OR (`user1` = '$receiverId' AND `user2` = '$senderId')";
                   $chatModel = Chats::findBySql($criteria)->all();
				}
				$chatModel->lastContacted = $timeUpdate;
				if ($chatModel->user1 == $senderId){
					$chatModel->lastToRead = $chatModel->user2;
				}else{
					$chatModel->lastToRead = $chatModel->user1;
				}
				$chatModel->lastMessage = $encodeMsg;
				$chatModel->save(false);

				$messageModel = new Messages();
				$messageModel->message = $encodeMsg;
				$messageModel->messageType = $messageType;
				$messageModel->senderId = $senderId;
				$messageModel->sourceId = $sourceId;
				$messageModel->chatId = $chatModel->chatId;
				$messageModel->createdDate = $timeUpdate;
				$messageModel->save(false);


				$notifyMessage = 'contacted you on your product';
				yii::$app->Myclass->addLogs("myoffer", $senderId, $receiverId, $sourceId, $sourceId, $notifyMessage);

				$userid = $receiverId;
				$sellerDetails = yii::$app->Myclass->getUserDetailss($senderId);
				// $criteria = new CDbCriteria;
				// $criteria->addCondition('user_id = "'.$userid.'"');
				$userdevicedet = Userdevices::find()->where(['user_id'=>$userid])->all();


			if(count($userdevicedet) > 0){
				foreach($userdevicedet as $userdevice){
				$deviceToken = $userdevice->deviceToken;
				$badge = $userdevice->badge;
				$badge +=1;
				$userdevice->badge = $badge;
				$userdevice->deviceToken = $deviceToken;
				$userdevice->save(false);
				if(isset($deviceToken)){
					$messages = $sellerDetails->name." : ".$message;
					yii::$app->Myclass->pushnot($deviceToken,$messages,$badge,"message");
				}
				}
			}

				echo "success";
		}
		}
		else
		{
		echo "failed";
		}
	}


	public function actionIndexx(){
           $this->layout = 'chat';
	//print_r($id);exit;
		$id=$_POST['id'];
		$userId = Yii::$app->user->id;

		$userDetails = yii::$app->Myclass->getUserDetailss(Yii::$app->user->id);

		if(!empty($_POST['id'])) {
	
			$dec = yii::$app->Myclass->safe_b64decode($id);
			$spl = explode('-',$dec);
			$id = $spl[0];
		//	$chatCriteria->condition = "user1 = '$userId' AND user2 = '$id' OR user1 = '$id' AND user2 = '$userId'";
        //  $chat = Chats::find()->where([('user1' = $userId) AND ('user2' = $id) OR user1 = '$id' AND user2 = '$userId'])
         $sql = "SELECT * FROM `hts_chats` where `user1` = '$userId' AND `user2` = '$id' OR `user1` = '$id' AND `user2` = '$userId'";
	     $chat = Chats::findBySql($sql)->one();
                    
            
			if(empty($chat)) {
				$newChat = new Chats();
				$newChat->user1 = $userId;
				$newChat->user2 = $id;
				$newChat->lastContacted = time();
				$newChat->save(false);
				$messageChatId = $newChat->chatId;
			} else {
				$messageChatId = $chat->chatId;
			}
		}
        //$criteria = new CDbCriteria;
        $chart2 = "SELECT * FROM `hts_chats` where `user1` = '$userId' OR `user2` = '$userId' order by lastContacted DESC";
 		//$criteria->condition = "user1 = '$userId' OR user2 = '$userId' order by lastContacted DESC";
		$chatedUsers = Chats::findBySql($chart2)->all();


		
    	$firstChat = "";
		$currentChatUser = $id;
		$firstLastReadCheck = 0;
		$chattingUsers = array();
		// echo ($chatedUsers);
		// exit;
		 	if (count($chatedUsers) > 0){
			$lastMessages = array();
			foreach ($chatedUsers as $chatedUser){
				$chatUserkkey = 0;
				$firstLastReadCheck = 0;
				if ($chatedUser->user1 != $userId){
					$chattingUsers[] = $chatedUser->user1;
					$lastMessages[$chatedUser->user1]['message'] = $chatedUser->lastMessage;
					$lastMessages[$chatedUser->user1]['time'] = $chatedUser->lastContacted;
					$lastMessages[$chatedUser->user1]['blockedUser'] = $chatedUser->blockedUser;

			//		print_r($chatedUser);
					//$currentChatUser = $currentChatUser == '' ? $chatedUser->user1 : $currentChatUser;
					if ($currentChatUser == ""){
						$currentChatUser = $chatedUser->user1;
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}elseif ($currentChatUser == $chatedUser->user1){
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}
					$chatUserkkey = $chatedUser->user1;
					//print_r($chatUserkkey);
				}elseif($chatedUser->user2 != $userId){	
					$chattingUsers[] = $chatedUser->user2;
					$lastMessages[$chatedUser->user2]['message'] = $chatedUser->lastMessage;
					$lastMessages[$chatedUser->user2]['time'] = $chatedUser->lastContacted;
					$lastMessages[$chatedUser->user2]['blockedUser'] = $chatedUser->blockedUser;
					//$currentChatUser = $currentChatUser == '' ? $chatedUser->user2 : $currentChatUser;
					if ($currentChatUser == ""){
						$currentChatUser = $chatedUser->user2;
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
				    	}elseif ($currentChatUser == $chatedUser->user2){
						$firstChat = $chatedUser->chatId;
						$firstLastReadCheck = 1;
					}
					$chatUserkkey = $chatedUser->user2;
				//	print_r($chatUserkkey);
				}
				if($chatedUser->lastToRead == $userId && $firstLastReadCheck != 1){
				//	print_r($chatedUser->lastToRead);
				
					$lastMessages[$chatUserkkey]['messaggeMarker'] = '<div class="message-unread-count"></div>';//"<i class='fa fa-circle green-dot'></i>";
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = "";
				}elseif($chatedUser->lastToRead != 0 && $firstLastReadCheck != 1){
				//	print_r($chatedUser->lastToRead);
				
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = '<img alt="send-icon" src="'.Yii::$app->urlManager->createAbsoluteUrl('images/reply.png').'">';//"<i class='fa fa-mail-reply'></i>";
					$lastMessages[$chatUserkkey]['messaggeMarker'] = "";
				}else{
				
				//	print_r($chatedUser->lastToRead);
					$lastMessages[$chatUserkkey]['messaggeMarker'] = "";
					$lastMessages[$chatUserkkey]['messaggeReplyMarker'] = "";
				}
				//$firstChat = $firstChat == '' ? $chatedUser->chatId : $firstChat;
            }
 			$firstChatModel = Chats::findOne($firstChat);
			if($firstChatModel->lastToRead != 0 && $firstChatModel->lastToRead == $userId){
				$firstChatModel->lastToRead = 0;
				$firstChatModel->save(false);
			}

        	$chatUserModel = Users::find()->where(['userId'=>$chattingUsers])->all();
        	foreach ($chatUserModel as $chatModel){
				$chatUser[$chatModel->userId] = $chatModel;
			}
			//print_r($chatUserModel);exit;

        //	$criteria = new CDbCriteria;
            $messageChart = "SELECT * FROM `hts_messages` where `chatId` = '$firstChat' AND `messageType` NOT LIKE 'exchange'";
		//	$criteria->condition = "chatId = '$firstChat' AND messageType NOT LIKE 'exchange'";
			$messageModel = Messages::findBySql($messageChart)->all();
        // print_r($messageModel);exit;
          
			//$messageModel = Messages::model()->findAllByAttributes(array('chatId'=>$firstChat,
			//		'messageType NOTLIKE'=>'exchange'));
			$messageChatId = $firstChat;
			$currentChatUserImage = $chatUser[$currentChatUser]->userImage;
			if(!empty($currentChatUserImage)) {
				//$currentChatUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$currentChatUserImage),$chatUser[$currentChatUser]->username);
				$currentChatUserImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$currentChatUserImage);
			} else {
				//$currentChatUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$chatUser[$currentChatUser]->username);
				$currentChatUserImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
			}
			if(!empty($userDetails->userImage)) {
				//$currentUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/'.$userDetails->userImage),$userDetails->username);
				$currentUserImage = Yii::$app->urlManager->createAbsoluteUrl('profile/'.$userDetails->userImage);
			} else {
				//$currentUserImage = CHtml::image(Yii::$app->urlManager->createAbsoluteUrl('user/resized/40x40/default/'.yii::$app->Myclass->getDefaultUser()),$userDetails->username);
				$currentUserImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/'.yii::$app->Myclass->getDefaultUser());
			}
			//echo "<pre>";print_r($chattingUsers);print_r($messageModel);die;

			$safetytipsModel = Helppages::find()->where(['id'=>3])->one();
			if(Yii::$app->request->isAjax) {
				
			//	print_r($chatUser);exit;
				return $this->renderPartial('index',['currentUserDetails'=>$userDetails, 'chattingUsers'=>$chattingUsers,
				'chatUser'=>$chatUser, 'messageModel'=>$messageModel, 'lastMessages'=>$lastMessages,
				'currentChatUser'=>$currentChatUser, 'currentChatUserImage'=>$currentChatUserImage,
				'currentUserImage'=>$currentUserImage,'messageChatId' => $messageChatId, 'ajaxChat' => 1, 'safetytipsModel' => $safetytipsModel]);

				}else{
				
				return $this->renderPartial('index',['currentUserDetails'=>$userDetails, 'chattingUsers'=>$chattingUsers,
				'chatUser'=>$chatUser, 'messageModel'=>$messageModel, 'lastMessages'=>$lastMessages,
				'currentChatUser'=>$currentChatUser, 'currentChatUserImage'=>$currentChatUserImage,
				'currentUserImage'=>$currentUserImage,'messageChatId' => $messageChatId, 'ajaxChat' => 0, 'safetytipsModel' => $safetytipsModel]);

				}
		}else{
			
			return $this->render('index',['currentUserDetails'=>$userDetails,
			'chattingUsers'=>$chattingUsers]);
			
       }
       
 
	}

	public function actionChataction()
	{
		 $this->layout = 'chat';
		$user = Yii::$app->user->id;
		if(Yii::$app->user->isGuest) {
			echo '0';
		 }else {
			if (isset($_POST)) {
				if(!empty($_POST['callValue']) && !empty($_POST['currentChatUserID'])) {
					$callValue = base64_decode(base64_decode($_POST['callValue']));
					$currentUserID = Yii::$app->user->id;
					$currentChatUserID = base64_decode($_POST['currentChatUserID']);

				$dec = yii::$app->Myclass->Change_chatUser_status($callValue, $currentUserID, $currentChatUserID);
					if($dec != "false"){
						echo $dec;
						return true;
					}
					

				}
			}
			//echo '0';
		}

	
	}


	public function actionHelp($details){
		if (!empty($details)){
			$helppageModel = Helppages::find()->where(['slug'=>$details])->one();
			if (!empty($helppageModel)){
				$allhelppageModel = Helppages::find()->all();
				return $this->render('help',['helppageModel' => $helppageModel,'allhelppageModel' => $allhelppageModel]);
			}else{
				Yii::$app->session->setFlash('error',"Unable to Process our request");
				$this->redirect(array('/'));
			}
		}else{
			Yii::$app->session->setFlash('error',"Unable to Process our request");
			$this->redirect(array('/'));
		}
	}


}
