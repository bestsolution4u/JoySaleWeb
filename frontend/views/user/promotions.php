<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
  use yii\helpers\ArrayHelper;
 ?>

<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */

// $this->pageTitle=Yii::app()->name . ' - Profile';
// $this->breadcrumbs=array(
// 	'Profile',
// );

?>



<div id="page-container" class="container">
<div class="row">
				<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
					 <ol class="breadcrumb">
						<li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home'); ?></a></li>
						<li><a href="#"><?php echo Yii::t('app','Profile'); ?></a></li>
					 </ol>
				</div>

<?php  $user->userId = $model['userId'];
$user->name = $model['name'];
$user->userImage = $model['userImage'];
$user->mobile_status = $model['mobile_status'];
$user->facebookId = $model['facebookId'];
	  //print_r($model); ?>

	<div class="profile-vertical-tab-section col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?=$this->render('//user/sidebar',['user'=>$user])?> 

					<div class="tab-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
                    <div class="classified-loader showLoad" id="exc-loader" style="width: 60px;"><div class="cssload-loader"></div></div>    

					<div id="promotion_content" class="hideAll">
						<?php echo  $this->render('urgentpromotions',['products'=>$products]); ?>
					</div>
							</div>

</div>
</div>
</div>

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
        $("#promotion_content").removeClass("hideAll");
        $("#exc-loader").removeClass("showLoad");
    };
	function geturgent() {
		$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/geturgent/',
        type: "POST",
        dataType : "html",
        data: {
           
        },
         beforeSend: function(data){    
                $("#promotion_content").hide();          
                $(".classified-loader").show();
                },
        success: function (res) {
            $("#promotion_content").show();
            $(".classified-loader").hide();
           	$('#promotion_content').html(res);
           
        },
    });
	}

	function getad() {
		$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/getad/',
        type: "POST",
        dataType : "html",
        data: {
           
        },
        beforeSend: function(data){    
                $("#promotion_content").hide();          
                $(".classified-loader").show();
                },
        success: function (res) {
            $("#promotion_content").show();
            $(".classified-loader").hide();
            $('#promotion_content').html(res);
           
        },
    });
	}

	function getexpired() {
		$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/getexpired/',
        type: "POST",
        dataType : "html",
        data: {
           
        },
         beforeSend: function(data){    
                $("#promotion_content").hide();          
                $(".classified-loader").show();
                },
        success: function (res) {
            $("#promotion_content").show();
            $(".classified-loader").hide();
            $('#promotion_content').html(res);
           
        },
    });
	}

	
</script>