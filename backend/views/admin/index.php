<?php

use dosamigos\chartjs\ChartJs;
use yii\web\JsExpression;
use conquer\toastr\ToastrWidget;
use common\models\Orders;
use bsadnu\googlecharts\GeoChart;
use bsadnu\googlecharts\GeoChartt;
use bsadnu\googlecharts\BarChart;
use yii\helpers\Json;
use kartik\alert\Alert;
use common\models\Sitesettings;
$orders = new Orders();
$sitesetting = yii::$app->Myclass->getSitesettings();
$promotionStatus = $sitesetting->promotionStatus;
$paidbannerStatus = $sitesetting->paidbannerstatus;
$givingawayStatus = $sitesetting->givingaway;
?>
<?php 
            if(Yii::$app->session->hasFlash('success')): 
                echo Alert::widget([
                'id' => 'alert-success',
                'type' => Alert::TYPE_SUCCESS,
                'body' => Yii::$app->session->getFlash('success'),
                'delay' => 8000
                ]); 
            endif; 

            if(Yii::$app->session->hasFlash('error')): 
                echo Alert::widget([
                 'id' => 'alert-error',
                'type' => Alert::TYPE_DANGER,
                'body' => Yii::$app->session->getFlash('error'),
                'delay' => 8000
                ]); 
            endif; 
                $sitepaymentmodes = yii::$app->Myclass->getSitePaymentModes();
        ?>

  <!-- Page Content  -->
