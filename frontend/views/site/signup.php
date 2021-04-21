<?php
use yii\helpers\Html;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
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
									<h2 class="signup-header-text"><?php echo Yii::t('app','Sign Up'); ?></h2>

								</div>
									<div class="sigup-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

										<div class="signup-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
											<div class="signup-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

                                              <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
														<div class="signup-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                                                        <?= $form->field($model, 'name')->textInput(['placeholder'=>Yii::t('app','Enter your Name')])->label(false); ?>

                                                        <?= $form->field($model, 'username')->textInput(['placeholder'=>Yii::t('app','Enter your Username')])->label(false); ?>
                                                        <?= $form->field($model, 'email')->textInput(['placeholder'=>Yii::t('app','Enter your email address')])->label(false); ?>
                                                        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>Yii::t('app','Enter your Password')])->label(false); ?>
														<?= $form->field($model, 'password_repeat')->passwordInput(['placeholder'=>Yii::t('app','Enter confirm Password')])->label(false); ?>

												</div>
                                                 <?= Html::submitButton(Yii::t('app','Sign Up'), ['class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding login-btn', 'name' => 'signup-button']) ?>

														
                                                <?php ActiveForm::end(); ?>
                                                </div>
										</div>
				<?php $lineMaring = "no-margin";

    $socialLogin = Yii::$app->Myclass->getsocialLoginDetails(); ?>
    <?php if($socialLogin['facebook']['status'] == 'enable' || $socialLogin['google']['status'] == 'enable'){ ?>
    <div class="login-div-line col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="left-div-line"></div>
      <div class="right-div-line"></div>
      <span class="login-or"><?php echo Yii::t('app','Social signup'); ?></span>
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

     <div id="customBbtn" class="facebook-login">
                    <img src="<?php echo Yii::$app->getUrlManager()->createAbsoluteUrl("/images/design/google-plus.png"); ?>" alt="Google">
                  </div>
   <?php } ?>
      </div>
    </div>
    </div>
    <?php $lineMaring = ""; ?>
    <?php } ?>

									
													<div class="login-line-2 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
														<div class="user-login col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<span><?php echo Yii::t('app','Already a member?'); ?></span><?=Html::a(Yii::t('app','Login'), ['site/login'],['class'=>'login-link txt-pink-color'])?>
											
														</div>

							</div>
						</div>
					  </div>
					
				</div>
			</div>
		</div>


</div>
<style type="text/css">  
    #customBbtn:hover {
      cursor: pointer;
    }
  </style>

