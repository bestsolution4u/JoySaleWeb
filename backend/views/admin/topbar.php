<?php
 use yii\helpers\Html;
 use common\models\Sitesettings;
 $logo = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
 $path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
 $path = $path1.'/media/logo'.'/';
 ?>
   <div class="topbar">

<!-- LOGO -->
<div class="topbar-left">
    <div class="text-center d-flex">
    <div class="sidebarToggler" data-pushbar-target="left"></div>
        <a href="<?php echo Yii::$app->homeUrl; ?>" class="logo">
            <img src="<?=$path.$logo->logo?>" alt="" />                        </a>
    </div>
</div>

<!-- Button mobile view to collapse sidebar menu -->
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="">

            <ul class="nav navbar-nav navbar-right pull-right mobile-top-right-menu">
                <!-- <li class="hidden-xs">
                    <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="icon-size-fullscreen"></i></a>
                </li> -->
                   <div class="lang-menu" style="display:inline-block;">
                     <form id="myForm" method="post">
<div id="language" class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<label class="language-select-box-heading"><?=Yii::t('app','Language')?>:</label>
<select id="language-selector" class="form-control select-box-down-arrow " onchange="callLang()">
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
<input type="hidden" id="lang" name="_lang" value="en">
</div>  
</form> 


                </div>
                   <li class="dropdown profile-drop-li">
                                    <a href="#" class="dropdown-toggle profile waves-effect waves-light " data-toggle="dropdown" aria-expanded="true"><i class="icon-user"></i> </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                           <?php
                                           $icon=Yii::t("app","Profile").' '.Yii::t("app","Settings");
                                           echo Html::a('<i class="fa fa-user"></i>&nbsp;&nbsp;'.$icon, ['admin/profile'])?>
                                       
                                        </li>

                                         <li>
                                           <?php
                                           $icon=Yii::t("app","Change").' '.Yii::t("app","Password");
                                           echo Html::a('<i class="fa fa-key"></i>&nbsp;&nbsp;'.$icon, ['admin/changepassword'])?>
                                       
                                        </li>
                                       <!--  <li><a href="javascript:void(0)"><i class="ti-settings m-r-5"></i> Settings</a></li>
                                        <li><a href="javascript:void(0)"><i class="ti-lock m-r-5"></i> Lock screen</a></li> -->
                                        <li>

                                      <?php
                                           $icon=Yii::t("app","Logout");
                                           echo Html::a('<i class="fa fa-power-off"></i>&nbsp;&nbsp;'.$icon,array('admin/logout'),array('onClick' => 'return confirm("'.Yii::t('app', 'Are you sure you want to logout ?').'")', 'title'=>Yii::t('app','Click here to Logout')));?></li>

                                    </ul>
                                </li>

        
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>
</div>

<script>
function callLang() {
    
    var language = $("#language-selector").val();
    console.log(language);
        $.ajax({
            type : 'GET',
            url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/admin/language/',
         // async: false,
            data : {
                language : language,
            },
            dataType : "html",
            success : function(data) {
                window.location.reload();
                },
                error: function(err)
                {
                    console.log("Error");
                }
    });
}
</script>