<section class="row m-b20">


                <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box1 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                            <a href="<?php echo Yii::$app->homeUrl."users/index" ?>" class="whiteTxtClr"><p class="mb-0"><?php echo Yii::t('app','Total').' '.Yii::t('app','Users'); ?></p></a> 
                            </div>
                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="mdi mdi-account fontSize30 whiteTxtClr"></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb"><?php echo yii::$app->Myclass->getTotalUsers(); ?></p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box2 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                    <a href="<?php echo Yii::$app->homeUrl."products/index" ?>" class="whiteTxtClr">  <p class="mb-0"><?php echo Yii::t('app','Total').' '.Yii::t('app','Items'); ?></p></a>
                            </div>
                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="mdi mdi-cart fontSize30 whiteTxtClr"></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb"><?php echo yii::$app->Myclass->getTotalItems(); ?></p>

                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box3 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                    <a href="javascript:void(0);" class="whiteTxtClr">     <p class="mb-0"><?php echo Yii::t('app','Active').' '.Yii::t('app','Users'); ?></p></a>
                            </div>
                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="mdi mdi-arrow-expand-up fontSize30 whiteTxtClr"></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb">  <?php echo $getActiveUsers; ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box4 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                    <a href="javascript:void(0);" class="whiteTxtClr">    <p class="mb-0"><?php echo Yii::t('app','Sold').' '.Yii::t('app','Products'); ?></p></a>
                            </div>
                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="mdi mdi-arrow-collapse fontSize30 whiteTxtClr"></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb"><?php echo yii::$app->Myclass->getSoldTotalItems(); ?></p>
                    </div>
                </div>
                
            </section>

            <section class="row m-b20">


                <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box5 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                            <a href="javascript:void(0);" class="whiteTxtClr"><p class="mb-0"><?php echo Yii::t('app','Total').' '.Yii::t('app','Revenue'); ?></p></a> 
                            </div>
                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="f fontSize30 whiteTxtClr"> <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="#fff"><g id="surface1"><path d="M164.04,3.24l-3.72,0.96l-34.44,9.24c-0.21,0.045 -0.39,0.06 -0.6,0.12l-85.8,23.04l-34.92,9.36l-3.72,0.96l0.96,3.72l8.28,30.48v0.24l12.96,47.88v47.4h168.96v-99.84h-8.16l-18.84,-69.96zM158.52,12.6l4.32,16.2c-0.795,-0.195 -1.62,-0.465 -2.4,-0.6c-2.43,-0.39 -4.725,-0.615 -6.6,-0.84c-1.875,-0.225 -3.315,-0.63 -3.48,-0.72c-0.15,-0.09 -1.185,-1.095 -2.4,-2.64c-1.215,-1.545 -2.775,-3.51 -4.68,-5.4c-0.48,-0.48 -1.005,-0.99 -1.56,-1.44zM130.8,20.52c3.06,-0.015 4.98,1.395 7.08,3.48c1.395,1.395 2.655,3.15 3.96,4.8c1.305,1.65 2.535,3.315 4.68,4.56c2.145,1.23 4.215,1.41 6.36,1.68c2.145,0.27 4.38,0.39 6.36,0.72c1.98,0.33 3.615,0.825 4.68,1.44c0.975,0.57 1.47,1.065 1.8,2.04c0.03,0.09 0.09,0.135 0.12,0.24l9.96,37.32h-45.6c0.15,-4.08 -0.315,-8.28 -1.44,-12.48c-4.065,-15.165 -15.975,-26.205 -29.52,-28.68c-2.25,-0.405 -4.53,-0.57 -6.84,-0.48c-2.31,0.09 -4.635,0.45 -6.96,1.08c-16.98,4.56 -27.045,22.17 -24.6,40.56h-37.8v22.92l-5.64,-20.88c-0.135,-0.855 -0.03,-1.515 0.48,-2.4c0.615,-1.065 1.815,-2.445 3.36,-3.72c1.545,-1.275 3.435,-2.415 5.16,-3.72c1.725,-1.305 3.33,-2.535 4.56,-4.68h0.12c1.245,-2.145 1.38,-4.17 1.68,-6.24c0.3,-2.07 0.57,-4.215 1.08,-6.12c1.035,-3.81 2.4,-6.495 7.68,-7.92l85.8,-23.04c1.32,-0.36 2.46,-0.48 3.48,-0.48zM92.64,42.72c12.465,-0.405 24.78,9.075 28.68,23.64c0.945,3.54 1.35,7.05 1.2,10.44h-53.88c-2.535,-15.39 5.58,-29.715 18.72,-33.24c1.77,-0.48 3.495,-0.78 5.28,-0.84zM149.64,47.28c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM27,48c-0.255,0.66 -0.42,1.275 -0.6,1.92c-0.705,2.595 -0.915,5.01 -1.2,6.96c-0.285,1.95 -0.75,3.45 -0.84,3.6c-0.09,0.165 -1.14,1.26 -2.64,2.4c-1.5,1.14 -3.375,2.4 -5.28,3.96c-0.645,0.525 -1.29,1.08 -1.92,1.68l-4.32,-16.08zM149.64,54.96c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,84.48h17.4c-0.42,0.57 -0.735,1.215 -1.08,1.8c-1.35,2.325 -2.34,4.68 -3.12,6.48c-0.78,1.8 -1.56,3 -1.68,3.12c-0.135,0.135 -1.365,0.975 -3.12,1.68c-1.755,0.705 -3.885,1.515 -6.12,2.52c-0.75,0.345 -1.515,0.795 -2.28,1.2zM63.12,84.48h88.8c5.475,0 7.5,2.22 9.48,5.64c0.99,1.71 1.815,3.72 2.64,5.64c0.825,1.92 1.485,3.885 3.24,5.64c1.74,1.74 3.765,2.43 5.76,3.24c1.995,0.81 4.05,1.575 5.88,2.4c1.83,0.825 3.33,1.77 4.2,2.64c0.87,0.87 1.2,1.41 1.2,2.64v38.4c0,0.54 -0.045,0.645 -0.48,1.08c-0.435,0.435 -1.44,1.02 -2.64,1.56c-1.2,0.54 -2.535,1.11 -3.96,1.68c-1.425,0.57 -2.985,1.065 -4.44,2.52c-1.47,1.47 -1.935,2.85 -2.52,4.2c-0.585,1.35 -1.17,2.745 -1.8,3.84c-1.275,2.19 -2.13,3.36 -5.52,3.36h-110.88c-3.39,0 -4.245,-1.17 -5.52,-3.36c-0.63,-1.095 -1.215,-2.475 -1.8,-3.84c-0.585,-1.365 -1.05,-2.73 -2.52,-4.2c-1.455,-1.455 -3.015,-1.95 -4.44,-2.52c-1.425,-0.57 -2.76,-1.14 -3.96,-1.68c-1.2,-0.54 -2.205,-1.125 -2.64,-1.56c-0.435,-0.435 -0.48,-0.54 -0.48,-1.08v-22.08h0.12l-0.12,-0.48v-15.84c0,-1.23 0.33,-1.77 1.2,-2.64c0.87,-0.87 2.37,-1.815 4.2,-2.64c1.83,-0.825 3.885,-1.59 5.88,-2.4c1.995,-0.81 4.02,-1.5 5.76,-3.24c1.755,-1.755 2.4,-3.72 3.24,-5.64c0.84,-1.92 1.65,-3.93 2.64,-5.64c1.98,-3.42 4.005,-5.64 9.48,-5.64zM166.92,84.48h17.4v16.8c-0.75,-0.405 -1.53,-0.855 -2.28,-1.2c-2.235,-1.005 -4.365,-1.815 -6.12,-2.52c-1.755,-0.705 -2.985,-1.545 -3.12,-1.68c-0.12,-0.12 -0.9,-1.32 -1.68,-3.12c-0.78,-1.8 -1.77,-4.155 -3.12,-6.48c-0.345,-0.585 -0.66,-1.23 -1.08,-1.8zM107.52,88.32c-19.305,0 -34.56,17.475 -34.56,38.4c0,20.925 15.255,38.4 34.56,38.4c19.305,0 34.56,-17.475 34.56,-38.4c0,-20.925 -15.255,-38.4 -34.56,-38.4zM107.52,96c14.625,0 26.88,13.485 26.88,30.72c0,17.235 -12.255,30.72 -26.88,30.72c-14.625,0 -26.88,-13.485 -26.88,-30.72c0,-17.235 12.255,-30.72 26.88,-30.72zM51.84,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM163.2,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM51.84,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM163.2,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,160.44c1.575,0.705 3.045,1.215 4.2,1.68c1.17,0.48 1.965,1.005 1.8,0.84c-0.165,-0.165 0.42,0.69 0.96,1.92c0.48,1.11 1.095,2.565 1.92,4.08h-8.88zM184.32,160.44v8.52h-8.88c0.825,-1.515 1.44,-2.97 1.92,-4.08c0.54,-1.23 1.125,-2.085 0.96,-1.92c-0.165,0.165 0.615,-0.36 1.8,-0.84c1.155,-0.465 2.625,-0.975 4.2,-1.68z"></path></g></g></g></svg></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb"><?php echo $totalRevenue; ?></p>
                    </div>
                </div>
           <?php    if($promotionStatus !=0) {?>
    
                  <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box6 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                    <a href="javascript:void(0);" class="whiteTxtClr">     <p class="mb-0"><?php echo Yii::t('app','Total').' '.Yii::t('app','Promotions'); ?></p></a>
                            </div>
                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="mdi mdi-account fontSize30 whiteTxtClr"></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb"><?php echo yii::$app->Myclass->getTotalPromotions(); ?></p>
                    </div>
                </div>
        
                 <?php }
 
     if($sitepaymentmodes['exchangePaymentMode'] == "1")
     {
    ?>
               <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box7 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                            <a href="javascript:void(0);" class="whiteTxtClr"><p class="mb-0"><?php echo Yii::t('app','Total').' '.Yii::t('app','Exchanges'); ?></p></a> 
                            </div>
                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="mdi mdi-cart fontSize30 whiteTxtClr"></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb"><?php echo yii::$app->Myclass->getTotalExchanges(); ?></p>
                    </div>
                </div>
            <?php }  ?>
            <?php   if($givingawayStatus =='yes') {?>
        
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box8 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                    <a href="javascript:void(0);" class="whiteTxtClr">    <p class="mb-0"><?php echo Yii::t('app','Total').' '.Yii::t('app','Giving Away'); ?></p></a>
                            </div>
                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="mdi mdi-arrow-collapse fontSize30 whiteTxtClr"></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb"><?php echo yii::$app->Myclass->getGivingAwayCount(); ?></p>
                    </div>
                </div>
            <?php } ?>
                
            </section>

            
            <?php

            $reguser = yii::$app->Myclass->getRegisteredUsers();
            $loguser = yii::$app->Myclass->getLoggedUsers();

            if ($reguser != 0 ) {
                $text=Yii::t('app','Registered').' '.Yii::t('app','Users');
            }
            else
            {
                $text=Yii::t('app','No').' '.Yii::t('app','Registered').' '.Yii::t('app','Users');
            }

            if ($loguser != 0) {
                $text1= Yii::t('app','Logged In').' '.Yii::t('app','Users');
            }
            else
            {
                $text1= Yii::t('app','No').' '.Yii::t('app','Logged In').' '.Yii::t('app','Users');
            }

            $mystring = array();
            $count= array();

            $leastDate = date("d-m-Y",strtotime("-7 days"));
            for($i=1;$i<=7; $i++) {
                $mystring[] = date("Y-m-d",strtotime($leastDate."+".$i."days"));
                $count[]=yii::$app->Myclass->getItemsAdded(date("d-m-Y",strtotime($leastDate."+".$i."days")));
            }

            // get Promotion status
            $promotionads = array();
            $promotionUrgent = array();
            $promotionLabel = array();
      
       
           for($i=7;$i>=0; $i--) {
              $promotionLabel[] = date('d-m-Y', strtotime('-'.$i.' days', time()));
              $promotionads[] = yii::$app->Myclass->getPromotionsAdds(date("d-m-Y",strtotime('-'.$i."days")));
              $promotionUrgent[] =yii::$app->Myclass->getPromotionsUrgent(date("d-m-Y",strtotime('-'.$i."days")));
       }



            ?>


                 <section class="row">
                      <div class="col-lg-8 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="cardbox">
                    <h6 class="text-dark header-title m-t-0 m-b-30"><?php echo Yii::t('app','Top Countries Users'); ?></h6>
       

