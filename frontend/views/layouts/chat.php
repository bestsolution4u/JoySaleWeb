
<?php
use yii\helpers\Html;
use frontend\assets\StyleAsset;
use frontend\models\SignupForm;
use yii\helpers\Url;
use conquer\toastr\ToastrWidget;

$signupModel = new SignupForm();

$siteSettings = yii::$app->Myclass->getSitesettings();
Yii::$app->i18nJs;
StyleAsset::register($this);

$baseUrl = Yii::$app->request->baseUrl;
$urll = Yii::$app->getUrlManager()->getBaseUrl();
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php $metaInformation = Yii::$app->Myclass->getMetaData();//echo "<pre>";print_r($metaInformation); ?>
	<meta name="description"
		content="<?php echo isset($this->fbdescription) ? strip_tags($this->fbdescription) : strip_tags($metaInformation['description']); ?>" />
	<meta name="keywords" content="<?php echo $metaInformation['metaKeywords']; ?>" />
	<meta property="og:site_name" content="<?php echo $metaInformation['sitename']; ?>"/>
	<meta property="og:title" content="<?php echo isset($this->fbtitle) ? $this->fbtitle : $metaInformation['title']; ?>" />
	<?php //} ?>
	<meta property="og:type" content="products" />
	<meta property="og:url"
		content="<?php echo Yii::$app->request->hostInfo . Yii::$app->request->url; ?>" />
		<?php if (isset($this->fbimg)) { ?>
	<meta property="og:image" content="<?php echo $this->fbimg; ?>" />
	<meta name="twitter:image" content="<?php echo $this->fbimg; ?>">
	<meta itemprop="image" content="<?php echo $this->fbimg; ?>">
	<?php 
} //if(isset($this->fbdescription)) ?>
	<meta property="og:description"
		content="<?php echo isset($this->fbdescription) ? $this->fbdescription : $metaInformation['description']; ?>" />


		<!-- For Twitter meta values -->
	<meta name="twitter:title" content="<?php echo Html::encode($this->title); ?>">
	<meta name="twitter:description" content="<?php echo isset($this->fbdescription) ? $this->fbdescription : $metaInformation['description']; ?>">
	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="<?php echo $metaInformation['sitename']; ?>">

	<!-- For Google+ meta values -->
	<meta itemprop="name" content="<?php echo $metaInformation['sitename']; ?>">
	<meta itemprop="description" content="<?php echo isset($this->fbdescription) ? $this->fbdescription : $metaInformation['description']; ?>">
    <?= Html::csrfMetaTags() ?>
    <link rel="icon" href="<?php echo Yii::$app->request->baseUrl; ?>/media/logo/<?= $siteSettings['favicon'] ?>">
	<title><?php echo Html::encode(isset($this->fbtitle) ? $metaInformation['sitename'] . " | " . $this->fbtitle : $metaInformation['sitename'] . " | " . $metaInformation['title']); ?></title>
	<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>

<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery/1.9.1/jquery-1.9.1.js"></script>
  <script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/jquery/1.12.1/jquery-ui.js"></script>
<script src="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/js/mousewheel.js"></script>


<script>
var reload = 0;
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
 <link rel="stylesheet" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/css/jquery/1.12.1/jquery-ui.css">
 <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>
 <?php $sitePaymentModes = yii::$app->Myclass->getSitePaymentModes(); ?>
<div id="wrapper">
<aside data-pushbar-id="left" class="pushbar from_left">
<div class="sideMenu">
<div class="classified-mobile-Category-catgr">Category
				<button class="close push_right margin-right-25" type="button" data-pushbar-close>×</button>
			</div>
			<!-- menu start-->
			<script>
//This baseUrl is used in front.js and nodeClient.js
var baseUrl = "<?php echo $urll; ?>";
</script>


