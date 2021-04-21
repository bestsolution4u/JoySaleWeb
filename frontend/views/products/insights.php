<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use bsadnu\googlecharts\LineChart;
use common\models\Products;
use common\models\Records;
use yii\helpers\Json;
use common\models\Sitesettings;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
error_reporting(0);
$settings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
$productId = $model->productId;
?>
<div class="container">	
	<div class="insight-page-section">
		<div class="row">
			<div class="help col-xs-12 col-sm-12 col-md-12 col-lg-12">	
				<div class="sidemenu-insight col-xs-12 col-sm-3 col-md-3 col-lg-2 no-hor-padding">	
					<div class="sidebar sticky" id="sideshow">	
						<nav class="navbar">					
							<ul class="navbar-nav" id="nav">
								<li class="nav-item">
									<a class="nav-link active" href="#view"><?php echo Yii::t('app','View'); ?></a>			
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#engagement"><?php echo Yii::t('app','Engagement'); ?>				</a>					
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#visited"><?php echo Yii::t('app','Visited City'); ?></a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-9 col-lg-10 no-hor-padding">
					<div class="help-rig-content insight-content active">
						<div class=""  data-spy="scroll" data-target=".navbar" data-offset="50">
							<button type="button" class="resp-view btn btn-primary border-none" data-toggle="collapse" data-target="#view"><span><?php echo Yii::t('app','Views'); ?></span> <i class="minus"></i> <i class="plus"></i></button>
							<div id="view" class="container-fluid collapse scroll-content first-review">
								<div class="view-popularity clearfix">
									<div class="resp-filter col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<h3 class="visite-title"><?php echo Yii::t('app','Views'); ?></h3>
									</div>
									<?php
								//echo $per.'setper'; exit;
									if($settings->promotionStatus==1){
										if(Yii::$app->user->id==$model->userId) {
											if($per<=45) { ?>
												<div class="resp-filter col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<div class="green-color-bg border-radius-5">
														<div class="promo-section clearfix">
															<span class="pull-left popul-low-img margin-left-20 txt-white-color bold"><?php echo Yii::t('app','Popularity low');?></span>
															<div class="pull-right prom-btn-left">
												<?php //echo Html::a(Yii::t('app','Promote'),Yii::$app->urlManager->createAbsoluteUrl(['user/profiles','id'=>yii::$app->Myclass->safe_b64encode($model->userId.'-'.rand(0,999))]),array('class' => 'prom-btn btn primary-txt-color graph-group'));  
												if($promotionStatus == 'enabled')
												{
													if($model->soldItem==0){
														?>
														<a href="javascript:void(0);" class="prom-btn btn primary-txt-color graph-group" data-toggle="modal" data-target="#modal1" 
														onclick="showListingPromotion(<?= $productId; ?>)">
														<?php echo Yii::t('app','Promote'); ?></a>
														<?php 
													}else{
														echo '<a href="javascript:void(0);" class="prom-btn btn primary-txt-color graph-group" >'.Yii::t('app','Sold out').'</a>';
													}
												}else{
													?>
													<a href="javascript:void(0);" class="prom-btn btn primary-txt-color graph-group" >
														<?php echo Yii::t('app','Already promoted'); ?></a>
														<?php
													} 
													?>
												</div>
											</div>
										</div>
									</div>
								<?php } } }?>
								<div class="resp-filter col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding text-center">
									<ul class="splited">
										<li class="unique">
											<div class="popularity-count">
												<div class="count-view">
													<p class="product-name">
														<?php echo Yii::t('app','Unique View'); ?></p>
														<p class="product-name"><?= $unquie_view; ?></p>
													</div>
												</div>
											</li>
											<li class="unique">
												<div class="popularity-count">
													<div class="count-view">
														<p class="product-name"><?php echo Yii::t('app','Total View');?></p>
														<p class="product-name"><?=$model->views?></p>
													</div>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<div class="resp-filter col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<input type="hidden" value="<?=$model->productId?>" id="product_id">
									<select id="items-selector" class="pull-right" onchange="reportItems(this.val)" class="insight-dropdown">
										<?php 
										if(!isset($_SESSION['productInsight'])) {
											$_SESSION['productInsight'] = 'weekly';
										}
										if($_SESSION['productInsight'] == 'weekly') {
											echo '<option selected value="weekly">'.Yii::t('app','Weekly').'</option>';
										} else {
											echo '<option value="weekly">Daily</option>';
										}?>
										<?php if($_SESSION['productInsight'] == 'monthly') {
											echo '<option selected value="monthly">'.Yii::t('app','Monthly').'</option>';
										} else {
											echo '<option value="monthly">Monthly</option>';
										}?>
										<?php if($_SESSION['productInsight'] == 'year') {
											echo '<option selected value="year">'.Yii::t('app','Yearly').'</option>';
										} else {
											echo '<option value="year">'.Yii::t('app','Yearly').'</option>';
										}?>
									</select>
								</div>
								<div class="resp-filter col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="graph-group">
										<div class="graph-chart">
											<div id="chartBar2" style="width: 100%">
												<?php
											//echo '<pre>'; print_r($prosubdata); exit;
												$prodinsight = Yii::t('app','Product Insight Report');
												?>
												<?= LineChart::widget([
													'id' => 'my-simple-line-chart-id',
													'data' => [
														$prosubdata
													],
													'options' => [
														'colors'=> ['#E40046'],
														'lineWidth'=> 3,
														'fontName' => 'Verdana',
														'height' => 400,
														'curveType' => 'function',
														'fontSize' => 12,
														'chartArea' => [
															'left' => '3%',
															'width' => '90%',
															'height' => 350
														],
														'pointSize' => 5,
														'tooltip' => [
															'textStyle' => [
																'fontName' => 'Verdana',
																'fontSize' => 13
															]
														],
														'vAxis' => [
															'title' => $prodinsight,
															'titleTextStyle' => [
																'fontSize' => 13,
																'italic' => false
															],        	
															'gridlines' => [
																'color' => '#e5e5e5',
																'count' => 10
															],            	
															'minValue' => 0
														],        
														'legend' => [
															'position' => 'top',
															'alignment' => 'center',
															'textStyle' => [
																'fontSize' => 12
															]
														]            
													]
												]) ?>  
											</div>
										</div>
									</div>
								</div>
							</div>
							<button type="button" class="resp-view btn btn-primary border-none" data-toggle="collapse" data-target="#engagement"><span><?php echo Yii::t('app','Engagement');?></span> <i class="minus"></i> <i class="plus"></i></button>
							<div id="engagement" class="container-fluid scroll-content collapse animatable fadeInUp">
								<div class="resp-filter margin-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<h3 class="visite-title"><?php echo Yii::t('app','Engagement');?></h3>
									<?php 
									if(Yii::$app->user->id==$model->userId) {
										if($percentageEnga<=45) { ?>
											<div class="engagement-section graph-group">
												<div class="promo-section clearfix">
													<span class="pull-left eng-low-img margin-left-20 primary-txt-color"><?php echo Yii::t('app','Engagements is low');?></span>
													<div class="pull-right prom-btn-left">
														<a href="javascript:void(0);" 
														class="green-color-bg prom-btn btn txt-white-color" data-toggle="modal" data-target="#modal2">
														<?php echo Yii::t('app','Reach');  ?></a>  
													</div>
												</div>
											</div>
										<?php } } ?>
									</div>
									<div class="resp-filter col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<ul class="splited">
											<li class="unique">
												<div class="graph-group engage-count clearfix">
													<div class="reach-view pull-left">
														<p class="product-name"><?php echo Yii::t('app','Total Likes');?></p>
														<p class="product-name"><?=$model->likes?></p>
													</div>
													<div class="reach-icon pull-right">
														<div class="engage-icon">
															<?=Html::img(Yii::$app->urlManagerfrontEnd->baseUrl . '/images/insight-heart.png')?>     
														</div>
													</div>
												</div>
											</li>
											<li class="unique">
												<div class="graph-group engage-count clearfix">
													<div class="reach-view pull-left">
														<p class="product-name"><?php echo Yii::t('app','Total Comments');?></p>
														<p class="product-name"><?=$comments?></p>
													</div>
													<div class="reach-icon pull-right">
														<div class="engage-icon">
															<?=Html::img(Yii::$app->urlManagerfrontEnd->baseUrl . '/images/insight-cmt.png')?>
														</div>
													</div>
												</div>
											</li>
											<li class="unique">
												<div class="graph-group engage-count clearfix">
													<div class="reach-view pull-left">
														<p class="product-name"><?php echo Yii::t('app','Total Offer Request');?></p>
														<p class="product-name"><?=$offerRequestcnt?></p>
													</div>
													<div class="reach-icon pull-right">
														<div class="engage-icon">
															<?=Html::img(Yii::$app->urlManagerfrontEnd->baseUrl . '/images/insight-chart.png')?>
														</div>
													</div>
												</div>
											</li>
											<?php $sitePaymentModes = yii::$app->Myclass->getSitePaymentModes(); 
											if($sitePaymentModes['exchangePaymentMode'] == "1"){ ?>
												<li class="unique">
													<div class="graph-group engage-count clearfix">
														<div class="reach-view pull-left">
															<p class="product-name"><?php echo Yii::t('app','Total Exchange Request');?></p>
															<p class="product-name"><?= $exchangeCount; ?></p>
														</div>
														<div class="reach-icon pull-right">
															<div class="engage-icon">
																<?=Html::img(Yii::$app->urlManagerfrontEnd->baseUrl . '/images/insight-exchange.png')?>												
															</div>
														</div>
													</div>
												</li>
											<?php } ?>
										</ul>
									</div>
								</div>
								<button type="button" class="resp-view btn btn-primary border-none" data-toggle="collapse" data-target="#visited"><span><?php echo Yii::t('app','Visited City'); ?></span> <i class="minus"></i> <i class="plus"></i></button>
								<div id="visited" class="container-fluid scroll-content collapse animatable fadeInUp">
									<div class="resp-filter margin-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<h3 class="visite-title"><?php echo Yii::t('app','Visited City'); ?></h3>
									</div>
									<div class="resp-filter col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding text-center">
										<ul class="splited">
											<li class="unique" style="width:100% !important;">
												<div class="popularity-count">
													<div class="count-view">
														<p class="product-name"><?php  echo Yii::t('app','Total visited city');?></p>
														<p class="product-name"><?=
														$total_visitedcity
														?></p>
													</div>
												</div>
											</li>
									<!-- li class="unique">
										<div class="popularity-count">
											<div class="count-view">
												<p class="product-name">Most visited city</p>
												<p class="product-name"><?= 
												$most_visitedcity; ?></p>
											</div>
										</div>
									</li -->
								</ul>
							</div>
							<?php 
							$progressCount = 100/(int)$totalCount;
							$progressCount = number_format($progressCount, 2, '.', '');
							foreach ($country as $key => $value) { ?>
								<div class="progress-bar-value col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding margin-top-20 margin-bottom-0 clearfix">
									<div class="resp-filter col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<div class="graph-group clearfix margin-bottom-0">
											<p><?=$key?></p> 
											<div class="col-xs-10 col-sm-10 col-md-11 col-lg-11 no-hor-padding">
												<div class="progress">
													<?php 
													$percentageValue = $progressCount * $value;
													$percentageValue = number_format($percentageValue, 2, '.', '');
													?>
													<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:<?=$percentageValue?>%"></div> 
												</div>
											</div>
											<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 no-hor-padding text-center">
												<span class="progres-value"><?=$value?></span>
											</div>
										</div>
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
</div>
</div></div>
<div id="modal1" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog post-list-modal-width">
		<div class="post-list-modal-content login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<div class="post-list-header login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<div class="modal-header-text"><p class="login-header-text"><?php echo Yii::t('app','Promote the listing'); ?></p></div>
				<button data-dismiss="modal" class="close login-close" type="button" id="white">×</button> 
			</div>
			<div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
			<?php 
			$sitesetting = yii::$app->Myclass->getSitesettings(); 
			?>
			<div class="post-list-cnt login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
				<div class="login-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="login-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="post-list-modal-heading"><?php echo Yii::t('app','Highlight your listing?'); ?></div>
						<div class="post-list-content">
							<?php echo $sitesetting->sitename." ".Yii::t('app','allows you to highlight your listing with two different options to reach more number of buyers. You can choose the appropriate option for your listings. Urgent listings gets more leads from buyers and featured listings shows at various places of the website to reach more buyers.'); ?>
						</div>
					</div>
					<div class="post-list-tab-cnt">
						<ul class="post-list-modal-tab nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#urgent"><?php echo Yii::t('app','Urgent'); ?></a></li>
							<li><a data-toggle="tab" href="#promote"><?php echo Yii::t('app','Ad'); ?></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="post-list-tab-content  tab-content">
				<div id="urgent" class="tab-pane fade in active">
					<p> <?php echo Yii::t('app','To make your ads instantly viewable you can go for Urgent ads, which gets highlighted at the top.'); ?></p>
					<?php  if (isset($urgentPrice)) {
						$promoteCurrency = explode("-", $promotionCurrency);
						echo "<p align='center'>";
						if (isset($_SESSION['language'])  && $_SESSION['language'] == 'ar'){
							echo yii::$app->Myclass->convertArabicPopupFormattingCurrency(str_replace(" ", "", $promoteCurrency[0]),$urgentPrice); 
						}
						else{
							echo yii::$app->Myclass->convertFormattingCurrency(str_replace(" ", "", $promoteCurrency[0]),$urgentPrice); 
						}
						echo "</p>";
					} ?></p>
					<div class="urgent-tab-left col-xs-12 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
						<ul><div class="urgent-tab-heading"><?php echo Yii::t('app','Urgent tag Features:'); ?></div>
							<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','More opportunities for your buyers to see your product'); ?></span></li>
							<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Higher frequency of listing placements'); ?></span></li>
							<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Highlight your listing to stand out'); ?></span></li>
							<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Use for Make fast sale for seller and Make buyer to do purchase as Urgent'); ?></span></li>
							<li class="stuff-post">
								<?php 
								$paymenttype = json_decode($sitesetting->sitepaymentmodes, true); 
								$bannerpaymenttype =  $paymenttype['bannerPaymenttype'];
								if($bannerpaymenttype == "stripe"){
									?>
									<?php $form = ActiveForm::begin(['id'=>'promotionstripeform',
									'action'  => Yii::$app->urlManager->createAbsoluteUrl('/products/promotionstripepaymentprocess'),'options' => ['enctype' => 'multipart/form-data']]); ?>
									<input type="hidden" name="BPromotionType" value='urgent' />
									<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">
									<button class="btn post-btn" id="customButton"><?php echo Yii::t('app','Highlight with stripe'); ?></button>
									<input type="hidden" id="itemids" name="itemids">
									<?php 
									$userId = Yii::$app->user->id;
									$sitesetting = yii::$app->Myclass->getSitesettings();
									$stripeSetting = json_decode($sitesetting->stripe_settings, true); 
									$stripe_key = $stripeSetting['stripePublicKey'];
									$total_price = ($sitesetting->urgentPrice)*100;
									$currency =  explode('-', $sitesetting->promotionCurrency);	
									$promotionType = "urgent";
									$customField = $promotionType."-_-".$currency[0]."-_-0-_-".$total_price."-_-".$userId;
									$customField = yii::$app->Myclass->cart_encrypt($customField, "pr0m0tion-det@ils");
									?>
									<input type="hidden" value="<?php echo $total_price; ?>" id="price" >
									<input type="hidden" value="<?php echo $promoteCurrency[1]; ?>" id="displaycurrency" >
									<input type="hidden" value="" id="promotiontype" name="promotiontype">
									<input type="hidden" value="<?php echo $stripe_key; ?>" id="stripekey" >
									<input type="hidden" value="" id="totalprice" name="totalPrice">
									<div id="shiperr-stripe" style="font-size:13px;color:red;margin-left: 20px;"></div>
									<input type="hidden" value="<?php echo $customField; ?>" id="customField1" name="customField1">
									<input type="hidden" value="" id="customField" name="customField">
									<input type="hidden" name="BPromotionType" value='urgent' />
									<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">
									<input type="hidden" name="currency" value="<?php echo $currency[0]; ?>"/>
									<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
									<div class="urgent-promote-error delete-btn"></div> 
									<?php ActiveForm::end(); }else{?>
										<?php $form = ActiveForm::begin(['id'=>'promotionbraintreeform',
										'action'  => Yii::$app->urlManager->createAbsoluteUrl('/products/promotionpaymentprocess'),'options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return promotionUpdate("urgent")']]); ?>
										<input type="hidden" name="BPromotionType" value='urgent' />
										<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">
										<button class="btn post-btn" href="javascript:void(0);" onclick='return promotionUpdate("urgent")' type="submit"><?php echo Yii::t('app','Highlight with braintree'); ?></button>
										<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
										<div class="urgent-promote-error delete-btn"></div>
										<?php ActiveForm::end();
									}
									?>
								</li>
							</ul>
						</div>
						<div class="urgent-tab-right col-xs-12 col-sm-4 col-md-4 col-lg-4 no-hor-padding">
							<div class="urgent-right-circle-icon"><span class="item-urgent-1"><?php Yii::t('app','Urgent');?></span></div>
						</div>
					</div>
					<div id="promote" class="tab-pane fade">
						<p><?php echo Yii::t('app','Promote your listings to reach more users than normal listings. The promoted listings will be shown at various places to attract the buyers easily.'); ?></p>
						<div class="tab-radio-button-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<?php
							$promotionCurrencyDetails = explode('-', $promotionCurrency);
							foreach ($promotionDetails as $promotion){ ?>
								<div class="tab-radio-button col-xs-6 col-sm-6 col-md-3 col-lg-3 no-hor-padding">
									<div class="tab-radio-content">
										<label><input type="radio" name="optradio" onclick="updatePromotion('<?php echo $promotion->id; ?>')"></label>
										<div class="radio-tab-period"><?php echo $promotion->name; ?></div>
										<div class="radio-tab-price col-xs-offset-3 col-sm-offset-5 col-md-offset-4 col-lg-offset-4">
											<?php
											if (isset($_SESSION['language'])  && $_SESSION['language'] == 'ar'){
												echo yii::$app->Myclass->convertArabicFormattingCurrency(str_replace(" ", "", $promotionCurrencyDetails[0]),$promotion->price);
											} 
											else{
												echo yii::$app->Myclass->convertFormattingCurrency(str_replace(" ", "", $promotionCurrencyDetails[0]),$promotion->price); 
											} ?>
										</div>
										<div class="radio-tab-days"><?php echo $promotion->days; ?> <?php echo Yii::t('app','days'); ?></div>
									</div>
								</div>
							<?php }  ?>
						</div>
						<div class="promote-tab-left col-xs-12 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
							<ul><div class="promote-tab-heading"><?php echo Yii::t('app','promote tag Features:'); ?></div>
								<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','View-able with highlight for all users on desktop and mobile'); ?></span></li>
								<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Displayed at the top of the page in search results'); ?></span></li>
								<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Higher visibility in search results means more buyers'); ?></span></li>
								<li><i class="tick-icon fa fa-check" aria-hidden="true"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Listing stands out from the regular posts'); ?></span></li>
								<li class="stuff-post">
									<?php
									if($bannerpaymenttype == "stripe"){
										$form = ActiveForm::begin(['id'=>'adpromotionstripeform',
										'action'  => Yii::$app->urlManager->createAbsoluteUrl('/products/promotionstripepaymentprocess'),'options' => ['enctype' => 'multipart/form-data']]); ?>
										<button class="btn post-btn" id="customButton1"><?php echo Yii::t('app','Promote with Stripe');?></button>
										<input type="hidden" id="promotionids" name="promotionids">
										<?php 
										$userId = Yii::$app->user->id; 
										$sitesetting = yii::$app->Myclass->getSitesettings();
										$stripeSetting = json_decode($sitesetting->stripe_settings, true); 
										$stripe_key = $stripeSetting['stripePublicKey'];
										$currency =  explode('-', $sitesetting->promotionCurrency);
										$promotionTypes = "adds";
										$total_pricess = $promotion->price;
										$customFieldd = $promotionTypes."-_-".$currency[0]."-_-0-_-".$total_pricess."-_-".$userId;
										$customFieldd = yii::$app->Myclass->cart_encrypt($customFieldd, "pr0m0tion-det@ils"); 
										?>
										<input type="hidden" value="<?php echo $promotionTypes; ?>" id="promotiontypee" name="promotiontypee">
										<input type="hidden" value="" id="itemide" name="itemide" >
										<input type="hidden" value="<?php echo $stripe_key; ?>" id="stripekeyy" >
										<input type="hidden"  value="<?php echo $total_pricess; ?>" id="totalpricee" >
										<div id="shiperr-stripe" style="font-size:13px;color:red;margin-left: 20px;display:none;"></div>
										<input type="hidden" value="<?php echo $customFieldd; ?>" id="customFieldd" name="customFieldd">
										<input type="hidden" id ="currencyy" name="currencyy" value="<?php echo $currency[0]; ?>"/>
										<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
										<div class="adds-promote-error delete-btn"></div>
										<?php ActiveForm::end();
									}else{ ?>
										<?php
										$form = ActiveForm::begin(['id'=>'promotionbraintreeform','action'  => Yii::$app->urlManager->createAbsoluteUrl('products/promotionpaymentprocess'),'options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return promotionUpdate("adds")']]); ?>
										<form name="promotionbraintreeform" method="post" action="<?php // Yii::$app->urlManager->createAbsoluteUrl('products/promotionpaymentprocess'); ?>" >
											<!-- validateOnSubmit="return promotionUpdate('adds')" -->
											<input type="hidden" name="BPromotionType" value='adds' />
											<input type="hidden" name="BPromotionProductid" id="ADPromotionProductid" value="">
											<input type="hidden" name="BPromotionid" id="ADPromotionid" value="promotionUpdate('adds')">
											<button class="post-btn btn" onclick="promotionUpdate('adds')" ><?php echo Yii::t('app','Promote with braintree'); ?></button>
											<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
											<div class="adds-promote-error delete-btn"></div>
											<?php ActiveForm::end();
										} ?>
									</form>
								</li>
							</ul>
						</div>
						<div class="promote-tab-right col-xs-12 col-sm-4 col-md-4 col-lg-4 no-hor-padding">
							<div class="promote-right-circle-icon"><span class="item-ad-1"><?php echo Yii::t('app','Ad'); ?></span></div>
						</div>
					</div>
				</div>
			</div> 
		</div>
		<input type="hidden" class="promotion-product-id" value="">
		<input type="hidden" name="Products[promotion][type]" value="" id="promotion-type">
		<input type="hidden" name="Products[promotion][addtype]" value="" id="promotion-addtype">
	</div>
	<div id="modal2" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		<div class="modal-dialog post-list-modal-width">
			<div class="post-list-modal-content login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
				<div class="post-list-header login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					<div class="modal-header-text"><p class="login-header-text"><?php echo Yii::t('app','To Reach!'); ?></p></div>
					<button data-dismiss="modal" class="close login-close" type="button" id="white">×</button>
				</div>
				<div>
					<?php
						//echo $reachcontent->page;
					?>
				</div>
				<div id="promote" class="post-list-tab-content  tab-content">
					<?php
					$session_lang =  $_SESSION['language'];  
					$helpcontent = Json::decode($reachcontent->pageContent,true);
					if ($helpcontent!="") {
						if (array_key_exists($session_lang, $helpcontent)) {
							$help_desc = $helpcontent[$session_lang]['content'];
						} 
						else
						{
							$firstelem = array_keys($helpcontent)[0];
							$help_desc = $helpcontent[$firstelem]['content'];
						}
					}
					?>
					<?php echo  $help_desc; ?>
						<!-- 	<?php
								echo $reachcontent->pageContent;
								?> -->
							</div>
							<div class="">
							</div>
						</div> 
					</div>
					<input type="hidden" class="promotion-product-id" value="">
					<input type="hidden" name="Products[promotion][type]" value="" id="promotion-type">
					<input type="hidden" name="Products[promotion][addtype]" value="" id="promotion-addtype">
				</div>
				<script>
					var color = Chart.helpers.color;
					var barChartData = {
						labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
						datasets: [ {
							type: 'line',
							label: '',
							pointBackgroundColor: '#E40046',
							pointBorderColor : '#fff',
							pointBorderWidth : 4,
							pointRadius: 8,
							borderWidth: 5,
							pointHoverRadius: 9,
							pointHitRadius:30,
							backgroundColor: color(window.chartColors.pink).alpha(0).rgbString(),
							borderColor: window.chartColors.pink,
							data: [20, 25, 28, 35, 30, 33, 27]
						}]
					};