<?php

  foreach ($userCountry as $key => $value) {
 $arr[]=[$key,$value];

}
$arr1[]=['City', 'Users'];
$c = $arr;
$data=array_merge($arr1,$c);

 ?>
<table id="table" style="opacity: 0;position: fixed;"></table>
<div id="morris-bar-chart"  class="p-3 bgWhite m-b20" style="height: 380px;">
          <?= GeoChart::widget([
    'id' => 'user_country',
    'data' => [
      $data
     ],
    'options' => [
        'fontName' => 'Verdana',
        // 'region' => 'IN','US',
        // 'displayMode' => 'markers',
        'height' => 360,
        'width' => '100%',
        'fontSize' => 12,
        'colors' => [
            '#80CEB9','#41AAC4'
        ],
        'tooltip' => [
            'textStyle' => [
                'fontName' => 'Verdana',
                'fontSize' => 13
            ]
        ]              
    ]
]); ?>
</div>
          </div>
        </div>
   <div class="col-lg-4 col-md-6 col-sm-6 col-12 m-b15">
                <div class="cardbox">
                

<?php 
if (isset($_SESSION['countryname'])) { ?>
<h6 class="text-dark header-title m-t-0 m-b-0" id="user_countryname"><?php echo Yii::t('app','Top Users in').' '.Yii::t('app',$_SESSION['countryname']); ?></h6>
<?php 
}else
{
  ?>
<h6 class="text-dark header-title m-t-0 m-b-30" id="user_countryname"><?php echo Yii::t('app','Top Users in').' '.Yii::t('app', $_SESSION['cname']); ?></h6>

  <?php
}
?>

  
<?php
$cityValue = array_slice($cityValue, 0,7);
  foreach ($cityValue as $key => $value) {
    if($key != "")
        $sub[]=[$key,(int)$value,'#4fb1c9',(int)$value];

}
$sub1[]=['Cities', 'Users', [ 'role' => 'style' ], [ 'role'=> 'annotation' ]];
$sublist = $sub;
$subdata=array_merge($sub1,$sublist);

 ?>
 <div id="morris-bar-chart" style="height: 380px;" class="p-3 bgWhite m-b20">
 <div id="map">
  <?php  


   echo BarChart::widget([
  'id' => 'my-bar-chart-id',
    'data' => [
        $subdata
    ],
    'options' => [
        'colors' => ['#4fb1c9'],
        'fontName' => 'Verdana',
        'height' => 360,
        'fontSize' => 12,
        'chartArea' => [
          'left' => '20%',
          'width' => '80%',
          'height' => 250
        ],
        'tooltip' => [
          'textStyle' => [
            'fontName' => 'Verdana',
            'fontSize' => 13
          ]
        ],
        'vAxis' => [
          'gridlines' => [
            'color' => '#e5e5e5',
            //'count' => 5
          ],              
          'minValue' => 0
        ],
        'legend' => [
          'position' => 'top',
          'alignment' => 'center',
          'textStyle' => [
          'color' => '#000',
            'fontSize' => 12
          ]
        ]            
    ]
]); 


  ?>
  </div>
</div>
</div>
</div>
      </section>



             <section class="row">
        <div class="col-lg-8 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="cardbox">
                    <h6 class="text-dark header-title m-t-0 m-b-30"><?php echo Yii::t('app','Top Countries Product'); ?></h6>
           
        

<?php

  foreach ($productcoun as $key => $value) {
 $proarr[]=[$value['countryname'],intval($value['counter'])];  

}
$proarr1[]=['City', 'Products'];
$proc = $proarr;
$prodata=array_merge($proarr1,$proc);

 ?>
