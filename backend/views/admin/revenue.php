<?php
use dosamigos\chartjs\ChartJs;
use yii\web\JsExpression;
use conquer\toastr\ToastrWidget;
use yii\helpers\Html;
use bsadnu\googlecharts\ColumnChart;
use yii\helpers\Json;

$sitePaymentMode = json_decode($sitesetting->sitepaymentmodes);
    $currency = explode('-', $sitesetting->promotionCurrency);
    //echo "<pre>"; print_r($currency);die;

$currencyformat = yii::$app->Myclass->getCurrencyFormat($currency[0]);
//echo "<pre>"; print_r($currencyformat);die;
//echo $sitePaymentMode->buynowPaymentMode; die;?>

            <section class="row m-b20">
                       <?php
                          if($sitesetting->promotionStatus == 1 && $sitesetting->paidbannerstatus == 1)
                          {
                            ?>
                 <div class="col-lg-4 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box6 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                            <a href="#" class="whiteTxtClr"><p class="mb-0 fontSize20"><?php echo Yii::t('app','Total').' '.Yii::t('app','Revenue'); ?></p></a> 
                            </div>
                                             <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="f fontSize30 whiteTxtClr"> <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="#fff"><g id="surface1"><path d="M164.04,3.24l-3.72,0.96l-34.44,9.24c-0.21,0.045 -0.39,0.06 -0.6,0.12l-85.8,23.04l-34.92,9.36l-3.72,0.96l0.96,3.72l8.28,30.48v0.24l12.96,47.88v47.4h168.96v-99.84h-8.16l-18.84,-69.96zM158.52,12.6l4.32,16.2c-0.795,-0.195 -1.62,-0.465 -2.4,-0.6c-2.43,-0.39 -4.725,-0.615 -6.6,-0.84c-1.875,-0.225 -3.315,-0.63 -3.48,-0.72c-0.15,-0.09 -1.185,-1.095 -2.4,-2.64c-1.215,-1.545 -2.775,-3.51 -4.68,-5.4c-0.48,-0.48 -1.005,-0.99 -1.56,-1.44zM130.8,20.52c3.06,-0.015 4.98,1.395 7.08,3.48c1.395,1.395 2.655,3.15 3.96,4.8c1.305,1.65 2.535,3.315 4.68,4.56c2.145,1.23 4.215,1.41 6.36,1.68c2.145,0.27 4.38,0.39 6.36,0.72c1.98,0.33 3.615,0.825 4.68,1.44c0.975,0.57 1.47,1.065 1.8,2.04c0.03,0.09 0.09,0.135 0.12,0.24l9.96,37.32h-45.6c0.15,-4.08 -0.315,-8.28 -1.44,-12.48c-4.065,-15.165 -15.975,-26.205 -29.52,-28.68c-2.25,-0.405 -4.53,-0.57 -6.84,-0.48c-2.31,0.09 -4.635,0.45 -6.96,1.08c-16.98,4.56 -27.045,22.17 -24.6,40.56h-37.8v22.92l-5.64,-20.88c-0.135,-0.855 -0.03,-1.515 0.48,-2.4c0.615,-1.065 1.815,-2.445 3.36,-3.72c1.545,-1.275 3.435,-2.415 5.16,-3.72c1.725,-1.305 3.33,-2.535 4.56,-4.68h0.12c1.245,-2.145 1.38,-4.17 1.68,-6.24c0.3,-2.07 0.57,-4.215 1.08,-6.12c1.035,-3.81 2.4,-6.495 7.68,-7.92l85.8,-23.04c1.32,-0.36 2.46,-0.48 3.48,-0.48zM92.64,42.72c12.465,-0.405 24.78,9.075 28.68,23.64c0.945,3.54 1.35,7.05 1.2,10.44h-53.88c-2.535,-15.39 5.58,-29.715 18.72,-33.24c1.77,-0.48 3.495,-0.78 5.28,-0.84zM149.64,47.28c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM27,48c-0.255,0.66 -0.42,1.275 -0.6,1.92c-0.705,2.595 -0.915,5.01 -1.2,6.96c-0.285,1.95 -0.75,3.45 -0.84,3.6c-0.09,0.165 -1.14,1.26 -2.64,2.4c-1.5,1.14 -3.375,2.4 -5.28,3.96c-0.645,0.525 -1.29,1.08 -1.92,1.68l-4.32,-16.08zM149.64,54.96c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,84.48h17.4c-0.42,0.57 -0.735,1.215 -1.08,1.8c-1.35,2.325 -2.34,4.68 -3.12,6.48c-0.78,1.8 -1.56,3 -1.68,3.12c-0.135,0.135 -1.365,0.975 -3.12,1.68c-1.755,0.705 -3.885,1.515 -6.12,2.52c-0.75,0.345 -1.515,0.795 -2.28,1.2zM63.12,84.48h88.8c5.475,0 7.5,2.22 9.48,5.64c0.99,1.71 1.815,3.72 2.64,5.64c0.825,1.92 1.485,3.885 3.24,5.64c1.74,1.74 3.765,2.43 5.76,3.24c1.995,0.81 4.05,1.575 5.88,2.4c1.83,0.825 3.33,1.77 4.2,2.64c0.87,0.87 1.2,1.41 1.2,2.64v38.4c0,0.54 -0.045,0.645 -0.48,1.08c-0.435,0.435 -1.44,1.02 -2.64,1.56c-1.2,0.54 -2.535,1.11 -3.96,1.68c-1.425,0.57 -2.985,1.065 -4.44,2.52c-1.47,1.47 -1.935,2.85 -2.52,4.2c-0.585,1.35 -1.17,2.745 -1.8,3.84c-1.275,2.19 -2.13,3.36 -5.52,3.36h-110.88c-3.39,0 -4.245,-1.17 -5.52,-3.36c-0.63,-1.095 -1.215,-2.475 -1.8,-3.84c-0.585,-1.365 -1.05,-2.73 -2.52,-4.2c-1.455,-1.455 -3.015,-1.95 -4.44,-2.52c-1.425,-0.57 -2.76,-1.14 -3.96,-1.68c-1.2,-0.54 -2.205,-1.125 -2.64,-1.56c-0.435,-0.435 -0.48,-0.54 -0.48,-1.08v-22.08h0.12l-0.12,-0.48v-15.84c0,-1.23 0.33,-1.77 1.2,-2.64c0.87,-0.87 2.37,-1.815 4.2,-2.64c1.83,-0.825 3.885,-1.59 5.88,-2.4c1.995,-0.81 4.02,-1.5 5.76,-3.24c1.755,-1.755 2.4,-3.72 3.24,-5.64c0.84,-1.92 1.65,-3.93 2.64,-5.64c1.98,-3.42 4.005,-5.64 9.48,-5.64zM166.92,84.48h17.4v16.8c-0.75,-0.405 -1.53,-0.855 -2.28,-1.2c-2.235,-1.005 -4.365,-1.815 -6.12,-2.52c-1.755,-0.705 -2.985,-1.545 -3.12,-1.68c-0.12,-0.12 -0.9,-1.32 -1.68,-3.12c-0.78,-1.8 -1.77,-4.155 -3.12,-6.48c-0.345,-0.585 -0.66,-1.23 -1.08,-1.8zM107.52,88.32c-19.305,0 -34.56,17.475 -34.56,38.4c0,20.925 15.255,38.4 34.56,38.4c19.305,0 34.56,-17.475 34.56,-38.4c0,-20.925 -15.255,-38.4 -34.56,-38.4zM107.52,96c14.625,0 26.88,13.485 26.88,30.72c0,17.235 -12.255,30.72 -26.88,30.72c-14.625,0 -26.88,-13.485 -26.88,-30.72c0,-17.235 12.255,-30.72 26.88,-30.72zM51.84,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM163.2,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM51.84,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM163.2,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,160.44c1.575,0.705 3.045,1.215 4.2,1.68c1.17,0.48 1.965,1.005 1.8,0.84c-0.165,-0.165 0.42,0.69 0.96,1.92c0.48,1.11 1.095,2.565 1.92,4.08h-8.88zM184.32,160.44v8.52h-8.88c0.825,-1.515 1.44,-2.97 1.92,-4.08c0.54,-1.23 1.125,-2.085 0.96,-1.92c-0.165,0.165 0.615,-0.36 1.8,-0.84c1.155,-0.465 2.625,-0.975 4.2,-1.68z"></path></g></g></g></svg></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb">
                         <?= $currency[1]. " "; ?>
                          <span data-plugin="counterup"><?php
                                    if($totalRevenue!="")
                                      echo $totalRevenue;
                                    else
                                      echo '0';
                                      ;?></span>
                        </p>
                    </div>
                </div>
              <?php }?>
                   <?php

                        if($sitesetting->paidbannerstatus == 1)
                        {
                          ?>


                               <div class="col-lg-4 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box7 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                    <a href="<?php echo Yii::$app->homeUrl."products/index" ?>" class="whiteTxtClr">  <p class="mb-0 fontSize20"><?php echo Yii::t('app','Total').' '.Yii::t('app','Paid Banner'); ?></p></a>
                            </div>
                                         <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="f fontSize30 whiteTxtClr"> <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="#fff"><g id="surface1"><path d="M164.04,3.24l-3.72,0.96l-34.44,9.24c-0.21,0.045 -0.39,0.06 -0.6,0.12l-85.8,23.04l-34.92,9.36l-3.72,0.96l0.96,3.72l8.28,30.48v0.24l12.96,47.88v47.4h168.96v-99.84h-8.16l-18.84,-69.96zM158.52,12.6l4.32,16.2c-0.795,-0.195 -1.62,-0.465 -2.4,-0.6c-2.43,-0.39 -4.725,-0.615 -6.6,-0.84c-1.875,-0.225 -3.315,-0.63 -3.48,-0.72c-0.15,-0.09 -1.185,-1.095 -2.4,-2.64c-1.215,-1.545 -2.775,-3.51 -4.68,-5.4c-0.48,-0.48 -1.005,-0.99 -1.56,-1.44zM130.8,20.52c3.06,-0.015 4.98,1.395 7.08,3.48c1.395,1.395 2.655,3.15 3.96,4.8c1.305,1.65 2.535,3.315 4.68,4.56c2.145,1.23 4.215,1.41 6.36,1.68c2.145,0.27 4.38,0.39 6.36,0.72c1.98,0.33 3.615,0.825 4.68,1.44c0.975,0.57 1.47,1.065 1.8,2.04c0.03,0.09 0.09,0.135 0.12,0.24l9.96,37.32h-45.6c0.15,-4.08 -0.315,-8.28 -1.44,-12.48c-4.065,-15.165 -15.975,-26.205 -29.52,-28.68c-2.25,-0.405 -4.53,-0.57 -6.84,-0.48c-2.31,0.09 -4.635,0.45 -6.96,1.08c-16.98,4.56 -27.045,22.17 -24.6,40.56h-37.8v22.92l-5.64,-20.88c-0.135,-0.855 -0.03,-1.515 0.48,-2.4c0.615,-1.065 1.815,-2.445 3.36,-3.72c1.545,-1.275 3.435,-2.415 5.16,-3.72c1.725,-1.305 3.33,-2.535 4.56,-4.68h0.12c1.245,-2.145 1.38,-4.17 1.68,-6.24c0.3,-2.07 0.57,-4.215 1.08,-6.12c1.035,-3.81 2.4,-6.495 7.68,-7.92l85.8,-23.04c1.32,-0.36 2.46,-0.48 3.48,-0.48zM92.64,42.72c12.465,-0.405 24.78,9.075 28.68,23.64c0.945,3.54 1.35,7.05 1.2,10.44h-53.88c-2.535,-15.39 5.58,-29.715 18.72,-33.24c1.77,-0.48 3.495,-0.78 5.28,-0.84zM149.64,47.28c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM27,48c-0.255,0.66 -0.42,1.275 -0.6,1.92c-0.705,2.595 -0.915,5.01 -1.2,6.96c-0.285,1.95 -0.75,3.45 -0.84,3.6c-0.09,0.165 -1.14,1.26 -2.64,2.4c-1.5,1.14 -3.375,2.4 -5.28,3.96c-0.645,0.525 -1.29,1.08 -1.92,1.68l-4.32,-16.08zM149.64,54.96c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,84.48h17.4c-0.42,0.57 -0.735,1.215 -1.08,1.8c-1.35,2.325 -2.34,4.68 -3.12,6.48c-0.78,1.8 -1.56,3 -1.68,3.12c-0.135,0.135 -1.365,0.975 -3.12,1.68c-1.755,0.705 -3.885,1.515 -6.12,2.52c-0.75,0.345 -1.515,0.795 -2.28,1.2zM63.12,84.48h88.8c5.475,0 7.5,2.22 9.48,5.64c0.99,1.71 1.815,3.72 2.64,5.64c0.825,1.92 1.485,3.885 3.24,5.64c1.74,1.74 3.765,2.43 5.76,3.24c1.995,0.81 4.05,1.575 5.88,2.4c1.83,0.825 3.33,1.77 4.2,2.64c0.87,0.87 1.2,1.41 1.2,2.64v38.4c0,0.54 -0.045,0.645 -0.48,1.08c-0.435,0.435 -1.44,1.02 -2.64,1.56c-1.2,0.54 -2.535,1.11 -3.96,1.68c-1.425,0.57 -2.985,1.065 -4.44,2.52c-1.47,1.47 -1.935,2.85 -2.52,4.2c-0.585,1.35 -1.17,2.745 -1.8,3.84c-1.275,2.19 -2.13,3.36 -5.52,3.36h-110.88c-3.39,0 -4.245,-1.17 -5.52,-3.36c-0.63,-1.095 -1.215,-2.475 -1.8,-3.84c-0.585,-1.365 -1.05,-2.73 -2.52,-4.2c-1.455,-1.455 -3.015,-1.95 -4.44,-2.52c-1.425,-0.57 -2.76,-1.14 -3.96,-1.68c-1.2,-0.54 -2.205,-1.125 -2.64,-1.56c-0.435,-0.435 -0.48,-0.54 -0.48,-1.08v-22.08h0.12l-0.12,-0.48v-15.84c0,-1.23 0.33,-1.77 1.2,-2.64c0.87,-0.87 2.37,-1.815 4.2,-2.64c1.83,-0.825 3.885,-1.59 5.88,-2.4c1.995,-0.81 4.02,-1.5 5.76,-3.24c1.755,-1.755 2.4,-3.72 3.24,-5.64c0.84,-1.92 1.65,-3.93 2.64,-5.64c1.98,-3.42 4.005,-5.64 9.48,-5.64zM166.92,84.48h17.4v16.8c-0.75,-0.405 -1.53,-0.855 -2.28,-1.2c-2.235,-1.005 -4.365,-1.815 -6.12,-2.52c-1.755,-0.705 -2.985,-1.545 -3.12,-1.68c-0.12,-0.12 -0.9,-1.32 -1.68,-3.12c-0.78,-1.8 -1.77,-4.155 -3.12,-6.48c-0.345,-0.585 -0.66,-1.23 -1.08,-1.8zM107.52,88.32c-19.305,0 -34.56,17.475 -34.56,38.4c0,20.925 15.255,38.4 34.56,38.4c19.305,0 34.56,-17.475 34.56,-38.4c0,-20.925 -15.255,-38.4 -34.56,-38.4zM107.52,96c14.625,0 26.88,13.485 26.88,30.72c0,17.235 -12.255,30.72 -26.88,30.72c-14.625,0 -26.88,-13.485 -26.88,-30.72c0,-17.235 12.255,-30.72 26.88,-30.72zM51.84,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM163.2,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM51.84,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM163.2,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,160.44c1.575,0.705 3.045,1.215 4.2,1.68c1.17,0.48 1.965,1.005 1.8,0.84c-0.165,-0.165 0.42,0.69 0.96,1.92c0.48,1.11 1.095,2.565 1.92,4.08h-8.88zM184.32,160.44v8.52h-8.88c0.825,-1.515 1.44,-2.97 1.92,-4.08c0.54,-1.23 1.125,-2.085 0.96,-1.92c-0.165,0.165 0.615,-0.36 1.8,-0.84c1.155,-0.465 2.625,-0.975 4.2,-1.68z"></path></g></g></g></svg></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb">
                           <?= $currency[1]; ?>
                          <span data-plugin="counterup">  <?php
                                    if($paidbanner!="")
                                      echo $paidbanner;
                                    else
                                      echo '0';
                                      ;?></span> 
                         
                        </p>

                    </div>
                </div>
                           
                          <?php    
                        }
                      ?>


                        <?php
                          if($sitesetting->promotionStatus == 1)
                          {
                            ?>

                             <div class="col-lg-4 col-md-6 col-sm-6 col-12 m-b15">
                    <div class="box8 whiteTxtClr rounded p-t10 p-b10 p-r20 p-l20">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                                    <a href="#" class="whiteTxtClr">     <p class="mb-0 fontSize20"><?php echo Yii::t('app','Total').' '.Yii::t('app','Promotions'); ?></p></a>
                            </div>
                                            <div class="borderWhite width50 h-25 text-center rounded-circle"><i class="f fontSize30 whiteTxtClr"> <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="#fff"><g id="surface1"><path d="M164.04,3.24l-3.72,0.96l-34.44,9.24c-0.21,0.045 -0.39,0.06 -0.6,0.12l-85.8,23.04l-34.92,9.36l-3.72,0.96l0.96,3.72l8.28,30.48v0.24l12.96,47.88v47.4h168.96v-99.84h-8.16l-18.84,-69.96zM158.52,12.6l4.32,16.2c-0.795,-0.195 -1.62,-0.465 -2.4,-0.6c-2.43,-0.39 -4.725,-0.615 -6.6,-0.84c-1.875,-0.225 -3.315,-0.63 -3.48,-0.72c-0.15,-0.09 -1.185,-1.095 -2.4,-2.64c-1.215,-1.545 -2.775,-3.51 -4.68,-5.4c-0.48,-0.48 -1.005,-0.99 -1.56,-1.44zM130.8,20.52c3.06,-0.015 4.98,1.395 7.08,3.48c1.395,1.395 2.655,3.15 3.96,4.8c1.305,1.65 2.535,3.315 4.68,4.56c2.145,1.23 4.215,1.41 6.36,1.68c2.145,0.27 4.38,0.39 6.36,0.72c1.98,0.33 3.615,0.825 4.68,1.44c0.975,0.57 1.47,1.065 1.8,2.04c0.03,0.09 0.09,0.135 0.12,0.24l9.96,37.32h-45.6c0.15,-4.08 -0.315,-8.28 -1.44,-12.48c-4.065,-15.165 -15.975,-26.205 -29.52,-28.68c-2.25,-0.405 -4.53,-0.57 -6.84,-0.48c-2.31,0.09 -4.635,0.45 -6.96,1.08c-16.98,4.56 -27.045,22.17 -24.6,40.56h-37.8v22.92l-5.64,-20.88c-0.135,-0.855 -0.03,-1.515 0.48,-2.4c0.615,-1.065 1.815,-2.445 3.36,-3.72c1.545,-1.275 3.435,-2.415 5.16,-3.72c1.725,-1.305 3.33,-2.535 4.56,-4.68h0.12c1.245,-2.145 1.38,-4.17 1.68,-6.24c0.3,-2.07 0.57,-4.215 1.08,-6.12c1.035,-3.81 2.4,-6.495 7.68,-7.92l85.8,-23.04c1.32,-0.36 2.46,-0.48 3.48,-0.48zM92.64,42.72c12.465,-0.405 24.78,9.075 28.68,23.64c0.945,3.54 1.35,7.05 1.2,10.44h-53.88c-2.535,-15.39 5.58,-29.715 18.72,-33.24c1.77,-0.48 3.495,-0.78 5.28,-0.84zM149.64,47.28c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM27,48c-0.255,0.66 -0.42,1.275 -0.6,1.92c-0.705,2.595 -0.915,5.01 -1.2,6.96c-0.285,1.95 -0.75,3.45 -0.84,3.6c-0.09,0.165 -1.14,1.26 -2.64,2.4c-1.5,1.14 -3.375,2.4 -5.28,3.96c-0.645,0.525 -1.29,1.08 -1.92,1.68l-4.32,-16.08zM149.64,54.96c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,84.48h17.4c-0.42,0.57 -0.735,1.215 -1.08,1.8c-1.35,2.325 -2.34,4.68 -3.12,6.48c-0.78,1.8 -1.56,3 -1.68,3.12c-0.135,0.135 -1.365,0.975 -3.12,1.68c-1.755,0.705 -3.885,1.515 -6.12,2.52c-0.75,0.345 -1.515,0.795 -2.28,1.2zM63.12,84.48h88.8c5.475,0 7.5,2.22 9.48,5.64c0.99,1.71 1.815,3.72 2.64,5.64c0.825,1.92 1.485,3.885 3.24,5.64c1.74,1.74 3.765,2.43 5.76,3.24c1.995,0.81 4.05,1.575 5.88,2.4c1.83,0.825 3.33,1.77 4.2,2.64c0.87,0.87 1.2,1.41 1.2,2.64v38.4c0,0.54 -0.045,0.645 -0.48,1.08c-0.435,0.435 -1.44,1.02 -2.64,1.56c-1.2,0.54 -2.535,1.11 -3.96,1.68c-1.425,0.57 -2.985,1.065 -4.44,2.52c-1.47,1.47 -1.935,2.85 -2.52,4.2c-0.585,1.35 -1.17,2.745 -1.8,3.84c-1.275,2.19 -2.13,3.36 -5.52,3.36h-110.88c-3.39,0 -4.245,-1.17 -5.52,-3.36c-0.63,-1.095 -1.215,-2.475 -1.8,-3.84c-0.585,-1.365 -1.05,-2.73 -2.52,-4.2c-1.455,-1.455 -3.015,-1.95 -4.44,-2.52c-1.425,-0.57 -2.76,-1.14 -3.96,-1.68c-1.2,-0.54 -2.205,-1.125 -2.64,-1.56c-0.435,-0.435 -0.48,-0.54 -0.48,-1.08v-22.08h0.12l-0.12,-0.48v-15.84c0,-1.23 0.33,-1.77 1.2,-2.64c0.87,-0.87 2.37,-1.815 4.2,-2.64c1.83,-0.825 3.885,-1.59 5.88,-2.4c1.995,-0.81 4.02,-1.5 5.76,-3.24c1.755,-1.755 2.4,-3.72 3.24,-5.64c0.84,-1.92 1.65,-3.93 2.64,-5.64c1.98,-3.42 4.005,-5.64 9.48,-5.64zM166.92,84.48h17.4v16.8c-0.75,-0.405 -1.53,-0.855 -2.28,-1.2c-2.235,-1.005 -4.365,-1.815 -6.12,-2.52c-1.755,-0.705 -2.985,-1.545 -3.12,-1.68c-0.12,-0.12 -0.9,-1.32 -1.68,-3.12c-0.78,-1.8 -1.77,-4.155 -3.12,-6.48c-0.345,-0.585 -0.66,-1.23 -1.08,-1.8zM107.52,88.32c-19.305,0 -34.56,17.475 -34.56,38.4c0,20.925 15.255,38.4 34.56,38.4c19.305,0 34.56,-17.475 34.56,-38.4c0,-20.925 -15.255,-38.4 -34.56,-38.4zM107.52,96c14.625,0 26.88,13.485 26.88,30.72c0,17.235 -12.255,30.72 -26.88,30.72c-14.625,0 -26.88,-13.485 -26.88,-30.72c0,-17.235 12.255,-30.72 26.88,-30.72zM51.84,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM163.2,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM51.84,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM163.2,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,160.44c1.575,0.705 3.045,1.215 4.2,1.68c1.17,0.48 1.965,1.005 1.8,0.84c-0.165,-0.165 0.42,0.69 0.96,1.92c0.48,1.11 1.095,2.565 1.92,4.08h-8.88zM184.32,160.44v8.52h-8.88c0.825,-1.515 1.44,-2.97 1.92,-4.08c0.54,-1.23 1.125,-2.085 0.96,-1.92c-0.165,0.165 0.615,-0.36 1.8,-0.84c1.155,-0.465 2.625,-0.975 4.2,-1.68z"></path></g></g></g></svg></i></div>
                        </div>
                        <p class="mb-0 fontSize30 fontSb">
                          <?= $currency[1]; ?>
                          <span data-plugin="counterup"> <?php
                                    if($promotionAmt!="")
                                      echo $promotionAmt;
                                    else
                                      echo '0';
                                      ;?>
                                  
                          </span> 
                          
                        </p>
                    </div>
                </div>
                           
                          <?php  
                          }
                          ?>
            </section>




                    
