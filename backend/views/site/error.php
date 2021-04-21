<?php

use yii\helpers\Html;
$this->title = $name;
$this->context->layout = 'error';
?>
<div  class="site-error">
<div class="">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding text-center" style="margin-top:100px;">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="payment-decline-status-info-txt"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/oops.jpg");?>"></br><span style="color:red;"> <?php echo Yii::t('app','Oops...!'); ?></span>&nbsp;&nbsp;<?php echo Yii::t('app','Something went wrong.'); ?></div>
                                     <br>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20 text-center"><a class="btn btn-sm btn-danger" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/'); ?>"><?php echo Yii::t('app','Go to home'); ?></a></div>
								</div>
							</div>
						</div>
						</div>


