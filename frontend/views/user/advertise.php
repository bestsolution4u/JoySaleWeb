

<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Sitesettings;
use conquer\toastr\ToastrWidget;
error_reporting(0);

$settings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

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
					]);
				?>
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
				<div id="edit-prof" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in">
					<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                  <span><?php echo Yii::t('app','Advertise history'); ?></span>	

                 	<?php if($settings->paidbannerstatus==1){?>
                 		<div class="advt-btn change-pwd-btn pull-right col-xs-8 col-sm-3 col-md-3 col-lg-3 no-hor-padding">
                 			<a class="primary-bg-color txt-white-color regular-font border-radius-5 text-align-center" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/addbanner'); ?>" id="element1" ><?php echo Yii::t('app','Advertise with us'); ?></a>
                 		</div> 
             		<?php }?>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="edit-profile-form">
						<div class="notification-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<!-- start code -->
							<?php if(count($models)==0) { ?>								
								<div class="payment-decline-status-info-txt decline-center">
										<img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/empty-tap.jpg'); ?>"><<br>Promote your shop using our banner advertisement to reach more buyers.
								</div>
							<?php } ?>	
                
                     <?php foreach($models as $model) { ?>
								<div class="notification-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<?php
									$now = date("Y-m-d");
								if(date_format(date_create($model['enddate']),"Y-m-d") < $now ) { ?>
										<h6><span class="badge badge-danger pull-right"><?=Yii::t('app','Expired')?></span></h6>   
									<?php }  else{
								 if($model['status']=="approved" && $model['paidstatus']=="1") { ?>
										<h6><span class="badge badge-success pull-right"><?=Yii::t('app','Approved')?></span></h6>
									<?php } else if($model['status']=="cancelled" && $model['paidstatus']=="1") { ?>	
										<h6><span class="badge badge-danger pull-right"><?=Yii::t('app','Cancelled')?></span></h6>     
									<?php } else if($model['status']=="0" && $model['paidstatus']=="1") { ?> 
										<h6><span class="badge badge-info pull-right"><?=Yii::t('app','Pending')?></span></h6>
									<?php } else if($model['status']=="0" && $model['paidstatus']=="0") { ?>
										<h6><span class="badge badge-danger pull-right"><?=Yii::t('app','Failed')?></span></h6>   
									<?php }
								}
									  ?>

									<div class="ad-cnt">
										<div class="adtab-message">
											<a  title="Click here to view details"   href="<?=Yii::$app->urlManager->createAbsoluteUrl('user/adsview/').'/'.yii::$app->Myclass->safe_b64encode($model->id)?>"  class="primary-txt-color">
											<?=date("jS F, Y",strtotime($model['startdate']))?> to <?=date("jS F, Y",strtotime($model['enddate']))?> </a>
										</div>
										<span class="txt-pink-color">
										

																			<?php
											if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar')
			 echo '<div style="direction:ltr; text-align:right">'.yii::$app->Myclass->convertArabicFormattingCurrency($model['currency'],$model['totalCost']).'</div>'; 
		else{
			echo yii::$app->Myclass->convertFormattingCurrency($model['currency'],$model['totalCost']); 
			// echo '<span>' . yii::$app->Myclass->getCurrency($model->currency) . '</span> <span>' . $model->price . '</span>';
		} ?>

										</span>
										<span class="exchange-initiate-date pull-right"><?=Yii::t('app','Posted On')?> - <?= date('jS M Y', strtotime($model['createdDate']));?></span>
									
									</div>
								</div>
						   <?php } ?>

						</div>                         
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

