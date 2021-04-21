<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
?>

<?php $currentslug = $helppageModel->slug; ?>

<div class="container">	
	<div class="row">		
			<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
				 <ol class="breadcrumb">
					<li><a href="#"><?php echo Yii::t('app','Home'); ?></a></li>
					<li><a href="#"><?php echo Yii::t('app','Help'); ?></a></li>					 
				 </ol>			
			</div>			
		</div>	
				
		<div class="row">
				<div class="help col-xs-12 col-sm-12 col-md-12 col-lg-12">								
					
					<ul class="nav nav-tabs col-xs-12 col-sm-3 col-md-3 col-lg-2 no-hor-padding ">
						<div class="help-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<h2 class="top-heading-text"><?php echo Yii::t('app','Help'); ?></h2>						
						</div>
						<?php foreach ($allhelppageModel as $helppage) { ?>
							<?php ($currentslug == $helppage->slug)? $status=' active' : $status=''; ?>
							<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding<?php echo $status ?>">

							<?=Html::a(Yii::t('app',$helppage->page),array('message/help/','details' => $helppage->slug)); ?>
							
							</li>
						<?php } ?>
					</ul>

					<div class="col-xs-12 col-sm-9 col-md-9 col-lg-10 no-hor-padding">
					  <div class="help-rig-content active">
						<h3><?php echo Yii::t('app', $helppageModel->page);?></h3>
				<?php

				 $session_lang =  $_SESSION['language'];  
         		 $helpcontent = Json::decode($helppageModel->pageContent,true);

		        if ($helpcontent!="") {

		         if (array_key_exists($session_lang, $helpcontent)) {
		      
		           	$help_desc = $helpcontent[$session_lang]['content'];

		         } 
		             else
		         {


		         	  $firstelem = array_keys($helpcontent)[0];
     				  $help_desc = $helpcontent[$firstelem]['content'];
		         }

		     	}
		     	?>
						<?php echo  $help_desc; ?>
						
					  </div>
					</div>
				</div>
		</div>		
	</div>

