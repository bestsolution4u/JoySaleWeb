<?php
use yii\helpers\Html;
$footerSettings = yii::$app->Myclass->getFooterSettings();
$siteSettings = yii::$app->Myclass->getSitesettings();
if (isset($_GET['search'])) {
   $search = str_replace("'", "", $_GET['search']);
}
else
{
    $search = "";
}
?>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="classified-footer-head col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="classified-social-connect col-xs-12 col-sm-6 col-md-3 col-lg-3 no-hor-padding">
                    <span class="classified-social-head">
                        <?php echo Yii::t('app',$footerSettings['socialloginheading']); ?></span>
                        <?php if(!empty($footerSettings['socialLinks']) && count($footerSettings['socialLinks']) > 0){ ?>
                            <div class="classified-social-icon">
                              <?php if(isset($footerSettings['socialLinks']['facebook'])){ ?>
                                <a href="<?php echo $footerSettings['socialLinks']['facebook']; ?>" target="_blank"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/design/facebook.png'); ?>" alt="facebook"></a>
                            <?php }if(isset($footerSettings['socialLinks']['twitter'])){ ?>
                                <a href="<?php echo $footerSettings['socialLinks']['twitter']; ?>" target="_blank"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/design/twitter.png'); ?>" alt="twitter"></a>
                            <?php }if(isset($footerSettings['socialLinks']['google'])){ ?>
                                <a href="<?php echo $footerSettings['socialLinks']['google']; ?>" target="_blank"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/design/google-plus.png'); ?>" alt="google plus"></a>
                            <?php }?>
                        </div>
                    <?php }else{ ?>
                        <div class="classified-nosocial-icon"><?php echo Yii::t('app','Yet no sociallinks are not updated.'); ?></div>
                    <?php } ?>
                </div>
                <div class="classified-app-links col-xs-12 col-sm-6 col-md-2 col-lg-2 no-hor-padding">
                    <span class="classified-app-head"><?php echo Yii::t('app',$footerSettings['applinkheading']); ?> </span>
                    <?php if(!empty($footerSettings['appLinks']) && count($footerSettings['appLinks']) > 0){ ?>
                        <div class="classified-app-icon">
                          <?php if(isset($footerSettings['appLinks']['ios'])){ ?>
                            <a class="classified-ios-app" href="<?php echo $footerSettings['appLinks']['ios']; ?>" target="_blank"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/design/ios.png'); ?>" alt="ios app" data-toggle="tooltip" title="" data-original-title="iOS app"></a>
                        <?php } if(isset($footerSettings['appLinks']['ios']) && isset($footerSettings['appLinks']['android']) ){?>
                            <span class="classified-footer-vertical-line"></span>
                        <?php } if(isset($footerSettings['appLinks']['android'])){ ?>
                            <a href="<?php echo $footerSettings['appLinks']['android']; ?>" target="_blank" class="classified-android-app"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/images/design/android.png'); ?>" alt="android app"data-toggle="tooltip" title="" data-original-title="Android app"></a>
                        <?php } ?>
                    </div>
                <?php }else{ ?>
                    <div class="classified-noapp-icon"><?php echo Yii::t('app','Yet no applinks are not updated.'); ?></div>
                <?php }?>
            </div>
            <?php if(empty(Yii::$app->user->id)) {?>
                <div class="classified-new-account col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
                    <p class="classified-new-account-info col-xs-12 col-sm-9 col-md-9 col-lg-9 no-hor-padding">
                        <?php echo Yii::t('app',$footerSettings['generaltextguest']); ?>
                    </p>
                    <?php   if(isset($siteSettings->paidbannerstatus) && $siteSettings->paidbannerstatus == "1")
                    {?>
                        <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('site/adverister'); ?>"
                            class="classified-create-btn border-radius-5 primary-bg-color col-xs-12 col-sm-3 col-md-3 col-lg-3 no-hor-padding">
                            <?php echo Yii::t('app','Banner Advertise'); ?>
                        </a>
                    <?php } ?>
                </div>
            <?php }else{ ?>
                <div class="classified-new-account col-xs-12 col-sm-12 col-md-7 col-lg-7 no-hor-padding">
                    <p class="classified-new-account-info col-xs-12 col-sm-9 col-md-9 col-lg-9 no-hor-padding">
                        <?php echo Yii::t('app',$footerSettings['generaltextuser']); ?>
                    </p>
                    <?php
                    if(isset($siteSettings->paidbannerstatus) && $siteSettings->paidbannerstatus == "1")
                    {
                        ?>
                        <a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl(
                        'site/adverister'); ?>"
                        class="classified-create-btn border-radius-5 primary-bg-color col-xs-12 col-sm-3 col-md-3 col-lg-3 no-hor-padding direcc">
                        <?php echo Yii::t('app','Banner Advertise'); ?>
                    </a>
                    <?php
                }
                ?>
            </div>
        <?php }?>
    </div>