<?php
if($sitesetting->promotionStatus != 1) {
  $promotiondisp = "display:none";
 
}
?>
<section class="row" style="<?php echo $promotiondisp; ?>">
<div class="col-lg-8 col-md-6 col-sm-6 col-12 m-b15">
<div class="cardbox">
<div class="form-group pull-right col-md-3">
                <select id="dailyIncome-selector" class="form-control select-box-down-arrow " onchange="IncomeReport(this.val)">

<?php 
           if(!isset($_SESSION['reportIncome'])) {
             $_SESSION['reportIncome'] = 'daily';
           }
           if($_SESSION['reportIncome'] == 'daily') {
             echo '<option selected value="daily">Daily</option>';
           } else {
             echo '<option value="daily">Daily</option>';
           }?>
           <?php if($_SESSION['reportIncome'] == 'monthly') {
             echo '<option selected value="monthly">Monthly</option>';
           } else {
             echo '<option value="monthly">Monthly</option>';
           }?>

            <?php 
              if($_SESSION['reportIncome'] == 'year') {
                echo '<option selected value="year">Yearly</option>';
              }else {
                echo '<option value="year">Yearly</option>';
              }
           ?>

</select></div>
<h6 class="text-dark header-title m-t-0 m-b-30"><?php echo Yii::t('app','Income Statistics'); ?></h6>
<?php if($sitePaymentMode->buynowPaymentMode == 1) 
{
  $itemlist[] = ['Date','Urgent', 'Ads','Orders',  [ 'role' => 'annotation' ]];
}
else
{
  $itemlist[] = ['Date', 'Urgent', 'Ads', [ 'role' => 'annotation' ]];
}
?>

