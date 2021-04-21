<?php
use yii\helpers\Html;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use conquer\toastr\ToastrWidget;
use frontend\models\PasswordResetRequestForm;


$userModel = new PasswordResetRequestForm();

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container">	
				
		<div class="row">
			<div class="site-maintenance col-xs-12 col-sm-12 col-md-12 col-lg-12">								
				<div class="mainten text-center">
					<img src="Online-Classifieds-site-maintanace_files/site-maintenance.png" class="site-img img-responsive">
					<h1>Sorry, We're down for maintenance.</h1>
					<h3>We will be back shortly.</h3>
				</div>		
			</div>
		</div>		

	</div>
	<?php exit; ?>