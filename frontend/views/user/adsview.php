<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Sitesettings;
use conquer\toastr\ToastrWidget;
//error_reporting(0);
?>
<?= Html::csrfMetaTags() ?>
<div id="page-container" class="container">
	<div class="row">
		<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<ol class="breadcrumb">
				<li><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/'); ?>"><?php echo Yii::t('app','Home'); ?></a></li>
				<li><a href="#"><?php echo Yii::t('app','Profile'); ?></a></li>
			</ol>
			<?php  if(Yii::$app->session->hasFlash('success')): ?>
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
		</div>
	</div>
	<div class="row page-container profile-page-update">
		<div class="container exchange-property-container profile-vertical-tab-section">
			<?=$this->render('//user/sidebar',['user'=>$user])?> 
			<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
				<div id="adus-second" class=" profile-tab-content tab-pane fade in active col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
					<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 textleft">
						<div class="advertise-us">
							<div><?php echo Yii::t('app','Advertise history'); ?></div>
							<div>
								<a href="<?=Yii::$app->urlManager->createAbsoluteUrl('user/advertise')?>" class="ad-fontsizer secondary-txt-color">
									<?php echo Yii::t('app','Back'); ?> </a>
								</div>
							</div>
						</div>
						<div id="products" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20">
								<div>
									<div class="upto secondary-txt-color">
										<?=yii::t('app','Website Banner')?>:
									</div>
									<div class="adtab-message primary-txt-color">
										<img src="<?= Yii::$app->urlManagerfrontEnd->createUrl('/media/banners/').'/'.$model->bannerimage;?>" alt="" class="img-responsive">
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20">
								<div>
									<div class="upto secondary-txt-color">
										<?=yii::t('app','App Banner')?>:
									</div>
									<div class="adtab-message primary-txt-color">
										<img src="<?= Yii::$app->urlManagerfrontEnd->createUrl('/media/banners/').'/'.$model->appbannerimage;?>" alt="" class="img-responsive">
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20">
								<div>
									<div class="upto secondary-txt-color">
										<?=yii::t('app','Paid Amount')?>:
									</div>
									<div class="adtab-message primary-txt-color">
										<?php
										if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
											echo '<div style="direction:ltr;text-align:right;"">'. yii::$app->Myclass->convertArabicFormattingCurrency($model['currency'],$model['totalCost']).'</div>'; 
										else{
											echo yii::$app->Myclass->convertFormattingCurrency($model['currency'],$model['totalCost']); 
			// echo '<span>' . yii::$app->Myclass->getCurrency($model->currency) . '</span> <span>' . $model->price . '</span>';
										} ?>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20">
								<div>
									<div class="upto secondary-txt-color">
										<?=yii::t('app','Up to')?>:
									</div>
									<div class="adtab-message primary-txt-color">
										<?=date("jS F, Y",strtotime($model['startdate']))?> to <?=date("jS F, Y",strtotime($model['enddate']))?>
									</div>
								</div>
							</div>
							<?php if($model['paidstatus'] != "0") { ?> 
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20">
									<div>
										<div class="upto secondary-txt-color">
											<?=yii::t('app','Transaction Id')?>:
										</div>
										<div class="adtab-message primary-txt-color">
											<?=$model['tranxId']?>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20">
									<div>
										<div class="upto secondary-txt-color">
											<?=yii::t('app','Banner Status')?>:
										</div>
										<div class="adtab-message primary-txt-color status-txt">
											<?php
											$now = date("Y-m-d");
											if(date_format(date_create($model['enddate']),"Y-m-d") < $now ) {
												echo yii::t('app','Expired'); ?>
											<?php } else {
												if($model['status'] == "0" && $model['paidstatus'] == "1") {
													echo yii::t('app','Pending');
												} else if($model['paidstatus'] == "1") { ?>
													<?= yii::t('app',ucfirst($model['status'])); ?>
												<?php } }?> 
											</div>
										</div>
									</div>
								<?php } ?>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20">
									<div>
										<div class="upto secondary-txt-color">
											<?=yii::t('app','Payment Status')?>:
										</div>
										<div class="adtab-message primary-txt-color status-txt">
											<?php if($model['paidstatus'] != "0") { ?>
												<?php if(!empty($model['trackPayment'])) {?> 
													<?= yii::t('app',ucfirst($model['trackPayment'])); ?>
												<?php } ?>
											<?php } else { 
												echo "Failed";
											} ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div></div>
			</div>
		</div>
	</div>
</div>