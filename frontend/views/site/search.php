<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Filtervalues;
use ruskid\nouislider\Slider;

$categoryUrl = "";

?>
<script>
	var offset = <?php echo count($products); ?>; 
	var limit = 32;
	var loadProductCond = []; 
</script>

<?php
	$sitesetting =yii::$app->Myclass->getSitesettings();
?>

<div class="container-fluid">
	<?php 
	if(!empty($category)) {
		$categoryname = yii::$app->Myclass->getCategoryName($category); ?>
		
		<div class="col-md-12 category_button no-hor-padding">
			<?php
			if ($subcats != '' && $subcats != 0) {
				foreach($subcats as $subcat):
					$subactive = "";$subIcon = "";
					$subcategoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category.'/'.$subcat->slug);
					if($subcategory == $subcat->slug) {
						$subactive = "active";

						$categoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category);
						$subIcon = "<i class='fa fa-times-circle'></i> ";
					}
					if(!empty($subcategory)) {
						$subcategoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category.'/'.$subcat->slug);
					}
					if(!empty($category)) {
						$categoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category);
					} 
				endforeach; 
			} ?>
			
			<div class="row">
				<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home');?></a>
						</li>

						<?php if(!empty($categoryname)) { ?>
							<li>
								<a href="<?php echo $categoryUrl; ?>"><?php echo Yii::t('app',$categoryname); ?></a>
							</li>
							<?php if (!empty($subcategory)) {
								$categoryname = yii::$app->Myclass->getCategoryName($subcategory);
								$subcategoryUrll= Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category.'/'.$subcategory);?>
								<li>
									<a href="<?php echo $subcategoryUrll; ?>"><?php echo Yii::t('app',$categoryname); ?></a>
								</li>
								<?php if (!empty($sub_subcategory)) {
								$categoryname = yii::$app->Myclass->getCategoryName($sub_subcategory);
								$sub_subcategoryUrll= Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category.'/'.$subcategory.'/'.$sub_subcategory);?>
								<li>
									<a href="<?php echo $sub_subcategoryUrll; ?>"><?php echo Yii::t('app',$categoryname); ?></a>
								</li>
							<?php }   } 
						} else { ?>
							<li><a href="<?php echo $categoryUrl; ?>"><?php echo Yii::t('app','All Categories')?></a></li>
						<?php } ?>
					</ol>
				</div>
			</div>

			<div class="row">
				<div class="full-horizontal-line col-xs-12 col-sm-12 col-md-12 col-lg-12 "></div>
			</div>
		</div>
	<?php }
	
	if(isset($_GET['search']) && !empty($_GET['search'])) { ?>
		<div class="row">
			<div class="search-result col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
				<div><?php echo Yii::t('app','Search Result')?> <span class="search-result-text">"<?php echo $_GET['search']; ?>"</span></div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
				<div class="full-horizontal-line col-xs-12 col-sm-12 col-md-12 col-lg-12 "></div>
			</div>
		</div>
	<?php }  ?>
</div>

<!-- /div  -->

<div class="container-fluid wholeContainerResp">
	<div style="background-color: rgba(255, 255, 255, 0.5); z-index: 9999; width: 100%; height: 100%; position: fixed; left: 0px; top: 0px; display: none;" id="location-loader" class="classified-loader-old">
		<div style="position: relative; left: 50%; top: 50%;" class="cssload-loader"></div>
	</div>
	
	<?php 
		echo $this->render('searchSidebar',['products'=>$products,'locationReset'=>$locationReset, 'category'=>$category,'subcategory'=>$subcategory,'sub_subcategory'=>$sub_subcategory,'attributes'=>$attributes,'subcats'=>$subcats, 'productcondn'=>$productcondn, 'sitesetting'=>$sitesetting,'third_level'=>$third_level,'sub_subcat' => $sub_subcat]); 

		 
	?>
	<div id="filterTags">
		<div id="advancefilter"> 
		</div>
	</div>
	<div id="products" class="resp-width-slider slider container-fluid col-xs-12 col-sm-12 col-md-9 col-lg-10 no-hor-padding">
		 

		<?php
			$initialLoad = 0;  

		 	echo $this->render('loadresults',['initialLoad'=>$initialLoad, 'products'=>$products,'locationReset'=>$locationReset, 'category'=>$category,'subcategory'=>$subcategory,'loadMore'=>$loadMore, 'productcount'=>$productcount, 'subcats'=>$subcats, 'worldData'=>$worldData,'sub_subcategory'=>$sub_subcategory]); ?> 
	</div>

	


 	<script type="text/javascript" charset="utf-8">

 		distancelimit = '<?php echo $sitesetting->searchList;?>'/2;
      jQuery("#Sliders2").slider({ from: 1, to: <?php echo $sitesetting->searchList;?>, step: 1, dimension: '&nbsp;<?php echo $sitesetting->searchType;?>',
      callback: function (value) {
        getLocationData();
    	}, });
 	</script>

	<script type="text/javascript">  	
		window.onload = function() {
			var location=$('#pac-input').val();
			if(location=="")
			{
				$('.filterTool').hide();
			}
			else
			{
				$('.filterTool').show();
			}
	     	$("#SliderPrice").val("0;10000");
	     	$("#SliderPriceSM").val("0;10000");

		};  
   </script>

 	<script type="text/javascript" charset="utf-8">
	 	$('#flat-slider').slider({
		  orientation: 'horizontal',
		  range:       true,
		  values:      [17,67]
		});
 	</script>

	<style type="text/css">
		.radio
		{
			text-align:left !important;
		}
	</style>


	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
	<script>
			$(function () {

					$('.filters-title').click(function () {
						$(this).siblings('.filters-list').slideToggle('fast')
						 $(this).siblings('.filters-list').css({height: '-480px'});
						})


					})
	</script>

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

				$(document).ready(function() {
		    $("#back2Top").click(function(event) {
		        event.preventDefault();
		        $("html, body").animate({ scrollTop: 0 }, "slow");
		        return false;
		    });

		});

	</script>
</div> 