<?php if (!empty(Yii::$app->user->id)) { ?>
			<div id="sidebar-wrapper" class="sidebar-wraper">
		<?php 
} else { ?>
			<div id="sidebar-wrapper">
		<?php 
} ?>
			

			<div class="sidebar-menu-listng">
				<ul class="nav navbar-nav sidebar-nav d-flex flex-column">
				<!-- Hot fixes -->
			
				
				<!--	<li class="dropdown"> 
					<a class="dropdown-toggle bold joysale-for-sale disabled" data-toggle="dropdown" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/allcategories'); ?>" style="background:url(<?php echo Yii::$app->urlManager->createAbsoluteUrl('admin/categories/resized/70/allcategories.png'); ?>) no-repeat scroll left center / 32px auto; " ><?php echo Yii::t('app', 'All Categories'); ?></a>
										  	</li>-!>
					<!-- Hot fixes -->
				<?php $categorypriority = yii::$app->Myclass->getCategoryPriority(); ?>

						<?php foreach ($categorypriority as $key => $category) :
						if ($category != "empty") {
								//$getcaname =   yii::$app->Myclass->getCatName($category);
						$getcatdet = yii::$app->Myclass->getCatDetails($category);
						$getcatimage = yii::$app->Myclass->getCatImage($category);
						$subCategory = yii::$app->Myclass->getSubCategory($category);

						?>
						<!-- mobile category view-->
						<li class="dropdown  main-mobile-menu">
							<?php if (!empty($subCategory)) { ?>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"  style="background:url(<?php echo Yii::$app->urlManagerBackEnd->baseUrl . '/uploads/' . $getcatimage; ?>) no-repeat scroll 18px 17px / 32px auto; " >
							<?php 
					} else { ?>
						<a class="dropdown-toggle" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/' . $getcatdet->slug); ?>" style="background:url(<?php echo Yii::$app->urlManagerBackEnd->baseUrl . '/uploads/' . $getcatimage; ?>) no-repeat scroll 18px 17px / 32px auto; " >
						<?php 
				} ?>
							<span><?php echo Yii::t('app', $getcatdet->name); ?></span>
							<?php if (!empty($subCategory)) { ?>
							<i class="angle-down"></i>
							<?php 
					} ?>
						</a>
						
											<?php if (!empty($subCategory)) { ?>
												<ul class="dropdown-menu mainmenu">
													<?php foreach ($subCategory as $key => $subCategory) :
														$subCatdet = yii::$app->Myclass->getCatDetails($key);
														$childCategory = Yii::$app->Myclass->getSubCategory($subCatdet->categoryId);  
													?>
													<?php if(count($childCategory) > 0){ ?>
														<li>
															<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/' . $getcatdet->slug . '/' . $subCatdet->slug); ?>"><div class="downarrow">
														<span><?php echo Yii::t('app', $subCategory); ?> </span></div></a>

														<ul class="submenu">
												


																<?php
																				foreach ($childCategory as $key => $childCategory) :

																	$childCatdet = Yii::$app->Myclass->getCatDetails($key);
																	
																
																	?>
													
														<li><a  href="<?php echo $baseUrl . '/category/' . $getcatdet->slug . '/' . $subCatdet->slug. '/'.$childCatdet->slug; ?>"><?php echo Yii::t('app', $childCategory); ?></a></li>
												
														<?php endforeach; ?>
															<li><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/' . $getcatdet->slug . '/' . $subCatdet->slug); ?>"><?php echo Yii::t('app','View all');?></a></li>
												</ul>

														</li>
													<?php } else{ ?>
														<li><a
													href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/' . $getcatdet->slug . '/' . $subCatdet->slug); ?>"><?php echo Yii::t('app', $subCategory); ?> </a></li>
													<?php } ?>
													<?php endforeach; ?>
													<li><a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/' . $getcatdet->slug); ?>"><?php echo Yii::t('app','View all');?></a></li>
												</ul>
											<?php } ?>
					  	</li>
					  	<!-- mobile category view end-->
						<?php 
				}
				endforeach; ?>
				</ul>

			</div>

			<?php if (!empty(Yii::$app->user->id)) { ?>
	<div class="mobile-user-area flex-column">
								<?= Html::a(Yii::t('app', 'Sell your stuff'), ['products/create'], ['class' => 'classified-stuff-mob']) ?>
								<?= Html::a(Yii::t('app', 'Profile'), ['/user/profiles'], ['class' => 'classified-account']) ?>


								<?php if ($siteSettings['promotionStatus'] == 1) { ?>
									<?= Html::a(Yii::t('app', 'My Promotions'), ['user/promotions', 'type' => 'urgent'], ['class' => 'classified-exchange']) ?>
									<?php 
								} ?>


								<?php if ($sitePaymentModes['exchangePaymentMode'] == 1) { ?>
									<?= Html::a(Yii::t('app', 'My Exchanges'), ['user/exchanges', 'type' => 'incoming'], ['class' => 'classified-exchange']) ?>
									<?php 
								} ?>

								<?php if ($sitePaymentModes['buynowPaymentMode'] == 1) { ?>
									<?= Html::a(Yii::t('app', 'My Orders and Sales'), ['buynow/orders', 'type' => 'orders'], ['class' => 'classified-exchange']) ?>
									<?php 
								} ?>
								<?= Html::a(Yii::t('app', 'Logout'), ['site/logout'], ['class' => 'classified-logout']) ?>

							</div>
			<?php 
	} else { ?>
			<div class="mobile-user-area">
		<?php /* Html::a(Yii::t('app','Login'), ['site/login'], ['class'=>'classified-login primary-bg-color text-align-center border-radius-5'])?>&nbsp;&nbsp;<?=Html::a(Yii::t('app','Sign up'), ['site/signup'],['class'=>'classified-signup txt-white-color text-align-center border-radius-5']) */ ?>
				 
		<div data-toggle="modal" data-target="#login-modal" class="primary-bg-color txt-white-color border-radius-5"><?php echo Yii::t('app', 'Login'); ?></div>
		<div data-toggle="modal" data-target="#signup-modal" class="classified-signup  txt-white-color border-radius-5"><?php echo Yii::t('app', 'Sign up'); ?></div>
			</div> 
			<?php 
	} ?>
