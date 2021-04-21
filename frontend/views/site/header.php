<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\SignupForm;
use common\models\Sitesettings;
$baseUrl = Yii::$app->request->baseUrl;
$Loginmodel = new LoginForm();
$signupModel = new SignupForm(['scenario' => 'signup']);
$userModel = new PasswordResetRequestForm();
$path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
$path = $path1 . '/media' . '/';
$sitePaymentModes = yii::$app->Myclass->getSitePaymentModes();
$sitesetting = yii::$app->Myclass->getSitesettings();
if (isset($_GET['search'])) {
  $search = $_GET['search'];
} else {
  $search = "";
}

//new change for google 
$siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
$googleid = '';
if (isset($siteSettings->socialLoginDetails)) {
  $socialLoginSettings = json_decode($siteSettings->socialLoginDetails, true);
  if (isset($socialLoginSettings['google']['appid'])) {
    $googleid = $socialLoginSettings['google']['appid'];
  }
  
}
?>

<div class="classified-header primary-bg-color">
<div class="container-fluid">
<div class="row">
<div class="classified-header-bar col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="header-left col-xs-6 col-sm-5 col-md-3 col-lg-3 d-flex no-hor-left">

        <div class="sidebarToggler" data-pushbar-target="left"></div>
        <div class="classified-logo">
          <?php $logo = yii::$app->Myclass->getLogo(); ?>
          <?= Html::a(Html::img($path . 'logo/' . $logo), Yii::$app->homeUrl); ?>
        </div>
      <div class="classified-header-nav dropdown">
            <a class="sticky-header-menu-icon dropdown-toggle" data-toggle="dropdown" href="#">
              <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/nav.png" alt="Message">
            </a>
            <?php 
            $categorypriority = yii::$app->Myclass->getCategory();

            if (count($categorypriority) > 5) {
              $scrollbar = '';
              'height:205px; overflow-y:scroll;';
            } else {
              $scrollbar = '';
            }
            ?>
            <ul id="dropdown-block" class="sticky-header-dropdown dropdown-menu" style="<?php echo $scrollbar; ?>">
            <!-- Hot fixes desktop-->
          <li>
          <a class="sticky-header-dropdown-height bold dropdown-toggle classified-for-sale-sticky" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/allcategories'); ?>" style="background:url(<?php echo Yii::$app->urlManager->createAbsoluteUrl('admin/categories/resized/70/allcategories.png'); ?>) no-repeat scroll 10px 4px / 32px auto; " ><span><?php echo Yii::t('app', 'All Categories'); ?></span></a>
                        </li>
          <!-- Hot fixes desktop-->

              <?php foreach ($categorypriority as $key => $category) :
                if ($category != "empty") {
                $getcatdet = $category;
                $getcatimage = !empty($category) ? $category->image : "";
                $subCategory = yii::$app->Myclass->getSubCategory($category->categoryId);

                ?>
              <li>
                <a class="sticky-header-dropdown-height bold dropdown-toggle classified-for-sale-sticky"
                  href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/' . $getcatdet->slug); ?>"
                   style="background:url(<?php echo Yii::$app->urlManagerBackEnd->baseUrl . '/uploads/' . $getcatimage; ?>) no-repeat scroll 10px 4px / 32px auto; ">
                  <span><?php echo Yii::t('app', $getcatdet->name); ?></span>
                </a>
              </li>
              <?php 
            }
            endforeach; ?>
            </ul>
          </div>

        </div>
       
                <div   <?php if (!Yii::$app->user->isGuest) { ?> class="full-vheader col-md-5 col-lg-5 "  <?php                                                                     } else { ?>  class="full-vheader col-md-5 col-lg-5 " <?php  } ?>>
   
