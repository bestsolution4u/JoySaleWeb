<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Sitesettings;

?>
<div id="content" style="min-height: 802px;">


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
						<li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home'); ?></a></li>
						<li><a href="#"><?php echo Yii::t('app','Profile'); ?></a></li>
					 </ol>
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

echo $user->id = $model['id'];
$user->name = $model['name'];
$user->mobile_status = $model['mobile_status'];
$user->facebookId = $model['facebookId'];

?>
<input type="hidden" value="<?php echo $model['id'];?>" id="id">
<div class="row page-container profile-page-update">
	<div class="container exchange-property-container profile-vertical-tab-section">
	<?=$this->render('//user/sidebar',['user'=>$user])?> 

					<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
							<div id="edit-prof" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in">
							<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<span><?php echo Yii::t('app','Profile'); ?></span>
								<div class="change-pwd-btn pull-right col-xs-8 col-sm-3 col-md-3 col-lg-3 no-hor-padding"><a class="primary-bg-color txt-white-color regular-font border-radius-5 text-align-center" href="<?php // Yii::app()->createAbsoluteUrl('user/changepassword'); ?>" id="element1" ><?php echo Yii::t('app','Change Password'); ?></a></div>
							</div>


							<div class="edit-profile-form col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="edit-profile-form">
                            <?php $form = ActiveForm::begin(['id' => 'users-profile-form']); ?>
						
								<div class="profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"><?php echo Yii::t('app','Name'); ?><span class="mandotory-field">*</span></div>
								<div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
                                <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>'required']) ?>
                                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'required'=>'required']) ?>
                                <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'required'=>'required']) ?>
                                <?= $form->field($model, 'phonevisible')->checkbox(['class'=>'cmn-toggle cmn-toggle-round']); ?>

    	
								<div class="profile-label-verification col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"><span><?php echo Yii::t('app','Verifications'); ?></span><span class="question-circle" data-toggle="tooltip" title="<?php echo Yii::t('app','To be a verified seller, please add your mobile number, email address and connect with your facebook. Buyers will be more interested to talking to the verified users.');?>"></span></div>
								<div class="profile-email profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"><?php echo Yii::t('app','Email'); ?><span class="mandotory-field">*</span></div>

								<div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
								<input type="text" class="form-control" value="<?php echo $model->email;?>" readonly="true"/>
								</div>
								<div class="hor-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
								<div class="profile-text-bold profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"> <?php echo Yii::t('app','Phone number :'); ?></div>
								<div class="profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<?php echo Yii::t('app','Get verified your phone number to become the trustworthy seller'); ?>
								</div>
								<?php if($model->mobile_status != '1') { ?>
								<div class="profile-email-txt add-phone col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><a href="javascript:void(0)" id="add-phone" onclick="phone_btn_onclick();"><?php echo "+";echo Yii::t('app','Add your mobile number'); ?></a></div>
								<?php } ?>
								
								<?php if($model->mobile_status == '1') {
									$mobile_verify = 'style="display:block;"';
								} else {
									$mobile_verify = 'style="display:none;"';
								}
								?>
								<div class="profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="mobile-verification" <?php echo $mobile_verify; ?>>

										<div class="profile-tab-tick-icon"><i class="tick-icon fa fa-check" aria-hidden="true"></i></div>
										<div class="verified-txt txt-pink-color">(<?php echo Yii::t('app','Your mobile has been verified'); ?> <span id="n_number"><?php echo $model->phone; ?></span>)</div>
										<div class="change-txt"><a href="javascript:void(0)" id="add-phone" onclick="switchVisible_addphone();"><?php echo Yii::t('app','Change'); ?></a></div>

									</div>

    	<?php 
									 $sitedetails = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
										$fb_appid = $sitedetails->fb_appid;
										$fb_secret = $sitedetails->fb_secret;
									?>
									<input type="textbox" name="fb_appid" id="fb_appid" value="<?php echo $fb_appid; ?>" >
									<input type="textbox" name="fb_secret" id="fb_secret" value="<?php echo $fb_secret; ?>">

								
									
									<div class="change-pwd-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-top:15px;">
										<a class="border-radius-5 primary-bg-color text-align-center" href="javascript:;" onclick="phone_btn_onclick();"><?php echo Yii::t('app','Verify via sms'); ?></a>
                                    </div>
                                    

									
                                    <?php if(empty($model->facebookId)) {
									$fb_verify = '';
								} else {
									$fb_verify = 'style="display:none;"';
								}
								?>
								<div class="hor-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
								<div class="profile-text-bold profile-text-label col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding"><?php echo Yii::t('app','Facebook :'); ?> </div>
								<div class="profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									 <?php echo Yii::t('app','Let your facebook users know that you are available here upon verifying your facebook account.'); ?> <a href="javascript:;" onclick="return popitup('Facebook');" id="fb_verify" <?php echo $fb_verify; ?>><?php echo Yii::t('app','Verify your facebook account');echo "."; ?></a>

								</div>
								<?php if(!empty($model->facebookId)) {
									$facebook_verify = 'style="display:block;"';
								} else {
									$facebook_verify = 'style="display:none;"';
								}
								?>
								<div class="facebook-verification profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" <?php echo $facebook_verify; ?>>

									<div class="profile-tab-tick-icon"><i class="tick-icon fa fa-check" aria-hidden="true"></i></div>
									<div class="verified-txt txt-pink-color"><?php echo Yii::t('app','Your facebook account has been verified.'); ?></div>

								</div>
								<div class="facebook-verification-failure profile-email-txt profile-text-label col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="display:none;">

									<div class="profile-tab-cancel-icon"></div>
									<div class="verified-txt" style="color:#ff0000;"><?php echo Yii::t('app','Something is wrong. Your facebook account is not verified.'); ?></div>

								</div>


    <div>

    
    
                                <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