</div>
		</div>

</div>
	</aside>



</div>

  <?= $this->render('//site/header', ['signupModel' => $signupModel]) ?> 

<!--Login modal-->

<!--E O signup modal-->
    <!--Forgot password-->

<div class="modal fade" id="forgot-password-modal" role="dialog">
<div class="modal-dialog modal-dialog-width">
<div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<h2 class="forgot-header-text">Forgot Password</h2>
<button data-dismiss="modal" class="close login-close" type="button">×</button>
<p class="forgot-sub-header-text">Enter your email address and we'll send you a link to reset your password.</p>
</div>

    <div class="forgot-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

        <div class="forgot-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
            <div class="forgot-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                <div class="forgot-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                <form onsubmit="return validforgot()" id="forgetpassword-form" action="/forgotpassword" method="post">									<input class="forgetpasswords popup-input forget-input" placeholder="Enter your email address" name="Users[email]" id="Users_email" type="text" maxlength="150" />									<div class="errorMessage" id="Users_emails_em_" style="display:none"></div>									<input class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding forgot-btn" style="margin-top:10px;" type="submit" name="yt3" value="Reset Password" />									</form>									</div>
            </div>

        </div>



</div>
</div>
</div>


<div class="classified-search-bar-bg">
<div class="container-fluid">
<div class="app-responsive-adjust"></div>


<div class="classified-search-bar-mobile col-xs-12 col-sm-12 col-md-12 no-hor-padding form-group search-input-container">

<form role="form" class="navbar-form- navbar-left- search-form" style="padding-left: 0;" action="<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>"
    method="get">
    <a href="" class="classified-icon-mobile input-search" data-toggle="modal" data-target="#search-mobile-cal">Search products</a>
</form>
</div>



</div>
</div>