<table id="protable" style="opacity: 0;position: fixed;"></table>
<div id="morris-bar-chart" style="height: 380px;"  class="p-3 bgWhite m-b20">

          <?= GeoChartt::widget([
    'id' => 'pro_country',
    'data' => [
      $prodata
     ],
    'options' => [
        'fontName' => 'Verdana',
        // 'region' => 'IN','US',
        // 'displayMode' => 'markers',
        'height' => 360,
        'width' => '100%',
        'fontSize' => 12,
        'colors' => [
             '#FECEA8','#E84A5F'
        ],
        'tooltip' => [
            'textStyle' => [
                'fontName' => 'Verdana',
                'fontSize' => 13
            ]
        ]              
    ]
]); ?> 
</div>
          </div>
        </div>
     <div class="col-lg-4 col-md-6 col-sm-6 col-12 m-b15">
                <div class="cardbox">
           
<?php 
if (isset($_SESSION['product_countrycode'])) { ?>
<h6 class="text-dark header-title m-t-0 m-b-30" id="pro_countryname"><?php echo Yii::t('app','Top Products in').' '.Yii::t('app',$_SESSION['product_countryname']); ?></h6>
<?php 
}else
{
  ?>
<h6 class="text-dark header-title m-t-0 m-b-30" id="pro_countryname"><?php echo Yii::t('app','Top Products in India'); ?></h6>

  <?php
}
?>

  
<?php
$procityValue = array_slice($procityValue, 0,7);
  foreach ($procityValue as $key => $value) {
  if($value['city'] != "")
    $prosub[]=[$value['city'],intval($value['counter']),'#ea6173',intval($value['counter'])]; 

}


$prosub1[]=['Cities', 'Products', [ 'role' => 'style' ], [ 'role'=> 'annotation' ]];
$prosublist = $prosub;
$prosubdata=array_merge($prosub1,$prosublist);
 ?>
 <div id="morris-bar-chart" style="height: 380px;" class="p-3 bgWhite m-b20">
 <div id="mapp">
  <?php  
//if (isset($_SESSION['product_countrycode'])) {

   echo BarChart::widget([
  'id' => 'my-bar-chart-idd',
    'data' => [
     $prosubdata
    ],
    'options' => [
        'colors' => ['ea6173'],
        'fontName' => 'Verdana',
        'height' => 360,
        'fontSize' => 12,
        'chartArea' => [
          'left' => '20%',
          'width' => '80%',
          'height' => 250
        ],
        'tooltip' => [
          'textStyle' => [
            'fontName' => 'Verdana',
            'fontSize' => 13
          ]
        ],
        'vAxis' => [
        'colors' => '#fed5b3',
          'gridlines' => [
            'color' => '#e5e5e5',
            //'count' => 5
          ],              
          'minValue' => 0
        ],
        'legend' => [
          'position' => 'top',
          'alignment' => 'center',
          'textStyle' => [
            'fontSize' => 12
          ]
        ]            
    ]
]); 
//}

  ?>
  </div>
</div>
</div>
</div>
     </section>


           <section class="row">
        <div class="col-lg-8 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="cardbox">
                <div class="form-group pull-right col-md-3">
                <select id="items-selector" class="form-control select-box-down-arrow " onchange="reportItems(this.val)">

<?php 
           if(!isset($_SESSION['reportItems'])) {
             $_SESSION['reportItems'] = 'daily';
           }
           if($_SESSION['reportItems'] == 'daily') {
             echo '<option selected value="daily">Daily</option>';
           } else {
             echo '<option value="daily">Daily</option>';
           }?>
           <?php if($_SESSION['reportItems'] == 'monthly') {
             echo '<option selected value="monthly">Monthly</option>';
           } else {
             echo '<option value="monthly">Monthly</option>';
           }?>

            <?php if($_SESSION['reportItems'] == 'year') {
             echo '<option selected value="year">Yearly</option>';
           } else {
             echo '<option value="year">Yearly</option>';
           }?>

</select></div>
            <h6 class="text-dark header-title m-t-0 m-b-30"><?php echo Yii::t('app','Items added'); ?></h6>



            <div>
                    <div class="p-3 bgWhite m-b20" id="chartbar1" style="height: 330px;">
                   
            <?= ChartJs::widget([
    'type' => 'line',
    'data' => [

    'labels' =>$mystring,
        'datasets' => [  
            [
                'label' => Yii::t('app','Items'),
                'backgroundColor' => "#ffe6f7",
                'borderColor' => "#ff99bb",
                'pointBackgroundColor' => "#ff99bb",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => $count                  
            ],
    ],
    
  ],
    
   
     'clientOptions' => [
         
        'legend' => [
            'onClick' => null,
            ],
            'tooltips' => [
                'enabled' => true,
                'intersect' => false
            ],
            'hover' => [
                'mode' => true
            ],
            'maintainAspectRatio' => false,
               ],

]);
?>
            </div>
                  </div>
          </div>
        </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 m-b15">
              
      <div class="cardbox col-xs-12">
          <h6 class="text-dark header-title m-t-0 m-b-0 break-word"><?php echo Yii::t('app','Engaged Categories'); ?></h6>
        <div class="col-xs-12">

            <div id="morris-area-chart-sales" style="height: 330px;" class="p-3 bgWhite m-b20">
            <?= ChartJs::widget([
    'type' => 'horizontalBar',
    'data' => [

    'labels' =>$catLabel,
        'datasets' => [  
            [
                'label' => Yii::t('app','Products'),
                'backgroundColor' => "#BF4AE8",
                'borderColor' => "#BF4AE8",
                'pointBackgroundColor' =>"#BF4AE8",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' =>  $catValue,
                // $promotionads
        //$count
      ],
  
    ],
    
  ],

     'clientOptions' => [
         
        'legend' => [
            'onClick' => null,
            ],
            'tooltips' => [
                'enabled' => true,
                'intersect' => false
            ],
            'hover' => [
                'mode' => true
            ],
            'maintainAspectRatio' => false,
          
        ],

]);
?>

