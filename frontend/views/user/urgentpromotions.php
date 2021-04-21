<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
  use yii\helpers\ArrayHelper;
 ?>
 <script>
var offset = 8;
var limit = 8;
</script>
<?php

	if(count($products) == 0)
		$empty_tap = " empty-tap ";
	else
		$empty_tap = "";
?>

<div id="promotions" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in <?php echo $empty_tap; ?>">
							<div class="promotion-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="promotion-content">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							

			<ul class="recent-activities-tab nav nav-tabs">
			<li class="active">
								  		<a href="javascript:void(0)" onclick="return geturgent()"> <?php echo Yii::t('app','Urgent'); ?>  </a>
								  </li>
								  <li class="">
								 		<a href="javascript:void(0)" onclick="return getad()"> <?php echo Yii::t('app','AD'); ?> </a>
								  		
								  </li>
								  <li class="">
								  		<a href="javascript:void(0)" onclick="return getexpired()"> <?php echo Yii::t('app','Expired'); ?> </a>
								  </li>

		</ul>

								</div>
								<div class="recent-activities-tab-content tab-content">
									<div id="urgent" class="tab-pane fade in active">
									<?php if(count($products) != 0) { ?>
										<div id="products" class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<!--product-->
											<?php echo $this->render('loadpromotions',['products'=>$products]); ?>
										</div>
									<?php } else { ?>
											<div>
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<div class="payment-decline-status-info-txt" style="margin: 8% auto 0;"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...').' ';?></span><?php echo Yii::t('app','Yet no product are here'); ?>.</div>  
														</div>
													</div>
												</div>
									<?php }  ?>
									</div>
.

								</div>

								<?php if(count($products) >= 8) { ?>
							<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div  class="classified-loader urgentpromotion-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
									<a class="loadmorenow load">
									<div class="load-more-icon" onclick="load_more()"></div>
									<div class="load-more-txt"><?=Yii::t('app','Load More')?></div> 
								<?php if(count($products) >= 3) {
								if(Yii::$app->controller->action->id == 'promotions') { ?>
								<?php //echo CHtml::ajaxButton('Load More', array('promotions','id' =>yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999))),
								// array(
								// 'data'=> 'js:{"limit": limit, "offset": offset}',
								// 'beforeSend'=>'js:function(data){
								// 	$(".classified-loader").show();
								// 	$(".load").hide();
								// }',
								// 'success' => 'js:function(response){
								// 	$(".classified-loader").hide();
								// 	$(".load").show();
						  //       		var output = response.trim();
								// if (output) {
								// 	offset = limit + offset;
								// 	$("#products").append(output);
								// } else {
								//        $(".load").html(Yii.t("app","No More Products"));
								//        //$(".load").hide();

								// }
								// }',
								// )
								// ); ?>
								 <?php } 
							} ?>
							</a>
							</div>


						<?php } ?>
							</div>

							<div class="promotion-details col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="promotion-details">
								<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<span><?php echo Yii::t('app','Promotion Details'); ?></span>
									<div class="exchange-back-link pull-right col-xs-3 col-sm-3 col-md-3 col-lg-3 no-hor-padding"><a href="#" onclick="switchVisible_promotionback();" id="element1"><?php echo Yii::t('app','Back'); ?></a></div>
								</div>
								<div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<!--row 1-->
									<div class="promotions-details-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									</div>
								</div>
							</div>
						</div>



	</div>
	</div>

	<script type="text/javascript">

	
function load_more()
{

	$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/promotions/',
        type: "POST",
        dataType : "html",
        data: {
           "limit": limit, "offset": offset
        },
        beforeSend: function(data){

				$(".urgentpromotion-loader").show();
				$(".load").hide();
				},
        success: function (res) {
        	
            $(".urgentpromotion-loader").hide();
				$(".load").show();
		        var output = res.trim();
						if (output) {
							offset = offset + limit;
				 $("#products").append(output);
						} else {
				        $(".load").html(yii.t('app',"No More Products"));
				        //$(".load").hide();

						}
        },
    });
    
}
</script>