</div>
                                <?php ActiveForm::end(); ?>
								
							</div>

						</div>
	<!-- form -->



	</div>
	</div></div>
</div>
</div>
</div>
</div>
</div>
<form id="login_success" method="post" action="/login_success">
  <input id="csrf" type="hidden" name="csrf" />
  <input id="code" type="hidden" name="code" />
</form>

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

 <!-- <script src="https://sdk.accountkit.com/en_EN/sdk.js"></script> -->

<script type="text/javascript">
var appid = document.getElementById("fb_appid").value;
         alert(secret);
  // initialize Account Kit with CSRF protection
  AccountKit_OnInteractive = function(){
      var appid = document.getElementById("fb_appid").value;
          alert(secret);
    AccountKit.init(
      {
          
        appId:appid,         
        state:"91", 
        version:"v1.1"
      }
      //If your Account Kit configuration requires app_secret, you have to include ir above
    );
  };
  // login callback
  function loginCallback(response) {
    console.log(response);
    if (response.status === "PARTIALLY_AUTHENTICATED") {
        alert(response);
        
      var codes = document.getElementById("code").value = response.code;
      var nonce =  document.getElementById("csrf_nonce").value = response.state;
      alert(code);
      $.ajax({
        type : 'POST',
        url : 'http://localhost/joysale/joysale_website/user/mobileverificationStatus',
        data : {'code': codes, 'csrf_nonce': nonce},
        success : function(data) {
          //  alert(data);
            location.reload();
        }
        });
    
    

      //document.getElementById("my_form").submit();
    }
    else if (response.status === "NOT_AUTHENTICATED") {
      // handle authentication failure
      console.log("Authentication failure");
    }
    else if (response.status === "BAD_PARAMS") {
      // handle bad parameters
      console.log("Bad parameters");
    }
  }
  // phone form submission handler
  function phone_btn_onclick() {
    // you can add countryCode and phoneNumber to set values
    var secret = document.getElementById("fb_secret").value;
     var appid = document.getElementById("fb_appid").value;
          if(secret != '' && appid != '' ) {
//alert(secret);
        AccountKit.login('PHONE', {}, // will use default values if this is not specified
              loginCallback);
    }
    else
    {
        $('.mobile-error').css('display','inline-block');
        $('.mobile-error').html(yii.t('app','Please provide the correct app id and secret id'));
        return false;
    }

    
  }
  
</script>