<!--<script>
var ctx = document.getElementById("w2").getContext("2d");
new Chart(ctx).Line(data, {
    responsive: true,
    customTooltips: function (tooltip) {
        var tooltipEl = $('#chartjs-tooltip');
        if (!tooltip) {
            tooltipEl.css({
                opacity: 0
            });
            return;
        }

        tooltipEl.removeClass('above below');
        tooltipEl.addClass(tooltip.yAlign);

        var innerHtml = ['<div class="chartjs-tooltip-section">',
                         '  <span>' + tooltip.title.long + '</span>',
                         '</div>'
                        ].join('');
        for (var i = tooltip.labels.length - 1; i >= 0; i--) {
            innerHtml += [
                '<div class="chartjs-tooltip-section">',
                ' <span style="color:' + tooltip.legendColors[i].fill + '">' + data.datasets[i].label + ': ' + tooltip.labels[i] + '</span>',
                '</div>'
            ].join('');
        }
        tooltipEl.html(innerHtml);

        tooltipEl.css({
            opacity: 1,
            left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
            top: tooltip.chart.canvas.offsetTop + tooltip.y + 'px',
            fontFamily: tooltip.fontFamily,
            fontStyle: tooltip.fontStyle,
        });
    }
});
</script>-->
            </div>
                
                    
                
                </div>
                    </div>
      </div>
</section>


  <section class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-b15">
              
      <div class="cardbox col-xs-12">
          <h6 class="text-dark header-title m-t-0 m-b-0 break-word"><?php echo Yii::t('app','IOS Model Users'); ?></h6>
        <div class="col-xs-12">

            <div id="morris-area-chart-sales" style="height: 330px;" class="p-3 bgWhite m-b20">
            <?= ChartJs::widget([
    'type' => 'bar',
    'data' => [

    'labels' =>$iosmodelLabel,
        'datasets' => [  
            [
                'label' => Yii::t('app','Count'),
                'backgroundColor' => "#BF4AE8",
                'borderColor' => "#BF4AE8",
                'pointBackgroundColor' =>"#BF4AE8",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' =>  $iosmodelValue,
                // $promotionads
        //$count
      ],
  
    ],
    
  ],

     'clientOptions' => [
         
        'legend' => [
            'onClick' => null,
            ],
            'tooltips' => [
                'enabled' => true,
                'intersect' => false
            ],
            'hover' => [
                'mode' => true
            ],
            'maintainAspectRatio' => false,
          
        ],

]);
?>

<!--<script>
var ctx = document.getElementById("w2").getContext("2d");
new Chart(ctx).Line(data, {
    responsive: true,
    customTooltips: function (tooltip) {
        var tooltipEl = $('#chartjs-tooltip');
        if (!tooltip) {
            tooltipEl.css({
                opacity: 0
            });
            return;
        }

        tooltipEl.removeClass('above below');
        tooltipEl.addClass(tooltip.yAlign);

        var innerHtml = ['<div class="chartjs-tooltip-section">',
                         '  <span>' + tooltip.title.long + '</span>',
                         '</div>'
                        ].join('');
        for (var i = tooltip.labels.length - 1; i >= 0; i--) {
            innerHtml += [
                '<div class="chartjs-tooltip-section">',
                ' <span style="color:' + tooltip.legendColors[i].fill + '">' + data.datasets[i].label + ': ' + tooltip.labels[i] + '</span>',
                '</div>'
            ].join('');
        }
        tooltipEl.html(innerHtml);

        tooltipEl.css({
            opacity: 1,
            left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
            top: tooltip.chart.canvas.offsetTop + tooltip.y + 'px',
            fontFamily: tooltip.fontFamily,
            fontStyle: tooltip.fontStyle,
        });
    }
});
</script>-->
            </div>
                
                    
                
                </div>
                    </div>
      </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-b15">
              
      <div class="cardbox col-xs-12">
          <h6 class="text-dark header-title m-t-0 m-b-0 break-word"><?php echo Yii::t('app','Android Model Users'); ?></h6>
        <div class="col-xs-12">

            <div id="morris-area-chart-sales" style="height: 330px;" class="p-3 bgWhite m-b20">
            <?= ChartJs::widget([
    'type' => 'bar',
    'data' => [

    'labels' =>$androidmodelLabel,
        'datasets' => [  
            [
                'label' => Yii::t('app','Count'),
                'backgroundColor' => "#BF4AE8",
                'borderColor' => "#BF4AE8",
                'pointBackgroundColor' =>"#BF4AE8",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' =>  $androidmodelValue,
                // $promotionads
        //$count
      ],
  
    ],
    
  ],

     'clientOptions' => [
         
        'legend' => [
            'onClick' => null,
            ],
            'tooltips' => [
                'enabled' => true,
                'intersect' => false
            ],
            'hover' => [
                'mode' => true
            ],
            'maintainAspectRatio' => false,
          
        ],

]);
?>

<!--<script>
var ctx = document.getElementById("w2").getContext("2d");
new Chart(ctx).Line(data, {
    responsive: true,
    customTooltips: function (tooltip) {
        var tooltipEl = $('#chartjs-tooltip');
        if (!tooltip) {
            tooltipEl.css({
                opacity: 0
            });
            return;
        }

        tooltipEl.removeClass('above below');
        tooltipEl.addClass(tooltip.yAlign);

        var innerHtml = ['<div class="chartjs-tooltip-section">',
                         '  <span>' + tooltip.title.long + '</span>',
                         '</div>'
                        ].join('');
        for (var i = tooltip.labels.length - 1; i >= 0; i--) {
            innerHtml += [
                '<div class="chartjs-tooltip-section">',
                ' <span style="color:' + tooltip.legendColors[i].fill + '">' + data.datasets[i].label + ': ' + tooltip.labels[i] + '</span>',
                '</div>'
            ].join('');
        }
        tooltipEl.html(innerHtml);

        tooltipEl.css({
            opacity: 1,
            left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
            top: tooltip.chart.canvas.offsetTop + tooltip.y + 'px',
            fontFamily: tooltip.fontFamily,
            fontStyle: tooltip.fontStyle,
        });
    }
});
</script>-->
            </div>
                
                    
                
                </div>
                    </div>
      </div>