<?php 
//$itemlist[] = ['Date', 'Orders', 'Ads', 'Urgent', [ 'role' => 'annotation' ]];
// $itemlist[] = ['Date', 'Orders', 'Urgent', 'Ads', [ 'role' => 'annotation' ]];
foreach ($getDailyrevenue as $key => $value) {

  $incometemp = explode(',', $value);
  $date[$key][] = $labeltoRevenue[$key];
  $incomearr[$key] = array_merge($date[$key],$incometemp); 

}

$incomedata = array_merge($itemlist,$incomearr);


?>

<div>
<?php
if($sitesetting->promotionStatus == 0)
  $style = 'display:none;';
else
  $style = 'display:block;';
?>
<div id="morris-bar-chart" class="bgWhite m-b20 m-t20" style="<?= $style; ?>">

 <?= ColumnChart::widget([
  'id' => 'my-stacked-column-chart-id',
    'data' => [
    $incomedata
  ],
    'options' => [
        'fontName' => 'Verdana',
        'height' => 350,
        'fontSize' => 12,
        'chartArea' => [
          'left' => '5%',
          'width' => '100%',
          'height' => 300
        ],
        'isStacked' => true,
        'tooltip' => [
          'textStyle' => [
            'fontName' => 'Verdana',
            'fontSize' => 13
          ]
        ],
        'vAxis' => [
          //'title' => 'Income statistics',
          'titleTextStyle' => [
            'fontSize' => 13,
            'italic' => false
          ],
                 'gridlines' => [
            'color' => '#e5e5e5',
            'count' => 10
          ],              
          'minValue' => 0,
          //'maxValue' => 200,
        ],
        'legend' => [
          'position' => 'top',
          'alignment' => 'center',
          'textStyle' => [
            'fontSize' => 12
          ]
        ]            
    ]
]) ?>
                        </div>

            </div>
