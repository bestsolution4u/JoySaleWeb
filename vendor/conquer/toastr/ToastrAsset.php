<?php
/**
 * @link https://github.com/borodulin/yii2-toastr
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-toast/blob/master/LICENSE.md
 */
namespace conquer\toastr;

/**
 * Toastr home page: {@link https://github.com/CodeSeven/toastr}
 */
class ToastrAsset extends \yii\web\AssetBundle
{
	// The files are not web directory accessible, therefore we need
	// to specify the sourcePath property. Notice the @bower alias used.
	public $sourcePath = '@bower/toastr';
	
	public $css=[
		'toastr.min.css',
	];
	
	public $js=[
		'toastr.min.js',
	];	
}