</section>






   <section class="row">
      <div class="col-lg-8 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="cardbox">

                     <div class="form-group pull-right col-md-3">
                <select id="user-selector" class="form-control select-box-down-arrow " onchange="reportuser(this.val)">

<?php 
           if(!isset($_SESSION['reportuser'])) {
             $_SESSION['reportuser'] = 'daily';
           }
           if($_SESSION['reportuser'] == 'daily') {
             echo '<option selected value="daily">Daily</option>';
           } else {
             echo '<option value="daily">Daily</option>';
           }?>
           <?php if($_SESSION['reportuser'] == 'monthly') {
             echo '<option selected value="monthly">Monthly</option>';
           } else {
             echo '<option value="monthly">Monthly</option>';
           }?>

            <?php if($_SESSION['reportuser'] == 'year') {
             echo '<option selected value="year">Yearly</option>';
           } else {
             echo '<option value="year">Yearly</option>';
           }?>

</select></div>


                        <h6 class="text-dark header-title m-t-0 m-b-0"><?php echo Yii::t('app','Users').' '.Yii::t('app','Log'); ?></h6>

                    <div class="p-3 bgWhite m-b20" id="chartBar3">
                        <div>
                        <?= ChartJs::widget([
    'type' => 'bar',
      'data' => [

        'labels' =>$userLabel,

        'datasets' => [  
            [
                'label' => Yii::t('app','Registered').' '.Yii::t('app','Users'),
                'backgroundColor' => "#F7B178",
                'borderColor' => "#F7B178",
                'pointBackgroundColor' => "#F7B178",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "#F7B178",
                'data' => $getRegisteredUsers
                // $getRegisteredUsers
            
            ],
            [
                'label' => Yii::t('app','Logged In').' '.Yii::t('app','Users'),
                'backgroundColor' => "#78D6AC",
                'borderColor' => "#78D6AC",
                'pointBackgroundColor' => "#78D6AC",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "#78D6AC",
                'data' => $getLoggedUsers
                // $getLoggedUsers
            
            ],
        ],
        
    ],

     'clientOptions' => [
        'legend' => [
            'onClick' => null,
            ],
        ],

]);
?>      

        <!--<script>
            var ctx = document.getElementById("w2").getContext("2d");
            new Chart(ctx).Line(data, {
                responsive: true,
                customTooltips: function (tooltip) {
                    var tooltipEl = $('#chartjs-tooltip');
                    if (!tooltip) {
                        tooltipEl.css({
                            opacity: 0
                        });
                        return;
                    }

                    tooltipEl.removeClass('above below');
                    tooltipEl.addClass(tooltip.yAlign);

                    var innerHtml = ['<div class="chartjs-tooltip-section">',
                                    '   <span>' + tooltip.title.long + '</span>',
                                    '</div>'
                                    ].join('');
                    for (var i = tooltip.labels.length - 1; i >= 0; i--) {
                        innerHtml += [
                            '<div class="chartjs-tooltip-section">',
                            '   <span style="color:' + tooltip.legendColors[i].fill + '">' + data.datasets[i].label + ': ' + tooltip.labels[i] + '</span>',
                            '</div>'
                        ].join('');
                    }
                    tooltipEl.html(innerHtml);

                    tooltipEl.css({
                        opacity: 1,
                        left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
                        top: tooltip.chart.canvas.offsetTop + tooltip.y + 'px',
                        fontFamily: tooltip.fontFamily,
                        fontStyle: tooltip.fontStyle,
                    });
                }
            });
        </script>-->
                        
                        
                        
                        </div>
                    </div>
                </div>
            </div>

                 <div class="col-lg-4 col-md-6 col-sm-6 col-12 m-b15">
                <div class="cardbox">
                    <h6 class="text-dark header-title  m-t-0 m-b-0"><?php echo Yii::t('app','Users'); ?></h6>
                    <div>
                        <div id="morris-donut-chart" style="height: 350px;" class="p-3 bgWhite m-b20">
                        <?= ChartJs::widget([
    'type' => 'doughnut',
    'options' => [
        'height' => 350,
        
    ],
    'data' => [
        'labels' => ['Web', 'Android','IOS'],
        'datasets' => [
            [
                'label' => "My First dataset",
                'backgroundColor' => [
                    '#595775',
                    '#F1E0D6',
                    '#BF988F',
             
            ],
                'borderColor' => "rgba(179,181,198,1)",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => [$webuser, $Andrioduser,$IOSuser]
             ],
         ]
    ],

     'clientOptions' => [
        'legend' => [
            'onClick' => null,
            ],
        ],
]);
?>
                        </div>
                    </div>
                </div>
            </div>


        </section> 

     <!--    <section class="row">
             <div class="col-lg-6">
          <div class="cardbox">
                <div class="form-group pull-right col-md-3">
              </div>
            <h6 class="text-dark header-title m-t-0 m-b-30"><?php echo Yii::t('app','Android Model & Version'); ?></h6>

<?php 

$anmodellist=array_slice($anmodellist, 0,5);
$ioshead1 = ['Model'];
$ioshead2 = ['Type'];
$modelname = array_keys($anmodellist);
$ioshead = array_merge($ioshead2,array_values($modelname));
$iosdata = array_merge($ioshead1,array_values($anmodellist));
?>

<div>
<div id="morris-bar-chart" class="p-3 bgWhite m-b20">
 <?/*= BarChart::widget([
  'id' => 'android-model',
    'data' => [
    [$ioshead,
        $iosdata,]
    ],
    'options' => [
        'fontName' => 'Verdana',
        'height' => 100,
        'fontSize' => 12,
        'chartArea' => [
          'left' => '15%',
          'width' => '100%',
          'height' => 50
        ],
        'isStacked' => true,
        'tooltip' => [
          'textStyle' => [
            'fontName' => 'Verdana',
            'fontSize' => 13
          ]
        ],
        'hAxis' => [
          'gridlines' => [
            'color' => '#e5e5e5',
            'count' => 5
          ],              
          'minValue' => 0
        ],
        'legend' => [
          'position' => 'top',
          'alignment' => 'center',
          'textStyle' => [
            'fontSize' => 12
          ]
        ]            
    ]
])*/ ?>

