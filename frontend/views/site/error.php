<?php

use yii\helpers\Html;

$this->title = $name;
?>



<div  class="site-error">
						<div class="">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="payment-decline-status-info-txt"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/oops.jpg");?>"></br><span class="payment-red"> <?php echo Yii::t('app','Oops...!'); ?></span><?php echo Yii::t('app','Something went wrong.'); ?></div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20 text-center"><a class="payment-promote-btn login-btn" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Go to home'); ?></a></div>
								</div>
							</div>
						</div>
						</div>