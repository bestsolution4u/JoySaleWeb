<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
 use yii\helpers\Json;
 use yii\widgets\ActiveForm;
 use common\models\Followers; ?>
<?php
$action = Yii::$app->controller->action->id;
$sitesetting = yii::$app->Myclass->getSitesettings();
$paymentmode = Json::decode($sitesetting->sitepaymentmodes,true);
$path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
$path = $path1.'/media'.'/';
$reviewcount = yii::$app->Myclass->getReviewcount($user->userId);
?>
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
<ul style="padding: 0;" class="profile-vertical-tab-container nav nav-tabs col-xs-12 col-sm-3 col-md-3 col-lg-3">
  <li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		<div class="profile-icon-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<div class="profile-page-profile-icon" style="background-image:url('<?php if(!empty($user->userImage)) {  echo Yii::$app->urlManager->createAbsoluteUrl('profile/'.$user->userImage); }else{ echo $path.'logo/'.yii::$app->Myclass->getDefaultUser(); }?>');">
   		<?php
			if(Yii::$app->controller->id == "user" && Yii::$app->controller->action->id == "editprofile") {
				if(Yii::$app->user->id == $user->userId) {
			$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],
			'id'=>'fileupload', 'action'=>'uploadfile']) ?>
			<div class="camera-edit-icon"></div>
				<?php $allowedExtensions = ".jpg,.jpeg,.png,.JPG,.JPEG,.PNG";
 //,'accept' => "'$allowedExtensions'"
echo $form->field($user, 'userImage')->fileInput(['class' => 'form-control file-upload','onchange'=>'on_submit()','id' => 'profilefile'])->label(false);
							// $form->field($user,'userImage',array('class' => 'form-control file-upload','onchange'=>'on_submit()','accept' => "'$allowedExtensions'")); ?>
							<div class="col-xs-4"  style="display:none;"><?php echo Html::submitButton(Yii::t('app','Save'),array('class'=>'edit-profile')); ?>

<?php ActiveForm::end(); }
			} ?>
</div></div>


		<div class="profile-details col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<div class="profile-user-name primary-txt-color text-align-center"><?php echo ucfirst($user->name);?></div>
			<div class="profile-country-name txt-pink-color text-align-center"><?php if($paymentmode['buynowPaymentMode']==0) echo $user->username; ?></div>
			<?php if($paymentmode['buynowPaymentMode'] == 1){

				if ($user->averageRating > 0) {
					
			 ?>
			<a class="product-review text-align-center" href="javascript:void(0);">
				<div class="write-review">
					<?php
					$averageRating = $user->averageRating;
					//for($i = 0; $i < $averageRating; $i++){
					//	echo '<span class="g-color fa fa-star"></span>';
					//}
					for($i=1;$i<=5;$i++)
 				{	
 					if($i<=$averageRating)
 						echo '<span class="g-color fa fa-star"></span>';
 				    else
 					echo '<span class="gray fa fa-star"></span>';
 				}
					?>
					<span class="review-count">(<?php echo $reviewcount; ?>)</span>
				</div>
			</a>
			<?php } else {  ?>
				<a class="product-review text-align-center" href="javascript:void(0);">
				<div class="write-review">
					<?php
					$averageRating = $user->averageRating;
					 	for($i = 0; $i < 5; $i++){
							echo '<span class="gray fa fa-star"></span>';
					 	}
					
					?>
						<span class="review-count">(0)</span>
				</div>
			</a>
			<?php } } ?>

			<?php
			if(isset($user->phonevisible) && $user->phonevisible == "1" && $user->phone!=0 && $user->mobile_status==1)
			{
			//	echo '<div class="phone-number primary-txt-color text-align-center"><span class="country-code">'.$user->country_code.'</span>'.$user->phone.'</div>
			//';
									 if($user->sms_country_code != 0){
											$mob_code =  explode($user->sms_country_code,$user->phone);
											echo '<div class="phone-number primary-txt-color text-align-center"><span class="country-code">+'.$user->sms_country_code.' '.$mob_code[1].'</div>';
			
										 
										}
										 else
										 {
										 	echo '<div class="phone-number primary-txt-color text-align-center"><span class="country-code">'.$user->phone.'</div>';
										 }
									
			}
			?>
			<div class="seller-verification col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				 <div class="text-align-center">
					<?php  if($user->mobile_status == '1') { ?>
						<div class="mobile-verification" id="verified" data-toggle="tooltip" title='<?php echo Yii::t('app','Mobile number Verified!')?>'></div>
					<?php } else { ?>
						<div class="mobile-verification" data-toggle="tooltip" title='<?php echo Yii::t('app','Mobile number is not Verified!')?>'></div>
					<?php } ?>
					<?php if(!empty($user->facebookId)) { ?>
						<div class="fb-verification" id="verified" data-toggle="tooltip" title='<?php echo Yii::t('app','Facebook account Verified!')?>'></div>
					<?php } else { ?>
						<div class="fb-verification" data-toggle="tooltip" title='<?php echo Yii::t('app','Facebook account is not Verified!')?>'></div>
					<?php } ?>
					<div class="mail-verification" id="verified" data-toggle="tooltip" title='<?php echo Yii::t('app','Mail Id Verified!')?>'></div>
				</div>  
			</div>

