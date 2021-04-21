<style>
/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>


<div class="tab">
  <ul class="recent-activities-tab nav nav-tabs">
                  <li class="active" id="like_active">
                      <a href="javascript:void(0)" class="tablinks" onclick="select_tab(event, 'Liked')"> <?php echo Yii::t('app','Liked') ?>  </a>
                  </li>
                  <li class="" id="follow_active">
                    <a href="javascript:void(0)" class="tablinks" onclick="select_tab(event, 'Followers')"> <?php echo Yii::t('app','Followers') ?> </a>
                      
                  </li>
                  <li class="" id="following_active">
                      <a href="javascript:void(0)" class="tablinks" onclick="select_tab(event, 'Followings')"> <?php echo Yii::t('app','Followings') ?> </a>
                  </li>
                </ul>
</div>

<div id="Liked" class="tabcontent">
  <?php print_r($following); ?>
</div>

<div id="Followers" class="tabcontent">
  <div id="followersss" class="tab-pane fade in active">
                  <div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

                    <?php  if(!empty($follower)) { ?>
                    <div id="follower" style="margin-top:0px !important;">
<?php echo $this->render('follower',['user'=>$user,'followerlist'=>$follower,'followerIds'=>$followerIds]); ?>
                    </div>
                    <div class="no-more"></div>
                  <?php if(count($follower) >= 15) {
                    if(Yii::$app->controller->action->id == 'follower') { ?>
                  <div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                  <div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
                  <a class="loadmorenow load">
                  <div class="load-more-icon" onclick="load_more_followers('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)) ?>')"></div>
                  <div class="load-more-txt"><?php echo Yii::t('app','Load More'); ?></div>
                </a>
                  </div>
                    <?php } ?>
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

<div id="Followings" class="tabcontent">
   <?php print_r($products); ?>
</div>

<script>
function select_tab(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>