</div>
<div class="row copyright-foter">
    <div class="classified-footer-horizontal-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
    <div class="classified-footer-bottom col-xs-12 col-sm-8 col-md-8 col-lg-10 no-hor-padding">
        <div class="classified-footer-menu-links col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
            <ul>
                <?php $footerLinks = yii::$app->Myclass->getFooterLinks();
                            //echo '<pre>'; print_r($footerLinks); exit;
                if (!empty($footerLinks)){
                    ?>
                    <li>
                        <?php
                        foreach ($footerLinks as $footerKey => $footerLink){
                            if($footerLink->status == 1)
                                continue;
                            $pageLink = Yii::$app->urlManager->createAbsoluteUrl('message/help?details='.$footerLink->slug);
                            ?>
                            <a class="primary-txt-color" target="_blank" href="<?php echo $pageLink; ?>"><?php echo Yii::t('app',$footerLink->page); ?></a>
                        </li>
                        <?php if(count($footerLinks) > ($footerKey + 1)){ ?>
                            <li class="classified-footer-dev"><?php echo Yii::t('app','l'); ?></li>
                            <?php
                        }
                    }
                    ?>
                <?php }?>
            </ul>
        </div>
        <div class="classified-footer-Copyright col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
            <?php if(!empty($footerSettings['footerCopyRightsDetails'])){
                echo Yii::t('app',$footerSettings['footerCopyRightsDetails']);
            }else{ ?>
                <span><?php echo Yii::t('app','© Copyright ').date("Y").Yii::t('app',' Hitasoft.com Limited. All rights reserved.'); ?> </span>
            <?php }  ?>
            <input type="hidden" id="currentDevice" value="<?php echo $deviceModel = yii::$app->Myclass->getDeviceName();?>">
        </div>
    </div>
    <form id="myForm" method="post">
       <div class="language col-xs-12 col-sm-4 col-md-4 col-lg-2 no-hor-padding">
        <label class="language-select-box-heading" style="float: inherit;"><?php echo Yii::t('app','Language');?>:</label>
        <select id="language_select" class="col-xs-12 col-sm-6 airfcfx-footer-select form-control margin10 no-hor-padding  select-box-down-arrow" onchange="ChangeLanguage()">
            <?php 
            if(!isset($_SESSION['language'])) {
                $_SESSION['language'] = 'en';
            }
            if($_SESSION['language'] == 'en') {
                echo '<option selected value="en">English</option>';
            } else {
                echo '<option value="en">English</option>';
            }?>
            <?php if($_SESSION['language'] == 'fr') {
                echo '<option selected value="fr">French</option>';
            } else {
                echo '<option value="fr">French</option>';
            }?>
        </select>
    </div>
</form>
</div>
</div>
</div>
<div class="analytics-codes">
    <?php if(!empty($footerSettings['analytics'])){
        echo Yii::t('app',$footerSettings['analytics']);
    } ?>
</div>
<!-- Location Floting icon -->
<div class="modal fade in" id="search-mobile-cal" role="dialog">
    <div class="searcls-mobile modal-dialog modal-dialog-width">
        <div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
            <div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                <h2 class="login-header-text"><?php echo Yii::t('app','Search'); ?></h2>
                <button data-dismiss="modal" class="close login-close" type="button">×</button>
            </div>
            <div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
            <div class="login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
                <div class="login-box">
                    <div class="login-text-box">
                        <form role="form" class="searchform navbar-form- navbar-left- search-form" style="padding-left: 0;"
                        action="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/'); ?>" method="get">
                        <div class="classified-search-bar col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                            <div class="ui-widget topsearch-locatn">
                                <input type="text" id="searchvalmobile" onkeyup="ajaxSearch(this,event);" maxlength="30" placeholder="<?php echo Yii::t('app','Search products'); ?>" class="tags classified-search-icon form-control input-search <?php echo !empty(Yii::$app->user->id) ? "" : "sign" ?>" name="search" value="<?php echo $search;?>">
                            </div>
                        </div>
                    </form>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                        <div class="hme-top-location map-input-section">
                            <div class="map-input-box">
                                <input id="pac-input2" placeholder="World Wide" class="controls" autocomplete="off" value="<?php echo Yii::$app->session['curr_place1'];?>" type="text">
                            </div>
                        </div>
                    </div>
                    <input class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding login-btn" name="Search" value="Search" type="submit" onclick="return gotogetLocationDatamobile();">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function ChangeLanguage() {
        var language = $("#language_select").val();
        console.log(language);
        $.ajax({
            type : 'GET',
            url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/site/language',
            data : {
                language : language,
            },
            dataType : "html",
            success : function(data) {
                window.location.reload();
                $("html, body").animate({ scrollTop: 0 }, "slow");
            },
            error: function(err)
            {
                console.log("Error");
            }
        });
    }
</script>