

<?php
use yii\helpers\Html;
use yii\helpers\Url;

$user->userId = $model['userId'];
$user->name = $model['name'];
$user->userImage = $model['userImage'];
$user->mobile_status = $model['mobile_status'];
$user->facebookId = $model['facebookId'];
$siteSettings = yii::$app->Myclass->getSitesettings();
?>
<?= Html::csrfMetaTags() ?>
	<!--exchange history modal-->
		<div class="modal fade" id="exchange-history-modal" role="dialog">
			<div>
				<div class="chat-seller-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="otp-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<button data-dismiss="modal" class="close chat-with-seller-close" type="button">×</button>
						<div class="otp-modal-content col-xs-9 col-sm-10 col-md-10 col-lg-10 no-hor-padding">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app', 'Exchange History'); ?></div>
						</div>
					</div>
						<div id="exchangeHistory"></div>
				</div>
			</div>
		</div>
	<!--E O exchange history modal-->


<div class="container">
	<div class="row">
		<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			 <ol class="breadcrumb">
				<li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl() . '/'; ?>"><?php echo Yii::t('app', 'Home'); ?></a></li>
				<li><a href="#"><?php echo Yii::t('app', 'Profile'); ?></a></li>
			 </ol>
		</div>

	</div>
	<div class="row page-container">
	<div class="container exchange-property-container profile-vertical-tab-section">
	<?= $this->render('//user/sidebar', ['user' => $user]) ?> 
	<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
	<div id="exchange" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in">
		<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<?php echo Yii::t('app', 'Exchange Request Details'); ?>
			<div class="exchange-back-link pull-right col-xs-3 col-sm-3 col-md-3 col-lg-3 no-hor-padding">
			<?php if ($exchange->requestFrom != Yii::$app->user->id) { ?>
				<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('user/exchanges?type=incoming'); ?>"><?php echo Yii::t('app', 'Back'); ?></a>
			<?php 
	} else { ?>
				<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('user/exchanges?type=outgoing'); ?>"><?php echo Yii::t('app', 'Back'); ?></a>
			<?php 
	} ?>
			</div>
		</div>
		<div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<div class="exchange-content-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<div class="exchange-left-content col-xs-12 col-sm-12 col-md-4 col-lg-4 no-hor-padding">
				<?php $mainProductImage = yii::$app->Myclass->getProductImage($exchange->mainProductId);
				$exchangeProductImage = yii::$app->Myclass->getProductImage($exchange->exchangeProductId); ?>
					<a class="prof-pic-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view') . '/' . yii::$app->Myclass->safe_b64encode($exchange->mainProductId . '-' . rand(0, 999)) . '/' . yii::$app->Myclass->productSlug($mainProduct->name); ?>">

						<div class="exchange-detail-prof-pic" style="
						<?php if (!empty($mainProductImage)) { 

								$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$exchange->mainProductId . '/' . $mainProductImage);

						if(file_exists($mediapath)) { ?>
						
							background: url('<?php echo Yii::$app->urlManager->createAbsoluteUrl("media/item/" . $exchange->mainProductId . '/' . $mainProductImage); ?>') no-repeat;
					<?php 	} else { ?>
						
								background: url('<?php echo Yii::$app->urlManager->createAbsoluteUrl("media/item/".$siteSettings->default_productimage); ?>') no-repeat;
						<?php }
							?>
						
						<?php 
				} else { ?>
						background: url('<?php echo Yii::$app->urlManager->createAbsoluteUrl("media/item/".$siteSettings->default_productimage); ?>') no-repeat;
						<?php 
				} ?>
						background-repeat: no-repeat;background-position: center center;background-size: cover;"></div>

						<div class="exchange-prod-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<?php echo $mainProduct->name; ?></div>

					</a>
				</div>
				<div class="exchange-detail-arrow-cnt col-xs-12 col-sm-12 col-md-4 col-lg-4 no-hor-padding"><div class="exchange-arrow"></div></div>
				<div class="exchange-right-content col-xs-12 col-sm-12 col-md-4 col-lg-4 no-hor-padding">
					<a class="prof-pic-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"
						href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view') . '/' . yii::$app->Myclass->safe_b64encode($exchange->mainProductId . '-' . rand(0, 999)) . '/' . yii::$app->Myclass->productSlug($mainProduct->name); ?>">

						<div class="exchange-detail-prof-pic" style="
						<?php if (!empty($exchangeProductImage)) { 

								$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$exchange->exchangeProductId . '/' . $exchangeProductImage);

						if(file_exists($mediapath)) { ?>
						
							background: url('<?php echo Yii::$app->urlManager->createAbsoluteUrl("media/item/" . $exchange->exchangeProductId . '/' . $exchangeProductImage); ?>') no-repeat;
					<?php 	} else { ?>
						
								background: url('<?php echo Yii::$app->urlManager->createAbsoluteUrl("media/item/".$siteSettings->default_productimage); ?>') no-repeat;
						<?php }
							?>
						
						<?php 
				} else { ?>
						background: url('<?php echo Yii::$app->urlManager->createAbsoluteUrl("media/item/".$siteSettings->default_productimage); ?>') no-repeat;
						<?php 
				} ?>
						background-repeat: no-repeat;background-position: center center;background-size: cover;"></div>

						<div class="exchange-prod-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<?php echo $exchangeProduct->name; ?></div>

					</a>
				</div>
			</div>

			<div class="exchange-details-info col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<div class="exchange-btn-cnt pull-right">
			<?php
		$mCheck = yii::$app->Myclass->checkWhetherProductSold($exchange->mainProductId);
		$exCheck = yii::$app->Myclass->checkWhetherProductSold($exchange->exchangeProductId);
		if (!empty($mCheck) || !empty($exCheck)) { ?>
				<p class="sold-status">
					<label class="label-lg label-default"><?php echo Yii::t('app', 'ONE OF THE PRODUCTS IS SOLD'); ?></label>
				</p>

				 <?php
				echo Html::a(
					Yii::t('app','OK'),
					['/user/sold', 'id' => $exchange->id],
					['class' => 'exchange-btn', 'style' => 'font-size: 13px; float: none;text-decoration:none;', 'id' => 'exc-pending'],
					[
						'data-confirm' => ' Are you sure you want to proceed ?',
						'data-method' => 'post',

					]

				);
				?>
				
				<?php 
		} else {
			if ($exchange->status == 1) {
				?>
				 <?php
				echo Html::a(
					Yii::t('app','SUCCESS'),
					['/user/success', 'id' => $exchange->id],
					['class' => 'exchange-btn', 'style' => 'color:black!important'],
					[
						'data-confirm' => ' Are you sure you want to proceed ?',
						'data-method' => 'post',

					]

				);
				?>&nbsp;
				<?php
			echo Html::a(
				Yii::t('app','FAILED'),
				['/user/failed', 'id' => $exchange->id],
				['class' => 'exchange-btn', 'style' => 'color:black!important'],
				[
					'data-confirm' => ' Are you sure you want to proceed ?',
					'data-method' => 'post',

				]

			);
			?>
				<?php 
		} else {
			if ($exchange->requestFrom == Yii::$app->user->id) { ?>
				 <?php
				echo Html::a(
					Yii::t('app', 'CANCEL'),
					['/user/cancel', 'id' => $exchange->id],
					['class' => 'exchange-btn', 'style' => 'color:black!important'],
					[
						'data-confirm' => ' Are you sure you want to proceed ?',
						'data-method' => 'post',

					]

				);
				?>
				<?php 
		} else { ?>
				 <?php
				echo Html::a(
					Yii::t('app', 'DECLINE'),
					['/user/decline', 'id' => $exchange->id],
					['class' => 'exchange-btn', 'style' => 'color:black!important'],
					[
						'data-confirm' => ' Are you sure you want to proceed ?',
						'data-method' => 'post',

					]

				);
				?>
				</span>
				<?php
			echo Html::a(
				Yii::t('app', 'ACCEPT'),
				['/user/accept', 'id' => $exchange->id],
				['class' => 'exchange-btn', 'style' => 'color:black!important'],
				[
					'data-confirm' => ' Are you sure you want to proceed ?',
					'data-method' => 'post',

				]

			);
			?>

				<?php 
		} ?>
				<?php 
		}
	} ?>
				</div>
				<div class="exchange-history">
					<a href="" onclick="showexchangehistory('<?php echo $exchange->slug; ?>')" data-dismiss="modal" data-toggle="modal" data-target="#exchange-history-modal"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/reload-icon.png'); ?>" width="16px" height="16px" alt="reload-icon"><?php echo Yii::t('app', 'View exchange history'); ?></a>
				</div>
			</div>
			<div class="exchange-message-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<div class="exchange-message-header-txt-cnt pull-right">
					<img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/message-icon.png'); ?>" width="24px" height="18px" alt="message-icon"><?php echo Yii::t('app', 'Message'); ?>
				</div>
			</div>
			<div class="exchange-message-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

			</div>
			</div></div></div>
		</div>
	</div>
</div>
<script>

function exchangeChat() {

var data={'sourceId'  : <?php echo $exchange->id; ?>,'from' : <?php echo $exchange->requestFrom; ?>,'to':<?php echo $exchange->requestTo; ?>};

	$.ajax({
     type:'GET',
     url: '<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/user/message/',
     data : data,
     contentType: "application/json; charset=utf-8", // this
     dataType: "html", // and this
     success : function(data) {
     	
           $(".exchange-message-cnt").html(data);
         }


		});
}
exchangeChat();
</script>
