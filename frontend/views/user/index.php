<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
 ?>
<div id="content" style="min-height: 657px;">

<script>
var offset = 15;
var limit = 15;
</script>

<div class="container profile-page-dev">

			<div class="row">
				<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
					 <ol class="breadcrumb">
						<li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home'); ?></a></li>
						<li><a href="#"><?php echo Yii::t('app','Profile'); ?></a></li>
					 </ol>
				</div>
			</div>
	<div class="row">
				<div class="profile-vertical-tab-section col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 <style>
.file-upload{
	cursor: pointer;
	height: 40px;
	position: absolute;
	left: 94px;
	top: 65px;
	width: 33%;
	opacity: 0;
}
.footer {
    margin-top: 0px !important;
}
</style>
<?=$this->render('//user/sidebar')?> 

<script>
function on_submit() {
	$('#fileupload').submit();
}
</script>
					<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">

					
						<!--Listing-->
						<div id="listing" class=" profile-tab-content tab-pane fade in active col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">

													<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 textleft">
													<?php echo Yii::t('app','My Listing')?>						</div>
														<div id="products" class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<!--product-->
								

                                  <div class="modal-dialog modal-dialog-width">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="payment-decline-status-info-txt decline-center"><img src="https://joysalescript.com/images/empty-tap.jpg"><br><span class="payment-red"><?=Yii::t('app','Sorry...')?></span> <?=Yii::t('app','You have not added any stuff.')?></div>
													<div class="text-align-center col-lg-12 no-hor-padding">
													<?=Html::a(Yii::t('app','Go to add your stuff'), ['products/create'],['class'=>'center-btn payment-promote-btn login-btn'])?>
												</div>
												</div>
											</div>
										</div>
			




							</div>
														
						</div>
					
															
			

						</div>
					</div>
				</div>
			</div>



</div>