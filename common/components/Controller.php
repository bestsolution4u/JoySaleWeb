<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Sitesettings;
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends \yii\web\Controller
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $fbtitle;
	public $fbimg;
	public $fbdescription;
	public $sitename;
	public $metaTitle;
	public $metaDescription;
	//public $layout='//layouts/column1';
	private $_identity;
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public function init() {
		$app = Yii::$app;
		$siteSetting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
		$sitename = $siteSetting->sitename;
		$metaData = json_decode($siteSetting->metaData, true);
		if(!empty($metaData)){
			$metaTitle = $metaData['metaTitle'];
			$metaDescription = $metaData['metaDescription'];
		}
		$app->name = $siteSetting->sitename;

		/*if(isset(Yii::app()->request->cookies))
		{
			$cookiesusername = Yii::app()->request->cookies['username'];
			$cookiespassword = Yii::app()->request->cookies['password'];
			$this->_identity=new UserIdentity($cookiesusername,$cookiespassword);
			//Yii::app()->user->login($this->_identity);
			unset(Yii::app()->request->cookies['username']);
			unset(Yii::app()->request->cookies['password']);
			return true;
		}*/

		if (isset($_POST['_lang']))
		{
			$app->language = $_POST['_lang'];
			$app->session['_lang'] = $app->language;

			if(Yii::$app->controller->module == Yii::$app->getModule('admin') ) {
				$adtrans = new JsTrans('admin',$app->language);
			} else {
				$apptrans = new JsTrans('app',$app->language);
			}
			//print_r($apptrans);
			//$apptrans = new JsTrans('app',$app->language);
		}
		else if (isset($app->session['_lang']))
		{
			$app->language = $app->session['_lang'];
			if(Yii::$app->controller->module == Yii::$app->getModule('admin') ) {
				$adtrans = new JsTrans('admin',$app->language);
			} else {
				$apptrans = new JsTrans('app',$app->language);
			}
		} else {
			if(Yii::$app->controller->module == Yii::$app->getModule('admin') ) {
				$adtrans = new JsTrans('admin',$app->language);
			} else {
				$apptrans = new JsTrans('app',$app->language);
			}
		}
	}
}