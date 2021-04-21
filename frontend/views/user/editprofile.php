<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Sitesettings;
use conquer\toastr\ToastrWidget;
?>
<?= Html::csrfMetaTags() ?>
<style>
	.file-upload{
		cursor: pointer;
		height: 40px;
		position: absolute;
		left: 94px;
		top: 65px;
		width: 33%;
		opacity: 0;
	}
	.footer {
		margin-top: 0px !important;
	}
</style>
<div id="page-container" class="container">
	<div class="row">
		<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<ol class="breadcrumb">
				<li><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/'); ?>"><?php echo Yii::t('app','Home'); ?></a></li>
				<li><a href="#"><?php echo Yii::t('app','Profile'); ?></a></li>
			</ol>
			<?php  if(Yii::$app->session->hasFlash('success')): ?>
				<?=ToastrWidget::widget(['type' => 'success', 'message'=>Yii::$app->session->getFlash('success'),
					"closeButton" => true,
					"debug" => false,
					"newestOnTop" => false,
					"progressBar" => false,
					"positionClass" => ToastrWidget::POSITION_TOP_RIGHT,
					"preventDuplicates" => false,
					"onclick" => null,
					"showDuration" => "300",
					"hideDuration" => "1000",
					"timeOut" => "5000",
					"extendedTimeOut" => "1000",
					"showEasing" => "swing",
					"hideEasing" => "linear",
					"showMethod" => "fadeIn",
					"hideMethod" => "fadeOut"
				]);?>
			<?php endif; ?>
			<?php if(Yii::$app->session->hasFlash('error')): ?>
				<?=ToastrWidget::widget(['type' => 'error', 'message'=>Yii::$app->session->getFlash('error'),
					"closeButton" => true,
					"debug" => false,
					"newestOnTop" => false,
					"progressBar" => false,
					"positionClass" => ToastrWidget::POSITION_TOP_RIGHT,
					"preventDuplicates" => false,
					"onclick" => null,
					"showDuration" => "300",
					"hideDuration" => "1000",
					"timeOut" => "5000",
					"extendedTimeOut" => "1000",
					"showEasing" => "swing",
					"hideEasing" => "linear",
					"showMethod" => "fadeIn",
					"hideMethod" => "fadeOut"
				]);?>
			<?php endif; ?>
		</div>
		<div class="modal fade" id="mobile-otp" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-width">
				<div class="chat-seller-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="otp-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<button onClick="close_otp()" class="close chat-with-seller-close" type="button">×</button>
						<div class="otp-modal-content col-xs-9 col-sm-10 col-md-10 col-lg-10 no-hor-padding">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Enter One Time Password'); ?></div>
						</div>
					</div>
					<div class="messgage-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="otp-message col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','One Time Password (OTP) has been sent to your mobile'); ?> <span class="mob_code"></span><?php echo Yii::t('app',', please enter the same here to login.'); ?></div>
						<div class="profile-input-fields col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><input type="text" placeholder="<?php echo Yii::t('app','Enter your OTP code');?>" id="otp_code" maxlength="10"></div>
						<div class="otp-message"><?php echo Yii::t('app','Please enter this code:'); ?> <span class="rand_code"></span></div>
						<p id="verification_error" class="errorMessage"></p>
						<div class="verify-otp-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<div class="change-pwd-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><a href="javascript:;" onClick="verify_otp()" id="verify_text"><?php echo Yii::t('app','Verify'); ?></a></div><div class="otp-error"><?php echo Yii::t('app','OTP code does not match'); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	$user->userId = $model['userId'];
	$user->name = $model['name'];
	$user->userImage = $model['userImage'];
	$user->mobile_status = $model['mobile_status'];
	$user->facebookId = $model['facebookId'];
	?>
	<input type="hidden" value="<?php echo $model['userId'];?>" id="userId">
	<div class="row page-container profile-page-update">
		<div class="container exchange-property-container profile-vertical-tab-section">
			<?=$this->render('//user/sidebar',['user'=>$model])?> 
			<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
				<div id="edit-prof" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in">
					<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<span><?php echo Yii::t('app','Profile'); ?></span>
						<div class="change-pwd-btn pull-right col-xs-8 col-sm-3 col-md-3 col-lg-3 no-hor-padding"><a class="primary-bg-color txt-white-color regular-font border-radius-5 text-align-center" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('user/changepassword'); ?>" id="element1" ><?php echo Yii::t('app','Change Password'); ?></a></div> 
					</div>
					<div class="edit-profile-form col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="edit-profile-form">
						<?php $form = ActiveForm::begin(['id' => 'users-profile-form','options' => ['onsubmit' => 'return profileVal()']]); ?>					
						<div class="profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"><?php echo Yii::t('app','Name'); ?><span class="mandotory-field">*</span></div>	
						<div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
							<?= $form->field($model, 'name')->textInput(["id" => "Users_namee",'autofocus' => true,"maxlength" => 30])->label(false) ?>
							<div class="required" id="Users_namee_em_" style="display: block;"></div>
						</div>
						<div class="profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding" style="padding-top: 30px;"><?php echo Yii::t('app','Username'); ?><span class="mandotory-field">*</span></div>
						<div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
							<?= $form->field($model, 'username')->textInput(['autofocus' => true, 'readonly'=> true])->label(false) ?>
						</div>
						<div class="profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding" style="padding-top: 30px;"><?php echo Yii::t('app','Stripe Private Key'); ?><span class="mandotory-field">*</span></div>
						<div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
							<?= $form->field($model, 'stripeprivatekey')->textInput(['autofocus' => true])->label(false) ?>
						</div>
						<div class="profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding" style="padding-top: 30px;"><?php echo Yii::t('app','Stripe Public Key'); ?><span class="mandotory-field">*</span></div>
						<div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
							<?= $form->field($model, 'stripepublickey')->textInput(['autofocus' => true])->label(false) ?>
						</div>
						<div class="profile-label-verification col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
							<span><?php echo Yii::t('app','Verifications'); ?></span>
							<span class="question-circle" data-toggle="tooltip" title="<?php echo Yii::t('app','To be a verified seller, please add your mobile number, email address and connect with your facebook. Buyers will be more interested to talking to the verified users.');?>"></span></div>
							<div class="profile-email profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"><?php echo Yii::t('app','Email'); ?><span class="mandotory-field">*</span></div>
							<div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
								<?= $form->field($model, 'email')->textInput(['autofocus' => true,'readonly'=> true])->label(false) ?>
							</div>
							<!--Get location details -->
							<div class="profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"><?php echo Yii::t('app','Location'); ?><span class="mandotory-field">*</span></div>	
							<div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
								<?php 
                        	//Default code.
                        	//$form->field($model, 'geolocationDetails')->textInput(["id" => "geolocationDetails",'onchange'=>"return resetLatLong();",'autofocus' => true,"maxlength" => 30])->label(false) 
								if($model->city != "" || $model->state != "" || $model->country != "" ){
									$geolocationDetails = json_decode($model->geolocationDetails);
                         			//echo $geolocationDetails->place;die;
									?>
									<input type="text" id="geolocationDetails" name="Users[geolocationDetails]" value="<?= $geolocationDetails->place; ?>" onchange = "return resetLatLong();" />
								<?php  }
								else{?>
									<input type="text" id="geolocationDetails" name="Users[geolocationDetails]" value=""  onchange = "return resetLatLong();"/>
								<?php  } ?>
								<div class="required" id="geolocationDetails_em_" style="display: block;"></div>
								<input id="latitude"  type="hidden" name="Users[latitude]" value="<?= $geolocationDetails->latitude; ?>"> 
								<input id="longitude" type="hidden" name="Users[longitude]" value="<?= $geolocationDetails->longitude; ?>">
								<input id="Productscountry" type="hidden" name="Users[country]" value="<?= $model->country; ?>">
								<input id="Productstate" type="hidden" name="Users[state]" value="<?= $geolocationDetails->state; ?>">
								<input id="Productscity" type="hidden" name="Users[city]" value="<?= $geolocationDetails->city; ?>">
								<div class="errorMessage" id="User_location_em_"></div>
							</div>
							<!--End location details -->
							<div class="hor-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding mt-35"></div>
							<div class="profile-text-bold profile-text-label mcol-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"> <?php echo Yii::t('app','Phone number :'); ?></div>
							<div class="profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<?php echo Yii::t('app','Get verified your phone number to become the trustworthy seller'); ?>
							</div>
							<?php if($model->mobile_status != '1') { ?>
								<div class="profile-email-txt add-phone col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><a href="javascript:void(0)" id="add-phone" onclick="phone_btn_onclick();">
									<?php //echo "+";echo Yii::t('app','Add your mobile number'); ?></a>
									<div id="firebaseui-auth-container"></div>
								<?php } ?>
								<?php if($model->mobile_status == '1') {
									$mobile_verify = 'style="display:block;"';
								} else {
									$mobile_verify = 'style="display:none;"';
								}
								?>
								<div class="profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="mobile-verification" <?php echo $mobile_verify; ?>>
									<div class="profile-tab-tick-icon tick-icon-padding"><i class="tick-icon fa fa-check" aria-hidden="true"></i></div>
									<div class="verified-txt txt-pink-color">(<?php echo Yii::t('app','Your mobile has been verified'); ?> <span id="n_number">
										<?php if($model->sms_country_code != 0){
											// $mob_code =  explode($model->sms_country_code,$model->phone);
											echo $model->phone;
										}
										else
										{
											echo $model->phone;
										}
										?></span>)</div>
										<div class="change-txt"><a href="javascript:void(0)" id="add-phone" onclick="switchVisible_addphone();"><?php echo Yii::t('app','Change'); ?></a></div>
									</div>
									<input type="hidden" value="<?php echo $model['userId'];?>" id="userId">
									<div class="profile-mobile-details col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="profile-mobile-details">
										<!-- FACEBOOK SMS -->
										<?php 									
										$sitedetails = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();						
										$fb_appid = $sitedetails->fb_appid;
										$fb_secret = $sitedetails->fb_secret;
										?>
										<input type="hidden" name="fb_appid" id="fb_appid" value="<?php echo $fb_appid; ?>" >
										<input type="hidden" name="fb_secret" id="fb_secret" value="<?php echo $fb_secret; ?>">
										<input type="hidden" name="code" id="code">
										<input type="hidden" name="csrf_nonce" id="csrf_nonce">
										<div class="change-pwd-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-top:15px;">
											<div id="firebaseui-auth-container"></div>
											<!-- <a class="border-radius-5 primary-bg-color text-align-center" href="javascript:;" onclick="phone_btn_onclick();"><?php //echo Yii::t('app','Verify via sms'); ?></a> -->
										</div>							
									</div>          
									<div class="profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
										<div class="switch-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<div class="profile-text-bold profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"> <?php echo Yii::t('app','Phone number Visible :'); ?></div>
											<div class="switch col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<?php
												if(isset($model->phonevisible) && $model->phonevisible == "1" && $model->mobile_status == 1)
												{
													?>
													<input id="Users_phonevisible" class="cmn-toggle cmn-toggle-round" checked="checked" type="checkbox" name="Users[phonevisible]" value="1" >
													<label for="Users_phonevisible"></label>
													<?php
												}
												else
												{
													?>
													<input id="Users_phonevisible" class="cmn-toggle cmn-toggle-round" type="checkbox" name="Users[phonevisible]" value="1" >
													<label for="Users_phonevisible"></label>
													<?php
												}
												?>
											</div>
										</div>
									</div>
									<input type="hidden" id="verify_mobile_number" value="<?php echo $model->phone; ?>">
									<?php if(empty($model->facebookId)) {
										$fb_verify = '';
									} else 
									{
										$fb_verify = 'style="display:none;"';
									}
									?>
									<div class="hor-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
									<div class="profile-text-bold profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"><?php echo Yii::t('app','Facebook :'); ?> </div>
									<div class="profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<?php echo Yii::t('app','Let your facebook users know that you are available here upon verifying your facebook account.'); ?> 
										<?php use yii\authclient\widgets\AuthChoice; ?>
										<?php $authAuthChoice = AuthChoice::begin(['baseAuthUrl' => ['site/auth'], 'autoRender' => false]); ?>
										<ul>
											<?php foreach ($authAuthChoice->getClients() as $client): 
												if($client->name=="facebook") { ?>
													<div <?php echo $fb_verify; ?>>
														<li><?= Html::a(Yii::t('app','Verify your facebook account'), ['site/auth', 'authclient'=> $client->name ],['class'=>'txt-pink-color']) ?></li></div>
													<?php } ?>
												<?php endforeach; ?>
											</ul>
											<?php AuthChoice::end(); ?>
										</div>
										<?php if(!empty($model->facebookId)) {
											$facebook_verify = 'style="display:block;"';
										} else {
											$facebook_verify = 'style="display:none;"';
										}
										?>
										<div class="facebook-verification profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" <?php echo $facebook_verify; ?>>
											<div class="profile-tab-tick-icon tick-icon-padding"><i class="tick-icon fa fa-check" aria-hidden="true"></i></div>
											<div class="verified-txt txt-pink-color"><?php echo Yii::t('app','Your facebook account has been verified.'); ?></div>
										</div>
										<div class="facebook-verification-failure profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="display:none;">
											<div class="profile-tab-cancel-icon"></div>
											<div class="verified-txt" style="color:#ff0000;"><?php echo Yii::t('app','Something is wrong. Your facebook account is not verified.'); ?></div>
										</div>
										<div>
											<div class="hor-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
											<div class="prof-save-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<div class="change-pwd-btn col-xs-4 col-sm-2 col-md-2 col-lg-2 no-hor-padding">
													<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'edit-profile border-radius-5 primary-bg-color text-align-center', 'name' => 'login-button']) ?>
												</div>
											</div>
										</div>
									</div>
									<?php ActiveForm::end(); ?>
								</div>
							</div>
						</div>
					</div></div>
				</div>
			</div>
		</div>
	</div>
	<script>
		function switchVisible_addphone() {
			if (document.getElementById('add-phone')) {
				if (document.getElementById('add-phone').style.display == 'none') {
					document.getElementById('add-phone').style.display = 'block';
					document.getElementById('profile-mobile-details').style.display = 'none';
				}
				else {
					document.getElementById('add-phone').style.display = 'none';
					document.getElementById('profile-mobile-details').style.display = 'block';
					document.getElementById('mobile-error').style.display = 'none';
				}
			}
		}
	</script>
	<script>
		function on_submit() {
			$('#fileupload').submit();
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
	<script type="text/javascript">
		$(document).ready(function() {
	//alert('map works');
     /*var autocomplete = new google.maps.places.Autocomplete((document.getElementById('geolocationDetails')), {
        types: ['(geocode)']
    });*/
    var autocomplete = new google.maps.places.Autocomplete((document
    	.getElementById('geolocationDetails')), {
    	types : [ 'geocode' ]
    });
    autocomplete.addListener('place_changed', function() {
    	var place = autocomplete.getPlace();
    	var latitude = place.geometry.location.lat();
    	var longitude = place.geometry.location.lng();
    	$('#Productstate').val('');  
    	$('#Productscity').val('');  
    	var placeDetails = place.address_components;
    	console.log(placeDetails);
    	var count = placeDetails.length;
    	var country = "";
    	var state = "";
    	for (var i = count; i >= 1; i--) { 
    		if(placeDetails[i-1].types[0] == "country") { 
    			country = placeDetails[i-1].short_name;
    			fullcountry = placeDetails[i-1].long_name;
    			$('#shippingcountry').val(country);
    			$('#Productscountry').val(fullcountry); 
    		} else if(placeDetails[i-1].types[0] == "administrative_area_level_1") {
    			state = placeDetails[i-1].long_name; 
    			$('#Productstate').val(state);   
    		}   else if(placeDetails[i-1].types[0] == "administrative_area_level_2") {
    			city = placeDetails[i-1].long_name;
    			$('#Productscity').val(city); 
	         //alert(city); 
	     }  
	 }
	 $("#latitude").val(latitude);
	 $("#longitude").val(longitude);
	});
})
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>
<?php
$siteSettings = yii::$app->Myclass->getSitesettings();
if(!empty($siteSettings) && isset($siteSettings->googleapikey) && $siteSettings->googleapikey!="")
	$googleapikey = "&key=".$siteSettings->googleapikey;
else
	$googleapikey = "";
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapikey; ?>&libraries=places&callback=initMap&language=en" async defer></script>
<script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>
<script src="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/frontend/web/js/firebaseui.js"></script>
<link type="text/css" rel="stylesheet" href="<?=Yii::$app->getUrlManager()->getBaseUrl()?>/frontend/web/css/firebaseui.css" />
<script>
	var appid = document.getElementById("fb_appid").value;
  // Your web app's Firebase configuration
  var firebaseConfig = {
  	apiKey: appid,
	// authDomain: "joysalev3-2.firebaseapp.com",
    // databaseURL: "https://joysalev3-2.firebaseio.com",
    // projectId: "joysalev3-2",
    // storageBucket: "joysalev3-2.appspot.com",
    // messagingSenderId: "272807015790",
    //appId: "1:272807015790:web:d26be40de5e98dbc493d7f"
};
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  // Initialize the FirebaseUI Widget using Firebase.
  var ui = new firebaseui.auth.AuthUI(firebase.auth());
  var uiConfig = {
  	callbacks: {
  		signInSuccessWithAuthResult: function(authResult, redirectUrl) {
  			var phone_no = authResult['user']['phoneNumber'];
  			$.ajax({
  				type : 'POST',
  				url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/mobileverificationstatusfirebase',
  				data : {'phone_no': phone_no},
  				success : function(data) {
  					location.reload();
  				}
  			});   
  		},
  		uiShown: function() {
      // The widget is rendered.
	  // Hide the loader.
	  console.log('shown');
	  document.getElementById('loader').style.display = 'none';
	}
},
  // Will use popup for IDP Providers sign-in flow instead of the default, redirect.
  signInFlow: 'popup',
  signInSuccessUrl: "<?=Yii::$app->getUrlManager()->getBaseUrl()?>/",
  signInOptions: [
	// Leave the lines as is for the providers you want to offer your users.
	firebase.auth.GoogleAuthProvider.PROVIDER_ID,
	firebase.auth.PhoneAuthProvider.PROVIDER_ID
	],
  // Terms of service url.
  tosUrl: "<?=Yii::$app->getUrlManager()->getBaseUrl()?>/",
  // Privacy policy url.
  privacyPolicyUrl: "<?=Yii::$app->getUrlManager()->getBaseUrl()?>/",
};
ui.start('#firebaseui-auth-container', uiConfig);
document.getElementsByClassName("firebaseui-idp-text").innerHTML = "Paragraph changed!";
</script>
<style type="text/css">
	.tick-icon-padding
	{
		padding-bottom: 3px;
		padding-top: 3px;
	}
	.firebaseui-idp-google, .firebaseui-card-footer{
		display : none!important;
	}
	.firebaseui-container.mdl-card,.firebaseui-page-provider-sign-in {
		margin : 0!important;
	}
	.firebaseui-idp-phone, .firebaseui-idp-phone:hover, .mdl-button.firebaseui-idp-phone:active, .mdl-button.firebaseui-idp-phone:focus {
		background-color: #e64446;
	}
</style>
<script type="text/javascript">
	initMap();
</script>