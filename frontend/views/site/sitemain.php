<?php 

$Description = $settings['sitename'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $Description;?></title>
     <link rel="icon" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/';?>/media/logo/favicon.png">

    <!-- Bootstrap -->
    <link href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/';?>css/bootstrap.min.css" rel="stylesheet">
	 <link href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/';?>css/style.css" rel="stylesheet">
    <!--E O Font awesome style-->
<style type="text/css">
    /*************************maintenance page style***********************/

.site-maintenance {
    margin: 50px 0;
    min-height: 100px;
}
.site-maintenance .site-img {
    display: inline-block;
}

</style>
  </head>
  <body>

<div class="container">	
				
		<div class="row">
			<div class="site-maintenance col-xs-12 col-sm-12 col-md-12 col-lg-12">								
				<div class="mainten text-center">
					<img src="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>images/site-maintenance.png" class="site-img img-responsive">
					<h1><?php echo Yii::t('app',$settings->maintenance_text); ?></h1>
					
				</div>		
			</div>
		</div>		

	</div>
	  </body>
</html>
	<?php exit; ?>