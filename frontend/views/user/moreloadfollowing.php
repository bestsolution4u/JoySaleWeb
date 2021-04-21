<?php
 use yii\helpers\Html;
 use yii\helpers\Url;

?>
<?php 

$colorArray = array('50405d', 'f1ed6e', 'bada55', '5eaba6', 'ab5e63', '5eab86', 'deba5e', 'de5e82',
		'5e82de');
$count=count($followinglist);

if($count > 0) {
foreach($followinglist as $followinglistt):
	$followersdet = yii::$app->Myclass->getUserDetailss($followinglistt->follow_userId);
	$productdet = yii::$app->Myclass->getUserProductDetails($followinglistt->follow_userId, 4);
	
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
			<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['user/profiles','id'=>yii::$app->Myclass->safe_b64encode($followersdet['userId'].'-'.rand(0,999))]); ?>"><div class="followers-prof-pic-1" style="background-image: url('<?php echo Yii::$app->urlManager->createAbsoluteUrl($img); ?>');"></div></a>

		</div>

		<div class="followers-info-cnt col-xs-12 col-sm-7 col-md-7 col-lg-7 no-hor-padding">

				


			<div class="follower-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo $followersdet['name']; ?>
			</div>

					
			<div class="un/follow-button col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
			<?php if(Yii::$app->user->id) { if(Yii::$app->user->id != $followersdet['userId']) { ?>
			<?php if (!in_array($followersdet['userId'], $followingIds)) { ?>
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


<?php } ?>		