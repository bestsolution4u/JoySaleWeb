<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.css" rel="stylesheet">
<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Filtervalues;
use ruskid\nouislider\Slider;

$searchUrl = "";
$categoryUrl = "";

?> 
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
				<div class="slick-container">
						<div class="slick-slider">

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
											echo '
												<a href="'.$banner->bannerurl.'" target="_blank"><img src="'.$imageurl.'" alt="'.$imgName.'"></a>
												';
										} else {
											$imageurl = $path1.$imgName;
											echo '
												<a href="'.$banner->bannerurl.'" target="_blank"><img src="'.$imageurl.'" alt="'.$imgName.'"></a>
												';
										}

									} ?>

			
						</div>
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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
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


	<style>
.banner-image .slick-dots-container {
  width: 5.5rem;
  overflow: hidden;
  display: block;
  padding: 0;
  margin: 0.625rem auto;
  height: 0.875rem;
  position: absolute;
bottom: 10px;
right: 0;
left: 0;
}

.banner-image .slick-dots-container > ul {
  padding: 0;
  display: flex;
  transition: all 0.25s;
  position: relative;
  margin: 0;
  list-style: none;
  transform: translateX(0);
  align-items: center;
  bottom: unset;
  height: 100%;
}

.banner-image .slick-dots-container > ul li {
  width: 0.625rem;
  height: 0.625rem;
  margin: 0 0.25rem;
  background-color: #fff;
  border: none;
  border-radius: 50%;
}

.banner-image .slick-dots-container > ul li button {
  font-size: 0;
  line-height: 0;
  display: block;
  width: 1.25rem;
  height: 1.25rem;
  padding: 0.3125rem;
  cursor: pointer;
  color: transparent;
  border: 0;
  outline: 0;
  background: 0 0;
}

.banner-image .slick-dots-container > ul li.p-small-1,
.banner-image .slick-dots-container > ul li.n-small-1 {
  transform: scale(0.8);
}

.banner-image .slick-dots-container > ul li.slick-active {
  transform: scale(1.3);
  transform-origin: center;
  background: #e40046;
}

.banner-image .slick-dots li button:before {
  display: none;
}
.banner-image .slick-slider img
{
	width: 100%;
	display: inline-block;
}

/* site style */
.classified-menu li a {
	font-size: 16px !important;
}
.no-hor-padding {
padding: 0;
}

		</style>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
		<script src="https://prodev.hitasoft.in/joysalescript/joysalev4.0/demo/js/bootstrap.min.js"></script>

		<script>
		$(document).ready(function() {  
  function setBoundries(slick, state) {
    if (state === 'default') {
      slick.find('ul.slick-dots li').eq(4).addClass('n-small-1');
    }
  }
  });

  // Slick Selector.
  var slickSlider = $('.slick-slider');
  var maxDots = 5;
  var transformXIntervalNext = -18;
  var transformXIntervalPrev = 18;

  slickSlider.on('init', function (event, slick) {
    $(this).find('ul.slick-dots').wrap("<div class='slick-dots-container'></div>");
    $(this).find('ul.slick-dots li').each(function (index) {
      $(this).addClass('dot-index-' + index);
    });
    $(this).find('ul.slick-dots').css('transform', 'translateX(0)');
    setBoundries($(this),'default');
  });

  var transformCount = 0;
  slickSlider.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
    var totalCount = $(this).find('.slick-dots li').length;
    if (totalCount > maxDots) {
      if (nextSlide > currentSlide) {
        if ($(this).find('ul.slick-dots li.dot-index-' + nextSlide).hasClass('n-small-1')) {
          if (!$(this).find('ul.slick-dots li:last-child').hasClass('n-small-1')) {
            transformCount = transformCount + transformXIntervalNext;
            $(this).find('ul.slick-dots li.dot-index-' + nextSlide).removeClass('n-small-1');
            var nextSlidePlusOne = nextSlide + 1;
            $(this).find('ul.slick-dots li.dot-index-' + nextSlidePlusOne).addClass('n-small-1');
            $(this).find('ul.slick-dots').css('transform', 'translateX(' + transformCount + 'px)');
            var pPointer = nextSlide - 3;
            var pPointerMinusOne = pPointer - 1;
            $(this).find('ul.slick-dots li').eq(pPointerMinusOne).removeClass('p-small-1');
            $(this).find('ul.slick-dots li').eq(pPointer).addClass('p-small-1');
          }
        }
      }
      else {
        if ($(this).find('ul.slick-dots li.dot-index-' + nextSlide).hasClass('p-small-1')) {
          if (!$(this).find('ul.slick-dots li:first-child').hasClass('p-small-1')) {
            transformCount = transformCount + transformXIntervalPrev;
            $(this).find('ul.slick-dots li.dot-index-' + nextSlide).removeClass('p-small-1');
            var nextSlidePlusOne = nextSlide - 1;
            $(this).find('ul.slick-dots li.dot-index-' + nextSlidePlusOne).addClass('p-small-1');
            $(this).find('ul.slick-dots').css('transform', 'translateX(' + transformCount + 'px)');
            var nPointer = currentSlide + 3;
            var nPointerMinusOne = nPointer - 1;
            $(this).find('ul.slick-dots li').eq(nPointer).removeClass('n-small-1');
            $(this).find('ul.slick-dots li').eq(nPointerMinusOne).addClass('n-small-1');
          }
        }
      }
    }
  });

  $('.slick-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    focusOnSelect: true,
    infinite: false,
	autoplay: true,
  autoplaySpeed: 1000,
  });
  
  $('button').on('click', function()  {
      $('.slick-slider').slick('slickGoTo', 4);
    // gallery.slick('slickGoTo', parseInt(slideIndex));
  });
//});
		</script>

</div> 


