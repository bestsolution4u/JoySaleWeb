<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Sitesettings;
use conquer\toastr\ToastrWidget;
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

<div id="page-container" class="container">
<div class="row">
				<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
					 <ol class="breadcrumb">
						<li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home'); ?></a></li>
						<li><a href="#"><?php echo Yii::t('app','Profile'); ?></a></li>
					 </ol>
				</div>

	
			</div>

<div class="row page-container profile-page-update">
	<div class="container exchange-property-container profile-vertical-tab-section">
		<?=$this->render('//user/sidebar',['user'=>$user])?> 

					<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
							<div id="edit-prof" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in">
							<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<span><?php echo Yii::t('app','Change Password'); ?></span>
								<div class="change-pwd-btn pull-right col-xs-8 col-sm-3 col-md-3 col-lg-3 no-hor-padding"><a class="regular-font border-radius-5 primary-bg-color text-align-center" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('user/editprofile'); ?>" id="element1" ><?php echo Yii::t('app','Back'); ?></a></div>
						
							</div>
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

							<div class="edit-profile-form col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="edit-profile-form">

			                   <?php $form = ActiveForm::begin(['id' => 'users-profile-form']); ?>
						
                        <div class="profile-input-fields col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
                        <?= $form->field($model, 'oldpass')->passwordInput(['autofocus' => true,'placeholder' => 'Enter current Password'])->label(false) ?>
                        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true,'placeholder' => 'Enter New Password'])->label(false) ?>
                        <?= $form->field($model, 'confirm_password')->passwordInput(['autofocus' => true,'placeholder' => 'Enter Confirm Password'])->label(false) ?>
            

 <div class="prof-save-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
    <div class="change-pwd-btn col-xs-4 col-sm-2 col-md-2 col-lg-2 no-hor-padding">
<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'post-btn btnUpdate btn']) ?>
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