<div class="classified-search-bar col-md-12 col-lg-12 no-hor-padding">
<form role="form" onsubmit="return dosearch();" class="searchform navbar-form- navbar-left- search-form" style="padding-left: 0;"
action="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category'); ?>" method="get"> 
    <div class="col-md-6 col-lg-6 no-hor-padding">
    <div class="ui-widget topsearch-locatn">
                <input id="searchval" onkeyup="ajaxSearch(this,event);" maxlength="60" placeholder="<?php echo Yii::t('app', 'Search products'); ?>" class="tags classified-search-icon form-control input-search sign" value="<?php echo $search; ?>" name="search" type="text">
              </div> 
    </div>
    </form>
    <div class="col-md-6 col-lg-6 no-hor-padding">
        <div class="hme-top-location map-input-section col-md-10 col-lg-10 no-hor-padding">
            <div class="map-input-box">
                <input id="pac-input" placeholder="<?= Yii::t('app', 'World wide') ?>" value="<?php echo Yii::$app->session['place']; ?>"  class="controls" autocomplete="off" type="text">
                <!--  onKeyup="if(event.keyCode==13) {mapinitialize2();}" -->
            

            </div>
         </div>
        <div class="col-md-2 col-lg-2 no-hor-padding"><a href="javascript:void(0);">
            <div class="search-go" onclick="return gotogetLocationData();"><?= Yii::t('app', 'Go') ?></div></a></div>
    </div>

</div>
</div>

<script>
// Get the input field
var input = document.getElementById("searchval");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Cancel the default action, if needed
//  event.preventDefault();
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    console.log("hi");
   //  return gotogetLocationData();
  }
});


</script>
<?php 
if (!Yii::$app->user->isGuest) { ?>
  <div  class="classified-login-user-nav col-sm-6 col-md-4 col-lg-4 no-hor-left pull-right no-hor-right">
<?php 
} else { ?>
<div  class="classified-login-user-nav col-sm-6 col-md-4 col-lg-4 pull-right no-hor-left">
<?php 
}
?>