<div class="classified-menu" >
<div class="container-fluid">
<div class="row" style="height: 64px;"></div>
<div class="row">


 <div class="gnbar" style="display: none;">
                        <button id="gnbar__toggle--nav" class="gnbar__toggle gnbar__toggle--nav animated-hamburger animated-hamburger--skewed"
                            aria-label="Toggle sitewide navigation">
                            <span class="animated-hamburger__child"></span>
                        </button>
                        <button id="gnbar__toggle--search" class="gnbar__toggle gnbar__toggle--search animated-search"
                            aria-label="Toggle sitewide search"></button>
                    </div>
                    <div class="gnbar__placeholder hide-on-desktop"></div>

                    <nav id="gn" class="gn global-dropdown">
                        <div class="gn__group gn__group--secondary">
                            <button id="gn__label--search"></button>
                        </div>
                    </nav>
                    <div class="global-dropdown__overlay"></div>
                    <div class="gs global-dropdown"></div>
                    <nav id="sn-bar" class="sn-bar sn-bar--transparent sn-bar--scrollable">
                        <div id="sn-bar__outer-wrap" class="sn-bar__outer-wrap">
                            <div id="sn-bar__heading" class="sn-bar__heading"></div>
                            <ul id="sn-bar__inner-wrap" class="sn-bar__inner-wrap">
                                <li class="sn-bar__item sn-bar__menu-item sn-bar__menu-item--has-children" tabindex="0">
                                    <span class="sn-bar__link sn-bar__link--has-dropdown">
                                        <a class="dropdown-toggle bold classified-for-sale disabled" data-toggle="dropdown"
                                            href="<?php echo $baseUrl . '/category/allcategories'; ?>" style="background:url(<?php echo $baseUrl . '/admin/categories/resized/70/allcategories.png'; ?>" style="background:url(/joysale/admin/categories/resized/70/allcategories.png) no-repeat scroll left center / 32px auto; ">
                                            <?php echo Yii::t('app', 'All Categories'); ?></a>

                                    </span>
                                </li>

								<?php $categorypriority = Yii::$app->Myclass->getCategoryPriority(); ?>


<?php 
	//echo '<pre>'; print_r($categorypriority); exit;
foreach ($categorypriority as $key => $category) :

if ($category != "empty") {


	
$getcatdet = Yii::$app->Myclass->getCatDetails($category);
$getcatimage = Yii::$app->Myclass->getCatImage($category);
$subCategory = Yii::$app->Myclass->getSubCategory($category);
?>
                                <li class="sn-bar__item sn-bar__menu-item sn-bar__menu-item--has-children" tabindex="0">
                                    <span class="sn-bar__link sn-bar__link--has-dropdown">
                                        <a class="dropdown-toggle bold classified-for-sale disabled" data-toggle="dropdown"
                                            href="<?php echo $baseUrl . '/category/' . $getcatdet->slug; ?>" style="background:url(<?php echo Yii::$app->urlManagerBackEnd->createUrl('/uploads/' . $getcatimage); ?>) no-repeat scroll left center / 32px auto; "><?php echo Yii::t('app', $getcatdet->name); ?></a>
                                    </span>
										<?php if (!empty($subCategory)) { ?>
											<ul class="sn-bar__dropdown dropdown-menu classified-dropdown-submenu">
												<?php foreach ($subCategory as $key => $subCategory) :

													$subCatdet = Yii::$app->Myclass->getCatDetails($key);
													$childCategory = Yii::$app->Myclass->getSubCategory($subCatdet->categoryId);  
												
													 if(count($childCategory) > 0)
													{?>
																		<li class="listsubmenu">
																	<a class="sn-bar__link bold" href="<?php echo $baseUrl . '/category/' . $getcatdet->slug . '/' . $subCatdet->slug; ?>">
																		<div class="subcate">
																		<span><?php echo Yii::t('app', $subCategory); ?></span>
																		<i class="fa fa-caret-right arrow_cart"></i> </div>
																	</a>



																	<ul class="dropdown-menu"
																		aria-labelledby="navbarDropdownMenuLink">

																		<li class="dropdown-submenu">

																			<?php
																				foreach ($childCategory as $key => $childCategory) :

																	$childCatdet = Yii::$app->Myclass->getCatDetails($key);
																	
																
																	?>
													
														<a class="dropdown-item dropdown-toggle bold" href="<?php echo $baseUrl . '/category/' . $getcatdet->slug . '/' . $subCatdet->slug. '/'.$childCatdet->slug; ?>"><?php echo Yii::t('app', $childCategory); ?></a>
												
														<?php endforeach; ?>
												
													
																			

																		</li>
																	</ul>
																</li>

												<?php	}else{
													?>
													<li>
														<a class="sn-bar__link bold" href="<?php echo $baseUrl . '/category/' . $getcatdet->slug . '/' . $subCatdet->slug; ?>"><?php echo Yii::t('app', $subCategory); ?></a>
													</li>
												<?php } ?>
												<?php endforeach; ?>
											</ul>
										<?php } ?>
                                </li>

								<?php } endforeach; ?>
                            

                            </ul>
                        </div>
                        <button id="sn-bar__scroll--left" class="sn-bar__scroll sn-bar__scroll--left hide" style="left: 106px;">‹</button>
                        <button id="sn-bar__scroll--right" class="sn-bar__scroll sn-bar__scroll--right ">›</button>
                    </nav>
                    <div class="sn-bar__placeholder"></div>
