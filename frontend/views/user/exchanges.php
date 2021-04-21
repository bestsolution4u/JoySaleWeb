<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
 use common\models\Photos;
use yii\widgets\LinkPager;
use conquer\toastr\ToastrWidget;
 ?>
<style>
.no-more-exchanges {
    display: inline-table;
    float: right;
    margin-bottom: 0;
    margin-right: 5px;
    padding: 0;
}
</style>
<?php  $user->userId = $model['userId'];
$user->name = $model['name'];
$user->userImage = $model['userImage'];
$user->mobile_status = $model['mobile_status'];
$user->facebookId = $model['facebookId'];
 ?>
<?php if(empty($exchanges)){ 
	$empty_tap = " empty-tap ";
}else{
	$empty_tap = "";
	} ?>
<div class="container profile-page-dev">
<div class="row">	
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
    <?php  endif; ?>

     <?php if(Yii::$app->session->hasFlash('warning')): ?>
     <?=ToastrWidget::widget(['type' => 'warning', 'message'=>Yii::$app->session->getFlash('warning'),
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
				<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
					 <ol class="breadcrumb">
						<li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home'); ?></a></li>
						<li><a href="#"><?php echo Yii::t('app','Profile'); ?></a></li>					 
					 </ol>			
				</div>
				
			</div>
<div class="row page-container">
	<div class="container exchange-property-container profile-vertical-tab-section">
		
		<?=$this->render('//user/sidebar',['user'=>$user])?> 

	

			<div class="item-properties tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
				<div class="classified-loader showLoad" id="exc-loader" style="width: 60px;"><div class="cssload-loader"></div></div>	
			<div id="exchange_content" class="hideAll">
			
			<?php echo $this->render('getexchanges',['model'=>$model,'exchanges'=>$exchanges,
				'type'=>$type,'userId'=>$userId,'user'=>$user,'pages'=>$pages]); ?>
			</div>
			
		</div></div></div></div>

	<div id="popup_container" style="display: none; opacity: 0;">
	<div id="review-user-popup" style="display: none;" class="popup ly-title update review-me-popup">
			<p class="ltit"><?php echo Yii::t('app','Users Review and Rating'); ?></p>
			<button type="button" class="ly-close" id="btn-browses">x</button>
			
			<div class="review-body-section">
				<div class="review-user-rating">
				  <div class="rating-title"><?php echo Yii::t('app','Give your Ratings');?>: </div>
					<i class="fa fa-2x  fa-star-o static-rating rating one" id="rateone" onclick="ratingClick('1');"></i>
					<i class="fa fa-2x  fa-star-o static-rating rating two" id="ratetwo" onclick="ratingClick('2');"></i>
					<i class="fa fa-2x  fa-star-o static-rating rating three" id="ratethree" onclick="ratingClick('3');"></i>
					<i class="fa fa-2x  fa-star-o static-rating rating four" id="ratefour" onclick="ratingClick('4');"></i>
					<i class="fa fa-2x  fa-star-o static-rating rating five" id="ratefive" onclick="ratingClick('5');"></i>
					<span class="current-rate">0</span> <?php echo Yii::t('app','Out of 5'); ?>
					<input type="hidden" id="rateval">
					<input type="hidden" id="reviewType" value="exchange">
					
				</div>
				
				<div class="review-user-textarea">
				<div class="rating-title"><?php echo Yii::t('app','Write your Review');?>: </div>
					<textarea class="review-textarea" rows="5" cols="48" id="contact-textarea" ></textarea>
					<div class="review-error error"></div>
				</div>
				
				<div class="review-btn-area">
				
					<div class="cancel-button close-contact"> <?php echo Yii::t('app','Cancel')?></div>
					<div onclick="saveReviewPopup()" class="send-button"> <?php echo Yii::t('app','Send'); ?> </div>
								
				</div>
			</div>
			<input type="hidden" class="review-sender" value="<?php echo Yii::$app->user->id; ?>" />
			<input type="hidden" class="review-receiver" value="" />
			<input type="hidden" class="exchangeid" value=""/>
	</div>
	</div></div>


<style type="text/css">
    .hideAll  {
        visibility:hidden;
     }
     .showLoad{
     	display: block;
     }
</style>

<script type="text/javascript">

   window.onload = function() {
        $("#exchange_content").removeClass("hideAll");
        $("#exc-loader").removeClass("showLoad");
    };

/*	window.onload = function() {
 $("#exchange_content").show();			
				$(".classified-loader").hide();
};


$(document).ready(function() {
   $("#exchange_content").hide();			
				$(".classified-loader").show();
});*/
	function getoutgoing() {
		$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/getexchanges?type=outgoing',
        type: "POST",
        dataType : "html",
        data: {
           
        },
         beforeSend: function(data){	
         		$("#exchange_content").hide();			
				$(".classified-loader").show();
				},
        success: function (res) {
        	$("#exchange_content").show();
        	$(".classified-loader").hide();
           	$('#exchange_content').html(res);
           
        },
    });
	}
	function getincoming() {
		$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/getexchanges?type=incoming',
        type: "POST",
        dataType : "html",
        data: {
           
        },
          beforeSend: function(data){	
         		$("#exchange_content").hide();			
				$(".classified-loader").show();
				},
        success: function (res) {
        
          $("#exchange_content").show();
        	$(".classified-loader").hide();
           	$('#exchange_content').html(res);
           
        },
    });
	}
	function getsuccess() {
		$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/getexchanges?type=success',
        type: "POST",
        dataType : "html",
        data: {
           
        },
          beforeSend: function(data){	
         		$("#exchange_content").hide();			
				$(".classified-loader").show();
				},
        success: function (res) {
        
            $("#exchange_content").show();
        	$(".classified-loader").hide();
           	$('#exchange_content').html(res);
           
        },
    });
	}
	function getfailed() {
		$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/getexchanges?type=failed',
        type: "POST",
        dataType : "html",
        data: {
           
        },
          beforeSend: function(data){	
         		$("#exchange_content").hide();			
				$(".classified-loader").show();
				},
        success: function (res) {
        	 $("#exchange_content").show();
        	$(".classified-loader").hide();
           	$('#exchange_content').html(res);
           
        },
    });
	}
</script>