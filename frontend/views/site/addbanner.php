<script src="https://checkout.stripe.com/checkout.js"></script>
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use conquer\toastr\ToastrWidget;
use yii\helpers\Json;
$currencyformat = yii::$app->Myclass->getCurrencyFormat($currency);
if($currencyformat[0] == "symbol")
{
	$currency = yii::$app->Myclass->getCurrencySymbol($currency);
}else
{
	$currency = $currency;
}
$currency_position = $currencyformat[1];
?>
<?php if (Yii::$app->session->hasFlash('error')) : ?>
	<?= ToastrWidget::widget([
		'type' => 'error', 'message' => Yii::$app->session->getFlash('error'),
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
	]); ?>
<?php endif; ?>
<div class="advrtz-section">
	<div class="advrtz-banner-top">
		<img class="img-responsive" src="<?=Yii::$app->urlManager->createAbsoluteUrl("/images/advtz.png");?>">
	</div>
	<div class="container-small container">	
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<h2 class="top-heading-text"><?=yii::t('app','Banner Advertise')?></h2>
				<div class="advrtz-form">
					<?php $form = ActiveForm::begin(['id'=>'banner-form','options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return validateBanner()']]);  ?>
					<div class="advrtz-row">
						<div class="add-photos col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="add-photos-heading padding-left-10">
									<span><?=yii::t('app','Banner Image For Web (1920 X 400)')?></span>
								</div> 
								<div class="form-group margin-bottom30 webbanner">
									<div class="file-section rounded text-center padding-top30 padding-bottom30 d-flex just-center align-item-center web-pic">
										<div class="text-center picture ">
											<img src=" <?= Yii::$app->request->baseUrl . '/frontend/web/images/banner_upload.png'   ?>  "  class="previmg">
											<?= $form->field($model, 'bannerimage')->fileInput(['id' => 'bannerimage','onchange'=>"checkBanner(this)"])->label(false);?>
										</div>
									</div>
									<div class="file-section rounded text-center padding-top30 padding-bottom30 def-web-pic" style="display: none;">
										<div class="text-center default-picture">
											<img src="banner-2.jpg" id="webpreview">
											<button id="webbannerclose" type="button" class="close"><span aria-hidden="true">×</span></button>
										</div>
									</div>
								</div>
								<p class="webbannername" style="display: none;"></p>
								<div id="bannerimage_error" class="errorMessage"></div>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="add-photos-heading padding-left-10">
									<span><?=yii::t('app','Banner Image For App (1024 X 500)')?></span>
								</div> 
								<div class="form-group margin-bottom30 appbanner">
									<div class="file-section rounded text-center padding-top30 padding-bottom30 d-flex just-center align-item-center app-pic">
										<div class="text-center picture ">
											<img src="  <?= Yii::$app->request->baseUrl . '/frontend/web/images/banner_upload.png'   ?>   "  class="previmg">
											<?= $form->field($model, 'appbannerimage')->fileInput(['id' => 'appbannerimage','onchange'=>"checkAppbanner(this)"])->label(false);?>
										</div>
									</div>
									<div class="file-section rounded text-center padding-top30 padding-bottom30 def-app-pic" style="display: none;">
										<div class="text-center default-picture">
											<img src="banner-2.jpg" id="apppreview">
											<button id="appbannerclose" type="button" class="close"><span aria-hidden="true">×</span></button>
										</div>
									</div>
								</div>
								<p class="appbannername" style="display: none;"></p>
								<div id="appbannerimage_error" class="errorMessage"></div>
							</div>
						</div>
						<div class="baner-link margin-top-10 add-photos col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="add-photos-heading padding-left-10 clearfix">
								<span class="advrt-header"><?=yii::t('app','Website Banner link')?></span>
							</div>
							<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="form-group">
									<?= $form->field($model, 'bannerurl')->textInput(['maxlength' => true,'id' => 'bannerurl'])->label(false); ?>		
									<div id="bannerurl_error" class="errorMessage"></div>
								</div>
							</div>
							<div class="add-photos-heading padding-left-10 clearfix">
								<span class="advrt-header"><?=yii::t('app','App Banner link')?></span>
							</div>
							<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="form-group">
									<?= $form->field($model, 'appurl')->textInput(['maxlength' => true,'id' => 'appurl'])->label(false); ?>		
									<div id="appbannerurl_error" class="errorMessage"></div>
								</div>
							</div>
						</div>
						<input type="hidden" id="perdaycost" value="<?=$perdayCost?>">
						<input type="hidden" id="currencycode" value="<?=$currency?>">
						<input type="hidden" id="currencyposition" value="<?=$currency_position?>">
						<div class="margin-top-10 add-photos col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="add-photos-heading padding-left-10 clearfix">
								<span class="advrt-header"><?=yii::t('app','When your banner display in live?')?></span>
								<span class="pull-right live-amount">
									<?php
									if (isset($_SESSION['language'])  && $_SESSION['language'] == 'ar'){
										echo yii::$app->Myclass->convertArabicFormattingCurrency($currency_code,$perdayCost);
									}
									else{
										echo yii::$app->Myclass->convertFormattingCurrency($currency_code,$perdayCost); 
									} ?>
									<?=yii::t('app','Per day')?></span>
								</div>
								<div class="Category-input-box-row col-xs-12 col-sm-6 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="startdate"><?=yii::t('app','Start Date')?> <span class="required">*</span></label>
										<input id="startdate" name="Banners[startdate]" type="text" class="banner-date form-control date-select">
										<div id="startDate_error" class="errorMessage"></div>
									</div>
								</div>
								<div class="Category-input-box-row col-xs-12 col-sm-6 col-md-6 col-lg-6">
									<div class="form-group">
										<label for="enddate"><?=yii::t('app','End Date')?><span class="required">*</span></label>
										<input id="enddate" name="Banners[enddate]" type="text" class="banner-date form-control date-select"/>
										<div id="endDate_error" class="errorMessage"></div>
									</div>
								</div>
								<input type="hidden" id="dateValidation" value="0">
								<input type="hidden" id="PayValidation" value="0">
								<div class="errorMessage" id="devicesuccess"></div>
								<p class="padding-left-10 margin-bottom-0">	<span id="total_Cost"></span></p>
								<div class=" col-xs-12 col-sm-6 col-md-6 col-lg-6">
								</div>
								<div class="margin-top-20">
									<?php 
									$sitesetting = yii::$app->Myclass->getSitesettings();
									$stripeSetting = json_decode($sitesetting->stripe_settings, true); 
									$stripe_key = $stripeSetting['stripePublicKey'];
									$paymenttype = json_decode($sitesetting->sitepaymentmodes, true); 
									$bannerpaymenttype =  $paymenttype['bannerPaymenttype'];
									if($bannerpaymenttype == "braintree"){
										?>
										<?= Html::submitButton(Yii::t('app','Pay with braintree'), ['class' => 'post-btn btnUpdate btn braintreepayment']) ?><?php }else{ ?>
											<?= Html::submitButton(Yii::t('app','Pay with stripe'), ['class' => 'post-btn btnUpdate btn stripepayment', 'id' => 'stripepaymentbtn']) ?>
										<?php } ?>
										<span  style="display:none;" id="paymenttype"><?php echo $bannerpaymenttype;?></span>
										<input type="hidden" name ="bannerpayment" value="<?php echo $bannerpaymenttype; ?>" id="bannerpayment" >
										<input type="hidden" value="<?php echo $stripe_key; ?>" id="stripekey" >
										<input type="hidden" value="" id="totalprice" name="totalPrice">
										<div id="shiperr-stripe" style="font-size:13px;color:red;margin-left: 20px;"></div>
									</div>
									<?php ActiveForm::end(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
			if (isset($_SESSION['language'])) {
				if ($_SESSION['language'] == 'ar') {
					?>
					<style>
						.date-select {
							background: url(../images/date-icon.png) no-repeat scroll left 10px center !important;
						}
					</style>
				<?php } } ?>
				<style>
					.default-picture img{
						width: 100%;
						object-fit: cover;
						height: 120px;
					}
					.default-picture .close{
						position: absolute;
						right: 16px;
						background-color: #e40046;
						color: #fff;
						opacity: 1;
						border-radius: 3px;
						width: 21px;
						font-size: 18px;
						/*top: 1px;*/
					}
				</style>
				<style>
					.d-flex{
						display: flex;
					} 
					.just-center{
						justify-content: center;
					} 
					.align-item-center{
						align-items: center;
					}
					.d-block {
						display: inline-block;
					}
					.picture input[type="file"] {
						cursor: pointer;
						display: block;
						height: 75%;
						opacity: 0 !important;
						position: absolute;
						top: 0;
						width: 91%;
					}
					.picture.add img{
						width: 100% !important;
						height: 120px;
						object-fit: cover;
					}
				</style>
				<script>
					/* profile upload start */
					$(document).ready(function(){
						/* Prepare the preview for profile picture */
						$("#bannerimage").change(function(){
							readURL(this);
						});
						$("#appbannerimage").change(function(){
							readappURL(this);
						});
						$("#webbannerclose").click(function(e){
							$('.webbanner .web-pic').show();
							$('.webbanner .def-web-pic').hide();
							$('.webbanner .file-section').removeClass("d-block");
							$('.webbanner .picture').removeClass("add");
						});
						$("#appbannerclose").click(function(e){
							$('.appbanner .app-pic').show();
							$('.appbanner .def-app-pic').hide();
							$('.appbanner .file-section').removeClass("d-block");
							$('.appbanner .picture').removeClass("add");
						});
					});
					function readURL(input) {
						if (input.files && input.files[0]) {
							var reader = new FileReader();
							reader.onload = function (e) {
								$('#webpreview').attr('src', e.target.result).fadeIn('slow');
								$('.webbanner .web-pic').hide();
								$('.webbanner .def-web-pic').show();
							}
							reader.readAsDataURL(input.files[0]);
						}
					}
					function readappURL(input) {
						if (input.files && input.files[0]) {
							var reader = new FileReader();
							reader.onload = function (e) {
								$('#apppreview').attr('src', e.target.result).fadeIn('slow');
								$('.appbanner .app-pic').hide();
								$('.appbanner .def-app-pic').show();
							}
							reader.readAsDataURL(input.files[0]);
						}
					}
					/* profile upload end*/
				</script>
				