</div>
</div>
</div>




<div class="modal fade" id="confirm_popup_container" role="dialog" aria-hidden="true">
<div id="confirm-popup" class="modal-dialog modal-dialog-width confirm-popup">
<div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
        <h2 class="login-header-text"><?= Yii::t('app', 'Confirm') ?></h2>

</div>

<div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

<div class="login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
    <span class="delete-sub-text col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
        <?= Yii::t('app', 'Are you sure you want to proceed ?'); ?>	
		</span>
    <span class="confirm-btn">
        <a class="margin-bottom-0 post-btn" href="#" onclick="closeConfirm()">
		<?= Yii::t('app', 'ok') ?>							</a>
    </span>
    <a class="margin-bottom-0 delete-btn margin-10" href="#" onclick="closeConfirm()">
	<?= Yii::t('app', 'cancel') ?>						</a>
</div>
</div>
</div>
</div>



<div id="content" class="chat-layout">
<?= $content ?>
</div>



<?php if (Yii::$app->session->hasFlash('warning')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'warning', 'message' => Yii::$app->session->getFlash('warning'),
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
					]); ?>
    <?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('success')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'success', 'message' => Yii::$app->session->getFlash('success'),
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
					]); ?>
    <?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'error', 'message' => Yii::$app->session->getFlash('error'),
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
					]); ?>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('info')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'info', 'message' => Yii::$app->session->getFlash('info'),
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
					]); ?>
<?php endif; ?>

<div class="backtop">
<a id="back2Top" title="<?= Yii::t('app', 'Back to top') ?>" href="#"><span><?= Yii::t('app', 'Back to top') ?></span></a>
</div>

<!-- End back to top -->
<?= $this->render('//site/footer') ?> 


<?php $this->endBody() ?>


<script type="text/javascript">
    window.csrfTokenName = "{{ craft.config.csrfTokenName|e('js') }}";
    window.csrfTokenValue = "{{ craft.request.csrfToken|e('js') }}";
</script>


</body>
</html>

<?php $this->endPage() ?>


      <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
        $("#wrapper.toggled").css("display", "block");
        $("body").toggleClass("scroll-hidden");
        //$("#wrapper.toggled").parent("body").css('overflow':'hidden');
    });
    </script>
	<style>
	.scroll-hidden{
	overflow:hidden;
	}
	</style>

<!-- Sticky menu -->
<script>
//$(document).ready(function(){
	$(window).scroll(function() {
	    var scroll = $(window).scrollTop();
	    var headerHeightTrack = ($('.classified-menu').height() - 64);

	    if (scroll >= headerHeightTrack) {
	        $(".classified-header").addClass("affix");
	    } else {
	        $(".classified-header").removeClass("affix");
	    }
	});
//});
</script>




<script type="text/javascript">

	/*Scroll to top when arrow up clicked BEGIN*/
