<?php
namespace frontend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\helpers\Json;
use common\models\Sitesettings;
use common\models\Users;
use common\models\Followers;
use common\models\Logs;
use common\models\Exchanges;
use common\models\Products;
use common\models\Promotions;
use common\models\Promotiontransaction;
use common\models\Favorites;
use common\models\Reviews;
use common\models\Userdevices;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\models\Helppages;
use common\models\Messages;
use common\models\Chats;
use common\models\Adspromotiondetails;
use common\models\Comments;
use common\models\Categories;
use common\models\Banners;
use common\models\Productconditions;
use common\models\Currencies;
use common\models\Country;
use common\models\Coupons;
use common\models\Orders;
use common\models\Filter;
use common\models\Filtervalues;
use common\models\Productfilters;
use common\models\Shippingaddresses;
use common\models\Invoices;
use common\models\Trackingdetails;
use common\models\Orderitems;
use common\models\Tempaddresses;
use common\models\Photos;
use common\models\Resetpassword; 
use common\models\Userviews;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use yii\helpers\ArrayHelper;
use yii\db\Query; 
use Braintree;
use twilio;
use yii\db\Expression;
use yii\imagine\Image;
use Imagine\Image\Box;
use yii\helpers\Url;
error_reporting(0);

class ApiController extends \yii\web\Controller  
{
	public $enableCsrfValidation = false;
	const PENDING = 0;
	const ACCEPT = 1;
	const DECLINE = 2;
	const CANCEL = 3;
	const SUCCESS = 4;
	const FAILED = 5;
	const SOLDOUT = 6;

	public $errorMessage;
	public function checking($user_id)
	{
		$userId = $user_id;
		if (isset($userId) && $userId != "") {
			$userModel = Users::find()->where(['userId' => $userId])->one();
			if (empty($userModel)) {
				$this->errorMessage = '{"status":"error","message":"User not registered yet"}';
				return false;
			} else {
				if ($userModel->userstatus == 0) {
					$this->errorMessage = '{"status":"error","message":"Your account has been disabled by the Administrator"}';
					return false;
				} elseif (($userModel->userstatus == 0 || $userModel->activationStatus == 0)) {
					$this->errorMessage = '{"status":"error","message":"Please activate your account by the email sent to you"}';
					return false;
				}
			}
		}
		return true;
	}


	/* CHECK ITEM STATUS PARAMS - $api_username, $api_password, $item_id */
	public function actionCheckItemstatus()
	{

		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$item_id = $_POST['item_id'];

			if (isset($item_id)) {
				$productModel = Products::findOne($item_id);
				if (isset($productModel->approvedStatus) && $productModel->approvedStatus == "1") {
					$item_approve = "1";
				} else {
					$item_approve = "0";
				}
				return '{"status":"true","item_approve":' . $item_approve . '}';

			} else {
				return '{"status":"false", "message":"Something went wrong"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}

	}


	/*
		PAYMENT PARAMS - $api_username, $api_password, $user_id, $shipping_id, $item_id, $quantity, $size, $coupon_id
	 */
	public function actionPayment()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$userId = $_POST['user_id'];

			if ($this->checking($userId)) {
				//Post Values
				$shipping_id = '';
				if (isset($_POST['shipping_id'])) {
					$shipping_id = $_POST['shipping_id'];
				}
				$productId = '';
				if (isset($_POST['item_id'])) {
					$productId = $_POST['item_id'];
				}
				$quantity = '';
				if (isset($_POST['quantity'])) {
					$quantity = $_POST['quantity'];
				}
				$size = '';
				if (isset($_POST['size'])) {
					$size = $_POST['size'];
				}
				$coupon_id = '';
				if (isset($_POST['coupon_id'])) {
					$coupon_id = $_POST['coupon_id'];
				}

				$productModel = Products::find()->where(['productId' => $productId])->one();
				if (!empty($productModel)) {
					$sellerModel = yii::$app->Myclass->getUserDetailss($productModel->userId);
					$userModel = Users::findOne($userId);
					$shippingAddressesModel = Tempaddresses::find()->where(['shippingaddressId' => $shipping_id])->one();
					if (!empty($shippingAddressesModel)) {
						$countryCode = $shippingAddressesModel->countryCode;
						$shippingFlag = 0;
						foreach ($productModel->shippings as $shippingModel) {
							if ($shippingModel->countryId == $countryCode) {
								$shippingPrice = $shippingModel->shippingCost;
								$shippingFlag = 1;
							} elseif ($shippingModel->countryId == 0 && $shippingFlag == 0) {
								$shippingPrice = $shippingModel->shippingCost;
							}
						}
						$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
						$paypalSettings = Json::decode($siteSettings->paypal_settings, true);

						if ($size == '') {
							$itemPrice = $productModel->price;
						} else {
							$options = Json::decode($productModel->sizeOptions, true);
							$optionDetails = $options[$size];
							if ($optionDetails['price'] != '') {
								$itemPrice = $optionDetails['price'];
							} else {
								$itemPrice = $productModel->price;
							}
						}
						$productPrice = $itemPrice;

						$discount = 0;
						$productDetails['couponId'] = "";
						$finalPrice = $itemPrice * $quantity;
						if (!empty($coupon_id)) {
							$couponModel = Coupons::find()->where(['id' => $coupon_id])->one();
							$couponType = $couponModel->couponType;
							$productDetails['couponId'] = $couponModel->id;
							if ($couponType == "1") {
								$discount = $quantity * $couponModel->couponValue;
							} else {
								$discount = ($itemPrice * $quantity) * ($couponModel->couponValue / 100);
								if ($couponModel->maxAmount != 0 && $couponModel->maxAmount < $discount) {
									$discount = $couponModel->maxAmount;
								}
							}
							$finalPrice = $finalPrice - $discount;
						}

						if (!empty($shippingPrice)) {
							$finalPrice = $finalPrice + $shippingPrice;
						}
						$productDetails['shippingId'] = $shipping_id;
						$productDetails['quantity'] = $quantity;
						$productDetails['options'] = $size;
						$productDetails['shippingPrice'] = $shippingPrice;
						$productDetails['discount'] = $discount;

						$result['ipnUrl'] = Yii::$app->urlManager->createAbsoluteUrl('/ipnprocess');
						$result['memo'] = $userModel['email'] . "-_-" . $shipping_id . "-_-" . $size . "-_-" . $coupon_id;

						$result['adminEmail'] = $paypalSettings['paypalEmailId'];

						$cur = explode("-", $productModel->currency);
						$result['currencyCode'] = $cur[0];
						$result['currencySymbol'] = $cur[1];

						$result['grandTotal'] = $finalPrice;

						if($_POST['lang_type'] == "ar")
							$result['formatted_grandTotal'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($cur,$finalPrice);
						else
							$result['formatted_grandTotal'] = yii::$app->Myclass->getFormattingCurrencyapi($cur,$finalPrice);
						
						$result['discountAmount'] = $discount;
						if($_POST['lang_type'] == "ar")
						 	$result['formatted_discountAmount'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($cur,$discount);
						else
							$result['formatted_discountAmount'] = yii::$app->Myclass->getFormattingCurrencyapi($cur,$discount);
						$result['itemName'] = $productModel->name;
						$result['itemPrice'] = $productPrice;
						if($_POST['lang_type'] == "ar")
							$result['formatted_itemPrice'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($cur,$productPrice);
						else
							$result['formatted_itemPrice'] = yii::$app->Myclass->getFormattingCurrencyapi($cur,$productPrice);
						$result['itemSize'] = $size;
						$result['itemShip'] = $shippingPrice;
						if($_POST['lang_type'] == "ar")
							$result['formatted_itemShip'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($cur,$shippingPrice);
						else
							$result['formatted_itemShip'] = yii::$app->Myclass->getFormattingCurrencyapi($cur,$shippingPrice);
						$result['itemCount'] = $quantity;
						$result['identifier'] = $productModel->productId;

						$final = Json::encode($result);
						return '{"status": "true","result":' . $final . '}';
					} else {
						return '{"status":"false", "message":"There is no Shipping address defined for your account."}';
					}
				} else {
					return '{"status":"false", "message":"Item Not Found."}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}


	public function authenticateAPI($username, $password)
	{
		if ($username != "" && $password != "") {
			$sitesettingsModel = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$apiDetails = Json::decode($sitesettingsModel->api_settings, true);
			$apiUsername = $apiDetails['apicredential']['current']['username'];
			$apiPassword = $apiDetails['apicredential']['current']['password'];

			if ($username == $apiUsername && $password == $apiPassword) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	/* 
		LOGIN PARAMS -  $api_username, $api_password, $email, $password
	 */
	public function actionLogin()
	{

		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$email = $_POST['email'];

			$userModel = Users::find()->where(['email' => $email])->one();
			if (!empty($userModel)) {
				$encryptPassword = base64_encode($_POST['password']);

				if ($encryptPassword == $userModel->password_encrypt) {
					if ($userModel->userstatus == 1) {
						if ($userModel->activationStatus == 1) {
							if (!empty($userModel->userImage)) {
								$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userModel->userImage);
							} else {
								$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . yii::$app->Myclass->getDefaultUser());
							}
							$review = Reviews::find()->select(['avg(rating) as rating'])->where(['receiverId' => $userModel->userId])->one();
							$user_review_rating = Reviews::find()->where(['receiverId' => $userModel->userId])->count();
								$userModel->lastLoginDate=time();
                				$userModel->save(false);
							return '{"status":"true","user_id":"' . $userModel->userId . '","email":"' . $userModel->email . '", "full_name":"' . $userModel->name . '","user_name":"' .
								$userModel->username . '","full_name":"' . $userModel->name . '",
								"email":"' . $userModel->email . '","photo":"' . $userImage . '","rating":"' . $review->rating . '","rating_user_count":"' . $user_review_rating . '"}';
						} else {
							return '{"status":"false","message":"Please activate your account by the email sent to you"}';
						}
					} else {
						return '{"status":"false","message":"Your account has been blocked by admin"}';
					}
				} else {
					return '{"status":"false","message":"Please enter correct email and password"}';
				}
			} else {
				return '{"status":"false","message":"User not registered yet"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}


	/* 
		SIGNUP PARAMS -  $api_username, $api_password, $user_name, $full_name, $email, $password
	 */
	public function actionSignup()
	{

		//Post Values 
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$username = $_POST['user_name'];
			$fullName = $_POST['full_name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$city_name = '';
			if (isset($_POST['city_name'])) {
				$city_name = $_POST['city_name'];
			}
			$state_name = '';
			if (isset($_POST['state_name'])) {
				$state_name = $_POST['state_name'];
			}
			$country_name = '';
			if (isset($_POST['country_name'])) {
				$country_name = $_POST['country_name'];
			}
			$userModel = Users::find()->where(['email' => $email])
				->orWhere(['username' => $username])->one();

			if (empty($userModel)) {
				$newUser = new Users();
				$newUser->username = $username;
				$newUser->name = $fullName;
				$newUser->password_encrypt = base64_encode($password);
				$newUser->setPassword($password);
				$newUser->email = $email;
				$newUser->userstatus = 1;
				$newUser->phonevisible = 0;
				$newUser->city = $city_name;
				$newUser->state = $state_name;
				$newUser->country = $country_name;
				$verifyLink = Yii::$app->urlManager->createAbsoluteUrl('/verify/' . base64_encode($email));
				if ($newUser->save(false)) {
					$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
					if ($siteSettings->signup_active == 'yes') {
						$mailer = Yii::$app->mailer->setTransport([
							'class' => 'Swift_SmtpTransport',
							'host' => $siteSettings['smtpHost'],
							'username' => $siteSettings['smtpEmail'],
							'password' => $siteSettings['smtpPassword'],
							'port' => $siteSettings['smtpPort'],
							'encryption' => 'tls',
						]);

						try {
							$Users = new Users();
							if ($Users->sendEmail($email, $verifyLink, $fullName)) {
								return '{"status":"true","message":"An email was sent to your mail box, please activate your account and login."}';
							}
						} catch (\Swift_TransportException $exception) {
							return '{"status":"true","message":"An email was sent to your mail box, please activate your account and login."}';
						} catch (\Exception $e) {
							return '{"status":"true","message":"An email was sent to your mail box, please activate your account and login."}';
						}

					} else {
						$newUser->activationStatus = 1;
						$newUser->save(false);
						return '{"status":"true","message":"account has been created, Amazing products are waiting for you, kindly login."}';
					}

				} else {
					return '{"status":"false", "message":"Sorry, unable to create user, please try again later"}';
				}
			} else {
				if (strcasecmp($userModel->email, $email) == 0) {
					return '{"status":"false","message":"Email already exists"}';
				} elseif (strcasecmp($userModel->username, $username) == 0) {
					return '{"status":"false","message":"Username already exists"}';
				}
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}


	/* 
		SOCIAL LOGIN PARAMS -  $api_username, $api_password, $type, $id, $first_name, $last_name, $email, $image_url
	 */
	public function actionSociallogin()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		// if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$type = $_POST['type'];
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email = $_POST['email'];
			$image_url = $_POST['image_url'];
			$socialId = $_POST['id'];

			$fullName = $first_name . " " . $last_name;
			$userName = $first_name . $last_name;
			if($last_name ==" ")
			{
				$userName = $first_name;
			}
			else
			{
				$userName = str_replace(" ", '',$first_name) . $last_name;
			}

			if ($email != "" && $type == 'twitter') {
				$userEmailCheck = Users::find()->where(['email' => $email])->one();
				if (!empty($userEmailCheck))
					return '{"status":"false", "message":"Email Already Exist"}';
			}

			$city_name = (isset($_POST['city_name'])) ? trim($_POST['city_name']) : '';
			$state_name = (isset($_POST['state_name'])) ? trim($_POST['state_name']) : '';
			$country_name = (isset($_POST['country_name'])) ? trim($_POST['country_name']) : ''; 

			$criteria = Users::find();
			if ($email != "")
			{
				$fbdetails['email'] =  $email;
				$criteria->andWhere(["email" => $email]);
			}

			if ($type == 'facebook') {
				$criteria->orWhere(['facebookId' => $socialId]);
			} elseif ($type == 'twitter') {
				$criteria->orWhere(['twitterId' => $socialId]);
			} 
			elseif ($type == 'apple') {
				$criteria->orWhere(['appleId' => $socialId]);


			}else {
				$criteria->orWhere(['googleId' => $socialId]);
			}


			$userModel = $criteria->one();


			if (empty($userModel) && $email != "") {
	
				if($image_url == "")
			    {
				    $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				    $imageName=$siteSettings['default_userimage'];
				    $imageUrl = Yii::$app->urlManager->createAbsoluteUrl('frontend/web/media/logo' . $imageName);
			    }
			    else
			    {
				    $imageName = yii::$app->Myclass->getImagefromURL($image_url);
				    $imageUrl = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $imageName);
			    }
				$userModel = new Users();
				$userModel->name = $fullName;
				$userModel->username = "";
				$userModel->password = "";
				$userModel->email = $email;
				$userModel->userstatus = 1;
				$userModel->activationStatus = 1;
				$userModel->city = $city_name;
				$userModel->state = $state_name;
				$userModel->country = $country_name; 

				if ($userModel->userImage == '') {
					$userModel->userImage = $imageName;
				}
				if ($type == 'facebook') {

					
					$fbdetails['firstName'] =  $first_name;
					$fbdetails['lastName'] =  $last_name;
					
					$fb_detail = json_encode($fbdetails);
					$userModel->fbdetails = $fb_detail;
					$userModel->facebookId = $socialId;

				} elseif ($type == 'twitter') {
					$userModel->twitterId = $socialId;
				} 
				elseif ($type == 'apple') {

					$userModel->appleId = $socialId;


				}else {
					$userModel->googleId = $socialId;
				}

				$userModel->save(false);

				$userModel->username = $userName . $userModel->userId;
				$userModel->lastLoginDate=time();
                	
				$userModel->save(false);

				if (!empty($userModel->userImage)) {
					$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userModel->userImage);
				} else {
					$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . yii::$app->Myclass->getDefaultUser());
				}
				$review = Reviews::find()->select(['avg(rating) as rating'])->where(['receiverId' => $userModel->userId])->one();
				$user_review_rating = Reviews::find()->where(['receiverId' => $userModel->userId])->count();
				return '{"status":"true","user_id":"' . $userModel->userId . '", "user_name":"' . $userModel->username . '", "full_name":"' . $userModel->name . '", "email":"' . $userModel->email . '", "photo":"' .
					$userImage . '", "rating":"' . $review->rating . '", "rating_user_count":"' . $user_review_rating . '"}';
				die;
			} elseif (!empty($userModel) && $userModel->userstatus == 1) {
				if ($type == 'facebook') {
					$fbdetails['firstName'] =  $first_name;
					$fbdetails['lastName'] =  $last_name;
					
					$fb_detail = json_encode($fbdetails);
					$userModel->fbdetails = $fb_detail;
					$userModel->facebookId = $socialId;
					$userModel->userstatus = 1;
				} elseif ($type == 'twitter') {
					$userModel->twitterId = $socialId;
					$userModel->userstatus = 1;
				}  elseif ($type == 'apple') {
					$userModel->appleId = $socialId;
					$userModel->userstatus = 1;
				}else {
					$userModel->googleId = $socialId;
					$userModel->userstatus = 1;
				}

				if(!empty($city_name))
					$userModel->city = $city_name;

				if(!empty($state_name))
					$userModel->state = $state_name;

				if(!empty($country_name))  
					$userModel->country = $country_name;

				$userModel->save(false);

				if (!empty($userModel->userImage)) {
					$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userModel->userImage);
				} else {
					$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . yii::$app->Myclass->getDefaultUser());
				}
				$review = Reviews::find()->select(['avg(rating) as rating'])->where(['receiverId' => $userModel->userId])->one();
				$user_review_rating = Reviews::find()->where(['receiverId' => $userModel->userId])->count();
				return '{"status":"true","user_id":"' . $userModel->userId . '",
					"user_name":"' . $userModel->username . '","full_name":"' . $userModel->name . '","email":"' . $userModel->email . '","photo":"' .
					$userImage . '","rating":"' . $review->rating . '", "rating_user_count":"' . $user_review_rating . '"}';

			} elseif (empty($userModel) && $email == "") { 
				return '{"status":"false", "message":"Account not found"}';
			} else {
				return '{"status":"false","message":"Your account has been blocked by admin"}';
			}
		// } else {
		// 	return '{"status":"false", "message":"Unauthorized Access to the API"}';
		// }
	}


	/* 
		FORGET PASSWORD PARAMS -  $api_username, $api_password, $email
	 */
	public function actionForgetpassword()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$email = $_POST['email'];
			$userModel = Users::find()->where(['email' => $email])
				->andWhere(['!=', 'userstatus', 2])
				->one();
			//print_R($userModel);die;
			if (empty($userModel)) {
				return '{"status":"false","message":"User not found"}';
			} else {
				$userid = $userModel->userId;
				$resetPasswordCheck = Resetpassword::find()->where(['userId' => $userid])->one();
				//print_r($resetPasswordCheck);return  "mmm";die;
				if ($userModel->userstatus == 1 && $userModel->activationStatus == 1) {
					if (empty($resetPasswordCheck)) {
						$createdDate = time();
						$randomValue = rand(10000, 100000);
						$resetPasswordData = base64_encode($userModel->userId . "-" . $createdDate . "-" . $randomValue);
						$resetPasswordModel = new Resetpassword();
						$resetPasswordModel->userId = $userModel->userId;
						$resetPasswordModel->resetData = $resetPasswordData;
						$resetPasswordModel->createdDate = $createdDate;
						$resetPasswordModel->save(false);
					} else {
						$resetPasswordData = $resetPasswordCheck->resetData;
					}
					//print_r($resetPasswordData);die;
					if (!empty($resetPasswordData)) {
						$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
						$mailer = Yii::$app->mailer->setTransport([
							'class' => 'Swift_SmtpTransport',
							'host' => $siteSettings['smtpHost'],
							'username' => $siteSettings['smtpEmail'],
							'password' => $siteSettings['smtpPassword'],
							'port' => $siteSettings['smtpPort'],
							'encryption' => 'tls',
						]);
						$Users = new Users();
						$PRmodel = new PasswordResetRequestForm();
						try {
							if ($PRmodel->sendForgotEmail($userModel->email)) {
								return '{"status":"true","message":"Reset password link has been mailed to you"}';
							}
						} catch (\Swift_TransportException $exception) {
							return '{"status":"false","message":"Something went wrong.Try again Later"}';
						} catch (\Exception $e) {
							return '{"status":"false","message":"Something went wrong.Try again Later"}';
						}
					}

				} elseif ($userModel->userstatus == 0 && $userModel->activationStatus == 0) {
					return '{"status":"error","message":"Your account has been disabled by the Administrator"}';
				} else {
					return '{"status":"true","message":"User not verified yet, activate the account from the email"}';
				}
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		CHANGE PASSWORD PARAMS -  $api_username, $api_password, $user_id, $old_password, $new_password
	 */
	public function actionChangepassword()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				//Post Values
				$old_password = $_POST['old_password'];
				$new_password = $_POST['new_password'];
				$userModel = Users::findOne($user_id);//print_r($userModel);die;
				$oldPassword = base64_decode($userModel->password_encrypt);
				if ($old_password == $new_password) {
					return '{"status":"false","message":"Old Password and new password are same, Please enter different one!"}';
					die;
				}
				if ($oldPassword == $old_password) {
					$newPassword = base64_encode($new_password);
					$userModel->password_encrypt = $newPassword;
					$userModel->save(false);
					return '{"status":"true","message":"Password Changed Successfully"}';
					die;
				} else {
					return '{"status":"false","message":"Old Password Incorrect"}';
					die;
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
			die;
		}
	}

	public function actionGetimage()
	{
		$url = $_POST['urlimage'];
		$imageName = yii::$app->Myclass->getImagefromURL($url);
		return Yii::$app->urlManager->createAbsoluteUrl("/profile/" . $imageName);
	}

	/* 
		 GET CATEGORY PARAMS -  $api_username, $api_password, $lang_type
	
	public function actionGetcategory()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$lang_type = $_POST['lang_type'];
			yii::$app->Myclass->push_lang($lang_type);

			//Declaration
			$categories = array();
			$subcategories = array();
			$maincategoryImage = array();

			//Site Settings
			$siteSettings = Sitesettings::find()->orderBy(['id'=>SORT_DESC])->one();
			
			//Main Category
			$maincategoryModel = Categories::find()->where(['=', 'parentCategory', 0])->all();

			//Sub Category
			$subcategoryModel = Categories::find()->where(['!=', 'parentCategory', 0])->all();
			
			foreach ($subcategoryModel as $subcategory) {
				$subcategories[$subcategory->parentCategory][$subcategory->categoryId] = $subcategory->name;
			}
			
			foreach ($maincategoryModel as $catkey => $maincategory) {
				$imageUrl = Yii::$app->urlManager->createAbsoluteUrl('/backend/web/uploads/' . $maincategory->image);
				$categories['category'][$catkey]['category_id'] = $maincategory->categoryId;
				$categories['category'][$catkey]['category_name'] = Yii::t('app', $maincategory->name);
				$categories['category'][$catkey]['category_img'] = $imageUrl;
				$categories['category'][$catkey]['subcategory'] = array();
				if (isset($subcategories[$maincategory->categoryId])) {
					$relatedSubcategory = $subcategories[$maincategory->categoryId];
					$relatedkey = 0;
					foreach ($relatedSubcategory as $relatedCategorykey => $relatedCategory) {
						$categories['category'][$catkey]['subcategory'][$relatedkey]['sub_id'] = "$relatedCategorykey";
						$categories['category'][$catkey]['subcategory'][$relatedkey]['sub_name'] = Yii::t('app', $relatedCategory);
						$relatedkey++;
					}
				}
			}
			return '{"status": "true","result":' . Json::encode($categories) . '}';
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	} */

	/* 
		GET ITEMS PARAMS -  $api_username, $api_password,$type , $price = NULL, $search_key = NULL, $category_id = 0, $subcategory_id = NULL, $user_id = 0, $item_id = 0, $seller_id = NULL, $sorting_id = 0, $offset = 0,	$limit = 20, $lat = NULL, $lon = NULL, $posted_within = NULL, $distance = NULL, $distance_type, $lang_type
	 */
 
 
	public function convertJsonItems($itemModel, $userId = 0, $sellerId = 0)
	{
		$items = array();
		foreach ($itemModel as $itemkey => $item) {
			
			$productId = $item->productId;
			$likedornot = $this->checkuserlike($userId, $productId);
			//	print_r($likedornot);exit;
			$items['items'][$itemkey]['id'] = $item->productId;
			$items['items'][$itemkey]['item_title'] = $item->name;
			$items['items'][$itemkey]['item_description'] = html_entity_decode($item->description);



			$productConditionModel = Productconditions::find()->where(['id'=>$item->productCondition])->one();
		
				// $productModel->productCondition = $productConditionModel->id;

			$items['items'][$itemkey]['item_condition'] = Yii::t('app', $productConditionModel->condition);
			$items['items'][$itemkey]['item_condition_id'] = Yii::t('app', $productConditionModel->id);
			$items['items'][$itemkey]['price'] = $item->price;

			$items['items'][$itemkey]['quantity'] = $item->quantity;
			$items['items'][$itemkey]['youtube_link'] = $item->videoUrl;    

			if ($item->quantity > 0 && $item->soldItem == 0) {
				$items['items'][$itemkey]['item_status'] = "onsale";
			} else {
				$items['items'][$itemkey]['item_status'] = "sold";
			}
			$items['items'][$itemkey]['size'] = "";
			if ($item->sizeOptions != '' && $item->sizeOptions != 0) {
				$sizeOptions = Json::decode($item->sizeOptions, true);
				//print_r($sizeOptions);exit;
				$size = array();
				$sizeKey = 0;
				foreach ($sizeOptions as $sizeOption) {
					$size[$sizeKey]['name'] = $sizeOption['option'];
					$size[$sizeKey]['qty'] = $sizeOption['quantity'];
					$size[$sizeKey]['price'] = $sizeOption['price'];
					$sizeKey++;
				}
				$items['items'][$itemkey]['size'] = $size;
			}
			$items['items'][$itemkey]['seller_name'] = $item->user->name;
			$items['items'][$itemkey]['seller_username'] = $item->user->username;
			$items['items'][$itemkey]['seller_id'] = (string)$item->user->userId;
			if ($item->user->userImage == "")
				$items['items'][$itemkey]['seller_img'] = Yii::$app->urlManagerfrontEnd->baseUrl . '/media/logo/' . yii::$app->Myclass->getDefaultUser();
			else
				$items['items'][$itemkey]['seller_img'] = $userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $item->user->userImage);
			$review = Reviews::find()->select(['avg(rating) as rating'])->where(['receiverId' => $item->user->userId])->one();
			$user_review_rating = Reviews::find()->where(['receiverId' => $item->user->userId])->count();
			$items['items'][$itemkey]['seller_rating'] = $review->rating;
			$items['items'][$itemkey]['rating_user_count'] = $user_review_rating;
			$items['items'][$itemkey]['mobile_no'] = $item->user->phone;
			if ($item->user->phonevisible == "1") {
				$items['items'][$itemkey]['show_seller_mobile'] = "true";
			} else {
				$items['items'][$itemkey]['show_seller_mobile'] = "false";
			}

			if ($item->user->facebookId == '') {
				$items['items'][$itemkey]['facebook_verification'] = 'false';
			} else {
				$items['items'][$itemkey]['facebook_verification'] = 'true';
			}
			if ($item->user->mobile_status == '1') {
				$items['items'][$itemkey]['mobile_verification'] = 'true';
			} else {
				$items['items'][$itemkey]['mobile_verification'] = 'false';
			}

			$items['items'][$itemkey]['email_verification'] = 'true';

			$items['items'][$itemkey]['currency_code'] = $item->currency;

			$currency_formats = yii::$app->Myclass->getCurrencyFormats($item->currency);
			if($currency_formats[0] != "")
					$items['items'][$itemkey]['currency_mode'] = $currency_formats[0];
			if($currency_formats[1] != "")
					$items['items'][$itemkey]['currency_position'] = $currency_formats[1];
			$items['items'][$itemkey]['product_url'] = Yii::$app->urlManager->createAbsoluteUrl('products/view') .
				'/' . yii::$app->Myclass->safe_b64encode($item->productId . '-' . rand(0, 999)) . '/' . yii::$app->Myclass->productSlug($item->name);
            //Yii::$app->urlManager->createAbsoluteUrl('/products/'.$item->productId);

				if($_POST['lang_type'] == "ar")

					$items['items'][$itemkey]['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($item->currency,$item->price);
				else
					$items['items'][$itemkey]['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($item->currency,$item->price);
			$items['items'][$itemkey]['likes_count'] = $item->likes;
			$items['items'][$itemkey]['comments_count'] = $item->commentCount;
			$items['items'][$itemkey]['views_count'] = $item->views;

			$items['items'][$itemkey]['liked'] = $likedornot;

			$items['items'][$itemkey]['report'] = "no";
			if ($item->reports != '') {
				$reports = Json::decode($item->reports, true);
				if (in_array($userId, $reports)) {
					$items['items'][$itemkey]['report'] = "yes";
				}
			}
			// $items['items'][$itemkey]['posted_time'] = yii::$app->Myclass->getElapsedTime($item->createdDate)." ago";
			$items['items'][$itemkey]['posted_time'] = $item->createdDate;
			$items['items'][$itemkey]['latitude'] = $item->latitude;
			$items['items'][$itemkey]['longitude'] = $item->longitude;
			$items['items'][$itemkey]['location'] = $item->location;
			$items['items'][$itemkey]['shipping_time'] = $item->shippingTime;
			$items['items'][$itemkey]['best_offer'] = "false";
			$buyType = "";
			if ($item->chatAndBuy) {
				$buyType .= "contactme";
			}
			if ($item->exchangeToBuy) {
				$buyType .= $buyType == "" ? "swap" : ",swap";
			}
			if ($item->instantBuy) {
				$buyType .= $buyType == "" ? "sale" : ",sale";
			}
			$items['items'][$itemkey]['buy_type'] = $buyType;
			$items['items'][$itemkey]['paypal_id'] = $item->paypalid;
			//return  "<pre>";print_r($item->subCategory0);print_r($item->shippings);die;
			if (isset($item->category0)) {
				$items['items'][$itemkey]['category_id'] = $item->category0->categoryId;
				$items['items'][$itemkey]['category_name'] = Yii::t('app', $item->category0->name);
			} else {
				$items['items'][$itemkey]['category_id'] = "";
				$items['items'][$itemkey]['category_name'] = "";
			}
			if (isset($item->subCategory0)) {
				$items['items'][$itemkey]['subcat_id'] = $item->subCategory0->categoryId;
				$items['items'][$itemkey]['subcat_name'] = Yii::t('app', $item->subCategory0->name);
			} else {
				$items['items'][$itemkey]['subcat_id'] = "";
				$items['items'][$itemkey]['subcat_name'] = "";
			}

			// third level category
			if (isset($item->sub_subCategory0)) {
				$items['items'][$itemkey]['child_category_id'] = $item->sub_subCategory0->categoryId;
				$items['items'][$itemkey]['child_category_name'] = Yii::t('app', $item->sub_subCategory0->name);
			} else {
				$items['items'][$itemkey]['child_category_id'] = "";
				$items['items'][$itemkey]['child_category_name'] = "";
			}

			$items['items'][$itemkey]['promotion_type'] = 'Normal';
			if ($item->promotionType == '3') {
				$items['items'][$itemkey]['promotion_type'] = "Normal";
			} elseif ($item->promotionType == '1') {
				$items['items'][$itemkey]['promotion_type'] = "Ad";
			} elseif ($item->promotionType == '2') {
				$items['items'][$itemkey]['promotion_type'] = "Urgent";
			}

			if (isset($item->approvedStatus) && $item->approvedStatus == "1") {
				$items['items'][$itemkey]['item_approve'] = "1";
			} else {
				$items['items'][$itemkey]['item_approve'] = "0";
			}

			/*$productModels['items'][$itemkey]['exchange_buy'] = '';
			if($item->exchangeToBuy == '0'){
				$items['items'][$itemkey]['exchange_buy'] = "false";
			}else{
				$items['items'][$itemkey]['exchange_buy'] = "true";
			}

			$items['items'][$itemkey]['make_offer'] = '';
			if($item->myoffer == '1' || $item->myoffer == '2'){
				$items['items'][$itemkey]['make_offer'] = "true";
			}else{
				$items['items'][$itemkey]['make_offer'] = "false";
			}*/
			$items['items'][$itemkey]['exchange_buy'] = "$item->exchangeToBuy";
			$items['items'][$itemkey]['make_offer'] = "$item->myoffer";
			$items['items'][$itemkey]['instant_buy'] = "$item->instantBuy";

			if ($item->instantBuy == "1") {
				$items['items'][$itemkey]['country_id'] = $item->shippingcountry;
				$items['items'][$itemkey]['paypal_id'] = $item->paypalid;
				$items['items'][$itemkey]['shipping_cost'] = $item->shippingCost;
					if($_POST['lang_type'] == "ar")
							$items['items'][$itemkey]['formatted_shipping_cost'] =  yii::$app->Myclass->arabicgetFormattingCurrencyapi($item->currency,$item->shippingCost);
					else
						$items['items'][$itemkey]['formatted_shipping_cost'] =  yii::$app->Myclass->getFormattingCurrencyapi($item->currency,$item->shippingCost);
			} else {
				$items['items'][$itemkey]['country_id'] = "";
				$items['items'][$itemkey]['paypal_id'] = "";
				$items['items'][$itemkey]['shipping_cost'] = "";
			}
			$total_price = $item->price + $items['items'][$itemkey]['shipping_cost'];
			$items['items'][$itemkey]['total_price'] = $total_price;

				if($_POST['lang_type'] == "ar")
					$items['items'][$itemkey]['formatted_total_price'] =  yii::$app->Myclass->arabicgetFormattingCurrencyapi($item->currency,$total_price);
				else
					$items['items'][$itemkey]['formatted_total_price'] =  yii::$app->Myclass->getFormattingCurrencyapi($item->currency,$total_price);
		
			/*$items['items'][$itemkey]['shipping_detail'] = array();
			if($item->instantBuy){
				$shipKey = 0;
				$shippingArray = array();
				foreach($item->shippings as $shipping){
					$shippingArray[$shipKey]['country_id'] = $shipping->countryId;
					$shippingArray[$shipKey]['country_name'] = $shipping->country->country;
					$shippingArray[$shipKey]['shipping_cost'] = $shipping->shippingCost;
					$shipKey++;
				}
				$items['items'][$itemkey]['shipping_detail'] = $shippingArray;
			}*/
			$items['items'][$itemkey]['photos'] = array();
			foreach ($item->photos as $photo) {
				$photoName = $photo->name;

				// $photodetails['item_url_main_350'] = Yii::$app->urlManager->createAbsoluteUrl("/media/item/resized/" . $productId . '/' . $photoName);
				// $photodetails['item_url_main_350'] = Yii::$app->urlManager->createAbsoluteUrl('media/item/' . $productId . '/' . $photoName);

				$image = Yii::$app->urlManager->createAbsoluteUrl("media/item/" . $productId . '/' . $photoName);
				$photodetails['item_url_main_350'] = Url::base(true).'/resized.php?src='.$image.'&w=300&h=300'; 
				$photodetails['height'] = '350';
				$photodetails['width'] = '350';
				$photodetails['item_url_main_original'] = Yii::$app->urlManager->createAbsoluteUrl('media/item/' . $productId . '/' . $photoName);
				$items['items'][$itemkey]['photos'][] = $photodetails;
			}

			// Filters - AK
			$advFilter = array(); 

			$criteria = new Query;
			$criteria->select(['hts_productfilters.id', 'u.filter_id AS super_id', 'u.name AS super_name', 'v.id AS parent_id', 'v.name AS parent_name', 'w.id AS child_id', 'w.name AS child_name', 'hts_productfilters.filter_type', 'hts_productfilters.filter_values', 'hts_filter.value']);    
			$criteria->from('hts_productfilters'); 
			$subQuery =(new Query())->select('*')->from('hts_filtervalues');
			$criteria->leftJoin('hts_filter', 'hts_filter.id = hts_productfilters.level_one'); 
			$criteria->leftJoin(['u' => $subQuery], 'u.filter_id = hts_productfilters.level_one');	
			$criteria->leftJoin(['v' => $subQuery], 'v.id = hts_productfilters.level_two');	
			$criteria->leftJoin(['w' => $subQuery], 'w.id = hts_productfilters.level_three');
			$advFilter[] = "and"; 
			$advFilter[] =  ['or',
									['and',
										['=','hts_productfilters.filter_type','dropdown'],
										['=','hts_productfilters.level_three',0],	
								 	],
								 	['and',
										['=','hts_productfilters.filter_type','multilevel'],
										['>','hts_productfilters.level_one',0],	
										['>','hts_productfilters.level_two',0],	
										['>','hts_productfilters.level_three',0], 
								 	], 
								 	['and',
										['=','hts_productfilters.filter_type','range'],
										['=','hts_productfilters.level_three',0],
								 	] 
								 ];
			$criteria->andWhere(['=', 'hts_productfilters.product_id', $productId]);  
			$criteria->andWhere(['=', 'u.parentid', 0]);  
			$criteria->andWhere($advFilter); 
			$criteria->groupBy('hts_productfilters.id');  
			$filterResult = $criteria->createCommand()->queryAll();  
			$filterDetails = array(); 

			if(count($filterResult) > 0) {
				foreach ($filterResult as $fKey => $valueData) {
					$filterDetails[$fKey]['type'] = $valueData['filter_type'];

					if($valueData['filter_type'] == "dropdown") {
						$filterDetails[$fKey]['parent_id'] = $valueData['super_id'];
						$filterDetails[$fKey]['parent_label'] = Yii::t('app',$valueData['super_name']);
						$filterDetails[$fKey]['child_id'] = $valueData['parent_id'];
						$filterDetails[$fKey]['child_label'] = Yii::t('app',$valueData['parent_name']);
					} elseif($valueData['filter_type'] == "multilevel") {
						$filterDetails[$fKey]['parent_id'] = $valueData['super_id'];
						$filterDetails[$fKey]['parent_label'] = Yii::t('app',$valueData['super_name']);
						$filterDetails[$fKey]['subparent_id'] = $valueData['parent_id'];
						$filterDetails[$fKey]['subparent_label'] = Yii::t('app',$valueData['parent_name']);
						$filterDetails[$fKey]['child_id'] = $valueData['child_id'];
						$filterDetails[$fKey]['child_label'] = Yii::t('app',$valueData['child_name']);
					} elseif($valueData['filter_type'] == "range") {
						$filterDetails[$fKey]['parent_id'] = $valueData['super_id'];
						$filterDetails[$fKey]['parent_label'] = Yii::t('app',$valueData['super_name']);
						$filterDetails[$fKey]['value'] = $valueData['filter_values'];
						$rangeValues = explode(';', $valueData['value']);

						$filterDetails[$fKey]['min_value'] = (isset($rangeValues[0])) ? trim($rangeValues[0]) : 'NULL';
						$filterDetails[$fKey]['max_value'] = (isset($rangeValues[1])) ? trim($rangeValues[1]) : 'NULL';     
					} 
				}
			} 
			$items['items'][$itemkey]['filters'] = $filterDetails;
		}
		return Json::encode($items); 
	} 


	public function checkuserlike($userId, $itemId)
	{
		if ($userId == '0') {
			return "no";
		} else {
			$favouriteModel = Favorites::find()->where(['userId' => $userId])
				->andWhere(['productId' => $itemId])->one();
			if (empty($favouriteModel)) {
				return "no";
			} else {
				return "yes";
			}
		}
	}

	/* 
		UPDATE VIEW PARAMS -  $api_username, $api_password, $item_id
	 */
	public function actionUpdateview()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		if ($this->authenticateAPI($api_username, $api_password)) {
			if(isset($_POST['item_id']))
			{
				$id = $_POST['item_id'];
			}

			if(isset($_POST['user_id']))
			{
				$user = $_POST['user_id'];
			}

			$itemModel = Products::findOne($id);
			if(isset($user) && $itemModel->userId != $user)
			{
				//Get insights by visitors.
				$insight_exists = json_decode($itemModel->insightUsers,true);
				$insightUsers[] = (int)$user;

				if(!empty($insight_exists))
				{
					if(!in_array($user,$insight_exists)) {
							
						$real_insight = array_merge($insightUsers,$insight_exists);
						//$insightdata  = array_slice($real_insight,0,5);
						$insightdetl  = json_encode($real_insight);
	
						$itemModel->views++;
						$itemModel->insightUsers = $insightdetl;
						$itemModel->save(false);	
					}else{
						$itemModel->views++;
						$itemModel->save(false);
					}
				}else{	
					$itemModel->views++;
					$itemModel->insightUsers = json_encode($insightUsers);
					$itemModel->save(false);
				} 
				$visitorDetails = yii::$app->Myclass->getUserDetailss($user);
				$userViewmodel = new Userviews;
				$userViewmodel->product_id = $id;
				$userViewmodel->user_id = $user;
				$userViewmodel->seller_id = $itemModel->userId;
				$userViewmodel->city = ($visitorDetails->city == '' || $visitorDetails->city == null) ? '': $visitorDetails->city;
				$userViewmodel->created_at = date('Y-m-d'); 
				$userViewmodel->cdate = gmdate("Y-m-d\TH:i:s\Z");
				$userViewmodel->save(false);	
			}
			return '{"status":"true","result":"Successfully added views"}';
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}


	/* 
		GET COMMENTS PARAMS -  $api_username, $api_password, $item_id
	 */
	public function actionGetcomments()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$productId = $_POST['item_id'];
			$commentModel = Comments::find()->where(['productId' => $productId])->all();
			if (count($commentModel) > 0) {
				$comments = array();
				foreach ($commentModel as $commentKey => $comment) {
					$comments['comments'][$commentKey]["comment_id"] = $comment->commentId;
					$comments['comments'][$commentKey]["comment"] = $comment->comment;
					$comments['comments'][$commentKey]["user_id"] = $comment->user->userId;
					$comments['comments'][$commentKey]["user_full_name"] = $comment->user->name;
					if ($comment->user->userImage == "")
						$comments['comments'][$commentKey]["user_img"] = Yii::$app->urlManager->createAbsoluteUrl('profile/' . yii::$app->Myclass->getDefaultUser());
					else
						$comments['comments'][$commentKey]["user_img"] = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $comment->user->userImage);
					$comments['comments'][$commentKey]["user_name"] = $comment->user->username;
					$comments['comments'][$commentKey]["comment_time"] = $comment->createdDate;
				}
				return '{"status": "true","result":' . Json::encode($comments) . '}';
			} else {
				return '{"status":"false","message":"No comment found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET COUNTRY CURRENCY PARAMS -  $api_username, $api_password
	 */
	public function actionGetcountrycurrency()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$countryModel = Country::find()->all();
			$currencyModel = Currencies::find()->all();;
			$result = array();

			foreach ($currencyModel as $currencykey => $currency) {
				$result['currency'][$currencykey]['id'] = $currency->id;
				$result['currency'][$currencykey]['symbol'] = $currency->currency_shortcode .
					"-" . $currency->currency_symbol;
			}

			foreach ($countryModel as $countrykey => $country) {
				$result['country'][$countrykey]['country_id'] = $country->countryId;
				$result['country'][$countrykey]['country_name'] = $country->country;
			}
			return '{"status": "true","result":' . Json::encode($result) . '}';
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		POST COMMENT PARAMS -  $api_username, $api_password, $comment, $user_id, $item_id
	 */
	public function actionPostcomment()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				$comment = $_POST['comment'];

				if (isset($comment) && $comment != '') {
					$item_id = $_POST['item_id'];

					$newComment = new Comments();
					$newComment->userId = $user_id;
					$newComment->productId = $item_id;
					$newComment->comment = $comment;
					$newComment->createdDate = time();
					$newComment->save();


					$productModel = Products::findOne($item_id);
					$userModel = Users::findOne($user_id);
					$productModel->commentCount = $productModel->commentCount + 1;
					$productModel->save(false);
					if ($user_id != $productModel->userId) {
						$notifyMessage = 'comment on your product';
						yii::$app->Myclass->addLogs("comment", $user_id, $productModel->userId, $newComment->commentId, $productModel->productId, $notifyMessage);
					}

					if (!empty($userModel->userImage)) {
						$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userModel->userImage);
					} else {
						$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . yii::$app->Myclass->getDefaultUser());
					}

					$userid = $productModel->userId;
						// $criteria = new CDbCriteria;
						// $criteria->addCondition('user_id = "'.$userid.'"');
					$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();

					$userModel = Users::findOne($user_id);
					if (count($userdevicedet) > 0) {
						foreach ($userdevicedet as $userdevice) {
							$deviceToken = $userdevice->deviceToken;
							$lang = $userdevice->lang_type;
							$badge = $userdevice->badge;
							$badge += 1;
							$userdevice->badge = $badge;
							$userdevice->deviceToken = $deviceToken;
							$userdevice->save(false);
							if (isset($deviceToken)) {
								yii::$app->Myclass->push_lang($lang);

								$text = 'comment on your product';
								$msg = Yii::t('app', $text);

								$messages = $userModel->username . " " . $msg . " " . $productModel->name;
								if ($user_id != $productModel->userId) {
									yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
								}
							}
						}
					}

					return '{"status":"true","comment_id":"' . $newComment->commentId . '","comment":"' . $newComment->comment . '", "user_id":"' . $userModel->userId . '", "user_img":"' . $userImage . '", "user_name":"' . $userModel->username . '", "comment_time": "' . $newComment->createdDate . '"}';
				} else {
					return '{"status":"false","message":"Comment Empty"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	public function actionUploadimage()
	{
		$type = '';
		if (isset($_POST['type'])) {
			$type = $_POST['type'];
		}
		@$ftmp = '';
		if (isset($_FILES['images']['tmp_name'])) {
			@$ftmp = $_FILES['images']['tmp_name'];
		}
		@$oname = '';
		if (isset($_FILES['images']['name'])) {
			@$oname = $_FILES['images']['name'];
		}
		@$fname = '';
		if (isset($_FILES['images']['name'])) {
			@$fname = $_FILES['images']['name'];
		}
		@$fsize = '';
		if (isset($_FILES['images']['size'])) {
			@$fsize = $_FILES['images']['size'];
		}
		@$ftype = '';
		if (isset($_FILES['images']['type'])) {
			@$ftype = $_FILES['images']['type'];
		}

		if ($type == 'item') {
			$path1 = realpath(Yii::$app->basePath . '/../');
			$user_image_path = realpath($path1 . '/frontend/web/media/item/tmp') . '/';
			$user_image_path1 = "media/item/tmp/";
		} else if ($type == 'chat') {
			$path1 = realpath(Yii::$app->basePath . '/../');
			$user_image_path = realpath($path1 . '/frontend/web/images/message') . '/';
			$user_image_path1 = "images/message/";
		} else if ($type == 'app') {
			$path1 = realpath(Yii::$app->basePath . '/../');
			$user_image_path = realpath($path1 . '/frontend/web/media/banners') . '/';
			$user_image_path1 = "media/banners/tmp/";
		} else if ($type == 'web') {
			$path1 = realpath(Yii::$app->basePath . '/../');
			$user_image_path = realpath($path1 . '/frontend/web/media/banners') . '/';
			$user_image_path1 = "media/banners/tmp/";
		} else {
			$path1 = realpath(Yii::$app->basePath . '/../');
			$user_image_path = realpath($path1 . '/frontend/web/profile') . '/';
			$user_image_path1 = "profile/";
				
				//$user_image_path = Yii::$app->urlManagerfrontEnd->baseUrl.'/profile/';
				//print_r($user_image_path);exit;
		}

		$ext = strrchr($oname, '.');
		if ($ext) {
			if (($ext != '.JPG' && $ext != '.PNG' && $ext != '.JPEG' && $ext != '.GIF' && $ext != '.jpg' && $ext != '.png' && $ext != '.jpeg' && $ext != '.gif') || $fsize > 200 * 1024 * 1024) {
				//return  'error';
			} else {
				if (isset($ftmp)) {
					$newname = rand(10, 100) . time() . $ext;
					$newimage = $user_image_path . $newname;
					$newimage1 = $user_image_path1 . $newname;

					$result = move_uploaded_file($ftmp, $newimage);
					chmod($newimage, 0777);
					if (empty($result)) {
						$error["result"] = "There was an error moving the uploaded file.";
						return '{"status":"false","message":"Image cannot be uploaded"}';
					} else if($type == 'app') {
						return '{"status":"true",
							"banner":{
								"Message":"Image Upload Successfully",
								"type":"app",
								"app_banner_name" :"' . $newname . '",
								"app_banner_url" :"' . Yii::$app->urlManager->createAbsoluteUrl($newimage1) . '"
							}
						}';
					}  else if($type == 'web') {
						return '{"status":"true",
							"banner":{
								"Message":"Image Upload Successfully",
								"type":"web",
								"web_banner_name" :"' . $newname . '",
								"web_banner_url" :"' . Yii::$app->urlManager->createAbsoluteUrl($newimage1) . '"
							}
						}';
					} else {
						return '{"status":"true",
							"Image":{
								"Message":"Image Upload Successfully",
								"Name" :"' . $newname . '",
								"View_url" :"' . Yii::$app->urlManager->createAbsoluteUrl($newimage1) . '"
							}
						}';
					}
				}
			}
		}

	}

	/* 
		REMOVE IMAGE PARAMS -  $api_username, $api_password, $type, $name
	 */
	public function actionRemoveimage()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$type = $_POST['type'];
			$name = $_POST['name'];
			if ($type == 'item') {
				$user_image_path = "media/item/tmp/";
				//Photos::model()->deleteAllByAttributes(array('productId'=>$_POST['item_id'],
				//		'name'=>$_POST['name']));
			} else {
				$user_image_path = "profile/";
			}
			$user_image_path .= $name;
			if (unlink($user_image_path)) {
				return '{"status":"true", "message":"Image deleted successfully"}';
			} else {
				return '{"status":"false","message":"Image cannot be deleted"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		ADD PRODUCT PARAMS -  $api_username, $api_password, $user_id, $item_id = 0,
		 $item_name, $item_des, $price, $size, $category, $subcategory, $sub_subcategory, 
		 $chat_to_buy, $exchange_to_buy, $currency, $lat, $lon, $address, 
		 $shipping_time, $remove_img = Null, $product_img, $shipping_detail,
		  $item_condition = NULL, $make_offer,$instant_buy=NULL,$paypal_id=NULL,
		  $shipping_cost=NULL,$country_id=NULL
	 */
	public function actionAddproduct()
	{

		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				$item_id = '';
				if (isset($_POST['item_id'])) {
					$item_id = $_POST['item_id'];
				}
				$item_name = '';
				if (isset($_POST['item_name'])) {
					$item_name = $_POST['item_name'];
				}
				$item_des = '';
				if (isset($_POST['item_des'])) {
					$item_des = $_POST['item_des'];
				}
				$price = '0';
				if (isset($_POST['price'])) {
					$price = $_POST['price'];
				}
				$size = '0';
				if (isset($_POST['size'])) {
					$size = $_POST['size'];
				}
				$category = '';
				if (isset($_POST['category'])) {
					$category = $_POST['category'];
				}
				$subcategory = '';
				if (isset($_POST['subcategory'])) {
					$subcategory = $_POST['subcategory'];
				}
				$sub_subcategory = '';
				if (isset($_POST['child_category'])) {
					$sub_subcategory = $_POST['child_category'];
				}
				$chat_to_buy = '0';
				if (isset($_POST['chat_to_buy'])) {
					$chat_to_buy = $_POST['chat_to_buy'];
				}
				$exchange_to_buy = '0';
				if (isset($_POST['exchange_to_buy'])) {
					$exchange_to_buy = $_POST['exchange_to_buy'];
				}
				$currency = '';
				if (isset($_POST['currency'])) {
					$currency = $_POST['currency'];
				}
				$lat = '';
				if (isset($_POST['lat'])) {
					$lat = $_POST['lat'];
				}
				$lon = '';
				if (isset($_POST['lon'])) {
					$lon = $_POST['lon'];
				}
				$address = '';
				if (isset($_POST['address'])) {
					$address = $_POST['address'];
				}
				$shipping_time = '';
				if (isset($_POST['shipping_time'])) {
					$shipping_time = $_POST['shipping_time'];
				}
				$remove_img = '';
				if (isset($_POST['remove_img'])) {
					$remove_img = $_POST['remove_img'];
				}
				$product_img = '';
				if (isset($_POST['product_img'])) {
					$product_img = $_POST['product_img'];
				}
				$shipping_detail = '';
				if (isset($_POST['shipping_detail'])) {
					$shipping_detail = $_POST['shipping_detail'];
				}
				$item_condition = '0';
				if (isset($_POST['item_condition'])) {
					$item_condition = $_POST['item_condition'];
				}
				$make_offer = '0';
				if (isset($_POST['make_offer'])) {
					$make_offer = $_POST['make_offer'];
				}
				$instant_buy = '0';
				if (isset($_POST['instant_buy'])) {
					$instant_buy = $_POST['instant_buy'];
				}
				$paypal_id = '';
				if (isset($_POST['paypal_id'])) {
					$paypal_id = $_POST['paypal_id'];
				}
				$shipping_cost = '0';
				if (isset($_POST['shipping_cost'])) {
					$shipping_cost = $_POST['shipping_cost'];
				}

				$youtube_link = (isset($_POST['youtube_link'])) ? trim($_POST['youtube_link']) : '';

				$country_id = '';
				if (isset($_POST['country_id'])) {
					$country_id = $_POST['country_id'];
				}
				$giving_away = '';
				if (isset($_POST['giving_away'])) {
					$giving_away = $_POST['giving_away'];
				}

				// FILTERS
				$filtersArray = array();
				if (isset($_POST['filters'])) {
					$filtersArray = Json::decode(trim($_POST['filters']), true);
				} 
				// FILTERS END

				if ($item_id != 0) {
					$productModel = Products::findOne($item_id);
					Productfilters::deleteAll(['product_id' => $item_id]);  
				} else { 
					$productModel = new Products();
				}



				$productModel->userId = $user_id;
				$productModel->name = $item_name;
					//$productModel->description = htmlentities($item_des);
				$string = '';
				if (isset($_POST['item_des'])) {
					$string = trim($_POST['item_des']);
				}

				if ($string != strip_tags($string)) {
						//return  "HTML";
					$productModel->description = htmlentities($string);
				} else {
					$productModel->description = $string;
				}

				if ($giving_away == "yes") {
					$productModel->price = '0';
				} else {
					$productModel->price = $price;
				}

				if (empty($quantity)) {
					$quantity = 1;
				}
				$quantity = 1;
				$productModel->quantity = $quantity;
				$productModel->sizeOptions = $size;


				// $productConditionModel = Productconditions::find()->where(['condition'=>$item_condition])->one();
		
				$productModel->productCondition = $item_condition;

				// $productModel->productCondition = $item_condition;
				$productModel->category = $category;
				$productModel->subCategory = $subcategory;
				$productModel->sub_subCategory = $sub_subcategory;
				$productModel->createdDate = time();
				$productModel->chatAndBuy = $chat_to_buy;
				$productModel->exchangeToBuy = $exchange_to_buy;
				$productModel->instantBuy = $instant_buy;
				$productModel->myoffer = $make_offer;
				$productModel->paypalid = $paypal_id;
				$productModel->currency = $currency;
				$productModel->latitude = $lat;
				$productModel->longitude = $lon;
				$productModel->location = $address;
				$productModel->shippingTime = $shipping_time;
				$productModel->videoUrl = $youtube_link; 

				if ($instant_buy == "1") {
					$productModel->shippingCost = $shipping_cost;
					$productModel->shippingcountry = $country_id;
				}

				$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				if ($siteSettings->product_autoapprove == 1) {
					$productModel->approvedStatus = 1;
					$productModel->Initial_approve = 1;
				} else {
					$productModel->approvedStatus = 0;
					$productModel->Initial_approve = 0;

				}

				$productModel->save(false);

				if ($item_id != 0) {
					//Photos::model()->deleteAllByAttributes(array('productId'=>$_POST['item_id']));
					if (!empty($remove_img) && $remove_img != '') {
						$imagesToRemove = explode(',', $remove_img);
						$remove_image_path = "media/item/{$item_id}/";
						foreach ($imagesToRemove as $images) {
							Photos::deleteAll([
								'productId' => $item_id,
								'name' => $images
							]);
							$imagePath = $remove_image_path . $images;
							unlink($imagePath);
						}
					}
				}
				$photos = explode(',', $product_img);

				$path = Yii::$app->getBasePath() . "/web/media/item/{$productModel->productId}/";
				if (!is_dir($path)) {
					mkdir($path);
					chmod($path, 0777);
				}
			    $resizedpath = Yii::$app->getBasePath() . "/web/media/item/resized/{$productModel->productId}/";
				if (!is_dir($resizedpath)) {
					mkdir($resizedpath);
					chmod($resizedpath, 0777);
				}
				foreach ($photos as $photo) {
					$path1 = realpath(Yii::$app->basePath . '/../');
						//$path = realpath($path1.'/frontend/web/media/item/tmp').'/';
					$imagepath = realpath($path1 . '/frontend/web/media/item/tmp') . '/' . $photo;
					if (is_file($imagepath)) {

								
						if (rename($imagepath, $path . $photo)) {

		


							
						//	chmod($path.$photo, 0777);
								$watermark = yii::$app->Myclass->getWatermark();

								$watermarkImage = Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark);
								$image = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$productModel->productId.'/'.$photo);



								$resizeimagineObj = Image::getImagine();

								$resizeimageObj = $resizeimagineObj->open($image);

								$resizeimageObj->resize(new Box(350, 350))->save(Yii::getAlias('@webroot/media/item/resized/'.$productModel->productId.'/'.$photo, ['quality' => 60]));

								
								list($widthh,$heightt) = getimagesize($image);
								$imagine = Image::getImagine();
								$imagine = $imagine->open(Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark));
								$sizes = getimagesize(Yii::$app->urlManager->createAbsoluteUrl("/media/logo/".$watermark)); 
								$width = ($widthh*30/100);
								$height = round($sizes[1]*$width/$sizes[0]);
								$imagine = $imagine->resize(new Box($width, $height))->save(Yii::getAlias('@webroot/media/item/'.$productModel->productId.'/watermark.png', ['quality' => 60]));
								
								$watermarkfile=Yii::getAlias('@webroot/media/item/'.$productModel->productId.'/watermark.png');

							list($watermark_width,$watermark_height) = getimagesize($watermarkfile);
							$size = getimagesize($image);
							$dest_x = $size[0] - $watermark_width - 15;
							$dest_y = $size[1] - $watermark_height - ($heightt*10/100);
				
							$position = array($dest_x,$dest_y);
							$newImage = Image::watermark($image, $watermarkfile, $position);
							$newImage->save(Yii::getAlias('@webroot/media/item/'.$productModel->productId.'/'.$photo, ['quality' => 60]));


							// $resizedimage = Yii::$app->urlManager->createAbsoluteUrl("/media/item/resized/".$productModel->productId.'/'.$photo);
							// $resized_size = getimagesize($resizedimage);
							// list($resized_widthh,$resized_heightt) = getimagesize($resizedimage);
							// $resized_dest_x = $resized_size[0] - $watermark_width - 15;
							// $resized_dest_y = $resized_size[1] - $watermark_height - ($resized_heightt*10/100);
							// $resized_position = array($resized_dest_x,$resized_dest_y);
								

							// $resized_newImage = Image::watermark($resizedimage, $watermarkfile, $resized_position);
							// $resized_newImage->save(Yii::getAlias('@webroot/media/item/resized/'.$productModel->productId.'/'.$photo, ['quality' => 60]));


							unlink($watermarkfile);

							//chmod( $path.$filenames[$i], 0777 );
							$img = new Photos();
							$img->name = $photo;
							$img->productId = $productModel->productId;
							$img->createdDate = time();
							if (!$img->save(false)) {
							}
						}




					}






				}


				// ADD FILTERS TO THE PRODUCT - AK $productModel->productId
				if(count($filtersArray) > 0) {
					foreach ($filtersArray as $key => $eachFilter) {
						$productFilters = new Productfilters();
						$productFilters->product_id = $productModel->productId;
						$productFilters->category_id = $category;
						$productFilters->subcategory_id = (empty($subcategory)) ? 0 :$subcategory;
						$productFilters->filter_type = trim($eachFilter['type']);
						$productFilters->filtervalue_id = 0;
						$filterModel = "";

						if(!empty($eachFilter['parent_id']) && $eachFilter['parent_id']!="0") {
							$parentFilterId = $eachFilter['parent_id'];
							$productFilters->filter_id = $parentFilterId;
							$productFilters->level_one = $parentFilterId;

							$filterModel = Filter::find()->where(['id' => $parentFilterId])->one();
						}
						if(trim($eachFilter['type']) == "dropdown") {
							$productFilters->level_two = $eachFilter['child_id'];
							$productFilters->level_three = 0;
							$productFilters->filter_values = 0;
						} elseif (trim($eachFilter['type']) == "range") {
							$productFilters->level_two = $eachFilter['value'];
							$productFilters->level_three = 0;
							$productFilters->filter_values = $eachFilter['value'];
						} elseif (trim($eachFilter['type']) == "multilevel") {
							$productFilters->level_two = $eachFilter['subparent_id'];
							$productFilters->level_three = $eachFilter['child_id']; 
							$productFilters->filter_values = 0; 
						}

						if(count($filterModel) > 0) {
							$productFilters->filter_name = $filterModel->name; 
							$productFilters->save(false);  
						} 
					}
				}

				// FILTERS END - AK

				if ($instant_buy == 1) {
					/*if(isset($item_id)){
						Shipping::model()->deleteAllByAttributes(array('productId'=>$item_id));
					}
					if(isset($shipping_detail)){
						$shippingDetails = Json::decode($shipping_detail, true);
						foreach ($shippingDetails as $productShipping){
							$shippingModel = new Shipping();
							$shippingModel->productId = $productModel->productId;
							$shippingModel->countryId = $productShipping['country_id'];
							$shippingModel->shippingCost = $productShipping['shipping_cost'];
							$shippingModel->createdDate = time();
							$shippingModel->save(false);
						}
					}else{
						if(isset($_POST['everywhere_cost']) && $_POST['everywhere_cost'] != 0){
							$shippingModel = new Shipping();
							$shippingModel->productId = $productModel->productId;
							$shippingModel->countryId = 0;
							$shippingModel->shippingCost = $_POST['everywhere_cost'];
							$shippingModel->createdDate = time();
							$shippingModel->save();
						}
						if(isset($_POST['shipping_cost']) && $_POST['shipping_cost'] != 0){
							$shippingModel = new Shipping();
							$shippingModel->productId = $productModel->productId;
							$shippingModel->countryId = $_POST['country_id'];
							$shippingModel->shippingCost = $_POST['shipping_cost'];
							$shippingModel->createdDate = time();
							$shippingModel->save();
						}
					}*/
				}
				$productLink = Yii::$app->urlManager->createAbsoluteUrl('products/view') . '/' . yii::$app->Myclass->safe_b64encode($productModel->productId . '-' . rand(0, 999)) . '/' . yii::$app->Myclass->productSlug($productModel->name);

				$promotion_type = 'Normal';
				if ($productModel->promotionType == "2") {
					$promotion_type = 'Urgent';
				} elseif ($productModel->promotionType == "1") {
					$promotion_type = 'Ad';
				}
				if ($siteSettings->product_autoapprove == 1) {
					$notifyMessage = 'added a product';
					$notifyvar = 0;
					yii::$app->Myclass->addLogs("add", $productModel->userId, $notifyvar, $productModel->productId, $productModel->productId, $notifyMessage);
					//return "add ".$productModel->userId." 0 ".$productModel->productId." ".$productModel->productId." ".$notifyMessage;
					$userid = $productModel->userId;
					$userdata = Users::findOne($userid);
					$currentusername = $userdata->name;
					// $followCriteria = new CDbCriteria;
					// $followCriteria->addCondition("follow_userId = $userid");
					$followers = Followers::find()->where(['follow_userId' => $userid])->all();
					foreach ($followers as $follower) {
						$followuserid = $follower->userId;
						// $criteria = new CDbCriteria;
						// $criteria->addCondition('user_id = "'.$followuserid.'"');
						$userdevicedet = Userdevices::find()->where(['user_id' => $followuserid])->all();
						if (count($userdevicedet) > 0) {
							foreach ($userdevicedet as $userdevice) {
								$deviceToken = $userdevice->deviceToken;
								$lang = $userdevice->lang_type;
								$badge = $userdevice->badge;
								$badge += 1;
								$userdevice->badge = $badge;
								$userdevice->deviceToken = $deviceToken;
								$userdevice->save(false);
								if (isset($deviceToken)) {
									$msg = yii::$app->Myclass->push_lang($lang);

									$text = 'added a product';
									$msg = Yii::t('app', $text);

									$messages = $currentusername . ' ' . $msg . ' ' . $productModel->name;
									yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
								}
							}
						}
					}
				}
				if ($siteSettings->product_autoapprove == 1) {
					return '{"status":"true", "message":"Product Posted Successfully", "product_url":"' . $productLink . '","item_id":"' . $productModel->productId . '","promotion_type":"' . $promotion_type . '"}';
				} else {
					return '{"status":"true", "message":"Product waiting for admin approval", "product_url":"' . $productLink . '","item_id":"' . $productModel->productId . '","promotion_type":"' . $promotion_type . '"}';
				}
				/*return '{"status":"true", "message":"Product Posted Successfully", "product_url":"'.$productLink.'","item_id":"'.$productModel->productId.'","promotion_type":"'.$promotion_type.'"}';*/
			} else {
				$errors = $this->errorMessage;
				return '{"status":"false","message":"Sorry, Your Product is not posted try after sometime", "Reason":"' . $errors . '"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}
	
	/* 
		MESSAGES PARAMS -  $api_username, $api_password, $user_id, $offset = 0, $limit = 20
	 */
	public function actionMessages()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		if ($this->authenticateAPI($api_username, $api_password)) {

			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {

				$userId = $user_id;

				$limit = 20;
				if (isset($_POST['limit']) && !empty($_POST['limit']))
					$limit = $_POST['limit'];

				$offset = 0;
				if (isset($_POST['offset']) && !empty($_POST['offset']))
					$offset = $_POST['offset'];

				//return  $limit.$offset;die;
					// $criteria = new CDbCriteria;
					// $criteria->limit = $limit;
					// $criteria->offset = $offset;
					// $criteria->condition = "user1 = '$userId' OR user2 = '$userId'";// order by lastContacted DESC
					// $criteria->order = 'lastContacted DESC';
				$chatedUsers = Chats::find()->orWhere(['user1' => $userId])
					->orWhere(['user2' => $userId])
					->limit($limit)
					->offset($offset)
					->orderBy(['lastContacted' => SORT_DESC])
					->all();

				if (!empty($chatedUsers)) {
					$chatUserList = array();
					$chatKey = 0;
					foreach ($chatedUsers as $chatKey => $chatedUser) {

						if ($chatedUser->user1 != $user_id) {
							$chatedUserId = $chatedUser->user1;
						} else {
							$chatedUserId = $chatedUser->user2;
						}

						$userDetails = yii::$app->Myclass->getUserDetailss($chatedUserId);
						//print_r($userDetails);
						$chatUserList[$chatKey]['message_id'] = $chatedUser->chatId;
						if (!empty($userDetails->userImage)) {
							$chatUserList[$chatKey]['user_image'] = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userDetails->userImage);
						} else {
							$chatUserList[$chatKey]['user_image'] = Yii::$app->urlManagerfrontEnd->baseUrl . '/media/logo/' . yii::$app->Myclass->getDefaultUser();
						}

							// $ctr = new CDbCriteria;
							// $ctr->limit = 1;
							// $ctr->condition = "chatId = '$chatedUser->chatId' AND messageType !='exchange'";// order by messageId DESC
							// $ctr->order = 'messageId DESC';
						$msgDats = Messages::find()->where(['chatId' => $chatedUser->chatId])
							->andWhere(['!=', 'messageType', 'exchange'])
							->orderBy(['messageId' => SORT_DESC])->limit(1)->one();

						if (!empty($msgDats)) {

							$msgContentType = $msgDats['messageContent'];
							$messageType = $msgDats->messageType;
							if ($msgContentType == 2) {
								$typ = "image";
							} elseif ($msgContentType == 3) {
								$typ = "location";
							} elseif ($msgContentType == 1) {
								$typ = "normal";
							} else {
								$typ = null;
							}

						} else {
							$typ = "";
							$messageType = "";
							$msgContentType = "";
						}


						$chatUserList[$chatKey]['user_name'] = $userDetails->username;
						$chatUserList[$chatKey]['full_name'] = $userDetails->name;
						$chatUserList[$chatKey]['user_id'] = $chatedUserId;
						$chatUserList[$chatKey]['message'] = urldecode($chatedUser->lastMessage);
							//if($messageType!='exchange'){
						$chatUserList[$chatKey]['last_repliedto'] = "$chatedUser->lastToRead";
							//}
						$chatUserList[$chatKey]['message_time'] = $chatedUser->lastContacted;
						$chatUserList[$chatKey]['type'] = $typ;
						$chatUserList[$chatKey]['messageType'] = $messageType;// $msgDats->messageType
						$chatUserList[$chatKey]['chatId'] = $chatedUser->chatId;

						$chatKey++;
					}

					return '{"status": "true","result": ' . Json::encode($chatUserList) . '}';
				} else {
					return '{"status":"false","message":"No Message found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		LOGIN PARAMS -  $api_username, $api_password, $user_id, $type
	 */
	public function actionMyexchanges()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$userId = $_POST['user_id'];
			if ($this->checking($userId)) {
				$type = $_POST['type'];
				$criteria = Exchanges::find();
				if ($type == 'incoming') {
					$criteria->andWhere(['status' => 0]);
					$criteria->orWhere(['status' => 1]);
					$criteria->andWhere(["requestTo" => $userId]);
				}
				if ($type == 'outgoing') {
					$criteria->andWhere(["status" => 0]);
					$criteria->orWhere(['status' => 1]);
					$criteria->andWhere(["requestFrom" => $userId]);
				}
				if ($type == 'success') {
					$criteria->andWhere(["requestTo" => $userId]);
					$criteria->orWhere(["requestFrom" => $userId]);
					$criteria->andWhere(["status" => self::SUCCESS]);
				}
				if ($type == 'failed') {
					$criteria->andWhere(["status" => 3]);
					$criteria->orWhere(["status" => 2]);
					$criteria->orWhere(["status" => 5]);
					$criteria->orWhere(["status" => 6]);
					$criteria->andWhere([
						'or',
						['requestTo' => $userId],
						['requestFrom' => $userId]
					]);
				}
				$criteria->orderBy(['date' => SORT_DESC]);
				$exchanges = $criteria->all();

					//print_r($exchanges);exit;

				$result = array();

				if (!empty($exchanges)) {
					foreach ($exchanges as $key => $exchange) :
						$result["exchange"][$key]["type"] = $type;
					$result["exchange"][$key]["exchange_id"] = $exchange->id;
					if ($exchange->status == self::PENDING)
						$status = 'Pending';
					elseif ($exchange->status == self::ACCEPT)
						$status = 'Accepted';
					elseif ($exchange->status == self::DECLINE)
						$status = 'Declined';
					elseif ($exchange->status == self::CANCEL)
						$status = 'Cancelled';
					elseif ($exchange->status == self::SUCCESS)
						$status = 'Success';
					elseif ($exchange->status == self::FAILED)
						$status = 'Failed';
					elseif ($exchange->status == self::SOLDOUT)
						$status = 'Sold Out';
					$result["exchange"][$key]["status"] = $status;

					if ($exchange->requestFrom == $userId) {
						$result["exchange"][$key]["request_by_me"] = 'true';
						$result["exchange"][$key]["exchange_time"] = date("d-m-Y", $exchange->date);
						$exchangerDetails = yii::$app->Myclass->getUserDetailss($exchange->requestTo);
						$result["exchange"][$key]["exchanger_name"] = $exchangerDetails->name;
						$result["exchange"][$key]["exchanger_username"] = $exchangerDetails->username;
						if (!empty($exchangerDetails->userImage)) {
							$userImageUrl = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $exchangerDetails->userImage);
						} else {
							$userImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/logo/' . yii::$app->Myclass->getDefaultUser());
						}
						$result["exchange"][$key]["exchanger_image"] = $userImageUrl;
					} else {
						$result["exchange"][$key]["request_by_me"] = 'false';
						$result["exchange"][$key]["exchange_time"] = date("d-m-Y", $exchange->date);
						$exchangerDetails = yii::$app->Myclass->getUserDetailss($exchange->requestFrom);
						$result["exchange"][$key]["exchanger_name"] = $exchangerDetails->name;
						$result["exchange"][$key]["exchanger_username"] = $exchangerDetails->username;
						if (!empty($exchangerDetails->userImage)) {
							$userImageUrl = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $exchangerDetails->userImage);
						} else {
							$userImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/logo/' . yii::$app->Myclass->getDefaultUser());
						}
						$result["exchange"][$key]["exchanger_image"] = $userImageUrl;
					}
					$result["exchange"][$key]["exchanger_id"] = $exchangerDetails->userId;

					$productImage = yii::$app->Myclass->getProductImage($exchange->mainProductId);
					$productDetails = yii::$app->Myclass->getProductDetails($exchange->mainProductId);
					$proImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/item/' . $exchange->mainProductId . '/' . $productImage);
					$result["exchange"][$key]["my_product"]["item_id"] = $productDetails->productId;
					$result["exchange"][$key]["my_product"]["item_name"] = $productDetails->name;
					$result["exchange"][$key]["my_product"]["item_image"] = $proImageUrl;


					$exproductImage = yii::$app->Myclass->getProductImage($exchange->exchangeProductId);
					$exproductDetails = yii::$app->Myclass->getProductDetails($exchange->exchangeProductId);
					$exproImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/item/' . $exchange->exchangeProductId . '/' . $exproductImage);
					$result["exchange"][$key]["exchange_product"]["item_id"] = $exproductDetails->productId;
					$result["exchange"][$key]["exchange_product"]["item_name"] = $exproductDetails->name;
					$result["exchange"][$key]["exchange_product"]["item_image"] = $exproImageUrl;

					endforeach;
					return '{"status":"true","result":' . Json::encode($result) . '}';
				} else {
					return '{"status":"false","message":"No data found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}


	/* 
		CREATE EXCHANGE PARAMS -  $api_username, $api_password, $user_id, $myitem_id, $exchangeitem_id
	 */
	public function actionCreateexchange()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		if ($this->authenticateAPI($api_username, $api_password)) {
			$user_id = $_POST['user_id'];
			if ($this->checking($user_id)) {
				$myitem_id = $_POST['myitem_id'];
				$exchangeitem_id = $_POST['exchangeitem_id'];
				$exchange = new Exchanges;
				$exchange->mainProductId = $myitem_id;
				$exchange->exchangeProductId = $exchangeitem_id;
				$exchange->requestFrom = $user_id;
				$product = Products::findOne($myitem_id);
				$exchange->requestTo = $product->userId;
				$exchange->date = time();
				$exchange->slug = yii::$app->Myclass->getRandomString(8);
				$exchange->status = self::PENDING;
				$mainProductModel = yii::$app->Myclass->getProductDetails($myitem_id);
				$exchangeProductModel = yii::$app->Myclass->getProductDetails($exchangeitem_id);
				if ($mainProductModel->quantity < 1 || $mainProductModel->soldItem != 0) {
					return '{"status":"false","message":"Product has been soldout unexpectedly"}';
				} elseif ($exchangeProductModel->quantity < 1 || $exchangeProductModel->soldItem != 0) {
					return '{"status":"false","message":"Your choosen Product has been soldout, choose a different one"}';
				} else {
					$check = yii::$app->Myclass->exchangeProductExist($exchange->mainProductId, $exchange->exchangeProductId, $exchange->requestFrom, $exchange->requestTo);

					if (!empty($check)) {
						if ($check->blockExchange == 1) {
							return '{"status":"false","message":"Exchange Request for this product has been blocked"}';
						} else {
							if ($check->status != 0 && $check->status != 1) {
								$check->requestFrom = $user_id;
								$check->requestTo = $product->userId;
								$check->status = self::PENDING;
								$check->date = time();
								$history = array();
								if (!empty($check->exchangeHistory)) {
									$history = Json::decode($check->exchangeHistory, true);
								}
								$history[] = array('status' => 'created', 'date' => $check->date, 'user' => $check->requestFrom);
								$check->exchangeHistory = Json::encode($history);
								$check->save(false);

								$userid = $check->requestFrom;
								$senderid = $check->requestTo;
								$sellerDetails = yii::$app->Myclass->getUserDetailss($userid);
								$receiverDetails = yii::$app->Myclass->getUserDetailss($senderid);
								$userdevicedet = Userdevices::find()->where(['user_id' => $senderid])->all();

								if (count($userdevicedet) > 0) {
									foreach ($userdevicedet as $userdevice) {
										$deviceToken = $userdevice->deviceToken;
										$lang = $userdevice->lang_type;
										$badge = $userdevice->badge;
										$badge += 1;
										$userdevice->badge = $badge;
										$userdevice->deviceToken = $deviceToken;
										$userdevice->save(false);
										if (isset($deviceToken)) {

											$msg = yii::$app->Myclass->push_lang($lang);

											$text = 'sent exchange request to your product';
											$msg = Yii::t('app', $text);

											$messages = $sellerDetails->name . " " . $msg . " " . $mainProductModel->name;
											yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
										}
									}
								}
								$notifyMessage = 'sent exchange request to your product';
								yii::$app->Myclass->addLogs("exchange", $user_id, $senderid, $check->id, $myitem_id, $notifyMessage);

								$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
								$emailTo = $receiverDetails->email;
								$mailer = Yii::$app->mailer->setTransport([
									'class' => 'Swift_SmtpTransport',
									'host' => $siteSettings->smtpHost,
									'username' => $siteSettings->smtpEmail,
									'password' => $siteSettings->smtpPassword,
									'port' => $siteSettings->smtpPort,
									'encryption' => 'tls',
								]);
								try {
									if ($exchange->sendExchangeProductEmail($emailTo, $sellerDetails->name, $receiverDetails->name)) {
										return '{"status":"true","result":"Exchange created successfully"}';
										die;
									}
								} catch (\Swift_TransportException $exception) {
									return '{"status":"true","result":"Exchange created successfully"}';
									die;
								} catch (\Exception $exception) {
									return '{"status":"true","result":"Exchange created successfully"}';
									die;
								}
							} else {
								return '{"status":"false","message":"Exchange Request exists. Please check Your Exchanges"}';
							}
						}
					} else {

						if ($exchange) {
							$history = array();
							if (!empty($exchange->exchangeHistory)) {
								$history = Json::decode($exchange->exchangeHistory, true);
							}
							$history[] = array('status' => 'created', 'date' => $exchange->date, 'user' => $exchange->requestFrom);
							$exchange->exchangeHistory = Json::encode($history);
							$exchange->save(false);
							$userid = $exchange->requestFrom;
							$senderid = $exchange->requestTo;
							$pushsender = $senderid;
							$pushuser = $userid;
							if ($user_id == $userid) {
								$pushuser = $senderid;
								$pushsender = $userid;
							}
							$sellerDetails = yii::$app->Myclass->getUserDetailss($pushsender);
							$userid = $exchange->requestFrom;
							$senderid = $exchange->requestTo;
							$sellerDetails = yii::$app->Myclass->getUserDetailss($userid);
							$receiverDetails = yii::$app->Myclass->getUserDetailss($senderid);
							$userdevicedet = Userdevices::find()->where(['user_id' => $senderid])->all();
							if (count($userdevicedet) > 0) {
								foreach ($userdevicedet as $userdevice) {
									$deviceToken = $userdevice->deviceToken;
									$lang = $userdevice->lang_type;
									$badge = $userdevice->badge;
									$badge += 1;
									$userdevice->badge = $badge;
									$userdevice->deviceToken = $deviceToken;
									$userdevice->save(false);
									if (isset($deviceToken)) {
										$msg = yii::$app->Myclass->push_lang($lang);

										$text = 'sent exchange request to your product';
										$msg = Yii::t('app', $text);

										$messages = $sellerDetails->name . " " . $msg . " " . $mainProductModel->name;
										yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
									}
								}
							}

							$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
							$emailTo = $receiverDetails->email;
							$mailer = Yii::$app->mailer->setTransport([
								'class' => 'Swift_SmtpTransport',
								'host' => $siteSettings['smtpHost'],
								'username' => $siteSettings['smtpEmail'],
								'password' => $siteSettings['smtpPassword'],
								'port' => $siteSettings['smtpPort'],
								'encryption' => 'tls',
							]);

							$notifyMessage = 'sent exchange request to your product';
							yii::$app->Myclass->addLogs("exchange", $user_id, $senderid, $exchange->id, $myitem_id, $notifyMessage);

							try {
								if ($exchange->sendExchangeProductEmail($emailTo, $sellerDetails->name, $receiverDetails->name)) {
									return '{"status":"true","result":"Exchange created successfully"}';
									die;

								}
							} catch (\Swift_TransportException $exception) {
								return '{"status":"true","result":"Exchange created successfully"}';
								die;
							} catch (\Exception $exception) {
								return '{"status":"true","result":"Exchange created successfully"}';
								die;
							}

						}
					}
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		EXCHANGE STATUS PARAMS -  $api_username, $api_password, $user_id, $exchange_id, $status
	 */
	public function actionExchangestatus()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				$exchange_id = $_POST['exchange_id'];
				$status = $_POST['status'];
				$userId = $user_id;
				$Exstatus = $status;
				$status = Exchanges::findOne($exchange_id);
				$statusUpdate = "";

				if ($Exstatus == "accept" && $status->status == 0) {
					$status->status = self::ACCEPT;
				} elseif ($Exstatus == "decline" && $status->status == 0) {
					$status->status = self::DECLINE;
				} elseif ($Exstatus == "cancel" && $status->status == 0) {
					$status->status = self::CANCEL;
				} elseif ($Exstatus == "success" && $status->status == 1) {
					$status->status = self::SUCCESS;
				} elseif ($Exstatus == "failed" && $status->status == 1) {
					$status->status = self::FAILED;
				} else {
					return '{"status":"false", "message":"Status Already Updated"}';
					die;
				}

				$userid = $status->requestFrom;
				$senderid = $status->requestTo;
				$pushsender = $senderid;
				$pushuser = $userid;
				$productId = $status->exchangeProductId;
				if ($user_id == $userid) {
					$pushuser = $senderid;
					$pushsender = $userid;
					$productId = $status->mainProductId;
				}

				$checkproductQuantity = Products::findOne($status->mainProductId);
				$checkexproductQuantity = Products::findOne($status->exchangeProductId);

				if( $checkproductQuantity->quantity == 0 || $checkexproductQuantity->quantity == 0 )
				{

					$status->status = 3;
					$status->save(false);
					return '{"status":"false", "message":"Product sold out."}';
					die;
				}

				$sellerDetails = yii::$app->Myclass->getUserDetailss($pushsender);
				$receiverDetails = yii::$app->Myclass->getUserDetailss($pushuser);
				$userdevicedet = Userdevices::find()->where(['user_id' => $pushuser])->all();
				$status->save(false);
				$emailTo = $receiverDetails->email;
				$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

				$mainProduct = Products::findOne($productId);
				if ($Exstatus == "accept") {
					$notifyMessage = 'accepted your exchange request on';
					yii::$app->Myclass->addLogs("exchange", $pushsender, $pushuser, $status->id, $status->mainProductId, $notifyMessage);
					$mailLayout = "exchangeaccept";
					$mailSubject = 'Exchange Request with your product was Accepted';
				} elseif ($Exstatus == "decline") {
					$notifyMessage = 'declined your exchange request on';
					yii::$app->Myclass->addLogs("exchange", $pushsender, $pushuser, $status->id, $status->mainProductId, $notifyMessage);
					$mailLayout = "exchangedecline";
					$mailSubject = 'Exchange Request with your product was Declined';
				} elseif ($Exstatus == "cancel") {
					$notifyMessage = 'canceled your exchange request on';
					yii::$app->Myclass->addLogs("exchange", $pushsender, $pushuser, $status->id, $status->mainProductId, $notifyMessage);
					$mailLayout = "exchangecancel";
					$mailSubject = 'cancelled Exchange Request with your product';
				} elseif ($Exstatus == "success") {
					$notifyMessage = 'successed your exchange request on';
					yii::$app->Myclass->addLogs("exchange", $pushsender, $pushuser, $status->id, $status->mainProductId, $notifyMessage);
					$mailLayout = "exchangesuccess";
					$mailSubject = 'Exchange Request with your product was Successed';
				} elseif ($Exstatus == "failed") {
					$notifyMessage = 'failed your exchange request on';
					yii::$app->Myclass->addLogs("exchange", $pushsender, $pushuser, $status->id, $status->mainProductId, $notifyMessage);
					$mailLayout = "exchangefailed";
					$mailSubject = 'Exchange Request with your product was Failed';
				}

				if (count($userdevicedet) > 0) {
					foreach ($userdevicedet as $userdevice) {
						$deviceToken = $userdevice->deviceToken;
						$lang = $userdevice->lang_type;
						$badge = $userdevice->badge;
						$badge += 1;
						$userdevice->badge = $badge;
						$userdevice->deviceToken = $deviceToken;
						$userdevice->save(false);
						if (isset($deviceToken)) {
							//$messages = $Exstatus." Exchange Request from ".$sellerDetails->name;
							$msg = yii::$app->Myclass->push_lang($lang);

							if ($Exstatus == "accept") {
								$text = 'accepted your exchange request on';
								$msg = Yii::t('app', $text);
								$messages = $sellerDetails->name . " " . $msg . " " . $mainProduct->name;
							} elseif ($Exstatus == "decline") {
								$text = 'declined your exchange request on';
								$msg = Yii::t('app', $text);
								$messages = $sellerDetails->name . " " . $msg . " " . $mainProduct->name;
							} elseif ($Exstatus == "cancel") {
								$text = 'canceled your exchange request on';
								$msg = Yii::t('app', $text);
								$messages = $sellerDetails->name . " " . $msg . " " . $mainProduct->name;
							} elseif ($Exstatus == "success") {
								$text = 'successed your exchange request on';
								$msg = Yii::t('app', $text);
								$messages = $sellerDetails->name . " " . $msg . " " . $mainProduct->name;
							} elseif ($Exstatus == "failed") {
								$text = 'failed your exchange request on';
								$msg = Yii::t('app', $text);
								$messages = $sellerDetails->name . " " . $msg . " " . $mainProduct->name;
							}

							yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
						}
					}
				}

				if ($Exstatus == "success") {
					$mainProduct = Products::findOne($status->mainProductId);
					$mainProduct->soldItem = 1;
					if ($mainProduct->promotionType != 3) {
						$promotionModel = Promotiontransaction::find()->where(['productId' => $mainProduct->productId])
							->andWhere(['LIKE', 'status', 'live'])->one();

						if (!empty($promotionModel)) {
							if ($promotionModel->promotionName != 'urgent') {
								$previousPromotion = Promotiontransaction::find()->where(['productId' => $promotionModel->productId])
									->andWhere(['LIKE', 'status', 'Expired'])->one();
								if (!empty($previousPromotion)) {
									$previousPromotion->status = "Canceled";
									$previousPromotion->save(false);
								}
							}
							$promotionModel->status = "Expired";
							$promotionModel->save(false);
						}
					}

					$mainProduct->promotionType = 3;
					$mainProduct->quantity--;
					$mainProduct->save(false);
					$exProduct = Products::findOne($status->exchangeProductId);
					$exProduct->soldItem = 1;
					if ($exProduct->promotionType != 3) {
						$promotionModel = Promotiontransaction::find()->where(['productId' => $exProduct->productId])
							->andWhere(['LIKE', 'status', 'live'])->one();
						if (!empty($promotionModel)) {
							if ($promotionModel->promotionName != 'urgent') {
								$previousPromotion = Promotiontransaction::find()->where(['productId' => $promotionModel->productId])
									->andWhere(['LIKE', 'status', 'Expired'])->one();
								if (!empty($previousPromotion)) {
									$previousPromotion->status = "Canceled";
									$previousPromotion->save(false);
								}
							}
							$promotionModel->status = "Expired";
							$promotionModel->save(false);
						}
					}

					$exProduct->promotionType = 3;
					$exProduct->quantity--;
					$exProduct->save(false);
				}

				if ($siteSettings->smtpEnable == 1) {
					$mailer = Yii::$app->mailer->setTransport([
						'class' => 'Swift_SmtpTransport',
						'host' => $siteSettings['smtpHost'],
						'username' => $siteSettings['smtpEmail'],
						'password' => $siteSettings['smtpPassword'],
						'port' => $siteSettings['smtpPort'],
						'encryption' => 'tls',
					]);
					try {
						$productModels = new Products;
						if ($productModels->sendExchangeProductMail(
							$emailTo,
							$receiverDetails->name,
							$sellerDetails->name,
							$mailLayout,
							$mailSubject
						)) {
							return '{"status":"true","result":"Exchange updated successfully"}';
						}
					} catch (\Swift_TransportException $exception) {
						return '{"status":"true","result":"Exchange updated successfully"}';
					} catch (\Exception $e) {
						return '{"status":"true","result":"Exchange updated successfully"}';
					}
				} else {
					return '{"status":"true","result":"Exchange updated successfully"}';
				}

			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		ADD SHIPING PARAMS -  $api_username, $api_password, $user_id, $full_name, $nick_name, $country_id, $country_name, $state, $address1, $address2, $city, $zip_code, $phone_no, $default, $shipping_id = 0
	 */
	public function actionAddshipping()
	{

		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];
			$shipping_id = $_POST['shipping_id'];

			if ($this->checking($user_id)) {
				$shippingId = 0;
				if ($shipping_id != 0) {
					$shippingId = $shipping_id;
				}

				$userId = $user_id;
				$fullName = $_POST['full_name'];
				$nickName = $_POST['nick_name'];
				$countryId = $_POST['country_id'];
				$countryName = $_POST['country_name'];
				$state = $_POST['state'];
				$address1 = $_POST['address1'];
				$address2 = $_POST['address2'];
				$city = $_POST['city'];
				$zipCode = $_POST['zip_code'];
				$phoneNo = $_POST['phone_no'];
				$default = $_POST['default'];

				if ($shippingId == 0) {
						// $criteria = new CDbCriteria;
						// $criteria->addCondition("nickname = '{$nickName}'");
						// $criteria->addCondition("userId = $userId");
					$shippingModel = Tempaddresses::find()->where(['nickname' => $nickName])
						->andWhere(['userId' => $userId])->all();
				}
				if (!empty($shippingModel)) {
					return '{"status":"false","result":"Already a Shipping Address with this Nick Name Exist"}';
				} else {
					$outputValue = 'Added';
					if ($shippingId != 0) {
						$tmpaddress = Tempaddresses::findOne($shippingId);
						$tmpaddress->shippingaddressId = $shippingId;
						$outputValue = 'Updated';
					} else {
						$tmpaddress = new Tempaddresses;
					}
					$tmpaddress->userId = $userId;
					$tmpaddress->name = $fullName;
					$tmpaddress->nickname = $nickName;
					$tmpaddress->country = $countryName;
					$tmpaddress->state = $state;
					$tmpaddress->address1 = $address1;
					$tmpaddress->address2 = $address2;
					$tmpaddress->city = $city;
					$tmpaddress->zipcode = $zipCode;
					$tmpaddress->phone = $phoneNo;
					$tmpaddress->slug = yii::$app->Myclass->getRandomString(8);
					$tmpaddress->countryCode = $countryId;
					if ($tmpaddress->save(false)) {
						$tempaddress['Tempaddresses']['shippingid'] = $tmpaddress->shippingaddressId;
						$tempaddress['Tempaddresses'] = $tmpaddress->attributes;

						if ($default == 1) {
							$user = Users::findOne($userId);
							$user->defaultshipping = $tmpaddress->shippingaddressId;
							$user->save(false);
							$output = Json::encode($tempaddress['Tempaddresses']);
						} else {
							$userModel = Users::findOne($userId);
							$defaultAddress = $userModel->defaultshipping;
							$shipping = Tempaddresses::findOne($tmpaddress->shippingaddressId);
							$shippingAddress['shippingid'] = $shipping->shippingaddressId;
							$shippingAddress['nickname'] = $shipping->nickname;
							$shippingAddress['name'] = $shipping->name;
							$shippingAddress['country'] = $shipping->country;
							$shippingAddress['state'] = $shipping->state;
							$shippingAddress['address1'] = $shipping->address1;
							$shippingAddress['address2'] = $shipping->address2;
							$shippingAddress['city'] = $shipping->city;
							$shippingAddress['zipcode'] = $shipping->zipcode;
							$shippingAddress['phone'] = $shipping->phone;
							$shippingAddress['countrycode'] = $shipping->countryCode;
							$output = Json::encode($shippingAddress);
						}
						return '{"status":"true","result":' . $output . '}';
					} else {
						return '{"status":"false","result":"Not Saved.Something went wrong.Try again Later."}';
					}
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET SHIPING ADDRESS PARAMS - $api_username, $api_password, $user_id, $item_id
	 */
	public function actionGetShippingAddress()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = '0';
			if (isset($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
			}

			if ($this->checking($user_id)) {
				$userId = $user_id;
				$userModel = yii::$app->Myclass->getUserDetailss($userId);
				$defaultShipping = $userModel->defaultshipping;
				$item_id = 0;
				if (isset($_POST['item_id'])) {
					$item_id = $_POST['item_id'];
				}

				if ($item_id != 0) {
					$productModel = Products::findOne($item_id);
					$checkBlockStatus = yii::$app->Myclass->getWhosBlock($user_id, $productModel->userId); //new changes
					//if($checkBlockStatus==0){
					if ($productModel->soldItem == 1) {
						return '{"status":"false","message":"Product already sold out"}';
						die;

					} else if ($productModel->approvedStatus == 0) {
						return '{"status":"false","message":"Product in disabled status."}';
						die;

					} elseif ($checkBlockStatus == 1) {
						return '{"status":"false","message":"conversation blocked."}';
						die;
					} else {
						$countryId = $productModel->shippingcountry;
							// $criteria = new CDbCriteria;
							// $criteria->addCondition('userId = "'.$userId.'"');
							// $criteria->addCondition('countryCode = "'.$countryId.'"');
							// $criteria->order = 'shippingaddressId DESC';
						$shippingModel = Tempaddresses::find()->where(['userId' => $userId])
							->andWhere(['countryCode' => $countryId])
							->orderBy(['shippingaddressId' => SORT_DESC])->all();

					}
				} else {
						// $criteria = new CDbCriteria;
						// $criteria->addCondition('userId = "'.$userId.'"');
						// $criteria->order = 'shippingaddressId DESC';
					$shippingModel = Tempaddresses::find()->where(['userId' => $userId])
						->orderBy(['shippingaddressId' => SORT_DESC])->all();
				}

				if (!empty($shippingModel)) {
					$shippingAddress = array();
					foreach ($shippingModel as $skey => $shipping) {
						$shippingAddress[$skey]['shippingid'] = $shipping->shippingaddressId;
						$shippingAddress[$skey]['nickname'] = $shipping->nickname;
						$shippingAddress[$skey]['name'] = $shipping->name;
						$shippingAddress[$skey]['country'] = $shipping->country;
						$shippingAddress[$skey]['state'] = $shipping->state;
						$shippingAddress[$skey]['address1'] = $shipping->address1;
						$shippingAddress[$skey]['address2'] = $shipping->address2;
						$shippingAddress[$skey]['city'] = $shipping->city;
						$shippingAddress[$skey]['zipcode'] = $shipping->zipcode;
						$shippingAddress[$skey]['phone'] = $shipping->phone;
						$shippingAddress[$skey]['countrycode'] = $shipping->countryCode;
						$shippingAddress[$skey]['defaultshipping'] = 0;
						if ($defaultShipping == $shipping->shippingaddressId) {
							$shippingAddress[$skey]['defaultshipping'] = 1;
						}
					}
					$resultArray = Json::encode($shippingAddress);
					return '{"status":"true","result":' . $resultArray . '}';
				} else {
					return '{"status":"false","message":"Yet no shipping address added"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		SET DEFAULT SHIPING PARAMS -  $api_username, $api_password, $user_id, $shipping_id
	 */
	public function actionSetdefaultshipping()
	{
	 	//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
	 		//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				$default = Users::findOne($user_id);
				$default->defaultshipping = $_POST['shipping_id'];
				if ($default->save(false)) {
					return '{"status":"true","message":"Your default Address changed"}';
				} else {
					return '{"status":"false","message":"Something went wrong"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		REMOVE SHIPPING PARAMS - $api_username, $api_password, $user_id,$shipping_id
	 */
	public function actionRemoveshipping()
	{
	 	//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
	 		//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				$address = Tempaddresses::find()->where(['shippingaddressId' => $_POST['shipping_id']])->one();
					//print_r($address);exit;
				$userDefaultShipping = yii::$app->Myclass->getDefaultShippingAddress($address['userId']);
				if ($userDefaultShipping == $address->shippingaddressId) {
						// $criteria = new CDbCriteria;
						// $criteria->addCondition("userId = '$address->userId'");
						// $criteria->addCondition("shippingaddressId != '$address->shippingaddressId'");
						// $criteria->order = 'shippingaddressId DESC';
					$addressChange = Tempaddresses::find()->where(['userId' => $address->userId])
						->andWhere(['!=', 'shippingaddressId', $address->shippingaddressId])
						->orderBy(['shippingaddressId' => SORT_DESC])->one();

					//return  "<pre>";print_r($addressChange);die;
					$user = Users::findOne($address->userId);
					if (!empty($addressChange)) {
						$user->defaultshipping = $addressChange->shippingaddressId;
					} else {
						$user->defaultshipping = 0;
					}
					$user->save(false);
				}
				if ($address->delete()) {
					return '{"status":"true","message":"Address deleted successfully"}';
				} else {
					return '{"status":"false","message":"Something went wrong"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET TRACKING DETAILS PARAMS -  $api_username, $api_password, $order_id
	 */
	public function actionGettrackdetails()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$orderid = $_POST['order_id'];

			$trackingModel = Trackingdetails::find()->where(['orderid' => $orderid])->one();

			if (!empty($trackingModel)) {
				$Trackingdetails['id'] = $trackingModel->id;
				$Trackingdetails['shippingdate'] = $trackingModel->shippingdate;
				$Trackingdetails['couriername'] = $trackingModel->couriername;
				$Trackingdetails['courierservice'] = $trackingModel->courierservice;
				$Trackingdetails['trackingid'] = $trackingModel->trackingid;
				$Trackingdetails['notes'] = $trackingModel->notes;

				$result = Json::encode($Trackingdetails);
				return '{"status":"true","result":' . $result . '}';
			} else {
				return '{"status":"false","result":"No Tracking Details Found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		ORDER STATUS PARAMS -  $api_username, $api_password, $orderid, $chstatus, $subject = NULL,	$message = NULL, $id = 0, $shippingdate = NULL, $couriername = NULL, $courierservice = NULL,	$trackid = NULL, $notes = NULL
	 */
	public function actionOrderstatus()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$orderid = $_POST['orderid'];
			$orderPreviousStatus = Orders::findOne($orderid);
			$status = $_POST['chstatus'];
			if ($status == 'Processing' && $orderPreviousStatus->status == "pending") {
				Orders::updateAll(['status' => 'processing'], ['orderId' => $orderid]);
				$userid = $orderPreviousStatus->userId;
				$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
				$notifyMessage = 'your order has been marked as processing Order Id :' . $orderPreviousStatus->orderId;
				yii::$app->Myclass->addLogs("order", $orderPreviousStatus->sellerId, $orderPreviousStatus->userId, $orderPreviousStatus->orderId, 0, $notifyMessage);

				if (count($userdevicedet) > 0) {
					foreach ($userdevicedet as $userdevice) {
						$deviceToken = $userdevice->deviceToken;
						$lang = $userdevice->lang_type;
						$badge = $userdevice->badge;
						$badge += 1;
						$userdevice->badge = $badge;
						$userdevice->deviceToken = $deviceToken;
						$userdevice->save(false);
						if (isset($deviceToken)) {
							$msg = yii::$app->Myclass->push_lang($lang);
							$text = 'Your orderid:';
							$msg = Yii::t('app', $text);
							$text1 = 'has been marked as processing';
							$msg1 = Yii::t('app', $text1);
							$messages = $msg . ' ' . $orderid . ' ' . $msg1;
							yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
						}
					}
				}

				return '{"status":"true","result":"Status changed to Processing"}';
			} else if ($status == "claim") {
				$order = Orders::findOne($orderid);
				$order->status = "claimed";
				$order->save(false);
				return '{"status":"true","result":"Status changed to claimed"}';
			} else if ($status == "cancel") {

				$paymentmodes = yii::$app->Myclass->getSitePaymentModes();
				$order = Orders::findOne($orderid);

				if (($paymentmodes['cancelEnableStatus'] == "processing" && $order->status == "pending") || ($paymentmodes['cancelEnableStatus'] == "shipped" && ($order->status == "pending" || $order->status == "processing"))) {
					
					$orderdata = Orders::findOne($orderid);
					$orderdata->status = "cancelled";
					$orderdata->trackPayment = "pending";
					$orderdata->save(false);
					$order = Orders::find()->with('orderitems')->where(['orderId' => $orderid])->one();
					$productid = $order['orderitems'][0]['productId'];
					$productdata = Products::findOne($productid);
					if(!empty($productdata))
					{
						$productdata->quantity = 1;
						$productdata->soldItem = 0;
						$productdata->save(false);
					}
					else {
						return '{"status":"false","result":"No product found"}';
					}
					
					$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
					$check = Users::findOne($order->sellerId);
					$notifyMessage = 'your order has been cancelled Order Id :' . $order->orderId;
					yii::$app->Myclass->addLogs("order", $order->userId, $order->sellerId, $order->orderId, 0, $notifyMessage);
					$userid = $check->userId;
					$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
					if (count($userdevicedet) > 0) {
						foreach ($userdevicedet as $userdevice) {
							$deviceToken = $userdevice->deviceToken;
							$lang = $userdevice->lang_type;
							$badge = $userdevice->badge;
							$badge += 1;
							$userdevice->badge = $badge;
							$userdevice->deviceToken = $deviceToken;
							$userdevice->save(false);
							if (isset($deviceToken)) {
	
								$msg = yii::$app->Myclass->push_lang($lang);
	
								$text = 'your order has been cancelled Order Id :';
								$msg = Yii::t('app', $text);
	
								$messages = $msg . ' ' . $order->orderId;
								yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
							}
						}
					}
	
					if ($siteSettings->smtpEnable == 1) {
						$mailer = Yii::$app->mailer->setTransport([
							'class' => 'Swift_SmtpTransport',
							'host' => $siteSettings['smtpHost'],
							'username' => $siteSettings['smtpEmail'],
							'password' => $siteSettings['smtpPassword'],
							'port' => $siteSettings['smtpPort'],
							'encryption' => 'tls',
						]);
						$orderModels = new Orders();
						try {
							if ($orderModels->sendCancelEmail($check->email, $check->name, $orderid)) {
								return '{"status":"true","result":"Status changed to cancelled"}';
							}
						} catch (\Swift_TransportException $exception) {
							return '{"status":"true","result":"Status changed to cancelled"}';
						} catch (\Exception $e) {
							return '{"status":"true","result":"Status changed to cancelled"}';
						}
					} else {
						return '{"status":"true","result":"Status changed to cancelled"}';
					}
				} else {
					return '{"status":"false","result":"Sorry cannot cancel your order"}';
				}

			} elseif ($status == 'Delivered' && ($orderPreviousStatus->status == "shipped" || $orderPreviousStatus->status == "claimed")) {
				$statusDate = time();

				Orders::updateAll(['status' => 'delivered', 'statusDate' => $statusDate], ['orderId' => $orderid]);

				$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				$check = Users::findOne($orderPreviousStatus->userId);
				$userName = $check->username;

				$notifyMessage = 'your order has been marked as delivered Order Id :' . $orderPreviousStatus->orderId;
				yii::$app->Myclass->addLogs("order", $orderPreviousStatus->userId, $orderPreviousStatus->sellerId, $orderPreviousStatus->orderId, 0, $notifyMessage);
				$userid = $orderPreviousStatus->sellerId;
				$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
				if (count($userdevicedet) > 0) {
					foreach ($userdevicedet as $userdevice) {
						$deviceToken = $userdevice->deviceToken;
						$lang = $userdevice->lang_type;
						$badge = $userdevice->badge;
						$badge += 1;
						$userdevice->badge = $badge;
						$userdevice->deviceToken = $deviceToken;
						$userdevice->save(false);
						if (isset($deviceToken)) {
							$msg = yii::$app->Myclass->push_lang($lang);
							$text = ' has marked the order as delivered, order id: ';
							$msg = Yii::t('app', $text);
							$messages = $userName . $msg . $orderid;
							yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);

						}
					}
				}

				if ($siteSettings->smtpEnable == 1) {
					$mailer = Yii::$app->mailer->setTransport([
						'class' => 'Swift_SmtpTransport',
						'host' => $siteSettings['smtpHost'],
						'username' => $siteSettings['smtpEmail'],
						'password' => $siteSettings['smtpPassword'],
						'port' => $siteSettings['smtpPort'],
						'encryption' => 'tls',
					]);
					$orderModels = new Orders();
					try {
						if ($orderModels->sendOrderEmail($check->email, $check->name, $orderid)) {
							return '{"status":"true","result":"Status changed to Delivered"}';
						}
					} catch (\Swift_TransportException $exception) {
						return '{"status":"true","result":"Status changed to Delivered"}';
					} catch (\Exception $e) {
						return '{"status":"true","result":"Status changed to Delivered"}';
					}
				} else {
					return '{"status":"true","result":"Status changed to Delivered"}';
				}

			} elseif ($status == 'Shipped' && $orderPreviousStatus->status == "processing") {
				$subject = $_POST['subject'];
				$message = $_POST['message'];
				$orderModel = Orders::findOne($orderid);
				$shipping = Shippingaddresses::findOne($orderModel->shippingAddress);
				$loguser = yii::$app->Myclass->getUserDetailss($orderModel->sellerId);
				$buyerModel = yii::$app->Myclass->getUserDetailss($orderModel->userId);
				$buyeremail = $buyerModel->email;
				$usernameforcust = $buyerModel->name;
				$orderitemModel = Orderitems::find()->where(['orderid' => $orderid])->all();
				$itemmailids = $orderitemModel->productId;
				$itemname = $orderitemModel->itemName;
				$itemsize = $orderitemModel->itemSize;
				$totquantity = $orderitemModel->itemQuantity;
				$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				Orders::updateAll(['status' => 'shipped'], ['orderId' => $orderid]);
				$order = Orders::find()->with('orderitems')->where(['orderId' => $orderid])->one();
				$notifyMessage = 'your order has been marked as shipped Order Id :' . $order->orderId;
				yii::$app->Myclass->addLogs("order", $order->sellerId, $order->userId, $order->orderId, 0, $notifyMessage);
				$userid = $orderModel->userId;
				$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
				if (count($userdevicedet) > 0) {
					foreach ($userdevicedet as $userdevice) {
						$deviceToken = $userdevice->deviceToken;
						$lang = $userdevice->lang_type;
						$badge = $userdevice->badge;
						$badge += 1;
						$userdevice->badge = $badge;
						$userdevice->deviceToken = $deviceToken;
						$userdevice->save(false);
						if (isset($deviceToken)) {
							$msg = yii::$app->Myclass->push_lang($lang);
							$text = 'Your orderid:';
							$msg = Yii::t('app', $text);
							$text1 = 'has been marked as shipped';
							$msg1 = Yii::t('app', $text1);
							$messages = $msg . ' ' . $orderid . ' ' . $msg1;
							yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
						}
					}
				}
				if ($siteSettings->smtpEnable == 1) {
					$mailer = Yii::$app->mailer->setTransport([
						'class' => 'Swift_SmtpTransport',
						'host' => $siteSettings['smtpHost'],
						'username' => $siteSettings['smtpEmail'],
						'password' => $siteSettings['smtpPassword'],
						'port' => $siteSettings['smtpPort'],
						'encryption' => 'tls',
					]);
					$orderModels = new Orders();
					try {
						if ($orderModel->sendEmail(
							$buyeremail,
							$subject,
							$siteSettings,
							$message,
							$shipping,
							$buyerModel,
							$orderid,
							$loguser->name
						)) {
							return '{"status":"true","result":"Status changed to Shipped"}';
						}
					} catch (\Swift_TransportException $exception) {
						return '{"status":"true","result":"Status changed to Shipped"}';
					} catch (\Exception $e) {
						return '{"status":"true","result":"Status changed to Shipped"}';
					}
				} else {
					return '{"status":"true","result":"Status changed to Shipped"}';
				}
			} elseif ($status == 'Track' && ($orderPreviousStatus->status == "processing" || $orderPreviousStatus->status == "shipped")) {
				$orderModel = Orders::findOne($orderid);
				$shipping = Shippingaddresses::findOne($orderModel->shippingAddress);
				$loguser = yii::$app->Myclass->getUserDetailss($orderModel->sellerId);
				$buyerModel = yii::$app->Myclass->getUserDetailss($orderModel->userId);
				$buyeremail = $buyerModel->email;
				$usernameforcust = $buyerModel->name;//$_POST['buyername'];
				$shipppingId = $orderModel->shippingAddress;
				$shippingModel = Shippingaddresses::findOne($shipppingId);
				$buyershipaddr = '';
				$buyershipaddr .= $shippingModel->address1 . ",</br>";
				$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				if (!empty($shippingModel->address2)) {
					$buyershipaddr .= $shippingModel->address2 . ",</br>";
				}
				$buyershipaddr .= $shippingModel->city . " - " . $shippingModel->zipcode . ",</br>";
				$buyershipaddr .= $shippingModel->state . ",</br>";
				$buyershipaddr .= $shippingModel->country . ",</br>";
				$buyershipaddr .= "Ph.: " . $shippingModel->phone . ".</br>";

				$id = $_POST['id'];
				if ($id != 0) {
					$track = Trackingdetails::findOne($id);
				} else {
					$track = new Trackingdetails;
				}
				Orders::updateAll(['status' => 'shipped'], ['orderId' => $orderid]);
				$order = Orders::find()->with('orderitems')->where(['orderId' => $orderid])->one();
				$notifyMessage = 'your order has been marked as shipped Order Id :' . $order->orderId;
				yii::$app->Myclass->addLogs("order", $order->sellerId, $order->userId, $order->orderId, 0, $notifyMessage);

				$userid = $orderModel->userId;
				$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
				if (count($userdevicedet) > 0) {
					foreach ($userdevicedet as $userdevice) {
						$deviceToken = $userdevice->deviceToken;
						$lang = $userdevice->lang_type;
						$badge = $userdevice->badge;
						$badge += 1;
						$userdevice->badge = $badge;
						$userdevice->deviceToken = $deviceToken;
						$userdevice->save(false);
						if (isset($deviceToken)) {
							$msg = yii::$app->Myclass->push_lang($lang);
							$text = 'Your orderid:';
							$msg = Yii::t('app', $text);
							$text1 = 'has been marked as shipped';
							$msg1 = Yii::t('app', $text1);
							$messages = $msg . ' ' . $orderid . ' ' . $msg1;
							yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
						}
					}
				}
				$track->orderid = $orderid;
				$track->status = "shipped";
				$track->merchantid = $loguser->userId;
				$track->buyername = $usernameforcust;
				$track->buyeraddress = $buyershipaddr;
				$track->shippingdate = $_POST['shippingdate'];
				$track->couriername = $_POST['couriername'];
				$track->courierservice = $_POST['courierservice'];
				$track->trackingid = $_POST['trackid'];
				$track->notes = $_POST['notes'];
				$track->save();
				$buyerModel = yii::$app->Myclass->getUserDetailss($orderModel->userId);
				$buyeremail = $buyerModel->email;
				$userMail = new Users();
				if ($siteSettings->smtpEnable == 1) {
					$mailer = Yii::$app->mailer->setTransport([
						'class' => 'Swift_SmtpTransport',
						'host' => $siteSettings['smtpHost'],
						'username' => $siteSettings['smtpEmail'],
						'password' => $siteSettings['smtpPassword'],
						'port' => $siteSettings['smtpPort'],
						'encryption' => 'tls',
					]);
					try {
						if ($userMail->sendBuyerEmail(
							$buyeremail,
							$shipping,
							$buyerModel,
							$loguser->name,
							$track,
							$orderModel
						)) {
							return '{"status":"true","result":"Tracking Details Updated"}';
						}
					} catch (\Swift_TransportException $exception) {
						return '{"status":"true","result":"Tracking Details Updated"}';
					} catch (\Exception $e) {
						return '{"status":"true","result":"Tracking Details Updated"}';
					}
				} else {
					return '{"status":"true","result":"Tracking Details Updated"}';
				}

			} else {
				return '{"status":"false", "message":"Status already changed"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		ITEM LIKE PARAMS -  $api_username, $api_password, $user_id, $item_id
	 */
	public function actionItemlike()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$userid = $_POST['user_id'];

			if ($this->checking($userid)) {
				$itemid = $_POST['item_id'];

				$product = Products::findOne($itemid);
				if (!empty($product)) {
					$favModel = Favorites::find()->where(['userId' => $userid])
						->andWhere(['productId' => $itemid])->one();
					if (empty($favModel)) {
						$model = new Favorites();
						$model->userId = $userid;
						$model->productId = $itemid;
						if ($model->save()) {
							$product->likes++;
							$product->save(false);

							$logsModel = new Logs();
							$logsModel->type = "like";
							$logsModel->userid = $userid;
							$logsModel->notifyto = $product->userId;
							$logsModel->itemid = $product->productId;
							$logsModel->notifymessage = 'liked your product';
							$logsModel->sourceid = $model->id;
							$logsModel->createddate = time();
							$logsModel->save(false);

							$userModel = Users::find()->where(['userId' => $product->userId])->one();
							if(!empty($userModel)){
								$userModel->unreadNotification += 1;
								$userModel->save(false);
							}

							$userid = $product->userId;
								// $criteria = new CDbCriteria;
								// $criteria->addCondition('user_id = "'.$userid.'"');
							$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();

							$userModel = Users::findOne($model->userId);
							if (count($userdevicedet) > 0) {
								foreach ($userdevicedet as $userdevice) {
									$deviceToken = $userdevice->deviceToken;
									$lang = $userdevice->lang_type;
									$badge = $userdevice->badge;
									$badge += 1;
									$userdevice->badge = $badge;
									$userdevice->deviceToken = $deviceToken;
									$userdevice->save(false);
									if (isset($deviceToken)) {

										$msg = yii::$app->Myclass->push_lang($lang);

										$text = 'liked your product';
										$msg = Yii::t('app', $text);

										$messages = $userModel->username . " " . $msg . " " . $product->name;
										yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
									}
								}
							}

							return '{"status":"true","result":"Item Liked Successfully"}';
						} else {
							return '{"status":"false","result":"Something went wrong."}';
						}
					} else {
						//	Favorites::model()->deleteByPk($favModel->id);
						$deleteFavorites = Favorites::findOne($favModel->id);
						$deleteFavorites->delete();


						$product->likes--;
						$product->save(false);

							// $logCriteria = new CDbCriteria();
							// $logCriteria->addCondition("type LIKE 'like'");
							// $logCriteria->addCondition("sourceId = $favModel->id");
						$logsModel = Logs::find()->where(['LIKE', 'type', 'like'])
							->andWhere(['sourceId' => $favModel->id])->one();
						$logsModel->delete();

						return '{"status":"true","result":"Item Unliked Successfully"}';
					}
				} else {
					return '{"status":"false", "message":"Item Not Found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		BUYNOW PAYMENT PARAMS -  $api_username, $api_password, $user_id, $item_id, $shipping_id, $merchant_id, $currency_code, $nonce
		,$item_price, $shipping_fee, $order_total,$offer_id
	 */
	public function actionBuynowPayment()
	{
		
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
				//Post Values
			$user_id = '0';
			if (isset($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
			}
			$item_id = '0';
			if (isset($_POST['item_id'])) {
				$item_id = $_POST['item_id'];
			}
			$shipping_id = '';
			if (isset($_POST['shipping_id'])) {
				$shipping_id = $_POST['shipping_id'];
			}
			$merchant_id = '0';
			if (isset($_POST['merchant_id'])) {
				$merchant_id = $_POST['merchant_id'];
			}
			$currency_code = '0';
			if (isset($_POST['currency_code'])) {
				$currency_code = $_POST['currency_code'];
			}
			$nonce = '';
			if (isset($_POST['nonce'])) {
				$nonce = $_POST['nonce'];
			}
			$item_price = '';
			if (isset($_POST['item_price'])) {
				$item_price = $_POST['item_price'];
			}
			$shipping_fee = '0';
			if (isset($_POST['shipping_fee'])) {
				$shipping_fee = $_POST['shipping_fee'];
			}
			$order_total = '0';
			if (isset($_POST['order_total'])) {
				$order_total = $_POST['order_total'];
			}
			$payment_type = $_POST['payment_type'];
			

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			if($payment_type == "stripe") {

				$stripeSettings = Json::decode($siteSettings->stripe_settings, true);
				$secretkey=$stripeSettings['stripePrivateKey'];
				$url = 'https://api.stripe.com/v1/charges';
				$data = array('amount' => $order_total * 100, 'currency' => $currency_code, 'source' => $nonce);
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


			}
			else
			{
			$brainTreeSettings = Json::decode($siteSettings->braintree_settings, true);

			$paymenttype = "sandbox";
			if ($brainTreeSettings['brainTreeType'] == 1) {
				$paymenttype = "production";
			}
			$merchantid = $brainTreeSettings['brainTreeMerchantId'];
			$publickey = $brainTreeSettings['brainTreePublicKey'];
			$privatekey = $brainTreeSettings['brainTreePrivateKey'];

			Braintree\Configuration::environment($paymenttype);
			Braintree\Configuration::merchantId($merchantid);
			Braintree\Configuration::publicKey($publickey);
			Braintree\Configuration::privateKey($privatekey);

			$productModel = Products::findOne($item_id);
			$productCurrency = explode('-', $productModel->currency);
			$merchant_account_id = yii::$app->Myclass->getbraintreemerchantid($productCurrency[1]);

			if (empty($merchant_account_id)) {
				return '{"status":"false","message":"Sorry, Something went wrong. Please try again later ok"}';
			} 
			else {
				$result = Braintree\Transaction::sale([
					'amount' => $order_total,
					'merchantAccountId' => $merchant_account_id,
					'paymentMethodNonce' => $nonce
				]);
				$result1 = Braintree\Transaction::submitForSettlement($result->transaction->id);
			}
			}


			
				if ($result->success || !is_null($result->transaction->id) && $result1->success == '1' || $output['status'] == 'succeeded' ) {

					

						/* new changes for offer status update process */
					if (isset($_POST['offer_id'])) {

						$msgId = $_POST['offer_id'];
						if ($msgId != 0) {
							$offerReceived = Messages::findOne($msgId);
							$msg = Json::decode($offerReceived->message, true);
							$buynowstatus = $msg['buynowstatus'];

							if ($buynowstatus == 0) {

								$offerMessage['message'] = $msg['message'];
								$offerMessage['price'] = $msg['price'];
								if($_POST['lang_type'] == "ar")
							    	$offerMessage['formatted_price'] =yii::$app->Myclass->arabicgetFormattingCurrencyapi($msg['currency'],$msg['price']);
							    else
							    	$offerMessage['formatted_price'] =yii::$app->Myclass->getFormattingCurrencyapi($msg['currency'],$msg['price']);
								$offerMessage['currency'] = $msg['currency'];

								$currency_formats = yii::$app->Myclass->getCurrencyFormats($msg['currency']);
								if($currency_formats[0] != "")
										$offerMessage['currency_mode'] = $currency_formats[0];
								if($currency_formats[1] != "")
										$offerMessage['currency_position'] = $currency_formats[1];

									// New keys for my offer section
								$offerMessage['offerstatus'] = $msg['offerstatus'];// 0- pending,1- accept,2 -declined
								$offerMessage['type'] = $msg['type']; // sendreceive,accept,decline
								$offerMessage['msgsourceid'] = $msg['msgsourceid'];
								$offerMessage['buynowstatus'] = 1; //0-pending,1 - buyed

								$offerMessage = Json::encode($offerMessage);
								$offerReceived->message = $offerMessage;
								$offerReceived->save(false);
							}
						}

					}
					/* end */
					$transaction = $result->transaction;
					$itemId = $item_id;

					$userModel = Users::findOne($user_id);
					$userId = $userModel->userId;

					$currencyCode = $currency_code;
					$quantity = 1;

					$itemId = $item_id;
					$tempShippingModel = Tempaddresses::find()->where(['shippingaddressId' => $shipping_id])->one();

					$shippingaddressesModel = Shippingaddresses::find()
						->andWhere(['userId' => $tempShippingModel->userId])
						->andWhere(['nickname' => $tempShippingModel->nickname])
						->andWhere(['name' => $tempShippingModel->name])
						->andWhere(['address1' => $tempShippingModel->address1])
						->andWhere(['address2' => $tempShippingModel->address2])
						->andWhere(['city' => $tempShippingModel->city])
						->andWhere(['state' => $tempShippingModel->state])
						->andWhere(['country' => $tempShippingModel->country])
						->andWhere(['zipcode' => $tempShippingModel->zipcode])
						->andWhere(['phone' => $tempShippingModel->phone])
						->one();

					if (!empty($shippingaddressesModel)) {
						$shippingId = $shippingaddressesModel->shippingaddressId;
					} else {
						$newShippingEntry = new Shippingaddresses();
						$newShippingEntry->userId = $tempShippingModel->userId;
						$newShippingEntry->name = $tempShippingModel->name;
						$newShippingEntry->nickname = $tempShippingModel->nickname;
						$newShippingEntry->country = $tempShippingModel->country;
						$newShippingEntry->state = $tempShippingModel->state;
						$newShippingEntry->address1 = $tempShippingModel->address1;
						$newShippingEntry->address2 = $tempShippingModel->address2;
						$newShippingEntry->city = $tempShippingModel->city;
						$newShippingEntry->zipcode = $tempShippingModel->zipcode;
						$newShippingEntry->phone = $tempShippingModel->phone;
						$newShippingEntry->countryCode = $tempShippingModel->countryCode;

						$newShippingEntry->save(false);

						$shippingId = $newShippingEntry->shippingaddressId;
					}

					$productModel = Products::findOne($itemId);

					$ordersModel = new Orders();
					$ordersModel->userId = $userId;
					$ordersModel->sellerId = $productModel->userId;
					$ordersModel->totalCost = $order_total;
					$ordersModel->totalShipping = $shipping_fee;
					$ordersModel->orderDate = time();
					$ordersModel->shippingAddress = $shippingId;
					$ordersModel->currency = $currencyCode;
					$ordersModel->sellerPaypalId = $productModel->paypalid;
					$ordersModel->status = 'pending';
					$ordersModel->trackPayment = 'pending';

					$ordersModel->save(false);
					$orderId = $ordersModel->orderId;

					$orderItemTotalPrice = $order_total - $shipping_fee;
					$orderItemUnitPrice = $orderItemTotalPrice / $quantity;

					$orderItemsModel = new Orderitems();
					$orderItemsModel->orderId = $orderId;
					$orderItemsModel->productId = $itemId;
					$orderItemsModel->itemName = $productModel->name;
					$orderItemsModel->itemPrice = $orderItemTotalPrice;
					$orderItemsModel->itemQuantity = $quantity;
					$orderItemsModel->itemunitPrice = $orderItemUnitPrice;
					$orderItemsModel->shippingPrice = $shipping_fee;
					$orderItemsModel->save(false);

					$productModel->quantity = $productModel->quantity - $quantity;
					$productModel->soldItem = 1;
					if ($productModel->promotionType != 3) {
						$promotionModel = Promotiontransaction::find()->where(['productId' => $productModel->productId])
							->andWhere(['like', 'status', 'live'])
							->one();
						if (!empty($promotionModel)) {
							if ($promotionModel->promotionName != 'urgent') {
								$previousPromotion = Promotiontransaction::find()->where(['productId' => $promotionModel->productId])
									->andWhere(['like', 'status', 'Expired'])->one();
								if (!empty($previousPromotion)) {
									$previousPromotion->status = "Canceled";
									$previousPromotion->save(false);
								}
							}
							$promotionModel->status = "Expired";
							$promotionModel->save(false);
						}
					}
					$productModel->promotionType = 3;
					$productModel->save(false);

					$invoiceModel = new Invoices();
					$invoiceModel->orderId = $orderId;
					$invoiceModel->invoiceNo = '';
					$invoiceModel->invoiceDate = time();
					$invoiceModel->invoiceStatus = "Completed";
					if($payment_type == "stripe")
					{
					$invoiceModel->paymentMethod = 'Stripe';
					$invoiceModel->paymentTranxid = $output['id'];

					}
					else
					{
					$invoiceModel->paymentMethod = 'Braintree';
					$invoiceModel->paymentTranxid = $transaction->id;
					}
				
					$invoiceModel->save(false);

					$invoiceNo = "INV" . $invoiceModel->invoiceId . $userId;

					$invoiceModel->invoiceNo = $invoiceNo;
					$invoiceModel->save(false);

					$sellerEmail = $productModel->user->email;
					$sellerName = $productModel->user->name;

					$i = 1;
					$keyarray['iteration'] = "1";
					$keyarray['item_name' . $i] = $productModel->name;
					$keyarray['quantity' . $i] = $quantity;
					$custom[2] = "";

					$user_stripe_details = $productModel->user->stripe_details;
					if($user_stripe_details == ""  || $user_stripe_details == null)
					{
						$notifystripedetails = "Still You didn't add the stripe credentials. Please add it for getting the amount.";
						// $pushnotifystripedetails = Yii::t('app',"Still You didn't add the stripe credentials. Please add it for getting the amount.");
					}
					else
					{
						$notifystripedetails ="";
					}
					$notifyMessage = 'placed an order in your shop, Order Id : ' . $orderId . ' '.$notifystripedetails;
					yii::$app->Myclass->addLogs("order", $userModel->userId, $productModel->user->userId, $productModel->productId, $productModel->productId, $notifyMessage);

					$userid = $productModel->user->userId;
					
					$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
					if (count($userdevicedet) > 0) {
						foreach ($userdevicedet as $userdevice) {
							$deviceToken = $userdevice->deviceToken;
							$lang = $userdevice->lang_type;
							$badge = $userdevice->badge;
							$badge += 1;
							$userdevice->badge = $badge;
							$userdevice->deviceToken = $deviceToken;
							$userdevice->save(false);
							if (isset($deviceToken)) {
								$msg = yii::$app->Myclass->push_lang($lang);
								$text = 'placed an order in your shop, order id :';
								$msg = Yii::t('app', $text);
								$pushnotifystripedetails = Yii::t('app',$notifystripedetails);
								$messages = $userModel->username . " " . $msg . " " . $orderId . " ".$pushnotifystripedetails;
								yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
							}
						}
					}


					return '{"status":"true","message":"Ordered Successfully"}';
					// if ($siteSettings->smtpEnable == 1) {
					// 	$mailer = Yii::$app->mailer->setTransport([
					// 		'class' => 'Swift_SmtpTransport',
					// 		'host' => $siteSettings['smtpHost'],
					// 		'username' => $siteSettings['smtpEmail'],
					// 		'password' => $siteSettings['smtpPassword'],
					// 		'port' => $siteSettings['smtpPort'],
					// 		'encryption' => 'tls',
					// 	]);
						// try {
						// 	if ($userModel->sendSellerOrderMail(
						// 		$sellerEmail,
						// 		$orderId,
						// 		$custom[2],
						// 		$userModel,
						// 		$sellerName,
						// 		$keyarray,
						// 		$tempShippingModel
						// 	)) {
						// return '{"status":"true","message":"Ordered Successfully"}';
						// 	}
						// } catch (\Swift_TransportException $exception) {
						// 	return '{"status":"true","message":"Ordered Successfully"}';
						// } catch (\Exception $e) {
						// 	return '{"status":"true","message":"Ordered Successfully"}';
						// }
					// } else {
						
					// }
				}

			else
			{
				return '{"status":"false", "message":"Something went to be wrong."}';
			}
		
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		MY ORDERS PARAMS -  $api_username, $api_password, $user_id, $offset = 0, $limit = 10
	 */
	public function actionMyorders()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				//$recent = $_POST['recent'];
				$userid = $user_id;
				$offset = 0;
				$limit = 10;

				if (isset($_POST['limit']) && !empty($_POST['limit']))
					$limit = $_POST['limit'];

				if (isset($_POST['offset']) && !empty($_POST['offset']))
					$offset = $_POST['offset'];

					// $criteria = new CDbCriteria;
					// $criteria->addCondition("userId = {$userid}");
					// $criteria->order = 'orderId DESC';
					// $criteria->offset = $offset;
					// $criteria->limit = $limit;
				$timeline = strtotime('-1 month');
				/*if ($recent == 1){
					$criteria->addCondition("orderDate > {$timeline}");
				}else{
					$criteria->addCondition("orderDate < {$timeline}");
				}*/
				$ordersModel = Orders::find()->with('orderitems', 'trackingdetails')->where(['userId' => $userid])
					->orderBy(['orderId' => SORT_DESC])
					->offset($offset)->limit($limit)
					->all();

			// //	$ordersModel = Orders::find()->where(['userId'=>$userid])
			// 				  ->orderBy(['orderId' => SORT_DESC])
			// 				  ->offset($offset)->limit(limit)
			// 			      ->all();


				foreach ($ordersModel as $key => $order) :
					$result[$key]['orderid'] = $order->orderId;
				$result[$key]['price'] = $order->totalCost;
				if($_POST['lang_type'] == "ar")
					$result[$key]['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order->totalCost);
				else
						$result[$key]['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order->totalCost);
				$result[$key]['saledate'] = $order->orderDate;
				$result[$key]['status'] = $order->status;

				$paymentmodes = yii::$app->Myclass->getSitePaymentModes();
				if (($paymentmodes['cancelEnableStatus'] == "processing" && $order->status == "pending") || ($paymentmodes['cancelEnableStatus'] == "shipped" && ($order->status == "pending" || $order->status == "processing"))) {
					$result[$key]['cancel'] = "true";
				} else {
					$result[$key]['cancel'] = "false";
				}

				if ($order->status == "delivered" || $order->status == "paid")
					$result[$key]['deliverydate'] = $order->statusDate;
				else
					$result[$key]['deliverydate'] = "";

				$invoices = Invoices::find()->where(['orderId' => $order->orderId])->one();
				$result[$key]['transaction_id'] = $invoices['paymentTranxid'];
				$result[$key]['payment_type'] = $invoices['paymentMethod'];

				//$reviewModel = Reviews::model()->findByAttributes(array('sourceId'=>$order->orderId, 'reviewType'=>'order'));

				$reviewModel = Reviews::find()->where(['sourceId' => $order->orderId])
					->andWhere(['reviewType' => 'order'])->one();



				$result[$key]['review_id'] = empty($reviewModel) ? "0" : $reviewModel->reviewId;
				$result[$key]['rating'] = empty($reviewModel) ? "0" : $reviewModel->rating;
				$result[$key]['review_title'] = empty($reviewModel) ? "" : $reviewModel->reviewTitle;
				$result[$key]['review_des'] = empty($reviewModel) ? "" : $reviewModel->review;
				$result[$key]['created_date'] = empty($reviewModel) ? "" : $reviewModel->createdDate;

				$userdata = Users::findOne($order->sellerId);
				$sellerId = $userdata->userId;
				$sellerName = $userdata->name;
				$sellerUsername = $userdata->username;

				if (!empty($userdata->userImage)) {
					$sellerImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userdata->userImage);
				} else {
					$sellerImage = Yii::$app->urlManagerfrontEnd->baseUrl . '/media/logo/' . yii::$app->Myclass->getDefaultUser();
				}

				$shipping = Shippingaddresses::findOne($order->shippingAddress);
				if (!empty($shipping)) {
					$id = $shipping->shippingaddressId;
					$result[$key]['shippingaddress']['name'] = $shipping->name;
					$result[$key]['shippingaddress']['nickname'] = $shipping->nickname;
					$result[$key]['shippingaddress']['country'] = $shipping->country;
					$result[$key]['shippingaddress']['state'] = $shipping->state;
					$result[$key]['shippingaddress']['address1'] = $shipping->address1;
					$result[$key]['shippingaddress']['address2'] = $shipping->address2;
					$result[$key]['shippingaddress']['city'] = $shipping->city;
					$result[$key]['shippingaddress']['zipcode'] = $shipping->zipcode;
					$result[$key]['shippingaddress']['phone'] = $shipping->phone;
					$result[$key]['shippingaddress']['countrycode'] = $shipping->countryCode;
				}
				if (!empty($order['trackingdetails'])) {
					$result[$key]['trackingdetails']['id'] = $order['trackingdetails']['id'];
					$result[$key]['trackingdetails']['shippingdate'] = $order['trackingdetails']['shippingdate'];
					$result[$key]['trackingdetails']['couriername'] = $order['trackingdetails']['couriername'];
					$result[$key]['trackingdetails']['courierservice'] = $order['trackingdetails']['courierservice'];
					$result[$key]['trackingdetails']['trackingid'] = $order['trackingdetails']['trackingid'];
					$result[$key]['trackingdetails']['notes'] = $order['trackingdetails']['notes'];

				}
				if (!empty($order['orderitems'])) {
					$productId = $order['orderitems'][0]['productId'];
					$check = Products::findOne($productId);
					if (!empty($check)) {
						$productImage = $order['orderitems'][0]->product->photos[0]->name;
					}
					if (!empty($check)) {
						$orderImageUrl = Yii::$app->urlManager->createAbsoluteUrl("media/item/" . $productId . "/" . $productImage);
					} else {
						$orderImageUrl = Yii::$app->urlManager->createAbsoluteUrl("media/item/" . 'default.jpeg');
					}
					$result[$key]['orderitems']['itemid'] = $productId;
					$result[$key]['orderitems']['itemname'] = $order['orderitems'][0]['itemName'];
					$result[$key]['orderitems']['itemname'] = $order['orderitems'][0]['itemName'];
					$result[$key]['orderitems']['seller_name'] = $sellerName;
					$result[$key]['orderitems']['seller_username'] = $sellerUsername;
					$result[$key]['orderitems']['seller_img'] = $sellerImage;
					$result[$key]['orderitems']['seller_id'] = $sellerId;
					$result[$key]['orderitems']['quantity'] = $order['orderitems'][0]['itemQuantity'];
					$result[$key]['orderitems']['price'] = $order['orderitems'][0]['itemPrice'];
						if($_POST['lang_type'] == "ar")
							$result[$key]['orderitems']['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemPrice']);
						else
							$result[$key]['orderitems']['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemPrice']);

					$result[$key]['orderitems']['unitprice'] = $order['orderitems'][0]['itemunitPrice'];
						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_unitprice'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemunitPrice']);
						else
								$result[$key]['orderitems']['formatted_unitprice'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemunitPrice']);

					$result[$key]['orderitems']['size'] = $order['orderitems'][0]['itemSize'];
					$currencysymbol = '';
					if (isset($order['orderitems'][0]->product->currency)) {
						$currencysymbol = $order['orderitems'][0]->product->currency;
					}
					$symbolval = explode("-", $currencysymbol);
					$cSymbol = $symbolval[0];
					$result[$key]['orderitems']['cSymbol'] = $cSymbol;

					$currency_formats = yii::$app->Myclass->getCurrencyFormats($currencysymbol);
					if($currency_formats[0] != "")
							$result[$key]['orderitems']['currency_mode'] = $currency_formats[0];
					if($currency_formats[1] != "")
							$result[$key]['orderitems']['currency_position'] = $currency_formats[1];

					$result[$key]['orderitems']['orderImage'] = $orderImageUrl;
					$result[$key]['orderitems']['shipping_cost'] = $order['orderitems'][0]['shippingPrice'];

						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_shipping_cost'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['shippingPrice']);
						else
									$result[$key]['orderitems']['formatted_shipping_cost'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['shippingPrice']);
					$result[$key]['orderitems']['total'] = $order['orderitems'][0]['shippingPrice'] + $order['orderitems'][0]['itemPrice'];
					if($_POST['lang_type'] == "ar")
						$result[$key]['orderitems']['formatted_total'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$result[$key]['orderitems']['total']);
					else
						$result[$key]['orderitems']['formatted_total'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$result[$key]['orderitems']['total']);
				}
				endforeach;
				if (!empty($result)) {
					$result = Json::encode($result);
					return '{"status":"true","result":' . $result . '}';
				} else {
					return '{"status":"true","message":"No Purchase History Found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}
	/* 
		MY SALES PARAMS -  $api_username, $api_password, $user_id, $offset = 0, $limit = 10
	 */
	public function actionMysales()
	{
		//Post Values  - finished


		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$userid = $_POST['user_id'];


			if ($this->checking($userid)) {
				$offset = 0;
				$limit = 10;

				if (isset($_POST['offset']) && !empty($_POST['offset']))
					$offset = $_POST['offset'];

				if (isset($_POST['limit']) && !empty($_POST['limit']))
					$limit = $_POST['limit'];

					// $criteria = new CDbCriteria;
					// $criteria->addCondition("sellerId = {$userid}");
					// $criteria->order = 'orderId DESC';
					// $criteria->offset = $offset;
					// $criteria->limit = $limit;



				$ordersModel = Orders::find()->with('orderitems', 'trackingdetails')->where(['sellerId' => $userid])
					->orderBy(['orderId' => SORT_DESC])
					->offset($offset)->limit($limit)->all();


					// $ordersModel = Orders::find()->where(['sellerId'=>$userid])
					// 			  ->orderBy(['orderId' => SORT_DESC])
					// 			  ->offset($offset)->limit($limit)->all();

				foreach ($ordersModel as $key => $order) :
					$result[$key]['orderid'] = $order->orderId;
				$result[$key]['price'] = $order->totalCost;
					if($_POST['lang_type'] == "ar")
						$result[$key]['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order->totalCost);
					else
						$result[$key]['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order->totalCost);
				$result[$key]['saledate'] = $order->orderDate;
				$result[$key]['status'] = $order->status;

				if ($order->status == "delivered" || $order->status == "paid")
					$result[$key]['deliverydate'] = $order->statusDate;
				else
					$result[$key]['deliverydate'] = "";


				$invoices = Invoices::find()->where(['orderId' => $order->orderId])->one();
					//print_r($invoices);exit;
				$result[$key]['transaction_id'] = $invoices['paymentTranxid'];
				$result[$key]['payment_type'] = $invoices['paymentMethod'];
				$result[$key]['invoice'] = $invoices['invoiceNo'];

				//	$reviewModel = Reviews::find()->findByAttributes(array('sourceId'=>$order->orderId, 'reviewType'=>'order'));

				$reviewModel = Reviews::find()->where(['sourceId' => $order->orderId])
					->andWhere(['reviewType' => 'order'])->one();


				$result[$key]['review_id'] = empty($reviewModel) ? "0" : $reviewModel->reviewId;
				$result[$key]['rating'] = empty($reviewModel) ? "0" : $reviewModel->rating;
				$result[$key]['review_title'] = empty($reviewModel) ? "" : $reviewModel->reviewTitle;
				$result[$key]['review_des'] = empty($reviewModel) ? "" : $reviewModel->review;
				$result[$key]['created_date'] = empty($reviewModel) ? "" : $reviewModel->createdDate;

				$paymentmodes = yii::$app->Myclass->getSitePaymentModes();
				$claimdays = $paymentmodes['sellerClimbEnableDays'];
				$trackingDetails = Trackingdetails::find()->where(["orderid" => $order->orderId])->one();




				if (!empty($trackingDetails)) {

					$shippingdate = $trackingDetails->shippingdate;
					$shipdate = strtotime("+$claimdays days", $shippingdate);
					$today = time();

				}


				if (isset($shipdate) && $shipdate <= $today && $order->status == "shipped") {

					$result[$key]['claim'] = "true";
				} else {
					$result[$key]['claim'] = "false";
				}



				$userdata = Users::findOne($order->userId);
				$buyerId = $userdata->userId;
				$buyerName = $userdata->name;
				$buyerUsername = $userdata->username;
				$buyerEmail = $userdata->email;
				if (!empty($userdata->userImage)) {
					$buyerImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userdata->userImage);
				} else {
					$buyerImage = Yii::$app->urlManagerfrontEnd->baseUrl . '/media/logo/' . yii::$app->Myclass->getDefaultUser();
				}

				$shipping = Shippingaddresses::findOne($order->shippingAddress);
				if (!empty($shipping)) {
					$id = $shipping->shippingaddressId;
					$result[$key]['shippingaddress']['name'] = $shipping->name;
					$result[$key]['shippingaddress']['nickname'] = $shipping->nickname;
					$result[$key]['shippingaddress']['country'] = $shipping->country;
					$result[$key]['shippingaddress']['state'] = $shipping->state;
					$result[$key]['shippingaddress']['address1'] = $shipping->address1;
					$result[$key]['shippingaddress']['address2'] = $shipping->address2;
					$result[$key]['shippingaddress']['city'] = $shipping->city;
					$result[$key]['shippingaddress']['zipcode'] = $shipping->zipcode;
					$result[$key]['shippingaddress']['phone'] = $shipping->phone;
					$result[$key]['shippingaddress']['countrycode'] = $shipping->countryCode;
				}

				if (!empty($order['trackingdetails'])) {
					$result[$key]['trackingdetails']['id'] = $order['trackingdetails']['id'];
					$result[$key]['trackingdetails']['shippingdate'] = $order['trackingdetails']['shippingdate'];
					$result[$key]['trackingdetails']['couriername'] = $order['trackingdetails']['couriername'];
					$result[$key]['trackingdetails']['courierservice'] = $order['trackingdetails']['courierservice'];
					$result[$key]['trackingdetails']['trackingid'] = $order['trackingdetails']['trackingid'];
					$result[$key]['trackingdetails']['notes'] = $order['trackingdetails']['notes'];

				}
				if (!empty($order['orderitems'])) {
					$productId = $order['orderitems'][0]['productId'];
					$check = Products::findOne($productId);
					if (!empty($check)) {
						$productImage = $order['orderitems'][0]->product->photos[0]->name;
					}
					if (!empty($check)) {
						$orderImageUrl = Yii::$app->urlManager->createAbsoluteUrl("media/item/" . $productId . "/" . $productImage);
					} else {
						$orderImageUrl = Yii::$app->urlManager->createAbsoluteUrl("media/item/" . 'default.jpg');
					}
					$result[$key]['orderitems']['itemid'] = $productId;
					$result[$key]['orderitems']['itemname'] = $order['orderitems'][0]['itemName'];
					$result[$key]['orderitems']['itemname'] = $order['orderitems'][0]['itemName'];
					$result[$key]['orderitems']['buyer_name'] = $buyerName;
					$result[$key]['orderitems']['buyer_username'] = $buyerUsername;
					$result[$key]['orderitems']['buyer_email'] = $buyerEmail;
					$result[$key]['orderitems']['buyer_img'] = $buyerImage;
					$result[$key]['orderitems']['buyer_id'] = $buyerId;
					$result[$key]['orderitems']['quantity'] = $order['orderitems'][0]['itemQuantity'];
					$result[$key]['orderitems']['price'] = $order['orderitems'][0]['itemPrice'];
						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemPrice']);
						else
							$result[$key]['orderitems']['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemPrice']);

					$result[$key]['orderitems']['unitprice'] = $order['orderitems'][0]['itemunitPrice'];
						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_unitprice'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemunitPrice']);
						else
							$result[$key]['orderitems']['formatted_unitprice'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemunitPrice']);

					$result[$key]['orderitems']['size'] = $order['orderitems'][0]['itemSize'];
						//print_r($order['orderitems'][0]);exit;
					$currencysymbol = $order['orderitems'][0]['product']['currency'];
					$symbolval = explode("-", $currencysymbol);
					$cSymbol = $symbolval[0];
					$result[$key]['orderitems']['cSymbol'] = $cSymbol;


					$currency_formats = yii::$app->Myclass->getCurrencyFormats($currencysymbol);
					if($currency_formats[0] != "")
							$result[$key]['orderitems']['currency_mode'] = $currency_formats[0];
					if($currency_formats[1] != "")
							$result[$key]['orderitems']['currency_position'] = $currency_formats[1];

					$result[$key]['orderitems']['orderImage'] = $orderImageUrl;
					$result[$key]['orderitems']['shipping_cost'] = $order['orderitems'][0]['shippingPrice'];
						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_shipping_cost'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['shippingPrice']);
						else
							$result[$key]['orderitems']['formatted_shipping_cost'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['shippingPrice']);
					$result[$key]['orderitems']['total'] = $order['orderitems'][0]['shippingPrice'] + $order['orderitems'][0]['itemPrice'];
						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_total'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$result[$key]['orderitems']['total']);
						else
							$result[$key]['orderitems']['formatted_total'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$result[$key]['orderitems']['total']);
				}
				endforeach;

				if (!empty($result)) {
					$result = Json::encode($result);
					return '{"status":"true","result":' . $result . '}';
					die;
				} else {
					return '{"status":"true","message":"No Sales History Found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}


	/* 
		ORDER DETAILS PARAMS - $api_username, $api_password, $user_id, $order_id, $offset = 0, $limit = 10
	 */
	public function actionOrderdetails()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
			//$recent = $_POST['recent'];
				$order_id = $_POST['order_id'];
				$userid = $user_id;
				$offset = 0;
				$limit = 10;

				if (isset($_POST['offset']) && !empty($_POST['offset']))
					$offset = $_POST['offset'];
				if (isset($_POST['limit']) && !empty($_POST['limit']))
					$limit = $_POST['limit'];

					// $criteria = new CDbCriteria;
					// $criteria->addCondition("userId = {$userid}");
					// $criteria->addCondition("orderId = {$order_id}");
					// $criteria->offset = $offset;
					// $criteria->limit = $limit;
					// $criteria->order = 'orderId DESC'; 
				/*if ($recent == 1){
					$criteria->addCondition("orderDate > {$timeline}");
				}else{
					$criteria->addCondition("orderDate < {$timeline}");
				}*/
				$ordersModel = Orders::find()->with('orderitems', 'trackingdetails')->where(['userId' => $userid])
					->andWhere(['orderId' => $order_id])
					->offset($offset)->limit($limit)
					->orderBy(['orderId' => SORT_DESC])->all();

				// $ordersModel = Orders::find()->where(['userId'=>$userid])
				// 			  ->andWhere(['orderId'=>$order_id])
				// 		      ->offset($offset)->limit($limit)
				// 			  ->orderBy(['orderId' => SORT_DESC])->all();

				foreach ($ordersModel as $key => $order) :
					$result[$key]['orderid'] = $order->orderId;
				$result[$key]['price'] = $order->totalCost;
					if($_POST['lang_type'] == "ar")
						$result[$key]['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order->totalCost);
					else
							$result[$key]['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order->totalCost);
				$result[$key]['saledate'] = $order->orderDate;
				$result[$key]['status'] = $order->status;
				if ($order->status == "delivered")
					$result[$key]['deliverydate'] = $order->statusDate;
				else
					$result[$key]['deliverydate'] = "";

				$invoices = Invoices::find()->where(['orderId' => $order->orderId])->one();
				$result[$key]['transaction_id'] = $invoices->paymentTranxid;

				$sellerdata = Users::findOne($order->sellerId);
				$sellerId = $sellerdata->userId;
				$sellerName = $sellerdata->name;

				$userdata = Users::findOne($order->userId);
				$buyerId = $userdata->userId;
				$buyerName = $userdata->name;

				$shipping = Shippingaddresses::findOne($order->shippingAddress);
				if (!empty($shipping)) {
					$id = $shipping->shippingaddressId;
					$result[$key]['shippingaddress']['name'] = $shipping->name;
					$result[$key]['shippingaddress']['nickname'] = $shipping->nickname;
					$result[$key]['shippingaddress']['country'] = $shipping->country;
					$result[$key]['shippingaddress']['state'] = $shipping->state;
					$result[$key]['shippingaddress']['address1'] = $shipping->address1;
					$result[$key]['shippingaddress']['address2'] = $shipping->address2;
					$result[$key]['shippingaddress']['city'] = $shipping->city;
					$result[$key]['shippingaddress']['zipcode'] = $shipping->zipcode;
					$result[$key]['shippingaddress']['phone'] = $shipping->phone;
					$result[$key]['shippingaddress']['countrycode'] = $shipping->countryCode;
				}
				if (!empty($order['trackingdetails'])) {
					$result[$key]['trackingdetails']['id'] = $order['trackingdetails']['id'];
					$result[$key]['trackingdetails']['shippingdate'] = $order['trackingdetails']['shippingdate'];
					$result[$key]['trackingdetails']['couriername'] = $order['trackingdetails']['couriername'];
					$result[$key]['trackingdetails']['courierservice'] = $order['trackingdetails']['courierservice'];
					$result[$key]['trackingdetails']['trackingid'] = $order['trackingdetails']['trackingid'];
					$result[$key]['trackingdetails']['notes'] = $order['trackingdetails']['notes'];

				}
				if (!empty($order['orderitems'])) {
					$productId = $order['orderitems'][0]['productId'];
					$check = Products::findOne($productId);
					if (!empty($check)) {
						$productImage = $order['orderitems'][0]->product->photos[0]->name;
					}
					if (!empty($check)) {
						$orderImageUrl = Yii::$app->urlManager->createAbsoluteUrl("/media/item/" . $productId . "/" . $productImage);
					} else {
						$orderImageUrl = Yii::$app->urlManager->createAbsoluteUrl("/media/item/" . 'default.jpeg');
					}
					$result[$key]['orderitems']['itemname'] = $order['orderitems'][0]['itemName'];
					$result[$key]['orderitems']['itemname'] = $order['orderitems'][0]['itemName'];
					if ($user_id == $order->userId) {
						$result[$key]['orderitems']['seller_name'] = $sellerName;
						$result[$key]['orderitems']['seller_id'] = $sellerId;
					} else if ($user_id == $order->sellerId) {
						$result[$key]['orderitems']['buyer_name'] = $buyerName;
						$result[$key]['orderitems']['buyer_id'] = $buyerId;
					}
					$result[$key]['orderitems']['quantity'] = $order['orderitems'][0]['itemQuantity'];
					$result[$key]['orderitems']['price'] = $order['orderitems'][0]['itemPrice'];

						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemPrice']);
							else
									$result[$key]['orderitems']['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemPrice']);

					$result[$key]['orderitems']['unitprice'] = $order['orderitems'][0]['itemunitPrice'];

						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_unitprice'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemunitPrice']);
						else
								$result[$key]['orderitems']['formatted_unitprice'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['itemunitPrice']);

					$result[$key]['orderitems']['size'] = $order['orderitems'][0]['itemSize'];
					$result[$key]['orderitems']['cSymbol'] = $order->currency;

					$currency_formats = yii::$app->Myclass->getCurrencyFormat($order->currency);
					if($currency_formats[0] != "")
							$result[$key]['orderitems']['currency_mode'] = $currency_formats[0];
					if($currency_formats[1] != "")
							$result[$key]['orderitems']['currency_position'] = $currency_formats[1];

					$result[$key]['orderitems']['orderImage'] = $orderImageUrl;
					$result[$key]['orderitems']['shipping_cost'] = $order['orderitems'][0]['shippingPrice'];
						if($_POST['lang_type'] == "ar")
								$result[$key]['orderitems']['formatted_shipping_cost'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['shippingPrice']);
						else
								$result[$key]['orderitems']['formatted_shipping_cost'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$order['orderitems'][0]['shippingPrice']);
					$result[$key]['orderitems']['total'] = $order['orderitems'][0]['shippingPrice'] + $order['orderitems'][0]['itemPrice'];
						if($_POST['lang_type'] == "ar")
							$result[$key]['orderitems']['formatted_total'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($order['orderitems'][0]->product->currency,$result[$key]['orderitems']['total']);
						else
							$result[$key]['orderitems']['formatted_total'] = yii::$app->Myclass->getFormattingCurrencyapi($order['orderitems'][0]->product->currency,$result[$key]['orderitems']['total']);



				}
				endforeach;
				if (!empty($result)) {
					$result = Json::encode($result);
					return '{"status":"true","result":' . $result . '}';
				} else {
					return '{"status":"true","message":"No Purchase History Found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET REVIEW PARAMS -  $api_username, $api_password, $user_id, $offset = 0, $limit = 10
	 */
	public function actionGetreview()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];
			$offset = 0;
			$limit = 10;

			if (isset($_POST['offset']) && !empty($_POST['offset']))
				$offset = $_POST['offset'];

			if (isset($_POST['limit']) && !empty($_POST['limit']))
				$limit = $_POST['limit'];

				
			$reviewModel = Reviews::find()->with('orders', 'user')->where(['LIKE', 'reviewType', 'order'])
				->andWhere(['receiverId' => $user_id])
				->orderBy(['reviewId' => SORT_DESC])
				->limit($limit)->offset($offset)->all();


			if (!empty($reviewModel)) {
				foreach ($reviewModel as $key => $review) {
					$reviewDetails[$key]['review_id'] = $review->reviewId;
					$reviewDetails[$key]['rating'] = $review->rating;
					$reviewDetails[$key]['review_title'] = $review->reviewTitle;
					$reviewDetails[$key]['review_des'] = $review->review;
					$reviewDetails[$key]['created_date'] = $review->createdDate;
					$senderDetails = Users::find()->where(['userId' => $review->senderId])->one();
					$reviewDetails[$key]['user_id'] = $senderDetails->userId;
					$reviewDetails[$key]['full_name'] = $senderDetails->name;
					$reviewDetails[$key]['user_image'] = !empty($senderDetails->userImage) ? $userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $senderDetails->userImage) : $userImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/' . yii::$app->Myclass->getDefaultUser());
					$reviewDetails[$key]['item_id'] = $review['orders'][0]['orderitems'][0]->productId;
					$reviewDetails[$key]['item_name'] = $review['orders'][0]['orderitems'][0]->itemName;
				}
				$final = Json::encode($reviewDetails);
				return '{"status":"true","result":' . $final . '}';
			} else {
				return '{"status":"false", "message":"No reviews yet"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		UPDATE REVIEW PARAMS - $api_username, $api_password, $user_id, $seller_id, $review_id, $rating, $review_title, $review_des, $order_id
	 */
	public function actionUpdatereview()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$review_id = $_POST['review_id'];
			$order_id = $_POST['order_id'];

			if ($review_id != 0) {
				$reviewModel = Reviews::findOne($review_id);
			} else {
				$reviewModel = new Reviews();
			}

			$reviewModel->senderId = $_POST['user_id'];
			$reviewModel->receiverId = $_POST['seller_id'];
			$reviewModel->reviewTitle = $_POST['review_title'];
			$reviewModel->review = $_POST['review_des'];
			$reviewModel->rating = $_POST['rating'];
			$reviewModel->reviewType = 'order';
			$reviewModel->sourceId = $order_id;
			$reviewModel->createdDate = time();
			$reviewModel->save(false);

			$orderModel = Orders::findOne($order_id);

			$sellerModel = Users::find()->where(['userId' => $orderModel->sellerId])->one();
			$review = Reviews::find()->select(['avg(rating) as rating'])->where(['receiverId' => $orderModel->sellerId])->one();
			$averageRatting = $review->rating;
			$sellerModel->averageRating = $averageRatting;
			$sellerModel->save(false);

			return '{"status":"true","result":"Review updated successfully"}';
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET COUPON PARAMS -  $api_username, $api_password, $user_id
	 */
	public function actionGetcoupon()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$userId = $_POST['user_id'];

			if ($this->checking($userId)) {
					// $criteria = new CDbCriteria;
					// $criteria->addCondition("sellerId = $userId");
					// $coupons = Coupons::model()->findAll($criteria);
				$coupons = Coupons::find()->where(['sellerId' => $userId])->all();
				if (!empty($coupons)) {
					foreach ($coupons as $key => $value) :
						$coupon[$key]['coupon_id'] = $value->id;
					$coupon[$key]['coupon_code'] = $value->couponCode;
					$coupon[$key]['coupon_value'] = $value->couponValue;
					$coupon[$key]['start_date'] = date("d-M-Y", strtotime($value->startDate));
					$coupon[$key]['end_date'] = date("d-M-Y", strtotime($value->endDate));
					$coupon[$key]['created_date'] = date("d-M-Y", strtotime($value->createdDate));
					$coupon[$key]['status'] = ($value->status == 1) ? 'Available' : 'Expired';
					endforeach;

					$coupon = Json::encode($coupon);
					return '{"status":"true","coupons":' . $coupon . '}';
				} else {
					return '{"status":"true","message":"Coupon Not Found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		CREATE COUPON PARAMS -  $api_username, $api_password, $user_id, $coupon_value, $type, $item_id, $start_date, $end_date, $status, $max_amount = 0
	 */
	public function actionCreatecoupon()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				//Post Values
				$coupon_value = $_POST['coupon_value'];
				$type = $_POST['type'];
				$item_id = $_POST['item_id'];
				$start_date = $_POST['start_date'];
				$end_date = $_POST['end_date'];
				$status = $_POST['status'];
				$max_amount = $_POST['$max_amount'];

				$model = new Coupons();
				$model->sellerId = $user_id;
				$model->couponValue = $coupon_value;

				if ($type == 'item') {
					$model->setScenario('itemView');
					$item = Products::findOne($item_id);
					$model->couponType = 1;
					$model->productId = $item_id;
					$currency = explode('-', $item->currency);
					$model->currency = $currency[0];
				} else {
					$model->setScenario('sellerProfile');
					$model->couponType = 2;
					$model->startDate = date("Y-m-d", strtotime($start_date));
					$model->endDate = date("Y-m-d", strtotime($end_date));
					if ($max_amount != 0) {
						$model->maxAmount = $max_amount;
					}
				}

				if ($status == 'enable') {
					$model->status = 1;
				} else {
					$model->status = 0;
				}
				$model->couponCode = yii::$app->Myclass->getRandomString(8);
				if ($model->save(false)) {
					if ($model->couponType == 1) {
						return '{"status":"true","result":' . $model->couponCode . '}';
					} else {
						$coupon['coupon_id'] = $model->id;
						$coupon['coupon_code'] = $model->couponCode;
						$coupon['coupon_value'] = $model->couponValue;
						$coupon['start_date'] = $model->startDate;
						$coupon['end_date'] = $model->endDate;
						$coupon['created_date'] = date("Y-m-d H:i:s");
						$coupon['status'] = $status;
						$coupon = Json::encode($coupon);
						return '{"status":"true","result":' . $coupon . '}';
					}
				} else {
					return '{"status":"false","message":"Coupon cannot be created"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		COUPON STATUS PARAMS -  $api_username, $api_password, $user_id, $coupon_id
	 */
	public function actionCouponstatus()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$userId = $_POST['user_id'];

			if ($this->checking($userId)) {
				$couponId = $_POST['coupon_id'];
					// $criteria = new CDbCriteria;
					// $criteria->addCondition("id = $couponId");
					// $criteria->addCondition("sellerId = $userId");
				$findCoupon = Coupons::find()->where(['id' => $couponId])
					->andWhere(['sellerId' => $userId])->one();


				if (!empty($findCoupon)) {
					$findCoupon->status = 0;
					$findCoupon->save(false);
					return '{"status":"true","result":"Coupon status changed successfully"}';
				} else {
					return '{"status":"false","result":"No Coupon found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		PROFILE PARAMS -  $api_username, $api_password, $user_id, $user_name = NULL
	 */
	public function actionProfile()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		//	return  '{"status":"true","result":"user found"}';

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];

			if (isset($_POST['user_name'])) {
				$user_name = $_POST['user_name'];
			}


			if ($this->checking($user_id)) {
				$proCriteria = Users::find();
				if ($user_id != 0) {
					$userId = $user_id;
					$proCriteria->andWhere(["userId" => $userId]);
				}
				if (isset($user_name) && $user_name != '') {
					$userName = $user_name;
					$proCriteria->andWhere(["username" => $userName]);
				}
				$model = $proCriteria->one();
					//print_r($model);exit;
				if (!empty($model)) {

					$userDetails['user_id'] = $model->userId;
					$userDetails['user_name'] = $model->username;
					$userDetails['full_name'] = $model->name;
					$userLoc = array();
					$userDetails['city'] = $userLoc[] = trim($model->city);
					$userDetails['state'] = $userLoc[] = trim($model->state);
					$userDetails['country'] = $userLoc[] = trim($model->country); 
					$userDetails['location'] = implode (", ",array_values(array_filter($userLoc)));      

					if (!empty($model->userImage)) {
						$imageUrl = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $model->userImage);
					} else {
						$imageUrl = Yii::$app->urlManagerfrontEnd->baseUrl . '/media/logo/' . yii::$app->Myclass->getDefaultUser();
					}
					$userDetails['user_img'] = $imageUrl;
					$review = Reviews::find()->select(['avg(rating) as rating'])->where(['receiverId' => $model->userId])->one();
					$userDetails['rating'] = $review->rating == "" ? "0.0" : "$review->rating";
					$userDetails['rating_user_count'] = Reviews::find()->where(['receiverId' => $model->userId])->count();
					$userDetails['email'] = $model->email;
					$userDetails['facebook_id'] = $model->facebookId;
					$userDetails['mobile_no'] = $model->phone;
					if ($model->phonevisible == 1) {
						$userDetails['show_mobile_no'] = "true";
					} else {
						$userDetails['show_mobile_no'] = "false";
					}
					if ($model->facebookId == '') {
						$userverdetails['facebook'] = 'false';
					} else {
						$userverdetails['facebook'] = 'true';
					}
					$userverdetails['email'] = 'true';
					if ($model->mobile_status == '1') {
						$userverdetails['mob_no'] = 'true';
					} else {
						$userverdetails['mob_no'] = 'false';
					}

					$userDetails['verification'] = $userverdetails;
					if($model->stripe_details != "" && $model->stripe_details != null)
					   $userDetails['stripe_details'] = Json::decode($model->stripe_details,true);
					else{
						$stripe_det['stripe_privatekey']="";
						$stripe_det['stripe_publickey']="";

						$userDetails['stripe_details'] = $stripe_det;
					}
					$result = Json::encode($userDetails);
					return '{"status":"true","result":' . $result . '}';
				} else {
					return '{"status":"false","result":"No user found"}';
				} 
					//return  '{"status":"true","result":"user found"}';
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		DELETE PRODUCT PARAMS - $api_username, $api_password, $user_id, $item_id
	 */
	public function actionDeleteproduct()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				$itemId = $_POST['item_id'];
				$userId = $user_id;
					// $delCriteria = new CDbCriteria;
					// $delCriteria->addCondition("productId = $itemId");
					// $delCriteria->addCondition("userId = $userId");
					// $productModel = Products::model()->find($delCriteria);

				$productModel = Products::find()->where(['productId' => $itemId])
					->andWhere(['userId' => $userId])->one();


				Adspromotiondetails::deleteAll(['productId' => $itemId]);
				Promotiontransaction::deleteAll(['productId' => $itemId]);

				if (!empty($productModel)) {
					$productModel->delete();
					return '{"status":"true","message":"Product Deleted Successfully"}';
				} else {
					return '{"status":"false","message":"Sorry, Your Product is not deleted try after sometime"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		SEARCH BY ITEM PARAMS - $api_username, $api_password, $item_id, $user_id = 0, $lang_type
	 */
	public function actionSearchbyitem()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$item_id = '';
			if (isset($_POST['item_id'])) {
				$item_id = $_POST['item_id'];
			}
			$lang_type = '';
			if (isset($_POST['lang_type'])) {
				$lang_type = $_POST['lang_type'];
			}
			$user_id = '';
			if (isset($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
			}

			$item = Products::find()->with('user')->where(['productId' => $item_id])->one();

			//	print_r($item);exit;
			  //  $item = Products::findOne($item_id);
			yii::$app->Myclass->push_lang($lang_type);
			$userId = $user_id;
			if (!empty($item)) {
				$itemkey = 0;
				$productId = $item->productId;
				$likedornot = $this->checkuserlike($userId, $productId);
				$items['items'][$itemkey]['id'] = $item->productId;
				$items['items'][$itemkey]['item_title'] = $item->name;
				$items['items'][$itemkey]['item_description'] = html_entity_decode($item->description);


				$productConditionModel = Productconditions::find()->where(['id'=>$item->productCondition])->one();

				$items['items'][$itemkey]['item_condition'] = Yii::t('app', $productConditionModel->condition);
				$items['items'][$itemkey]['item_condition_id'] = Yii::t('app', $productConditionModel->id);
				$items['items'][$itemkey]['price'] = $item->price;
					if($_POST['lang_type'] == "ar")
							$items['items'][$itemkey]['formatted_price'] =  yii::$app->Myclass->arabicgetFormattingCurrencyapi($item->currency,$item->price);
					else
						$items['items'][$itemkey]['formatted_price'] =  yii::$app->Myclass->getFormattingCurrencyapi($item->currency,$item->price);
				$items['items'][$itemkey]['quantity'] = $item->quantity;
				if ($item->quantity > 0 && $item->soldItem == 0) {
					$items['items'][$itemkey]['item_status'] = "onsale";
				} else {
					$items['items'][$itemkey]['item_status'] = "sold";
				}
				$items['items'][$itemkey]['size'] = "M";

				$items['items'][$itemkey]['seller_name'] = $item->user->name;
				$items['items'][$itemkey]['seller_username'] = $item->user->username;
				$items['items'][$itemkey]['seller_id'] = (string)$item->user->userId;
				//$items['items'][$itemkey]['seller_img'] = $item->user->userImage;
				if ($item->user->userImage == "")
					$items['items'][$itemkey]['seller_img'] = Yii::$app->urlManager->createAbsoluteUrl('media/logo/' . yii::$app->Myclass->getDefaultUser());
				else
					$items['items'][$itemkey]['seller_img'] = $userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $item->user->userImage);
				$review = Reviews::find()->select(['avg(rating) as rating'])->where(['receiverId' => $item->user->userId])->one();
				$items['items'][$itemkey]['seller_rating'] = $review->rating;
				$user_review_rating = Reviews::find()->where(['receiverId' => $item->user->userId])->count();
				$items['items'][$itemkey]['rating_user_count'] = $user_review_rating;
				$items['items'][$itemkey]['mobile_no'] = $item->user->phone;
				if ($item->user->phonevisible == "1") {
					$items['items'][$itemkey]['show_seller_mobile'] = "true";
				} else {
					$items['items'][$itemkey]['show_seller_mobile'] = "false";
				}

				if ($item->user->facebookId == '') {
					$items['items'][$itemkey]['facebook_verification'] = 'false';
				} else {
					$items['items'][$itemkey]['facebook_verification'] = 'true';
				}
				if ($item->user->mobile_status == '1') {
					$items['items'][$itemkey]['mobile_verification'] = 'true';
				} else {
					$items['items'][$itemkey]['mobile_verification'] = 'false';
				}

				$items['items'][$itemkey]['email_verification'] = 'true';

				$items['items'][$itemkey]['currency_code'] = $item->currency;

				$currency_formats = yii::$app->Myclass->getCurrencyFormats($item->currency);
				if($currency_formats[0] != "")
						$items['items'][$itemkey]['currency_mode'] = $currency_formats[0];
				if($currency_formats[1] != "")
						$items['items'][$itemkey]['currency_position'] = $currency_formats[1];
				$items['items'][$itemkey]['product_url'] = Yii::$app->urlManager->createAbsoluteUrl('/products/view/' . $item->productId);
				$items['items'][$itemkey]['youtube_link'] = $item->videoUrl;

		// $dec =  yii::$app->Myclass->safe_b64decode($item_id);
		// $user = Yii::$app->user->id;
		// // $spl = explode('-',$dec);
		// // $item_id = $spl[0];
		// if (isset($_POST['user_id'])) {
		// 		$loguser = $_POST['user_id'];
		// 	}


		// 	if($loguser) {
			
		// 	$curruserdetails = Users::find()->where(['userId' => $loguser])->one();
		// 	$loguserdetails = Users::find()->where(['userId' => $loguser])->one();
			
		// 	if(empty($loguserdetails->recently_view_product)) {

		// 		$prodctdata[] = $item_id;
		// 		$prodctdetl = json_encode($prodctdata);
		// 		$loguserdetails->recently_view_product = $prodctdetl;
		// 		$loguserdetails->save(false);
		// 	} else {
		// 		$product_exists = json_decode($loguserdetails->recently_view_product,true);
		// 		if(!in_array($id,$product_exists)) {
		// 			$new_product[] = $item_id;
		// 			$real_products = array_merge($new_product,$product_exists);
		// 			$prodctdata = array_slice($real_products,0,5);
		// 			$prodctdetl = json_encode($prodctdata);
		// 			$loguserdetails->recently_view_product = $prodctdetl;
		// 			$loguserdetails->save(false);
		// 		}
		// 	}
		// 	$prodctIds = json_decode($curruserdetails->recently_view_product,true);
		// 	$product_ids = array_diff($prodctIds,[$id]);

		// 	// $recentlyprodcts = Products::find()->where(['productId'=>$product_ids,'approvedStatus'=>'1'])->limit(4)->all();
		// }
		// echo "<pre>"; print_r($recentlyprodcts); die;


				$items['items'][$itemkey]['likes_count'] = $item->likes;
				$items['items'][$itemkey]['comments_count'] = $item->commentCount;
				$items['items'][$itemkey]['views_count'] = $item->views;
				$items['items'][$itemkey]['liked'] = $likedornot;
//				$items['items'][$itemkey]['posted_time'] = yii::$app->Myclass->getElapsedTime($item->createdDate)." ago";
				$items['items'][$itemkey]['posted_time'] = $item->createdDate;
				$items['items'][$itemkey]['latitude'] = $item->latitude;
				$items['items'][$itemkey]['longitude'] = $item->longitude;
				$items['items'][$itemkey]['location'] = $item->location;
				$items['items'][$itemkey]['best_offer'] = "false";
				$buyType = "";
				if ($item->chatAndBuy) {
					$buyType .= "contactme";
				}
				if ($item->exchangeToBuy) {
					$buyType .= $buyType == "" ? "swap" : ",swap";
				}
				if ($item->instantBuy) {
					$buyType .= $buyType == "" ? "sale" : ",sale";
				}
				$items['items'][$itemkey]['buy_type'] = $buyType;
				$items['items'][$itemkey]['paypal_id'] = $item->paypalid;

				$items['items'][$itemkey]['report'] = "no";
				if ($item->reports != '') {
					$reports = Json::decode($item->reports, true);
					if (in_array($userId, $reports)) {
						$items['items'][$itemkey]['report'] = "yes";
					}
				}

				if (isset($item->category0)) {
					$items['items'][$itemkey]['category_id'] = $item->category0->categoryId;
					$items['items'][$itemkey]['category_name'] = Yii::t('app', $item->category0->name);
				} else {
					$items['items'][$itemkey]['category_id'] = "";
					$items['items'][$itemkey]['category_name'] = "";
				}
				if (isset($item->subCategory0)) {
					$items['items'][$itemkey]['subcat_id'] = (string)$item->subCategory0->categoryId;
					$items['items'][$itemkey]['subcat_name'] = Yii::t('app', $item->subCategory0->name);
				} else {
					$items['items'][$itemkey]['subcat_id'] = "";
					$items['items'][$itemkey]['subcat_name'] = "";
				}
				// third level category
			if (isset($item->sub_subCategory0)) {
				$items['items'][$itemkey]['child_category_id'] = $item->sub_subCategory0->categoryId;
				$items['items'][$itemkey]['child_category_name'] = Yii::t('app', $item->sub_subCategory0->name);
			} else {
				$items['items'][$itemkey]['child_category_id'] = "";
				$items['items'][$itemkey]['child_category_name'] = "";
			}

				$items['items'][$itemkey]['promotion_type'] = 'Normal';
				if ($item->promotionType == '3') {
					$items['items'][$itemkey]['promotion_type'] = "Normal";
				} elseif ($item->promotionType == '1') {
					$items['items'][$itemkey]['promotion_type'] = "Ad";
				} elseif ($item->promotionType == '2') {
					$items['items'][$itemkey]['promotion_type'] = "Urgent";
				}

				/*$items['items'][$itemkey]['exchange_buy'] = '';
				if($item->exchangeToBuy == '0'){
					$items['items'][$itemkey]['exchange_buy'] = "false";
				}else{
					$items['items'][$itemkey]['exchange_buy'] = "true";
				}

				$items['items'][$itemkey]['make_offer'] = '';
				if($item->myoffer == '1' || $item->myoffer == '2'){
					$items['items'][$itemkey]['make_offer'] = "true";
				}else{

					$items['items'][$itemkey]['make_offer'] = "false";
				}*/

				if ($item->instantBuy == "1") {
					$items['items'][$itemkey]['country_id'] = $item->shippingcountry;
					$items['items'][$itemkey]['paypal_id'] = $item->paypalid;
					$items['items'][$itemkey]['shipping_cost'] = $item->shippingCost;
					if($_POST['lang_type'] == "ar")
							$items['items'][$itemkey]['formatted_shipping_cost'] =  yii::$app->Myclass->arabicgetFormattingCurrencyapi($item->currency,$item->shippingCost);
					else
						$items['items'][$itemkey]['formatted_shipping_cost'] =  yii::$app->Myclass->getFormattingCurrencyapi($item->currency,$item->shippingCost);
				} else {
					$items['items'][$itemkey]['country_id'] = "";
					$items['items'][$itemkey]['paypal_id'] = "";
					$items['items'][$itemkey]['shipping_cost'] = "";
				}

				$items['items'][$itemkey]['exchange_buy'] = (string)$item->exchangeToBuy;
				$items['items'][$itemkey]['make_offer'] = (string)$item->myoffer;
				$items['items'][$itemkey]['instant_buy'] = (string)$item->instantBuy;

				if (isset($item->approvedStatus) && $item->approvedStatus == "1") {
					$items['items'][$itemkey]['item_approve'] = "1";
				} else {
					$items['items'][$itemkey]['item_approve'] = "0";
				}

				$items['items'][$itemkey]['shipping_detail'] = array();
				if ($item->instantBuy) {
					$shipKey = 0;
					$shippingArray = array();
					foreach ($item->shippings as $shipping) {
						$shippingArray[$shipKey]['country_id'] = $shipping->countryId;
						$shippingArray[$shipKey]['country_name'] = $shipping->country->country;
						$shippingArray[$shipKey]['shipping_cost'] = $shipping->shippingCost;
						$shipKey++;
					}
					$items['items'][$itemkey]['shipping_detail'] = $shippingArray;
				}


				$items['items'][$itemkey]['photos'] = array();
				foreach ($item->photos as $photo) {
					$photoName = $photo->name;

					$image = Yii::$app->urlManager->createAbsoluteUrl("media/item/" . $productId . '/' . $photoName);
					$photodetails['item_url_main_350'] = Url::base(true).'/resized.php?src='.$image.'&w=300&h=300'; 
					$photodetails['height'] = '350';
					$photodetails['width'] = '350';
					$photodetails['item_url_main_original'] = Yii::$app->urlManager->createAbsoluteUrl('media/item/' . $productId . '/' . $photoName);
					$items['items'][$itemkey]['photos'][] = $photodetails;
				}

				// Filters - AK
				$advFilter = array();   

				$criteria = new Query;
				$criteria->select(['hts_productfilters.id', 'u.filter_id AS super_id', 'u.name AS super_name', 'v.id AS parent_id', 'v.name AS parent_name', 'w.id AS child_id', 'w.name AS child_name', 'hts_productfilters.filter_type', 'hts_productfilters.filter_values', 'hts_filter.value']);    
				$criteria->from('hts_productfilters'); 
				$subQuery =(new Query())->select('*')->from('hts_filtervalues');
				$criteria->leftJoin('hts_filter', 'hts_filter.id = hts_productfilters.level_one'); 
				$criteria->leftJoin(['u' => $subQuery], 'u.filter_id = hts_productfilters.level_one');	
				$criteria->leftJoin(['v' => $subQuery], 'v.id = hts_productfilters.level_two');	
				$criteria->leftJoin(['w' => $subQuery], 'w.id = hts_productfilters.level_three');
				$advFilter[] = "and"; 
				$advFilter[] =  ['or',
										['and',
											['=','hts_productfilters.filter_type','dropdown'],
											['=','hts_productfilters.level_three',0],	
									 	],
									 	['and',
											['=','hts_productfilters.filter_type','multilevel'],
											['>','hts_productfilters.level_one',0],	
											['>','hts_productfilters.level_two',0],	
											['>','hts_productfilters.level_three',0], 
									 	], 
									 	['and',
											['=','hts_productfilters.filter_type','range'],
											['=','hts_productfilters.level_three',0],
									 	] 
									 ];
				$criteria->andWhere(['=', 'hts_productfilters.product_id', $productId]);  
				$criteria->andWhere(['=', 'u.parentid', 0]);  
				$criteria->andWhere($advFilter); 
				$criteria->groupBy('hts_productfilters.id');  
				$filterResult = $criteria->createCommand()->queryAll();  
				$filterDetails = array(); 

				if(count($filterResult) > 0) {
					foreach ($filterResult as $fKey => $valueData) {
						$filterDetails[$fKey]['type'] = $valueData['filter_type'];

						if($valueData['filter_type'] == "dropdown") {
							$filterDetails[$fKey]['parent_id'] = $valueData['super_id'];
							$filterDetails[$fKey]['parent_label'] = $valueData['super_name'];
							$filterDetails[$fKey]['child_id'] = $valueData['parent_id'];
							$filterDetails[$fKey]['child_label'] = $valueData['parent_name'];
						} elseif($valueData['filter_type'] == "multilevel") {
							$filterDetails[$fKey]['parent_id'] = $valueData['super_id'];
							$filterDetails[$fKey]['parent_label'] = $valueData['super_name'];
							$filterDetails[$fKey]['subparent_id'] = $valueData['parent_id'];
							$filterDetails[$fKey]['subparent_label'] = $valueData['parent_name'];
							$filterDetails[$fKey]['child_id'] = $valueData['child_id'];
							$filterDetails[$fKey]['child_label'] = $valueData['child_name'];
						} elseif($valueData['filter_type'] == "range") {
							$filterDetails[$fKey]['parent_id'] = $valueData['super_id'];
							$filterDetails[$fKey]['parent_label'] = $valueData['super_name'];
							$filterDetails[$fKey]['value'] = $valueData['filter_values'];
							$rangeValues = explode(';', $valueData['value']);

							$filterDetails[$fKey]['min_value'] = (isset($rangeValues[0])) ? trim($rangeValues[0]) : 'NULL';
							$filterDetails[$fKey]['max_value'] = (isset($rangeValues[1])) ? trim($rangeValues[1]) : 'NULL';     
						} 
					}
				} 
				$items['items'][$itemkey]['filters'] = $filterDetails;


				$result = Json::encode($items);  
				return '{"status": "true","result":' . $result . '}';
				die;
			} else {
				return '{"status":"false","message":"No item found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		ADMIN DATAS PARAMS -  $api_username, $api_password, $lang_type
	 */  
	public function actionAdmindatas() 
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$lang_type = $_POST['lang_type'];

			yii::$app->Myclass->push_lang($lang_type);
			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

			$paymentModes = "";
			$resultarray = array();
			if (!empty($siteSettings->sitepaymentmodes)) {
				$paymentModes = Json::decode($siteSettings->sitepaymentmodes, true);
				if ($paymentModes['exchangePaymentMode'] == "1")
					$resultarray['exchange'] = 'enable';
				else
					$resultarray['exchange'] = 'disable';
				if ($paymentModes['buynowPaymentMode'] == "1")
					$resultarray['buynow'] = 'enable';
				else
					$resultarray['buynow'] = 'disable';
			} else {
				$resultarray['exchange'] = 'disable';
				$resultarray['buynow'] = 'disable';
			}

			if ($siteSettings->paidbannerstatus == "1")
			$resultarray['paid_banner'] = 'enable';
				else
				$resultarray['paid_banner'] = 'disable';

				if ($siteSettings->site_maintenance_mode == "1"){
					$resultarray['site_maintenance'] = 'enable';
					$resultarray['site_maintenance_text'] = $siteSettings->maintenance_text;
				}
				else
				{
					$resultarray['site_maintenance'] = 'disable';
					$resultarray['site_maintenance_text'] = '';
				}
				

			$resultarray['facebook_appid'] = $siteSettings->fb_appid;
			$resultarray['facebook_secret'] = $siteSettings->fb_secret;

			if ($siteSettings->givingaway == "yes")
				$resultarray['giving_away'] = 'enable';
			else
				$resultarray['giving_away'] = 'disable';


			if ($siteSettings->promotionStatus == "1") {
				$resultarray['promotion'] = 'enable';
			} else {
				$resultarray['promotion'] = 'disable';
			}
			if ($siteSettings->appbannerStatus == "1") {
				$resultarray['banner'] = 'enable';
				
				if($siteSettings->paidbannerstatus == 1) {  
					$currentdate = date("Y-m-d");   
	      	$bannerCondition = ['or',
	      								['and',  // Only Approved Banners
	                              	['<=',DATE_FORMAT(`startdate`,'%Y-%m-%d'), $currentdate],
	                              	['>=',DATE_FORMAT(`enddate`,'%Y-%m-%d'), $currentdate],
	                              	['=','status', 'approved'],
	                          		], 
	                          		['=','status', '']  // Default Banners
														];
					$query = "SELECT * FROM `hts_banners` WHERE (DATE_FORMAT(`startdate`,'%Y-%m-%d') <=  '$currentdate' AND DATE_FORMAT(`enddate`,'%Y-%m-%d') >=  '$currentdate' AND `status` = 'approved') OR  `status` = ''";

	      	$banners = Banners::findBySql($query)->all();  
	    	} else {
	        	$banners = Banners::find()->Where(['status'=>''])->all();
	    	}

				foreach ($banners as $key => $value) {
					$resultarray['bannerData'][$key]['bannerImage'] = Yii::$app->urlManager->createAbsoluteUrl('/frontend/web/media/banners/' . $value->appbannerimage);
					$resultarray['bannerData'][$key]['bannerURL'] = $value->bannerurl;
				}

				if(empty($resultarray['bannerData']))
				{
					$resultarray['banner'] = 'disable';
					$resultarray['bannerData'] = [];	
				}
			} else {
				$resultarray['banner'] = 'disable';
				$resultarray['bannerData'] = [];
			}



			if ($siteSettings->searchType == 'miles') {
				$distanceType = 'mi';
			} else {
				$distanceType = 'km';
			}
			$resultarray['distance_type'] = $distanceType;
		    $resultarray['price_range'] = json_decode($siteSettings->pricerange);

		    $promotionCurrency = $siteSettings->promotionCurrency;
			$promotionCurrency = explode('-', $promotionCurrency);
			$promotionData['currency_symbol'] = $promotionCurrency[1];
			$promotionData['currency_code'] = str_replace(" ", "", $promotionCurrency[0]);


		    $resultarray['admin_currency_code'] = $promotionData['currency_code'];

		    $paymentmodes = yii::$app->Myclass->getSitePaymentModes();
		    $resultarray['admin_payment_type'] = $paymentmodes['bannerPaymenttype'];
		    $stripe_Settings = Json::decode($siteSettings->stripe_settings, true);
		    $resultarray['stripePublicKey'] = $stripe_Settings['stripePublicKey'];
			$resultarray['block'] = "YES";
			//$result = array();
			//$maincategoryModel = Categories::find()->where(['parentCategory' => '0'])->all();
			foreach (json_decode($siteSettings->category_priority) as $catkey => $id) {
				$maincategory = Categories::find()->where(['categoryId' => $id])->one();
				$imageUrl = Yii::$app->urlManager->createAbsoluteUrl('/backend/web/uploads/' . $maincategory->image);
				$resultarray['category'][$catkey]['category_id'] = $maincategory->categoryId;
				$resultarray['category'][$catkey]['category_name'] = Yii::t('app', $maincategory->name);
				$resultarray['category'][$catkey]['category_img'] = $imageUrl;

				$subCategoryResult = array(); 
				$subcategoryModel = Categories::find()->where(['parentCategory' => $maincategory->categoryId])->all(); 
				if(count($subcategoryModel) > 0) {
					foreach ($subcategoryModel as $subkey => $subcategory) {
						$subCategoryResult[$subkey]['sub_id'] = $subcategory->categoryId;
						$subCategoryResult[$subkey]['sub_name'] = Yii::t('app', $subcategory->name);
						$sub_subCategoryResult = array(); 
						$sub_subCategoryResult = Categories::find()->where(['parentCategory' => $subcategory->categoryId])->all(); 
						if(count($sub_subCategoryResult) > 0) {
							foreach ($sub_subCategoryResult as $sub_subkey => $sub_subcategory) {
								$subCategoryResult[$subkey]['child_category'][$sub_subkey]['child_id'] = $sub_subcategory->categoryId;
								$subCategoryResult[$subkey]['child_category'][$sub_subkey]['child_name'] = Yii::t('app', $sub_subcategory->name);
							} 
						} 
					} 
				} 
				$resultarray['category'][$catkey]['subcategory'] = $subCategoryResult;
			}

				// CHAT TEMPLATES

			$resultarray['chat_template'][]['name'] = Yii::t('app', "Hi, I'd like to buy it");
			$resultarray['chat_template'][]['name'] = Yii::t('app', "I'm Interested");
			$resultarray['chat_template'][]['name'] = Yii::t('app', 'Is it Still available');
			$resultarray['chat_template'][]['name'] = Yii::t('app', 'Where we can meet up?');

			return '{"status": "true","result":' . Json::encode($resultarray) . '}';

			//	return  '{"status": "true","result" }';
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}

	}

	/* 
		GET COUNT DETAILS PARAMS - $api_username, $api_password, $user_id
	 */
	public function actionGetcountdetails()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			if (isset($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
			}
			if (isset($user_id) && $user_id != "") {
				$messageCount = yii::$app->Myclass->getMessageCount($user_id);
				$notificationCount = yii::$app->Myclass->getNotificationCount($user_id);
				$resultarray['chatCount'] = $messageCount;
				$resultarray['notificationCount'] = empty($notificationCount) ? 0 : $notificationCount;
				return '{"status":"true","result":' . Json::encode($resultarray) . '}';
			} else {
				return '{"status":"false", "message":"Something went wrong"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		DELETE COMMENT PARAMS - $api_username, $api_password, $user_id, $comment_id, $item_id
	 */
	public function actionDeletecomment()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];
			$comment_id = $_POST['comment_id'];
			if (isset($user_id) && $user_id != "" && isset($comment_id) && $comment_id != "") {
				$commentId = $comment_id;
				$commentModel = Comments::find()->where(['commentId' => $commentId])->one();
				if(!empty($commentModel))
				{
				$commentModel->delete();
				$productModel = Products::find()->where(['productId' => $_POST['item_id']])->one();
				if(!empty($productModel))
				{
				 $commentCount = $productModel->commentCount;
				 $productModel->commentCount = $commentCount - 1;
				 $productModel->save(false);
				}
				}
				else {
					return '{"status":"false", "message":"Something went wrong"}'; die;
				}
				$logsModel = Logs::find()->where(['LIKE', 'type', 'comment'])
					->andWhere(['sourceId' => $commentId])->one();
				if(!empty($logsModel))
				{
					$logsModel->delete();
				}
				

				return '{"status":"true","message":"Comment deleted successfully"}';
				die;
			} else {
				return '{"status":"false", "message":"Something went wrong"}'; die;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}'; die;
		}
	}

	/* 
		GET PROMOTION PARAMS -  $api_username, $api_password
	 */
	public function actionGetpromotion()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$promotionDetails = Promotions::find()->all();

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();;
			$urgentPrice = $siteSettings->urgentPrice;
			$promotionCurrency = $siteSettings->promotionCurrency;
			$promotionCurrency = explode('-', $promotionCurrency);

			$promotionData['urgent'] = $urgentPrice;
				if($_POST['lang_type'] == "ar")
					$promotionData['formatted_urgent'] = yii::$app->Myclass->arabicconvertFormattingCurrencyapi(str_replace(" ", "", $promotionCurrency[0]),$urgentPrice);
				else
						$promotionData['formatted_urgent'] = yii::$app->Myclass->convertFormattingCurrencyapi(str_replace(" ", "", $promotionCurrency[0]),$urgentPrice);
			$promotionData['currency_symbol'] = $promotionCurrency[1];
			
			str_replace(" ", "", $promotionCurrency[0]);
			$promotionData['currency_code'] = $promotionCurrency[0];

			
			$currency_formats = yii::$app->Myclass->getCurrencyFormat(str_replace(" ", "", $promotionCurrency[0]));
			if($currency_formats[0] != "")
					$promotionData['currency_mode'] = $currency_formats[0];
			if($currency_formats[1] != "")
					$promotionData['currency_position'] = $currency_formats[1];
			$promotionData['other_promotions'] = array();

			foreach ($promotionDetails as $promotionDetailKey => $promotionDetail) {
				$promotionData['other_promotions'][$promotionDetailKey]['id'] = $promotionDetail->id;
				$promotionData['other_promotions'][$promotionDetailKey]['name'] = $promotionDetail->name;
				$promotionData['other_promotions'][$promotionDetailKey]['price'] = $promotionDetail->price;
					if($_POST['lang_type'] == "ar")
							$promotionData['other_promotions'][$promotionDetailKey]['formatted_price'] = yii::$app->Myclass->arabicconvertFormattingCurrencyapi(str_replace(" ", "", $promotionCurrency[0]),$promotionDetail->price);
					else
							$promotionData['other_promotions'][$promotionDetailKey]['formatted_price'] = yii::$app->Myclass->convertFormattingCurrencyapi(str_replace(" ", "", $promotionCurrency[0]),$promotionDetail->price);
				$promotionData['other_promotions'][$promotionDetailKey]['days'] = $promotionDetail->days;
			}
			return '{"status": "true","result":' . Json::encode($promotionData) . '}';
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		MY PROMOTIONS PARAMS -  $api_username, $api_password, $user_id, $type
	 */
	public function actionMypromotions()
	{
		//Post Values - image path need to correct
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				$type = '';
				if (isset($_POST['type'])) {
					$type = strtolower($_POST['type']);
				}


				if ($type == 'urgent') {
						// $criteria = new CDbCriteria;
						// $criteria->addCondition("userId = $user_id");
						// $criteria->addCondition("promotionType = '2'");
						// $criteria->order = 'productId DESC';
						// $products = Products::model()->findAll($criteria);

					$products = Products::find()->where(['userId' => $user_id])
						->andWhere(['promotionType' => 2])
						->orderBy(['productId' => SORT_DESC])->all();


				} elseif ($type == 'ad') {
						// $criteria = new CDbCriteria;
						// $criteria->addCondition("userId = $user_id");
						// $criteria->addCondition("promotionType = '1'");
						// $criteria->order = 'productId DESC';
						// $products = Products::model()->findAll($criteria);

					$products = Products::find()->where(['userId' => $user_id])
						->andWhere(['promotionType' => 1])
						->orderBy(['productId' => SORT_DESC])->all();


				} elseif ($type == 'expire') {
						// $criteria = new CDbCriteria;
						// $criteria->addCondition("userId = $user_id");
						// $criteria->addCondition("promotionType = '3'");
						// $criteria->order = 'productId DESC';
						// $products = Products::model()->findAll($criteria);

					$products = Products::find()->where(['userId' => $user_id])
						->andWhere(['promotionType' => 3])
						->orderBy(['productId' => SORT_DESC])->all();
				}
				//var_dump($products);
				if (!empty($products)) {
					$key = 0;
					foreach ($products as $product) {
							//print_r($product);exit;
						$productId = $product->productId;
						$product_criteria = Promotiontransaction::find();
						$product_criteria->andWhere(["productId" => $productId]);

						if ($type == 'ad') {
							$product_criteria->andWhere(["status" => 'Live']);
						} elseif ($type == 'expire') {
							$product_criteria->andWhere(["status" => 'Expired']);
						}
						$product_criteria->orderBy(['id' => SORT_DESC]);
						$promot_detail = $product_criteria->one();
						if (!empty($promot_detail)) {
							$promotions[$key]['id'] = $promot_detail->id;
							$promotions[$key]['promotion_name'] = $promot_detail->promotionName;
							$promotions[$key]['paid_amount'] = $promot_detail->promotionPrice;
							if($_POST['lang_type'] == "ar")
								$promotions[$key]['formatted_paid_amount'] =  yii::$app->Myclass->arabicconvertFormattingCurrencyapi($promot_detail->promotionCurrency,$promot_detail->promotionPrice);
							else
									$promotions[$key]['formatted_paid_amount'] =  yii::$app->Myclass->convertFormattingCurrencyapi($promot_detail->promotionCurrency,$promot_detail->promotionPrice);
							$currency = '';
							$currency = explode('-', $product->currency);
							$promotions[$key]['currency_symbol'] = $currency[0];
							$promotions[$key]['currency_code'] = $currency[1];

							$currency_formats = yii::$app->Myclass->getCurrencyFormat($currency[1]);
							if($currency_formats[0] != "")
									$promotions[$key]['currency_mode'] = $currency_formats[0];
							if($currency_formats[1] != "")
									$promotions[$key]['currency_position'] = $currency_formats[1];

							$start_date = date("M d Y", $promot_detail->createdDate);
							$end_date = date("M d Y", strtotime("+" . $promot_detail->promotionTime . "  days", $promot_detail->createdDate));

							$promotions[$key]['upto'] = strtotime($start_date) . ' - ' . strtotime($end_date);
							$promotions[$key]['transaction_id'] = $promot_detail->tranxId;
							$promotions[$key]['status'] = $promot_detail->status;
							$promotions[$key]['item_id'] = $product->productId;
							$promotions[$key]['item_name'] = $product->name;
								//$promotions[$key]['item_image'] = Yii::$app->urlManager->createAbsoluteUrl('media/item/'.$product->productId.'/'.$product->photos['0']->name);
							$promotions[$key]['item_image'] = $product->photos['0']->name;

								//print_r($promotions[$key]['item_image']);exit;

							if (isset($product->approvedStatus) && $product->approvedStatus == 1) {
								$promotions[$key]['item_approve'] = 1;
							} else {
								$promotions[$key]['item_approve'] = 0;
							}

							$key++;
							// foreach ($product->photos as $photo){
							// 	$photoName = $photo->name;
							// 	$promotions[$key]['item_image'] = $photoName;
							// 	//break;
							// }
						}
					}
					if (!empty($promotions)) {
						$promotion_details = Json::encode($promotions);
						return '{"status": "true", "result":' . $promotion_details . '}';
					} else {
						return '{"status":"false", "message":"No data found"}';
					}
				} else {
					return '{"status":"false", "message":"No data found"}';
				}

			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		NOTIFICATION PARAMS - $api_username, $api_password, $userId, $offset = 0, $limit = 20
	 */
	public function actionNotification()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$userId = $_POST['userId'];
			$model = Users::findOne($userId);

			if ($model->unreadNotification != 0) {
				$model->unreadNotification = 0;
				$model->save(false);
			}

			$followersModel = Followers::find()->where(['userId' => $userId])->all();

			$followers = array();

			foreach ($followersModel as $follower) {
				$followers[] = $follower->follow_userId;
			}
				//$followers = Json::encode($followers);

			$offset = 0;
			$limit = 20;

			if (isset($_POST['offset']) && !empty($_POST['offset']))
				$offset = $_POST['offset'];

			if (isset($_POST['limit']) && !empty($_POST['limit']))
				$limit = $_POST['limit'];

			$criteria = Logs::find();
			$criteria->andWhere(['userid' => $followers]);
			$criteria->andWhere(['LIKE', 'type', 'add']);
			$criteria->orWhere(["notifyto" => $userId]);
			$criteria->orWhere(['type' => 'admin']);
			$criteria->andWhere([">", "createddate", $model->created_at]);
			$criteria->orderBy(['id' => SORT_DESC]);
			$criteria->limit($limit);
			$criteria->offset($offset);

			$logModel = $criteria->all();

				// $logModel = Logs::find()->andWhere(['userid'=>$followers])
				// 		   ->andWhere(['LIKE','type','add'])
				// 		->orWhere(['notifyto'=>$userId,'type'=>'Admin'])
				// 		->orWhere(['>','createddate',$model->created_at])
				// 		->orderBy(['id' => SORT_DESC])
				// 		->limit($limit)->offset($offset)->all();


			$notificationData = array();

			if (!empty($logModel)) {
				foreach ($logModel as $logKey => $log) {
					$productModel = array();
					if ($log->itemid != 0) {
						$productModel = yii::$app->Myclass->getProductDetails($log->itemid);
							//print_r($productModel);exit;
					}
					$userModel = yii::$app->Myclass->getUserDetailss($log->userid);
					if (!empty($userModel->userImage)) {
						$userImage = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userModel->userImage);
					} else {
						$userImage = Yii::$app->urlManager->createAbsoluteUrl('media/logo/' . yii::$app->Myclass->getDefaultUser());
					}
					//$createdDate = date('jS M Y', $log->createddate);

					$notificationData[$logKey]['type'] = $log->type;
					$notificationData[$logKey]['message'] = $log->notifymessage;
					$notificationData[$logKey]['event_time'] = $log->createddate;
					$notificationData[$logKey]['user_image'] = $userImage;
					if ($log->type === 'admin') {
						$notificationData[$logKey]['message'] = $log->message;
					}
					if ($log->type !== 'admin') {
						if ($log->type == 'banner') {
							$banner = Banners::find()->where(['id' => $log->sourceid])->one(); 
							$notificationData[$logKey]['banner_id'] = $log->sourceid;
							$notificationData[$logKey]['start_date'] = $banner->startdate;
							$notificationData[$logKey]['end_date'] = $banner->enddate;
							$notificationData[$logKey]['posted_date'] = $banner->createdDate;
							$notificationData[$logKey]['approve_status'] = $banner->status;
							if($banner->status == '0')
							{
								$notificationData[$logKey]['approve_status'] = 'Pending';
							}
							$now = date("Y-m-d");
							if(date_format(date_create($banner->enddate),"Y-m-d") < $now )
							{
								$notificationData[$logKey]['approve_status'] = 'Expired';
							}
							$cur_sym = Currencies::find()->where(['currency_shortcode' => $banner->currency])->one();
							$notificationData[$logKey]['currency_symbol'] = $cur_sym->currency_symbol;
							$notificationData[$logKey]['currency_code'] = $banner->currency;

							$currency_formats = yii::$app->Myclass->getCurrencyFormat($banner->currency);
							if($currency_formats[0] != "")
									$notificationData[$logKey]['currency_mode'] = $currency_formats[0];
							if($currency_formats[1] != "")
									$notificationData[$logKey]['currency_position'] = $currency_formats[1];

							$notificationData[$logKey]['price'] = $banner->totalCost;
								if($_POST['lang_type'] == "ar")
									$notificationData[$logKey]['formatted_price'] = yii::$app->Myclass->arabicconvertFormattingCurrencyapi($banner->currency,$banner->totalCost);
								else
										$notificationData[$logKey]['formatted_price'] = yii::$app->Myclass->convertFormattingCurrencyapi($banner->currency,$banner->totalCost);
							$notificationData[$logKey]['transaction_id'] = $banner->tranxId;
							$notificationData[$logKey]['app_banner_url'] = Yii::$app->urlManager->createAbsoluteUrl('media/banners/' . $banner->bannerimage);
							$notificationData[$logKey]['web_banner_url'] = Yii::$app->urlManager->createAbsoluteUrl('media/banners/' . $banner->appbannerimage);
						}
						else{
							$notificationData[$logKey]['user_id'] = $log->userid;
							$notificationData[$logKey]['user_name'] = $userModel['name'];
						}
						if (!empty($productModel)) {
							$notificationData[$logKey]['item_id'] = $productModel->productId;
							$notificationData[$logKey]['item_title'] = $productModel->name;

							if (isset($productModel->photos[0])) {
								$productImage = Yii::$app->urlManager->createAbsoluteUrl('media/item/' . $productModel->productId .
									'/' . $productModel->photos[0]->name);
							} else {
								$productImage = Yii::$app->urlManager->createAbsoluteUrl('media/item/default.jpg');
							}
							$notificationData[$logKey]['item_image'] = $productImage;
						}
					}

				}
					//print_r($notificationData);exit;
				return '{"status": "true","result":' . Json::encode($notificationData) . '}';
				die;
			} else {
				return '{"status":"false","message":"No notifications found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET CHAT ID PARAMS -  $api_username, $api_password, $sender_id, $receiver_id
	 */
	public function actionGetchatid()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$senderId = $_POST['sender_id'];

			if ($this->checking($senderId)) {
				$receiverId = $_POST['receiver_id'];

				//	$chatCriteria = new CDbCriteria;
				$sql = "select * from `hts_chats` where `user1` = '$senderId' AND `user2` = '$receiverId' OR `user1` = '$receiverId' AND `user2` = '$senderId'";
				$chat = Chats::findBySql($sql)->one();

				if (empty($chat)) {
					$newChat = new Chats();
					$newChat->user1 = $senderId;
					$newChat->user2 = $receiverId;
					$newChat->lastContacted = time();
					$newChat->save(false);
					$chatId = $newChat->chatId;
				} else {
					$chatId = $chat->chatId;
				}
				if (!empty($chatId)) {
					return '{"status": "true","chat_id":' . $chatId . '}';
				} else {
					return '{"status": "false","message":"Chat id cannot be created"}';
				}
			}

		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET CHAT PARAMS - $api_username, $api_password, $sender_id, $receiver_id, $type,$source_id, $offset = 0, $limit = 20
	 */
	public function actionGetchat()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$sender_id = $_POST['sender_id'];
			if ($this->checking($sender_id)) {
				$receiverId = $_POST['receiver_id'];
				$senderId = $sender_id;
				$type = '';
				if (isset($_POST['type'])) {
					$type = $_POST['type'];
				}
				if (isset($_POST['source_id'])) {
					$sourceId = $_POST['source_id'];
				} else {
					$sourceId = 0;
				}
				$offset = 0;
				$limit = 20;
				if (isset($_POST['offset']) && !empty($_POST['offset']))
					$offset = $_POST['offset'];
				if (isset($_POST['limit']) && !empty($_POST['limit']))
					$limit = $_POST['limit'];

				//$chatCriteria = new CDbCriteria;
				$chatCriteria = "select * from `hts_chats` where `user1` = '$senderId' AND `user2` = '$receiverId' OR `user1` = '$receiverId' AND `user2` = '$senderId'";
				$chat = Chats::findBySql($chatCriteria)->one();



				if (!empty($chat)) {


					if ($type == 'normal') {
						if ($chat->lastToRead != 0 && $chat->lastToRead == $sender_id) {
							$chat->lastToRead = 0;
							$chat->save(false);
						}


						$messageType[] = 'normal';
						$messageType[] = 'offer';
							// $messageModel = Messages::model()->findAllByAttributes(array('chatId'=>$chat->chatId,
							// 	'messageType'=>$messageType),array('order' => 'messageId DESC','limit' => $limit,'offset' => $offset));


						$messageModel = Messages::find()->where(['chatId' => $chat->chatId])
							->andWhere(['messageType' => $messageType])
							->orderBy(['messageId' => SORT_DESC])
							->limit($limit)
							->offset($offset)
							->all();


					} else {
							// $messageModel = Messages::model()->findAllByAttributes(array('chatId'=>$chat->chatId,'sourceId'=> $sourceId,
							// 	'messageType'=>$type),array('order' => 'messageId DESC','limit' => $limit,'offset' => $offset));



						$messageModel = Messages::find()->where(['chatId' => $chat->chatId])
							->andWhere(['sourceId' => $sourceId])
							->andWhere(['messageType' => $type])
							->orderBy(['messageId' => SORT_DESC])
							->limit($limit)
							->offset($offset)
							->all();


					}
					$chats = array();
					
					if (!empty($messageModel)) {
						foreach ($messageModel as $key => $message) :
							$senderDetails = yii::$app->Myclass->getUserDetailss($message->senderId);
						if ($chat->user1 == $message->senderId) {
							$receiver = $chat->user2;
						} else {
							$receiver = $chat->user1;
						}
						$receiverDetails = yii::$app->Myclass->getUserDetailss($receiver);
						$chats['chats'][$key]['receiver'] = $receiverDetails->username;
						$chats['chats'][$key]['sender'] = $senderDetails->username;
						if ($message->sourceId != 0 && $message->messageType != 'exchange') {
							$chatSourceItem = yii::$app->Myclass->getProductDetails($message->sourceId);
							if ($chatSourceItem != "") {
								$itemDelStatus = 1;
							} //1-item active,0- item deleted 
							else {
								$itemDelStatus = 0;
							}
								//print_r($chatSourceItem);exit;
							if ($message->messageType == 'normal') {
								$chats['chats'][$key]['type'] = "about";
								$chats['chats'][$key]['item_status'] = $itemDelStatus;

							} elseif ($message->messageType == 'offer') {
								$chats['chats'][$key]['type'] = "offer";
								$chats['chats'][$key]['item_status'] = $itemDelStatus;
								$offerDetails = Json::decode($message->message, true);
								$offerCurrency = explode('-', $offerDetails['currency']);
								$chats['chats'][$key]['offer_price'] = $offerDetails['price'];
									if($_POST['lang_type'] == "ar")
										$chats['chats'][$key]['formatted_offer_price'] =  yii::$app->Myclass->arabicconvertFormattingCurrencyapi($offerCurrency[1],$offerDetails['price']);
									else
											$chats['chats'][$key]['formatted_offer_price'] =  yii::$app->Myclass->convertFormattingCurrencyapi($offerCurrency[1],$offerDetails['price']);
								if($chatSourceItem['shippingCost'] > 0)
										$total_offer_price = $offerDetails['price'] + $chatSourceItem['shippingCost'];
								else
										$total_offer_price = $offerDetails['price'];

									if($_POST['lang_type'] == "ar")
										$chats['chats'][$key]['formatted_shipping_price'] =  yii::$app->Myclass->arabicconvertFormattingCurrencyapi($offerCurrency[1],$chatSourceItem['shippingCost']);
									else
											$chats['chats'][$key]['formatted_shipping_price'] =  yii::$app->Myclass->convertFormattingCurrencyapi($offerCurrency[1],$chatSourceItem['shippingCost']);

								$chats['chats'][$key]['total_offer_price'] = $total_offer_price;
									if($_POST['lang_type'] == "ar")
								      $chats['chats'][$key]['formatted_total_offer_price'] =  yii::$app->Myclass->arabicconvertFormattingCurrencyapi($offerCurrency[1],$total_offer_price);
								  else
								  	 $chats['chats'][$key]['formatted_total_offer_price'] =  yii::$app->Myclass->convertFormattingCurrencyapi($offerCurrency[1],$total_offer_price);
								$chats['chats'][$key]['offer_currency'] = $offerCurrency[0];
								$chats['chats'][$key]['offer_currency_code'] = $offerCurrency[1];

								$currency_formats = yii::$app->Myclass->getCurrencyFormat($offerCurrency[1]);
								if($currency_formats[0] != "")
										$chats['chats'][$key]['currency_mode'] = $currency_formats[0];
								if($currency_formats[1] != "")
										$chats['chats'][$key]['currency_position'] = $currency_formats[1];

								$chats['chats'][$key]['offer_id'] = $message->messageId; //message id
								$chats['chats'][$key]['offer_type'] = $offerDetails['type']; // acept,decline,sendreceive
								$chats['chats'][$key]['offer_status'] = $offerDetails['offerstatus']; // 0-pending,1-accept,2-decline
								$chats['chats'][$key]['buynow_status'] = $offerDetails['buynowstatus']; // offer is buy or not ,0-pending,1-alreadybought
								if (isset($chatSourceItem)) {
									$chats['chats'][$key]['seller_id'] = $chatSourceItem->productId;
									$chats['chats'][$key]['instant_buy'] = $chatSourceItem->instantBuy;
								}
								$chats['chats'][$key]['chat_id'] = $message->chatId;

							}
							$chats['chats'][$key]['item_id'] = $message->sourceId;
							if (isset($chatSourceItem)) {
								$chats['chats'][$key]['item_title'] = $chatSourceItem->name;
							}
							if (isset($chatSourceItem->photos[0])) {
								$chats['chats'][$key]['item_image'] = Yii::$app->urlManager->createAbsoluteUrl(
									'/media/item/' . $chatSourceItem->productId .
										'/' . $chatSourceItem->photos[0]->name
								);
							} else {
								$chats['chats'][$key]['item_image'] = Yii::$app->urlManager->createAbsoluteUrl('/media/item/default.jpg');
							}
						} else {
							$chats['chats'][$key]['type'] = "message";
						}
						$chats['chats'][$key]['message']['userName'] = $receiverDetails->username;
						if (!empty($receiverDetails->userImage)) {
							$currentChatUserImage = $receiverDetails->userImage;
						} else {
							$currentChatUserImage = yii::$app->Myclass->getDefaultUser();
						}
						$chats['chats'][$key]['message']['userImage'] = $currentChatUserImage;
						$chats['chats'][$key]['message']['chatTime'] = $message->createdDate;
						if ($message->messageType == 'offer') {
							$chats['chats'][$key]['message']['message'] = $offerDetails['message'];
						} else {
							$chats['chats'][$key]['message']['message'] = urldecode($message->message);
							//return  $message->messageContent;die;
						//	if($message->messageType != 'exchange'){
							if ($message->messageContent == 1) {//vaishnavi
								$chats['chats'][$key]['message']['message'] = urldecode($message->message);
								$chats['chats'][$key]['message']['imageName'] = "";
								if ($message->sourceId != 0 && $message->messageType != 'exchange') {
									$chatSourceItem = yii::$app->Myclass->getProductDetails($message->sourceId);
									if ($chatSourceItem != "") {
										$itemDelStatus = 1;
									} //1-item active,0- item deleted 
									else {
										$itemDelStatus = 0;
									}
									$chats['chats'][$key]['type'] = "about";
									$chats['chats'][$key]['item_status'] = $itemDelStatus;
								} else {
									$chats['chats'][$key]['type'] = "normal";
								}
							} else if ($message->messageContent == 2) {
								$chats['chats'][$key]['message']['upload_image'] = Yii::$app->urlManager->createAbsoluteUrl('images/message/' . $message->message);
								$chats['chats'][$key]['type'] = "image";
							} else if ($message->messageContent == 3) {
								$latLongArr = explode("@#@", $message->message);
								$chats['chats'][$key]['message']['latitude'] = $latLongArr[0];
								$chats['chats'][$key]['message']['longitude'] = $latLongArr[1];
								$chats['chats'][$key]['type'] = "share_location";
							}
						}
						endforeach;
						if ($chat->blockedUser != "" && $chat->blockedUser != 0) {
							if ($senderId == $chat->blockedUser) {
								$block = "true";
								$blocked_by_me = "false";
							} elseif ($receiverId == $chat->blockedUser) {
								$block = "false";
								$blocked_by_me = "true";
							} else {
								$block = "false";
								$blocked_by_me = "false";
							}
						} else {
							$block = "false";
							$blocked_by_me = "false";
						}

						$chatURL = Yii::$app->urlManager->createAbsoluteUrl("/message/" . yii::$app->Myclass->safe_b64encode($senderDetails->userId . '-0'));
						return '{"status": "true","chat_id":' . $chat->chatId . ', "chat_url":"' . $chatURL . '", "block":"' . $block . '", "blocked_by_me":"' . $blocked_by_me . '", "chats":' . Json::encode($chats) . '}';
						die;
					} else {
						if ($chat->blockedUser != "" && $chat->blockedUser != 0) {
							if ($senderId == $chat->blockedUser) {
								$block = "true";
								$blocked_by_me = "false";
							} elseif ($receiverId == $chat->blockedUser && $senderId != $chat->blockedUser) {
								$block = "true";
								$blocked_by_me = "true";
							} else {
								$block = "false";
								$blocked_by_me = "false";
							}
						} else {
							$block = "false";
							$blocked_by_me = "false";
						}

						return '{"status": "false","message":"No Chat History Found", "block":"' . $block . '", "blocked_by_me":"' . $blocked_by_me . '"}';
						die;
					}
				}
				 else {
			return '{"status":"false", "message":"No chats found"}';
		}
			}
			 else {
			return '{"status":"false", "message":"No chats found"}';
		}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		SEND CHAT PARAMS -  $api_username, $api_password, $sender_id, $chat_id, $type, $message, $created_date = 0, $source_id, $current_latitude=0, $current_longitude=0, $image_url, $chat_type
	 */
	public function actionSendchat()
	{
			//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$sender_id = $_POST['sender_id'];
			if ($this->checking($sender_id)) {

				$chat_id = $_POST['chat_id'];
				$chataction = yii::$app->Myclass->getChatBlockValue($chat_id);

				if ($chataction == '0') {
					$type = $_POST['type'];
					$message = $_POST['message'];
					if (isset($_POST['created_date'])) {
						$created_date = $_POST['created_date'];
					}
					$sourceId = '';
					if (isset($_POST['source_id'])) {
						$sourceId = $_POST['source_id'];
					}
					if (isset($_POST['current_latitude'])) {
						$current_latitude = $_POST['current_latitude'];
					}
					if (isset($_POST['current_longitude'])) {
						$current_longitude = $_POST['current_longitude'];
					}
					if (isset($_POST['image_url'])) {
						$image_url = $_POST['image_url'];
					}
					$chat_type = '';
					if (isset($_POST['chat_type'])) {
						$chat_type = $_POST['chat_type'];
					}

					$senderId = $sender_id;
					$chatId = $chat_id;
					$messageType = $type;

					if ($created_date != 0) {
						$createdDate = $created_date;
					} else {
						$createdDate = time();
					}

					if ($type == 'share_location' && $chat_type == 'normal') {
						$messageContent = 3;
						$message = $current_latitude . '@#@' . $current_longitude;
						$messageType = 'normal';
					} else if ($type == 'image' && $chat_type == 'normal') {
						$messageContent = 2;
						$message = $image_url;
						$messageType = 'normal';
					} elseif ($type == 'share_location' && $chat_type == 'exchange') {
						$messageContent = 3;
						$message = $current_latitude . '@#@' . $current_longitude;
						$messageType = 'exchange';
					} else if ($type == 'image' && $chat_type == 'exchange') {
						$messageContent = 2;
						$message = $image_url;
						$messageType = 'exchange';
					} else {
						$messageContent = 1;
						$messageType = $chat_type;

					}

					$messageModel = new Messages();
					$messageModel->message = $message;
					$messageModel->messageType = $messageType;
					$messageModel->senderId = $senderId;
					$messageModel->sourceId = $sourceId;
					$messageModel->chatId = $chatId;
					$messageModel->messageContent = $messageContent;
					$messageModel->createdDate = $createdDate;
					$messageModel->save(false);
					$chatModel = Chats::findOne($chatId);
					$chatModel->lastContacted = $createdDate;
					if ($chatModel->user1 == $senderId) {
						$chatModel->lastToRead = $chatModel->user2;
					} else {
						$chatModel->lastToRead = $chatModel->user1;
					}
					if ($messageType == 'share_location')
						$chatModel->lastMessage = "Share an Location";
					else if ($messageType == 'image')
						$chatModel->lastMessage = "Share an Image";
					else if ($messageType != 'exchange')
						$chatModel->lastMessage = $message;

					if ($chat_type != 'exchange') {
						$chatModel->save();
					}

					if ($sourceId != 0 && $messageType == "normal") {
						$userid = $chatModel->user1;
						if ($chatModel->user2 != $sender_id) {
							$userid = $chatModel->user2;
						}
						$sellerDetails = yii::$app->Myclass->getUserDetailss($sender_id);
						$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->one();
						$notifyMessage = 'contacted you on your product';
						yii::$app->Myclass->addLogs("myoffer", $senderId, $userid, $sourceId, $sourceId, $notifyMessage);

					}

					if (($messageType == "normal") || ($messageType == "image") || ($messageType == "exchange")) {
						$userid = $chatModel->lastToRead;
						$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
						$sellerDetails = yii::$app->Myclass->getUserDetailss($sender_id);
						if (count($userdevicedet) > 0) {
							foreach ($userdevicedet as $userdevice) {
								$deviceToken = $userdevice->deviceToken;
								$badge = $userdevice->badge;
								$badge += 1;
								$userdevice->badge = $badge;
								$userdevice->deviceToken = $deviceToken;
								$userdevice->save(false);
								if (isset($deviceToken)) {

									if ($messageType == "exchange" && $type == "image")
										$messages = $sellerDetails->name .Yii::t('app', ' : shared an image in exchange request');
									elseif ($messageType == "exchange" && $type == "share_location")
										$messages = $sellerDetails->name .Yii::t('app', ' : shared a location in exchange request');
									elseif ($type == "image")
										$messages = $sellerDetails->name .Yii::t('app', ' : Send an Image');
									elseif ($type == "share_location")
										$messages = $sellerDetails->name . Yii::t('app', ' : Share a location with you');
									else
										$messages = $sellerDetails->name . " : " . $message;

									yii::$app->Myclass->pushnot($deviceToken, $messages, $badge, $chat_type);
								}
							}
						}
					}
					return '{"status": "true","message":"Message sent successfully"}';
					die;
				} else {
					return '{"status":"false", "message":"Your chat is blocked"}';
					die;
				}
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET LIKED ID PARAMS -  $api_username, $api_password,$user_id
	 */
	public function actionGetlikedid()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$userId = $_POST['user_id'];
			$userModel = Favorites::find()->where(['userId' => $userId])->all();
			if (!empty($userModel)) {
				foreach ($userModel as $user) {
					$userdetails[] = $user->productId;
				}
				$userdetails = Json::encode($userdetails);
				return '{"status": "true","result":' . $userdetails . '}';
			} else {
				return '{"status":"false", "message":"No data found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET FOLLOWER ID PARAMS - $api_username, $api_password, $user_id 
	 */
	public function actionGetfollowerid()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$userId = $_POST['user_id'];
			$userModel = Followers::find()->where(['userId' => $userId])->all();
			if (!empty($userModel)) {
				foreach ($userModel as $user) {
					$userdetails[] = (string)$user->follow_userId;
				}
				$userdetails = Json::encode($userdetails);
				return '{"status": "true","result":' . $userdetails . '}';
			} else {
				return '{"status":"false", "message":"No data found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		SOLD ITEM PARAMS - $api_username, $api_password, $value, $item_id
	 */
	public function actionSolditem()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$id = $_POST['item_id'];
			$value = $_POST['value'];

			$product = Products::findOne($id);
			if (!empty($product)) {
				if ($value == 1) {
					if ($product->promotionType != 3) {
							// $promotionCriteria = new CDbCriteria();
							// $promotionCriteria->addCondition("productId = $id");
							// $promotionCriteria->addCondition("status LIKE 'live'");
						$promotionModel = Promotiontransaction::find()->where(['productId' => $id])
							->andWhere(['LIKE', 'status', 'live'])
							->one();
						if (!empty($promotionModel)) {
							if ($promotionModel->promotionName != 'urgent') {
									// $previousCriteria = new CDbCriteria();
									// $previousCriteria->addCondition("productId = $id");
									// $previousCriteria->addCondition("status LIKE 'Expired'");
								$previousPromotion = Promotiontransaction::find()->where(['productId' => $id])
									->andWhere(['LIKE', 'status', 'Expired'])
									->one();
								if (!empty($previousPromotion)) {
									$previousPromotion->status = "Canceled";
									$previousPromotion->save(false);
								}
							}
							$promotionModel->status = "Expired";
							$promotionModel->save(false);
						}
						$product->promotionType = 3;
					}

					$product->soldItem = 1;
					$product->quantity = 0;

					$product->save(false);
					return '{"status": "true","message":"Item Status changed to Sold"}';
				} else {
					$product->soldItem = 0;
					$product->quantity = 1;
					$product->save(false);
					return '{"status": "true","message":"Item Status changed to Available"}';
				}
			} else {
				return '{"status": "false","message":"Item status cannot be changed"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		REPORT ITEM PARAMS - $api_username, $api_password, $user_id, $item_id
	 */
	public function actionReportitem()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {

			$user_id = $_POST['user_id'];
			$id = $_POST['item_id'];

			$product = Products::findOne($id);
			if (!empty($product)) {
				if ($product->reports == "") {
					$reports[] = $user_id;
					$product->reportCount += 1;
					$message = '{"status": "true","message":"Reported Successfully"}';
				} else {
					$reports = Json::decode($product->reports, true);
					if (($key = array_search($user_id, $reports)) !== false) {
						unset($reports[$key]);

						$product->reportCount -= 1;
						$message = '{"status": "true","message":"Unreported Successfully"}';
					} else {
						$reports[] = $user_id;
						$product->reportCount += 1;
						$message = '{"status": "true","message":"Reported Successfully"}';
					}
				}
				if (empty($reports)) {
					$product->reports = '';
				} else {
					$reportData = Json::encode($reports);
					$product->reports = $reportData;
				}
				$product->save(false);
				return $message;
			} else {
				return '{"status": "false","message":"Item invalid"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		RESET BADGE PARAMS - $api_username, $api_password, $deviceToken
	 */
	public function actionResetbadge()
	{
		//Post Values
		//$api_username = $_POST['api_username'];
		//$api_password = $_POST['api_password'];
		$deviceToken = $_POST['deviceToken'];

			// $criteria = new CDbCriteria;
			// $criteria->addCondition('deviceToken = "'.$deviceToken.'"');
		$userdevicedatas = Userdevices::find()->where(['deviceToken' => $deviceToken])->one();
		$userdevicedatas->badge = '0';
		$userdevicedatas->save(false);
		if ($userdevicedatas->save(false)) {
			return '{"status": "false","message":"Badge reset successfully"}';
		} else {
			return '{"status": "false","message":"Something went wrong, Please try again"}';
		}
	}

	/* Adding Stripe details for user */
		public function actionAddstripedetails()
	{
    	//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
		
			$userid = $_POST['user_id'];
			$stripe_privatekey = $_POST['stripe_privatekey'];
			$stripe_publickey = $_POST['stripe_publickey'];
		


			$userdatas = Users::find()->where(['userId' => $userid])->one();
		

			if (isset($stripe_privatekey) && trim($stripe_privatekey) != ''&& isset($stripe_publickey) && trim($stripe_publickey) != '') {
			
					if (!empty($userdatas)) {
							$stripe_details['stripe_privatekey'] = $stripe_privatekey;
							$stripe_details['stripe_publickey'] = $stripe_publickey;
							$userdatas->stripe_details = Json::encode($stripe_details);
							$userdatas->save(false);
							$stripedetails = Json::decode($userdatas->stripe_details);
					} else {
					return '{"status":"false","result":"Something went wrong, please try again later"}';
					}
					return '{"status":"true","result":'.$userdatas->stripe_details.'}';
				
			} else {
				return '{"status":"false","result":"Something went wrong, please try again later"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		ADD DEVICE ID PARAMS - $api_username, $api_password, $deviceId, $userid, $devicetype, $deviceToken, $devicemode, $lang_type
	 */
	public function actionAdddeviceid()
	{
    	//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$deviceId = $_POST['deviceId'];
			$userid = $_POST['userid'];
			$deviceToken = $_POST['deviceToken'];
			$devicetype = $_POST['devicetype'];
			$devicemode = $_POST['devicemode'];
			$lang_type = $_POST['lang_type'];
			$devicemodel = $_POST['device_model'];
			$devicename = $_POST['device_name'];	
			$deviceos = $_POST['device_os'];



				// $criteria = new CDbCriteria;
				// $criteria->addCondition('deviceId = "'.$deviceId.'"');
			$userdevicedatas = Userdevices::find()->where(['deviceId' => $deviceId])->one();

			if (isset($deviceId) && trim($deviceId) != '') {
				if (isset($devicetype)) {
					if (!empty($userdevicedatas)) {
						if (isset($devicetype)) {
							$userdevicedatas->deviceId = $deviceId;
							$userdevicedatas->user_id = $userid;
							$userdevicedatas->type = $devicetype;
							$userdevicedatas->mode = $devicemode;
							$userdevicedatas->lang_type = $lang_type;
							$userdevicedatas->deviceModel = $devicemodel;
							$userdevicedatas->deviceName = $devicename;
							$userdevicedatas->deviceOS = $deviceos;
							$userdevicedatas->save(false);
						}
						if (isset($deviceToken)) {
							$userdevicedatas->deviceId = $deviceId;
							$userdevicedatas->user_id = $userid;
							$userdevicedatas->deviceToken = $deviceToken;
							$userdevicedatas->mode = $devicemode;
							$userdevicedatas->lang_type = $lang_type;
							$userdevicedatas->deviceModel = $devicemodel;
							$userdevicedatas->deviceName = $devicename;
							$userdevicedatas->deviceOS = $deviceos;
							$userdevicedatas->save(false);
						}
					} else {
						$newdevice = new Userdevices();
						$newdevice->deviceId = $deviceId;
						$newdevice->user_id = $userid;
						$newdevice->deviceToken = $deviceToken;
						$newdevice->type = $devicetype;
						$newdevice->mode = $devicemode;
						$newdevice->deviceModel = $devicemodel;
						$newdevice->deviceName = $devicename;
						$newdevice->deviceOS = $deviceos;
						$newdevice->lang_type = $lang_type;
						$newdevice->cdate = time();
						$newdevice->save(false);
					}
					return '{"status":"true","result":"Registered successfully"}';
				}
			} else {
				return '{"status":"false","result":"Something went wrong, please try again later"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

   /* 
		FOLLOWERS DETAILS PARAMS - $api_username, $api_password, $user_id, $offset = 0, $limit = 20
	 */

	public function actionFollowersdetails()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {

			$userId = $_POST['user_id'];
			if (isset($userId) && $userId != "") {
				$offset = 0;
				$limit = 10;

				if (isset($_POST['offset']) && !empty($_POST['offset']))
					$offset = $_POST['offset'];

				if (isset($_POST['limit']) && !empty($_POST['limit']))
					$limit = $_POST['limit'];

				$criteria = Followers::find();
				$criteria->limit = $limit;
				$criteria->offset = $offset;
				$criteria->andWhere(["follow_userId" => $userId]);
				$criteria->andWhere(["<>","userId",$userId]);
				$FollowersModel = $criteria->all();
				if (!empty($FollowersModel)) {
					foreach ($FollowersModel as $followkey => $Followers) {
						$follow_userId = $Followers->userId;
						$userModel = Users::find()->where(['userId' => $follow_userId])->all();
						$result[$followkey]['user_id'] = $userModel['0']['userId'];
						$result[$followkey]['user_name'] = $userModel['0']['username'];
						$result[$followkey]['full_name'] = $userModel['0']['name'];

						$_FollowersModel = Followers::find()->where(['userId' => $userId, 'follow_userId' => $follow_userId])->all();

						if (count($_FollowersModel) > 0) {
							$result[$followkey]['status'] = "unfollow";
						} else {
							$result[$followkey]['status'] = "follow";
						}

						if (isset($userModel['0']['userImage'])) {
							$imageUrl = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userModel['0']['userImage']);
						} else {
							$imageUrl = Yii::$app->urlManagerfrontEnd->baseUrl . '/media/logo/' . yii::$app->Myclass->getDefaultUser();
						}
						$result[$followkey]['user_image'] = $imageUrl;
					}
					$final = json_encode($result);
					return '{"status": "true", "result":' . $final . '}';
				} else {
					return '{"status":"false","message":"No followers found"}';
				}
			} else {
				return '{"status":"false","message":"No followers found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		FOLLOWING DETAILS PARAMS - $api_username, $api_password, $user_id, $limit = 20, $offset = 0
	 */
	public function actionFollowingdetails()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$userId = $_POST['user_id'];
			if (isset($userId) && $userId != "") {
				$offset = 0;
				$limit = 10;

				if (isset($_POST['offset']) && !empty($_POST['offset']))
					$offset = $_POST['offset'];

				if (isset($_POST['limit']) && !empty($_POST['limit']))
					$limit = $_POST['limit'];

				$criteria = Followers::find();
				$criteria->limit = $limit;
				$criteria->offset = $offset;
				$criteria->andWhere(["userId" => $userId]);
				$criteria->andWhere(["<>","follow_userid",$userId]);
				$FollowersModel = $criteria->all();
				if (!empty($FollowersModel)) {
					foreach ($FollowersModel as $followkey => $Followers) {
						$follow_userId = $Followers->follow_userId;
						$userModel = Users::find()->where(['userId' => $follow_userId])->all();
						$result[$followkey]['user_id'] = $userModel['0']['userId'];
						$result[$followkey]['user_name'] = $userModel['0']['username'];
						$result[$followkey]['full_name'] = $userModel['0']['name'];

						$result[$followkey]['status'] = "unfollow";

						if (isset($userModel['0']['userImage'])) {
							$imageUrl = Yii::$app->urlManager->createAbsoluteUrl('profile/' . $userModel['0']['userImage']);
						} else {
							$imageUrl = Yii::$app->urlManager->createAbsoluteUrl('profile/' . yii::$app->Myclass->getDefaultUser());
						}
						$result[$followkey]['user_image'] = $imageUrl;
					}
					$final = json_encode($result);
					return '{"status": "true", "result":' . $final . '}';
				} else {
					return '{"status":"false","message":"No followings found"}';
				}
			} else {
				return '{"status":"false","message":"No followings found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		CONFIRN OTP PARAMS - $api_username, $api_password, $user_id, $otp, $mob_no
	 */

	public function actionConfirmotp()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$user_id = $_POST['user_id'];
			$otp = $_POST['otp'];
			$mob_no = $_POST['mob_no'];

			$user = Users::findOne($user_id);
				//print_r($user);exit;
			if (!empty($user) && !empty($otp)) {
					//print_r($otp);exit;
				if ((strcmp($otp, $user->mobile_verificationcode) == 0) && $user->userId == $user_id) {
					$user->phone == $mob_no;
					$user->mobile_status = '1';
					$user->save(false);
					return ' {"status":"true","message":"Your mobile number verified successfully"}';
				} else {
					return '{"status":"false","message":"Sorry, Enter the correct verification code"}';
				}
			} else {
				return '{"status":"false","message":"Sorry, Something went to be wrong"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		FOLLOW USER PARAMS - $api_username, $api_password, $user_id, $follow_id
	 */
	public function actionFollowuser()
	{
			//Post Values - email have to be add
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		if ($this->authenticateAPI($api_username, $api_password)) {
			$userId = $_POST['user_id'];
			$follow_user = $_POST['follow_id'];

			$userdetails = Users::findOne($userId);
			$fusername = $userdetails->name;
			$followerdetail = Users::findOne($follow_user);
			$curentusername = $followerdetail->name;
			$emailTo = $followerdetail->email;
			if (!empty($follow_user)) {
				$getfollowmodel = Followers::find()->where(['userId' => $userId])
					->andWhere(['follow_userId' => $follow_user])->one();
				if (empty($getfollowmodel)) {
					$model = new Followers();
					$model->userId = $userId;
					$model->follow_userId = $follow_user;
					$model->followedOn = date("Y-m-d H:i:s");
					$model->save();
					$notifyMessage = 'start Following you';
					yii::$app->Myclass->addLogs("follow", $userId, $follow_user, $model->id, 0, $notifyMessage);
					$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
						//if ($siteSettings->smtpEnable == 1) {}
					$userid = $follow_user;
					$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
					$followerdetail = Users::findOne($userId);
					$curentusername = $followerdetail->name;
					if (count($userdevicedet) > 0) {
						foreach ($userdevicedet as $userdevice) {
							$deviceToken = $userdevice->deviceToken;
							$lang = $userdevice->lang_type;
							$badge = $userdevice->badge;
							$badge += 1;
							$userdevice->badge = $badge;
							$userdevice->deviceToken = $deviceToken;
							$userdevice->save(false);
							if (isset($deviceToken)) {

								$msg = yii::$app->Myclass->push_lang($lang);

								$text = 'start Following you';
								$msg = Yii::t('app', $text);
								$messages = $curentusername . ' ' . $msg;
								yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
							}
						}
					}
					return '{"status":"true", "message":"Successfully Followed"}';
					die;
				} else {
					return '{"status":"false", "message":"User Already Following"}';
					die;
				}
			} else {
				return '{"status":"false", "message":"User Not Available for Following"}';
				die;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
			die;
		}
	}

	/* 
		UNFOLLOW USER PARAMS - $api_username, $api_password, $user_id, $follow_id
	 */
	public function actionUnfollowuser()
	{
			//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$userId = $_POST['user_id'];
			$follow_user = $_POST['follow_id'];

			if (!empty($follow_user)) {
				$getfollowmodel = Followers::find()->where(['userId' => $userId])
					->andWhere(['follow_userId' => $follow_user])->one();
				if (!empty($getfollowmodel)) {
					$followId = $getfollowmodel->id;
					Followers::deleteAll(['userId' => $userId, 'follow_userId' => $follow_user]);
					$logsModel = Logs::find()->where(['LIKE', 'type', 'follow'])
						->andWhere(['sourceId' => $followId])->one();
					$logsModel->delete();
					return '{"status":"true", "message":"Successfully Unfollowed"}';
					die;
				} else {
					return '{"status":"false", "message":"User Already Not Following"}';
					die;
				}
			} else {
				return '{"status":"false", "message":"User Not Available for Following"}';
				die;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
			die;
		}
	}

	/* 
		CHECK PROMOTION PARAMS -  $api_username, $api_password, $item_id
	 */
	public function actionCheckpromotion()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$id = "";
			if (isset($_POST['item_id'])) {
				$id = $_POST['item_id'];
			}
			$productModel = Products::findOne($id);

			$productId = $productModel->productId;
				// $product_criteria = new CDbCriteria;
				// $product_criteria->addCondition("productId = $productId");
				// $product_criteria->order = 'id DESC';
			$promot_detail = Promotiontransaction::find()->where(['productId' => $productId])
				->orderBy(['id' => SORT_DESC])->one();

			if (!empty($promot_detail)) {
				$promotions['id'] = $promot_detail->id;
				$promotions['promotion_name'] = $promot_detail->promotionName;
				$promotions['paid_amount'] = $promot_detail->promotionPrice;
				if($_POST['lang_type'] == "ar")
				 $promotions['formatted_paid_amount'] = yii::$app->Myclass->arabicconvertFormattingCurrencyapi($promot_detail->promotionCurrency,$promot_detail->promotionPrice);
				else
						 $promotions['formatted_paid_amount'] = yii::$app->Myclass->convertFormattingCurrencyapi($promot_detail->promotionCurrency,$promot_detail->promotionPrice);
				$currency = '';
				$currency = explode('-', $productModel->currency);
				$promotions['currency_symbol'] = $currency[0];
				$promotions['currency_code'] = $currency[1];

			    $currency_formats = yii::$app->Myclass->getCurrencyFormat($currency[1]);
				if($currency_formats[0] != "")
						$promotions['currency_mode'] = $currency_formats[0];
				if($currency_formats[1] != "")
						$promotions['currency_position'] = $currency_formats[1];
				

				$start_date = date("M d Y", $promot_detail->createdDate);
				$end_date = date("M d Y", strtotime("+" . $promot_detail->promotionTime . "  days", $promot_detail->createdDate));
				$promotions['upto'] = strtotime($start_date) . ' - ' . strtotime($end_date);
				$promotions['transaction_id'] = $promot_detail->tranxId;
				$promotions['status'] = $promot_detail->status;
				$promotions['item_id'] = $productModel->productId;
				$promotions['item_name'] = $productModel->name;
				$promotions['item_image'] = $productModel->photos[0]->name;

				if (isset($productModel->approvedStatus) && $productModel->approvedStatus == "1") {
					$promotions['item_approve'] = "1";
				} else {
					$promotions['item_approve'] = "0";
				}
				/*foreach ($productModel->photos as $photo){
					$photoName = $photo->name;
					$promotions['item_image'] = $photoName;
					//break;
				}*/
				$promotion_details = Json::encode($promotions);
				return '{"status": "true", "result":' . $promotion_details . '}';
			} else {
				return '{"status":"false", "message":"Item Not Found for Promotions."}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		HELP PAGE PARAMS -  $api_username, $api_password
	 */
	public function actionHelppage()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {	
				// $criteria = new CDbCriteria;
				// $criteria->addCondition('id != 2');
			$Helppages = Helppages::find()->where(['!=', 'id', 2])->all();
	 			$help_lang =  $_POST['lang_type'];  
				 yii::$app->Myclass->push_lang($help_lang);
			foreach ($Helppages as $key => $Helppage) {
				$pagetitle[$key]['page_name'] = Yii::t('app',$Helppage->page);

			
         		 $helpcontent = Json::decode($Helppage->pageContent,true);

		        if ($helpcontent!="") {
		             
		         if (array_key_exists($help_lang, $helpcontent)) {
		      
		           	$help_desc = $helpcontent[$help_lang]['content'];
		         } 
		         else
		         {


		         	  $firstelem = array_keys($helpcontent)[0];
     				  $help_desc = $helpcontent[$firstelem]['content'];
		         }

		     	}

				$pagetitle[$key]['page_content'] = $help_desc;
			}
			$final = Json::encode($pagetitle);
			return '{"status":"true","result":' . $final . '}';
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		GET OTP PARAMS -  $api_username, $api_password, $user_id, $mob_no, $country_code
	 */
	public function actionGetotp()
	{
		//Post Values - finished but didnt get msg
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		$user_id = $_POST['user_id'];
		$mob_no = $_POST['mob_no'];
		$country_code = $_POST['country_code'];

		if ($user_id == 0 && $api_username != "" && $api_password != "" && $mob_no != "" && $country_code != "") {
			$pass = rand(100000, 999999);
			// twilio mobile verification code
				// Yii::import('application.vendors.*',true);
				// spl_autoload_unregister(array('YiiBase','autoload'));
				// require_once(Yii::$app->basePath."/vendors/twilio-php/Services/Twilio.php");
				// spl_autoload_register(array('YiiBase', 'autoload'));

				// $setting_id = 1;
				// $checkCondition= new CDbCriteria;
				// $checkCondition->addCondition('id = "'.$setting_id.'"');
			$twilioService = Yii::$app->Yii2Twilio->initTwilio();
			$sitedetails = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$account_sid = $sitedetails->account_sid;
			$auth_token = $sitedetails->auth_token;
			$from_no = $sitedetails->sms_number;


				//$client = new Services_Twilio($account_sid, $auth_token);

			try {
				$message = $twilioService->account->messages->create(
					'+' . $country_code . $mob_no, // To a number that you want to send sms
					array(
						"from" => $from_no,   // From a number that you are sending
						"body" => 'Welcome to ' . $sitedetails->sitename . ' site.your mobile verification code is ' . $pass,
						array("url" => "https://demo.twilio.com/welcome/sms/reply/")
					)
				);



				return '{"status":"true","message":"Mobile verification code sent successfully","otp":"' . $pass . '"}';
			} catch (\Twilio\Exceptions\RestException $e) {
				$a = $e->getMessage();
				return '{"status":"false","message":"' . $a . '"}';
			}
			// twilio mobile verification code end
		} else {
			if ($this->authenticateAPI($api_username, $api_password)) {
				$pass = rand(100000, 999999);

				$user = Users::find()->where(['userId' => $user_id])->one();
				if (!empty($user) && !empty($mob_no)) {
					if ($user->phone == $mob_no && $user->mobile_status == 1) {
						return ' {"status":"false","message":"Mobile Number already Verified"}';
					} else {
						$user->mobile_verificationcode = $pass;
						//$user->phone = $mob_no;
						$user->save(false);
							// twilio mobile verification code
							// Yii::import('application.vendors.*',true);
							// spl_autoload_unregister(array('YiiBase','autoload'));
							// require_once(Yii::app()->basePath."/vendors/twilio-php/Services/Twilio.php");
							// spl_autoload_register(array('YiiBase', 'autoload'));

							// $setting_id = 1;
							// $checkCondition= new CDbCriteria;
							// $checkCondition->addCondition('id = "'.$setting_id.'"');
							//print_r($country_code);print_r($mob_no);exit;
						$sitedetails = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
						$account_sid = $sitedetails->account_sid;
						$auth_token = $sitedetails->auth_token;
						$from_no = $sitedetails->sms_number;
						$twilioService = Yii::$app->Yii2Twilio->initTwilio();

							//$client = new Services_Twilio($account_sid, $auth_token);

						try {
							$message = $twilioService->account->messages->create(
								'+' . $country_code . $mob_no, // To a number that you want to send sms
								array(
									"from" => $from_no,   // From a number that you are sending
									"body" => 'Welcome to ' . $sitedetails->sitename . ' site.your mobile verification code is ' . $pass,
								)
							);



							return '{"status":"true","message":"Mobile verification code sent successfully","otp":"' . $pass . '"}';
						} catch (\Twilio\Exceptions\RestException $e) {
							$a = $e->getMessage();
							return '{"status":"false","message":"' . $a . '"}';
						}
					// twilio mobile verification code end
					}
				} else {
					return ' {"status":"false","message":"Sorry, Something went to be wrong"}';
				}
			} else {
				return '{"status":"false", "message":"Unauthorized Access to the API"}';
			}
		}
	}

	/* 
		BRAINTREE CLIENT TOKEN PARAMS - $api_username, $api_password
	 */
	public function actionBraintreeClientToken()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$currency_code = $_POST['currency_code'];

				//Yii::setPathOfAlias('Braintree',Yii::getPathOfAlias('application.vendors.Braintree.Braintree'));

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();;
			$brainTreeSettings = Json::decode($siteSettings->braintree_settings, true);

			$paymenttype = "sandbox";
			if ($brainTreeSettings['brainTreeType'] == 1) {
				$paymenttype = "production";
			}
			$merchantid = $brainTreeSettings['brainTreeMerchantId'];
			$publickey = $brainTreeSettings['brainTreePublicKey'];
			$privatekey = $brainTreeSettings['brainTreePrivateKey'];

			Braintree\Configuration::environment($paymenttype);
			Braintree\Configuration::merchantId($merchantid);
			Braintree\Configuration::publicKey($publickey);
			Braintree\Configuration::privateKey($privatekey);

			$merchantAccountId = yii::$app->Myclass->getbraintreemerchantid($currency_code);

			if (empty($merchantAccountId)) {
				return '{"status":"false","message":"Token cannot be created now, Sorry!"}';
			} else {
				$clientToken = Braintree\ClientToken::generate([
					"merchantAccountId" => $merchantAccountId
				]);

				if ($clientToken && $clientToken != "") {
					return '{"status":"true","token":"' . $clientToken . '"}';
				} else {
					return '{"status":"false","message":"Token cannot be created now, Sorry!"}';
				}
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		PROCESSING PAYMENT PARAMS - $api_username, $api_password, $user_id, $item_id, $promotion_id, $currency_code, $pay_nonce
	 */
	public function actionProcessingPayment()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$promotion_id = $_POST['promotion_id'];
			$user_id = $_POST['user_id'];
			$item_id = $_POST['item_id'];
			$currency_code = $_POST['currency_code'];
			$pay_nonce = $_POST['pay_nonce'];
			$payment_type = $_POST['payment_type'];

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			if ($promotion_id == 0) {
				$promotionName = "urgent";
				$promotionPrice = $siteSettings->urgentPrice;
				$promotionTime = 0;
			} else {
				$promotionDetails = Promotions::findOne($promotion_id);
				$promotionName = "adds";
				$promotionPrice = $promotionDetails->price;
				$promotionTime = $promotionDetails->days;
			}

			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$brainTreeSettings = Json::decode($siteSettings->braintree_settings, true);
			if($payment_type == "stripe") {

			$default = yii::$app->Myclass->getDefaultShippingAddress($user_id);
			$shippingAddressesModel = Tempaddresses::find()->where(['userId'=>$user_id])->all();

			if (count($shippingAddressesModel) > 0){
		
			if(empty($default)) {
				$shippingAddress = $shippingAddressesModel[0];
			} else {
				$shippingAddress = Tempaddresses::find()->where(['shippingaddressId' => $default])->one();
			}
			}
			$stripeSettings = Json::decode($siteSettings->stripe_settings, true);
		
			// $tempShippingModel = Tempaddresses::find()->where(['shippingaddressId'=>$shippingid])->one();
			$secretkey=$stripeSettings['stripePrivateKey'];

			$url = 'https://api.stripe.com/v1/charges';
			$data = array('amount' => $promotionPrice * 100, 'currency' => $currency_code, 'source' => $pay_nonce);
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
		// echo "<pre>"; print_r($output); die;

	}else{
			$paymenttype = "sandbox";
			if ($brainTreeSettings['brainTreeType'] == 1) {
				$paymenttype = "production";
			}
			$merchantid = $brainTreeSettings['brainTreeMerchantId'];
			$publickey = $brainTreeSettings['brainTreePublicKey'];
			$privatekey = $brainTreeSettings['brainTreePrivateKey'];
			Braintree\Configuration::environment($paymenttype);
			Braintree\Configuration::merchantId($merchantid);
			Braintree\Configuration::publicKey($publickey);
			Braintree\Configuration::privateKey($privatekey);
			$merchant_account_id = yii::$app->Myclass->getbraintreemerchantid($currency_code);
			if (empty($merchant_account_id)) {
				return '{"status":"false","message":"Sorry, Something went wrong. Please try again later ok"}';
			}
			 else {
			 		$result = Braintree\Transaction::sale([
					'amount' => $promotionPrice,
					'merchantAccountId' => $merchant_account_id,
					'paymentMethodNonce' => $pay_nonce
				]);
			 		$result1 = Braintree\Transaction::submitForSettlement($result->transaction->id);
			 }
		}

	

		

			

			// $result->success || !is_null($result->transaction->id) && $result1->success == '1' || 
			
			
				if ($result->success || !is_null($result->transaction->id) && $result1->success == '1' || $output['status'] == 'succeeded' ) {
					if($payment_type == "stripe")
						$transaction = $output['id'];
					else
						$transaction = $result->transaction->id;
					$itemId = $item_id;
					$productModel = Products::findOne($itemId);

					$currencyCode = $currency_code;
					$createdDate = time();

					$promotionTranxModel = new Promotiontransaction();
					$promotionTranxModel->promotionName = $promotionName;
					$promotionTranxModel->promotionPrice = $promotionPrice;
					$promotionTranxModel->promotionCurrency = $currency_code;
					$promotionTranxModel->promotionTime = $promotionTime;
					$promotionTranxModel->productId = $itemId;
					$promotionTranxModel->status = 'Live';
					$promotionTranxModel->userId = $user_id;
					$promotionTranxModel->tranxId = $transaction;

					if ($siteSettings->product_autoapprove == 1) {
						$promotionTranxModel->approvedStatus = 1;
						$promotionTranxModel->initial_check = 1;
						$promotionTranxModel->createdDate = $createdDate;
					} else {
						$promotionTranxModel->approvedStatus = 0;
						$promotionTranxModel->initial_check = 0;
						$promotionTranxModel->createdDate = $createdDate;
					}

					$promotionTranxModel->save(false);
					$promotionTranxId = $promotionTranxModel->id;

					if ($promotion_id != 0) {
						$adsPromotionDetailsModel = new Adspromotiondetails();
						$adsPromotionDetailsModel->productId = $itemId;
						$adsPromotionDetailsModel->promotionTime = $promotionTime;
						$adsPromotionDetailsModel->promotionTranxId = $promotionTranxId;
						$adsPromotionDetailsModel->createdDate = $createdDate;

						$adsPromotionDetailsModel->save(false);
					}

					if ($promotionName == "urgent") {
						$productModel->promotionType = 2;
					} else {
						$productModel->promotionType = 1;
					}

					$productModel->save(false);
					$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
					$userModel = yii::$app->Myclass->getUserDetailss($productModel->userId);
					$sellerEmail = $userModel->email;
					$sellerName = $userModel->name;
					$userid = $productModel->userId;
					$userdevicedet = Userdevices::find()->where(['user_id' => $userid])->all();
					if (count($userdevicedet) > 0) {
						foreach ($userdevicedet as $userdevice) {
							$deviceToken = $userdevice->deviceToken;
							$lang = $userdevice->lang_type;
							$badge = $userdevice->badge;
							$badge += 1;
							$userdevice->badge = $badge;
							$userdevice->deviceToken = $deviceToken;
							$userdevice->save(false);
							if (isset($deviceToken)) {

								$msg = yii::$app->Myclass->push_lang($lang);

								if ($promotionName == "urgent") {
									$text = 'You have promoted your product';
									$msg = Yii::t('app', $text);

									$text1 = 'by';
									$msg1 = Yii::t('app', $text1);



									if($_POST['lang_type'] == "ar")
										$formatted_price = yii::$app->Myclass->convertFormattingCurrencyapi($currencyCode,$promotionPrice);
									else
										$formatted_price = yii::$app->Myclass->convertFormattingCurrencyapi($currencyCode,$promotionPrice);
									$messages = $msg . " " . $productModel->name;
								} else {
									$text = 'You have promoted your product';
									$msg = Yii::t('app', $text);

									$text1 = 'by';
									$msg1 = Yii::t('app', $text1);

									$text2 = 'for';
									$msg2 = Yii::t('app', $text2);

									$text3 = 'days';
									$msg3 = Yii::t('app', $text3);
									if($_POST['lang_type'] == "ar")
									   $formatted_price = yii::$app->Myclass->convertFormattingCurrencyapi($currencyCode,$promotionPrice);
									else
										  $formatted_price = yii::$app->Myclass->convertFormattingCurrencyapi($currencyCode,$promotionPrice);
									$messages = $msg . " " . $productModel->name ." ".$msg2 . " " . $promotionTime . " " . $msg3;
								}
								yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
							}
						}
					}
					return '{"status":"true","message":"Your promotion was activated successfully"}';
					// if ($siteSettings->smtpEnable == 1) {
					// 	$mailer = Yii::$app->mailer->setTransport([
					// 		'class' => 'Swift_SmtpTransport',
					// 		'host' => $siteSettings['smtpHost'],
					// 		'username' => $siteSettings['smtpEmail'],
					// 		'password' => $siteSettings['smtpPassword'],
					// 		'port' => $siteSettings['smtpPort'],
					// 		'encryption' => 'tls',
					// 	]);
						
					// 	try {
					// 		if ($productModel->sendPromotionMail($sellerEmail, $userModel, $productModel, $productModel->name, $sellerName)) {
					// 			return '{"status":"true","message":"Your promotion was activated successfully"}';
					// 		}
					// 	} catch (\Swift_TransportException $exception) {
					// 		return '{"status":"true","message":"Your promotion was activated successfully"}';
					// 	} catch (\Exception $e) {
					// 		return '{"status":"true","message":"Your promotion was activated successfully"}';
					// 	}
					// } else {
					// 	return '{"status":"true","message":"Your promotion was activated successfully"}';
					// }
				} else {
					return '{"status":"false","message":"Sorry, Something went wrong. Please try again later"}';
				}
		
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		EDIT PROFILE PARAMS - $api_username, $api_password, $user_id,
		 $fb_email=NULL, $fb_firstname=NULL, $fb_lastname=NULL, $fb_phone=NULL, 
		 $fb_profileurl=NULL, $full_name = NULL, $user_img = NULL, $facebook_id = NULL, 
		 $mobile_no = NULL, $show_mobile_no=NULL
	 */
	public function actionEditprofile()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		
		if ($this->authenticateAPI($api_username, $api_password)) {
			
			$userId = $_POST['user_id'];
			$user = Users::find()->where(['userId' => $userId])->one();


			if (!empty($user)) {
				if (isset($_POST['fb_email'])) {
					$fb_email = $_POST['fb_email'];
				}


				if (isset($_POST['fb_firstname'])) {
					$fb_firstname = $_POST['fb_firstname'];
				}


				if (isset($_POST['fb_lastname'])) {
					$fb_lastname = $_POST['fb_lastname'];
				}


				if (isset($_POST['fb_phone'])) {
					$fb_phone = $_POST['fb_phone'];
				}


				if (isset($_POST['fb_profileurl'])) {
					$fb_profileurl = $_POST['fb_profileurl'];
				}

				if (isset($_POST['full_name'])) {
					$full_name = $_POST['full_name'];
				}


				if (isset($_POST['user_img'])) {
					$user_img = $_POST['user_img'];
				}


				if (isset($_POST['facebook_id'])) {
					$facebook_id = $_POST['facebook_id'];
				}

				if (isset($_POST['mobile_no'])) {
					$mobile_no = $_POST['mobile_no'];
				}

				if (isset($_POST['show_mobile_no'])) {
					$show_mobile_no = $_POST['show_mobile_no'];
				}

				$city_name = (isset($_POST['city_name'])) ? trim($_POST['city_name']) : '';
				$state_name = (isset($_POST['state_name'])) ? trim($_POST['state_name']) : '';
				$country_name = (isset($_POST['country_name'])) ? trim($_POST['country_name']) : ''; 
				
				if (!empty($full_name)) {
					$user->name = $full_name;
				}
				if (!empty($facebook_id)) {
					$user->facebookId = $facebook_id;
				}
				if (!empty($mobile_no)) {
					$user->phone = $mobile_no;
					$user->mobile_status = 1;
				}
				if (!empty($user_img)) {
					$user->userImage = $user_img;
				}

				$user->city = $city_name;
				$user->state = $state_name;
				$user->country = $country_name;

				if (!empty($show_mobile_no)) {
					if ($show_mobile_no == "true") {
						$user->phonevisible = "1";
					} else if ($show_mobile_no == "false") {
						$user->phonevisible = "0";
					}
				}


				$socialids = Json::decode($user->fbdetails, true);
				if (!empty($user->fbdetails)) {
					if ($socialids['email'] != "")
						$fbdetails['email'] = $socialids['email'];
					else
						$fbdetails['email'] = "";
					if ($socialids['firstName'] != "")
						$fbdetails['firstName'] = $socialids['firstName'];
					else
						$fbdetails['firstName'] = "";
					if ($socialids['lastName'] != "")
						$fbdetails['lastName'] = $socialids['lastName'];
					else
						$fbdetails['lastName'] = "";
					if (array_key_exists("phone",$socialids))
						$fbdetails['phone'] = $socialids['phone'];
					else
						$fbdetails['phone'] = "";
					if (array_key_exists("profileURL",$socialids))
						$fbdetails['profileURL'] = $socialids['profileURL'];
					else
						$fbdetails['profileURL'] = "";
				} else {
					if (!empty($fb_email))
						$fbdetails['email'] = $fb_email;
					else
						$fbdetails['email'] = "";
					if (!empty($fb_firstname))
						$fbdetails['firstName'] = $fb_firstname;
					else
						$fbdetails['firstName'] = "";
					if (!empty($fb_lastname))
						$fbdetails['lastName'] = $fb_lastname;
					else
						$fbdetails['lastName'] = "";
					if (!empty($fb_phone))
						$fbdetails['phone'] = $fb_phone;
					else
						$fbdetails['phone'] = "";
					if (!empty($fb_profileurl))
						$fbdetails['profileURL'] = $fb_profileurl;
					else
						$fbdetails['profileURL'] = "";
				}
				$user->fbdetails = Json::encode($fbdetails);

					if($model->stripe_details != "" && $model->stripe_details != null)
					   $userDetails['stripe_details'] = Json::decode($model->stripe_details,true);
					else{
						$stripe_det['stripe_privatekey']="";
						$stripe_det['stripe_publickey']="";

						$userDetails['stripe_details'] = $stripe_det;
					}


				if (!empty($facebook_id) || !empty($full_name) || !empty($mobile_no) || !empty($user_img) || !empty($city_name) || !empty($state_name) || !empty($country_name) )  { 
					$user->save(false);
				}
				$user = Users::find()->where(['userId' => $userId])->one();

				$userDetails['user_id'] = $user->userId;
				$userDetails['user_name'] = $user->username;
				$userDetails['full_name'] = $user->name;
				if ($user->userImage == "")
					$userDetails['user_img'] = Yii::$app->urlManagerfrontEnd->baseUrl . '/media/logo/' . yii::$app->Myclass->getDefaultUser();
				else
					$userDetails['user_img'] = Yii::$app->urlManager->createAbsoluteUrl('/profile/' . $user->userImage);
				$review = Reviews::find()->select(['avg(rating) as rating'])->where(['receiverId' => $user->userId])->one();
				$userDetails['rating'] = $review->rating;

				
				$userDetails['rating_user_count'] = Reviews::find()->where(['receiverId' => $user->userId])->count();
				$userDetails['email'] = $user->email;
				$userDetails['facebook_id'] = $user->facebookId;

				$userDetails['mobile_no'] = $user->phone;

				$userLoc = array();
				$userDetails['city'] = $userLoc[] = trim($user->city);
				$userDetails['state'] = $userLoc[] = trim($user->state);
				$userDetails['country'] = $userLoc[] = trim($user->country); 
				$userDetails['location'] = implode (", ",array_values(array_filter($userLoc)));   



				if ($user->facebookId == '') {
					$userverify['facebook'] = 'false';
				} else {
					$userverify['facebook'] = 'true';
				}

				$userverify['email'] = 'true';

				if ($user->mobile_status == '1') {
					$userverify['mob_no'] = 'true';
				} else {
					$userverify['mob_no'] = 'false';
				}



				$userDetails['verification'] = $userverify;
				$final = Json::encode($userDetails);
				return '{"status": "true", "result": ' . $final . '}'; die;
			} else {
				return '{"status":"false","message":"Sorry, Something went to be wrong"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}


/* 
		Offerstatus REQ PARAMS - $api_username, $api_password, $sender_id, $offer_id, $status
		status=accept/decline // status have this value 
	 */

	public function actionOfferstatus()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$sender_id = $_POST['sender_id'];
			$userDetails = yii::$app->Myclass->getUserDetailss($sender_id);
			if (!empty($userDetails)) {

				$chat_id = $_POST['chat_id'];
				$chataction = yii::$app->Myclass->getChatBlockValue($chat_id);

				if ($chataction == '0') {
					$msgId = $_POST['offer_id'];
					$status = $_POST['status'];
					if ($msgId != 0 && ($status == 'accept' || $status == 'decline')) {

						if ($status == 'accept') {
							$offerStatus = 1;
							$message = Yii::t('app',"successfully Accepted this offer");
							$content = "accepted";
						} else {
							$offerStatus = 2;
							$message = Yii::t('app',"declined this offer");
							$content = "declined";
						}

						$offerReceived = Messages::findOne($msgId);
						$senderId = $offerReceived->senderId;
						$productId = $offerReceived->sourceId;//product Id
						$chatId = $offerReceived->chatId;//chatId
						$msg = Json::decode($offerReceived->message, true);
						$offStatus = $msg['offerstatus'];
						if ($offStatus == 0) {

							$offerMessage['message'] = $msg['message'];
							$offerMessage['price'] = $msg['price'];
								if($_POST['lang_type'] == "ar")
									$offerMessage['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($msg['currency'],$msg['price']);
								else
									$offerMessage['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($msg['currency'],$msg['price']);
							$offerMessage['currency'] = $msg['currency'];


							$currency_formats = yii::$app->Myclass->getCurrencyFormats($msg['currency']);
							if($currency_formats[0] != "")
									$offerMessage['currency_mode'] = $currency_formats[0];
							if($currency_formats[1] != "")
									$offerMessage['currency_position'] = $currency_formats[1];
							// New keys for my offer section
							$offerMessage['offerstatus'] = $offerStatus;// 0- pending,1- accept,2 -declined
							$offerMessage['type'] = 'sendreceive'; // sendreceive,accept,decline
							$offerMessage['msgsourceid'] = 0;
							$offerMessage['buynowstatus'] = 0; //0-pending,1 - buyed
							$offerMessage = Json::encode($offerMessage);
							$offerReceived->message = $offerMessage;
							$offerReceived->save(false);
							// end my offer section
							$offerAccept['message'] = $msg['message'];
							$offerAccept['price'] = $msg['price'];
								if($_POST['lang_type'] == "ar")
									$offerAccept['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($msg['currency'],$msg['price']);
								else
									$offerAccept['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($msg['currency'],$msg['price']);
							$offerAccept['currency'] = $msg['currency'];

						    $currency_formats = yii::$app->Myclass->getCurrencyFormats($msg['currency']);
							if($currency_formats[0] != "")
									$offerAccept['currency_mode'] = $currency_formats[0];
							if($currency_formats[1] != "")
									$offerAccept['currency_position'] = $currency_formats[1];
							// New keys for my offer section
							$offerAccept['offerstatus'] = $offerStatus;// 0- pending,1- accept,2 -declined
							$offerAccept['type'] = $status; // sendreceive,accept,decline
							$offerAccept['msgsourceid'] = $msgId;
							$offerAccept['buynowstatus'] = 0; //0-pending,1 - buyed
							$acceptEncode = Json::encode($offerAccept);


							$messageModel = new Messages();
							$messageModel->message = $acceptEncode;
							$messageModel->messageType = "offer";
							$messageModel->senderId = $senderId;
							$messageModel->sourceId = $productId;
							$messageModel->chatId = $chatId;
							$messageModel->createdDate = time();
							$messageModel->save();
							$newofferId = $messageModel->messageId;


							$offerReceived = Messages::findOne($newofferId);
							$senderId = $offerReceived->senderId;
							$productId = $offerReceived->sourceId;//product Id
							$productDetails = yii::$app->Myclass->getProductDetails($productId);
							$productImage = yii::$app->Myclass->getProductImage($productId);
							if ($productImage != "") {
								$proImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/item/' . $productId . '/' . $productImage);
							} else {
								$proImageUrl = Yii::$app->urlManager->createAbsoluteUrl('media/item/default.jpeg');
							}
							$msg = Json::decode($offerReceived->message, true);
							$offerCurrency = explode('-', $msg['currency']);
							$mkeOfferPrice = $msg['price'];
							$cartDataURL = yii::$app->Myclass->cart_encrypt($productId . "-0-" . $mkeOfferPrice . "-" . $newofferId, 'joy*ccart');
							$buynow_URL = Yii::$app->urlManager->createAbsoluteUrl('revieworder2/' . $cartDataURL);
							$sitePaymentModes = yii::$app->Myclass->getSitePaymentModes();
							$timeUpdate = time();
							$date = date('Y-m-d', $timeUpdate);
								// $dateToLocale=Yii::app()->dateFormatter->formatDateTime(CDateTimeParser::parse($date, 'yyyy-MM-dd'),'medium',null);


							$outputData = array();
							$outputData['offer_id'] = $newofferId;
							$outputData['offer_type'] = $msg['type'];// acept,decline,sendreceive
							$outputData['offer_price'] = $msg['price'];
								if($_POST['lang_type'] == "ar")
									$outputData['formatted_offer_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($msg['currency'],$msg['price']);
								else
									$outputData['formatted_offer_price'] = yii::$app->Myclass->getFormattingCurrencyapi($msg['currency'],$msg['price']);
							$outputData['offer_currency'] = $offerCurrency[0];
							$outputData['offer_currency_code'] = $offerCurrency[1];

							$currency_formats = yii::$app->Myclass->getCurrencyFormats($msg['currency']);
							if($currency_formats[0] != "")
									$outputData['currency_mode'] = $currency_formats[0];
							if($currency_formats[1] != "")
									$outputData['currency_position'] = $currency_formats[1];


								$total_amount = $productDetails->shippingCost + $msg['price'];
								if($_POST['lang_type'] == "ar")
									$outputData['formatted_shipping_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($msg['currency'],$productDetails->shippingCost);
								else
									$outputData['formatted_shipping_price'] = yii::$app->Myclass->getFormattingCurrencyapi($msg['currency'],$productDetails->shippingCost);

								if($_POST['lang_type'] == "ar")
									$outputData['formatted_total_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($msg['currency'],$total_amount);
								else
									$outputData['formatted_total_price'] = yii::$app->Myclass->getFormattingCurrencyapi($msg['currency'],$total_amount);

							$outputData['total_price'] = $total_amount;
							$outputData['shipping_cost'] = $productDetails->shippingCost;
							$outputData['offer_status'] = $msg['offerstatus'];// 0-pending,1-accept,2-decline
							$outputData['buynow_status'] = $msg['buynowstatus'];	// offer is buy or not ,0-pending,1-alreadybought
							$outputData['instant_buy'] = $productDetails->instantBuy; // 1-buy available,0-not avaiable
							$outputData['sold_item'] = $productDetails->soldItem;//1- sold,0-available
							$outputData['site_buynowPaymentMode'] = $sitePaymentModes['buynowPaymentMode'];
							$outputData['item_image'] = $proImageUrl;
							$outputData['buynow_url'] = $buynow_URL;
							$outputData['item_id'] = $productDetails->productId;
							$outputData['chatTimeWeb'] = $date;


							$sellerDtls = yii::$app->Myclass->getUserDetailss($productDetails->userId);
							$outputData['seller_name'] = $sellerDtls->username;
								$userdevicedet = Userdevices::find()->where(['user_id' => $senderId])->one();
							if (count($userdevicedet) > 0) {

								if($userdevicedet->lang_type == "ar")
									$notifyofferprice = yii::$app->Myclass->arabicgetFormattingCurrencyapi($msg['currency'],$msg['price']);
								else
									$notifyofferprice = yii::$app->Myclass->getFormattingCurrencyapi($msg['currency'],$msg['price']);

							}


						//	$notifyMessage = $content. ' your offer request on ' .$offerCurrency[0]." ".$mkeOfferPrice;
							$notifyMessage = $content.' '.'your offer request on '.$productDetails->name." ".":" .$notifyofferprice;
							$empty=0;
							$type="myoffer";
							
							$a=yii::$app->Myclass->addLogs($type, $productDetails->userId, $senderId, $empty, $productDetails->productId, $notifyMessage);

							$userdevicedet = Userdevices::find()->where(['user_id' => $senderId])->all();
							if (count($userdevicedet) > 0) {
								foreach ($userdevicedet as $userdevice) {
									$deviceToken = $userdevice->deviceToken;
									$lang = $userdevice->lang_type;
									$badge = $userdevice->badge;
									$badge += 1;
									$userdevice->badge = $badge;
									$userdevice->deviceToken = $deviceToken;
									$userdevice->save(false);
									if (isset($deviceToken)) {
										$msg = yii::$app->Myclass->push_lang($lang);
										$msg = $sellerDtls->name;
										$langcontent = Yii::t('app',$content);
										$requestcontent = Yii::t('app',' your offer request on ');
										$for = Yii::t('app'," for ");
										$messages = $msg." ".$langcontent.' '.$requestcontent." ".$productDetails->name;
										yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
									}
								}
							}

							$final = Json::encode($outputData);
							return '{"status": "true", "message":"' . $message . '","result":' . $final . '}';
							//return  '{"status":"true", "message":"'.$message.'","offerId":"'.$messageModel->messageId.'"}';
						} else {
							return '{"status":"false","message":"Offer aleady accept/decline"}';
						}

					} else {
						return '{"status":"false","message":"Something went to be wrong"}';
					}
				} else {
					return '{"status":"false","message":"block status unable to make process"}';
				} //if block show error this line
			} else {
				return '{"status":"false","message":"Something went to be wrong"}'; //user not found
			}
			//
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}

	}
	/* 
		SEND OFFER REQ PARAMS - $api_username, $api_password, $sender_id, $source_id, $chat_id, $created_date, $message, $offer_price
	 */

	public function actionSendofferreq()
	{
		//Post Values - finished (email)
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {

			$sender_id = '0';
			if (isset($_POST['sender_id'])) {
				$sender_id = $_POST['sender_id'];
			}
			$userDetails = yii::$app->Myclass->getUserDetailss($sender_id);
			if (!empty($userDetails)) {

				$senderId = $sender_id;
				$chat_id = '';
				if (isset($_POST['chat_id'])) {
					$chat_id = $_POST['chat_id'];
				}
				$message = '';
				if (isset($_POST['message'])) {
					$message = $_POST['message'];
				}
				$name = $userDetails->name;
				$email = $userDetails->email;
				$phone = $userDetails->phone;
				$productId = '';
				if (isset($_POST['source_id'])) {
					$productId = $_POST['source_id'];
				}
				$productModel = Products::findOne($productId);
				$productURL = Yii::$app->urlManager->createAbsoluteUrl('products/view') . '/' . yii::$app->Myclass->safe_b64encode($productModel['productId'] . '-' . rand(0, 999)) . '/' . yii::$app->Myclass->productSlug($productModel['name']);
				$seller_id = $productModel['userId'];
				//print_r($productURL);exit;
				$sellerDetails = yii::$app->Myclass->getUserDetailss($seller_id);
				$receiverId = $seller_id;
				$sellerEmail = $sellerDetails['email'];
				$sellerName = $sellerDetails['name'];
				$offerRate = $_POST['offer_price'];
				$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				$timeUpdate = '0';
				if (isset($_POST['created_date'])) {
					$timeUpdate = $_POST['created_date'];
				}
				$senderDetails = yii::$app->Myclass->getUserDetailss($sender_id);
				$checkBlockStatus = yii::$app->Myclass->getWhosBlock($sender_id, $seller_id); //new changes
				if ($checkBlockStatus == 0) {
					$chatModel = Chats::find()->where(['chatId' => $chat_id])->one();
					if ($chatModel->user1 == $senderId) {
						$chatModel->lastToRead = $chatModel->user2;
					} else {
						$chatModel->lastToRead = $chatModel->user1;
					}
					$chatModel->lastContacted = time();
					$chatModel->lastMessage = $message;
					$chatModel->save();

					$offerMessage['message'] = $message;
					$offerMessage['price'] = $offerRate;
						if($_POST['lang_type'] == "ar")
								$offerMessage['formatted_price'] = yii::$app->Myclass->arabicgetFormattingCurrencyapi($productModel['currency'],$offerRate);
						else
							$offerMessage['formatted_price'] = yii::$app->Myclass->getFormattingCurrencyapi($productModel['currency'],$offerRate);
					$offerMessage['currency'] = $productModel['currency'];

					$currency_formats = yii::$app->Myclass->getCurrencyFormats($productModel['currency']);
					if($currency_formats[0] != "")
									$offerMessage['currency_mode'] = $currency_formats[0];
					if($currency_formats[1] != "")
									$offerMessage['currency_position'] = $currency_formats[1];

					// New keys for my offer section
					$offerMessage['offerstatus'] = 0;
					$offerMessage['type'] = 'sendreceive'; // sendreceive,accept,decline
					$offerMessage['msgsourceid'] = 0;
					$offerMessage['buynowstatus'] = 0; //0-pending,1 - buyed

					// end my offer section
					$offerMessage = Json::encode($offerMessage);

					$messageModel = new Messages();
					$messageModel->message = $offerMessage;
					$messageModel->messageType = "offer";
					$messageModel->senderId = $senderId;
					$messageModel->sourceId = $productId;
					$messageModel->chatId = $chat_id;
					$messageModel->createdDate = $timeUpdate;
					$messageModel->save();

					$userdevicedet = Userdevices::find()->where(['user_id' => $receiverId])->one();
					if (count($userdevicedet) > 0) {
						if($userdevicedet->lang_type == "ar")
							$notifyofferprice = yii::$app->Myclass->arabicgetFormattingCurrencyapi($productModel['currency'],$offerRate);
						else
							$notifyofferprice = yii::$app->Myclass->getFormattingCurrencyapi($productModel['currency'],$offerRate);
					}
					// $notifyMessage = 'sent offer request ' . $productModel['currency'] . $offerRate . ' on your product';
					$notifyMessage = 'sent offer request on your product'.' '.$productModel->name.':'.$notifyofferprice;

					yii::$app->Myclass->addLogs("myoffer", $senderId, $receiverId, 0, $productId, $notifyMessage);
					$userid = $sender_id;
					$userdevicedet = Userdevices::find()->where(['user_id' => $receiverId])->all();
					$userdata = Users::findOne($senderId);
					$currentusername = $userdata->name;
					if (count($userdevicedet) > 0) {
						foreach ($userdevicedet as $userdevice) {
							$deviceToken = $userdevice->deviceToken;
							$lang = $userdevice->lang_type;
							$badge = $userdevice->badge;
							$badge += 1;
							$userdevice->badge = $badge;
							$userdevice->deviceToken = $deviceToken;
							$userdevice->save(false);
							if (isset($deviceToken)) {
								$msg = yii::$app->Myclass->push_lang($lang);
								$text = 'sent offer request';
								$msg = Yii::t('app', $text);
								$text1 = 'on your product';
								$msg1 = Yii::t('app', $text1);
								$currency = explode('-', $productModel->currency);
								$messages = $currentusername . " " . $msg ." ". $msg1 . " " . $productModel->name;
								yii::$app->Myclass->pushnot($deviceToken, $messages, $badge);
							}
						}
					}

					if ($siteSettings->smtpEnable == 1) {
						$mailer = Yii::$app->mailer->setTransport([
							'class' => 'Swift_SmtpTransport',
							'host' => $siteSettings['smtpHost'],
							'username' => $siteSettings['smtpEmail'],
							'password' => $siteSettings['smtpPassword'],
							'port' => $siteSettings['smtpPort'],
							'encryption' => 'tls',
						]);
						try {
							if ($messageModel->sendEmail(
								$sellerEmail,
								$name,
								$email,
								$phone,
								$offerRate,
								$message,
								$sellerName,
								$productModel->currency,
								$productURL
							)) {
								return '{"status":"true","message":"Message sent successfully"}';
							}
						} catch (\Swift_TransportException $exception) {
							return '{"status":"true","message":"Message sent successfully"}';
						} catch (\Exception $e) {
							return '{"status":"true","message":"Message sent successfully"}';
						}
					} else {
						return '{"status":"true","message":"Message sent successfully"}';
					}
				} //new
				else {
					if ($checkBlockStatus == 1) {
						$msg = $sellerName . " has been blocked by you";
						return '{"status":"false","message":"' . $msg . '"}'; //seller blocked by you
					} else {
						$msg = "you blocked " . $senderDetails->name;
						return '{"status":"false","message":"' . $msg . '"}';
					}
				}

			} else {
				return '{"status":"false","message":"Message cannot be send"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		PUSH SIGNOUT PARAMS - $api_username, $api_password, $deviceId
	 */
	public function actionPushsignout()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$deviceId = $_POST['deviceId'];
			if (isset($deviceId) && trim($deviceId) != '') {
				Userdevices::deleteAll(['deviceId' => $deviceId]);
				return '{"status":"true","result":"Unregistered successfully"}';
			} else {
				return '{"status":"false","result":"Something went wrong, please try again later"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		TOS PARAMS - $api_username, $api_password
	 */
	public function actionTos()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$id = 2;
			$Helppages = Helppages::find()->where(['id' => $id])->one();

			if (!empty($Helppages)) {

				$help_lang =  $_POST['lang_type'];  
         		 $helpcontent = Json::decode($Helppages->pageContent,true);

		        if ($helpcontent!="") {
		             
		         if (array_key_exists($help_lang, $helpcontent)) {
		      
		           	$help_desc = $helpcontent[$help_lang]['content'];
		         } 

		               else
		         {


		         	  $firstelem = array_keys($helpcontent)[0];
     				  $help_desc = $helpcontent[$firstelem]['content'];
		         }


		     	}

				

				$pagetitle = $help_desc;
				$final = Json::encode($pagetitle);
				return '{"status":"true","message":' . $final . '}';
			} else {
				return '{"status":"false", "message":"Sorry, Something went to be wrong"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	public function actionSafetyTips()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$id = 3;
			$Helppages = Helppages::find()->where(['id' => $id])->one();

			if (!empty($Helppages)) {

				 $help_lang =  $_POST['lang_type'];  
         		 $helpcontent = Json::decode($Helppages->pageContent,true);

		        if ($helpcontent!="") {
		             
		         if (array_key_exists($help_lang, $helpcontent)) {
		      
		           	$help_desc = $helpcontent[$help_lang]['content'];
		         } 

		     	}

				$pagetitle = $help_desc;
				$final = Json::encode($pagetitle);
				return '{"status":"true","message":' . $final . '}';
			} else {
				return '{"status":"false", "message":"Sorry, Something went to be wrong"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	/* 
		CHAT ACTION PARAMS - api_username, api_password, user_id, action_id, action_value
	 */
	public function actionChataction()
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			if (isset($_POST['user_id'])) {
				$userId = $_POST['user_id'];
			}
			$actionId = '';
			if (isset($_POST['action_id'])) {
				$actionId = $_POST['action_id'];
			}

			if ($this->checking($userId) && $this->checking($actionId) && $userId != $actionId) {
				$actionValue = strtolower($_POST['action_value']);
				if ($actionValue == "block" || $actionValue == "unblock") {
					$result = yii::$app->Myclass->Change_chatUser_status($actionValue, $userId, $actionId);
					$result = explode("~#~", $result);
					if ($result[0] == "blocked")
						return '{"status":"true", "message":"Blocked Successfully"}';
					elseif ($result[0] == "unblocked")
						return '{"status":"true", "message":"Unblocked Successfully"}';
					else
						return '{"status":"false", "message":"Something went wrong"}';
				} else {
					return '{"status":"false", "message":"Something went wrong"}';
				}
			} else {
				return '{"status":"false", "message":"Something went wrong 23"}';
			}
		}
	}	

		
	/* 
		CONFIRN PHONE PARAMS - $api_username, $api_password,$user_id, $mob_no,$country_code
	 */

	public function actionConfirmphone()
	{
    		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$user_id = $_POST['user_id'];
			$mob_no = $_POST['mob_no'];
			$sms_country_code = $_POST['country_code'];

            // $userCondition = new CDbCriteria;
            // $userCondition->addCondition('userId = "'.$user_id.'"');
			$loguserdetails = Users::find()->where(['userId' => $user_id])->one();
			$loguserdetails->sms_country_code = $sms_country_code;
			$loguserdetails->phone = $mob_no;
			$loguserdetails->mobile_status = '1';
			if ($loguserdetails->save(false))
				return '{"status":"true","message":"Your mobile number verified successfully"}';
			else
				return '{"status":"false","message":"Sorry, Something went to be wrong"}';
		}
	}

	public function actionData()
	{
		$id = Yii::$app->request->post('id');
		return '{"status":"true","message":"Listing unreported successfully"}';
	}

	public function actionAk() 
	{
		$advFilter[] = "and"; 
		$criteria = new Query;
		$criteria->select(['hts_productfilters.id', 'u.filter_id AS super_id', 'u.name AS super_name', 'v.id AS parent_id', 'v.name AS parent_name', 'w.id AS child_id', 'w.name AS child_name', 'hts_productfilters.filter_type', 'hts_productfilters.filter_values', 'hts_filter.value']);    
		$criteria->from('hts_productfilters'); 
		$subQuery =(new Query())->select('*')->from('hts_filtervalues');
		$criteria->leftJoin('hts_filter', 'hts_filter.id = hts_productfilters.level_one'); 
		$criteria->leftJoin(['u' => $subQuery], 'u.filter_id = hts_productfilters.level_one');	
		$criteria->leftJoin(['v' => $subQuery], 'v.id = hts_productfilters.level_two');	
		$criteria->leftJoin(['w' => $subQuery], 'w.id = hts_productfilters.level_three');
		
		$advFilter[] =  ['or',
								['and',
									['=','hts_productfilters.filter_type','dropdown'],
									['=','hts_productfilters.level_three',0],	
							 	],
							 	['and',
									['=','hts_productfilters.filter_type','multilevel'],
									['>','hts_productfilters.level_one',0],	
									['>','hts_productfilters.level_two',0],	
									['>','hts_productfilters.level_three',0], 
							 	], 
							 	['and',
									['=','hts_productfilters.filter_type','range'],
									['=','hts_productfilters.level_three',0],
							 	] 
							 ];
		$filtersCheck = [34, 30];
		$criteria->andWhere(['IN', 'hts_productfilters.product_id', $filtersCheck]);  
		$criteria->andWhere(['=', 'u.parentid', 0]);  
		$criteria->andWhere($advFilter); 
		$criteria->groupBy('hts_productfilters.id'); 
		$categoryModel = $criteria->createCommand()->queryAll();  
		echo "asdads ".$criteria->createCommand()->getRawSql();   
		
	}


	// AK update Starts

	public function actionGetuserproducts() { 
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$lang_type = $_POST['lang_type'];
			yii::$app->Myclass->push_lang($lang_type);
			$userId = (isset($_POST['user_id']) && !empty(trim($_POST['user_id']))) ? trim($_POST['user_id']) : ""; 
			$productId = (isset($_POST['product_id']) && !empty(trim($_POST['product_id']))) ? trim($_POST['product_id']) : "";
			$categoryId = (isset($_POST['category_id']) && !empty(trim($_POST['category_id']))) ? trim($_POST['category_id']) : "";
			$subCategoryId = (isset($_POST['subcategory_id']) && !empty(trim($_POST['subcategory_id']))) ? trim($_POST['subcategory_id']) : 0;   

			if(empty($productId) || empty($categoryId)) { 
				return '{"status":"false","message":"Sorry, Something went to be wrong"}';
			} else { 
				$condition[] = 'and';
				$modelFlag = ""; 

				if($subCategoryId != 0 && $subCategoryId > 0) {
					$condition[] = ['=','subCategory', $subCategoryId];
					$modelFlag = "1";
				}
				if(empty($modelFlag)){
					$condition[] = ['=','category', $categoryId]; 
				}

				$condition[] = ['<>','soldItem', 1];
				$condition[] = ['<>','productId', $productId];

				$interestModel = Products::find()->where($condition)->andWhere(['=','approvedStatus',1])->andWhere(['<>','productId', $productId])->orderBy(['productId' => SORT_DESC])->limit(8)->all();

				if (!empty($interestModel)) {
					if ($userId != 0) {
						$result = $this->convertJsonItems($interestModel, $userId);
					} else {
						$result = $this->convertJsonItems($interestModel);
					}
					return '{"status": "true","result":' . $result . '}';
				} else {
					return '{"status":"false","message":"No item found"}'; 
				}  
			}
		} 
	}
 
	public function actionProductbeforeadd() 
	{
		//Post Values - finished
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$lang_type = $_POST['lang_type'];

			yii::$app->Myclass->push_lang($lang_type);
			$result = array();
			$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$maincategoryModel = Categories::find()->where(['parentCategory' => 0])->all();
			$subcategoryModel = Categories::find()->where(['!=', 'parentCategory', 0])->all();
			$categories = array();
			$subcategories = array();
			$maincategoryImage = array();
			foreach ($subcategoryModel as $subcategory) {
				$subcategories[$subcategory->parentCategory][$subcategory->categoryId] = $subcategory->name;
			}

			foreach (json_decode($siteSettings->category_priority) as $catkey => $id) {
				$maincategory = Categories::findOne($id);
				$imageUrl = Yii::$app->urlManager->createAbsoluteUrl('/admin/uploads/' . $maincategory->image);
				$result['category'][$catkey]['category_id'] = "$maincategory->categoryId";
				$result['category'][$catkey]['category_name'] = Yii::t('app', $maincategory->name);
				$result['category'][$catkey]['category_img'] = $imageUrl;
				$categoryRules = Json::decode($maincategory->categoryProperty, true);
				//{"itemCondition":"enable","exchangetoBuy":"enable","myOffer":"enable","contactSeller":"disable"}

				$result['category'][$catkey]['product_condition'] = isset($categoryRules['itemCondition']) ? trim($categoryRules['itemCondition']) : "disable";

				$result['category'][$catkey]['exchange_buy'] = isset($categoryRules['exchangetoBuy']) ? trim($categoryRules['exchangetoBuy']) : "disable";

				$result['category'][$catkey]['make_offer'] = isset($categoryRules['myOffer']) ? trim($categoryRules['myOffer']) : "disable";

				$result['category'][$catkey]['instant_buy'] =  isset($categoryRules['buyNow']) ? trim($categoryRules['buyNow']) : "disable";

				//Category Filters
				$result['category'][$catkey]['filters'] = $this->getFilters($maincategory->categoryId); 

				$result['category'][$catkey]['subcategory'] = array();
				if (isset($subcategories[$maincategory->categoryId])) {
					$relatedSubcategory = $subcategories[$maincategory->categoryId];
					$relatedkey = 0;
					foreach ($relatedSubcategory as $relatedCategorykey => $relatedCategory) {
						$result['category'][$catkey]['subcategory'][$relatedkey]['sub_id'] = "$relatedCategorykey";
						$result['category'][$catkey]['subcategory'][$relatedkey]['sub_name'] = Yii::t('app', $relatedCategory);

						//Sub Category Filters  
						$result['category'][$catkey]['subcategory'][$relatedkey]['filters'] = $this->getFilters("$relatedCategorykey");

						//third level Category
						$sub_subcategoryModel = Categories::find()->where(['=', 'parentCategory', $relatedCategorykey])->all();

						$relatedsubkey = 0;
						foreach ($sub_subcategoryModel as $relatedsub_Category) {
							$result['category'][$catkey]['subcategory'][$relatedkey]['child_category'][$relatedsubkey]['child_id'] = "$relatedsub_Category->categoryId";
							$result['category'][$catkey]['subcategory'][$relatedkey]['child_category'][$relatedsubkey]['child_name'] = Yii::t('app', $relatedsub_Category->name);
							$result['category'][$catkey]['subcategory'][$relatedkey]['child_category'][$relatedsubkey]['filters'] = $this->getFilters("$relatedsub_Category->categoryId");

							$relatedsubkey++;
						}

						$relatedkey++; 
					}
				}

			}
			
			if ($siteSettings->givingaway == "yes")
				$result['giving_away'] = 'enable';
			else
				$result['giving_away'] = 'disable';

				if ($siteSettings->searchList == '')
				$result['distance'] = '';
			else
				$result['distance'] = $siteSettings->searchList;

				if ($siteSettings->searchType == 'miles') {
					$distanceType = 'mi';
				} else {
					$distanceType = 'km';
				}
				$result['search_type'] = $distanceType;

				$countryModel = Country::find()->all();
			$currencyModel = Currencies::find()->all();
			$productConditionModel = Productconditions::find()->all();

			foreach ($productConditionModel as $productConditionKey => $productCondition) {
				$result['product_condition'][$productConditionKey]['name'] = Yii::t('app', $productCondition->condition);
				$result['product_condition'][$productConditionKey]['id'] = $productCondition->id;
			}

			foreach ($currencyModel as $currencykey => $currency) {
				$result['currency'][$currencykey]['id'] = $currency->id;
				$result['currency'][$currencykey]['symbol'] = $currency->currency_shortcode .
					"-" . $currency->currency_symbol;
			}

			foreach ($countryModel as $countrykey => $country) {
				$result['country'][$countrykey]['country_id'] = $country->countryId;
				$result['country'][$countrykey]['country_code'] = $country->code;
				yii::$app->Myclass->push_lang($lang_type);
				//$result['country'][$countrykey]['country_name'] = Yii::t('app',$country->country);
				$result['country'][$countrykey]['country_name'] = Yii::t('app',$country->country);
			}
			$result['shipDeliveryTime'][0]['id'] = '1 business day';
			$result['shipDeliveryTime'][0]['Time'] = '1 business day';
			$result['shipDeliveryTime'][1]['id'] = '1-2 business day';
			$result['shipDeliveryTime'][1]['Time'] = '1-2 business day';
			$result['shipDeliveryTime'][2]['id'] = '2-3 business day';
			$result['shipDeliveryTime'][2]['Time'] = '2-3 business day';
			$result['shipDeliveryTime'][3]['id'] = '3-5 business day';
			$result['shipDeliveryTime'][3]['Time'] = '3-5 business day';
			$result['shipDeliveryTime'][4]['id'] = '1-2 weeks';
			$result['shipDeliveryTime'][4]['Time'] = '1-2 weeks';
			$result['shipDeliveryTime'][5]['id'] = '2-4 weeks';
			$result['shipDeliveryTime'][5]['Time'] = '2-4 weeks';
			$result['shipDeliveryTime'][6]['id'] = '5-8 weeks';
			$result['shipDeliveryTime'][6]['Time'] = '5-8 weeks';

			if (!empty($result)) {
				return '{"status": "true","result":' . Json::encode($result) . '}';
				die;
			} else {
				return '{"status": "false","message":"No data found"}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}


	public function actionGetcategory() 
	{ 
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];
		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$lang_type = $_POST['lang_type'];
			yii::$app->Myclass->push_lang($lang_type);

			//Declaration
			$categories = array();
			$subcategories = array();
			$maincategoryImage = array();

			//Site Settings
			$siteSettings = Sitesettings::find()->orderBy(['id'=>SORT_DESC])->one();
			
			//Main Category
			$maincategoryModel = Categories::find()->where(['=', 'parentCategory', 0])->all();

			//Sub Category
			$subcategoryModel = Categories::find()->where(['!=', 'parentCategory', 0])->all();

			
			foreach ($subcategoryModel as $subcategory) {
				$subcategories[$subcategory->parentCategory][$subcategory->categoryId] = $subcategory->name;
			}
			
			foreach (json_decode($siteSettings->category_priority) as $catkey => $id) {
				$maincategory = Categories::findOne($id);
				$imageUrl = Yii::$app->urlManager->createAbsoluteUrl('/backend/web/uploads/' . $maincategory->image);
				$categories['category'][$catkey]['category_id'] = $maincategory->categoryId;
				$categories['category'][$catkey]['category_name'] = Yii::t('app', $maincategory->name);
				$categories['category'][$catkey]['category_img'] = $imageUrl;
				$categories['category'][$catkey]['filters'] = $this->getFilters($maincategory->categoryId); 

				$categories['category'][$catkey]['subcategory'] = array();
				if (isset($subcategories[$maincategory->categoryId])) {
					$relatedSubcategory = $subcategories[$maincategory->categoryId];
					$relatedkey = 0;
					foreach ($relatedSubcategory as $relatedCategorykey => $relatedCategory) {
						$categories['category'][$catkey]['subcategory'][$relatedkey]['sub_id'] = "$relatedCategorykey";
						$categories['category'][$catkey]['subcategory'][$relatedkey]['sub_name'] = Yii::t('app', $relatedCategory);
						$categories['category'][$catkey]['subcategory'][$relatedkey]['filters'] = $this->getFilters("$relatedCategorykey");
										
						//third level Category
						$sub_subcategoryModel = Categories::find()->where(['=', 'parentCategory', $relatedCategorykey])->all();

						$relatedsubkey = 0;
						foreach ($sub_subcategoryModel as $relatedsub_Category) {
							$categories['category'][$catkey]['subcategory'][$relatedkey]['child_category'][$relatedsubkey]['child_id'] = "$relatedsub_Category->categoryId";
							$categories['category'][$catkey]['subcategory'][$relatedkey]['child_category'][$relatedsubkey]['child_name'] = Yii::t('app', $relatedsub_Category->name);
							$categories['category'][$catkey]['subcategory'][$relatedkey]['child_category'][$relatedsubkey]['filters'] = $this->getFilters("$relatedsub_Category->categoryId");

							$relatedsubkey++;
						}

						$relatedkey++;
					}
				}
			}

			$productConditionModel = Productconditions::find()->all();

			foreach ($productConditionModel as $productConditionKey => $productCondition) {
				$categories['product_condition'][$productConditionKey]['name'] = Yii::t('app', $productCondition->condition);
				$categories['product_condition'][$productConditionKey]['id'] =$productCondition->id;
			}

			return '{"status": "true","result":' . Json::encode($categories) . '}';
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	public function getFilters($catId) {  
		$categoryModel = Categories::find()->where(['=', 'categoryId', $catId])->one();
		$filtersCheck = array_filter(explode(',', $categoryModel->categoryAttributes));
		$query = new Query;
		$query->select(['hts_categories.categoryId','hts_categories.name','hts_categories.name', 'hts_filter.type', 'hts_filter.name AS filter_named', 'hts_filtervalues.filter_id', 'hts_filtervalues.id AS c_id', 'hts_filtervalues.name AS c_name', 'hts_filtervalues.parentid AS c_parentid', 'hts_filtervalues.parentlevel AS c_parentlevel']);  
		$query->from('hts_categories');
		$query->leftJoin('hts_filter', ['IN', 'hts_filter.id', $filtersCheck]); 
		$query->leftJoin('hts_filtervalues', 'hts_filtervalues.filter_id = hts_filter.id');   
		$query->where(['=', 'hts_categories.categoryId', $catId]);  
		$query->orderBy('hts_categories.categoryId asc'); 
		$categoryModel = $query->createCommand()->queryAll(); 

		$mainFilterFlag = 0;
		$multiFilterFlag = 0;
		$resultArray = array();

		foreach ($categoryModel as $key => $data) { 
			if($data['c_parentid'] == 0 && $data['c_parentlevel'] == 0 && $data['type'] != NULL) {
				$cntFlag = count($resultArray);
				$mainFilterFlag = $data['c_id'];
				$resultArray[$cntFlag]['id'] = $data['filter_id'];
				$resultArray[$cntFlag]['label'] = Yii::t('app', $data['c_name']);
				$resultArray[$cntFlag]['type'] = $data['type'];
				$incFlag = 0;
			}

			if($data['type']=="dropdown" && $data['c_parentid']==$mainFilterFlag) {
				$resultArray[$cntFlag]['values'][$incFlag]['id'] = $data['c_id'];
				$resultArray[$cntFlag]['values'][$incFlag]['name'] = Yii::t('app', $data['c_name']);
				++$incFlag; 
			} 
			if($data['type']=="range" && $data['c_parentid']==$mainFilterFlag && $incFlag <= 1) {
				$rangeValFlag = ($incFlag == 0) ? "min_value" : "max_value";
				$resultArray[$cntFlag][$rangeValFlag] = Yii::t('app', $data['c_name']); 
				++$incFlag;   
			} 

			if($data['type']=="multilevel" && $data['c_parentid']==$mainFilterFlag) {
				$multiFilterFlag = $data['c_id'];
				$resultArray[$cntFlag]['values'][$incFlag]['parent_id'] = $data['c_id'];
				$resultArray[$cntFlag]['values'][$incFlag]['parent_label'] = Yii::t('app', $data['c_name']);
				$main_mlFlag = $incFlag; 
				++$incFlag; 
				$mlFlag = 0;
			} 
			if($data['type']=="multilevel" && $data['c_parentid']==$multiFilterFlag && $data['c_parentlevel'] == 4) 
			{
				$resultArray[$cntFlag]['values'][$main_mlFlag]['parent_values'][$mlFlag]['child_id'] = $data['c_id'];
				$resultArray[$cntFlag]['values'][$main_mlFlag]['parent_values'][$mlFlag]['child_name'] = Yii::t('app', $data['c_name']);
				++$mlFlag;  
			}  
			
		}
		//return $query->createCommand()->getRawSql(); 
		return $resultArray; 	    
	}
	// AK update Ends 


	// joysale v4.0 updates

	public function actionAddbanner() {
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {

				date_default_timezone_set('Asia/Kolkata');
				

				$cu_code = '';
				if (isset($_POST['currency_code'])) {
					$cu_code = $_POST['currency_code'];
				}
				$nonce = '';
				if (isset($_POST['nonce'])) {
					$nonce = $_POST['nonce'];
				}
				$app_banner = '';
				if (isset($_POST['app_banner_url'])) {
					$temp = explode('/tmp/',$_POST['app_banner_url']);
					$app_banner = $temp[1];
				}
				$web_banner = '';
				if (isset($_POST['web_banner_url'])) {
					$temp = explode('/tmp/',$_POST['web_banner_url']);
					$web_banner = $temp[1];
				}
				$app_link = '';
				if (isset($_POST['app_banner_link'])) {
					$app_link = $_POST['app_banner_link'];
				}
				$web_link = '';
				if (isset($_POST['web_banner_link'])) {
					$web_link = $_POST['web_banner_link'];
				}
				$start_date = '';
				if (isset($_POST['start_date'])) {
					$start_date = $_POST['start_date'];
					
					$sdate = date('Y-m-d\TH:i:s.B\Z',strtotime($start_date));
					// $sdate = date("Y-m-d",strtotime($start_date));
				}
				$end_date = '';
				if (isset($_POST['end_date'])) {
					$end_date = $_POST['end_date'];
					$edate = date('Y-m-d\TH:i:s.B\Z',strtotime($end_date));
					// $edate = date("Y-m-d",strtotime($end_date));
				}

				$path = Yii::$app->getBasePath()."/web/media/banners/";
				if (!is_dir($path)) {
					mkdir($path);
					chmod($path, 0777);
					echo 'created';
				}
				$price = '';
				if (isset($_POST['price'])) {
					$price = $_POST['price'];
				}

				$payment_type = $_POST['payment_type'];

				$path1 = realpath(Yii::$app->basePath . '/../');
				$app_banner_path = realpath($path1 . '/frontend/web/media/banners') . '/' . $app_banner;
				$web_banner_path = realpath($path1 . '/frontend/web/media/banners') . '/' . $web_banner;

				$siteSettings = Sitesettings::find()->where(['id'=>'1'])->one();
				$bannerCurrency = $siteSettings->promotionCurrency; 
				$currencyDetails = explode('-', $bannerCurrency);
				$bannerCurrency = trim($currencyDetails[0]);

			
				$date1 = date_create($sdate);
				$date2 = date_create($edate);
				$diff = date_diff($date1,$date2);

				$total_days =$diff->format("%a")+1; 
				$createdDate = date("Y-m-d");
				$Per_day_amount = $siteSettings->ad_price;   
				$total_amount = (int) $total_days * $Per_day_amount;
				//$total_amount = $price;

		if($payment_type == "stripe") {
			$stripeSettings = Json::decode($siteSettings->stripe_settings, true);
			$secretkey=$stripeSettings['stripePrivateKey'];
			$url = 'https://api.stripe.com/v1/charges';
			$data = array('amount' => $total_amount * 100, 'currency' => $cu_code, 'source' => $nonce);
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
		}
		else{
			$brainTreeSettings = Json::decode($siteSettings->braintree_settings, true);
				$merchant_account_id = yii::$app->Myclass->getbraintreemerchantid($bannerCurrency);

				$paymenttype = "sandbox";
				if ($brainTreeSettings['brainTreeType'] == 1) {
					$paymenttype = "production";
				}
				$merchantid = $brainTreeSettings['brainTreeMerchantId'];
				$publickey = $brainTreeSettings['brainTreePublicKey'];
				$privatekey = $brainTreeSettings['brainTreePrivateKey'];



				Braintree\Configuration::environment($paymenttype);
				Braintree\Configuration::merchantId($merchantid);
				Braintree\Configuration::publicKey($publickey);
				Braintree\Configuration::privateKey($privatekey);

				if (empty($merchant_account_id)) {
					return '{"status":"false","message":"Sorry, Something went wrong."}';
				} else {
					$result = Braintree\Transaction::sale([
						'amount' => $total_amount,
						'merchantAccountId' => $merchant_account_id,
						'paymentMethodNonce' => $nonce
					]);


					$result1 = Braintree\Transaction::submitForSettlement($result->transaction->id);
					}
				}
		
				

					

					if ($result->success || !is_null($result->transaction->id) && $result1->success == '1' || $output['status'] == 'succeeded' ) {
						$banner = new Banners();
						if (is_file($app_banner_path)) {
							rename($app_banner_path, $path . $app_banner);
						}

						if (is_file($web_banner_path)) {
						 rename($web_banner_path, $path . $web_banner);
						}

						$banner->appbannerimage = $app_banner;
						$banner->bannerimage = $web_banner;
						$banner->bannerurl = $web_link;
						$banner->appurl = $app_link;
						$banner->userid = $user_id;
						$banner->startdate = $sdate;
						$banner->enddate = $edate;
						$banner->totaldays = $total_days;
						$banner->totalCost=round($total_amount,2);
						$banner->paidstatus =1;
						$banner->status =0;  
						$banner->createdDate=gmdate("Y-m-d\TH:i:s.B\Z");
						$banner->currency=$bannerCurrency;
						if($payment_type == "stripe") {
						$banner->tranxId =$output['id'];  
						$banner->paymentMethod='Stripe';
						
						}
						else
						{
						$banner->paymentMethod='Braintree';
						$banner->tranxId =$result->transaction->id;  
						
						}
						$banner->trackPayment='Paid';
						if(!$banner->save(false))
						{
							return '{"status":"false","message":"Something went wrong."}';
						}
					}
			
				return '{"status":"true", "message":"Banner added successfully "}';
			} else {
				return '{"status":"false","message":"Sorry, Something went wrong."}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	public function actionBanneravailability()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$user_id = $_POST['user_id'];

			if ($this->checking($user_id)) {
				$start_date = '';
				if (isset($_POST['start_date'])) {
					$start_date = $_POST['start_date'];
					date_default_timezone_set('Asia/Kolkata');
					$sdate = date('Y-m-d\TH:i:s.B\Z',strtotime($start_date));
					$s_date = date('Y-m-d',strtotime($start_date));
	
				}
				$end_date = '';
				if (isset($_POST['end_date'])) {
					$end_date = $_POST['end_date'];
					date_default_timezone_set('Asia/Kolkata');
					$edate = date('Y-m-d\TH:i:s.B\Z',strtotime($end_date));
					$e_date = date('Y-m-d',strtotime($end_date));
				}

				$settings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
				$limit = $settings->ad_limit;
				$interval = new \DateInterval('P1D');
				$stop_date = date('Y-m-d', strtotime($e_date . ' +1 day'));
				$period = new \DatePeriod(new \DateTime($s_date), $interval, new \DateTime($stop_date));
				
				foreach ( $period as $dt ){
					$eachDate = $dt->format( "Y-m-d" );
					$the_date = strtotime($eachDate);
					$query = "SELECT * FROM `hts_banners` WHERE DATE_FORMAT(`startdate`,'%Y-%m-%d') <=  '$eachDate' AND DATE_FORMAT(`enddate`,'%Y-%m-%d') >=  '$eachDate' AND `status` = 'approved'";
					$count = Banners::findBySql($query)->count();
					if((int)$limit <= (int)$count)
					{
						$result[] = date("Y-m-d", $the_date);
					}
				}
				

				if(isset($result))
				{
					$count = sizeof($result);
					if($count!=0) {
						$data =  json_encode($result);
					}
					else
					{
						$data = [];
					}
					return '{"status":"false", "message":"Dates are not available","no_dates":'.$data.'}';
				}
				else{
					return '{"status":"true", "message":"Dates are available"}';
				}
				
				
			} else {
				return '{"status":"false","message":"Sorry, Something went wrong."}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	public function actionGetadwithus()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
				$user_id = $_POST['user_id'];
			
				if ($this->checking($user_id)) {
					$siteSettings = Sitesettings::find()->where(['id'=>'1'])->one();

					$bannerCurrency = $siteSettings->promotionCurrency; 
					$currencyDetails = explode('-', $bannerCurrency);
					
					// $ad_desc = $siteSettings->ad_content;
					//$ad_desc = str_replace('"','\"',$ad_desc);
					
					$ad_image = Yii::$app->urlManager->createAbsoluteUrl('media/logo/' . $siteSettings->ad_image);
					$ad_curr = trim($currencyDetails[0]);
					$ad_sym = $currencyDetails[1];
					$price_per_day = $siteSettings->ad_price;


				  $ad_lang =  $_POST['lang_type'];  
         		 $adcontent = Json::decode($siteSettings->ad_content,true);

		        if ($adcontent!="") {
		             
		         if (array_key_exists($ad_lang, $adcontent)) {
		      
		           	$ad_desc = $adcontent[$ad_lang]['content'];
		         } 

		     	}

					$ad['ad_description'] = $ad_desc;
					$ad['ad_image'] = $ad_image;
					$ad['currency_code'] = $ad_curr;

					$currency_formats = yii::$app->Myclass->getCurrencyFormat(str_replace(" ", "", $ad_curr));
					if($currency_formats[0] != "")
							$ad['currency_mode'] = $currency_formats[0];
					if($currency_formats[1] != "")
							$ad['currency_position'] = $currency_formats[1];
		
					$ad['currency_symbol'] = $ad_sym;
					$ad['price_per_day'] = $price_per_day;
						if($_POST['lang_type'] == "ar")
								$ad['formatted_price_per_day'] = yii::$app->Myclass->arabicconvertFormattingCurrencyapi(str_replace(" ", "", $ad_curr),$price_per_day);
						else
								$ad['formatted_price_per_day'] = yii::$app->Myclass->convertFormattingCurrencyapi(str_replace(" ", "", $ad_curr),$price_per_day);

					$final = Json::encode($ad);

					return '{"status":"true","result":[' . $final . ']}';
			} else {
				return '{"status":"false","message":"Sorry, Something went wrong."}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	public function actionGetadhistory()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
				$user_id = '';
				if (isset($_POST['user_id'])) {
					$user_id = $_POST['user_id'];
				}

				if ($this->checking($user_id)) {
					$siteSettings = Sitesettings::find()->where(['id'=>'1'])->one();
					$banners = Banners::find()->where(['userid' => $user_id])->andWhere(['!=','paidstatus','0'])->orderBy(['id' => SORT_DESC])->all(); 
					foreach ($banners as $key => $banner) {
						$outputData[$key]['start_date'] =$banner->startdate;
						$outputData[$key]['end_date'] = $banner->enddate;
						$outputData[$key]['posted_date'] = $banner->createdDate;
						$outputData[$key]['approve_status'] = $banner->status;
						if($banner->status == '0')
						{
							$outputData[$key]['approve_status'] = 'Pending';
						}
						$now = date("Y-m-d");
						if(date_format(date_create($banner->enddate),"Y-m-d") < $now )
						{
							$outputData[$key]['approve_status'] = 'Expired';
						}
						$cur_sym = Currencies::find()->where(['currency_shortcode' => $banner->currency])->one();
						$outputData[$key]['currency_symbol'] = $cur_sym->currency_symbol;
						$outputData[$key]['currency_code'] = $banner->currency;


						$currency_formats = yii::$app->Myclass->getCurrencyFormat($banner->currency);
						if($currency_formats[0] != "")
								$outputData[$key]['currency_mode'] = $currency_formats[0];
						if($currency_formats[1] != "")
								$outputData[$key]['currency_position'] = $currency_formats[1];
		
						$outputData[$key]['price'] = $banner->totalCost;
						if($_POST['lang_type'] == "ar")
						  $outputData[$key]['formatted_price'] =  yii::$app->Myclass->arabicconvertFormattingCurrencyapi($banner->currency,$banner->totalCost);
						else
							  $outputData[$key]['formatted_price'] =  yii::$app->Myclass->convertFormattingCurrencyapi($banner->currency,$banner->totalCost);
						$outputData[$key]['transaction_id'] = $banner->tranxId;
						$outputData[$key]['app_banner_url'] = Yii::$app->urlManager->createAbsoluteUrl('media/banners/' . $banner->bannerimage);
						$outputData[$key]['web_banner_url'] = Yii::$app->urlManager->createAbsoluteUrl('media/banners/' . $banner->appbannerimage);
					}
					
					if(isset($outputData))
					{
						$result = Json::encode($outputData);
						return '{"status":"true","ad_history":'.$result.'}';
					}
					else
					{
						return '{"status":"false","message":"No data found"}';
					}
					
			} else {
				return '{"status":"false","message":"Sorry, Something went wrong."}';
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}

	public function actionGetinsights()
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			$user_id = 0;
			if (isset($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
			}
			$product_id = 0;
			if (isset($_POST['product_id'])) {
				$product_id = $_POST['product_id'];
			}

			$product = Products::find()->select('hts_products.name,hts_products.price,hts_products.currency,hts_products.productId,hts_products.insightUsers,hts_products.commentCount,hts_products.userId,hts_products.likes,hts_products.offerRequest,hts_products.exchangeRequest,hts_products.productId,hts_products.views,users.userId,users.country')
			->leftJoin('users', 'users.userId=hts_products.userId')
			->where(['productId' => $product_id])
			->one();
		
			if($product != '')
			{
			if(trim($product->userId) != trim($user_id)) {
				return '{"status":"false", "message":"Access denied..."}';
			}
			else
			{
				$insightUser = json_decode($product->insightUsers);		
				$items = array();
			
				if($insightUser != '')
				{
					foreach (array_unique($insightUser) as $key => $value) 
					{
						$country= Users::find()->select('city')->where(['userId' => $value])->andWhere(['!=','city',''])->one();
						$items[] =$country->city; 
						
						
					}
				}
				$getComments = Comments::find()->where(['productId'=>$product_id])->count();

				$country = array_count_values($items);
				arsort($country, SORT_NUMERIC); 
				$country = array_slice($country, 0,5); 

				$totalCount =count($country);
				$unquie_view=count(json_decode($product->insightUsers));
				$comments = count($getComments);
				$user_id = $product->userId;


				$likes = $product->likes;
			   $myofferRequest = $product->offerRequest;
			   $exchangeRequest = $product->exchangeRequest;

				$getcountryCount = Users::find()->where(['country' => $product->country])->count();
				$percentage = ($unquie_view/$getcountryCount)*100;


				$totalengagements = (int)$comments + (int)$likes+(int)$myofferRequest+(int)$exchangeRequest;
				$calcPercentage = ($totalengagements/$getcountryCount)*100;

				$engagementStatus = ($calcPercentage<=45) ? "low" : "high"; 

				$getExchangescount = Exchanges::find()->where(['mainProductId'=>$product_id])->count();

				$getOfferRequest = (new yii\db\Query())
				    ->select(['userId'])
				    ->from('hts_favorites')
				    ->where(['productId'=>$product_id])
					->all();
				
				$total_visitedcity = Userviews::find()
		            ->where(['product_id'=>$product_id])
		         		->andWhere(['>', 'LENGTH(city)', 0])
								->groupBy('city')->all();
				
				$sendofferRequestcnt=0;
				$sendofferRequest = Messages::find()->where(['messageType' =>'offer','sourceId'=>$product_id])->all();
		    if(count($sendofferRequest) > 0){
			foreach ($sendofferRequest as $key => $sendofferRequests) {
				$offerRequestType = json_decode($sendofferRequests->message, true);
				if($offerRequestType['type']=="sendreceive")
				{
						$sendofferRequestcnt = $sendofferRequestcnt + 1;
				}
			}
		}

				if($percentage<=45)
				{
					$popularity_level = 'low';
				}
				else{
					$popularity_level = 'high';
				}


				$most_visitedcity=Json::encode([]);


				if(!empty($country)){
					$progressCount = 100/(int)$totalCount;
					$progressCount = number_format($progressCount, 2, '.', '');
					$citylimit= 1;

					foreach(array_unique($items) as $key => $value)
					{
					if($value != "" && $citylimit < 6){
						$output[$key]['city_name'] = $value;
						$output[$key]['city_count'] = "$country[$value]";
						$percentageValue = $progressCount * $country[$value];
						$percentageValue = number_format($percentageValue, 2, '.', '');
						$output[$key]['percentage'] = $percentageValue;
						$citylimit++;
					}
					
					}
					$output = array_values($output);
					usort($output, function($cityprev, $citynext)
					{
					    $cityprev = $cityprev['city_count'];
					    $citynext = $citynext['city_count'];

					    if ($cityprev == $citynext) return 0;
					    return ($cityprev > $citynext) ? -1 : 1;
					});

		
						$most_visitedcity=json_encode($output);
				}	
				                                                                                                                                                                                             
	
					//views by week

				  for($i=7;$i>=0; $i--) {
				  		$weekDates = gmdate("Y-m-d\TH:i:s.B\Z",strtotime("-".$i."days"));
				  		$weekDatescheck = date("Y-m-d",strtotime("-".$i."days"));
					   	$count = Userviews::find()->where([
					   		'product_id'=>$product_id,
					   		'created_at'=>$weekDatescheck])->groupBy('user_id')->count();

					/*	$weekDates = gmdate("Y-m-d\TH:i:s.B\Z",strtotime("-".$i."days"));
					  $count=yii::$app->Myclass->getDaterecordsWeeklyApi(date("Y/m/d",strtotime("-".$i."days")),$product_id);*/
						$prosub[]=[$weekDates,(int)$count];
				  }

				  foreach ($prosub as $key => $value) {
					$week[$key]['duration'] = "$value[0]";
					$week[$key]['views'] = "$value[1]";
				  }

				  $views_by_week = json_encode($week);

				  //views by month
				 
					$getViews = Userviews::find()
		            ->select('created_at')
								->where(['product_id'=>$product_id])
								->groupBy('user_id')
		            ->all();
				  for($i=7;$i>=0; $i--) {
					$mystring = gmdate("Y-m-d\TH:i:s.B\Z", strtotime('-'.$i.' month', time()));
					$thismonth = date('Y/m', strtotime('-'.$i.' month', time()));
					$arrayCount =  array();
					foreach ($getViews as $key=>$value) {
						$data = date("Y/m", strtotime($value->created_at));
						if($data == $thismonth)
						{
							$arrayCount[] = $value;
						}
					}
					$count = count($arrayCount);
					/*	$monthcheck = date("Y-m",strtotime("-".$i."days"));
					   	$count = Userviews::find()->where([
					   		'product_id'=>$product_id,
					   		'created_at'=>$monthcheck])->groupBy('user_id')->count();
				/*	$count=yii::$app->Myclass->getDaterecordsMontlyApi(date('Y/m', strtotime('-'.$i.' month', time())),$product_id);*/
					$mprosub[]=[$mystring,(int)$count];
				  }
				  foreach ($mprosub as $key => $value) {
					$month[$key]['duration'] = "$value[0]";
					$month[$key]['views'] = "$value[1]";
				  }

				  $views_by_month = json_encode($month);

				  //views by year

				  for($i=7;$i>=0; $i--) {
					$mystring = gmdate("Y-m-d\TH:i:s.B\Z", strtotime('-'.$i.' year', time()));
					/*	$count=Yii::$app->Myclass->getDaterecordsYearlyApi(date('Y', strtotime('-'.$i.' year', time())),$product_id);*/

					$thisyear = date('Y', strtotime('-'.$i.' year', time()));
					$arrayCount =  array();
					foreach ($getViews as $key=>$value) {
						$data = date("Y", strtotime($value->created_at));
						if($data == $thisyear)
						{
							$arrayCount[] = $value;
						}
					}
					$count = count($arrayCount);
					/*	$yearcheck = date("Y",strtotime("-".$i."days"));
					   	$count = Userviews::find()->where([
					   		'product_id'=>$product_id,
					   		'created_at'=>$yearcheck])->groupBy('user_id')->count();*/
					$yprosub[]=[$mystring,(int)$count];
				  }
				  foreach ($yprosub as $key => $value) {
					$year[$key]['duration'] = "$value[0]";
					$year[$key]['views'] = "$value[1]";
				  }

				  $views_by_year = json_encode($year);
				  $date_utc = new \DateTime("now", new \DateTimeZone("UTC"));

				  $reachcontent = Helppages::find()->where(['id'=>4])->one();  

				 $help_lang =  $_POST['lang_type'];  
         		 $helpcontent = Json::decode($reachcontent->pageContent,true);

		        if ($helpcontent!="") {
		             
		         if (array_key_exists($help_lang, $helpcontent)) {
		      
		           	$help_desc = $helpcontent[$help_lang]['content'];
		         } 

		               else
		         {


		         	  $firstelem = array_keys($helpcontent)[0];
     				  $help_desc = $helpcontent[$firstelem]['content'];
		         }


		     	}



				  // $reachcontent = (isset($reachcontent->pageContent)) ? Json::encode(html_entity_decode($reachcontent->pageContent)) : "";  

		     	$reachcontent = $help_desc;

				return '{"status":"true", "unique_views":"'.$unquie_view.'", "total_views":"'.$product->views.'", "likes":"'.$product->likes.'", "comments":"'.$getComments.'", "exchange_request":"'.$getExchangescount.'", "offer_request":"'.$sendofferRequestcnt.'", "total_visitedcity":"'.count($total_visitedcity).'", "popularity_level":"'.$popularity_level.'", "most_visitedcity":'.$most_visitedcity.', "views_by_week":'.$views_by_week.', "views_by_month":'.$views_by_month.', "engagement_status":"'.$engagementStatus.'",  "reach_tips":'.json_encode($help_desc).',  "views_by_year":'.$views_by_year.'}';         
			} 
			} else
			{
				return '{"status":"false", "message":"No data found"}';
			}

		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}
	}



	// public function actionGetitemss() 
	// {
	// 	//Post Values
	// 	$api_username = $_POST['api_username'];
	// 	$api_password = $_POST['api_password'];

	// 	if ($this->authenticateAPI($api_username, $api_password)) {
	// 		//Post Values
	// 		$user_id = "";
	// 		if (isset($_POST['user_id'])) {
	// 			$user_id = $_POST['user_id'];
	// 		}

	// 		$lang_type = "";
	// 		if (isset($_POST['lang_type'])) {
	// 			$lang_type = $_POST['lang_type'];
	// 			yii::$app->Myclass->push_lang($lang_type);
	// 		}
	// 		if ($this->checking($user_id)) {
	// 			//Post Values
	// 			$type = "";
	// 			if (isset($_POST['type'])) {
	// 				$type = $_POST['type'];
	// 			}
	// 			$searchtype = "";
	// 			if (isset($_POST['search_type'])) {
	// 				$searchtype = $_POST['search_type'];
	// 			}
	// 			$price = "";
	// 			if (isset($_POST['price'])) {
	// 				$price = $_POST['price'];
	// 			}
	// 			$search_key = "";
	// 			if (isset($_POST['search_key'])) {
	// 				$search_key = $_POST['search_key'];
	// 			}
	// 			$category_id = "";
	// 			if (isset($_POST['category_id'])) {
	// 				$category_id = $_POST['category_id'];
	// 			}
	// 			$subcategory_id = "";
	// 			if (isset($_POST['subcategory_id'])) {
	// 				$subcategory_id = $_POST['subcategory_id'];
	// 			}
	// 			$sub_subcategory_id = "";
	// 			if (isset($_POST['child_category_id'])) {
	// 				$sub_subcategory_id = $_POST['child_category_id'];
	// 			}
	// 			$item_id = '';
	// 			if (isset($_POST['item_id'])) {
	// 				$item_id = $_POST['item_id'];
	// 			}
	// 			$seller_id = '';
	// 			if (isset($_POST['seller_id'])) {
	// 				$seller_id = $_POST['seller_id'];
	// 			}
	// 			$sorting_id = "";
	// 			if (isset($_POST['sorting_id'])) {
	// 				$sorting_id = $_POST['sorting_id'];
	// 			}

	// 			$prod_cond = 0;
	// 			if (isset($_POST['product_condition'])) {
	// 				$prod_cond = $_POST['product_condition'];
	// 			}

	// 			// FILTERS - GET ITEMS
	// 			$filtersArray = array();
	// 			if (isset($_POST['filters'])) {
	// 				$filtersArray = Json::decode($_POST['filters'], true); 
	// 			}  
				
	// 			$dropdownValues = (isset($filtersArray['dropdown'])) ? $filtersArray['dropdown'] : ""; 
	// 			$multiLevelValues = (isset($filtersArray['multilevel'])) ? $filtersArray['multilevel'] : "";
	// 			$rangeValues = (isset($filtersArray['range'])) ? $filtersArray['range'] : "";   
	// 			// FILTERS END  

	// 			$lat = "";
	// 			if (isset($_POST['lat'])) {
	// 				$lat = $_POST['lat'];
	// 			}
	// 			$lon = "";
	// 			if (isset($_POST['lon'])) {
	// 				$lon = $_POST['lon'];
	// 			}
	// 			$posted_within = "";
	// 			if (isset($_POST['posted_within'])) {
	// 				$posted_within = $_POST['posted_within'];
	// 			}
	// 			$distance = "";
	// 			$kilometer = "";
	// 			if (isset($_POST['distance'])) {
	// 				$kilometer = $_POST['distance'];
	// 			}

	// 			$distance_type = "";
	// 			if (isset($_POST['distance_type'])) {
	// 				$distance_type = $_POST['distance_type'];
	// 			}

	// 			$limit = 20;
	// 			if (isset($_POST['limit'])) {
	// 				$limit = $_POST['limit'];
	// 			}
	// 			$offset = 0;
	// 			if (isset($_POST['offset'])) {
	// 				$offset = $_POST['offset'];
	// 			}

	// 			$adsarray = '';
	// 			$adsarrays = [];
	// 			$ads = '';
	// 			if (isset($_POST['ads'])) {
	// 				$adsarray = $_POST['ads'];
	// 				$adsarrays = json_decode($_POST['ads']);
	// 			}

	// 			$criteria = Products::find();
	// 			$criteria->orderBy(['productId' => SORT_DESC]);
				
				
	// 			// 	if (isset($_POST['user_id']) && !empty($user_id)) {
	// 			// 	$criteria->andWhere(['userId'=> $_POST['user_id']]);
	// 			// }
				
	// 			if($type == 'search' && $searchtype == 'all')
	// 			{
	// 				$criteria->andWhere(['approvedStatus' => 1]);
					
	// 				$adsCriteria = clone $criteria;
	// 				$adsProducts = "";
	// 				$adsCriteria->andWhere(['promotionType' => '1']);
	// 				$adsCriteria->andWhere(['approvedStatus' => 1]);
	// 				if($adsarray != '')
	// 				{
	// 					$adsCriteria->andWhere(['NOT IN','productId', json_decode($adsarray)]);
	// 				}
	// 				$adsCriteria->orderBy(new Expression('rand()'));
	// 				$adsCriteria->limit(7);			

	// 				$criteria->andWhere(['<>','promotionType',1]);
					
	// 			}

	// 			$criteria->limit($limit);
	// 			$criteria->offset($offset);

	// 			if ($type == 'liked') {
	// 					// $likecriteria = clone $criteria;
	// 					// $likecriteria->addCondition("userId = '$user_id'");
	// 					if (isset($user_id) && isset($seller_id) && $user_id != $seller_id) {
	// 						$favouriteModel = Favorites::find()->where(['userId' => $seller_id])->orderBy(['productId' => SORT_DESC])->limit($limit)->offset($offset)->all();
	// 					}
	// 					else{
	// 						$favouriteModel = Favorites::find()->where(['userId' => $user_id])->orderBy(['productId' => SORT_DESC])->limit($limit)->offset($offset)->all();
	// 					}
					
	// 				if (!empty($favouriteModel)) {
	// 					foreach ($favouriteModel as $favourite) {
	// 						$likedproducts[] = $favourite->productId;
	// 					}
	// 					$criteria->andWhere(['productId' => $likedproducts]);
	// 				} else {
	// 					$criteria->andWhere(['productId' => '']);
	// 				}

	// 			}
	// 			if ($type == 'home' || $type == 'search') {
	// 				if (isset($sorting_id) && $sorting_id != 0) {
	// 					switch ($sorting_id) {
	// 						case 1:
	// 							$criteria->orderBy(['productId' => SORT_DESC]);
	// 							break;
	// 						case 2:
	// 							$criteria->orderBy(['likes' => SORT_DESC]);
	// 							break;
	// 						case 3:
	// 							$criteria->orderBy(['price' => SORT_DESC]);
	// 							break;
	// 						case 4:
	// 							$criteria->orderBy(['price' => SORT_ASC]);
	// 							break;
	// 						case 5:
	// 							$criteria->andWhere(['promotionType' => 2]);
	// 							break;
	// 					}

	// 				}
					

	// 			}
	// 			if ($type == 'moreitems') {

	// 				if (isset($user_id) && isset($seller_id) && $user_id != $seller_id) {
	// 					$criteria->andWhere(['approvedStatus' => 1]);
	// 				}
	// 				if (!empty($seller_id)) {

	// 					$sellerId = $seller_id;
	// 					$criteria->andWhere(['userId' => $sellerId]);
	// 					if ($item_id != 0) {
	// 						$itemId = $item_id;
	// 						$criteria->andWhere(['<>', 'productId', $itemId]);
	// 					}
	// 				} else {

	// 				}

					
	// 			} elseif ($type == 'search') {
	// 				$criteria->andWhere(['<>','soldItem',1]);
	// 				/* if ($user_id != 0){
	// 					$criteria->addCondition('userId = '.$user_id);
	// 				} */
	// 				if (isset($category_id) && $category_id != '') {
	// 					if (strpos($category_id, ',') === false) {
	// 						$criteria->andWhere(['category' => $category_id]);
	// 					} else {
	// 						$category_ids = explode(",", $category_id);
	// 						$criteria->andWhere(['category' => $category_ids]);
	// 					}

	// 				}
	// 				if (isset($subcategory_id) && $subcategory_id != '') {
	// 					if (strpos($subcategory_id, ',') === false) {
	// 						$criteria->andWhere(['subCategory' => $subcategory_id]);
	// 					} else {
	// 						$subcategory_ids = explode(",", $subcategory_id);
	// 						$criteria->andWhere(['subCategory' => $subcategory_ids]);
	// 					}
	// 				}
	// 				if (isset($sub_subcategory_id) && $sub_subcategory_id != '') {
	// 					if (strpos($sub_subcategory_id, ',') === false) {
	// 						$criteria->andWhere(['sub_subCategory' => $sub_subcategory_id]);
	// 					} else {
	// 						$sub_subcategory_id = explode(",", $sub_subcategory_id);
	// 						$criteria->andWhere(['sub_subCategory' => $sub_subcategory_id]);
	// 					}
	// 				}

	// 				if (!empty($search_key)) {
	// 					$criteria->andWhere(['like', 'name', $search_key]);
	// 				}
	// 				if (!empty($price)) {
	// 					$dataPrice = explode('-', $price);


	// 					/*
	// 					if (count($priceRange) > 1)
	// 						$criteria->andWhere(['between', 'price', $priceRange[0], $priceRange[1]]);
	// 					else
	// 						$criteria->andWhere(['<=', 'price', $priceRange[0]]);
	// 					*/

	// 					if($dataPrice[0]=='0' && $dataPrice[1]>='5000'){
	// 						$criteria->andWhere(['>=','price',0]);
	// 					}elseif($dataPrice[0]>'0' && $dataPrice[1]>='5000'){
	// 						    $criteria->andWhere(['>=','price',$dataPrice[0]]);
	//                     }elseif($dataPrice[0]=='0' && $dataPrice[1]=='0'){
	//                     	    $criteria->andWhere(['=','price' , $dataPrice[0]]);
	// 					}elseif($dataPrice[0]=='0' && $dataPrice[1]<'5000'){
							
	//                             $criteria->andWhere(['<=','price' , $dataPrice[1]]);
	// 					}else{
	// 						    $criteria->andWhere(['>=','price', $dataPrice[0]]);
	//                             $criteria->andWhere(['<=','price',$dataPrice[1]]);                          
	// 					}
	// 				}

	// 				if (!empty($posted_within)) {
	// 					$date = date('d-M-Y');
	// 					if ($posted_within == 'last24h') {
	// 						$prev_date = strtotime($date . ' -1 day');
	// 						$criteria->andWhere(['>=', 'createdDate', $prev_date]);

	// 					} elseif ($posted_within == 'last7d') {
	// 						$prev_week = strtotime($date . ' -7 day');
	// 						$criteria->andWhere(['>=', 'createdDate', $prev_week]);

	// 					} elseif ($posted_within == 'last30d') {
	// 						$prev_month = strtotime($date . ' -30 day');
	// 						$criteria->andWhere(['>=', 'createdDate', $prev_month]);
	// 					}
	// 				}

	// 				//search by product condition
	// 				if (!empty($prod_cond)) {
	// 					$criteria->andWhere(['productCondition' => $prod_cond]);
	// 				}


	// 				if (!empty($lat) && !empty($lon)) {

	// 					$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
	// 					/* convert km to mile calculation */
	// 					if (!empty($distance_type)) {
	// 						if ($distance_type == 'mi') {
	// 							$value = 1.60934; // 1km = 0.621371 miles 
	// 						} else {
	// 							$value = 1;
	// 						}
	// 					} else {
	// 						$distance_type = $siteSettings->searchType;
	// 						if ($distance_type == 'miles') {
	// 							$value = 1.60934; // 1km = 0.621371 miles 
	// 						} else {
	// 							$value = 1;
	// 						}
	// 					}

	// 					/* convert km to mile calculation */
	// 					if (!empty($kilometer)) {
	// 						$distance = ($kilometer * $value) * 0.1 / 11;
	// 					} else {
	// 						$searchListing = $siteSettings->searchList;
	// 						$kilometer = $searchListing[0]; // default 25 km
	// 						$distance = ($kilometer * $value) * 0.1 / 11;
	// 					}

	// 					$LatN = $lat + $distance;
	// 					$LatS = $lat - $distance;
	// 					$LonE = $lon + $distance;
	// 					$LonW = $lon - $distance;
	// 					//return  "North:".$LatN." Southlat:".$LatS." West:".$LonW." Eastlon:".$LonE;
	// 					$criteria->andWhere(['between', 'longitude', $LonW, $LonE]);
	// 					$criteria->andWhere(['between', 'latitude', $LatS, $LatN]);

	// 				}
	// 				$criteria->andWhere(['approvedStatus' => 1]);
	// 			}

	// 			//FILTERS START
	// 			$advFilterList = "";
	// 		   $advFilter[] = "and";  

	// 		   if(!empty($dropdownValues)) {
	// 		   	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 		   	$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');	
	// 				$advFilterList = "added";
	// 				//$dropdownData = array_map('intval', explode(',', $dropdownValues));
	// 				$dropdownData = array_map('intval', $dropdownValues); 

	// 				if(count($dropdownData) > 0) {
	// 					$advFlag = 0;
	// 					foreach ($dropdownData as $key => $value) {
	// 						if($advFlag == 0) {
	// 							$advFilter[] =  ['and',
	// 										['=','u.level_two',$value],
	// 										['=','u.level_three',0],							
	// 									 ];
	// 						} else {
	// 							$slag = 'd'.$advFlag; 
	// 							$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 		   				$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
	// 			            $advFilter[] =  ['and',
	// 											['=',$slag.'.level_two',$value],
	// 											['=',$slag.'.level_three',0],							
	// 										 ];
	// 						}
	// 						++$advFlag;
	// 					}
	// 				}
	// 			}

	// 			$multiLevelData = array();

	// 			if(empty($dropdownValues) && !empty($multiLevelValues)) {
	// 		   	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 		   	$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
	// 				$advFilterList = "added";
	// 				//$multiLevelData = array_map('intval', array_values(array_filter(explode(',', $multiLevelValues))));
	// 				$multiLevelData = array_map('intval', array_values(array_filter($multiLevelValues))); 

	// 				if(count($multiLevelData) > 0) {
	// 					$mlFlag = 0; 
	// 					if($mlFlag == 0) { 
	// 						$advFilter[] =  ['and',
	// 									['<>','u.level_two',0],
	// 									['=','u.level_three',$multiLevelData[0]],
	// 								 ];
	// 					}
	// 					++$mlFlag;
	// 				}  
	// 			} elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
	// 		   	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 		   	$criteria->leftJoin(['v' => $subQuery], 'v.product_id=u.product_id');
	// 				$advFilterList = "added";
	// 				$multiLevelData = array_map('intval', array_values(array_filter($multiLevelValues)));  
	// 				if(count($multiLevelData) > 0) {
	// 					$mlFlag = 0;
	// 					if($mlFlag == 0) { 
	// 						$advFilter[] =  ['and',
	// 									['<>','v.level_two',0],
	// 									['=','v.level_three',$multiLevelData[0]],
	// 								 ];
	// 					}
	// 					++$mlFlag;
	// 				} 
	// 			}

	// 			if(!empty($multiLevelValues) && count($multiLevelData) > 1) {
	// 				for ($i=$mlFlag; $i<count($multiLevelData); $i++) {
	// 					$mlFlag = $i;  
	// 					if($mlFlag > 0) {
	// 						$slag = 'm'.$mlFlag; 
	// 						$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 	   				$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
	// 		            $advFilter[] =  ['and',
	// 										['<>',$slag.'.level_two',0],
	// 										['=',$slag.'.level_three',$multiLevelData[$i]],							
	// 									 ];
	// 					} 
	// 				}
	// 			} 
				
	// 			if(count($rangeValues) > 0 && !empty($rangeValues)) { 
	// 				$advFlag = 0;
	// 				foreach ($rangeValues as $key => $value) {
	// 					//$valueRange = explode(';', $value['value']);
	// 					if(count($value) == 3) {
	// 						if($advFlag == 0) {						
	// 							$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 							if(empty($dropdownValues) && empty($multiLevelValues)) {
	// 				   			$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
	// 				   			$advFilter[] = ['and',
	// 			                  ['=', 'u.filter_id', $value['id'] ],
	// 			                  ['>=', 'u.filter_values', $value['min_value'] ], 
	// 			                  ['<=', 'u.filter_values', $value['max_value'] ],  
	// 			                  ['=', 'u.filter_type', 'range'],   
	// 			               ];
	// 							} elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
	// 				   			$criteria->leftJoin(['w' => $subQuery], 'w.product_id=u.product_id');
	// 				   			$advFilter[] = ['and',
	// 			                  ['=', 'w.filter_id', $value['id'] ],
	// 			                  ['>=', 'w.filter_values', $value['min_value'] ], 
	// 			                  ['<=', 'w.filter_values', $value['max_value'] ],   
	// 			                  ['=', 'w.filter_type', 'range'],   
	// 			               ];
	// 							} else {
	// 								$criteria->leftJoin(['v' => $subQuery], 'v.product_id=u.product_id');
	// 								$advFilter[] = ['and',
	// 			                  ['=', 'v.filter_id', $value['id'] ],
	// 			                  ['>=', 'v.filter_values', $value['min_value'] ], 
	// 			                  ['<=', 'v.filter_values', $value['max_value'] ],  
	// 			                  ['=', 'v.filter_type', 'range'],   
	// 			               ];
	// 							} 
	// 							++$advFlag;
	// 						} else { 
	// 							$slag = 'r'.$advFlag; 
	// 							$criteria->leftJoin([ $slag => $subQuery], $slag.'.product_id=u.product_id');
	// 								$advFilter[] = ['and',
	// 			                  ['=', $slag.'.filter_id', $value['id'] ],
	// 			                  ['>=', $slag.'.filter_values', $value['min_value'] ], 
	// 			                  ['<=', $slag.'.filter_values', $value['max_value'] ], 
	// 			                  ['=', $slag.'.filter_type', 'range'],   
	// 			               ]; 
	// 			            ++$advFlag; 
	// 						}
	// 					}	
	// 				}
	// 			}   

	// 			if(!empty($dropdownValues) || !empty($multiLevelValues) || !empty($rangeValues)) {  
	// 				$criteria->andWhere($advFilter); 
	// 	         $criteria->groupBy('u.product_id');   
	// 			}

	// 			//FILTERS END
	// 			//echo $criteria->createCommand()->getRawSql();
	// 			//die;

	// 			$itemModel = $criteria->all();

	// 			$adsarray = [];
	// 			$adsPosition = [];
	// 			$arrayad = [];
	// 			if(isset($adsCriteria)){
	// 				$adsProducts = $adsCriteria->all();

	// 				if (!empty($adsProducts)) {
	// 						$adsProductss = $this->convertJsonItems($adsProducts);
	// 						foreach ($adsProducts as $value) {
	// 							$adsPositionn = rand(0,2);
	// 							$adsarray[] = $value->productId;
	// 							$adsPosition[] = $adsPositionn;
	// 						}	

	// 						if(isset($itemModel))
	// 						{
	// 							if($itemModel > 3){
	// 								$subitem = array_chunk($itemModel,3);
	// 							}
	// 							else
	// 							{
	// 								$subitem[0] = $itemModel;
	// 							}

	// 							if(count($subitem) > count($adsProducts))
	// 							{
									
	// 								foreach ($subitem as $keys=>$subs) {
	// 									$position = $adsPosition[$keys];
	// 									$avalue = $adsarray[$keys];
	// 									$arrayad[] = $adsarray[$keys];
										
	// 									foreach ($subs as $key=>$value) {
	// 											if($position == $key)
	// 											{
	// 												$temp = Products::findOne($avalue);
	// 												$subitem[$keys][$position] = $temp;
	// 												$subitem[$keys][3] = $value;
	// 											}
	// 											else{
	// 												$subitem[$keys][$key] = $value;
	// 											}
	// 										} 
	// 								}
	// 							}else
	// 							{
	// 								for ($i=0; $i < count($adsarray); $i++) { 
	// 									$position = $adsPosition[$i];
	// 									$avalue = $adsarray[$i];
	// 									$arrayad[] = $adsarray[$i];
										
	// 									if(!empty($subitem[$i]))
	// 									{
	// 										foreach ($subitem[$i] as $key=>$value) {
	// 											if($position == $key)
	// 											{
	// 												$temp = Products::findOne($avalue);
	// 												$subitemm[$i][$position] = $temp;
	// 												$subitemm[$i][3] = $value;
	// 											}
	// 											else{
	// 												$subitemm[$i][$key] = $value;
	// 											}
	// 										} 
	// 									}
	// 									else{
	// 										$temp = Products::findOne($avalue);
	// 										$subitemm[$i][$position]=$temp;
	// 									}	
	// 								}
	// 								$subitem = $subitemm;
	// 							}

	// 							foreach ($subitem as $value) {
	// 								foreach ($value as $key) {
	// 									$newarray[] = $key;
	// 								}
	// 							}
	// 							$itemModel = $newarray;
	// 						}
	// 				}
					
	// 			}


				
	// 			if($adsarrays != '' || $arrayad != '')
	// 			{
	// 					$ads = array_merge($adsarrays,$arrayad);
	// 			}

	// 			if (!empty($itemModel)) {
	// 				if ($user_id != 0) {
	// 					$result = $this->convertJsonItems($itemModel, $user_id, $seller_id);
	// 				} else {
	// 					$result = $this->convertJsonItems($itemModel);
	// 				}
	// 				return '{"status": "true","ads":'.json_encode($ads).',"result":' . $result . '}';
	// 				die;
	// 			} else {
	// 				return '{"status":"false","ads":'.json_encode($ads).',"message":"No item found"}';
	// 			}
	// 		} else {
	// 			return $this->errorMessage;
	// 		}
	// 	} else {
	// 		return '{"status":"false", "message":"Unauthorized Access to the API"}';
	// 	}

	// }
	
	
	// public function actionGetitems() 
	// {
	// 	//Post Values
	// 	$api_username = $_POST['api_username'];
	// 	$api_password = $_POST['api_password'];

	// 	if ($this->authenticateAPI($api_username, $api_password)) {
	// 		//Post Values
	// 		$user_id = "";
	// 		if (isset($_POST['user_id'])) {
	// 			$user_id = $_POST['user_id'];
	// 		}

	// 		$lang_type = "";
	// 		if (isset($_POST['lang_type'])) {
	// 			$lang_type = $_POST['lang_type'];
	// 			yii::$app->Myclass->push_lang($lang_type);
	// 		}
	// 		if ($this->checking($user_id)) {
	// 			//Post Values
	// 			$type = "";
	// 			if (isset($_POST['type'])) {
	// 				$type = $_POST['type'];
	// 			}
	// 			$searchtype = "";
	// 			if (isset($_POST['search_type'])) {
	// 				$searchtype = $_POST['search_type'];
	// 			}
	// 			$price = "";
	// 			if (isset($_POST['price'])) {
	// 				$price = $_POST['price'];
	// 			}
	// 			$search_key = "";
	// 			if (isset($_POST['search_key'])) {
	// 				$search_key = $_POST['search_key'];
	// 			}
	// 			$category_id = "";
	// 			if (isset($_POST['category_id'])) {
	// 				$category_id = $_POST['category_id'];
	// 			}
	// 			$subcategory_id = "";
	// 			if (isset($_POST['subcategory_id'])) {
	// 				$subcategory_id = $_POST['subcategory_id'];
	// 			}
	// 			$sub_subcategory_id = "";
	// 			if (isset($_POST['child_category_id'])) {
	// 				$sub_subcategory_id = $_POST['child_category_id'];
	// 			}
	// 			$item_id = '';
	// 			if (isset($_POST['item_id'])) {
	// 				$item_id = $_POST['item_id'];
	// 			}
	// 			$seller_id = '';
	// 			if (isset($_POST['seller_id'])) {
	// 				$seller_id = $_POST['seller_id'];
	// 			}
	// 			$sorting_id = "";
	// 			if (isset($_POST['sorting_id'])) {
	// 				$sorting_id = $_POST['sorting_id'];
	// 			}

	// 			$prod_cond = 0;
	// 			if (isset($_POST['product_condition'])) {
	// 				$prod_cond = $_POST['product_condition'];
	// 			}

	// 			// FILTERS - GET ITEMS
	// 			$filtersArray = array();
	// 			if (isset($_POST['filters'])) {
	// 				$filtersArray = Json::decode($_POST['filters'], true); 
	// 			}  
				
	// 			$dropdownValues = (isset($filtersArray['dropdown'])) ? $filtersArray['dropdown'] : ""; 
	// 			$multiLevelValues = (isset($filtersArray['multilevel'])) ? $filtersArray['multilevel'] : "";
	// 			$rangeValues = (isset($filtersArray['range'])) ? $filtersArray['range'] : "";   
	// 			// FILTERS END  

	// 			$lat = "";
	// 			if (isset($_POST['lat'])) {
	// 				$lat = $_POST['lat'];
	// 			}
	// 			$lon = "";
	// 			if (isset($_POST['lon'])) {
	// 				$lon = $_POST['lon'];
	// 			}
	// 			$posted_within = "";
	// 			if (isset($_POST['posted_within'])) {
	// 				$posted_within = $_POST['posted_within'];
	// 			}
	// 			$distance = "";
	// 			$kilometer = "";
	// 			if (isset($_POST['distance'])) {
	// 				$kilometer = $_POST['distance'];
	// 			}

	// 			$distance_type = "";
	// 			if (isset($_POST['distance_type'])) {
	// 				$distance_type = $_POST['distance_type'];
	// 			}

	// 			$limit = 20;
	// 			if (isset($_POST['limit'])) {
	// 				$limit = $_POST['limit'];
	// 			}
	// 			$offset = 0;
	// 			if (isset($_POST['offset'])) {
	// 				$offset = $_POST['offset'];
	// 			}

	// 			$adsarray = '';
	// 			$adsarrays = [];
	// 			$ads = '';
	// 			if (isset($_POST['ads'])) {
	// 				$adsarray = $_POST['ads'];
	// 				$adsarrays = json_decode($_POST['ads']);
	// 			}

	// 			$criteria = Products::find();
	// 			$criteria->orderBy(['productId' => SORT_DESC]);
				
				
	// 			// 	if (isset($_POST['user_id']) && !empty($user_id)) {
	// 			// 	$criteria->andWhere(['userId'=> $_POST['user_id']]);
	// 			// }
				
	// 			if($type == 'search' && $searchtype == 'all')
	// 			{
	// 				$criteria->andWhere(['approvedStatus' => 1]);
					
	// 				$adsCriteria = clone $criteria;
	// 				$adsProducts = "";
	// 				$adsCriteria->andWhere(['promotionType' => '1']);
	// 				$adsCriteria->andWhere(['approvedStatus' => 1]);
	// 				if($adsarray != '')
	// 				{
	// 					$adsCriteria->andWhere(['NOT IN','productId', json_decode($adsarray)]);
	// 				}
	// 				$adsCriteria->orderBy(new Expression('rand()'));
	// 				$adsCriteria->limit(7);			

	// 				$criteria->andWhere(['<>','promotionType',1]);
					
	// 			}

	// 			$criteria->limit($limit);
	// 			$criteria->offset($offset);

	// 			if ($type == 'liked') {
	// 					// $likecriteria = clone $criteria;
	// 					// $likecriteria->addCondition("userId = '$user_id'");
	// 					if (isset($user_id) && isset($seller_id) && $user_id != $seller_id) {
	// 						$favouriteModel = Favorites::find()->where(['userId' => $seller_id])->orderBy(['productId' => SORT_DESC])->limit($limit)->offset($offset)->all();
	// 					}
	// 					else{
	// 						$favouriteModel = Favorites::find()->where(['userId' => $user_id])->orderBy(['productId' => SORT_DESC])->limit($limit)->offset($offset)->all();
	// 					}
					
	// 				if (!empty($favouriteModel)) {
	// 					foreach ($favouriteModel as $favourite) {
	// 						$likedproducts[] = $favourite->productId;
	// 					}
	// 					$criteria->andWhere(['productId' => $likedproducts]);
	// 				} else {
	// 					$criteria->andWhere(['productId' => '']);
	// 				}

	// 			}
	// 			if ($type == 'home' || $type == 'search') {
	// 				if (isset($sorting_id) && $sorting_id != 0) {
	// 					switch ($sorting_id) {
	// 						case 1:
	// 							$criteria->orderBy(['productId' => SORT_DESC]);
	// 							break;
	// 						case 2:
	// 							$criteria->orderBy(['likes' => SORT_DESC]);
	// 							break;
	// 						case 3:
	// 							$criteria->orderBy(['price' => SORT_DESC]);
	// 							break;
	// 						case 4:
	// 							$criteria->orderBy(['price' => SORT_ASC]);
	// 							break;
	// 						case 5:
	// 							$criteria->andWhere(['promotionType' => 2]);
	// 							break;
	// 					}

	// 				}
					

	// 			}
	// 			if ($type == 'moreitems') {

	// 				if (isset($user_id) && isset($seller_id) && $user_id != $seller_id) {
	// 					$criteria->andWhere(['approvedStatus' => 1]);
	// 				}
	// 				if (!empty($seller_id)) {

	// 					$sellerId = $seller_id;
	// 					$criteria->andWhere(['userId' => $sellerId]);
	// 					if ($item_id != 0) {
	// 						$itemId = $item_id;
	// 						$criteria->andWhere(['<>', 'productId', $itemId]);
	// 					}
	// 				} else {

	// 				}

					
	// 			} elseif ($type == 'search') {
	// 				$criteria->andWhere(['<>','soldItem',1]);
	// 				/* if ($user_id != 0){
	// 					$criteria->addCondition('userId = '.$user_id);
	// 				} */
	// 				if (isset($category_id) && $category_id != '') {
	// 					if (strpos($category_id, ',') === false) {
	// 						$criteria->andWhere(['category' => $category_id]);
	// 					} else {
	// 						$category_ids = explode(",", $category_id);
	// 						$criteria->andWhere(['category' => $category_ids]);
	// 					}

	// 				}
	// 				if (isset($subcategory_id) && $subcategory_id != '') {
	// 					if (strpos($subcategory_id, ',') === false) {
	// 						$criteria->andWhere(['subCategory' => $subcategory_id]);
	// 					} else {
	// 						$subcategory_ids = explode(",", $subcategory_id);
	// 						$criteria->andWhere(['subCategory' => $subcategory_ids]);
	// 					}
	// 				}
	// 				if (isset($sub_subcategory_id) && $sub_subcategory_id != '') {
	// 					if (strpos($sub_subcategory_id, ',') === false) {
	// 						$criteria->andWhere(['sub_subCategory' => $sub_subcategory_id]);
	// 					} else {
	// 						$sub_subcategory_id = explode(",", $sub_subcategory_id);
	// 						$criteria->andWhere(['sub_subCategory' => $sub_subcategory_id]);
	// 					}
	// 				}

	// 				if (!empty($search_key)) {
	// 					$criteria->andWhere(['like', 'name', $search_key]);
	// 				}
	// 				if (!empty($price)) {
	// 					$dataPrice = explode('-', $price);


	// 					/*
	// 					if (count($priceRange) > 1)
	// 						$criteria->andWhere(['between', 'price', $priceRange[0], $priceRange[1]]);
	// 					else
	// 						$criteria->andWhere(['<=', 'price', $priceRange[0]]);
	// 					*/

	// 					if($dataPrice[0]=='0' && $dataPrice[1]>='5000'){
	// 						$criteria->andWhere(['>=','price',0]);
	// 					}elseif($dataPrice[0]>'0' && $dataPrice[1]>='5000'){
	// 						    $criteria->andWhere(['>=','price',$dataPrice[0]]);
	//                     }elseif($dataPrice[0]=='0' && $dataPrice[1]=='0'){
	//                     	    $criteria->andWhere(['=','price' , $dataPrice[0]]);
	// 					}elseif($dataPrice[0]=='0' && $dataPrice[1]<'5000'){
							
	//                             $criteria->andWhere(['<=','price' , $dataPrice[1]]);
	// 					}else{
	// 						    $criteria->andWhere(['>=','price', $dataPrice[0]]);
	//                             $criteria->andWhere(['<=','price',$dataPrice[1]]);                          
	// 					}
	// 				}

	// 				if (!empty($posted_within)) {
	// 					$date = date('d-M-Y');
	// 					if ($posted_within == 'last24h') {
	// 						$prev_date = strtotime($date . ' -1 day');
	// 						$criteria->andWhere(['>=', 'createdDate', $prev_date]);

	// 					} elseif ($posted_within == 'last7d') {
	// 						$prev_week = strtotime($date . ' -7 day');
	// 						$criteria->andWhere(['>=', 'createdDate', $prev_week]);

	// 					} elseif ($posted_within == 'last30d') {
	// 						$prev_month = strtotime($date . ' -30 day');
	// 						$criteria->andWhere(['>=', 'createdDate', $prev_month]);
	// 					}
	// 				}

	// 				//search by product condition
	// 				if (!empty($prod_cond)) {
	// 					$criteria->andWhere(['productCondition' => $prod_cond]);
	// 				}


	// 				if (!empty($lat) && !empty($lon)) {

	// 					$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
	// 					/* convert km to mile calculation */
	// 					if (!empty($distance_type)) {
	// 						if ($distance_type == 'mi') {
	// 							$value = 1.60934; // 1km = 0.621371 miles 
	// 						} else {
	// 							$value = 1;
	// 						}
	// 					} else {
	// 						$distance_type = $siteSettings->searchType;
	// 						if ($distance_type == 'miles') {
	// 							$value = 1.60934; // 1km = 0.621371 miles 
	// 						} else {
	// 							$value = 1;
	// 						}
	// 					}

	// 					/* convert km to mile calculation */
	// 					if (!empty($kilometer)) {
	// 						$distance = ($kilometer * $value) * 0.1 / 11;
	// 					} else {
	// 						$searchListing = $siteSettings->searchList;
	// 						$kilometer = $searchListing[0]; // default 25 km
	// 						$distance = ($kilometer * $value) * 0.1 / 11;
	// 					}

	// 					$LatN = $lat + $distance;
	// 					$LatS = $lat - $distance;
	// 					$LonE = $lon + $distance;
	// 					$LonW = $lon - $distance;
	// 					//return  "North:".$LatN." Southlat:".$LatS." West:".$LonW." Eastlon:".$LonE;
	// 					$criteria->andWhere(['between', 'longitude', $LonW, $LonE]);
	// 					$criteria->andWhere(['between', 'latitude', $LatS, $LatN]);

	// 				}
	// 				$criteria->andWhere(['approvedStatus' => 1]);
	// 			}

	// 			//FILTERS START
	// 			$advFilterList = "";
	// 		   $advFilter[] = "and";  

	// 		   if(!empty($dropdownValues)) {
	// 		   	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 		   	$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');	
	// 				$advFilterList = "added";
	// 				//$dropdownData = array_map('intval', explode(',', $dropdownValues));
	// 				$dropdownData = array_map('intval', $dropdownValues); 

	// 				if(count($dropdownData) > 0) {
	// 					$advFilter[] =  ['and',
	// 						['IN','u.level_two',$dropdownData],
	// 						['=','u.level_three',0],							
	// 					];
	// 				}
	// 			}

	// 			$multiLevelData = array();

	// 			if(empty($dropdownValues) && !empty($multiLevelValues)) {
	// 		   	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 		   	$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
	// 				$advFilterList = "added";
	// 				//$multiLevelData = array_map('intval', array_values(array_filter(explode(',', $multiLevelValues))));
	// 				$multiLevelData = array_map('intval', array_values(array_filter($multiLevelValues))); 

	// 				if(count($multiLevelData) > 0) {
	// 					$advFilter[] =  ['and',
	// 						['<>','u.level_two',0],
	// 						['IN','u.level_three',$multiLevelData],
	// 					];
	// 				}  
	// 			} elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
	// 		   	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 		   	$criteria->leftJoin(['v' => $subQuery], 'v.product_id=u.product_id');
	// 				$advFilterList = "added";
	// 				$multiLevelData = array_map('intval', array_values(array_filter($multiLevelValues)));  
	// 				if(count($multiLevelData) > 0) {
	// 					$advFilter[] =  ['and',
	// 						['<>','v.level_two',0],
	// 						['IN','v.level_three',$multiLevelData],
	// 					];
	// 				} 
	// 			}

	// 			// if(!empty($multiLevelValues) && count($multiLevelData) > 1) {
	// 			// 	for ($i=$mlFlag; $i<count($multiLevelData); $i++) {
	// 			// 		$mlFlag = $i;  
	// 			// 		if($mlFlag > 0) {
	// 			// 			$slag = 'm'.$mlFlag; 
	// 			// 			$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 	   	// 			$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
	// 		  //           $advFilter[] =  ['and',
	// 			// 							['<>',$slag.'.level_two',0],
	// 			// 							['=',$slag.'.level_three',$multiLevelData[$i]],							
	// 			// 						 ];
	// 			// 		} 
	// 			// 	}
	// 			// } 
				
	// 			if(count($rangeValues) > 0 && !empty($rangeValues)) { 
	// 				$advFlag = 0;
	// 				foreach ($rangeValues as $key => $value) {
	// 					//$valueRange = explode(';', $value['value']);
	// 					if(count($value) == 3) {
	// 						if($advFlag == 0) {						
	// 							$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 							if(empty($dropdownValues) && empty($multiLevelValues)) {
	// 				   			$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
	// 				   			$advFilter[] = ['and',
	// 			                  ['=', 'u.filter_id', $value['id'] ],
	// 			                  ['>=', 'u.filter_values', $value['min_value'] ], 
	// 			                  ['<=', 'u.filter_values', $value['max_value'] ],  
	// 			                  ['=', 'u.filter_type', 'range'],   
	// 			               ];
	// 							} elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
	// 				   			$criteria->leftJoin(['w' => $subQuery], 'w.product_id=u.product_id');
	// 				   			$advFilter[] = ['and',
	// 			                  ['=', 'w.filter_id', $value['id'] ],
	// 			                  ['>=', 'w.filter_values', $value['min_value'] ], 
	// 			                  ['<=', 'w.filter_values', $value['max_value'] ],   
	// 			                  ['=', 'w.filter_type', 'range'],   
	// 			               ];
	// 							} else {
	// 								$criteria->leftJoin(['v' => $subQuery], 'v.product_id=u.product_id');
	// 								$advFilter[] = ['and',
	// 			                  ['=', 'v.filter_id', $value['id'] ],
	// 			                  ['>=', 'v.filter_values', $value['min_value'] ], 
	// 			                  ['<=', 'v.filter_values', $value['max_value'] ],  
	// 			                  ['=', 'v.filter_type', 'range'],   
	// 			               ];
	// 							} 
	// 							++$advFlag;
	// 						} else { 
	// 							$slag = 'r'.$advFlag; 
	// 							$criteria->leftJoin([ $slag => $subQuery], $slag.'.product_id=u.product_id');
	// 								$advFilter[] = ['and',
	// 			                  ['=', $slag.'.filter_id', $value['id'] ],
	// 			                  ['>=', $slag.'.filter_values', $value['min_value'] ], 
	// 			                  ['<=', $slag.'.filter_values', $value['max_value'] ], 
	// 			                  ['=', $slag.'.filter_type', 'range'],   
	// 			               ]; 
	// 			            ++$advFlag; 
	// 						}
	// 					}	
	// 				}
	// 			}   

	// 			if(!empty($dropdownValues) || !empty($multiLevelValues) || !empty($rangeValues)) {  
	// 				$criteria->andWhere($advFilter); 
	// 	         $criteria->groupBy('u.product_id');   
	// 			}

	// 			//FILTERS END
	// 			//echo $criteria->createCommand()->getRawSql();
	// 			//die;

	// 			$itemModel = $criteria->all();

	// 			$adsarray = [];
	// 			$adsPosition = [];
	// 			$arrayad = [];
	// 			if(isset($adsCriteria)){
	// 				$adsProducts = $adsCriteria->all();

	// 				if (!empty($adsProducts)) {
	// 						$adsProductss = $this->convertJsonItems($adsProducts);
	// 						foreach ($adsProducts as $value) {
	// 							$adsPositionn = rand(0,2);
	// 							$adsarray[] = $value->productId;
	// 							$adsPosition[] = $adsPositionn;
	// 						}	

	// 						if(isset($itemModel))
	// 						{
	// 							if($itemModel > 3){
	// 								$subitem = array_chunk($itemModel,3);
	// 							}
	// 							else
	// 							{
	// 								$subitem[0] = $itemModel;
	// 							}

	// 							if(count($subitem) > count($adsProducts))
	// 							{
									
	// 								foreach ($subitem as $keys=>$subs) {
	// 									$position = $adsPosition[$keys];
	// 									$avalue = $adsarray[$keys];
	// 									$arrayad[] = $adsarray[$keys];
										
	// 									foreach ($subs as $key=>$value) {
	// 											if($position == $key)
	// 											{
	// 												$temp = Products::findOne($avalue);
	// 												$subitem[$keys][$position] = $temp;
	// 												$subitem[$keys][3] = $value;
	// 											}
	// 											else{
	// 												$subitem[$keys][$key] = $value;
	// 											}
	// 										} 
	// 								}
	// 							}else
	// 							{
	// 								for ($i=0; $i < count($adsarray); $i++) { 
	// 									$position = $adsPosition[$i];
	// 									$avalue = $adsarray[$i];
	// 									$arrayad[] = $adsarray[$i];
										
	// 									if(!empty($subitem[$i]))
	// 									{
	// 										foreach ($subitem[$i] as $key=>$value) {
	// 											if($position == $key)
	// 											{
	// 												$temp = Products::findOne($avalue);
	// 												$subitemm[$i][$position] = $temp;
	// 												$subitemm[$i][3] = $value;
	// 											}
	// 											else{
	// 												$subitemm[$i][$key] = $value;
	// 											}
	// 										} 
	// 									}
	// 									else{
	// 										$temp = Products::findOne($avalue);
	// 										$subitemm[$i][$position]=$temp;
	// 									}	
	// 								}
	// 								$subitem = $subitemm;
	// 							}

	// 							foreach ($subitem as $value) {
	// 								foreach ($value as $key) {
	// 									$newarray[] = $key;
	// 								}
	// 							}
	// 							$itemModel = $newarray;
	// 						}
	// 				}
					
	// 			}


				
	// 			if($adsarrays != '' || $arrayad != '')
	// 			{
	// 					$ads = array_merge($adsarrays,$arrayad);
	// 			}

	// 			if (!empty($itemModel)) {
	// 				if ($user_id != 0) {
	// 					$result = $this->convertJsonItems($itemModel, $user_id, $seller_id);
	// 				} else {
	// 					$result = $this->convertJsonItems($itemModel);
	// 				}
	// 				return '{"status": "true","ads":'.json_encode($ads).',"result":' . $result . '}';
	// 				die;
	// 			} else {
	// 				return '{"status":"false","ads":'.json_encode($ads).',"message":"No item found"}';
	// 			}
	// 		} else {
	// 			return $this->errorMessage;
	// 		}
	// 	} else {
	// 		return '{"status":"false", "message":"Unauthorized Access to the API"}';
	// 	}

	// }


	public function actionGetitems_old() 
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = "";
			if (isset($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
			}

			$lang_type = "";
			if (isset($_POST['lang_type'])) {
				$lang_type = $_POST['lang_type'];
				yii::$app->Myclass->push_lang($lang_type);
			}
			if ($this->checking($user_id)) {
				//Post Values
				$type = "";
				if (isset($_POST['type'])) {
					$type = $_POST['type'];
				}
				$searchtype = "";
				if (isset($_POST['search_type'])) {
					$searchtype = $_POST['search_type'];
				}
				$price = "";
				if (isset($_POST['price'])) {
					$price = $_POST['price'];
				}
				$search_key = "";
				if (isset($_POST['search_key'])) {
					$search_key = $_POST['search_key'];
				}
				$category_id = "";
				if (isset($_POST['category_id'])) {
					$category_id = $_POST['category_id'];
				}
				$subcategory_id = "";
				if (isset($_POST['subcategory_id'])) {
					$subcategory_id = $_POST['subcategory_id'];
				}
				$sub_subcategory_id = "";
				if (isset($_POST['child_category_id'])) {
					$sub_subcategory_id = $_POST['child_category_id'];
				}
				$item_id = '';
				if (isset($_POST['item_id'])) {
					$item_id = $_POST['item_id'];
				}
				$seller_id = '';
				if (isset($_POST['seller_id'])) {
					$seller_id = $_POST['seller_id'];
				}
				$sorting_id = "";
				if (isset($_POST['sorting_id'])) {
					$sorting_id = $_POST['sorting_id'];
				}

				$prod_cond = 0;
				if (isset($_POST['product_condition'])) {
					$prod_cond = $_POST['product_condition'];
				}

				// FILTERS - GET ITEMS
				$filtersArray = array();
				if (isset($_POST['filters'])) {
					$filtersArray = Json::decode($_POST['filters'], true); 
				}  
				
				$dropdownValues = (isset($filtersArray['dropdown'])) ? $filtersArray['dropdown'] : ""; 
				$multiLevelValues = (isset($filtersArray['multilevel'])) ? $filtersArray['multilevel'] : "";
				$rangeValues = (isset($filtersArray['range'])) ? $filtersArray['range'] : "";   
				// FILTERS END  

				$lat = "";
				if (isset($_POST['lat'])) {
					$lat = $_POST['lat'];
				}
				$lon = "";
				if (isset($_POST['lon'])) {
					$lon = $_POST['lon'];
				}
				$posted_within = "";
				if (isset($_POST['posted_within'])) {
					$posted_within = $_POST['posted_within'];
				}
				$distance = "";
				$kilometer = "";
				if (isset($_POST['distance'])) {
					$kilometer = $_POST['distance'];
				}

				$distance_type = "";
				if (isset($_POST['distance_type'])) {
					$distance_type = $_POST['distance_type'];
				}

				$limit = 20;
				if (isset($_POST['limit'])) {
					$limit = $_POST['limit'];
				}
				$offset = 0;
				if (isset($_POST['offset'])) {
					$offset = $_POST['offset'];
				}

				$adsarray = '';
				$adsarrays = [];
				$ads = '';
				if (isset($_POST['ads'])) {
					$adsarray = $_POST['ads'];
					$adsarrays = json_decode($_POST['ads']);
				}

				$criteria = Products::find();
				$criteria->orderBy(['productId' => SORT_DESC]);
				
				
				// 	if (isset($_POST['user_id']) && !empty($user_id)) {
				// 	$criteria->andWhere(['userId'=> $_POST['user_id']]);
				// }
				
				if($type == 'search' && $searchtype == 'all')
				{
					$criteria->andWhere(['approvedStatus' => 1]);
					
					$adsCriteria = clone $criteria;
					$adsProducts = "";
					$adsCriteria->andWhere(['promotionType' => '1']);
					$adsCriteria->andWhere(['approvedStatus' => 1]);
					if($adsarray != '')
					{
						$adsCriteria->andWhere(['NOT IN','productId', json_decode($adsarray)]);
					}
					$adsCriteria->orderBy(new Expression('rand()'));
					$adsCriteria->limit(7);			

					$criteria->andWhere(['<>','promotionType',1]);
					
				}

				$criteria->limit($limit);
				$criteria->offset($offset);

				if ($type == 'liked') {
						// $likecriteria = clone $criteria;
						// $likecriteria->addCondition("userId = '$user_id'");
						if (isset($user_id) && isset($seller_id) && $user_id != $seller_id) {
							$favouriteModel = Favorites::find()->where(['userId' => $seller_id])->orderBy(['productId' => SORT_DESC])->limit($limit)->offset($offset)->all();
						}
						else{
							$favouriteModel = Favorites::find()->where(['userId' => $user_id])->orderBy(['productId' => SORT_DESC])->limit($limit)->offset($offset)->all();
						}
					
					if (!empty($favouriteModel)) {
						foreach ($favouriteModel as $favourite) {
							$likedproducts[] = $favourite->productId;
						}
						$criteria->andWhere(['productId' => $likedproducts]);
					} else {
						$criteria->andWhere(['productId' => '']);
					}

				}
				if ($type == 'home' || $type == 'search') {
					if (isset($sorting_id) && $sorting_id != 0) {
						switch ($sorting_id) {
							case 1:
								$criteria->orderBy(['productId' => SORT_DESC]);
								break;
							case 2:
								$criteria->orderBy(['likes' => SORT_DESC]);
								break;
							case 3:
								$criteria->orderBy(['price' => SORT_DESC]);
								break;
							case 4:
								$criteria->orderBy(['price' => SORT_ASC]);
								break;
							case 5:
								$criteria->andWhere(['promotionType' => 2]);
								break;
						}

					}
					

				}
				if ($type == 'moreitems') {

					if (isset($user_id) && isset($seller_id) && $user_id != $seller_id) {
						$criteria->andWhere(['approvedStatus' => 1]);
					}
					if (!empty($seller_id)) {

						$sellerId = $seller_id;
						$criteria->andWhere(['userId' => $sellerId]);
						if ($item_id != 0) {
							$itemId = $item_id;
							$criteria->andWhere(['<>', 'productId', $itemId]);
						}
					} else {

					}

					
				} elseif ($type == 'search') {
					$criteria->andWhere(['<>','soldItem',1]);
					/* if ($user_id != 0){
						$criteria->addCondition('userId = '.$user_id);
					} */
					if (isset($category_id) && $category_id != '') {
						if (strpos($category_id, ',') === false) {
							$criteria->andWhere(['category' => $category_id]);
						} else {
							$category_ids = explode(",", $category_id);
							$criteria->andWhere(['category' => $category_ids]);
						}

					}
					if (isset($subcategory_id) && $subcategory_id != '') {
						if (strpos($subcategory_id, ',') === false) {
							$criteria->andWhere(['subCategory' => $subcategory_id]);
						} else {
							$subcategory_ids = explode(",", $subcategory_id);
							$criteria->andWhere(['subCategory' => $subcategory_ids]);
						}
					}
					if (isset($sub_subcategory_id) && $sub_subcategory_id != '') {
						if (strpos($sub_subcategory_id, ',') === false) {
							$criteria->andWhere(['sub_subCategory' => $sub_subcategory_id]);
						} else {
							$sub_subcategory_id = explode(",", $sub_subcategory_id);
							$criteria->andWhere(['sub_subCategory' => $sub_subcategory_id]);
						}
					}

					if (!empty($search_key)) {
						$criteria->andWhere(['like', 'name', $search_key]);
					}
					if (!empty($price)) {
						$dataPrice = explode('-', $price);


						/*
						if (count($priceRange) > 1)
							$criteria->andWhere(['between', 'price', $priceRange[0], $priceRange[1]]);
						else
							$criteria->andWhere(['<=', 'price', $priceRange[0]]);
						*/

						if($dataPrice[0]=='0' && $dataPrice[1]>='5000'){
							$criteria->andWhere(['>=','price',0]);
						}elseif($dataPrice[0]>'0' && $dataPrice[1]>='5000'){
							    $criteria->andWhere(['>=','price',$dataPrice[0]]);
	                    }elseif($dataPrice[0]=='0' && $dataPrice[1]=='0'){
	                    	    $criteria->andWhere(['=','price' , $dataPrice[0]]);
						}elseif($dataPrice[0]=='0' && $dataPrice[1]<'5000'){
							
	                            $criteria->andWhere(['<=','price' , $dataPrice[1]]);
						}else{
							    $criteria->andWhere(['>=','price', $dataPrice[0]]);
	                            $criteria->andWhere(['<=','price',$dataPrice[1]]);                          
						}
					}

					if (!empty($posted_within)) {
						$date = date('d-M-Y');
						if ($posted_within == 'last24h') {
							$prev_date = strtotime($date . ' -1 day');
							$criteria->andWhere(['>=', 'createdDate', $prev_date]);

						} elseif ($posted_within == 'last7d') {
							$prev_week = strtotime($date . ' -7 day');
							$criteria->andWhere(['>=', 'createdDate', $prev_week]);

						} elseif ($posted_within == 'last30d') {
							$prev_month = strtotime($date . ' -30 day');
							$criteria->andWhere(['>=', 'createdDate', $prev_month]);
						}
					}

					//search by product condition
					if (!empty($prod_cond)) {

				$productConditionModel = Productconditions::find()->where(['condition'=>$prod_cond])->one();
				$criteria->andWhere(['productCondition' => $prod_cond]);
					}


					if (!empty($lat) && !empty($lon)) {

						$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
						/* convert km to mile calculation */
						if (!empty($distance_type)) {
							if ($distance_type == 'mi') {
								$value = 1.60934; // 1km = 0.621371 miles 
							} else {
								$value = 1;
							}
						} else {
							$distance_type = $siteSettings->searchType;
							if ($distance_type == 'miles') {
								$value = 1.60934; // 1km = 0.621371 miles 
							} else {
								$value = 1;
							}
						}

						/* convert km to mile calculation */
						if (!empty($kilometer)) {
							$distance = ($kilometer * $value) * 0.1 / 11;
						} else {
							$searchListing = $siteSettings->searchList;
							$kilometer = $searchListing[0]; // default 25 km
							$distance = ($kilometer * $value) * 0.1 / 11;
						}

						$LatN = $lat + $distance;
						$LatS = $lat - $distance;
						$LonE = $lon + $distance;
						$LonW = $lon - $distance;
						//return  "North:".$LatN." Southlat:".$LatS." West:".$LonW." Eastlon:".$LonE;
						$criteria->andWhere(['between', 'longitude', $LonW, $LonE]);
						$criteria->andWhere(['between', 'latitude', $LatS, $LatN]);

					}
					$criteria->andWhere(['approvedStatus' => 1]);
				}

				//FILTERS START
				$advFilterList = "";
	   $advFilter[] = "and"; 
	    

	   $advFilteror[] = "or"; 

	//    print_r($dropdownValues);echo "!!";print_r($multiLevelValues);echo "!!";print_r($rangeValues);echo "!!";exit;

	if(!empty($dropdownValues)) {
		$subQuery =(new Query())->select('*')->from('hts_productfilters');
		$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');	
		 $advFilterList = "added";
		 $dropdownData = array_map('intval', $dropdownValues);

		 if(count($dropdownData) > 0) {
			 $advFlag = 0;
			 foreach ($dropdownData as $key => $value) {
				
				$filter_val = Filtervalues::find()->where(['id' => $value])->one();
				$advFilter[$filter_val->filter_id][0] = "or";
				
				if($advFlag == 0) {
					$fid = $filter_val->filter_id;
					$advFilter[$filter_val->filter_id][] = ['and',
								 ['=','u.level_two',$value],
								 ['=','u.level_three',0],							
							  ];
				} else {
					$slag = 'd'.$advFlag; 
					$subQuery =(new Query())->select('*')->from('hts_productfilters');
					$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
					if ($fid == $filter_val->filter_id) {
						$fid = $filter_val->filter_id;
						$advFilter[$filter_val->filter_id][] = ['and',
									 ['=',$slag.'.level_two',$value],
									 ['=',$slag.'.level_three',0],							
								  ];
					} else {
						$fid = $filter_val->filter_id;
						$advFilter[$filter_val->filter_id][] = ['and',
									 ['=',$slag.'.level_two',$value],
									 ['=',$slag.'.level_three',0],							
								  ];
					}
					
				}
				++$advFlag;
			 }
		 }
	 }

	 //print_r($advFilter);exit;

	 $multiLevelData = array();

	 if(empty($dropdownValues) && !empty($multiLevelValues)) {
		$subQuery =(new Query())->select('*')->from('hts_productfilters');
		$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
		 $advFilterList = "added";
		 $multiLevelData = array_map('intval', array_values(array_filter( $multiLevelValues)));

		 if(count($multiLevelData) > 0) {
			 $mlFlag = 0; 
			 foreach ($multiLevelData as $key => $value) {
				$filter_val = Filtervalues::find()->where(['id' => $value])->one();
				$advFilter[$filter_val->filter_id][0] = "or";
				//$advFilter[$filter_val->filter_id][$filter_val->parentid][0] = "or";
				if($mlFlag == 0) { 
					$advFilter[$filter_val->filter_id][] =  ['and',
								['<>','u.level_two',0],
								['=','u.level_three',$value],
							];
				}else{
					$slag = 'm'.$mlFlag; 
					$subQuery =(new Query())->select('*')->from('hts_productfilters');
					$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
					if ($fid == $filter_val->filter_id) {
						$fid = $filter_val->filter_id;
						$advFilter[$filter_val->filter_id][] = ['and',
									 ['<>',$slag.'.level_two',0],
									 ['=',$slag.'.level_three',$value],							
								  ];
					} else {
						$fid = $filter_val->filter_id;
						$advFilter[$filter_val->filter_id][] = ['and',
						['<>',$slag.'.level_two',0],
						['=',$slag.'.level_three',$value],						
								  ];
					}
				}
				++$mlFlag;
			}
		 }  
	 } elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
		$subQuery =(new Query())->select('*')->from('hts_productfilters');
		$criteria->leftJoin(['v' => $subQuery], 'v.product_id=hts_products.productId');
		 $advFilterList = "added";
		 $multiLevelData = array_map('intval', array_values(array_filter( $multiLevelValues)));

		 if(count($multiLevelData) > 0) {
			 $mlFlag = 0; 
			 foreach ($multiLevelData as $key => $value) {
				$filter_val = Filtervalues::find()->where(['id' => $value])->one();
				$advFilter[$filter_val->filter_id][0] = "or";
				//$advFilter[$filter_val->filter_id][$filter_val->parentid][0] = "or";
				if($mlFlag == 0) { 
					$advFilter[$filter_val->filter_id][] =  ['and',
								['<>','v.level_two',0],
								['=','v.level_three',$value],
							];
				}else{
					$slag = 'm'.$mlFlag; 
					$subQuery =(new Query())->select('*')->from('hts_productfilters');
					$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=v.product_id');
					if ($fid == $filter_val->filter_id) {
						$fid = $filter_val->filter_id;
						$advFilter[$filter_val->filter_id][] = ['and',
									 ['<>',$slag.'.level_two',0],
									 ['=',$slag.'.level_three',$value],							
								  ];
					} else {
						$fid = $filter_val->filter_id;
						$advFilter[$filter_val->filter_id][] = ['and',
						['<>',$slag.'.level_two',0],
						['=',$slag.'.level_three',$value],						
								  ];
					}
				}
				++$mlFlag;
			}
		 }  
	 }

	// print_r($advFilter);exit;

	//  if(empty($dropdownValues) && !empty($multiLevelValues)) {
	// 	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 	$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
	// 	 $advFilterList = "added";
	// 	 $multiLevelData = array_map('intval', array_values(array_filter(explode(',', $multiLevelValues))));
	// 	 $filter_val = Filtervalues::find()->where(['id' => $multiLevelData[0]])->one();

	// 	 if(count($multiLevelData) > 0) {
	// 		 $mlFlag = 0; 
	// 		 if($mlFlag == 0) { 
	// 			$fid = $filter_val->filter_id;
	// 			$advFilter[] = ['and',
	// 						 ['<>','u.level_two',0],
	// 						 ['=','u.level_three',$multiLevelData[0]],
	// 					  ];
	// 		 }
	// 		 ++$mlFlag;
	// 	 }  
	//  } elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
	// 	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 	$criteria->leftJoin(['v' => $subQuery], 'v.product_id=u.product_id');
	// 	 $advFilterList = "added";
	// 	 $multiLevelData = array_map('intval', array_values(array_filter(explode(',', $multiLevelValues)))); 
	// 	 $filter_val = Filtervalues::find()->where(['id' => $multiLevelData[0]])->one();

	// 	 if(count($multiLevelData) > 0) {
	// 		 $mlFlag = 0;
	// 		 if($mlFlag == 0) { 
	// 			$fid = $filter_val->filter_id;
	// 			$advFilter[] = ['and',
	// 						 ['<>','v.level_two',0],
	// 						 ['=','v.level_three',$multiLevelData[0]],
	// 					  ];
	// 		 }
	// 		 ++$mlFlag;
	// 	 } 
	//  }


	//  if(!empty($multiLevelValues) && count($multiLevelData) > 1) {
	// 	 for ($i=$mlFlag; $i<count($multiLevelData); $i++) {
	// 		 $mlFlag = $i;  
	// 		 $filter_val = Filtervalues::find()->where(['id' => $multiLevelData[$i]])->one();
	// 		 if($mlFlag > 0) {
	// 			$slag = 'm'.$mlFlag; 
	// 			$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 			$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
	// 			if ($fid == $filter_val->filter_id) {
	// 				$fid = $filter_val->filter_id;
	// 				$advFilter[] = ['and',
	// 						['<>',$slag.'.level_two',0],
	// 						['=',$slag.'.level_three',$multiLevelData[$i]],							
	// 				];
	// 			}
	// 			else{
	// 				$fid = $filter_val->filter_id;
	// 				$advFilter[] = ['and',
	// 						['<>',$slag.'.level_two',0],
	// 						['=',$slag.'.level_three',$multiLevelData[$i]],							
	// 				];
	// 			}
	// 		} 
	// 	 }
	//  } 
	 
	if(count($rangeValues) > 0 && !empty($rangeValues)) { 
		$advFlag = 0;
		foreach ($rangeValues as $key => $value) {
			//$valueRange = explode(';', $value['value']);
			if(count($value) == 3) {
				if($advFlag == 0) {						
					$subQuery =(new Query())->select('*')->from('hts_productfilters');
					if(empty($dropdownValues) && empty($multiLevelValues)) {
						 $criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
						 $advFilter[] = ['and',
										['=', 'u.filter_id', $value['id'] ],
										['>=', 'u.filter_values', $value['min_value'] ], 
										['<=', 'u.filter_values', $value['max_value'] ],  
										['=', 'u.filter_type', 'range'],   
								 ];
					} elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
						 $criteria->leftJoin(['w' => $subQuery], 'w.product_id=u.product_id');
						 $advFilter[] = ['and',
										['=', 'w.filter_id', $value['id'] ],
										['>=', 'w.filter_values', $value['min_value'] ], 
										['<=', 'w.filter_values', $value['max_value'] ],   
										['=', 'w.filter_type', 'range'],   
								 ];
					} else {
						$criteria->leftJoin(['v' => $subQuery], 'v.product_id=u.product_id');
						$advFilter[] = ['and',
										['=', 'v.filter_id', $value['id'] ],
										['>=', 'v.filter_values', $value['min_value'] ], 
										['<=', 'v.filter_values', $value['max_value'] ],  
										['=', 'v.filter_type', 'range'],   
								 ];
					} 
					++$advFlag;
				} else { 
					$slag = 'r'.$advFlag; 
					$criteria->leftJoin([ $slag => $subQuery], $slag.'.product_id=u.product_id');
						$advFilter[] = ['and',
										['=', $slag.'.filter_id', $value['id'] ],
										['>=', $slag.'.filter_values', $value['min_value'] ], 
										['<=', $slag.'.filter_values', $value['max_value'] ], 
										['=', $slag.'.filter_type', 'range'],   
								 ]; 
							++$advFlag; 
				}
			}	
		}
	}  
	
				if(!empty($dropdownValues) || !empty($multiLevelValues) || !empty($rangeValues)) {  
					$criteria->andWhere($advFilter); 
		         $criteria->groupBy('u.product_id');   
				}

				//FILTERS END
				//echo $criteria->createCommand()->getRawSql();
				//die;

				$itemModel = $criteria->all();

				$adsarray = [];
				$adsPosition = [];
				$arrayad = [];
				if(isset($adsCriteria)){
					$adsProducts = $adsCriteria->all();

					if (!empty($adsProducts)) {
							$adsProductss = $this->convertJsonItems($adsProducts);
							foreach ($adsProducts as $value) {
								$adsPositionn = rand(0,2);
								$adsarray[] = $value->productId;
								$adsPosition[] = $adsPositionn;
							}	

							if(isset($itemModel))
							{
								if($itemModel > 3){
									$subitem = array_chunk($itemModel,3);
								}
								else
								{
									$subitem[0] = $itemModel;
								}

								if(count($subitem) > count($adsProducts))
								{
									
									foreach ($subitem as $keys=>$subs) {
										$position = $adsPosition[$keys];
										$avalue = $adsarray[$keys];
										$arrayad[] = $adsarray[$keys];
										
										foreach ($subs as $key=>$value) {
												if($position == $key)
												{
													$temp = Products::findOne($avalue);
													$subitem[$keys][$position] = $temp;
													$subitem[$keys][3] = $value;
												}
												else{
													$subitem[$keys][$key] = $value;
												}
											} 
									}
								}else
								{
									for ($i=0; $i < count($adsarray); $i++) { 
										$position = $adsPosition[$i];
										$avalue = $adsarray[$i];
										$arrayad[] = $adsarray[$i];
										
										if(!empty($subitem[$i]))
										{
											foreach ($subitem[$i] as $key=>$value) {
												if($position == $key)
												{
													$temp = Products::findOne($avalue);
													$subitemm[$i][$position] = $temp;
													$subitemm[$i][3] = $value;
												}
												else{
													$subitemm[$i][$key] = $value;
												}
											} 
										}
										else{
											$temp = Products::findOne($avalue);
											$subitemm[$i][$position]=$temp;
										}	
									}
									$subitem = $subitemm;
								}

								foreach ($subitem as $value) {
									foreach ($value as $key) {
										$newarray[] = $key;
									}
								}
								$itemModel = $newarray;
							}
					}
					
				}


				
				if($adsarrays != '' || $arrayad != '')
				{
						$ads = array_merge($adsarrays,$arrayad);
				}

				if (!empty($itemModel)) {
					if ($user_id != 0) {
						$result = $this->convertJsonItems($itemModel, $user_id, $seller_id);
					} else {
						$result = $this->convertJsonItems($itemModel);
					}
					return '{"status": "true","ads":'.json_encode($ads).',"result":' . $result . '}';
					die;
				} else {
					return '{"status":"false","ads":'.json_encode($ads).',"message":"No item found"}';
				}
			} else {
				return $this->errorMessage;
			}
		} else {
			return '{"status":"false", "message":"Unauthorized Access to the API"}';
		}

	}
	
	public function actionGetitems() 
	{
		//Post Values
		$api_username = $_POST['api_username'];
		$api_password = $_POST['api_password'];

		// if ($this->authenticateAPI($api_username, $api_password)) {
			//Post Values
			$user_id = "";
			if (isset($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
			}

			$lang_type = "";
			if (isset($_POST['lang_type'])) {
				$lang_type = $_POST['lang_type'];
				yii::$app->Myclass->push_lang($lang_type);
			}
			if ($this->checking($user_id)) {
				//Post Values
				$type = "";
				if (isset($_POST['type'])) {
					$type = $_POST['type'];
				}
				$searchtype = "";
				if (isset($_POST['search_type'])) {
					$searchtype = $_POST['search_type'];
				}
				$price = "";
				if (isset($_POST['price'])) {
					$price = $_POST['price'];
				}
				$search_key = "";
				if (isset($_POST['search_key'])) {
					$search_key = $_POST['search_key'];
				}
				$category_id = "";
				if (isset($_POST['category_id'])) {
					$category_id = $_POST['category_id'];
				}
				$subcategory_id = "";
				if (isset($_POST['subcategory_id'])) {
					$subcategory_id = $_POST['subcategory_id'];
				}
				$sub_subcategory_id = "";
				if (isset($_POST['child_category_id'])) {
					$sub_subcategory_id = $_POST['child_category_id'];
				}
				$item_id = '';
				if (isset($_POST['item_id'])) {
					$item_id = $_POST['item_id'];
				}
				$seller_id = '';
				if (isset($_POST['seller_id'])) {
					$seller_id = $_POST['seller_id'];
				}
				$sorting_id = "";
				if (isset($_POST['sorting_id'])) {
					$sorting_id = $_POST['sorting_id'];
				}

				$prod_cond = 0;
				if (isset($_POST['product_condition'])) {
					$prod_cond = $_POST['product_condition'];
				}

				// FILTERS - GET ITEMS
				$filtersArray = array();
				if (isset($_POST['filters'])) {
					$filtersArray = Json::decode($_POST['filters'], true); 
				}  
				
				$dropdownValues = (isset($filtersArray['dropdown'])) ? $filtersArray['dropdown'] : ""; 
				$multiLevelValues = (isset($filtersArray['multilevel'])) ? $filtersArray['multilevel'] : "";
				$rangeValues = (isset($filtersArray['range'])) ? $filtersArray['range'] : "";   
				// FILTERS END  

				$lat = "";
				if (isset($_POST['lat'])) {
					$lat = $_POST['lat'];
				}
				$lon = "";
				if (isset($_POST['lon'])) {
					$lon = $_POST['lon'];
				}
				$posted_within = "";
				if (isset($_POST['posted_within'])) {
					$posted_within = $_POST['posted_within'];
				}
				$distance = "";
				$kilometer = "";
				if (isset($_POST['distance'])) {
					$kilometer = $_POST['distance'];
				}

				$distance_type = "";
				if (isset($_POST['distance_type'])) {
					$distance_type = $_POST['distance_type'];
				}

				$limit = 20;
				if (isset($_POST['limit'])) {
					$limit = $_POST['limit'];
				}
				$offset = 0;
				if (isset($_POST['offset'])) {
					$offset = $_POST['offset'];
				}

				$adsarray = '';
				$adsarrays = [];
				$ads = '';
				if (isset($_POST['ads'])) {
					$adsarray = $_POST['ads'];
					$adsarrays = json_decode($_POST['ads']);
				}

				$criteria = Products::find();
				$criteria->orderBy(['productId' => SORT_DESC]);
				
				
				// 	if (isset($_POST['user_id']) && !empty($user_id)) {
				// 	$criteria->andWhere(['userId'=> $_POST['user_id']]);
				// }
				
				if($type == 'search' && $searchtype == 'all')
				{
					$criteria->andWhere(['approvedStatus' => 1]);
					
					$adsCriteria = clone $criteria;
					$adsProducts = "";
					$adsCriteria->andWhere(['promotionType' => '1']);
					$adsCriteria->andWhere(['approvedStatus' => 1]);
					if($adsarray != '')
					{
						$adsCriteria->andWhere(['NOT IN','productId', json_decode($adsarray)]);
					}
					$adsCriteria->orderBy(new Expression('rand()'));
					$adsCriteria->limit(7);			

					$criteria->andWhere(['<>','promotionType',1]);
					
				}

				$criteria->limit($limit);
				$criteria->offset($offset);
				if ($type == 'liked') {
						// $likecriteria = clone $criteria;
						// $likecriteria->addCondition("userId = '$user_id'");
						if (isset($user_id) && isset($seller_id) && $user_id != $seller_id) {
							$favouriteModel = Favorites::find()->where(['userId' => $seller_id])->orderBy(['productId' => SORT_DESC])->limit($limit)->offset($offset)->all();
						}
						else{
							$favouriteModel = Favorites::find()->where(['userId' => $user_id])->orderBy(['productId' => SORT_DESC])->limit($limit)->offset($offset)->all();
						}
					
					if (!empty($favouriteModel)) {
						foreach ($favouriteModel as $favourite) {
							$likedproducts[] = $favourite->productId;
						}
						$criteria->andWhere(['productId' => $likedproducts]);
					} else {
						$criteria->andWhere(['productId' => '']);
					}

				}
				if ($type == 'home' || $type == 'search') {
					if (isset($sorting_id) && $sorting_id != 0) {
						switch ($sorting_id) {
							case 1:
								$criteria->orderBy(['productId' => SORT_DESC]);
								break;
							case 2:
								$criteria->orderBy(['likes' => SORT_DESC]);
								break;
							case 3:
								$criteria->orderBy(['price' => SORT_DESC]);
								break;
							case 4:
								$criteria->orderBy(['price' => SORT_ASC]);
								break;
							case 5:
								$criteria->andWhere(['promotionType' => 2]);
								break;
						}


					}
					

				}
				

			
				if ($type == 'moreitems') {

						if (isset($user_id) && isset($seller_id) && $user_id != $seller_id) {
						$criteria->andWhere(['approvedStatus' => 1]);
					}
					if (!empty($seller_id)) {

						$sellerId = $seller_id;
						$criteria->andWhere(['userId' => $sellerId]);
						if ($item_id != 0) {
							$itemId = $item_id;
							$criteria->andWhere(['<>', 'productId', $itemId]);
						}
					} else {

					}


			
					
				}

				elseif ($type == 'recentlyviewed') {
						if (isset($user_id)){


							$criteria->andWhere(['approvedStatus' => 1]);
							$curruserdetails = Users::find()->where(['userId' => $user_id])->one();
			if(empty($curruserdetails->recently_view_product)) {
			
				$prodctdata[] = $item_id;
				$prodctdetl = json_encode($prodctdata);
				$curruserdetails->recently_view_product = $prodctdetl;
				$curruserdetails->save(false);
			} else {
				$product_exists = json_decode($curruserdetails->recently_view_product,true);
				if(!in_array($item_id,$product_exists)) {
					$new_product[] = $item_id;
					$real_products = array_merge($new_product,$product_exists);
					$prodctdata = array_slice($real_products,0,5);
					$prodctdetl = json_encode($prodctdata);
					$curruserdetails->recently_view_product = $prodctdetl;
					$curruserdetails->save(false);
				}
			}
					

							if($curruserdetails->recently_view_product != "" && $curruserdetails->recently_view_product != null)
								$prodctIds = json_decode($curruserdetails->recently_view_product,true);

						if ($item_id != 0 && count($prodctIds) > 0) {
							$product_ids = array_diff($prodctIds,[$itemId]);
						
							$criteria->andWhere(['IN', 'productId', $product_ids]);
							$criteria->andWhere(['<>', 'productId', $item_id]);
							// $recentlyprodcts = Products::find()->where(['productId'=>$product_ids,'approvedStatus'=>'1'])->limit(4)->all();
						}
						}

				}
				 elseif ($type == 'search') {

					$criteria->andWhere(['<>','soldItem',1]);
					/* if ($user_id != 0){
						$criteria->addCondition('userId = '.$user_id);
					} */
					if (isset($category_id) && $category_id != '') {
						if (isset($sorting_id) && $sorting_id == 1) {
							$criteria->orderBy(['promotionType' => SORT_ASC,'createdDate' => SORT_DESC]);
						}
						if (strpos($category_id, ',') === false) {
							$criteria->andWhere(['category' => $category_id]);
						} else {
							$category_ids = explode(",", $category_id);
							$criteria->andWhere(['category' => $category_ids]);
						}

					}
					if (isset($subcategory_id) && $subcategory_id != '') {
					if (isset($sorting_id) && $sorting_id == 1) {
							$criteria->orderBy(['promotionType' => SORT_ASC,'createdDate' => SORT_DESC]);
						}
						if (strpos($subcategory_id, ',') === false) {
							$criteria->andWhere(['subCategory' => $subcategory_id]);
						} else {
							$subcategory_ids = explode(",", $subcategory_id);
							$criteria->andWhere(['subCategory' => $subcategory_ids]);
						}
					}
					if (isset($sub_subcategory_id) && $sub_subcategory_id != '') {
						if (isset($sorting_id) && $sorting_id == 1) {
							$criteria->orderBy(['promotionType' => SORT_ASC,'createdDate' => SORT_DESC]);
						}
						if (strpos($sub_subcategory_id, ',') === false) {
							$criteria->andWhere(['sub_subCategory' => $sub_subcategory_id]);
						} else {
							$sub_subcategory_id = explode(",", $sub_subcategory_id);
							$criteria->andWhere(['sub_subCategory' => $sub_subcategory_id]);
						}
					}

					if (!empty($search_key)) {
						$criteria->andWhere(['like', 'name', $search_key]);
						if (isset($sorting_id) && $sorting_id == 1) {
							$criteria->orderBy(['promotionType' => SORT_ASC,'createdDate' => SORT_DESC]);
						}
					}
					if (!empty($price)) {
						$dataPrice = explode('-', $price);


						/*
						if (count($priceRange) > 1)
							$criteria->andWhere(['between', 'price', $priceRange[0], $priceRange[1]]);
						else
							$criteria->andWhere(['<=', 'price', $priceRange[0]]);
						*/

						if($dataPrice[0]=='0' && $dataPrice[1]>='5000'){
							$criteria->andWhere(['>=','price',0]);
						}elseif($dataPrice[0]>'0' && $dataPrice[1]>='5000'){
							    $criteria->andWhere(['>=','price',$dataPrice[0]]);
	                    }elseif($dataPrice[0]=='0' && $dataPrice[1]=='0'){
	                    	    $criteria->andWhere(['=','price' , $dataPrice[0]]);
						}elseif($dataPrice[0]=='0' && $dataPrice[1]<'5000'){
							
	                            $criteria->andWhere(['<=','price' , $dataPrice[1]]);
						}else{
							    $criteria->andWhere(['>=','price', $dataPrice[0]]);
	                            $criteria->andWhere(['<=','price',$dataPrice[1]]);                          
						}
					}

					if (!empty($posted_within)) {
						if (isset($sorting_id) && $sorting_id == 1) {
							$criteria->orderBy(['promotionType' => SORT_ASC,'createdDate' => SORT_DESC]);
						}
						$date = date('d-M-Y');
						if ($posted_within == 'last24h') {
							$prev_date = strtotime($date . ' -1 day');
							$criteria->andWhere(['>=', 'createdDate', $prev_date]);

						} elseif ($posted_within == 'last7d') {
							$prev_week = strtotime($date . ' -7 day');
							$criteria->andWhere(['>=', 'createdDate', $prev_week]);

						} elseif ($posted_within == 'last30d') {
							$prev_month = strtotime($date . ' -30 day');
							$criteria->andWhere(['>=', 'createdDate', $prev_month]);
						}
					}

					//search by product condition
					if (!empty($prod_cond)) {
						if (isset($sorting_id) && $sorting_id == 1) {
							$criteria->orderBy(['promotionType' => SORT_ASC,'createdDate' => SORT_DESC]);
						}

						// $productConditionModel = Productconditions::find()->where(['condition'=>$prod_cond])->one();
						$criteria->andWhere(['productCondition' => $prod_cond]);

						// $criteria->andWhere(['productCondition' => $prod_cond]);
					}


					if (!empty($lat) && !empty($lon)) {
						if (isset($sorting_id) && $sorting_id == 1) {
							$criteria->orderBy(['promotionType' => SORT_ASC,'createdDate' => SORT_DESC]);
						}
						$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
						/* convert km to mile calculation */
						if (!empty($distance_type)) {
							if ($distance_type == 'mi') {
								$value = 1.60934; // 1km = 0.621371 miles 
							} else {
								$value = 1;
							}
						} else {
							$distance_type = $siteSettings->searchType;
							if ($distance_type == 'miles') {
								$value = 1.60934; // 1km = 0.621371 miles 
							} else {
								$value = 1;
							}
						}

						/* convert km to mile calculation */
						if (!empty($kilometer)) {
							$distance = ($kilometer * $value) * 0.1 / 11;
						} else {
							$searchListing = $siteSettings->searchList;
							$kilometer = $searchListing[0]; // default 25 km
							$distance = ($kilometer * $value) * 0.1 / 11;
						}

						$LatN = $lat + $distance;
						$LatS = $lat - $distance;
						$LonE = $lon + $distance;
						$LonW = $lon - $distance;
						//return  "North:".$LatN." Southlat:".$LatS." West:".$LonW." Eastlon:".$LonE;
						$criteria->andWhere(['between', 'longitude', $LonW, $LonE]);
						$criteria->andWhere(['between', 'latitude', $LatS, $LatN]);

					}
					$criteria->andWhere(['approvedStatus' => 1]);
				}

				//FILTERS START
				$advFilterList = "";
	   $advFilter[] = "and"; 
	    

	   $advFilteror[] = "or"; 

	//    print_r($dropdownValues);echo "!!";print_r($multiLevelValues);echo "!!";print_r($rangeValues);echo "!!";exit;

	if(!empty($dropdownValues)) {
		$subQuery =(new Query())->select('*')->from('hts_productfilters');
		$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');	
		 $advFilterList = "added";
		 $dropdownData = array_map('intval', $dropdownValues);

		 if(count($dropdownData) > 0) {
			 $advFlag = 0;
			 foreach ($dropdownData as $key => $value) {
				
				$filter_val = Filtervalues::find()->where(['id' => $value])->one();
				$advFilter[$filter_val->filter_id][0] = "or";
				
				if($advFlag == 0) {
					$fid = $filter_val->filter_id;
					$slag = 'u';
					$advFilter[$filter_val->filter_id][] = ['and',
								 ['=','u.level_two',$value],
								 ['=','u.level_three',0],							
							  ];
				} else {
					
					if ($fid == $filter_val->filter_id) {
						$fid = $filter_val->filter_id;
						$fslag = $slag;
						$advFilter[$filter_val->filter_id][] = ['and',
									 ['=',$fslag.'.level_two',$value],
									 ['=',$fslag.'.level_three',0],							
								  ];
					} else {
						$slag = 'd'.$advFlag; 
						$subQuery =(new Query())->select('*')->from('hts_productfilters');
						$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
						$fid = $filter_val->filter_id;
						$fslag = $slag;
						$advFilter[$filter_val->filter_id][] = ['and',
									 ['=',$fslag.'.level_two',$value],
									 ['=',$fslag.'.level_three',0],							
								  ];
					}
					
				}
				++$advFlag;
			 }
		 }
	 }

	 //print_r($advFilteror);

	 $multiLevelData = array();

	 if(empty($dropdownValues) && !empty($multiLevelValues)) {
		$subQuery =(new Query())->select('*')->from('hts_productfilters');
		$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
		 $advFilterList = "added";
		 $multiLevelData = array_map('intval', array_values(array_filter($multiLevelValues)));

		 if(count($multiLevelData) > 0) {
			 $mlFlag = 0; 
			 foreach ($multiLevelData as $key => $value) {
				$filter_val = Filtervalues::find()->where(['id' => $value])->one();
				$advFilter[$filter_val->filter_id][0] = "or";
				//$advFilter[$filter_val->filter_id][$filter_val->parentid][0] = "or";
				if($mlFlag == 0) { 
					$fid = $filter_val->filter_id;
					$slag = 'u';
					$advFilter[$filter_val->filter_id][] =  ['and',
								['<>','u.level_two',0],
								['=','u.level_three',$value],
							];
				}else{
					
					if ($fid == $filter_val->filter_id) {
						$fid = $filter_val->filter_id;
						$fslag = $slag;
						$advFilter[$filter_val->filter_id][] = ['and',
									 ['<>',$fslag.'.level_two',0],
									 ['=',$fslag.'.level_three',$value],							
								  ];
					} else {
						$slag = 'm'.$mlFlag; 
						$subQuery =(new Query())->select('*')->from('hts_productfilters');
						$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
						$fid = $filter_val->filter_id;
						$fslag = $slag;
						$advFilter[$filter_val->filter_id][] = ['and',
						['<>',$fslag.'.level_two',0],
						['=',$fslag.'.level_three',$value],						
								  ];
					}
				}
				++$mlFlag;
			}
		 }  
	 } elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
		$subQuery =(new Query())->select('*')->from('hts_productfilters');
		$criteria->leftJoin(['v' => $subQuery], 'v.product_id=hts_products.productId');
		 $advFilterList = "added";
		 $multiLevelData = array_map('intval', array_values(array_filter($multiLevelValues)));

		 if(count($multiLevelData) > 0) {
			 $mlFlag = 0; 
			 foreach ($multiLevelData as $key => $value) {
				$filter_val = Filtervalues::find()->where(['id' => $value])->one();
				$advFilter[$filter_val->filter_id][0] = "or";
				//$advFilter[$filter_val->filter_id][$filter_val->parentid][0] = "or";
				if($mlFlag == 0) { 
					$fid = $filter_val->filter_id;
					$slag = 'v';
					$advFilter[$filter_val->filter_id][] =  ['and',
								['<>','v.level_two',0],
								['=','v.level_three',$value],
							];
				}else{
					
					if ($fid == $filter_val->filter_id) {
						$fid = $filter_val->filter_id;
						$fslag = $slag;
						$advFilter[$filter_val->filter_id][] = ['and',
									 ['<>',$fslag.'.level_two',0],
									 ['=',$fslag.'.level_three',$value],							
								  ];
					} else {
						$slag = 'm'.$mlFlag; 
						$subQuery =(new Query())->select('*')->from('hts_productfilters');
						$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=v.product_id');
						$fid = $filter_val->filter_id;
						$fslag = $slag;
						$advFilter[$filter_val->filter_id][] = ['and',
						['<>',$fslag.'.level_two',0],
						['=',$fslag.'.level_three',$value],						
								  ];
					}
				}
				++$mlFlag;
			}
		 }  
	 }

	// print_r($advFilter);exit;

	//  if(empty($dropdownValues) && !empty($multiLevelValues)) {
	// 	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 	$criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
	// 	 $advFilterList = "added";
	// 	 $multiLevelData = array_map('intval', array_values(array_filter(explode(',', $multiLevelValues))));
	// 	 $filter_val = Filtervalues::find()->where(['id' => $multiLevelData[0]])->one();

	// 	 if(count($multiLevelData) > 0) {
	// 		 $mlFlag = 0; 
	// 		 if($mlFlag == 0) { 
	// 			$fid = $filter_val->filter_id;
	// 			$advFilter[] = ['and',
	// 						 ['<>','u.level_two',0],
	// 						 ['=','u.level_three',$multiLevelData[0]],
	// 					  ];
	// 		 }
	// 		 ++$mlFlag;
	// 	 }  
	//  } elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
	// 	$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 	$criteria->leftJoin(['v' => $subQuery], 'v.product_id=u.product_id');
	// 	 $advFilterList = "added";
	// 	 $multiLevelData = array_map('intval', array_values(array_filter(explode(',', $multiLevelValues)))); 
	// 	 $filter_val = Filtervalues::find()->where(['id' => $multiLevelData[0]])->one();

	// 	 if(count($multiLevelData) > 0) {
	// 		 $mlFlag = 0;
	// 		 if($mlFlag == 0) { 
	// 			$fid = $filter_val->filter_id;
	// 			$advFilter[] = ['and',
	// 						 ['<>','v.level_two',0],
	// 						 ['=','v.level_three',$multiLevelData[0]],
	// 					  ];
	// 		 }
	// 		 ++$mlFlag;
	// 	 } 
	//  }


	//  if(!empty($multiLevelValues) && count($multiLevelData) > 1) {
	// 	 for ($i=$mlFlag; $i<count($multiLevelData); $i++) {
	// 		 $mlFlag = $i;  
	// 		 $filter_val = Filtervalues::find()->where(['id' => $multiLevelData[$i]])->one();
	// 		 if($mlFlag > 0) {
	// 			$slag = 'm'.$mlFlag; 
	// 			$subQuery =(new Query())->select('*')->from('hts_productfilters');
	// 			$criteria->leftJoin([$slag => $subQuery], $slag.'.product_id=u.product_id');
	// 			if ($fid == $filter_val->filter_id) {
	// 				$fid = $filter_val->filter_id;
	// 				$advFilter[] = ['and',
	// 						['<>',$slag.'.level_two',0],
	// 						['=',$slag.'.level_three',$multiLevelData[$i]],							
	// 				];
	// 			}
	// 			else{
	// 				$fid = $filter_val->filter_id;
	// 				$advFilter[] = ['and',
	// 						['<>',$slag.'.level_two',0],
	// 						['=',$slag.'.level_three',$multiLevelData[$i]],							
	// 				];
	// 			}
	// 		} 
	// 	 }
	//  } 
	 
	if(count($rangeValues) > 0 && !empty($rangeValues)) { 
		$advFlag = 0;
		foreach ($rangeValues as $key => $value) {
			//$valueRange = explode(';', $value['value']);
			if(count($value) == 3) {
				if($advFlag == 0) {						
					$subQuery =(new Query())->select('*')->from('hts_productfilters');
					if(empty($dropdownValues) && empty($multiLevelValues)) {
						 $criteria->leftJoin(['u' => $subQuery], 'u.product_id=hts_products.productId');
						 $advFilter[] = ['and',
										['=', 'u.filter_id', $value['id'] ],
										['>=', 'u.filter_values', $value['min_value'] ], 
										['<=', 'u.filter_values', $value['max_value'] ],  
										['=', 'u.filter_type', 'range'],   
								 ];
					} elseif(!empty($dropdownValues) && !empty($multiLevelValues)) {
						 $criteria->leftJoin(['w' => $subQuery], 'w.product_id=u.product_id');
						 $advFilter[] = ['and',
										['=', 'w.filter_id', $value['id'] ],
										['>=', 'w.filter_values', $value['min_value'] ], 
										['<=', 'w.filter_values', $value['max_value'] ],   
										['=', 'w.filter_type', 'range'],   
								 ];
					} else {
						$criteria->leftJoin(['v' => $subQuery], 'v.product_id=u.product_id');
						$advFilter[] = ['and',
										['=', 'v.filter_id', $value['id'] ],
										['>=', 'v.filter_values', $value['min_value'] ], 
										['<=', 'v.filter_values', $value['max_value'] ],  
										['=', 'v.filter_type', 'range'],   
								 ];
					} 
					++$advFlag;
				} else { 
					$slag = 'r'.$advFlag; 
					$criteria->leftJoin([ $slag => $subQuery], $slag.'.product_id=u.product_id');
						$advFilter[] = ['and',
										['=', $slag.'.filter_id', $value['id'] ],
										['>=', $slag.'.filter_values', $value['min_value'] ], 
										['<=', $slag.'.filter_values', $value['max_value'] ], 
										['=', $slag.'.filter_type', 'range'],   
								 ]; 
							++$advFlag; 
				}
			}	
		}
	}  
	
				if(!empty($dropdownValues) || !empty($multiLevelValues) || !empty($rangeValues)) {  
					$criteria->andWhere($advFilter); 
		         $criteria->groupBy('u.product_id');   
				}

				//FILTERS END
				//echo $criteria->createCommand()->getRawSql();
				//die;

		

				$itemModel = $criteria->all();

				$adsarray = [];
				$adsPosition = [];
				$arrayad = [];
				if(isset($adsCriteria)){
					$adsProducts = $adsCriteria->all();
				
					
					if (!empty($adsProducts)) {
							$adsProductss = $this->convertJsonItems($adsProducts);
							foreach ($adsProducts as $value) {
								$adsPositionn = rand(0,2);
								$adsarray[] = $value->productId;
								$adsPosition[] = $adsPositionn;
							}	

							if(isset($itemModel))
							{

								if($itemModel > 3){
									$subitem = array_chunk($itemModel,3);
									
								}
								else
								{
									$subitem[0] = $itemModel;
								}
							
								if(count($subitem) > count($adsProducts))
								{
									
									foreach ($subitem as $keys=>$subs) {
										$position = $adsPosition[$keys];
										$avalue = $adsarray[$keys];
										if($adsarray[$keys] != "")
										    $arrayad[] = $adsarray[$keys];
										
										foreach ($subs as $key=>$value) {
												if($position == $key)
												{
													$temp = Products::findOne($avalue);
													$subitem[$keys][$position] = $temp;
													$subitem[$keys][3] = $value;
												}
												else{
													$subitem[$keys][$key] = $value;
												}
											} 
									}
								}else
								{
									for ($i=0; $i < count($adsarray); $i++) { 
										$position = $adsPosition[$i];
										$avalue = $adsarray[$i];

										$arrayad[] = $adsarray[$i];
									
										if(!empty($subitem[$i]))
										{
											foreach ($subitem[$i] as $key=>$value) {
												if($position == $key)
												{
													$temp = Products::findOne($avalue);
													$subitemm[$i][$position] = $temp;
													$subitemm[$i][3] = $value;
												}
												else{
													$subitemm[$i][$key] = $value;
												}
											} 
										}
										else{
											$temp = Products::findOne($avalue);
											$subitemm[$i][$position]=$temp;
										}	
									}
									$subitem = $subitemm;
								}
								$j = 0;
								foreach ($subitem as $value) {
									foreach ($value as $key) {
										if($key->productId != "" && $j <= $limit)
										     $newarray[] = $key;
									$j++;
									}
								}
								$itemModel = $newarray;
							}
					}
					
				}

				// echo "<pre>"; print_r($arrayad); die;
				
				if($adsarrays != '' || $arrayad != '')
				{
						$ads = array_merge($adsarrays,$arrayad);
				}


				if (!empty($itemModel)) {
					if ($user_id != 0) {
						$result = $this->convertJsonItems($itemModel, $user_id, $seller_id);
					} else {
						$result = $this->convertJsonItems($itemModel);
					}
					return '{"status": "true","ads":'.json_encode($ads).',"result":' . $result . '}';
					die;
				} else {
					return '{"status":"false","ads":'.json_encode($ads).',"message":"No item found"}';
				}
			} else {
				return $this->errorMessage;
			}
		// } else {
		// 	return '{"status":"false", "message":"Unauthorized Access to the API"}';
		// }

	}

}

