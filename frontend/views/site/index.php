<script type="text/javascript">
if (!window.console) {
	window.console = {};
	window.console.error = function() {
		return false;
	};
}
</script>	
<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Filtervalues;
use ruskid\nouislider\Slider;

$searchUrl = "";
$categoryUrl = "";
$basepath1 = Yii::$app->urlManagerfrontEnd->baseUrl; 
?>
<link rel="stylesheet" href="<?php echo $basepath1.'/css/swiper/swiper.css';?>">
<script>
	var offset = <?php echo count($products); ?>;  
	var limit = 32;  
</script>

<script type="text/javascript">
	var adsarray = '<?php echo $adsarray; ?>';  
</script>
 
<style>
	.no-more {
		font-weight: bold;
		padding: 5%;
		text-align: center;
		margin-top: 20px;
	}
	#content {
	    min-height: 0 !important;
	}
</style>

<?php
$sitesetting =yii::$app->Myclass->getSitesettings();

$bannervideo=$sitesetting->bannervideo;
$bannervideoStatus=$sitesetting->bannervideoStatus;
$bannervideoposter=$sitesetting->bannervideoposter;
$bannerText=$sitesetting->bannerText;
$extensionArray=explode(".",$bannervideo);
$path = Yii::$app->urlManagerfrontEnd->createUrl('/media/banners/videos/').'/';
$path1 = Yii::$app->urlManagerfrontEnd->createUrl('/media/banners/').'/';



// Video Banner Starts
if(!isset($_GET['search']) && empty($category) && empty($subcategory))
{ ?>
	<?php if($bannervideo!="" && $bannervideoStatus==1) {
		$slider="display: none;";
	?>
		<div class="slider-imag">
			<div class="respSell">SELL</div>
			<div class="vide-slider-imag">
				<div class="img-video">
					<video id="intro-video" src="<?php echo $path.$bannervideo;?>" type="video/<?php echo $extensionArray[1];?>" class="video-cover" preload="" loop="loop" muted="muted" autoplay="autoplay" poster="<?php //echo Yii::$app->urlManager->createAbsoluteUrl('/media/banners/videos/'.$bannervideoposter);?>">
					</video> 
				</div>

				<?php $footerSettings = yii::$app->Myclass->getFooterSettings();?>
				<div class="img-slide-contetnt">
					<div class="img-text txt-white-color text-align-center">
						<h1 class="bold"><?php echo $bannerText;?></h1>

						<ul class="text-link">
							<?php if(isset($footerSettings['appLinks']['android'])) { ?>
								<li>
									<a class="sendapp_link ios_link" target="_blank"  href="<?php echo $footerSettings['appLinks']['android']; ?>" target="_self"><img src="images/google-play-download-badge.svg" alt="Android" width="145" height="50"></a>
								</li>
							<?php }

							if(isset($footerSettings['appLinks']['ios'])) { ?>
								<li>
									<a class="sendapp_link" target="_blank" href="<?php echo $footerSettings['appLinks']['ios']; ?>" target="_self"><img src="images/app-store-download-badge.svg" alt="Ios" width="145" height="50"></a>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	<?php } else {
		$slider="display: block;";
	}
	// Video Banner Ends.


	// Slider Banner Starts
	if(isset($sitesetting->bannerstatus) && $sitesetting->bannerstatus == "1" && !empty($banners))
	{ ?>

		<?php if (!empty(Yii::$app->user->id)) { ?>
			<div class="respSell">
				<?= Html::a(Yii::t('app', 'SELL'), ['products/create'], ['style' => 'color:#ffffff !important;']) ?>
			</div> 
		<?php } else { ?>
			<div data-toggle="modal" data-target="#login-modal" class="respSell">
				<?php echo Yii::t('app', 'SELL'); ?>
			</div>
		<?php } ?>

			<div class="slider-container"> 
				<div class="container-fluid">
					<div class="row">
						


		        <div class="banner-image" style="<?php echo $slider;?>">
				<div class="swiper-container">
						<div class="swiper-wrapper">

					<?php
				  					foreach ($banners as $key => $banner) {
				  						$deviceModel = yii::$app->Myclass->getDeviceName(); //pc
				  
				  						if($deviceModel=='pc') {
				  							$imgName=$banner->bannerimage; 
				  						} else { 
				  							$imgName=$banner->appbannerimage; 
				  						}

									  	if($key == 0) {
									  		$imageurl = $path1.$imgName;
											echo '<div class="swiper-slide">
												<a href="'.$banner->bannerurl.'" target="_blank"><img src="'.$imageurl.'" alt="'.$imgName.'"></a></div>
												';
										} else {
											$imageurl = $path1.$imgName;
											echo '<div class="swiper-slide">
												<a href="'.$banner->bannerurl.'" target="_blank"><img src="'.$imageurl.'" alt="'.$imgName.'"></a></div>
												';
										}

									} ?>

			
						</div>    <div class="swiper-pagination"></div>
        </div>
      </div>
					</div>
				</div>
			</div>
		<?php
	}
} 

	// Slider Banner Ends
?> 

<div class="container-fluid wholeContainerResp">
	
	<div id="products" class="slider container-fluid col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"> 
		<?php echo $this->render('indexload', [ 
                'adsProducts'=>$adsProducts,
                'kilometer'=>$kilometer, 'products'=>$products,
                'searchList'=>$searchList,'searchType'=>$searchType, 'lat' => $lat,'lon' => $lon,'adsarray' => $adsarray, 'initialLoad' => $initialLoad
            ]);  ?> 
	</div>
</div>  


	<script>

		$(window).scroll(function() {    
		    if (isScrolledIntoView('.footer') )
		      $('.respSell').css('visibility','hidden');      
		    else{
		      $('.respSell').css('visibility','visible'); 
		    }
		});

		function isScrolledIntoView(elem)
		{
		    var docViewTop = $(window).scrollTop();    
		    var docViewBottom = docViewTop + $(window).height();
		    var elemTop = $(elem).offset().top;    
		    var elemBottom = elemTop + $(elem).height();   
		    return ((elemTop < docViewBottom));
		}

	</script>

   <script src="<?php echo $basepath1.'/js/swiper/swiper.min.js';?>"></script>
    <script>
      var swiper = new Swiper('.banner-image .swiper-container', {
        autoplay:true,
        autoplaySpeed: 1000,  
        pagination: {
          el: '.swiper-pagination',
          dynamicBullets: true,
          clickable:true,
        },
      });
    </script>




	

	


	<style>

.banner-image .swiper-container img
{
	width: 100%;
	display: inline-block;
}




		</style>