$(window).scroll(function() {
    var height = $(window).scrollTop();
    if (height > 500) {
        $('#back2Top').fadeIn();
    } else {
        $('#back2Top').fadeOut();
    }
});
$(document).ready(function() {
    $("#back2Top").click(function(event) {
        event.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });

});
 /*Scroll to top when arrow up clicked END*/

</script>
<!-- E o tooltip menu -->

			<!-- page -->
	<style type="text/css">
		.flashes{
			 -webkit-transition: all 3s ease-out;
		    -moz-transition: all 3s ease-out;
		    -ms-transition: all 3s ease-out;
		    -o-transition: all 3s ease-out;
		    transition: all 3s ease-out;

		}
		.move{

			 position: absolute;
		    -webkit-transition: all 3s ease-out;
		    -moz-transition: all 3s ease-out;
		    -ms-transition: all 3s ease-out;
		    -o-transition: all 3s ease-out;
		    transition: all 3s ease-out;

		}
		
	</style>
	<script>

	$(document).keyup(function(e) {
		if (e.keyCode === 27){

	   		if ($('#login-modal').css('display') == 'block'){
		       $('#login-modal').modal('hide');
			}

	   		if ($('#signup-modal').css('display') == 'block'){
 			   $('#signup-modal').modal('hide');
			}

	   		if ($('#forgot-password-modal').css('display') == 'block'){
		       $('#forgot-password-modal').modal('hide');
			}

			if ($('#nearmemodals').css('display') == 'block'){
		       $('#nearmemodals').modal('hide');
			}

			if ($('#post-your-list').css('display') == 'block'){
		       $('#post-your-list').modal('hide');
			}

			if ($('#mobile-otp').css('display') == 'block'){
		       $('#mobile-otp').modal('hide');
			}

			if ($('#chat-with-seller-success-modal').css('display') == 'block'){
		       $('.modal').modal('hide');
		       $('#chat-with-seller-success-modal').css('display','none');
			}

			if ($('#offer-success-modal').css('display') == 'block'){
		       $('.modal').modal('hide');
		       $('#offer-success-modal').css('display','none');
			}
		}
	});

	var loginSession = readCookie('PHPSESSID');
	setTimeout(function() {
			//$(".flashes").slideRight();
			 //$('.flashes').toggle( "slide" );
			//$(".flashes").addClass('move');
			$('.flashes').fadeOut('fast');
		}, 3000);
	function readCookie(name) {
	    var nameEQ = escape(name) + "=";
	    var ca = document.cookie.split(';');
	    for (var i = 0; i < ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
	        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
	    }
	    return null;
	}
	if (typeof timerId != 'undefined'){
		clearInterval(timerId);
	}
	var timerId = setInterval(function() {
		var currentSession = readCookie('PHPSESSID');
	    if(loginSession != currentSession) {
		    window.location = '<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>';
		    clearInterval(timerId);
	    }

	},1000);




</script>
<?php 
$lat = Yii::$app->session['latitude'];
$lon = Yii::$app->session['longitude'];

?>
<input id="map-latitude" class="map-latitude" type="hidden" value="">
<input id="map-longitude" class="map-longitude" type="hidden" value="">
<div id="googleMap" style="display:none;" class="google-Map col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
<script>
var map;
var myCenter=new google.maps.LatLng(51.508742,-0.120850);
var mapzoom = 2;
var geocoder = new google.maps.Geocoder();
var marker;
geocoder.geocode({'latLng': myCenter}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
    //console.log(results)
      if (results[1]) {
          // document.getElementById("pac-input").value = results[0].formatted_address;

      } else {
        alert("No results found");
      }
    } else {
      console.log("Geocoder failed due to: " + status);
    }
  });

function mapinitialize()
{

	var input = document.getElementById('pac-input');
	var autocomplete = new google.maps.places.Autocomplete(input);
	autocomplete.addListener('place_changed', function() {
	    var place = autocomplete.getPlace();
	    var address = place.formatted_address;
	    var latitude = place.geometry.location.lat();
	    var longitude = place.geometry.location.lng();
	    document.getElementById("map-latitude").value = latitude;
	    document.getElementById("map-longitude").value = longitude;
	  });
}

