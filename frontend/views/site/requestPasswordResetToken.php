<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
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
											<div class="signup-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding site-request-password-reset">




    <p><?=Yii::t('app','Please fill out your email. A link to reset password will be sent there')?>.</p>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true,'placeholder'=>Yii::t('app','Enter your email address')])->label(Yii::t('app','Email')) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app','Send'), ['class' => 'btn btn-primary']) ?>
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
