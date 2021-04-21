<?php
use yii\helpers\Html;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use conquer\toastr\ToastrWidget;
use frontend\models\PasswordResetRequestForm;


$userModel = new PasswordResetRequestForm();

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
 <!-- Bottom to top-->
 <div class="row product_align_cnt">




  <div class="display-flex modal-dialog modal-dialog-width">

   
   <div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
    <div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
      <h2 class="login-header-text"> <?php echo Yii::t('app','Login to'); ?>&nbsp;<?php echo yii::$app->Myclass->getSiteName(); ?></h2>
      <p class="login-sub-header-text"><?php echo Yii::t('app','Signup or login to explore the great things available near you'); ?></p>
      
    </div>
    
    
    <div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

    <div class="login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
     <div class="login-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
      
       <?php if(Yii::$app->session->hasFlash('success')): ?>
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
    <?php if(Yii::$app->session->hasFlash('warning')): ?>
     <?=ToastrWidget::widget(['type' => 'warning', 'message'=>Yii::$app->session->getFlash('warning'),
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
  <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

  <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder'=>Yii::t('app','Enter your email address')])->label(false); ?>

  <?= $form->field($model, 'password')->passwordInput(['placeholder'=>Yii::t('app','Enter your password')])->label(false); ?>



 <div class="remember-pwd col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
        <div class="checkbox checkbox-primary remember-me-checkbox ">
          <input type="checkbox" class="remember-me-checkbox cust_checkbox" name="rememberMe" >
          <label><?php echo Yii::t('app','Remember me'); ?></label>
        </div>
        <span class="remember-div">l</span>
    <a href="#" data-toggle="modal" data-target="#forgot-password-modal" data-dismiss="modal" class="forgot-pwd"><?php echo Yii::t('app','Forgot Password ?'); ?></a> 

  </div>

  <div class="form-group">
   <?= Html::submitButton(Yii::t('app','Login'), ['class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding login-btn', 'name' => 'login-button']) ?>
 </div>


 <?php ActiveForm::end(); ?>

 


</div>

</div>


<?php $lineMaring = "no-margin";

$socialLogin = Yii::$app->Myclass->getsocialLoginDetails(); ?>
<?php if($socialLogin['facebook']['status'] == 'enable' || $socialLogin['google']['status'] == 'enable'){ ?>
  <div class="login-div-line col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="left-div-line"></div>
    <div class="right-div-line"></div>
    <span class="login-or"><?php echo Yii::t('app','Social Login'); ?></span>
  </div>
  <div class="social-login col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
    <div class="social-login-center">
     
      <?php $authAuthChoice = AuthChoice::begin([
        'baseAuthUrl' => ['site/auth']
      ]);
      $client=$authAuthChoice->getClients();
      ?>

      <?php     if($socialLogin['facebook']['status'] == 'enable'){ ?>
        <div class="facebook-login">
        <a href='<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/site/auth?authclient=facebook'; ?>' title='Facebook'>
          <img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/design/facebook.png"); ?>" alt="Facebook">
        </a>
      </div>
     <?php } ?>

     <?php if($socialLogin['google']['status'] == 'enable'){ ?>

     <div id="customBttnn" class="facebook-login">
                    <img src="<?php echo Yii::$app->getUrlManager()->createAbsoluteUrl("/images/design/google-plus.png"); ?>" alt="Google">
                  </div>
   <?php } ?>
 </div>
</div>
</div>
<?php $lineMaring = ""; ?>
<?php } ?>

<div class="login-line-2 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
<div class="new-signup col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

 <span><?php echo Yii::t('app','Not a member yet ?'); ?></span>

 <?=Html::a(Yii::t('app','click here'), ['site/signup'], ['class' => 'signup-link txt-pink-color'])?></li>
 
 
</div>

</div>
</div>
</div>
<!-- end Bottom to top-->
</div>

<div class="modal fade" id="forgot-password-modal" role="dialog">
  <div class="modal-dialog modal-dialog-width">
    <div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
      <div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
        <h2 class="forgot-header-text">Forgot Password</h2>
        <button data-dismiss="modal" class="close login-close" type="button">×</button>
        <p class="forgot-sub-header-text">Enter your email address and we'll send you a link to reset your password.</p>
      </div>

      <div class="forgot-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

      <div class="forgot-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
        <div class="forgot-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
          <div class="forgot-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
          <?php $form = ActiveForm::begin(['id' => 'forgetpassword-form',
    'action'=> ['site/request-password-reset/'],'method' => 'post',
    'options' => ['onsubmit' => 'return validforgot()'],
    ]); ?>
      <?= $form->field($userModel, 'email')->textInput(['placeholder'=>Yii::t('app','Enter your email address'),'id'=>'Users_email','class' => 'forgetpasswords popup-input forget-input'])->label(false); ?>
   <div class="errorMessage" id="Users_emails_em_"></div>
   <input class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding forgot-btn" style="margin-top:10px;" type="submit" id="submit" name="yt3" value="Reset Password" onclick="enableButton2()" />        
   <?php ActiveForm::end(); ?>
    </div>
          </div>

        </div>



      </div>
    </div>
  </div>

  <script>
        function enableButton2() {
            document.getElementById("submit").disabled = true;
        }
    </script>

<style type="text/css">  
    #customBttnn:hover {
      cursor: pointer;
    }
  </style>