function mapinitialize1()
{
	var input = document.getElementById('pac-input2');
	var autocomplete = new google.maps.places.Autocomplete(input);
	autocomplete.bindTo('bounds', map);
	autocomplete.addListener('place_changed', function() {
	var place = autocomplete.getPlace();
	var address = place.formatted_address;
	var latitude = place.geometry.location.lat();
	var longitude = place.geometry.location.lng();
	document.getElementById("map-latitude").value = latitude;
	document.getElementById("map-longitude").value = longitude;
    });
}
google.maps.event.addDomListener(window, 'load', mapinitialize);
google.maps.event.addDomListener(window, 'load', mapinitialize1);

var mapStickyTrack;
var bannerdetails = 0;
var userdetails = 0;
$(document).ready(function(){
	$(window).on('load resize', function () {
		if($(window).width() >= 1024){
			if(userdetails == 0)
				mapStickyTrack = $('.classified-menu').height() + $('.classified-app-download').height();
			else
				mapStickyTrack = $('.classified-menu').height();
		}else{
			if(userdetails == 0)
				mapStickyTrack = $('.classified-app-download').height();
			else
				mapStickyTrack = $('.classified-header').height();
		}
		if(bannerdetails == 1)
		{
			mapStickyTrack = $('.classified-header').height() + $("#myCarousel").height() + 50;
		}
		mapStickyTrack -= 56;
		//console.log('mapStickyTrack: '+mapStickyTrack);
	   // $('.sticky-screen').height($(window).height()).width($(window).width());
	});

	$(window).on('scroll', function () {
	    if ($(window).scrollTop() >= mapStickyTrack) {
	        $('.find-near-you').addClass('map-menu-fixed');
	    } else {
	        $('.find-near-you').removeClass('map-menu-fixed');
	    }
	});
});
</script>

<!-- <script	src="/js/jquery-ui.min.js"></script> -->

<script type="text/javascript">
$('#pac-input').focus(function() {
    $(this).attr('placeholder', yii.t('app' , 'Where to?'))
}).blur(function() {
    $(this).attr('placeholder', yii.t('app','World Wide'))
})
$('#pac-input2').focus(function() {
   $(this).attr('placeholder', yii.t('app' , 'Where to?'))
}).blur(function() {
    $(this).attr('placeholder', yii.t('app','World Wide'))
})
var loadflag = 0;


</script>