</div>
		<?php $subActive = '';
			if(Yii::$app->controller->action->id == 'profiles')
			{
			$subActive = 'active';
			$subclick = "return false";
			}
			else
			{
			$subclick = "return true"; 
			$subActive = ''; } ?>
			<?php if(Yii::$app->controller->action->id == 'liked')
			$lactive = 'active';						
			else			
			$lactive = ''; ?>
			<?php if(Yii::$app->controller->action->id == 'review')
			{
			$ractive = 'active';
			$reviewClick="return false";
			}
			else
			{
			$ractive = ''; 
			$reviewClick="return true";
			}?>
			<?php if(Yii::$app->controller->action->id == 'follower')
			$factive = 'active';
			else
			$factive = ''; ?>
			<?php if(Yii::$app->controller->action->id == 'following')
			$f1active = 'active';
			else
			$f1active = ''; ?>
		   <?php if(Yii::$app->controller->action->id == 'liked' || Yii::$app->controller->action->id == 'follower' || Yii::$app->controller->action->id == 'following' ) 
		   $click="return false";
		   else
		   $click="return true";
		   ?>

			<?php if(Yii::$app->controller->action->id == 'notification')
			{
			$notactive = 'active';
			$onclick="return false";
			}
			else
			{
			$notactive = '';
			$oraction = '';
			$onclick="return true"; 
			}?>
			<?php if(Yii::$app->controller->action->id == 'orders' || Yii::$app->controller->action->id == 'sales' || Yii::$app->controller->action->id == 'vieworders' || Yii::$app->controller->action->id == 'viewsales')
			{
			$oraction = 'active';
			$orclick="return false";
			}
			else
			{
			$orclick="return true";
			$oraction = '';
			}


		if(Yii::$app->controller->action->id == 'expiredpromotions' || Yii::$app->controller->action->id == 'promotions' || Yii::$app->controller->action->id == 'advertisepromotions')
		{
			$promactive = 'active';
			$proclick="return false";

		}
			else
			{
			$promactive = '';
			$proclick="return true";
			}
			if(Yii::$app->controller->action->id == 'index' || Yii::$app->controller->action->id == 'view')
			$exactive = 'active';
		
			else
			$exactive = '';
			if(Yii::$app->controller->action->id == 'user' || Yii::$app->controller->action->id == 'editprofile' || Yii::$app->controller->action->id == 'changepassword')
			$edactive = 'active';
			else
			$edactive = '';
		if(Yii::$app->controller->action->id == 'user' || Yii::$app->controller->action->id == 'exchanges')
			{
			$excactive = 'active';
			$exclick="return false"; 
			}
			else
			{
			$excactive = '';
			$exclick="return true"; 
			}
			 ?>