<?php if (!Yii::$app->user->isGuest) { ?>
<ul class="navbar-nav">
<?php
$userImage = yii::$app->Myclass->getUserDetailss(Yii::$app->user->id);
if (!empty($userImage->userImage)) {
  $userimg = $path1 . '/profile/' . $userImage->userImage;
} else {
  $userimg = $path . 'logo/' . yii::$app->Myclass->getDefaultUser();
}
?>

<li class="classified-header-message">

 <?= Html::a(Html::img(Yii::$app->urlManagerfrontEnd->baseUrl . '/images/design/message.png'), ['message/index'], ['class' => 'head-tooltip tooltip tooltip-bottom', 'data-tooltip' => Yii::t('app', 'Message')]); ?>
 <?php
$messageCount = yii::$app->Myclass->getMessageCount(Yii::$app->user->id); ?>
                <script>
                  var liveCount = <?php echo $messageCount; ?>;
                </script>
                <?php
                $messageStatus = "";
                if ($messageCount == 0) {
                  $messageStatus = "message-hide";
                }
                ?>
                <span class="message-counter message-count <?php echo $messageStatus; ?>"><?php echo $messageCount; ?></span>
</li>
<span class="classified-header-har-line"></span>
<li class="classified-header-message">
    <?= Html::a(Html::img(Yii::$app->urlManagerfrontEnd->baseUrl . '/images/notification.png'), ['user/notification'], ['class' => 'head-tooltip tooltip tooltip-bottom', 'data-tooltip' => Yii::t('app', 'Notification')]); ?>
    <?php $notificationCount = yii::$app->Myclass->getNotificationCount(Yii::$app->user->id);
    $notificationStatus = "";
    if ($notificationCount == 0 || Yii::$app->controller->action->id == 'notification') {
      $notificationStatus = "message-hide";
    }
    ?>
                    <span class="message-counter <?php echo $notificationStatus; ?>"><?php echo $notificationCount; ?></span>

</li>
<span class="classified-header-har-line"></span>
                                        <li class="dropdown classified-header-profile">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <span class="classified-header-profile-img img-responsive" style=" background: rgba(0, 0, 0, 0) url(<?php echo $userimg; ?>) no-repeat scroll 0 0 / cover ; background-position: center center; "></span>
        <span class="classified-header-down-arrow"></span>
      </a> <ul class="dropdown-menu dropdown-submenu">
        <li> <?= Html::a(Yii::t('app', 'Profile'), ['/user/profiles']) ?></li>  
        
  <?php
  if (isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == "1") {
    ?>     
        <li><?= Html::a(Yii::t('app', 'My Promotions'), ['user/promotions']) ?></li>
        <?php 
      } ?>
        <?php if ($sitePaymentModes['exchangePaymentMode'] == 1) { ?>
        <li><?= Html::a(Yii::t('app', 'My Exchanges'), ['user/exchanges', 'type' => 'incoming']) ?></li>
        <?php 
      } ?>
        <?php if ($sitePaymentModes['buynowPaymentMode'] == 1) { ?>
        <li><?= Html::a(Yii::t('app', 'My Orders & My Sales'), ['buynow/orders']) ?></li>
        <?php 
      } ?>
        <li class="logout"><?= Html::a(Yii::t('app', 'Logout'), ['site/logout']) ?></li>
      </ul>
    </li>
 <li class="classified-header-stuff border-radius-5">
 <?= Html::a(Yii::t('app', 'SELL'), ['products/create'], ['class' => 'classified-camera-icon']) ?></li>

</ul>
<div id="page-content-wrapper">
                <a class="col-xs-2 col-sm-1 col-md-1 no-hor-padding" href="#menu-toggle" id="menu-toggle"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/design/3-line.png'); ?>" alt="Menu"></a>
              </div>
 <?php 
} else { ?>
 
<ul class="navbar-nav pull-right">

<?php if ((Yii::$app->controller->action->id != 'login') && (Yii::$app->controller->action->id != 'signup') && (Yii::$app->controller->action->id != 'request-password-reset') && (Yii::$app->controller->action->id != 'socialLogin')) { ?>
<li class="classified-header-signup d-none d-xl-block">  <a href="#" data-toggle="modal" data-target="#login-modal"><?php echo Yii::t('app', 'Login'); ?></a></li>
<li class="classified-header-login d-none d-xl-block">    <a href="#" data-toggle="modal" data-target="#signup-modal" data-backdrop="static" data-keyboard="false" ><?php echo Yii::t('app', 'Sign up'); ?></a></li>
<?php 
} ?>
<li class="classified-header-stuff border-radius-5">
<?= Html::a(Yii::t('app', 'SELL'), ['products/create'], ['class' => 'classified-camera-icon']) ?></li> 
</ul>
<div id="page-content-wrapper">
                <a class="col-xs-2 col-sm-1 col-md-1 no-hor-padding" href="#menu-toggle" id="menu-toggle"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/design/3-line.png'); ?>" alt="Menu"></a>
              </div>
 <?php 
} ?>


</div>


</div>
</div>
</div>
</div>


<div class="modal fade" id="login-modal" role="dialog">
<div class="modal-dialog modal-dialog-width">
<div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<h2 class="login-header-text"><?php echo Yii::t('app', 'Login to'); ?>&nbsp;<?php echo yii::$app->Myclass->getSiteName(); ?></h2>
<button data-dismiss="modal" class="close login-close" type="button">×</button>
<p class="login-sub-header-text"><?php echo Yii::t('app', 'Signup or login to explore the great things available near you'); ?></p>
</div>

<div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

<div class="login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
<div class="login-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div class="login-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

  <?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'method' => 'post',
    'action' => ['site/ajax-login'],
    'enableAjaxValidation' => true,


  ]); ?>

<?= $form->field($Loginmodel, 'username')->textInput(['autofocus' => true, 'placeholder' => Yii::t('app', 'Enter your email address'), 'id' => 'LoginForm_username', 'class' => 'popup-input', ['inputOptions' => [
  'autocomplete' => 'off'
]]])->label(Yii::t('app', 'Username'))->label(false) ?>
<div class="required" id="LoginForm_username_em_" style="display: block;"></div>

