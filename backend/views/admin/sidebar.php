<style type="text/css">
#testDiv
{
  height: 95vh !important;
} 
</style>

<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
 use common\models\Admin;
 use backend\models\Roles;
 use common\models\Sitesettings;
 $logo = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
 $path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
 $path = $path1.'/media/logo'.'/';
 $sitesetting = yii::$app->Myclass->getSitesettings();
 $adminPr = Admin::find()->where(['id'=> Yii::$app->user->id])->one();
 $role = Roles::find()->where(['id'=> $adminPr['role']])->one();
$path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
$path = $path1.'/media/logo'.'/'; 


 if(isset($role['priviliges'])) {
    $priviliges= json_decode($role['priviliges']);
 }
 else
 {
    $priviliges=array();  
 }

 $getbuyNowdata = json_decode($sitesetting->sitepaymentmodes);


 ?>
 

  <nav id="sidebar" class="admin-scroll">
  <div class="examples">
  <div id="testDiv"  >
     
            <div class="sidebar-header text-center">
            <a href="<?php echo Yii::$app->homeUrl; ?>" class="logo"><img src="<?=$path.$logo->logoDarkVersion?>" class="height40"></a>
            </div>

            

            <ul class="list-unstyled components mainsidemenu">

            <?php 
                        $request = parse_url($_SERVER['REQUEST_URI']);
                        $path = $request["path"];
                        $result = trim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $path), '/');

                        $result = explode('/', $result);
                        $actionName = Yii::$app->controller->action->id;
                    
                         $active = '';$subActive = '';$fill='#000';
                   
                         $controllerName = Yii::$app->controller->id;
                         
                       
                        if ($controllerName == 'admin')
                        {
                   
                     
                
                        
                         if ($actionName == 'dashboard') {
                        $subActive = 'active';
                           $fill = '#007bff';
                                $active = 'active';
                     
                       }
                         }
                       
                        
                        $sitesetting = yii::$app->Myclass->getSitesettings();
                        $paymentmode = json_decode($sitesetting->sitepaymentmodes,true);
                        ?>

                <li class="<?php echo $active; ?>">
                        <a href="<?= Yii::$app->urlManager->createUrl(['index'])?>">
                  
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                width="20" height="20"
                                viewBox="0 0 192 192"
                                style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><path d="M19.2,15.36c-6.319,0 -11.52,5.20528 -11.52,11.52v34.56v38.4v65.28c0,6.31472 5.201,11.52 11.52,11.52h153.6c6.31704,0 11.52,-5.20296 11.52,-11.52v-65.28v-38.4v-19.2c0,-6.31704 -5.20296,-11.52 -11.52,-11.52h-103.68c0.33243,0 -0.11908,0.0014 -1.0575,-1.08c-0.93843,-1.0814 -2.09353,-2.87543 -3.3,-4.8c-1.20647,-1.92457 -2.47087,-3.97809 -4.065,-5.7825c-1.59413,-1.80441 -3.79349,-3.6975 -6.9375,-3.6975zM19.2,23.04h34.56c-0.23328,0 0.23478,0.02692 1.185,1.1025c0.95023,1.07559 2.10677,2.86207 3.3075,4.7775c1.20073,1.91543 2.44419,3.9614 4.005,5.76c1.56081,1.7986 3.70958,3.72 6.8625,3.72h103.68c2.16168,0 3.84,1.67832 3.84,3.84v8.4c-1.20919,-0.43607 -2.48892,-0.72 -3.84,-0.72h-153.6c-1.35108,0 -2.63082,0.28393 -3.84,0.72v-23.76c0,-2.164 1.6726,-3.84 3.84,-3.84zM19.2,57.6h153.6c2.16972,0 3.84,1.67028 3.84,3.84v38.4v65.28c0,2.16168 -1.67832,3.84 -3.84,3.84h-153.6c-2.1674,0 -3.84,-1.676 -3.84,-3.84v-65.28v-38.4c0,-2.16972 1.67028,-3.84 3.84,-3.84z"></path></g></g></svg>
                        <?php echo Yii::t("app","Dashboard"); ?> </a>

                  
                </li>
                      <?php 
                          $promotionStatus = $sitesetting->promotionStatus;
                          $paidbannerStatus = $sitesetting->paidbannerstatus;
                          $getbuyNowdata->buynowPaymentMode;

                      if(($promotionStatus ==0) && 
                            ($paidbannerStatus == 0) && 
                            ($getbuyNowdata->buynowPaymentMode == 0))
                          {
                            $style = 'display:none;';
                            
                          }else{
                            $style = 'display:block;';
                          }
               if(
                            (empty($role) || 
                            in_array('revenue', $priviliges) || 
                            in_array('revenuelog', $priviliges) || 
                            in_array('paidbanner', $priviliges) || 
                            in_array('promotionlog', $priviliges))
                            ) {   
                                $active = '';$subActive = ''; $display='';
                                  $fill='#000';
                            if ($controllerName == 'admin' && $actionName == 'revenue' )
                              {$active = 'active';$fill = '#007bff'; $subActive="show";}
                            if ($controllerName == 'admin' && $actionName == 'revenuelog')
                                {$active = 'active';$fill = '#007bff'; $subActive="show";}
                            if ($controllerName == 'admin' && $actionName == 'promotion')
                                {$active = 'active';$fill = '#007bff'; $subActive="show";}
                            if ($controllerName == 'admin' && $actionName == 'paidbanner')
                                {$active = 'active';$fill = '#007bff'; $subActive="show";}

                          //  if ($controllerName == 'admin'){
                           //$display = "display:block";$fill = '#007bff';$subActive='show';
                             //} 
                            ?>
                  <li class="has_sub <?php echo $active; ?>" style="<?= $style; ?>">
                    <a href="#Revenue" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">




<svg xmlns="http://www.w3.org/2000/svg" wx="0px" y="0px"
width="20" height="20"
 viewBox="0 0 479.998 487.992" style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1">
  <g id="money" transform="translate(-3.997 0)">
    <g id="Group_2485" data-name="Group 2485">
      <g id="Group_2484" data-name="Group 2484">
        <path id="Path_4392" data-name="Path 4392" d="M207.677,17.656l8.64,13.472a97.327,97.327,0,0,1,103.336,0l8.64-13.472A111.827,111.827,0,0,0,207.677,17.656Z"/>
      </g>
    </g>
    <g id="Group_2487" data-name="Group 2487">
      <g id="Group_2486" data-name="Group 2486">
        <path id="Path_4393" data-name="Path 4393" d="M172,111.992H156a111.212,111.212,0,0,0,8.624,43.112l14.768-6.176A95.181,95.181,0,0,1,172,111.992Z"/>
      </g>
    </g>
    <g id="Group_2489" data-name="Group 2489">
      <g id="Group_2488" data-name="Group 2488">
        <path id="Path_4394" data-name="Path 4394" d="M304.893,200.608a94.962,94.962,0,0,1-36.9,7.384v16a110.992,110.992,0,0,0,43.088-8.616Z"/>
      </g>
    </g>
    <g id="Group_2491" data-name="Group 2491">
      <g id="Group_2490" data-name="Group 2490">
        <path id="Path_4395" data-name="Path 4395" d="M364,111.992a95.135,95.135,0,0,1-7.384,36.912l14.768,6.176A111.028,111.028,0,0,0,380,111.992H364Z"/>
      </g>
    </g>
    <g id="Group_2493" data-name="Group 2493">
      <g id="Group_2492" data-name="Group 2492">
        <path id="Path_4396" data-name="Path 4396" d="M268,31.992a80,80,0,1,0,80,80A80,80,0,0,0,268,31.992Zm0,144a64,64,0,1,1,64-64A64,64,0,0,1,268,175.992Z"/>
      </g>
    </g>
    <g id="Group_2495" data-name="Group 2495">
      <g id="Group_2494" data-name="Group 2494">
        <path id="Path_4397" data-name="Path 4397" d="M285.6,109.44l-9.6-3.2V85.712a12,12,0,0,1,8,10.28h16a28.96,28.96,0,0,0-24-27.008V63.992H260v4.992a28.96,28.96,0,0,0-24,27.008,20.105,20.105,0,0,0,14.4,18.552l9.6,3.2v20.512a12,12,0,0,1-8-10.264H236A28.96,28.96,0,0,0,260,155v4.992h16V155a28.96,28.96,0,0,0,24-27.008A20.106,20.106,0,0,0,285.6,109.44ZM260,100.912l-4.56-1.536C253.413,98.7,252,97.3,252,95.992a12,12,0,0,1,8-10.28Zm16,37.36v-15.2l4.56,1.536c2.024.68,3.44,2.072,3.44,3.384A12,12,0,0,1,276,138.272Z"/>
      </g>
    </g>
    <g id="Group_2497" data-name="Group 2497">
      <g id="Group_2496" data-name="Group 2496">
        <path id="Path_4398" data-name="Path 4398" d="M481.653,330.336l-26.344-26.344,26.344-26.344a8,8,0,0,0,0-11.312l-104-104a8,8,0,0,0-11.312,0L264,264.68,153.653,154.336a8,8,0,0,0-11.312,0l-104,104a8,8,0,0,0,0,11.312l58.344,58.344H4v16H108a7.891,7.891,0,0,0,3.152-.648l54.488-23.352H284v32H196a8,8,0,0,0-3.352,15.264l104,48a8,8,0,0,0,8-.8L443.2,314.472l21.064,21.064L305.037,471.992H261.429l-150.664-55.5a8.019,8.019,0,0,0-2.768-.5H4v16H106.565l150.664,55.5a8,8,0,0,0,2.768.5h48a7.952,7.952,0,0,0,5.2-1.928l168-144a8,8,0,0,0,.87-11.28C481.935,330.63,481.8,330.48,481.653,330.336ZM164,303.992a7.874,7.874,0,0,0-3.152.648l-1.944.8a39.776,39.776,0,0,1,73.64-1.448Zm85.64,0a55.76,55.76,0,0,0-53.64-40A56.361,56.361,0,0,0,140.4,313.4l-24.984,10.7L55.309,263.992,148,171.3,280.685,303.992Zm49.472,94.776-66.688-30.776H292a8,8,0,0,0,8-8V323.3l43.44,43.44Zm1.64-97.336a35.912,35.912,0,1,1,41.808,41.808Zm54.792,54.776a51.937,51.937,0,0,0,9.165-4.839l-8.133,5.871ZM438.8,297.872l-70.5,50.92a52,52,0,1,0-80.545-60.336l-12.456-12.464L372,179.3l92.688,92.688Z"/>
      </g>
    </g>
  </g>
</g></g></g></svg>

                        <?=Yii::t('app','Revenue')?> <i class="mdi mdi-chevron-down float-right"></i></a>

                    <ul class="collapse list-unstyled Submenu <?=$subActive?>" id="Revenue">
                    <?php if ($controllerName == 'admin')
                                $subActive = 'active';
                                if(empty($role) ||in_array('revenue', $priviliges)) { ?>  
                                    <?php
                                    $subActive = '';  $fill='#000';
                                    if ($controllerName == 'admin' && $actionName == 'revenue')
                                    $subActive = 'active'; $fill = '#007bff';  ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['admin/revenue'])?>"><?php echo Yii::t('app','Revenue').' '.Yii::t('app','Management'); ?></a>
                        </li>
                          <?php } ?>
                         <?php if((empty($role) ||in_array('revenuelog', $priviliges))
                                        && ($getbuyNowdata->buynowPaymentMode==1)
                                    ) { ?>  
                                    <?php $subActive = '';
                                     if ($controllerName == 'admin' && $actionName == 'revenuelog')
                                    $subActive = 'active'; $fill = '#007bff';  ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['admin/revenuelog'])?>"><?php echo Yii::t('app','Buynow').' '.Yii::t('app','Revenuelog'); ?></a>
                        </li>
                           <?php }   
                           if( (empty($role) ||in_array('promotionlog', $priviliges)) &&
                                        ($sitesetting->promotionStatus == 1)
                                        ) { ?>  
                                    <?php $subActive = '';
                                     if ($controllerName == 'admin' && $actionName == 'promotion')
                                    $subActive = 'active';  $fill = '#007bff'; ?>
                        <li>
                          
                            <a href="<?= Yii::$app->urlManager->createUrl(['admin/promotion'])?>"><?php echo Yii::t('app','Promotions').' '.Yii::t('app','Logs'); ?></a>
                        </li>
                           <?php } ?>


                         <?php if( (empty($role) ||in_array('paidbanner', $priviliges)) ){
                          if(($sitesetting->paidbannerstatus==1)
                                    ) { ?>  
                                    <?php $subActive = '';
                                     if ($controllerName == 'admin' && $actionName == 'paidbanner')
                                    $subActive = 'active'; $fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['admin/paidbanner'])?>"><?php echo Yii::t('app','Paid').' '.Yii::t('app','Banner').' '.Yii::t('app','Logs'); ?></a>
                        </li>
                           <?php } }?>
                       
                     
                        
                    </ul>
                </li>
                  <?php } ?>    

             <?php if(empty($role) || in_array('roles', $priviliges) ||  in_array('moderator', $priviliges) ) { ?>
                              <?php $active = '';$subActive = ''; $display=''; $fill='#000';
                         
                               if ($controllerName == 'roles')
                              {$active = 'active';$fill = '#007bff'; $subActive="show";}
                            if ($controllerName == 'moderator')
                                {$active = 'active';$fill = '#007bff'; $subActive="show";}
                           
                            ?>
                  <li class="has_sub <?php echo $active; ?>">
                    <a href="#Roles" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" style=" fill:#000000;margin-right: 10px" viewBox="0 0 531.364 531.365"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1">
  <g id="role" transform="translate(-1 -1)">
    <path id="Path_4409" data-name="Path 4409" d="M144.986,124.982v-8.57a36.1,36.1,0,0,0-20.072-32.473l-12.967-6.479a34.148,34.148,0,0,0,7.328-21.04V40.735c0-19.061-14.253-34.736-32.448-35.687A33.967,33.967,0,0,0,61.4,14.407,34.419,34.419,0,0,0,50.711,39.278V56.419a34.192,34.192,0,0,0,7.319,21.04L45.063,83.938A36.106,36.106,0,0,0,25,116.411v8.57ZM52.734,99.271,76.422,87.426V71.64l-2.837-2.554a16.893,16.893,0,0,1-5.734-12.667V39.278A17.145,17.145,0,0,1,85.918,22.163c8.939.471,16.215,8.8,16.215,18.572V56.419A16.893,16.893,0,0,1,96.4,69.086L93.563,71.64V87.426l23.689,11.844a19.125,19.125,0,0,1,8.6,8.57h-81.7a19.064,19.064,0,0,1,8.588-8.57Z" transform="translate(181.69 30.285)"/>
    <path id="Path_4410" data-name="Path 4410" d="M480.025,108.336l-4.7-6.308L360.271,173.094,431.337,58.036l-6.308-4.7a265.7,265.7,0,0,0-316.693,0l-6.308,4.7,71.066,115.058L58.036,102.028l-4.7,6.308a265.7,265.7,0,0,0,0,316.693l4.7,6.308,115.058-71.066L102.028,475.329l6.308,4.7a265.7,265.7,0,0,0,316.693,0l6.308-4.7L360.271,360.271l115.058,71.066,4.7-6.308a265.7,265.7,0,0,0,0-316.693ZM124.945,62.51a248.575,248.575,0,0,1,283.475,0L327.258,193.928l-9.2-9.2-18.829,3.377-10.936-15.7H245.059l-10.927,15.7L215.3,184.732l-9.2,9.2ZM330.18,312.268,312.268,330.18l-20.843-3.745-12.076,17.381H254.024L241.94,326.444,221.1,330.189l-17.912-17.912,3.745-20.843-17.381-12.084V254.024l17.372-12.093L203.176,221.1l17.912-17.9,20.843,3.737,12.084-17.381h25.326l12.084,17.372,20.843-3.737,17.912,17.9-3.745,20.835,17.381,12.093v25.326l-17.372,12.084ZM62.518,408.42a248.55,248.55,0,0,1,0-283.483l131.418,81.17-9.2,9.2,3.377,18.829-15.71,10.927v43.238l15.7,10.927-3.377,18.829,9.2,9.2ZM240.971,513.758V498.083h51.422v15.675a253.4,253.4,0,0,1-25.711,1.465A253.4,253.4,0,0,1,240.971,513.758Zm85.7-67.1H206.69V403.809a8.574,8.574,0,0,1,8.57-8.57H318.1a8.574,8.574,0,0,1,8.57,8.57ZM206.69,463.8H326.675v8.57a8.574,8.574,0,0,1-8.57,8.57H215.26a8.574,8.574,0,0,1-8.57-8.57Zm201.73,7.045a245.574,245.574,0,0,1-98.885,40.572V498.083h8.57a25.743,25.743,0,0,0,25.711-25.711V403.809A25.743,25.743,0,0,0,318.1,378.1H215.26a25.743,25.743,0,0,0-25.711,25.711v68.563a25.743,25.743,0,0,0,25.711,25.711h8.57v13.344a245.816,245.816,0,0,1-98.885-40.572l81.162-131.41,9.2,9.2,18.829-3.377,10.936,15.692h43.238l10.927-15.7,18.829,3.377,9.2-9.2Zm62.427-62.427-131.41-81.162,9.2-9.2-3.377-18.829,15.7-10.936V245.059l-15.7-10.927,3.377-18.829-9.2-9.2,131.418-81.17a248.583,248.583,0,0,1-.009,283.492Z" transform="translate(0 0)"/>
    <path id="Path_4411" data-name="Path 4411" d="M69.852,27A42.852,42.852,0,1,0,112.7,69.852,42.9,42.9,0,0,0,69.852,27Zm0,68.563A25.711,25.711,0,1,1,95.563,69.852,25.743,25.743,0,0,1,69.852,95.563Z" transform="translate(196.83 196.83)"/>
    <path id="Path_4412" data-name="Path 4412" d="M112.563,22A68.643,68.643,0,0,0,44,90.563c0,24.014,12.41,44.395,34.282,56.633v37.641h68.563V147.2c21.872-12.247,34.282-32.619,34.282-56.633A68.643,68.643,0,0,0,112.563,22ZM95.422,167.7V150.556H129.7V167.7Zm8.57-77.134a8.57,8.57,0,1,1,8.57,8.57A8.579,8.579,0,0,1,103.993,90.563Zm32.182,42.852H121.134V114.7a25.711,25.711,0,1,0-17.141,0v18.718H88.952c-11.073-5.631-27.811-18.169-27.811-42.852a51.422,51.422,0,1,1,102.845,0C163.986,115.246,147.248,127.784,136.175,133.415Z" transform="translate(325.527 158.978)"/>
    <path id="Path_4413" data-name="Path 4413" d="M5,145.986H124.986V26H5Zm68.563-17.141H56.422V111.7H73.563Zm-17.141-85.7H73.563V60.282H56.422Zm-34.282,0H39.282V77.422H90.7V43.141h17.141v85.7H90.7V94.563H39.282v34.282H22.141Z" transform="translate(30.282 189.26)"/>
  </g>
</g></g></g></svg>

                        <?=Yii::t('app','Roles & Privileges')?> <i class="mdi mdi-chevron-down float-right"></i></a>

                    <ul class="collapse list-unstyled Submenu <?=$subActive?>" id="Roles">
                     <?php if ($controllerName == 'roles')
                                $subActive = 'active'; $fill = '#007bff'; ?>
                               <?php if(empty($role) ||in_array('roles', $priviliges)) { ?>  
                                    <?php
                                    $subActive = '';
                                    if ($controllerName == 'roles' && $actionName == 'index')
                                    $subActive = 'active'; ?>
                                
                        <li>

                            <a href="<?= Yii::$app->urlManager->createUrl(['roles/index'])?>"><?php echo Yii::t('app','Roles'); ?></a>
                        </li>
                         <?php } ?>
                         <?php if(empty($role) ||in_array('moderator', $priviliges)) { ?>  
                                    <?php $subActive = '';
                                    if ($controllerName == 'moderator' && $actionName == 'index')
                                    $subActive = 'active'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['moderator/index'])?>"><?php echo Yii::t('app','Moderator') ?></a>
                        </li>
                          <?php } ?>
                        
                      </ul>
                </li>
                    <?php } ?>


                   <?php if(empty($role) || in_array('module', $priviliges)) { ?>


                            <?php $active = '';$subActive = '' ; $fill='#000';
                             if ($controllerName == 'sitesettings' && $actionName == 'managemodule'){
                                 $active = 'active'; $fill = '#007bff'; $subActive='active';}
                              else{
                                 $active = '';$subActive = '' ; $fill='#000';}
                               ?>
                           

                               <li class="<?php echo $active; ?>">
                        <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/managemodule'])?>">
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" style=" fill:#000000;margin-right: 10px" viewBox="0 0 512 429.175">
 <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1">  <g id="Group_2519" data-name="Group 2519" transform="translate(10901 5684)">
    <g id="Group_2518" data-name="Group 2518">
      <g id="modules" transform="translate(-10901 -5725.412)">
        <g id="Group_2499" data-name="Group 2499">
          <g id="Group_2498" data-name="Group 2498">
            <path id="Path_4399" data-name="Path 4399" d="M473.666,41.412H38.327A38.372,38.372,0,0,0,0,79.746v52.337c0,.023,0,.046,0,.069s0,.046,0,.069V432.253a38.372,38.372,0,0,0,38.324,38.334H473.666A38.377,38.377,0,0,0,512,432.253V79.746A38.377,38.377,0,0,0,473.666,41.412Zm23.126,390.842a23.152,23.152,0,0,1-23.126,23.126H38.327a23.147,23.147,0,0,1-23.116-23.126v-292.5H496.792Zm0-307.7H15.211v-44.8A23.147,23.147,0,0,1,38.327,56.62H473.666a23.152,23.152,0,0,1,23.126,23.126Z"/>
          </g>
        </g>
        <g id="Group_2501" data-name="Group 2501">
          <g id="Group_2500" data-name="Group 2500">
            <path id="Path_4400" data-name="Path 4400" d="M60.327,63.717A26.867,26.867,0,1,0,87.194,90.584,26.9,26.9,0,0,0,60.327,63.717Zm0,38.527A11.659,11.659,0,1,1,71.986,90.585,11.672,11.672,0,0,1,60.327,102.244Z"/>
          </g>
        </g>
        <g id="Group_2503" data-name="Group 2503">
          <g id="Group_2502" data-name="Group 2502">
            <path id="Path_4401" data-name="Path 4401" d="M180.979,63.717a26.867,26.867,0,1,0,26.867,26.867A26.9,26.9,0,0,0,180.979,63.717Zm0,38.527a11.659,11.659,0,1,1,11.659-11.659A11.672,11.672,0,0,1,180.979,102.244Z"/>
          </g>
        </g>
        <g id="Group_2505" data-name="Group 2505">
          <g id="Group_2504" data-name="Group 2504">
            <path id="Path_4402" data-name="Path 4402" d="M120.653,63.717A26.867,26.867,0,1,0,147.52,90.584,26.9,26.9,0,0,0,120.653,63.717Zm0,38.527a11.659,11.659,0,1,1,11.659-11.659A11.672,11.672,0,0,1,120.653,102.244Z"/>
          </g>
        </g>
        <g id="Group_2507" data-name="Group 2507">
          <g id="Group_2506" data-name="Group 2506">
            <path id="Path_4403" data-name="Path 4403" d="M160.577,158.063H41.2a7.6,7.6,0,0,0-7.6,7.6V285.042a7.6,7.6,0,0,0,7.6,7.6H160.577a7.6,7.6,0,0,0,7.6-7.6V165.666A7.6,7.6,0,0,0,160.577,158.063Zm-7.6,119.375H48.806V173.27H152.974Z"/>
          </g>
        </g>
        <g id="Group_2509" data-name="Group 2509">
          <g id="Group_2508" data-name="Group 2508">
            <path id="Path_4404" data-name="Path 4404" d="M315.689,158.063H196.314a7.6,7.6,0,0,0-7.6,7.6V285.042a7.6,7.6,0,0,0,7.6,7.6H315.689a7.6,7.6,0,0,0,7.6-7.6V165.666A7.6,7.6,0,0,0,315.689,158.063Zm-7.6,119.375H203.918V173.27H308.086V277.438Z"/>
          </g>
        </g>
        <g id="Group_2511" data-name="Group 2511">
          <g id="Group_2510" data-name="Group 2510">
            <path id="Path_4405" data-name="Path 4405" d="M470.8,158.063H351.427a7.6,7.6,0,0,0-7.6,7.6v28.384a7.6,7.6,0,1,0,15.208,0v-20.78H463.2V277.439H359.03V194.051c0-4.2-3.4-17.744-7.6-17.744s-7.605,13.545-7.605,17.744v90.992a7.6,7.6,0,0,0,7.6,7.6H470.8a7.6,7.6,0,0,0,7.6-7.6V165.666A7.6,7.6,0,0,0,470.8,158.063Z"/>
          </g>
        </g>
        <g id="Group_2513" data-name="Group 2513">
          <g id="Group_2512" data-name="Group 2512">
            <path id="Path_4406" data-name="Path 4406" d="M160.577,305.534H41.2a7.6,7.6,0,0,0-7.6,7.6V432.513a7.6,7.6,0,0,0,7.6,7.6h65.413c4.2,0-7.6-3.4-7.6-7.6s11.8-7.6,7.6-7.6H48.806V320.742H152.974V424.91H106.615a7.6,7.6,0,0,0,0,15.208h53.962a7.6,7.6,0,0,0,7.6-7.6V313.138A7.6,7.6,0,0,0,160.577,305.534Z"/>
          </g>
        </g>
        <g id="Group_2515" data-name="Group 2515">
          <g id="Group_2514" data-name="Group 2514">
            <path id="Path_4407" data-name="Path 4407" d="M315.689,305.534H196.314a7.6,7.6,0,0,0-7.6,7.6V432.513a7.6,7.6,0,0,0,7.6,7.6H315.689a7.6,7.6,0,0,0,7.6-7.6V313.138A7.6,7.6,0,0,0,315.689,305.534Zm-7.6,119.376H203.918V320.742H308.086V424.91Z"/>
          </g>
        </g>
        <g id="Group_2517" data-name="Group 2517">
          <g id="Group_2516" data-name="Group 2516">
            <path id="Path_4408" data-name="Path 4408" d="M470.8,305.534H351.427a7.6,7.6,0,0,0-7.6,7.6V432.513a7.6,7.6,0,0,0,7.6,7.6H470.8a7.6,7.6,0,0,0,7.6-7.6V313.138A7.6,7.6,0,0,0,470.8,305.534ZM463.2,424.91H359.03V320.742H463.2Z"/>
          </g>
        </g>
      </g>
    </g>
  </g>
</g></g></g></svg>


                        <?php echo Yii::t('app','Modules'); ?> </a>

                </li>
                  <?php } ?>   
    <?php if(empty($role) || in_array('users', $priviliges)) { ?>
                            <?php $active = '';$subActive = '';$fill='#000';
                             if ($controllerName == 'users'){
                                $active = 'active'; $fill = '#007bff'; $subActive='active'; }
                             
                               else{
                                 $active = '';$subActive = '' ; $fill='#000';}
                           ?>
                        <li class="<?php echo $active; ?>">
                        <a href="<?= Yii::$app->urlManager->createUrl(['users/index'])?>">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                        width="20" height="20"
                        viewBox="0 0 192 192"
                        style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1"><path d="M65.28,7.68c-12.675,0 -23.04,10.365 -23.04,23.04c0,7.62 3.75,14.4 9.48,18.6c-3.075,1.545 -5.88,3.54 -8.28,6c-2.79,-0.99 -5.76,-1.56 -8.88,-1.56c-14.805,0 -26.88,12.075 -26.88,26.88c0,9.405 4.875,17.76 12.24,22.56c-11.73,5.55 -19.92,17.43 -19.92,31.2v17.28c-0.015,1.23 0.57,2.385 1.56,3.12c0,0 2.715,1.92 7.92,3.48c5.205,1.56 13.245,3 25.08,3c11.835,0 19.875,-1.44 25.08,-3c0.675,-0.195 1.215,-0.39 1.8,-0.6v17.04c-0.015,1.23 0.57,2.385 1.56,3.12c0,0 2.715,1.92 7.92,3.48c5.205,1.56 13.245,3 25.08,3c11.835,0 19.875,-1.44 25.08,-3c5.205,-1.56 7.92,-3.48 7.92,-3.48c0.99,-0.735 1.575,-1.89 1.56,-3.12v-17.04c0.585,0.21 1.125,0.405 1.8,0.6c5.205,1.56 13.245,3 25.08,3c11.835,0 19.875,-1.44 25.08,-3c5.205,-1.56 7.92,-3.48 7.92,-3.48c0.99,-0.735 1.575,-1.89 1.56,-3.12v-17.28c0,-13.77 -8.19,-25.65 -19.92,-31.2c7.365,-4.8 12.24,-13.155 12.24,-22.56c0,-14.805 -12.075,-26.88 -26.88,-26.88c-3.345,0 -6.525,0.675 -9.48,1.8c-2.415,-2.535 -5.265,-4.65 -8.4,-6.24c5.73,-4.2 9.48,-10.98 9.48,-18.6c0,-12.675 -10.365,-23.04 -23.04,-23.04c-12.675,0 -23.04,10.365 -23.04,23.04c0,7.62 3.75,14.4 9.48,18.6c-8.82,4.35 -15.27,12.72 -16.8,22.8c-1.53,-10.095 -7.965,-18.465 -16.8,-22.8c5.73,-4.2 9.48,-10.98 9.48,-18.6c0,-12.675 -10.365,-23.04 -23.04,-23.04zM65.28,15.36c8.535,0 15.36,6.825 15.36,15.36c0,8.535 -6.825,15.36 -15.36,15.36c-8.535,0 -15.36,-6.825 -15.36,-15.36c0,-8.535 6.825,-15.36 15.36,-15.36zM126,15.36c8.535,0 15.36,6.825 15.36,15.36c0,8.535 -6.825,15.36 -15.36,15.36c-8.535,0 -15.36,-6.825 -15.36,-15.36c0,-8.535 6.825,-15.36 15.36,-15.36zM65.28,53.76c12.945,0 23.04,10.095 23.04,23.04v1.2c-11.04,3.345 -19.2,13.575 -19.2,25.68c0,9.405 4.875,17.76 12.24,22.56c-4.815,2.28 -8.94,5.625 -12.24,9.72v-1.56c0,-13.77 -8.19,-25.65 -19.92,-31.2c7.365,-4.8 12.24,-13.155 12.24,-22.56c0,-8.76 -4.275,-16.56 -10.8,-21.48c3.99,-3.405 8.94,-5.4 14.64,-5.4zM126,53.76c5.865,0 10.965,2.055 15,5.64c-6.33,4.92 -10.44,12.645 -10.44,21.24c0,9.405 4.875,17.76 12.24,22.56c-11.73,5.55 -19.92,17.43 -19.92,31.2v1.56c-3.3,-4.095 -7.425,-7.44 -12.24,-9.72c7.365,-4.8 12.24,-13.155 12.24,-22.56c0,-12.375 -8.49,-22.83 -19.92,-25.92v-0.96c0,-12.945 10.095,-23.04 23.04,-23.04zM34.56,61.44c2.985,0 5.76,0.72 8.28,1.92c6.45,3.09 10.92,9.615 10.92,17.28c0,10.65 -8.55,19.2 -19.2,19.2c-10.65,0 -19.2,-8.55 -19.2,-19.2c0,-10.65 8.55,-19.2 19.2,-19.2zM157.44,61.44c10.65,0 19.2,8.55 19.2,19.2c0,10.65 -8.55,19.2 -19.2,19.2c-10.65,0 -19.2,-8.55 -19.2,-19.2c0,-7.095 3.795,-13.23 9.48,-16.56c0.03,-0.015 0.09,0.015 0.12,0c0.57,-0.12 1.095,-0.36 1.56,-0.72c0.135,-0.06 0.225,-0.18 0.36,-0.24c0.075,-0.03 0.165,-0.075 0.24,-0.12c2.295,-0.975 4.785,-1.56 7.44,-1.56zM96,84.48c10.65,0 19.2,8.55 19.2,19.2c0,10.65 -8.55,19.2 -19.2,19.2c-10.65,0 -19.2,-8.55 -19.2,-19.2c0,-10.65 8.55,-19.2 19.2,-19.2zM34.56,107.52c14.805,0 26.88,12.075 26.88,26.88v14.76c-0.57,0.345 -0.795,0.735 -3.96,1.68c-4.395,1.32 -11.715,2.76 -22.92,2.76c-11.205,0 -18.525,-1.44 -22.92,-2.76c-3.165,-0.945 -3.39,-1.335 -3.96,-1.68v-14.76c0,-14.805 12.075,-26.88 26.88,-26.88zM157.44,107.52c14.805,0 26.88,12.075 26.88,26.88v14.76c-0.57,0.345 -0.795,0.735 -3.96,1.68c-4.395,1.32 -11.715,2.76 -22.92,2.76c-11.205,0 -18.525,-1.44 -22.92,-2.76c-3.165,-0.945 -3.39,-1.335 -3.96,-1.68v-14.76c0,-14.805 12.075,-26.88 26.88,-26.88zM96,130.56c14.805,0 26.88,12.075 26.88,26.88v14.76c-0.57,0.345 -0.795,0.735 -3.96,1.68c-4.395,1.32 -11.715,2.76 -22.92,2.76c-11.205,0 -18.525,-1.44 -22.92,-2.76c-3.165,-0.945 -3.39,-1.335 -3.96,-1.68v-14.76c0,-14.805 12.075,-26.88 26.88,-26.88z"></path></g></g></g></svg>
                        <?php echo Yii::t('app','Users'); ?> </a>

                </li>
                <?php } ?>   


   <?php if(empty($role) || in_array('items', $priviliges) || in_array('productCnt', $priviliges) || in_array('report', $priviliges))  { ?>
                            <?php $active = '';$subActive = ''; $display ='display:none';
                            if ($controllerName == 'products' || $controllerName == 'productconditions' || $controllerName == 'reportitem' ){
                            $active = 'active'; $fill = '#007bff';
                          }
                          else
                          {
                             
                                 $active = '';$subActive = '' ; $fill='#000';}
                          
                          

                            if ($controllerName == 'products' || $controllerName == 'productconditions' || $controllerName == 'reportitem' ){
                           $display = "display:none"; $subActive='show';
                             } 
                            ?>

               
                <li class="<?php echo $active; ?>">
                    <a href="#Items" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1"><path d="M15.36,15.36c-8.445,0 -15.36,6.915 -15.36,15.36c0,8.445 6.915,15.36 15.36,15.36c8.445,0 15.36,-6.915 15.36,-15.36c0,-8.445 -6.915,-15.36 -15.36,-15.36zM15.36,23.04c4.29,0 7.68,3.39 7.68,7.68c0,4.29 -3.39,7.68 -7.68,7.68c-4.29,0 -7.68,-3.39 -7.68,-7.68c0,-4.29 3.39,-7.68 7.68,-7.68zM46.08,26.88v7.68h145.92v-7.68zM15.36,80.64c-8.445,0 -15.36,6.915 -15.36,15.36c0,8.445 6.915,15.36 15.36,15.36c8.445,0 15.36,-6.915 15.36,-15.36c0,-8.445 -6.915,-15.36 -15.36,-15.36zM15.36,88.32c4.29,0 7.68,3.39 7.68,7.68c0,4.29 -3.39,7.68 -7.68,7.68c-4.29,0 -7.68,-3.39 -7.68,-7.68c0,-4.29 3.39,-7.68 7.68,-7.68zM46.08,92.16v7.68h145.92v-7.68zM15.36,145.92c-8.445,0 -15.36,6.915 -15.36,15.36c0,8.445 6.915,15.36 15.36,15.36c8.445,0 15.36,-6.915 15.36,-15.36c0,-8.445 -6.915,-15.36 -15.36,-15.36zM15.36,153.6c4.29,0 7.68,3.39 7.68,7.68c0,4.29 -3.39,7.68 -7.68,7.68c-4.29,0 -7.68,-3.39 -7.68,-7.68c0,-4.29 3.39,-7.68 7.68,-7.68zM46.08,157.44v7.68h145.92v-7.68z"></path></g></g></g></svg>
                        <?=Yii::t('app','Items')?> <i class="mdi mdi-chevron-down float-right"></i></a>

                    <ul class="collapse list-unstyled Submenu <?=$subActive?>" id="Items">
                    <?php if ($controllerName == 'products')
                                $subActive = 'active';$fill = '#007bff'; 
                                if(empty($role) || in_array('items', $priviliges)) { ?> 
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['products/index'])?>"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Items'); ?></a>
                        </li>
                      <?php } ?>
                         <?php if(empty($role) || in_array('productCnt', $priviliges)) { ?> 
                                    <?php $subActive = '';
                                    if ($controllerName == 'productconditions')
                                    $subActive = 'active'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['productconditions/index'])?>"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Product Conditions'); ?></a>
                        </li>
                         <?php } ?>
                        <?php if(empty($role) || in_array('report', $priviliges)) { ?> 
                                    <?php $subActive = '';
                                    if ($controllerName == 'reportitem')
                                    $subActive = 'active'; ?>
                        <li >
                            <a href="<?= Yii::$app->urlManager->createUrl(['reportitem/index'])?>"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Report Items'); ?> </a>
                        </li>
                          <?php } ?>
                    </ul>
                </li>
                  <?php } ?>
                 <?php if(empty($role) || in_array('notification', $priviliges)) { ?> 
                            <?php $active = '';$subActive = '';$fill='#000';
                            if ($controllerName == 'admin' && $actionName == 'notification')
                            {$active = 'active';$fill = '#007bff';}
                            ?> 
                        <li class="<?php echo $active; ?>">
                        <a href="<?= Yii::$app->urlManager->createUrl(['admin/notification'])?>">
             <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
style=" fill:#000000;margin-right: 10px" viewBox="0 0 426.685 512">

<g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1">
  <g id="notification" transform="translate(-42.657)">
    <g id="Group_2520" data-name="Group 2520">
      <g id="Group_2519" data-name="Group 2519">
        <path id="Path_4414" data-name="Path 4414" d="M467.819,431.851,431.168,370.8a181.486,181.486,0,0,1-25.835-93.312V224c0-82.325-67.008-149.333-149.333-149.333S106.667,141.675,106.667,224v53.483a181.486,181.486,0,0,1-25.835,93.312L44.181,431.851A10.657,10.657,0,0,0,53.333,448H458.666a10.658,10.658,0,0,0,9.153-16.15ZM72.171,426.667,99.115,381.76A202.631,202.631,0,0,0,128,277.483V224a128,128,0,0,1,256,0v53.483A202.813,202.813,0,0,0,412.864,381.76l26.965,44.907H72.171Z"/>
      </g>
    </g>
    <g id="Group_2522" data-name="Group 2522">
      <g id="Group_2521" data-name="Group 2521">
        <path id="Path_4415" data-name="Path 4415" d="M256,0a42.71,42.71,0,0,0-42.667,42.667V85.334a10.667,10.667,0,0,0,21.334,0V42.667a21.333,21.333,0,0,1,42.666,0V85.334a10.667,10.667,0,0,0,21.334,0V42.667A42.71,42.71,0,0,0,256,0Z"/>
      </g>
    </g>
    <g id="Group_2524" data-name="Group 2524">
      <g id="Group_2523" data-name="Group 2523">
        <path id="Path_4416" data-name="Path 4416" d="M302.165,431.936a10.68,10.68,0,1,0-18.432,10.794,32,32,0,1,1-55.424,0,10.68,10.68,0,0,0-18.432-10.794,53.355,53.355,0,1,0,92.288,0Z"/>
      </g>
    </g>
  </g>
</g></g></g></svg>

                        <?php echo Yii::t('app','Notifications'); ?> </a>

                </li>

                 <?php } ?>
                <?php  if(empty($role) || in_array('filters', $priviliges)) { ?> 
                            <?php $active = '';$subActive = '';$fill='#000';
                            if ($controllerName == 'filter')
                            {$active = 'active';$fill = '#007bff';}
                            ?>
                <li class="<?php echo $active; ?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['filter/management'])?>">

                      <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20" viewBox="0 0 384.377 393.988" style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1">
  <path id="filter" d="M368.313,0H17.051A16.5,16.5,0,0,0,2.344,8.961a16.732,16.732,0,0,0,1.3,17.414L132.333,207.656c.043.063.09.121.133.184a36.769,36.769,0,0,1,7.219,21.816v147.8a16.431,16.431,0,0,0,16.434,16.535,16.922,16.922,0,0,0,6.48-1.3l72.313-27.574c6.48-1.977,10.781-8.09,10.781-15.453V229.656a36.774,36.774,0,0,1,7.215-21.816c.043-.062.09-.121.133-.184L381.724,26.367a16.717,16.717,0,0,0,1.3-17.406A16.5,16.5,0,0,0,368.314,0ZM236.782,195.992a56.932,56.932,0,0,0-11.1,33.664V347.234l-66,25.164V229.656a56.909,56.909,0,0,0-11.1-33.664L23.649,20h338.07Zm0,0" transform="translate(-0.496)"></path></g></g></g></svg>

                        <?=Yii::t('app','Filters')?> </a>

                </li>
                    <?php } ?>


                 <?php if(empty($role) || in_array('category', $priviliges)) { ?>
                            <?php $active = '';$subActive = '';$fill='#000';
                            if ($controllerName == 'categories')
                            {$active = 'active';$fill = '#007bff';}
                            ?>
                <li class="<?php echo $active; ?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['categories/index'])?>">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1"><path d="M11.52,15.36c-6.315,0 -11.52,5.205 -11.52,11.52v141.72c-0.24,0.885 -0.15,1.815 0.24,2.64c1.08,5.235 5.76,9.24 11.28,9.24h149.76c5.73,0 10.44,-4.32 11.28,-9.84c0.03,-0.24 0.105,-0.48 0.12,-0.72c0,-0.075 0,-0.165 0,-0.24l0.12,-0.6c0,-0.045 0,-0.075 0,-0.12l19.08,-102.96l0.12,-0.36v-0.36c0,-6.315 -5.205,-11.52 -11.52,-11.52v-11.52c0,-6.315 -5.205,-11.52 -11.52,-11.52h-99.72c0.015,0.015 -0.03,0 -0.12,0c-0.12,-0.09 -0.465,-0.375 -1.08,-1.08c-0.945,-1.08 -2.04,-2.88 -3.24,-4.8c-1.2,-1.92 -2.49,-3.96 -4.08,-5.76c-1.59,-1.8 -3.81,-3.72 -6.96,-3.72zM11.52,23.04h42.24c-0.24,0 0.255,0 1.2,1.08c0.945,1.08 2.04,2.88 3.24,4.8c1.2,1.92 2.52,3.96 4.08,5.76c1.56,1.8 3.69,3.72 6.84,3.72h99.84c2.16,0 3.84,1.68 3.84,3.84v11.52h-142.08c-6.045,0 -10.86,4.86 -11.28,10.8h-0.12l-0.12,0.72l-11.52,62.16v-100.56c0,-2.16 1.68,-3.84 3.84,-3.84zM30.72,61.44h149.76c2.16,0 3.84,1.68 3.84,3.84l-18.84,101.88l-0.12,0.24c-0.045,0.15 -0.09,0.315 -0.12,0.48c-0.045,0.15 -0.09,0.315 -0.12,0.48c0,0.075 0,0.165 0,0.24c0,0.045 0,0.075 0,0.12c-0.06,0.195 -0.09,0.39 -0.12,0.6c-0.015,0.12 0.015,0.24 0,0.36c-0.015,0.165 -0.015,0.315 0,0.48c-0.48,1.56 -1.965,2.64 -3.72,2.64h-149.76c-2.16,0 -3.84,-1.68 -3.84,-3.84l19.08,-102.96l0.12,-0.36v-0.36c0,-2.16 1.68,-3.84 3.84,-3.84z"></path></g></g></g></svg>
                        <?=Yii::t('app','Category')?> </a>

                </li>
                   <?php } ?>
                    <?php if(empty($role) || in_array('promotion', $priviliges)) { ?>
                <?php if(isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == "1") { ?>
                            <?php $active = '';$subActive = '';$fill='#000';
                            if ($controllerName == 'promotions')
                            {$active = 'active';$fill = '#007bff';}
                            ?>
                            <li class="<?php echo $active; ?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['promotions/index'])?>">
  <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"

style=" fill:#000000;margin-right: 10px;" viewBox="0 0 512 484.489" >
<g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1">
  <g id="promotion" transform="translate(0 0)">
  
    <path id="Path_4423" data-name="Path 4423" d="M136.965,308.234a10,10,0,1,0-13.66-3.66A10,10,0,0,0,136.965,308.234Zm0,0"/>
    <path id="Path_4424" data-name="Path 4424" d="M95.984,377.254l50.359,87.23a40,40,0,1,0,69.281-40l-30-51.969,25.98-15a10,10,0,0,0,3.66-13.66l-13-22.523c1.551-.3,11.746-2.3,191.539-37.57a30,30,0,0,0,24.316-44.949l-33.234-57.562,21.238-32.168a10,10,0,0,0,.316-10.512l-20-34.641a10.021,10.021,0,0,0-9.262-4.98L338.7,101.262,301.809,37.356a29.739,29.739,0,0,0-25.605-15c-.129,0-.254,0-.383,0a29.736,29.736,0,0,0-25.258,13.832L119.929,202.6,35,251.633a70,70,0,0,0,60.98,125.621Zm102.324,57.238a20,20,0,0,1-34.645,20l-50-86.613,34.641-20c57.867,100.242,49.074,85.012,50,86.617Zm-22.684-79.3-10-17.32,17.32-10,10,17.32Zm196.582-235.91,13.82,23.938L373.7,161.887l-23.82-41.262ZM267.289,47.153a10,10,0,0,1,17.2.2L400.8,248.817a10,10,0,0,1-8.414,14.992c-1.363.031-1.992.277-5.484.93L263.864,51.634c2.582-3.32,2.914-3.641,3.426-4.48ZM250.554,68.586l115.6,200.223L191.691,303.028l-53.047-91.879ZM26.7,337.254a49.966,49.966,0,0,1,18.3-68.3l77.941-45,50,86.6L95,355.559a50.061,50.061,0,0,1-68.3-18.3Zm0,0"/>
    <path id="Path_4425" data-name="Path 4425" d="M105.984,314.574a10,10,0,0,0-13.66-3.66l-17.32,10a10.013,10.013,0,0,1-13.66-3.66,10,10,0,0,0-17.32,10A30.039,30.039,0,0,0,85,338.234l17.32-10a10,10,0,0,0,3.66-13.66Zm0,0"/>
    <path id="Path_4426" data-name="Path 4426" d="M497.137,43.746,441.414,74.754a10,10,0,0,0,9.727,17.477L506.86,61.223a10,10,0,0,0-9.723-17.477Zm0,0"/>
    <path id="Path_4427" data-name="Path 4427" d="M491.293,147.316l-38.637-10.352a10,10,0,1,0-5.176,19.316l38.641,10.352a10,10,0,1,0,5.172-19.316Zm0,0"/>
    <path id="Path_4428" data-name="Path 4428" d="M394.2,7.414,383.836,46.055a10,10,0,0,0,19.32,5.18l10.359-38.641A10,10,0,1,0,394.2,7.414Zm0,0"/>
  </g></g>
</g></g></svg>

                        <?=Yii::t('app','Promotions')?> </a>

                </li> <?php } ?>
                   <?php } ?>
                             <?php if(empty($role) || in_array('currency', $priviliges)) { ?>
                            <?php $active = '';$subActive = '';$fill='#000';
                            if ($controllerName == 'currencies')
                            {$active = 'active';$fill = '#007bff';}
                            ?>
                            <li class="<?php echo $active; ?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['currencies/index'])?>">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1"><path d="M164.04,3.24l-3.72,0.96l-34.44,9.24c-0.21,0.045 -0.39,0.06 -0.6,0.12l-85.8,23.04l-34.92,9.36l-3.72,0.96l0.96,3.72l8.28,30.48v0.24l12.96,47.88v47.4h168.96v-99.84h-8.16l-18.84,-69.96zM158.52,12.6l4.32,16.2c-0.795,-0.195 -1.62,-0.465 -2.4,-0.6c-2.43,-0.39 -4.725,-0.615 -6.6,-0.84c-1.875,-0.225 -3.315,-0.63 -3.48,-0.72c-0.15,-0.09 -1.185,-1.095 -2.4,-2.64c-1.215,-1.545 -2.775,-3.51 -4.68,-5.4c-0.48,-0.48 -1.005,-0.99 -1.56,-1.44zM130.8,20.52c3.06,-0.015 4.98,1.395 7.08,3.48c1.395,1.395 2.655,3.15 3.96,4.8c1.305,1.65 2.535,3.315 4.68,4.56c2.145,1.23 4.215,1.41 6.36,1.68c2.145,0.27 4.38,0.39 6.36,0.72c1.98,0.33 3.615,0.825 4.68,1.44c0.975,0.57 1.47,1.065 1.8,2.04c0.03,0.09 0.09,0.135 0.12,0.24l9.96,37.32h-45.6c0.15,-4.08 -0.315,-8.28 -1.44,-12.48c-4.065,-15.165 -15.975,-26.205 -29.52,-28.68c-2.25,-0.405 -4.53,-0.57 -6.84,-0.48c-2.31,0.09 -4.635,0.45 -6.96,1.08c-16.98,4.56 -27.045,22.17 -24.6,40.56h-37.8v22.92l-5.64,-20.88c-0.135,-0.855 -0.03,-1.515 0.48,-2.4c0.615,-1.065 1.815,-2.445 3.36,-3.72c1.545,-1.275 3.435,-2.415 5.16,-3.72c1.725,-1.305 3.33,-2.535 4.56,-4.68h0.12c1.245,-2.145 1.38,-4.17 1.68,-6.24c0.3,-2.07 0.57,-4.215 1.08,-6.12c1.035,-3.81 2.4,-6.495 7.68,-7.92l85.8,-23.04c1.32,-0.36 2.46,-0.48 3.48,-0.48zM92.64,42.72c12.465,-0.405 24.78,9.075 28.68,23.64c0.945,3.54 1.35,7.05 1.2,10.44h-53.88c-2.535,-15.39 5.58,-29.715 18.72,-33.24c1.77,-0.48 3.495,-0.78 5.28,-0.84zM149.64,47.28c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM27,48c-0.255,0.66 -0.42,1.275 -0.6,1.92c-0.705,2.595 -0.915,5.01 -1.2,6.96c-0.285,1.95 -0.75,3.45 -0.84,3.6c-0.09,0.165 -1.14,1.26 -2.64,2.4c-1.5,1.14 -3.375,2.4 -5.28,3.96c-0.645,0.525 -1.29,1.08 -1.92,1.68l-4.32,-16.08zM149.64,54.96c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,84.48h17.4c-0.42,0.57 -0.735,1.215 -1.08,1.8c-1.35,2.325 -2.34,4.68 -3.12,6.48c-0.78,1.8 -1.56,3 -1.68,3.12c-0.135,0.135 -1.365,0.975 -3.12,1.68c-1.755,0.705 -3.885,1.515 -6.12,2.52c-0.75,0.345 -1.515,0.795 -2.28,1.2zM63.12,84.48h88.8c5.475,0 7.5,2.22 9.48,5.64c0.99,1.71 1.815,3.72 2.64,5.64c0.825,1.92 1.485,3.885 3.24,5.64c1.74,1.74 3.765,2.43 5.76,3.24c1.995,0.81 4.05,1.575 5.88,2.4c1.83,0.825 3.33,1.77 4.2,2.64c0.87,0.87 1.2,1.41 1.2,2.64v38.4c0,0.54 -0.045,0.645 -0.48,1.08c-0.435,0.435 -1.44,1.02 -2.64,1.56c-1.2,0.54 -2.535,1.11 -3.96,1.68c-1.425,0.57 -2.985,1.065 -4.44,2.52c-1.47,1.47 -1.935,2.85 -2.52,4.2c-0.585,1.35 -1.17,2.745 -1.8,3.84c-1.275,2.19 -2.13,3.36 -5.52,3.36h-110.88c-3.39,0 -4.245,-1.17 -5.52,-3.36c-0.63,-1.095 -1.215,-2.475 -1.8,-3.84c-0.585,-1.365 -1.05,-2.73 -2.52,-4.2c-1.455,-1.455 -3.015,-1.95 -4.44,-2.52c-1.425,-0.57 -2.76,-1.14 -3.96,-1.68c-1.2,-0.54 -2.205,-1.125 -2.64,-1.56c-0.435,-0.435 -0.48,-0.54 -0.48,-1.08v-22.08h0.12l-0.12,-0.48v-15.84c0,-1.23 0.33,-1.77 1.2,-2.64c0.87,-0.87 2.37,-1.815 4.2,-2.64c1.83,-0.825 3.885,-1.59 5.88,-2.4c1.995,-0.81 4.02,-1.5 5.76,-3.24c1.755,-1.755 2.4,-3.72 3.24,-5.64c0.84,-1.92 1.65,-3.93 2.64,-5.64c1.98,-3.42 4.005,-5.64 9.48,-5.64zM166.92,84.48h17.4v16.8c-0.75,-0.405 -1.53,-0.855 -2.28,-1.2c-2.235,-1.005 -4.365,-1.815 -6.12,-2.52c-1.755,-0.705 -2.985,-1.545 -3.12,-1.68c-0.12,-0.12 -0.9,-1.32 -1.68,-3.12c-0.78,-1.8 -1.77,-4.155 -3.12,-6.48c-0.345,-0.585 -0.66,-1.23 -1.08,-1.8zM107.52,88.32c-19.305,0 -34.56,17.475 -34.56,38.4c0,20.925 15.255,38.4 34.56,38.4c19.305,0 34.56,-17.475 34.56,-38.4c0,-20.925 -15.255,-38.4 -34.56,-38.4zM107.52,96c14.625,0 26.88,13.485 26.88,30.72c0,17.235 -12.255,30.72 -26.88,30.72c-14.625,0 -26.88,-13.485 -26.88,-30.72c0,-17.235 12.255,-30.72 26.88,-30.72zM51.84,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM163.2,115.2c-7.38,0 -13.44,6.06 -13.44,13.44c0,7.38 6.06,13.44 13.44,13.44c7.38,0 13.44,-6.06 13.44,-13.44c0,-7.38 -6.06,-13.44 -13.44,-13.44zM51.84,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM163.2,122.88c3.225,0 5.76,2.535 5.76,5.76c0,3.225 -2.535,5.76 -5.76,5.76c-3.225,0 -5.76,-2.535 -5.76,-5.76c0,-3.225 2.535,-5.76 5.76,-5.76zM30.72,160.44c1.575,0.705 3.045,1.215 4.2,1.68c1.17,0.48 1.965,1.005 1.8,0.84c-0.165,-0.165 0.42,0.69 0.96,1.92c0.48,1.11 1.095,2.565 1.92,4.08h-8.88zM184.32,160.44v8.52h-8.88c0.825,-1.515 1.44,-2.97 1.92,-4.08c0.54,-1.23 1.125,-2.085 0.96,-1.92c-0.165,0.165 0.615,-0.36 1.8,-0.84c1.155,-0.465 2.625,-0.975 4.2,-1.68z"></path></g></g></g></svg>
                        <?=Yii::t('app','Currency')?> </a>

                </li>
                 <?php } ?>
                  <?php if(empty($role) || in_array('sitepayment', $priviliges) || in_array('braintree', $priviliges) || in_array('stripe', $priviliges))  { ?>
                <?php $active = '';$subActive = ''; $display='';$fill='#000';
                            if ($controllerName == 'sitesettings' && ($actionName == 'sitepaymentmodes' || $actionName == 'braintreesettings' || $actionName == 'stripesettings'))
                            {$active = 'active';$fill = '#007bff';}
                            if ($controllerName == 'sitesettings' && ($actionName == 'sitepaymentmodes' || $actionName == 'braintreesettings' || $actionName == 'stripesettings')) {
                            $display = "display: block;"; $subActive='show';   
                            }
                            ?>
                           

                            <li class="has_sub <?php echo $active; ?>">
                    <a href="#SitePayment" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1"><path d="M26.88,34.56c-10.56,0 -19.2,8.64 -19.2,19.2v84.48c0,10.56 8.64,19.2 19.2,19.2h138.24c10.56,0 19.2,-8.64 19.2,-19.2v-84.48c0,-10.56 -8.64,-19.2 -19.2,-19.2zM26.88,42.24h138.24c6.405,0 11.52,5.115 11.52,11.52v7.68h-161.28v-7.68c0,-6.405 5.115,-11.52 11.52,-11.52zM15.36,80.64h161.28v57.6c0,6.405 -5.115,11.52 -11.52,11.52h-138.24c-6.405,0 -11.52,-5.115 -11.52,-11.52zM34.56,92.16v7.68h61.44v-7.68z"></path></g></g></g></svg>
<?php echo Yii::t('app','Site Payment Options'); ?> <i class="mdi mdi-chevron-down float-right"></i></a>

                    <ul class="collapse list-unstyled Submenu <?=$subActive?>" id="SitePayment">
                    <?php if ($controllerName == 'commissions')
                                $subActive = 'active';$fill = '#007bff'; ?>
                                <?php 
                                 //if($paymentmode['buynowPaymentMode'] == 1) { ?>
                                    <?php if(empty($role) || in_array('sitepayment', $priviliges)) { ?>
                                    <?php 
                                    $subActive = '';
                                    if ($controllerName == 'sitesettings' && $actionName == 'sitepaymentmodes')
                                    $subActive = 'active'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/sitepaymentmodes'])?>"><?php echo Yii::t('app','Site Payment Modes'); ?></a>
                        </li>
                         <?php } ?> 
                        <?php //}  ?>
                                     <?php if(empty($role) || in_array('braintree', $priviliges)) { ?>
                                    <?php $subActive = '';
                                    if ($controllerName == 'sitesettings' && $actionName == 'braintreesettings')
                                    $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/braintreesettings'])?>"><?php echo Yii::t('app','Brain Tree Settings'); ?></a>
                        </li>
                           <?php } ?> 

                             <?php if(empty($role) || in_array('stripe', $priviliges)) { ?>
                                    <?php $subActive = '';
                                    if ($controllerName == 'sitesettings' && $actionName == 'stripesettings')
                                    $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/stripesettings'])?>"><?php echo Yii::t('app','Stripe Settings'); ?></a>
                        </li>
                           <?php } ?> 
                        <?php $subActive = '';
                                    if ($controllerName == 'orders' && ($actionName == 'app' || $actionName == 'view'))
                                    $subActive = 'active';$fill = '#007bff'; ?>
                                
                                    <?php $subActive = '';
                                    if ($controllerName == 'orders' && ($actionName == 'mobileorders'))
                                    $subActive = 'active';$fill = '#007bff'; ?>
                             
                                    <?php $subActive = '';
                                    if ($controllerName[1] == 'invoices')
                                    $subActive = 'active';$fill = '#007bff'; ?>
                    </ul>
                </li>
              <?php } ?> 
               <?php if(empty($role) || in_array('commission', $priviliges) || in_array('invoice', $priviliges) || in_array('order', $priviliges))  { ?>
                <?php $active = '';$subActive = '' ; $display='';$fill='#000';
                            if ($controllerName == 'commissions' || $controllerName == 'invoices' || $controllerName == 'orders')
                            {$active = 'active';$fill = '#007bff';}

                            if ($controllerName == 'commissions' || $controllerName == 'invoices' || $controllerName == 'orders')
                            {
                                $display = "display:block";$subActive='show';
                            }
                            if($paymentmode['buynowPaymentMode'] == 1) {
                            ?>
                            <li class="has_sub <?php echo $active; ?>">
                    <a href="#Management" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1"><path d="M6.96,7.68c-0.12,0.03 -0.24,0.075 -0.36,0.12c-3.735,0.525 -6.6,3.69 -6.6,7.56c0,4.245 3.435,7.68 7.68,7.68c4.245,0 7.68,-3.435 7.68,-7.68h17.88c4.785,0 7.275,0.99 9.12,2.64c1.815,1.635 3.285,4.395 4.56,8.64l30.72,123.96c1.155,4.395 2.31,8.925 5.52,12.6c1.335,1.53 3.015,2.775 5.04,3.72c-2.25,2.67 -3.72,5.985 -3.72,9.72c0,8.445 6.915,15.36 15.36,15.36c8.445,0 15.36,-6.915 15.36,-15.36c0,-2.82 -0.825,-5.4 -2.16,-7.68h23.52c-1.335,2.28 -2.16,4.86 -2.16,7.68c0,8.445 6.915,15.36 15.36,15.36c8.445,0 15.36,-6.915 15.36,-15.36c0,-3.945 -1.605,-7.47 -4.08,-10.2c0.435,-1.17 0.255,-2.49 -0.45,-3.51c-0.72,-1.035 -1.89,-1.65 -3.15,-1.65h-58.68c-5.925,0 -8.34,-1.26 -9.96,-3.12c-1.605,-1.845 -2.58,-5.07 -3.72,-9.36v-0.12l-2.64,-10.44h70.44c1.605,0 3.06,-1.005 3.6,-2.52l27.6,-72.96c0.45,-1.17 0.285,-2.49 -0.435,-3.525c-0.72,-1.035 -1.905,-1.65 -3.165,-1.635h-117.96l-8.16,-32.76c0,-0.075 0,-0.165 0,-0.24c-1.47,-4.95 -3.375,-9.24 -6.84,-12.36c-3.465,-3.12 -8.34,-4.56 -14.28,-4.56h-25.56c-0.12,0 -0.24,0 -0.36,0c-0.12,0 -0.24,0 -0.36,0zM64.44,65.28h110.52l-24.72,65.28h-69.6zM99.84,168.96c4.29,0 7.68,3.39 7.68,7.68c0,4.29 -3.39,7.68 -7.68,7.68c-4.29,0 -7.68,-3.39 -7.68,-7.68c0,-4.29 3.39,-7.68 7.68,-7.68zM149.76,168.96c4.29,0 7.68,3.39 7.68,7.68c0,4.29 -3.39,7.68 -7.68,7.68c-4.29,0 -7.68,-3.39 -7.68,-7.68c0,-4.29 3.39,-7.68 7.68,-7.68z"></path></g></g></g></svg>
<?php echo Yii::t('app','Buy Now Management'); ?> <i class="mdi mdi-chevron-down float-right"></i></a>

                    <ul class="collapse list-unstyled Submenu <?=$subActive?>" id="Management">
                        <?php if(empty($role) || in_array('commission', $priviliges)) { ?>
                    <?php if ($controllerName == 'commissions' && ($actionName == 'index' || $actionName == 'create' || $actionName == 'update'))
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['commissions/index'])?>"><?php echo Yii::t('app','Commission setup'); ?></a>
                        </li>
                          <?php } ?> 
                              <?php if(empty($role) || in_array('invoices', $priviliges)) { ?>
                        <?php $subActive = '';
                                    if ($controllerName == 'invoices')
                                    $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                             <?php } ?> 
                            <a href="<?= Yii::$app->urlManager->createUrl(['invoices/index'])?>"><?php echo Yii::t('app','Invoices'); ?></a>
                        </li>
                           <?php if(empty($role) || in_array('order', $priviliges)) { ?>
                        <?php $subActive = '';
                                    if ($controllerName == 'orders')
                                    $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['orders/scroworders'])?>"><?php echo Yii::t('app','Orders'); ?></a>
                        </li>
                      <?php } ?>
                    </ul>
                </li>
                  <?php } ?>
                <?php } ?>
                            <?php $active = '';$subActive = '';$fill='#000';
                            if (($controllerName == 'sitesettings' && $actionName == 'sociallogin') ||
                            ($controllerName == 'sitesettings' && $actionName == 'defaultsettings')
                            || ($controllerName == 'sitesettings' && $actionName == 'showtop') ||
                            ($controllerName == 'sitesettings' && $actionName == 'smtpsettings') ||
                            ($controllerName == 'sitesettings' && $actionName == 'apidetails') ||
                            ($controllerName == 'sitesettings' && $actionName == 'logo') ||
                            ($controllerName == 'sitesettings' && $actionName == 'footersettings') ||
                            ($controllerName == 'sitesettings' && $actionName == 'messagesettings'))
                            {$active = 'active';$fill = '#007bff';$subActive='show';}
                            ?>
                             <?php if(empty($role) || in_array('api', $priviliges) || in_array('socialNtw', $priviliges) || in_array('footer', $priviliges) 
                              || in_array('default', $priviliges) ||in_array('email', $priviliges) ||in_array('sms', $priviliges) || in_array('logo', $priviliges)) { ?>

                            <li class="has_sub <?php echo $active; ?>">
                    <a href="#setting" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1"><path d="M123.72,7.92l-3,1.2l-10.8,4.2l-3,1.2l0.72,3.12l2.28,11.52c-1.125,1.125 -2.325,2.445 -3.6,3.84l-11.4,-2.52l-3.12,-0.72l-1.2,2.88l-4.68,10.8l-1.2,2.88l2.64,1.8l9.84,6.6c-0.12,1.875 -0.105,3.765 0,5.64l-10.08,6.24l-2.76,1.68l1.2,3l4.2,10.8l1.2,3l3.12,-0.72l11.52,-2.28c1.17,1.395 2.46,2.655 3.84,3.84l-2.64,11.52l-0.72,3.12l3,1.32l10.8,4.56l2.88,1.2l1.8,-2.64l6.6,-9.84c1.875,0.12 3.765,0.105 5.64,0l6.24,10.2l1.68,2.64l3,-1.08l13.8,-5.52l-0.72,-3.12l-2.64,-12.6c1.44,-1.215 2.745,-2.52 3.96,-3.96l11.64,3l3.24,0.84l1.2,-3l4.68,-10.8l1.32,-3l-2.88,-1.8l-10.08,-6.12c0.12,-1.875 0.105,-3.765 0,-5.64l10.08,-6.24l2.76,-1.68l-1.2,-3l-4.2,-10.8l-1.2,-3l-3.24,0.72l-11.76,2.64c-1.17,-1.395 -2.46,-2.655 -3.84,-3.84l2.52,-11.52l0.72,-3.12l-2.88,-1.2l-10.8,-4.68l-3,-1.2l-1.8,2.76l-6.12,9.72c-1.77,-0.18 -3.69,-0.015 -5.64,0.12l-6.24,-10.2zM120.48,17.4l5.64,9.24l1.44,2.28l2.64,-0.6c2.715,-0.6 5.28,-0.27 8.76,0.12l2.4,0.24l1.32,-2.04l5.52,-8.76l4.8,2.04l-2.4,10.32l-0.6,2.52l2.16,1.44c2.355,1.68 4.32,3.645 6,6l1.44,2.16l2.52,-0.6l10.56,-2.4l1.92,4.8l-9.24,5.52l-2.04,1.32l0.24,2.4c0.36,3.21 0.36,5.91 0,9.12l-0.24,2.4l2.04,1.32l9.12,5.64l-1.92,4.56l-10.68,-2.64l-2.64,-0.6l-1.44,2.16c-1.68,2.355 -3.645,4.32 -6,6l-2.04,1.44l0.48,2.52l2.4,11.4l-4.8,1.8l-5.52,-9.12l-1.32,-2.04l-2.4,0.24c-3.21,0.36 -5.91,0.36 -9.12,0l-2.4,-0.24l-1.2,1.92l-5.88,8.88l-4.92,-2.16l2.28,-10.32l0.6,-2.4l-2.04,-1.56c-2.355,-1.68 -4.32,-3.645 -6,-6l-1.44,-2.04l-2.52,0.48l-10.32,2.16l-1.8,-4.8l9.12,-5.64l2.16,-1.32l-0.36,-2.4c-0.36,-3.21 -0.36,-5.91 0,-9.12l0.36,-2.28l-2.04,-1.32l-8.88,-5.88l2.04,-4.92l10.44,2.4l2.28,0.48l1.44,-1.8c2.19,-2.55 4.44,-4.8 6.36,-6.72l1.44,-1.44l-0.36,-1.92l-2.16,-10.32zM134.4,38.4c-10.56,0 -19.2,8.64 -19.2,19.2c0,10.56 8.64,19.2 19.2,19.2c10.56,0 19.2,-8.64 19.2,-19.2c0,-10.56 -8.64,-19.2 -19.2,-19.2zM134.4,46.08c6.405,0 11.52,5.115 11.52,11.52c0,6.405 -5.115,11.52 -11.52,11.52c-6.405,0 -11.52,-5.115 -11.52,-11.52c0,-6.405 5.115,-11.52 11.52,-11.52zM51.96,76.8l-0.48,3.24l-1.92,12.48c-2.085,0.66 -4.095,1.62 -6,2.64l-13.2,-9.6l-13.44,13.44l1.8,2.64l7.32,10.56c-1.035,2.055 -1.95,4.11 -2.64,6.12l-12.6,2.28l-3.12,0.6v18.72l3.12,0.6l12.6,2.28c0.675,2.1 1.605,4.065 2.64,6l-7.56,10.2l-2.04,2.64l13.44,13.44l2.64,-1.8l10.56,-7.32c2.055,1.035 4.11,1.95 6.12,2.64l1.92,12.48l0.48,3.24h18.84l0.48,-3.12l2.4,-12.6c2.085,-0.66 4.095,-1.62 6,-2.64l13.2,9.6l13.44,-13.44l-1.8,-2.64l-7.68,-10.68c1.005,-2.025 1.965,-4.02 2.64,-6l12.96,-2.28l3.12,-0.6v-18.84l-16.2,-2.4c-0.66,-2.07 -1.5,-4.095 -2.52,-6l7.68,-10.56l1.8,-2.64l-2.16,-2.28l-8.88,-9.24l-2.28,-2.4l-2.76,1.92l-10.56,7.68c-2.025,-1.005 -4.02,-1.965 -6,-2.64l-1.92,-12.48l-0.48,-3.24zM58.56,84.48h5.76l2.16,13.68l2.16,0.72c3.45,1.155 6.675,2.235 9.24,3.84l2.16,1.32l2.04,-1.44l9.6,-6.96l4.32,4.56l-6.96,9.72l-1.56,2.04l1.44,2.16c1.725,2.76 3.075,5.955 3.72,8.88l0.6,2.52l2.52,0.36l11.76,1.8v5.88l-11.76,2.04l-2.28,0.36l-0.72,2.16c-1.155,3.45 -2.235,6.675 -3.84,9.24l-1.44,2.16l1.56,2.04l6.96,9.72l-4.32,4.2l-9.6,-6.96l-2.04,-1.44l-2.16,1.32c-2.76,1.725 -5.955,3.075 -8.88,3.72l-2.4,0.6l-0.48,2.52l-2.04,11.4h-5.88l-2.16,-13.68l-2.16,-0.72c-3.45,-1.155 -6.675,-2.235 -9.24,-3.84l-2.16,-1.32l-2.04,1.44l-9.72,6.6l-4.2,-4.2l6.84,-9.24l1.68,-2.16l-1.44,-2.16c-1.725,-2.76 -3.075,-5.835 -3.72,-8.76l-0.6,-2.52l-2.52,-0.48l-11.4,-2.04v-6l11.4,-2.04l2.28,-0.48l0.72,-2.04c1.155,-3.45 2.235,-6.675 3.84,-9.24l1.32,-2.16l-1.44,-2.04l-6.6,-9.72l4.32,-4.2l9.6,6.96l2.04,1.44l2.16,-1.32c2.76,-1.725 5.955,-3.075 8.88,-3.72l2.52,-0.6l0.36,-2.52zM61.44,111.36c-10.545,0 -19.2,8.655 -19.2,19.2c0,10.545 8.655,19.2 19.2,19.2c10.545,0 19.2,-8.655 19.2,-19.2c0,-10.545 -8.655,-19.2 -19.2,-19.2zM61.44,119.04c6.36,0 11.52,5.16 11.52,11.52c0,6.36 -5.16,11.52 -11.52,11.52c-6.36,0 -11.52,-5.16 -11.52,-11.52c0,-6.36 5.16,-11.52 11.52,-11.52z"></path></g></g></g></svg>
<?php echo Yii::t('app','Site Settings'); ?> <i class="mdi mdi-chevron-down float-right"></i></a>

                    <ul class="collapse list-unstyled Submenu <?=$subActive?>" id="setting">
                       <?php if(empty($role) || in_array('api', $priviliges)) { ?>
                    <?php $subActive = '';
                                if ($controllerName == 'sitesettings' && $actionName == 'apidetails')
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/apidetails'])?>"><?php echo Yii::t('app','API Credentials'); ?></a>
                        </li>
                      <?php } ?>
                       <?php if(empty($role) || in_array('socialNtw', $priviliges)) { ?>
                      
                        <?php $subActive = '';
                                if ($controllerName == 'sitesettings' && $actionName == 'sociallogin')
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/sociallogin'])?>"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Social Networks');$fill = '#007bff'; ?></a>
                        </li>
                           <?php } ?>
                                 <?php if(empty($role) || in_array('footer', $priviliges)) { ?>
                        <?php $subActive = '';
                                if ($controllerName == 'sitesettings' && $actionName == 'footersettings')
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/footersettings'])?>"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Footer Settings'); ?></a>
                        </li>
                      <?php } ?>
                        <?php if(empty($role) || in_array('default', $priviliges)) { ?>
                        <?php $subActive = '';
                                if ($controllerName == 'sitesettings' && $actionName == 'defaultsettings')
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/defaultsettings'])?>"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Default').' '.Yii::t('app','Settings'); ?></a>
                        </li>
                      <?php } ?>
                        <?php if(empty($role) || in_array('email', $priviliges)) { ?>
                        <?php  $subActive = '';
                                if ($controllerName == 'sitesettings' && $actionName == 'smtpsettings')
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/smtpsettings'])?>"><?php echo Yii::t('app','Email').' '.Yii::t('app','Settings'); ?></a>
                        </li>
                      <?php } ?>
                       <?php if(empty($role) || in_array('sms', $priviliges)) { ?>
                        <?php $subActive = '';
                                if ($controllerName == 'sitesettings' && $actionName == 'messagesettings')
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/messagesettings'])?>"><?php echo Yii::t('app','Mobile').' '.Yii::t('app','SMS').' '.Yii::t('app','Settings'); ?></a>
                        </li>
                      <?php } ?>
                        <?php if(empty($role) || in_array('logo', $priviliges)) { ?>

                          <?php $subActive = '';
                                if ($controllerName == 'sitesettings' && $actionName == 'logo')
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/logo'])?>"><?php echo Yii::t('app','Logo Settings'); ?></a>
                        </li>
                      <?php } ?>
                    </ul>
                </li>
              <?php } ?>
                 <?php if(empty($role) || in_array('seo', $priviliges)) { ?>
                             <?php $active = '';$subActive = '';$fill='#000';
                            if ($controllerName == 'sitesettings' && $actionName=="seosetting")
                            {$active = 'active';$fill = '#007bff';}
                            ?> 
                        <li class="<?php echo $active; ?>">
                        <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/seosetting'])?>">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20.039 20.823" style=" fill:#000000;margin-right: 10px; opacity: 0.8; "> 
                        <g id="Group_69" data-name="Group 69" transform="translate(-8162.931 -7124.129)">
                            <path id="Path_430" data-name="Path 430" d="M8.12,16.453l-.679.942A7.841,7.841,0,0,1,12.04,3.2V2.5a.1.1,0,0,1,.153-.066l1.84,1.27a.106.106,0,0,1,0,.153l-1.84,1.27a.1.1,0,0,1-.153-.066V4.386A6.666,6.666,0,0,0,8.12,16.453Zm9.921,1.511a1.565,1.565,0,0,0,.35,1.664l3.154,3.154a1.586,1.586,0,0,0,2.234,0h0a1.586,1.586,0,0,0,0-2.234l-3.132-3.154a1.591,1.591,0,0,0-1.664-.372l-.92-.92a7.771,7.771,0,0,0,1.862-5.059,7.854,7.854,0,0,0-3.241-6.351L16,5.635a6.68,6.68,0,0,1-3.92,12.089h0v-.679a.1.1,0,0,0-.153-.066l-1.862,1.248a.106.106,0,0,0,0,.153l1.84,1.27a.1.1,0,0,0,.153-.066v-.679h0a7.771,7.771,0,0,0,5.059-1.862Zm-7.906-3.614-.767.548a.341.341,0,0,1-.46-.044l-.329-.329L8.252,14.2a.365.365,0,0,1-.044-.46l.526-.745a3.257,3.257,0,0,1-.416-.986l-.92-.153a.336.336,0,0,1-.285-.35v-.92a.374.374,0,0,1,.285-.35l.92-.153a3.866,3.866,0,0,1,.394-.986l-.548-.767a.346.346,0,0,1,.066-.416l.329-.329.329-.329a.365.365,0,0,1,.46-.044l.745.526A3.634,3.634,0,0,1,11.1,7.321l.153-.92a.336.336,0,0,1,.35-.285h.92a.374.374,0,0,1,.35.285l.153.92a3.257,3.257,0,0,1,.986.416l.745-.548a.4.4,0,0,1,.46.022l.329.329.329.329a.365.365,0,0,1,.044.46l-.548.745a3.921,3.921,0,0,1,.416.986l.92.153a.336.336,0,0,1,.285.35v.92a.374.374,0,0,1-.285.35l-.942.153a3.257,3.257,0,0,1-.416.986l.548.745a.341.341,0,0,1-.044.46l-.329.328-.329.329a.365.365,0,0,1-.46.044l-.745-.548a4.059,4.059,0,0,1-.964.394l-.153.92a.336.336,0,0,1-.35.285H11.6a.374.374,0,0,1-.35-.285l-.153-.9A2.8,2.8,0,0,1,10.135,14.351Zm.438-1.818a2.137,2.137,0,1,0,0-3.022A2.141,2.141,0,0,0,10.573,12.533Z" transform="translate(8158.73 7121.709)" />
                        </g>
                    </svg> 
                        <?php echo Yii::t('app','Seo Settings'); ?> </a> 

                </li>
              <?php } ?>


    <?php if(empty($role) || in_array('banner', $priviliges)) { 
              $active = '';$subActive = ''; $display ='display:none';$fill='#000';
                         

                            if ($controllerName == 'banners'){
                         $active = 'active';   $display = "display:block";$fill = '#007bff';$subActive='show';
                             } 
                               if ($controllerName == 'sitesettings' && $actionName == 'ads'){
                          $active = 'active'; $display = "display:block";$fill = '#007bff';$subActive='show';
                             } 
                            ?>
                  <li class="has_sub <?php echo $active; ?>">
                    <a href="#Banners" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                       <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                        width="20" height="20"
                        viewBox="0 0 192 192"
                        style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><g id="surface1"><path d="M0,15.36v161.28h192v-161.28zM7.68,23.04h176.64v145.92h-176.64zM19.2,34.56v78.72c-0.6,1.125 -0.6,2.475 0,3.6v40.56h153.6v-39.36c0.06,-0.405 0.06,-0.795 0,-1.2v-82.32zM26.88,42.24h138.24v69.48l-29.28,-11.64c-1.005,-0.405 -2.145,-0.36 -3.12,0.12l-27.84,13.92l-36.48,-51.12c-0.84,-1.185 -2.28,-1.785 -3.72,-1.56c-0.885,0.15 -1.695,0.63 -2.28,1.32l-35.52,41.52zM126.72,57.6c-8.445,0 -15.36,6.915 -15.36,15.36c0,8.445 6.915,15.36 15.36,15.36c8.445,0 15.36,-6.915 15.36,-15.36c0,-8.445 -6.915,-15.36 -15.36,-15.36zM126.72,65.28c4.29,0 7.68,3.39 7.68,7.68c0,4.29 -3.39,7.68 -7.68,7.68c-4.29,0 -7.68,-3.39 -7.68,-7.68c0,-4.29 3.39,-7.68 7.68,-7.68zM64.92,71.52l35.64,49.8c1.095,1.515 3.12,2.025 4.8,1.2l29.16,-14.64l30.6,12.24v29.64h-138.24v-33.84z"></path></g></g></g></svg>
                        <?=Yii::t('app','Banners')?> <i class="mdi mdi-chevron-down float-right"></i></a>

                    <ul class="collapse list-unstyled Submenu <?=$subActive?>" id="Banners">
                    <?php if ($controllerName == 'banners')
                                $subActive = 'active';$fill = '#007bff'; ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['banners/index'])?>"><?php echo Yii::t('app','Banner').' '.Yii::t('app','Settings'); ?></a>
                        </li>
                        <?php $subActive = '';
                                     if ($controllerName == 'sitesettings' && $actionName == 'ads')
                                    $subActive = 'active'; ?>
                               

                                  <?php if($paidbannerStatus==1){ ?>
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['sitesettings/ads'])?>"><?php echo Yii::t('app','Advertisement').' '.Yii::t('app','Settings'); ?></a>
                        </li>
                      <?php } ?>
                        <?php $subActive = '';
                                   if ($controllerName == 'banners' && ($actionName == 'paidbanner' || $actionName == 'bannerlist'||$actionName == 'cancelled'))
                                    $subActive = 'active'; ?>
                                   <?php if($paidbannerStatus==1){ ?>
                        <li >
                            <a href="<?= Yii::$app->urlManager->createUrl(['banners/paidbanner'])?>"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Paid banners'); ?> </a>
                        </li>
                               <?php } ?>
                          <?php $subActive = '';
                                    if ($controllerName == 'banners' && ($actionName == 'bannervideo'))
                                    $subActive = 'active'; ?>
                        <li >
                            <a href="<?= Yii::$app->urlManager->createUrl(['banners/bannervideo'])?>"><?php echo Yii::t('app','Manage').' '.Yii::t('app','Video banner'); ?> </a>
                        </li>
                    </ul>
                </li>
                 <?php } ?>  
                   <?php if(empty($role) || in_array('help', $priviliges)) { ?>
                <?php $active = '';$subActive = '';$fill='#000';
                            if ($controllerName == 'help')
                            {$active = 'active';$fill = '#007bff';}
                            ?>
                            <li class="<?php echo $active; ?>">  
                    <a href="<?= Yii::$app->urlManager->createUrl(['help/index'])?>">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
width="20" height="20"
viewBox="0 0 192 192"
style=" fill:#000000;margin-right: 10px"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,192v-192h192v192z" fill="none"></path><g fill="<?php echo $fill; ?>"><path d="M96,7.68c-48.73231,0 -88.32,39.58769 -88.32,88.32c0,48.73231 39.58769,88.32 88.32,88.32c48.73231,0 88.32,-39.58769 88.32,-88.32c0,-48.73231 -39.58769,-88.32 -88.32,-88.32zM96,15.36c44.58172,0 80.64,36.05828 80.64,80.64c0,44.58172 -36.05828,80.64 -80.64,80.64c-44.58172,0 -80.64,-36.05828 -80.64,-80.64c0,-44.58172 36.05828,-80.64 80.64,-80.64zM97.11,48.03c-14.3232,0 -23.82024,8.67888 -25.89,21.9c-0.1344,0.82944 0.27306,1.37676 1.1025,1.515l8.6775,1.5225c0.82944,0.13824 1.37676,-0.2769 1.515,-1.1025c1.6512,-8.40192 6.60678,-13.0875 14.3175,-13.0875c7.8528,0 13.3575,4.96104 13.3575,12.81c0,4.6848 -1.64964,7.85136 -6.465,14.46l-9.2325,12.6675c-2.89152,3.99744 -4.125,6.89076 -4.125,12.405v5.64c0,0.8256 0.5469,1.38036 1.3725,1.365h9.09c0.8256,0 1.3725,-0.5469 1.3725,-1.3725v-4.4025c0,-4.68096 0.83172,-6.61038 3.585,-10.3275l9.225,-12.675c4.6848,-6.47424 7.02,-11.29002 7.02,-17.9025c0,-13.63584 -10.05402,-23.415 -24.9225,-23.415zM91.0575,128.61c-0.82944,0 -1.38,0.5544 -1.38,1.38v11.8425c0,0.8256 0.54672,1.3725 1.38,1.3725h10.47c0.82176,0 1.3725,-0.54306 1.3725,-1.3725v-11.8425c0,-0.82176 -0.5469,-1.38 -1.3725,-1.38z"></path></g></g></svg>
                        <?=Yii::t('app','Help Pages')?> </a>

                </li>
     <?php } ?>  
            </ul>
        
                            </div>    </div></nav>

        <!-- Page Content  -->
        <div id="content" class="category">
            <nav class="navbar navbar-expand-lg navbar-light pl-0 pr-0 m-b15 mynavbar">
                <div class="d-flex justify-content-between container-fluid pl-0 p-r15">