</div>
</div>

<?php
if($sitesetting->promotionStatus == 1)
                          {
    ?>
  <div class="col-lg-4 col-md-6 col-sm-6 col-12 m-b15">
                <div class="cardbox">
                <h6 class="text-dark header-title m-t-0 m-b-0"><?php echo Yii::t('app',"Promotions") ?></h6>
						<div id="morris-donut-chart" class="p-3 bgWhite m-b20">
						<?= ChartJs::widget([
    'type' => 'doughnut',
    'options' => [
        'height' => 350,
        'width' => 280
    ],
    'data' => [
        'labels' => [Yii::t('app','Ads Promotions'),Yii::t('app','Urgent Promotions')],
        'datasets' => [
            [
                'label' => "My First dataset",
                'backgroundColor' => [
                    '#582a20',
                    '#BE7052',
                 
             
            ],
                'borderColor' => "rgba(179,181,198,1)",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => [$adspromotionAmt, $urgentpromotionAmt]
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
            </section>
<?php } ?>




 <!-- <div class="row">

                   	<div class="col-lg-8">
	    		<div class="card-box">
                <div class="form-group pull-right col-md-2">
                <select id="revenue-selector" class="form-control select-box-down-arrow " onchange="reportRevenue(this.val)">

<?php 
           if(!isset($_SESSION['reportRevenue'])) {
             $_SESSION['reportRevenue'] = 'daily';
           }
           if($_SESSION['reportRevenue'] == 'daily') {
             echo '<option selected value="daily">Daily</option>';
           } else {
             echo '<option value="daily">Daily</option>';
           }?>
           <?php if($_SESSION['reportRevenue'] == 'monthly') {
             echo '<option selected value="monthly">Monthly</option>';
           } else {
             echo '<option value="monthly">Monthly</option>';
           }?>

            <?php if($_SESSION['reportRevenue'] == 'year') {
             echo '<option selected value="year">Yearly</option>';
           } else {
             echo '<option value="year">Yearly</option>';
           }?>

</select></div>
	    			<h4 class="text-dark header-title m-t-0 m-b-30"><?php echo Yii::t('app','Buynow').' '.Yii::t('app','Commisions'); ?></h4>



	    			<div>
	                	<div id="morris-bar-chart" style="height: 320px;">
						<?= ChartJs::widget([
    'type' => 'line',
    'data' => [

		'labels' =>$label,
        'datasets' => [  
            [
                'label' => Yii::t('app','Buynow').' '.Yii::t('app','Commisions'),
                'backgroundColor' => "#ffe6f7",
                'borderColor' => "#ff99bb",
                'pointBackgroundColor' => "#ff99bb",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => $getTotalrevenue              		
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
						</div>
	                </div>
	    		</div>
	    	</div>
         
            </div>



                <div class="row">
                <div class="col-lg-8">
				<div class="card-box">
                <div class="form-group pull-right col-md-2">
                <select id="promotions-selector" class="form-control select-box-down-arrow " onchange="PromotionsRevenue(this.val)">

<?php 
           if(!isset($_SESSION['promotionsRevenue'])) {
             $_SESSION['promotionsRevenue'] = 'daily';
           }
           if($_SESSION['promotionsRevenue'] == 'daily') {
             echo '<option selected value="daily">Daily</option>';
           } else {
             echo '<option value="daily">Daily</option>';
           }?>
           <?php if($_SESSION['promotionsRevenue'] == 'monthly') {
             echo '<option selected value="monthly">Monthly</option>';
           } else {
             echo '<option value="monthly">Monthly</option>';
           }?>

            <?php if($_SESSION['promotionsRevenue'] == 'year') {
             echo '<option selected value="year">Yearly</option>';
           } else {
             echo '<option value="year">Yearly</option>';
           }?>

</select></div>
					<h4 class="text-dark header-title m-t-0 m-b-0"><?php echo Yii::t('app','Promotion'); ?></h4>
					<div>
						<div id="morris-donut-chart" style="height: 350px;">
						<?= ChartJs::widget([
    'type' => 'bar',
      'data' => [

		'labels' =>$currentLabel,

        'datasets' => [  
            [
                'label' => Yii::t('app','Ads Promotions'),
                'backgroundColor' => "#008000",
                'borderColor' => "rgba(179,181,198,1)",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => $currentadPromotion             
			],
			[
                'label' => Yii::t('app','Urgent Promotions'),
                'backgroundColor' => "#1a75ff",
                'borderColor' => "rgba(179,181,198,1)",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => $currentUrgentPromotion             		
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
			
						</div>
					</div>
				</div>
			</div>

</div> -->

 
<script>
function reportRevenue() {
    
    var revenue = $("#revenue-selector").val();
    //console.log(items);
        $.ajax({
            type : 'GET',
            url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/admin/datarevenue',
         // async: false,
            data : {
                revenue : revenue,
            },
           dataType : "text",
            success : function(data) 
            {
                window.location.reload();
            },
            error: function(err)
            {
                console.log("Error");
            }
    });
}




function PromotionsRevenue() {
    
    var promotions = $("#promotions-selector").val();
    //console.log(items);
        $.ajax({
            type : 'GET',
            url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/admin/datapromotionrevenue',
         // async: false,
            data : {
                promotions : promotions,
            },
           dataType : "text",
            success : function(data) {
               
             //    alert(data);
                window.location.reload();
                },
                error: function(err)
                {
                    console.log("Error");
                }
    });
}
function IncomeReport() {
    
    var dailyincome = $("#dailyIncome-selector").val();
    //console.log(dailyincome);
        $.ajax({
            type : 'GET',
            url : '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/admin/incomedata',
         // async: false,
            data : {
                dailyincome : dailyincome,
            },
           dataType : "text",
            success : function(data) {
              var obj = data;
              google.charts.load('current', {packages: ['corechart']});
               
              function drawChart() {


            // Define the chart to be drawn.
            var data = google.visualization.arrayToDataTable(
              JSON.parse(obj)
            );

            var options = {
              isStacked:true,
              fontSize:12,
              fontName: 'Verdana',
              legend: { position: 'top','alignment': 'center','textStyle': {'color': '#000','fontSize': 12} },
              height:350,
              chartArea: {width:550,height:280},
              vAxis:{
                titleTextStyle:{fontSize: 13},
                gridlines: {color:'#e5e5e5',count:10}
              }
            };  

            // Instantiate and draw the chart.
            var chart = new google.visualization.ColumnChart(document.getElementById('my-stacked-column-chart-id'));
            chart.draw(data, options);
         }
         google.charts.setOnLoadCallback(drawChart);

                },
                error: function(err)
                {
                    console.log("Error");
                }
    });
}


</script>    
<?php

$siteSettings = yii::$app->Myclass->getSitesettings();
//print_r($siteSettings->googleapikey);exit;
if (!empty($siteSettings) && isset($siteSettings->googleapikey) && $siteSettings->googleapikey != "")
    $googleapikey = $siteSettings->googleapikey;
else
    $googleapikey = "";
?>
   <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapikey; ?>&libraries=places&callback=initMap&language=en"
        async defer></script>     
        <script src="https://www.gstatic.com/charts/loader.js"></script>


<style type="text/css">
  .box6{
background-image: linear-gradient(25deg, #0c3483 0%, #a2b6df 100%);}

.box7{
background-image: linear-gradient(25deg, #f77062 0%, #fe5196 100%);}

.box8{
background-image: linear-gradient(25deg, #fc6076 0%, #ff9a44 100%);}

</style>