<?= $form->field($Loginmodel, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Enter your password'), 'id' => 'LoginForm_password', 'class' => 'popup-input', ['inputOptions' => [
  'autocomplete' => 'off'
]]])->label(Yii::t('app', 'Password'))->label(false) ?>
<div class="required" id="LoginForm_password_em_" style="display: block;"></div>
 <input type="hidden" name="lo-submitt" id="lo-submitt">
<div class="remember-pwd col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
        <div class="checkbox checkbox-primary remember-me-checkbox ">
          <input type="checkbox" class="remember-me-checkbox cust_checkbox" name="rememberMe" >
          <label><?php echo Yii::t('app', 'Remember me'); ?></label>
        </div>
        <span class="remember-div">l</span>
   <a href="#" data-toggle="modal" data-target="#forgot-password-modal" data-dismiss="modal" class="forgot-pwd"><?php echo Yii::t('app', 'Forgot Password ?'); ?></a>


  </div>
 

<div class="form-group">
  <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding login-btn', 'id' => 'submit-btn', 'onclick' => 'return validsigninfrm()']) ?>
</div>



<?php ActiveForm::end(); ?>



</div>

 </div>
</div>
   <?php $lineMaring = "no-margin";

  $socialLogin = Yii::$app->Myclass->getsocialLoginDetails(); ?>
    <?php if ($socialLogin['facebook']['status'] == 'enable' || $socialLogin['google']['status'] == 'enable') { ?>
    <div class="login-div-line col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="left-div-line"></div>
      <div class="right-div-line"></div>
      <span class="login-or"><?php echo Yii::t('app', 'Social Login'); ?></span>
    </div>
    <div class="social-login col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
    <div class="social-login-center">
   
<?php $authAuthChoice = AuthChoice::begin([
  'baseAuthUrl' => ['site/auth']
]);
$client = $authAuthChoice->getClients();
?>

  <?php if ($socialLogin['facebook']['status'] == 'enable') { ?>
<div class="facebook-login">
        <a href='<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/site/auth?authclient=facebook'; ?>' title='Facebook'>
          <img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/design/facebook.png"); ?>" alt="Facebook">
        </a>
      </div>

      <?php 
    } ?>

<?php if ($socialLogin['google']['status'] == 'enable') { ?>
        <div id="customBtn" class="facebook-login">
                    <img src="<?php echo Yii::$app->getUrlManager()->createAbsoluteUrl("/images/design/google-plus.png"); ?>" alt="Google">
                  </div>
      <?php 
    } ?>
      </div>
    </div>
    </div>
    <?php $lineMaring = ""; ?>
    <?php 
  } ?>

    <div class="login-line-2 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding "></div>
<div class="new-signup col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

<span><?php echo Yii::t('app', 'Not a member yet ?'); ?></span><a class="signup-link txt-pink-color" data-dismiss="modal" data-toggle="modal" data-target="#signup-modal" href="#signup-modal"><?php echo Yii::t('app', 'click here'); ?></a>
</div>

</div>
</div>
</div>


<div class="modal fade" id="signup-modal" role="dialog">
<div class="modal-dialog modal-dialog-width">
<div class="signup-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div class="signup-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<h2 class="signup-header-text"><?php echo Yii::t('app', 'Signup'); ?></h2>
<button data-dismiss="modal" class="close signup-close" type="button">×</button>

</div>
<div class="sigup-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

<div class="signup-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
<div class="signup-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div class="signup-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

<?php $form = ActiveForm::begin([
  'id' => 'form-signup',
  'action' => ['site/ajaxsignup'],
  'enableAjaxValidation' => true,
]); ?>

  <?= $form->field($signupModel, 'name')->textInput(['autofocus' => true, 'placeholder' => Yii::t('app', 'Enter your name'), 'id' => 'Users_name'])->label(false); ?>
<div id="Users_name_em_" class="required"></div>

  <?= $form->field($signupModel, 'username', ['enableAjaxValidation' => true])->textInput(['autofocus' => true, 'placeholder' => Yii::t('app', 'Enter your username'), 'id' => 'Users_username'])->label(false); ?>