<?php 
$anoslist=array_slice($anoslist, 0,5);
$iosver1 = ['OS Version'];
$iosver2 = ['Type'];
$osname = array_keys($anoslist);
$oshead = array_merge($iosver2,array_values($osname));
$osdata = array_merge($iosver1,array_values($anoslist));
?>

<div>
<div id="morris-bar-chart"   class="p-3 bgWhite m-b20">
 <?/*= BarChart::widget([
  'id' => 'android-os',
    'data' => [
    [$oshead,
        $osdata]
    ],
    'options' => [
        'fontName' => 'Verdana',
        'height' => 100,
        'fontSize' => 12,
        'chartArea' => [
          'left' => '15%',
          'width' => '100%',
          'height' => 50
        ],
        'isStacked' => true,
        'tooltip' => [
          'textStyle' => [
            'fontName' => 'Verdana',
            'fontSize' => 13
          ]
        ],
        'hAxis' => [
          'gridlines' => [
            'color' => '#e5e5e5',
            'count' => 5
          ],              
          'minValue' => 0
        ],
        'legend' => [
          'position' => 'top',
          'alignment' => 'center',
          'textStyle' => [
            'fontSize' => 12
          ]
        ]            
    ]
])*/ ?>
            </div>

            </div>
          </div>
        </div>

      </div>
      </div>

 <div class="col-lg-6 ">
          <div class="cardbox">
                <div class="form-group pull-right col-md-3">
              </div>
            <h6 class="text-dark header-title m-t-0 m-b-30"><?php echo Yii::t('app','IOS Model & Version'); ?></h6>

<?php 
$iosmodellist=array_slice($iosmodellist, 0,5);
$anhead1 = ['Model'];
$anhead2 = ['Type'];
$anmodelname = array_keys($iosmodellist);
$anmodelhead = array_merge($anhead2,array_values($anmodelname));
$anmodeldata = array_merge($anhead1,array_values($iosmodellist));
?>

<div>
<div id="morris-bar-chart"   class="p-3 bgWhite m-b20">
 <?/*= BarChart::widget([
  'id' => 'ios-model',
    'data' => [
    [$anmodelhead,
        $anmodeldata]
    ],
    'options' => [
        'fontName' => 'Verdana',
        'height' => 100,
        'fontSize' => 12,
        'chartArea' => [
          'left' => '15%',
          'width' => '100%',
          'height' => 50
        ],
        'isStacked' => true,
        'tooltip' => [
          'textStyle' => [
            'fontName' => 'Verdana',
            'fontSize' => 13
          ]
        ],
        'hAxis' => [
          'gridlines' => [
            'color' => '#e5e5e5',
            'count' => 5
          ],              
          'minValue' => 0
        ],
        'legend' => [
          'position' => 'top',
          'alignment' => 'center',
          'textStyle' => [
            'fontSize' => 12
          ]
        ]            
    ]
])*/ ?>

<?php 
$ioslist=array_slice($ioslist, 0,5);
$anver1 = ['OS Version'];
$anver2 = ['Type'];
$anosname = array_keys($ioslist);
$anoshead = array_merge($anver2,array_values($anosname));
$anosdata = array_merge($anver1,array_values($ioslist));
?>

<div>
<div id="morris-bar-chart" class="p-3 bgWhite m-b20">
 <?/*= BarChart::widget([
  'id' => 'ios-os',
    'data' => [
    [$anoshead,
        $anosdata]
    ],
    'options' => [
        'fontName' => 'Verdana',
        'height' => 100,
        'fontSize' => 12,
        'chartArea' => [
          'left' => '15%',
          'width' => '100%',
          'height' => 50
        ],
        'isStacked' => true,
        'tooltip' => [
          'textStyle' => [
            'fontName' => 'Verdana',
            'fontSize' => 13
          ]
        ],
        'hAxis' => [
          'gridlines' => [
            'color' => '#e5e5e5',
            'count' => 5
          ],              
          'minValue' => 0
        ],
        'legend' => [
          'position' => 'top',
          'alignment' => 'center',
          'textStyle' => [
            'fontSize' => 12
          ]
        ]            
    ]
])*/ ?>
            </div>

            </div>
          </div>
        </div>

      </div>
      </div>
      </section>
 -->

      

           

             <?php 

                $getRegisteredUsers = array();
                $getLoggedUsers = array();

                for($i=1;$i<=7; $i++) {
                $getRegisteredUsers[]=yii::$app->Myclass->getRegisteredUsers(date("d-m-Y",strtotime($leastDate."+".$i."days")));
                $getLoggedUsers[]=yii::$app->Myclass->getLoggedUsers(date("d-m-Y",strtotime($leastDate."+".$i."days"))); 
                }

            ?>
     <?php    if($promotionStatus !=0) {?>
    
   
 <section class="row">
  
          <div class="col-lg-8 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="cardbox">

                  <div class="form-group pull-right col-md-3">
                <select id="promotion-selector" class="form-control select-box-down-arrow " onchange="reportPromotions(this.val)">

<?php 
           if(!isset($_SESSION['reportpromotions'])) {
             $_SESSION['reportpromotions'] = 'daily';
           }
           if($_SESSION['reportpromotions'] == 'daily') {
             echo '<option selected value="daily">Daily</option>';
           } else {
             echo '<option value="daily">Daily</option>';
           }?>
           <?php if($_SESSION['reportpromotions'] == 'monthly') {
             echo '<option selected value="monthly">Monthly</option>';
           } else {
             echo '<option value="monthly">Monthly</option>';
           }?>

            <?php if($_SESSION['reportpromotions'] == 'year') {
             echo '<option selected value="year">Yearly</option>';
           } else {
             echo '<option value="year">Yearly</option>';
           }?>

</select></div>
                    <h6 class="text-dark header-title m-t-0 m-b-30"><?php echo Yii::t('app','Promotions added'); ?></h6>
                    <div>
                        <div class="p-3 bgWhite m-b20" id="chartBar2" style="height: 330px;">
                        <?= ChartJs::widget([
    'type' => 'bar',
     'data' => [

        'labels' =>$promotionLabel,
        'datasets' => [  
            [
                'label' => Yii::t('app','Ads Promotions'),
                'backgroundColor' => "#003366",
                'borderColor' => "#003366",
                'pointBackgroundColor' =>"#003366",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' =>   $promotionads
                // $promotionads
                //$count
            ],
            [
                'label' => Yii::t('app','Urgent Promotions'),
                'backgroundColor' => "#FFC300",
                'borderColor' => "#FFC300",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' =>  $promotionUrgent
                // $promotionUrgent
                //$count
            ],
        ],
        
    ],

     'clientOptions' => [
         
        'legend' => [
            'onClick' => null,
            ],
            'tooltips' => [
                'enabled' => true,
                'intersect' => false
            ],
            'hover' => [
                'mode' => true
            ],
            'maintainAspectRatio' => false,
               ],

]);
?>

<!--<script>
var ctx = document.getElementById("w0").getContext("2d");
new Chart(ctx).Line(data, {
    responsive: true,
    customTooltips: function (tooltip) {
        var tooltipEl = $('#chartjs-tooltip');
        if (!tooltip) {
            tooltipEl.css({
                opacity: 0
            });
            return;
        }

        tooltipEl.removeClass('above below');
        tooltipEl.addClass(tooltip.yAlign);

        var innerHtml = ['<div class="chartjs-tooltip-section">',
                         '  <span>' + tooltip.title.long + '</span>',
                         '</div>'
                        ].join('');
        for (var i = tooltip.labels.length - 1; i >= 0; i--) {
            innerHtml += [
                '<div class="chartjs-tooltip-section">',
                '   <span style="color:' + tooltip.legendColors[i].fill + '">' + data.datasets[i].label + ': ' + tooltip.labels[i] + '</span>',
                '</div>'
            ].join('');
        }
        tooltipEl.html(innerHtml);

        tooltipEl.css({
            opacity: 1,
            left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
            top: tooltip.chart.canvas.offsetTop + tooltip.y + 'px',
            fontFamily: tooltip.fontFamily,
            fontStyle: tooltip.fontStyle,
        });
    }
});
</script>-->
                        </div>
                    </div>
                </div>
            </div>

      </section>

  <?php } ?>





        <script>