// Define a plugin to provide data labels
Chart.plugins.register({
	afterDatasetsDraw: function(chart) {
		var ctx = chart.ctx;
		chart.data.datasets.forEach(function(dataset, i) {
			var meta = chart.getDatasetMeta(i);
			if (!meta.hidden) {
				meta.data.forEach(function(element, index) {
					// Draw the text in black, with the specified font
					ctx.fillStyle = 'rgb(0, 0, 0)';
					var fontSize = 20;
					var fontStyle = 'normal';
					var fontFamily = 'Regular';
					ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
					// Just naively convert to string for now
					var dataString = dataset.data[index].toString();
					// Make sure alignment settings are correct
					ctx.textAlign = 'left';
					ctx.textBaseline = 'middle';
					var padding = 10;
					var position = element.tooltipPosition();
					ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
				});
			}
		});
	}
});
window.onload = function() {
	var draw = Chart.controllers.line.prototype.draw;
	Chart.controllers.line = Chart.controllers.line.extend({
		draw: function() {
			draw.apply(this, arguments);
			let ctx = this.chart.chart.ctx;
			let _stroke = ctx.stroke;
			ctx.stroke = function() {
				ctx.save();
				ctx.shadowColor = '#999999';
				ctx.shadowBlur = 15;
				ctx.shadowOffsetX = 0;
				ctx.shadowOffsetY = 13;
				_stroke.apply(this, arguments)
				ctx.restore();
			}
		}
	});
	var ctx = document.getElementById('canvas').getContext('2d');
	window.myLine = new Chart(ctx, {
		type: 'line',
		data: barChartData,
		options: {
			responsive: true,
			legend : {
				display: false
			},
			title: {
				display: false,
				text: 'Insight',
				fontStyle: 'bold',
				fontSize: 20
			},
			layout: {
				padding: {
					left: 10,
					right: 25,
					top: 30,
					bottom: 30
				}
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: ''
					},
					ticks : {
						display: true
					},
					gridLines : {
						color: 'rgba(228, 0, 70, 0.10)'
					}
				}],
				yAxes: [{
					position: 'left',
					display: false,
					scaleLabel: {
						display: true,
						labelString: ''
					},
					ticks: {
						reverse: false
					}
				}]
			}
		}
	});
};
</script>
<script>
	function reportItems() {
		var items = $("#items-selector").val();
		var productId = $("#product_id").val();
		$.ajax({
			type : 'GET',
			url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/products/itemsdata',
			data : {
				items : items,
				productId:productId,
			},
			dataType : "text",
			success : function(data) 
			{
				//console.log(data);
				//alert(data); return false;
				var obj = data;
				google.charts.load('current', {packages: ['corechart']});
				function drawLineChart() {
            // Define the chart to be drawn.
            var data = google.visualization.arrayToDataTable(
            	JSON.parse(obj)
            	);
            var options = {
            	isStacked:true,
            	fontSize:12,
            	curveType:'function',
            	pointSize:5,
            	colors:['#E40046'],
            	lineWidth:3,
            	fontName: 'Verdana',
            	legend: { position: 'top','alignment': 'center','textStyle': {'color': '#000','fontSize': 12} },
            	height:350,
            	chartArea: {width:800,height:280},
            	vAxis:{
            		titleTextStyle:{fontSize: 13},
            		gridlines: 'rgba(228, 0, 70, 0.10)'
            	}
            };  
            // Instantiate and draw the chart.
            var chart = new google.visualization.LineChart(document.getElementById('my-simple-line-chart-id'));
            chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(drawLineChart);
    },
    error: function(err)
    {
    	console.log("Error");
    }
});
	}
</script>
<script src="https://www.gstatic.com/charts/loader.js"></script> 
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://code.jquery.com/jquery-latest.min.js"></script> 
<script>
	$('#customButton').click(function(){
		if(this.disabled = true){
		var stripekey = $('#stripekey').val();//alert(stripekey);
		var x='<?php echo $promoteCurrency[1]; ?>';
		var promotionType = $('#promotiontype').val('urgent');//alert(promotionType);
		//var promotionType = $('#promotiontype').val();alert(promotionType);
		var totalprice = $('#price').val();
		var customField1 = $('#customField1').val();
		$('#customField').val(customField1);
		$('#totalprice').val(totalprice);
		$('#customButtonn').attr('disabled', 'disabled');
		$id = $('.promotion-product-id').val();//alert($id);
		$('#itemids').val($id);
		var token = function(res){
			var $input = $('<input type=hidden name=stripeToken class=stripee />').val(res.id);
			$('#braintreeurgentt').attr('disabled', 'disabled');
			$('#customButton').attr('disabled', 'disabled');
			$('#promotionstripeform').append($input).submit();
		};
		StripeCheckout.open({
			key:         stripekey,
			address:     false,
			amount:      totalprice,
			currency:    '<?php echo trim(strtolower($currency[0])); ?>',
			name:        '<?php echo $sitesetting->sitename; ?>',
			panelLabel:  'Checkout',
			token:       token,
			closed : function() {
				var stripeee = $('.stripee').val();
				if(typeof(stripeee) === "undefined"){
						//alert("1");
						$("#braintreeurgentt").removeAttr('disabled');
						$("#customButton").removeAttr('disabled');
					}else{
						//alert("2");
						$('#braintreeurgentt').attr('disabled', 'disabled');
						$('#customButton').attr('disabled', 'disabled');
					}
				}
			});
		return false;
	}
});
	$('#customButton1').click(function(){
		var stripekey = $('#stripekeyy').val();
		//var promotionTypes = $('#promotiontypee').val('adds');alert(promotionTypes);
		var promotionTypes = $('#promotiontypee').val();//alert(promotiontypes);
		$id = $('.promotion-product-id').val();//alert($id);
		$('#itemide').val($id);
		var totalpricee = $('#totalpricee').val();
		var  totalprice =  totalpricee * 100;
		var errorSelector = ".adds-promote-error";	//alert(errorSelector);
		var promotionId = $('#promotion-addtype').val();
		$('#customButtonn1').attr('disabled', 'disabled');
		if(promotionId == ""){
			$(errorSelector).html(yii.t('app', 'Select a Promotion'));
			$(errorSelector).show();
			setTimeout(function() {
				$(errorSelector).html('');
				$(errorSelector).hide();
			}, 1500);
			return false;
		}
		else {
			if(this.disabled = true){
				var token = function(res){
					var $input = $('<input type=hidden name=stripeToken class=stripee />').val(res.id);
					$('#braintreeaddss').attr('disabled', 'disabled');
					$('#customButton1').attr('disabled', 'disabled');
					$('#adpromotionstripeform').append($input).submit();
				};
				StripeCheckout.open({
					key:         stripekey,
					address:     false,
					amount:      totalprice,
					currency:    '<?php echo trim(strtolower($currency[0])); ?>',
					name:        '<?php echo $sitesetting->sitename; ?>',
					panelLabel:  'Checkout',
					token:       token,
			 // closed : function() {
			 // 	$("#customButton1").removeAttr('disabled');
			 // 	$("#customButtonn1").removeAttr('disabled');
			 // }
			 closed : function() {
			 	var stripeee = $('.stripee').val();
			 	if(typeof(stripeee) === "undefined"){
						//alert("1");
						$("#braintreeaddss").removeAttr('disabled');
						$("#customButton1").removeAttr('disabled');
					}else{
						//alert("2");
						$('#braintreeaddss').attr('disabled', 'disabled');
						$('#customButton1').attr('disabled', 'disabled');
					}
				}
			});
			}}
			return false;
		});
	</script>