<div id="Users_username_em_" class="required"></div>

  <?= $form->field($signupModel, 'email', ['enableAjaxValidation' => true])->textInput(['placeholder' => Yii::t('app', 'Enter your email address')])->label(false); ?>
<div id="Users_email_em_" class="required"></div>

  <?= $form->field($signupModel, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Enter your Password'), 'id' => 'Users_password'])->label(false); ?>
<div id="Users_password_em_" class="required"></div>
 <input type="hidden" name="si-submitt" id="si-submitt">

  <?= $form->field($signupModel, 'password_repeat', ['enableAjaxValidation' => true])->passwordInput(['placeholder' => Yii::t('app', 'Enter confirm Password'), 'id' => 'Users_confirm_password'])->label(false); ?>
  <div id="Users_confirm_password_em_" class="required"></div>

               <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Sign up'), ['class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding login-btn', 'name' => 'submit', 'id' => 'submit', 'onclick' => 'aaa()']) ?>
                </div>

            <?php ActiveForm::end(); ?>


</div>
</div>
</div>
<script type="text/javascript">
  function aaa()
  {
     $("#si-submitt").attr("value","1");
     setTimeout(function() {
          $("#si-submitt").removeAttr("value");
                        }, 3000);
  }
</script>

 <?php $lineMaring = "no-margin";

$socialLogin = Yii::$app->Myclass->getsocialLoginDetails(); ?>
    <?php if ($socialLogin['facebook']['status'] == 'enable' || $socialLogin['google']['status'] == 'enable') { ?>
    <div class="login-div-line col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="left-div-line"></div>
      <div class="right-div-line"></div>
      <span class="login-or"><?php echo Yii::t('app', 'Social signup'); ?></span>
    </div>
    <div class="social-login col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
    <div class="social-login-center">
   
<?php $authAuthChoice = AuthChoice::begin([
  'baseAuthUrl' => ['site/auth']
]);
$client = $authAuthChoice->getClients();
?>

 <?php if ($socialLogin['facebook']['status'] == 'enable') { ?>
<div class="facebook-login">
        <a href='<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/site/auth?authclient=facebook'; ?>' title='Facebook'>
          <img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/design/facebook.png"); ?>" alt="Facebook">
        </a>
      </div>

      <?php 
    } ?>

      <?php if ($socialLogin['google']['status'] == 'enable') { ?>
        <div id="customBtnn" class="facebook-login">
                    <img src="<?php echo Yii::$app->getUrlManager()->createAbsoluteUrl("/images/design/google-plus.png"); ?>" alt="Google">
                  </div>
     <!-- <div class="googleplus-login">
        <a href='<?php //echo Yii::$app->getUrlManager()->getBaseUrl().'/site/auth?authclient=google'; ?>' title='Google'>
          <img src="<?php //echo Yii::$app->getUrlManager()->createAbsoluteUrl("/images/design/google-plus.png"); ?>" alt="Google">
        </a>
      </div> -->
      <?php 
    } ?>
      </div>
    </div>
    </div>
    <?php $lineMaring = ""; ?>
    <?php 
  } ?>


<div class="login-line-2 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding "></div>
<div class="user-login col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<span><?php echo Yii::t('app', 'Already a member?'); ?></span><a class="login-link txt-pink-color" href="#login-modal" data-dismiss="modal" data-toggle="modal" data-target="#login-modal"><?php echo Yii::t('app', 'login'); ?></a>
</div>

</div>
</div>
</div>



<div class="modal fade" id="forgot-password-modal" role="dialog">
<div class="modal-dialog modal-dialog-width">
<div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<h2 class="forgot-header-text"><?php echo Yii::t('app', 'Forgot Password'); ?></h2>
<button data-dismiss="modal" class="close login-close" type="button">×</button>
<p class="forgot-sub-header-text"><?php echo Yii::t('app', 'Enter your email address and we\'ll send you a link to reset your password.'); ?></p>
</div>

    <div class="forgot-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

        <div class="forgot-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
            <div class="forgot-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                <div class="forgot-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
               <?php $form = ActiveForm::begin([
                'id' => 'forgetpassword-form',
                'action' => ['site/request-password-reset/'], 'method' => 'post',
                'options' => ['onsubmit' => 'return validforgot()'],
              ]); ?>

      <?= $form->field($userModel, 'email')->textInput(['placeholder' => Yii::t('app', 'Enter your email address'), 'class' => 'forgetpasswords popup-input forget-input'])->label(false); ?>

                            <div class="errorMessage" id="Users_emails_em_"></div>



                <input class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding forgot-btn" style="margin-top:10px;" type="submit" name="yt3" value="<?php echo Yii::t('app', 'Reset Password') ?>" />                  </  <?php ActiveForm::end(); ?>                </div>
            </div>

        </div>
</div>
</div>
</div>
<?php
$lat = Yii::$app->session['latitude'];
$lon = Yii::$app->session['longitude'];
?>
<input id="map-latitude" class="map-latitude" type="hidden" value="<?php echo $lat; ?>">
<input id="map-longitude" class="map-longitude" type="hidden" value="<?php echo $lon; ?>">
 <input type="hidden" id="session_lang" value="<?= Yii::$app->language; ?>">
<script>
  var googleUser = {};
  function startApp() {
    gapi.load('auth2', function(){
      auth2 = gapi.auth2.init({
        client_id: '<?php echo $googleid; ?>',
        cookiepolicy: 'single_host_origin',
      });
      attachSignin(document.getElementById('customBtn'));
      attachSignin(document.getElementById('customBtnn'));
      <?php if ((Yii::$app->controller->action->id == 'login')) { ?>
      attachSignin(document.getElementById('customBttnn'));
    <?php } ?>
    <?php if ((Yii::$app->controller->action->id == 'signup')) { ?>
      attachSignin(document.getElementById('customBbtn'));
    <?php } ?>
    });
  };

  function attachSignin(element) {
   // console.log(element.id);
    auth2.attachClickHandler(element, {},
        function(googleUser) {
         var profile = googleUser.getBasicProfile();
        var id = profile.getId();
        var full_name=[];
        full_name.push({
          givenName:profile.getName()
        })
        var last_name = profile.getFamilyName();
        var first_name = profile.getGivenName();
        var image = [];
        image.push({
          url:profile.getImageUrl()
        })
        var email = profile.getEmail();

        var attributes = [];
        attributes.push({
          id:id,
          name:full_name[0],
          last_name:last_name,
          image:image[0],
          email:email,
          first_name:first_name,
          type:'google'
        });

       window.location = baseUrl+'/site/loginwithgoogle?attributes='+JSON.stringify(attributes[0]);

        }, function(error) {
        });
  }
  </script>
   <style type="text/css">  
    #customBtn:hover {
      cursor: pointer;
    }
    #customBtnn:hover {
      cursor: pointer;
    }
  </style>

  <script>startApp();</script>

  <script>
    $.ajaxSetup({
        data: <?= \yii\helpers\Json::encode([
                \yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
              ]) ?>
    });



    $("#Users_name").bind("keyup", function(e) {
       //on letter number
       if (e.which <= 90 && e.which >= 48)
       {
            $("#Users_username_em_").closest('div.row').removeClass('success');
            $("#Users_username_em_").closest('div.row').addClass('error');
            $("#Users_username_em_").show();
            $('#Users_username_em_').text(Yii.t('admin', 'Special Characters not allowed.'));
            return false;
       }
});



$(document).ready(function(){
//alert($('#session_lang').val());
    $('#Users_username').keypress(function (e) {
      //alert('ads');
var regex = new RegExp("^[a-zA-Z0-9]+$");
var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
if (regex.test(str)) {
return true;
}
e.preventDefault();
return false;
});
  });
</script>