<script type="text/javascript" charset="utf-8">

 	distancelimit = '<?php echo $siteSettings->searchList; ?>'/2;
      jQuery("#Sliders2").slider({ from: 1, to: <?php echo $siteSettings->searchList; ?>, step: 1, dimension: '&nbsp;<?php echo $siteSettings->searchType; ?>',
      callback: function (value) {
        getLocationData();
    }, });
 </script>
 <script type="text/javascript">  	
 $( document ).ready(function() {
     	$("#SliderPrice").val("0;10000");
     	$("#SliderPriceSM").val("0;10000");
     	 });  </script>
 <script type="text/javascript" charset="utf-8">
      jQuery("#SliderPrice").slider({ from: 0, to: 5000, step: 1, dimension: '&nbsp;',
      callback: function (value) {
      	//value = $("#SliderPrice").val();
      	$("#SliderPrice").val(value);
			var value = value.split(";");
		    if (value[1]>4999) {
		       $(".SliderPriceCol .jslider-value-to > span").html('5,000+');
			} 
        getLocationData();
    }, });
 </script>
 <script type="text/javascript" charset="utf-8">
      jQuery("#SliderPriceSM").slider({ from: 0, to: 5000, step: 1, dimension: '&nbsp;',
      callback: function (value) {
      	//alert(0);
      //	value = $("#SliderPriceSM").val();
      $("#SliderPriceSM").val(value);
			var value = value.split(";");
		    if (value[1]>4999) {
		       $(".SliderPriceSM .jslider-value-to > span").html('5,000+');
			}
        getLocationDatamobile();
    }, });
 </script>

 <script type="text/javascript" charset="utf-8">

 	$('#flat-slider').slider({
  orientation: 'horizontal',
  range:       true,
  values:      [17,67]
});
 </script>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
	jQuery('body').on('click','#yt0',function(){jQuery.ajax({'beforeSend':function(){$(".more-listing").hide();$(".classified-loader").show();$("#ugnt_load").html("");},'data':{"limit": limit, "offset": offset, "loadData": 1,"adsOffset": adsoffset,"urgent": urgent,"ads": ads, "PriceValue": PriceValue},'success':function(response){
					//var grid = document.querySelector("#fh5co-board");
						var grid = document.querySelector("#fh5co-board");
					//$(".more-listing").remove();
						$(".more-listing").show();$(".classified-loader").hide();
						var output = response.trim();
						var contentData = eval($.trim(output));
						loadflag++;
						if (output) {
							offset = offset + limit;
							adsoffset = adsoffset + 10;
						//$("#products").append(output);
						//$("#fh5co-board").append($.trim(output));
						//salvattore.recreateColumns(grid);
							for(var i = 0; i < contentData.length; i++){
								var item = document.createElement("div");
								salvattore["append_elements"](grid, [item]);
								item.outerHTML = contentData[i];
							}
							$("#ugnt_load").html(" ");
							$(".imgcls").load(function () {
								$(".imgcls").addClass("hgtremoved");
							});
							//.attr("style", "height: auto !important");
						} else {
							$(".classified-loader").hide();
							$(".more-listing").hide();
						}
					},'url':'/site/loadresults/?search=&category=&subcategory=&lat=&lon=','cache':false});return false;});
	
});
/*]]>*/
</script>


</body>
</html>

<script type="text/javascript">
	function initMap() {
       
      var autocomplete = new google.maps.places.Autocomplete((document
            .getElementById('pac-input')), {
        types : [ 'geocode' ]
    });
      	var autocomplete = new google.maps.places.Autocomplete((document
				.getElementById('pac-input2')), {
				types : [ 'geocode' ]
			});
	 autocomplete.addListener('place_changed', function() {
        
    var place = autocomplete.getPlace();

     var latitude = place.geometry.location.lat();
     var longitude = place.geometry.location.lng();

    var placeDetails = place.address_components;
     var count = placeDetails.length;
   
    var country = "";
    while(count != 0 && country == ""){
     if(placeDetails[count-1].types[0] == "country"){
         country = placeDetails[count-1].short_name;
         $('#shippingcountry').val(country);
     }
     count--;
    }
    
    $("#map-latitude").val(latitude);
     $("#map-longitude").val(longitude);

    });

    
      }



</script>
<?php


//print_r($siteSettings->googleapikey);exit;
if (!empty($siteSettings) && isset($siteSettings->googleapikey) && $siteSettings->googleapikey != "")
	$googleapikey = $siteSettings->googleapikey;
else
	$googleapikey = "";
?>

   <?php if (isset($_SESSION['language']) && ($_SESSION['language'] == 'ar')){?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapikey; ?>&libraries=places&callback=initMap&language=ar"></script>
	<?php }else {?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapikey; ?>&libraries=places&callback=initMap&language=en"></script>
	<?php } ?>
<!-- for autocomplete -->


<script>
 document.getElementById("w_nouislider").style.display = "none";
</script>



<script type="text/javascript">

$(document).ready(function () {
	$.noConflict();
	$('.default_msg_txt').mousewheel(function(e, delta) {
    this.scrollLeft -= (delta * 40);
    e.preventDefault();
});

});
</script>


<script>

$(".sidebarToggler").click(function () {
	$('html, body').animate({
		scrollTop: $(window).scrollTop() + 60
	});
});

$(".push_right, .pushbar_overlay").click(function () {
	$('html, body').animate({
		scrollTop: $(window).scrollTop() - 60
	});
});
</script> 