<?php
			if(Yii::$app->controller->action->id == 'editprofile')
			{
			$editclick = "return false";
			}
			else
			{
			$editclick = "return true";
			} ?>
            <?php
			if(Yii::$app->controller->action->id == 'shippingaddress')
			{
			$adclick = "return false";
			}
			else
			{
			$adclick = "return true";
			} ?>

		  <?php
			if(Yii::$app->controller->action->id == 'advertise'||Yii::$app->controller->action->id == 'adsview' )
			{
			$advertiseactive = 'active';
			$advertiseclick = "return false";
			}
			else
			{
			$advertiseactive = '';
			$advertiseclick = "return true";
			} ?>

			<?php if(Yii::$app->user->id == $user->userId) { ?>

			<?php }else { //var_dump($followerIds);
				echo '<a class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 15px; background: transparent;">';

				
			if(!empty(Yii::$app->user->id) && $user->userstatus == 1) {


				$follower = Followers::find()->where(['userId'=>Yii::$app->user->id,'follow_userId'=>$user->userId])->one();
			if (count($follower)<=0) { ?>
			<div class="btn-follow primary-bg-color btn-chat-with-seller col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<div class="chat-with-seller-text col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding " id = "follow<?php echo $user->userId; ?>" onclick="getfollows(<?php echo $user->userId; ?>)"><span><?php echo Yii::t('app','Follow'); ?></span>
			</div> </div>
			<?php }else { ?>
			<div class="btn-follow primary-bg-color btn-chat-with-seller col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<div class="chat-with-seller-text col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id = "follow<?php echo $user->userId; ?>" onclick="deletefollows(<?php echo $user->userId; ?>)"><span><?php echo Yii::t('app','Following'); ?></span>
			</div> </div>
			<?php }
				}
				echo '</a>';
			} ?>




  </li>
  <?php   if(Yii::$app->user->id == $user->userId){ ?>
	    <li class="<?php echo $subActive; ?> col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	  <?php echo Html::a(Yii::t('app','My Listing'),Yii::$app->urlManager->createAbsoluteUrl(['user/profiles','id'=>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))]),array('class' => 'btn-category '.$subActive,'onclick'=>$subclick));  ?>
	  </li>

  <?php } else { ?>
	<li class="<?php echo $subActive; ?> col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	  <?php echo Html::a(Yii::t('app','Listing'),Yii::$app->urlManager->createAbsoluteUrl(['user/profiles','id'=>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))]),array('class' => 'btn-category '.$subActive));  ?>
	  </li>
  <?php } ?>
  <?php  if(Yii::$app->user->id == $user->userId){ ?>
   <li class="<?php echo $edactive; ?> col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $action == 'profile' ? "active" : ""; ?>">
   <?=Html::a(Yii::t('app','Edit Profile'), ['/user/editprofile/'],array('onclick'=>$editclick))?>

  </li> 
  <?php } ?>
   <li class="<?php echo $lactive.$factive.$f1active; ?> col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	<?php echo Html::a(Yii::t('app','Recent Activities'),Yii::$app->urlManager->createAbsoluteUrl(['user/liked','id'=>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))]),array('class' => ''.$lactive,'onclick'=>$click));  ?>
  </li>

	<?php
	if(isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == "1")
	{
	?>
	<?php  if(Yii::$app->user->id == $user->userId){ ?>
	<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $promactive; ?>">
		<a  href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('user/promotions'); ?>" onclick="<?=$proclick?>">
			<?php echo Yii::t('app','Promotions'); ?><span class="ad-label pull-right"><?php echo Yii::t('app','AD'); ?></span>
		</a>
  	</li>
  	<?php } ?>
 <?php } ?>
 	<?php $sitePaymentModes = yii::$app->Myclass->getSitePaymentModes(); if(Yii::$app->user->id == $user->userId && $sitePaymentModes['exchangePaymentMode'] == "1"){ ?>
	  <li class="<?php echo $excactive; ?> col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $exactive; ?>">
		<?php echo Html::a(Yii::t('app','My Exchange'),Yii::$app->urlManager->createAbsoluteUrl('user/exchanges?type=incoming'),array('class' => ''.$exactive,'onclick'=>$exclick));  ?>
	  </li>
	<?php } ?>
 <?php if(Yii::$app->user->id == $user->userId && $paymentmode['buynowPaymentMode'] == 1){ ?>
   <li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $oraction; ?>">
  	<?php echo Html::a(Yii::t('app','My Orders & My Sales'),Yii::$app->urlManager->createAbsoluteUrl('orders'),array('class' => ''.$notactive,'onclick'=>$orclick));  ?>
  </li> 
 <?php } ?>
  <?php  if(Yii::$app->user->id == $user->userId && $paymentmode['buynowPaymentMode'] == 1){ ?>
  <li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $action == 'shippingaddress' ? "active" : ""; ?>">
  	<?php echo Html::a(Yii::t('app','Address Book'),Yii::$app->urlManager->createAbsoluteUrl('shippingaddress'),array('class' => ''.$notactive,'onclick'=>$adclick));  ?>
  </li> 
 <?php } ?>
 <?php  if(Yii::$app->user->id == $user->userId){ ?>
  <li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $action == 'notification' ? "active" : ""; ?>">
  	<?php echo Html::a(Yii::t('app','Notifications'),Yii::$app->urlManager->createAbsoluteUrl('user/notification'),array('class' => ''.$notactive, 'onclick'=>$onclick));  ?>
  </li>
 <?php } ?>
 <?php  if(Yii::$app->user->id == $user->userId && $paymentmode['buynowPaymentMode'] == 1){ ?>
   <li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $action == 'review' ? "active" : ""; ?>">
  <?php echo Html::a(Yii::t('app','Rating & Review'),Yii::$app->urlManager->createAbsoluteUrl('buynow/review'.'/'.yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))),array('class' => 'btn-category '.$subActive,'onclick'=>$reviewClick));  ?>
  </li> 
<?php } elseif($paymentmode['buynowPaymentMode'] == 1) { ?>
 <li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $action == 'review' ? "active" : ""; ?>">
  <?php echo Html::a(Yii::t('app','Rating & Review'),Yii::$app->urlManager->createAbsoluteUrl(['buynow/review'.'/'.yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))]),array('class' => 'btn-category '.$subActive,'onclick'=>$reviewClick));  ?>
  </li> 
<?php } ?>

  <?php  if((Yii::$app->user->id == $user->userId) && $sitesetting->paidbannerstatus != 0) { ?>

<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?=$advertiseactive ?>">
<?=Html::a(Yii::t('app','Banner Ads history'), ['/user/advertise/'],array('onclick'=>$advertiseclick))?>

</li> 
<?php } ?>
</ul>



<script>
function on_submit() {
	
	$('#fileupload').submit();
}


</script>

