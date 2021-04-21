<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="content" style="min-height: 719px;">



<div class="slider container container-1 section_container">
			  <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					  <!-- Bottom to top-->
					  <div class="row product_align_cnt">
						<div class="display-flex modal-dialog modal-dialog-width">
							<div class="signup-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="signup-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<h2 class="signup-header-text"><?=Yii::t('app','Reset Password')?></h2>

								</div>
									<div class="sigup-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

										<div class="signup-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
											<div class="signup-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding site-reset-password">


    <p><?=Yii::t('app','Please choose your new password')?>:</p>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true,'placeholder'=>Yii::t('app','Enter your new password')])->label(false) ?>

                 <?= $form->field($model, 'confirmpassword')->passwordInput(['placeholder'=>Yii::t('app','Enter your confirm password')])->label(false) ?>

              
    <div class="change-pwd-btn col-xs-4 col-sm-2 col-md-2 col-lg-2 no-hor-padding">
<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'post-btn btnUpdate btn']) ?>
      </div>
    

            <?php ActiveForm::end(); ?>
            </div>
</div>
</div>
										</div>
			
					  </div>
					 
				</div>
			</div>
		</div>


</div>
