<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
 use common\models\Followers; 
?>
<?php

	if(empty($followerlist))
		$fempty_tap = " empty-tap ";
	else
		$fempty_tap = "";
?>
<script>
var offset = 15;
var limit = 15;
</script>
<!--Recent activity-->
						<div id="recent-activity" class="profile-tab-content tab-pane fade in active col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $fempty_tap; ?>">

							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<ul class="recent-activities-tab nav nav-tabs">
								  <li class="" id="like_active">
								  		<a href="javascript:void(0)" onclick="return getliked('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)); ?>')"> <?php echo Yii::t('app','Liked') ?>  </a>
								  </li>
								  <li class="active" id="follow_active">
								 		<a id="followerclk" href="javascript:void(0)" onclick="return getfollower('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)); ?>')"> <?php echo Yii::t('app','Followers') ?> </a>
								  		
								  </li>
								  <li class="" id="following_active">
								  		<a id="followingclk" href="javascript:void(0)" onclick="return getfollowing('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)); ?>')"> <?php echo Yii::t('app','Followings') ?> </a>
								  </li>
								</ul>

							</div>
							<div class="recent-activities-tab-content tab-content">
								<!--Followers-->

								<div id="followersss" class="tab-pane fade in active">
									<div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

										<?php if(!empty($followerlist)) { ?>
										<div id="follower" style="margin-top:0px !important;">

<?php
$colorArray = array('50405d', 'f1ed6e', 'bada55', '5eaba6', 'ab5e63', '5eab86', 'deba5e', 'de5e82',
		'5e82de');
$count=count($followerlist);


if($count > 0) {
foreach($followerlist as $followerlistt):

	 $id=$followerlistt->userId;
	
$productdet = yii::$app->Myclass->getUserProductDetails($followerlistt->userId, 4);
$followersdet = yii::$app->Myclass->getUserDetailss($id);

$image = $followersdet['userImage'];

if(!empty($image)) {

	
		$img = 'profile/'.$image;
	
} else {
	$img = 'media/logo/'.yii::$app->Myclass->getDefaultUser();
}

?>


<div class="profile-listing-product product-padding col-xs-12 col-sm-6 col-md-4 col-lg-4" style="">

	<div class="followers-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		
		<div class="followers-prof-pic-cnt col-xs-12 col-sm-5 col-md-5 col-lg-5 no-hor-padding">
			<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/profiles','id'=>yii::$app->Myclass->safe_b64encode($followersdet['userId'].'-'.rand(0,999))]); ?>">
			<div class="followers-prof-pic-1" style="background-image: url('<?php echo Yii::$app->urlManager->createAbsoluteUrl($img); ?>');"></div></a>

		</div>

		<div class="followers-info-cnt col-xs-12 col-sm-7 col-md-7 col-lg-7 no-hor-padding">

				


			<div class="follower-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo $followersdet['name']; ?>
			</div>	
			
			<div class="un/follow-button col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<?php if(Yii::$app->user->id) { if(Yii::$app->user->id != $followersdet['userId']) {
					$follower = Followers::find()->where(['userId'=>Yii::$app->user->id])->all();
		         	$followerIds = array();
					if(!empty($follower)) {
						foreach($follower as $follower):
							$loguserfollowerIds[] = $follower->follow_userId;
						endforeach;
					}
			 ?>
			<?php if (!in_array($followersdet['userId'], $loguserfollowerIds)) { ?>
				<div class="unfollow-btn primary-bg-color txt-white-color border-radius-5 text-align-center col-xs-12 col-sm-9 col-md-9 col-lg-9 no-hor-padding" id = "follow<?php echo $followersdet['userId']; ?>" onclick="getfollows(<?php echo $followersdet['userId']; ?>)">
					<?php echo Yii::t('app','Follow'); ?>
				</div>
				<?php }else { ?>
				<div class="unfollow-btn primary-bg-color txt-white-color border-radius-5 text-align-center col-xs-12 col-sm-9 col-md-9 col-lg-9 no-hor-padding" id = "follow<?php echo $followersdet['userId']; ?>" onclick="deletefollows(<?php echo $followersdet['userId']; ?>)"><?php echo Yii::t('app','Following'); ?>
				</div>
			<?php } } }?>
			</div>
		</div>
 	</div>
</div>
			<?php 
			endforeach; ?>

<?php }?>								
							
										</div>
										<div class="no-more"></div>
									<?php if(count($followerlist) >= 15) {
										 ?>
									<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="follower-loader classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
									<a class="loadmorenow load">
									<div class="load-more-icon" onclick="load_more_followers('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)) ?>')"></div>
									<div class="load-more-txt"><?php echo Yii::t('app','Load More'); ?></div>
							 	</a>
									</div>
										
										<?php  } ?>
										<?php }else {?>
											<div class="modal-dialog modal-dialog-width">
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<div class="payment-decline-status-info-txt decline-center" ><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','Yet no follower are here'); echo "."; ?></div>
														</div>
													</div>
												</div>
										<?php } ?>
									</div>
								</div>
								
							
							</div>
						</div>
		


						</div>
<script type="text/javascript">
	
function load_more_followers(id)
{

	$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/moreloadfollower/',
        type: "POST",
        dataType : "html",
        data: {
           "limit": limit, "offset": offset, "id" : id
        },
        beforeSend: function(data){
						$(".follower-loader").show();
										$(".load").hide();
				},
        success: function (response) {

						$(".follower-loader").hide();
										$(".load").show();
								         var output = response.trim();

												if (output) {
													offset = offset + limit;
										 $("#follower").append(output);
												} else {

				            $(".load").html(yii.t('app',"No More Followers"));
						   

												}
        },
    });
    
}
</script>