<div class="d-flex">
                    <button type="button" id="sidebarCollapse"
                        class="d-flex d-inline-block  d-lg-none d-xl-none btn blueTxtClr fontSize30 boxShadowNone d-none justify-content-center align-items-center border-0">
                        <i class="mdi mdi-menu"></i>

                    </button>
                    <div class="align-self-center">
                    <form id="myForm" method="post">
                        <select id="language-selector" class="form-control" onchange="callLang()">
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
                        </form> 
                    </div>
                </div>
                        
                        <div class="align-self-center">
                            <div class="half">
                                <label for="profile2" class="profile-dropdown m-b0 ">
                                    <input type="checkbox" id="profile2">
                                    <div class="d-flex">
                                        <i class="mdi mdi-account fontSize30"></i>

                                        <label for="profile2" class="align-self-center">
                                            <i class="mdi mdi-chevron-down fontSize20"></i></label>
                                    </div>
                                    <ul class=" position-absolute dnone m-t10 p-b5">
                                        <!-- <li><a href="#" class="p-t10 p-l15"><i class="mdi mdi-email-outline"></i>Messages</a></li> 
                                                    <li><a href="#" class="p-t10 p-l15"><i class="mdi mdi-account"></i>Account</a></li>-->
                                        <li><a href="<?= Yii::$app->urlManager->createUrl(['admin/profile'])?>" class="p-t10 p-l15"><i class="mdi mdi-settings"></i><?=Yii::t("app","Settings")?></a>
                                        </li>
                                        <li><a href="<?= Yii::$app->urlManager->createUrl(['admin/changepassword'])?>" class="p-t10 p-l15"><i class="mdi mdi-refresh"></i><?=Yii::t("app","Password")?></a>
                                        </li>
                                        <li data-toggle="modal" data-target="#logout"><a href="#" class="p-t10 p-l15"><i
                                                    class="mdi mdi-logout"></i><?=Yii::t("app","Logout")?></a></li>
                                    </ul>
                                </label>
                            </div>
                        </div>

                </div>
            </nav>
            <div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content w-75 mx-auto text-center">
    
                <div class="modal-body">
                        <?=Yii::t("app","Are you sure you want to logout ?")?>
                </div>
                <div class="m-t20 m-b20 text-center justify-content-center">
                 
                  <button type="button" class="btn btn-primary m-r20"><a href="<?= Yii::$app->urlManager->createUrl(['admin/logout'])?>" style="color:#fff;"><?=Yii::t("app","Okay")?></a></button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><?=Yii::t("app","Cancel")?></button>
                </div>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