function reportItems() {
    
    var items = $("#items-selector").val();
        $.ajax({
            type : 'GET',
            url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/admin/itemsdata',
         // async: false,
            data : {
                items : items,
            },
           dataType : "text",
            success : function(data) {
                  $('#w0').remove();
              $('#chartbar1').append('<canvas id="w0"></canvas>'); 
                var temp = data.split("||");
                var ctx = document.getElementById('w0').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    options: {height : 300},
                    data: {
                    labels: JSON.parse(temp[0]),
                    datasets: [{
                    label: 'Items',
                    data: JSON.parse(temp[1]),
                    backgroundColor: "#ffe6f7",
                    borderColor: "#ff99bb",
                    pointBackgroundColor: "#ff99bb",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor:"#fff",
                    pointHoverBorderColor: "rgba(179,181,198,1)"
                    }]
                    }
                });
              
                },
                error: function(err)
                {
                    console.log("Error");
                }
    });
}

function reportPromotions() {
    
    var promotions = $("#promotion-selector").val();
        $.ajax({
            type : 'GET',
            url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/admin/promotiondata',
         // async: false,
            data : {
                promotions : promotions,
            },
           dataType : "text",
            success : function(data) { 

             $('#w4').remove();
              $('#chartBar2').append('<canvas id="w4"></canvas>'); 
               var temp = data.split("||");
                var ctx = document.getElementById('w4').getContext('2d');
                var data = {
                    labels: JSON.parse(temp[0]),
                    datasets: [{
                        label:'Ads Promotions',
                        backgroundColor: "#003366",
                        data: JSON.parse(temp[1])
                    }, {
                        label: 'Urgent Promotions',
                        backgroundColor: "#FFC300",
                        data: JSON.parse(temp[2])
                    }]
                  };
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    options: {height : 300},
                    data: data
                });
              
                },
                error: function(err)
                {
                    console.log("Error");
                }
    });
}

function reportuser() {
    
    var user = $("#user-selector").val();
        $.ajax({
            type : 'GET',
            url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/admin/userdata',
         // async: false,
            data : {
                user : user,
            },
           dataType : "text",
            success : function(data) {  
              $('#w2').remove();
              $('#chartBar3').append('<canvas id="w2"></canvas>');
               var temp = data.split("||");
                var ctx = document.getElementById('w2').getContext('2d');
                var data = {
                    labels: JSON.parse(temp[0]),
                    datasets: [{
                        label:'Registered Users',
                        backgroundColor: "#F7B178",
                        data: JSON.parse(temp[1])
                    }, {
                        label: 'Logged In Users',
                        backgroundColor: "#78D6AC",
                        data: JSON.parse(temp[2])
                    }]
                  };
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    options: {height : 300},
                    data: data
                });
                },
                error: function(err)
                {
                    console.log("Error");
                }
    });
}
</script>

<style type="text/css">
  .box1{
background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
}

.box2{
background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);}

.box3{
background-image: linear-gradient(to top, #30cfd0 0%, #330867 100%);
}

.box4{
background-image: linear-gradient(25deg, #7028e4 0%, #e5b2ca 100%);}

.box5{
background-image: linear-gradient(25deg, #13547a 0%, #80d0c7 100%);}

.box6{
background-image: linear-gradient(25deg, #0c3483 0%, #a2b6df 100%);}

.box7{
background-image: linear-gradient(25deg, #f77062 0%, #fe5196 100%);}

.box8{
background-image: linear-gradient(25deg, #fc6076 0%, #ff9a44 100%);}

</style>