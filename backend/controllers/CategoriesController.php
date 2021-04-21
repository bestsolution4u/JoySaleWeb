<?php

namespace backend\controllers;
use yii\helpers\ArrayHelper;
use Yii;
use common\models\Categories;
use common\models\Sitesettings;
use common\models\Filter;
use common\models\Filtervalues;
use backend\models\CategoriesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\Products;
use yii\data\Pagination;
use common\components\Myclass;
use yii\helpers\Json;
error_reporting(0);
class CategoriesController extends Controller
{
   
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actions()
    {
     
         $model = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
         if(isset($model->sitename)) {
         Yii::$app->view->title =  $model->sitename;     
        }
        else
        {
                     Yii::$app->view->title =  "Joysale";     

        }

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
   public function beforeAction($action) {
            if (Yii::$app->user->isGuest) {            
                return $this->goHome();          
            }
            return true;
    }
   
    public function actionIndex()
    {
        $this->layout="page";
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['=','parentCategory',0]);
         $dataProvider->pagination->pageSize=10;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubcategory($id)
    {
       //   echo "string"; die;
        $this->layout="page";

        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['=','parentCategory',$id]);
        $dataProvider->pagination->pageSize=10;

        return $this->render('subcategory', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSub_subcategory($id)
    {
       //   echo "string"; die;
        $this->layout="page";
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['=','parentCategory',$id]);
        $dataProvider->pagination->pageSize=10;

        return $this->render('sub_subcategory', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionAdmin()
    {
        $model=new Categories();
         $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       // $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Categories']))
        $model->attributes=$_GET['Categories'];

        $this->render('admin',array(
            'model'=>$model,'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ));
    }

  
    public function actionView($id)
    {
        $getCategory = $this->findModel($id);
        $attributes = explode(',', $getCategory->categoryAttributes);
        $filterValues = array();
        foreach($attributes as $key=>$val)
        {
            $filterValues[] = Filter::find()->where(['id'=>$val])->one();
            
        }
        //Filtervalues::find()->where(['filter_id'])
        return $this->render('view', [
            'model' => $this->findModel($id),
            'filters'=>$filterValues
        ]);
    }

  
    public function actionCreate()
    {
        $model=new Categories();

        $parentCategory = array();
        $parentCategory = Categories::find()->where(['parentCategory' => 0])->all();
        if (!empty($parentCategory)){
            $parentCategory = ArrayHelper::map($parentCategory, 'categoryId', 'name');

            // Helpers::listData($parentCategory, 'categoryId', 'name');
        }

        $filters = Filter::find()->where(['status' => 1])->all();    

        /*
        $getMultifilters = $filters = Filter::find()
                    ->where(['status' => 1,'type'=>'multilevel'])->all();

                    learn to be techie
                    programming, business logics, how to lead a team, app algorithms
        */
        
        // Uncomment the following line if AJAX validation is needed
        //$this->performAjaxValidation($model);



        if(isset($_POST['Categories']))
        {
            
            $existcategory = Categories::find()
                                ->where(['name'=>$_POST['Categories']['name']])
                                ->all();
      
                    if(count($existcategory)==0)
                    {
                            $model->attributes=$_POST['Categories'];
                            if ($model->parentCategory == ''){
                                $model->parentCategory = 0;
                                $model->subcategoryVisible=0;
                            }else{
                                $model->subcategoryVisible=$_POST['Categories']['subcategoryVisible'];
                            }
                            $catImage = UploadedFile::getInstances($model,'image');
                            if(!empty($catImage)) {
                                $imageName = explode(".",$catImage[0]->name);
                                $model->image = rand(000,9999).'-'.yii::$app->Myclass->productSlug($imageName[0]).'.'.$catImage[0]->extension;
                              //  print_r($model->image);exit;
                            }

                            $categoryProperty = array();
                            if ($_POST['Categories']['itemCondition'] == 1){
                                $categoryProperty['itemCondition'] = 'enable';
                            }elseif ($_POST['Categories']['itemCondition'] == 0){
                                $categoryProperty['itemCondition'] = 'disable';
                            }
                            if ($_POST['Categories']['exchangetoBuy'] == 1){
                                $categoryProperty['exchangetoBuy'] = 'enable';
                            }elseif ($_POST['Categories']['exchangetoBuy'] == 0){
                                $categoryProperty['exchangetoBuy'] = 'disable';
                            }
                            if(isset($_POST['Categories']['buyNow'])) {
                            if ($_POST['Categories']['buyNow'] == 1){
                                $categoryProperty['buyNow'] = 'enable';
                            }elseif ($_POST['Categories']['buyNow'] == 0){
                                $categoryProperty['buyNow'] = 'disable';
                            } 
                        }
                            if ($_POST['Categories']['myOffer'] == '1'){
                                $categoryProperty['myOffer'] = 'enable';
                            }elseif ($_POST['Categories']['myOffer'] == 0){
                                $categoryProperty['myOffer'] = 'disable';
                            }
                            /*if ($_POST['Categories']['contactSeller'] == '1'){
                                $categoryProperty['contactSeller'] = 'enable';
                            }elseif ($_POST['Categories']['contactSeller'] == 0){
                                $categoryProperty['contactSeller'] = 'disable';
                            }*/


                            $model->categoryProperty = json_encode($categoryProperty);
                            
                            $model->slug = yii::$app->Myclass->productSlug($model->name);

                            if(!empty($_POST['attributes']))
                            {
                                $model->categoryAttributes = implode(',', $_POST['attributes']);    
                            }
                            $model->createdDate = date('Y-m-d h:m:s');

                            //print_r($model->categoryProperty);exit;
                           
                               $catImage = UploadedFile::getInstances($model,'image');
                    if(!is_null($catImage)) {
                        $logoUploadValues = array();
                        $logoUploadValues = getimagesize($catImage[0]->tempName);
                        $extensionarray = array('jpg', 'png', 'jpeg');
                        $extension=$catImage[0]->extension;

                        if (in_array($extension, $extensionarray) && $logoUploadValues[0] > "0" && $logoUploadValues[1] > "0"  && (end($logoUploadValues) == "image/jpeg" || end($logoUploadValues) == "image/png") && count($logoUploadValues) >= 6) {

                            $imageName = explode(".",$catImage[0]->name);
                            $model->image = rand(000,9999).'-'.yii::$app->Myclass->productSlug($imageName[0]).'.'.$catImage[0]->extension;

                            $catImage[0]->saveAs('uploads/'. $model->image);  
                        } 
                    }
                                if ($model->validate()) {
                                    $model->save(false);

                                    $siteSettings =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
                                    $decodeValue = json_decode($siteSettings->category_priority);
                                    if(!in_array($model->categoryId, $decodeValue))
                                    {
                                        array_push($decodeValue, $model->categoryId);
                                        $jsonData = json_encode($decodeValue);
                                        $siteSettingsModel =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
                                        $siteSettingsModel->category_priority = $jsonData;
                                        $siteSettingsModel->save(false);
                                    }

                                    Yii::$app->session->setFlash('success',Yii::t('app','Category/Subcategory Created'));
                                    return $this->redirect(['index']);
                                }

                    }else{
                            Yii::$app->session->setFlash('success',Yii::t('app','Category already exists')); 
                            return $this->redirect(['index']);
                    }
        }
            return $this->render('create', [
                        'model'=>$model, 
                        'parentCategory'=>$parentCategory,
                        'attributes'=>$filters,
                    ]);
    }


  
   public function actionUpdate($id)
    {
        $model=$this->findModel($id);
        $model->setScenario('update');

        $parentCategory = array();
        $parentCategory = Categories::find()->where(['parentCategory' => 0])->all();

        $getattributes = Filter::find()->where(['status' => 1])->all();

        

       //$model->getattributes = $getattributes;

       //echo '<pre>'; print_r($model); exit;
        //  print_r($parentCategory);exit;
        if (!empty($parentCategory)){
             $parentCategory =\yii\helpers\ArrayHelper::map($parentCategory, 'categoryId', 'name');

        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $oldImage = $model->image;
        $categoryProperty = json_decode($model->categoryProperty, true);

        if ($categoryProperty['itemCondition'] == 'enable'){
            $model->itemCondition = '1';
        }elseif ($categoryProperty['itemCondition'] == 'disable'){
            $model->itemCondition = '0';
        }
       /* if ($categoryProperty['contactSeller'] == 'enable'){
            $model->contactSeller = '1';
        }elseif ($categoryProperty['contactSeller'] == 'disable'){
            $model->contactSeller = '0';
        }*/
        if ($categoryProperty['exchangetoBuy'] == 'enable'){
            $model->exchangetoBuy = '1';
        }elseif ($categoryProperty['exchangetoBuy'] == 'disable'){
            $model->exchangetoBuy = '0';
        }
        if ($categoryProperty['buyNow'] == 'enable'){
            $model->buyNow = '1';
        }elseif ($categoryProperty['buyNow'] == 'disable'){
            $model->buyNow = '0';
        }
        if ($categoryProperty['myOffer'] == 'enable'){
            $model->myOffer = '1';
        }elseif ($categoryProperty['myOffer'] == 'disable'){
            $model->myOffer = '0';
        }

        //sub cat

        $getsubCategories = Categories::find()->where(['parentCategory'=>$id])->all();
        
        $mergeCat = array();
        //$getsub_subCategories = array();
        foreach($getsubCategories as $subkey=>$subval)
        {
            $mergeCat[] .= $subval->categoryAttributes;
            $getsub_subCategories = Categories::find()->where(['parentCategory'=>$subval->categoryId])->all();

        }
        
        if(!empty($mergeCat))
        {
            $slistchild = implode(',', $mergeCat);
            $listchild1 = explode(',', $slistchild);
        }else{
            $listchild1 = array();
        }

        //sub sub cate

        $mergesubCat = array();
        foreach($getsub_subCategories as $subkey=>$subval)
        {
            $mergesubCat[] .= $subval->categoryAttributes;
        }
        
        if(!empty($mergesubCat))
        {
            $slistchild1 = implode(',', $mergesubCat);
            $listchild2 = explode(',', $slistchild1);
        }else{
            $listchild2 = array();
        }

        $listchild = array_merge($listchild1, $listchild2);
        
        //print_r($getsub_subCategories);exit;

        if(isset($_POST['Categories']))
        {

//echo '<pre>'; print_r($_POST['attributes']); exit;
    //    if ($_POST['Categories']['parentCategory'] == ''){
    //             $_POST['Categories']['parentCategory'] = 0;
    //         }

           // print_r($id);exit;
        $existcategory = Categories::find()->where(['<>','categoryId', $id])->andWhere(['like','name',$_POST['Categories']['name']])->all();
        // if(count($existcategory)==0)
        // {
            $model->name=$_POST['Categories']['name'];
         //   $model->parentCategory=$_POST['Categories']['parentCategory'];
          //  $model->name=$_POST['Categories']['name'];
            //print_r($model->attributes);exit;
            if(!isset($_POST['Categories']['parentCategory']) || $_POST['Categories']['parentCategory'] == ""){
                $model->parentCategory = 0;
            }
             if(isset($_POST['Categories']['subcategoryVisible']) || $_POST['Categories']['subcategoryVisible'] != ""){
                $model->subcategoryVisible = $_POST['Categories']['subcategoryVisible'];
            }
            $catImage = UploadedFile::getInstances($model,'image');

            if(!empty($catImage)) {
                $logoUploadValues = array();
                $logoUploadValues = getimagesize($catImage[0]->tempName);
                $extensionarray = array('jpg', 'png', 'jpeg');
                $extension=$catImage[0]->extension; 

                if (in_array($extension, $extensionarray) && $logoUploadValues[0] > "0" && $logoUploadValues[1] > "0"  && (end($logoUploadValues) == "image/jpeg" || end($logoUploadValues) == "image/png") && count($logoUploadValues) >= 6) {
                    $imageName = explode(".",$catImage[0]->name);
                    $model->image = rand(000,9999).'-'.yii::$app->Myclass->productSlug($imageName[0]).'.'.$extension; 
                    $catImage[0]->saveAs('uploads/'.$model->image);
                } else {
                    $model->image = $oldImage;
                }
            } else {
                $model->image = $oldImage;
            }

            $categoryProperty = array();
            if ($_POST['Categories']['itemCondition'] == 1){
                $categoryProperty['itemCondition'] = 'enable';
            }elseif ($_POST['Categories']['itemCondition'] == 0){
                $categoryProperty['itemCondition'] = 'disable';
            }
            if ($_POST['Categories']['exchangetoBuy'] == 1){
                $categoryProperty['exchangetoBuy'] = 'enable';
            }elseif ($_POST['Categories']['exchangetoBuy'] == 0){
                $categoryProperty['exchangetoBuy'] = 'disable';
            }
            if ($_POST['Categories']['buyNow'] == 1){
                $categoryProperty['buyNow'] = 'enable';
            }elseif ($_POST['Categories']['buyNow'] == 0){
                $categoryProperty['buyNow'] = 'disable';
            }
            if ($_POST['Categories']['myOffer'] == '1'){
                $categoryProperty['myOffer'] = 'enable';
            }elseif ($_POST['Categories']['myOffer'] == 0){
                $categoryProperty['myOffer'] = 'disable';
            }
         /*   if ($_POST['Categories']['contactSeller'] == '1'){
                $categoryProperty['contactSeller'] = 'enable';
            }elseif ($_POST['Categories']['contactSeller'] == 0){
                $categoryProperty['contactSeller'] = 'disable';
            }*/

            /*
                $decodeValue = explode(',', $model->categoryAttributes);

            if(!in_array($_POST['attributes'], $decodeValue))
                $valMerge = array_merge($_POST['attributes'], $decodeValue);
            else

            */
                $valMerge = $_POST['attributes'];

            
            
            $model->categoryProperty = json_encode($categoryProperty);
            $model->categoryAttributes = implode(',', $valMerge);
            $model->slug = yii::$app->Myclass->productSlug($model->name);
           
                /*if(!empty($catImage)) {
                    $catImage[0]->saveAs('uploads/'.$model->image);
                }*/
                $model->save(false);
                    Yii::$app->session->setFlash('success',Yii::t('app','Category Updated'));
                    return $this->redirect(['index']);
                // }else{
                //    Yii::$app->session->setFlash('success',Yii::t('app','Category already exists'));
                // return $this->redirect(['index']); 
                // }
}

        return $this->render('create', [
            'model' => $model, 
            'parentCategory'=>$parentCategory,
            'attributes'=>$getattributes,
            'parentAttribute'=>$listchild
        ]);
    }

    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    public function actionDelete($id)
	{
		$model = $this->findModel($id);
       
        $products = Products::find()->where(['category'=>$id])
                    ->orWhere(['subCategory'=>$id])->all();

                   


		if(empty($products)) {
		$siteSettings =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
			$priorityCategories = $siteSettings->category_priority;
			if(!empty($priorityCategories)){
				$priorityCategories = Json::decode($priorityCategories, true);
				if(in_array($id, $priorityCategories)){
					$restricedCategories = array();
					foreach($priorityCategories as $priorityKey => $priorityCategory){
						if($priorityCategory != $id)
							$restricedCategories[] = $priorityCategory;
					}
					$filteredCategories = "";
					if(!empty($restricedCategories))
						$filteredCategories = Json::encode($restricedCategories);
					$siteSettings->category_priority = $filteredCategories;
					$siteSettings->save(false);
				}
			}
            $subcategories = Categories::find()->where(['parentCategory' => $id])->all();
			foreach($subcategories as $subcategory):
			$subcategory->delete();
			endforeach;
			$model->delete();
			$val = 0; 
		} else {
			$val = 1;
        }
        

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		// if(!isset($_GET['ajax'])) {
        //     Yii::$app->session->setFlash('success', Yii::t('app','Category Deleted Successfully'));
        //   //  $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        //     return $this->redirect(Yii::$app->request->referrer);

		// } else {
			if($val == 1) {
                Yii::$app->session->setFlash('error',Yii::t('app', 'One or more products has been added to this category.You cannot delete this category'));
                return $this->redirect(Yii::$app->request->referrer);

			} else {
                Yii::$app->session->setFlash('success',Yii::t('app', 'Category Deleted'));
                return $this->redirect(Yii::$app->request->referrer);

    		}
		//}
	}

    function actionShowtopcategory()
    {
        $model = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        $decodeval = json_decode($model->category_priority);
        $category = array();
        foreach($decodeval as $categoriesValue)
        {
            $category_settings = Categories::find()->where(['categoryId'=>$categoriesValue,
                    'parentCategory'=>'0'])->one();    
            $category[] = $category_settings->name;
        }


        $totalCategories = count($categories);
        $model = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

        if(isset($_POST['Sitesettings'])) 
        {
            
            $categoryCount = Categories::find()->where([
                    'parentCategory'=>'0'])->count();


            $unique = $_POST['Sitesettings']['priority'];

            $split = explode(',', $unique);

            //echo count($split); exit;
            if( $categoryCount != count($split) )
            {
                Yii::$app->session->setFlash('success',Yii::t('app','Something went wrong please check again.'));
                return $this->redirect(['showtopcategory']);
            }
            $categoryId = array();
            foreach($split as $keyval)
            {
                $getvals = yii::$app->Myclass->productSlug($keyval);
                $category_settings = Categories::find()->where(['slug'=>$getvals,
                    'parentCategory'=>'0'])->one();  
                if(empty($category_settings))
                {
                    Yii::$app->session->setFlash('success',Yii::t('app','Something went wrong please check again.'));
                    return $this->redirect(['showtopcategory']);
                }
                $categoryId[] = $category_settings->categoryId;
            }
            
            //echo '<pre>'; print_r($categoryId); exit;
            //echo '<pre>'; print_r($split); exit;
            /*
            foreach($_POST['Sitesettings']['priority'] as $value):
            if (in_array($value,$unique)) {
               
            }
            endforeach;
            */
            
            $settings = json_encode($categoryId);
            $model->category_priority = $settings;
            $model->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','Category priority settings updated successfully.'));
            return $this->redirect(['showtopcategory']);
        }

        /*
        if(!empty($model->category_priority)) {
            $topTen = json_decode($model->category_priority);
            if($topTen[0] == 'empty') {
                $topTen = array();

                for($i=0;$i < $totalCategories ; $i++) {
                    $topTen[] = 'empty';
                }  
            } else { 
                $count = count($topTen);
                for($i=$count;$i < $totalCategories ; $i++) {
                    $topTen[] = 'empty';
                }
            }
        } else {
            for($i=0; $i < $totalCategories; $i++) {
                $topTen[] = 'empty';
            } 
        }
*/
        return $this->render('showtopcategory', [
            'categorylist'=>implode(',', $category),
            'categoryCount'=>count($category),
            'categories' => $categories,
            'topTen' => $topTen, 
            'totalCategories' => $totalCategories
        ]);
    }

    function actionShowtopcatesssgory() 
    {
        $categories = Categories::find()->where(['parentCategory' => 0])->all();
        $model = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
     
        if(isset($_POST['Sitesettings'])) {
            echo '<pre>'; print_r($_POST['Sitesettings']);exit;
            $unique = $_POST['Sitesettings']['priority'];
            foreach($_POST['Sitesettings']['priority'] as $value):
            if (in_array($value,$unique)) {
               // print_r($value);exit;
            //    $val =Categories::find()->orderBy(['id' => $value])->one();
                // Categories::findOne($value);
            //  Yii::$app->session->setFlash('warning','You have selected twice.');

            }
            endforeach;
            $settings = json_encode($_POST['Sitesettings']['priority']);
            //print_r($settings);exit;
            $model->category_priority = $settings;
            $model->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','Category priority settings updated successfully'));
            return $this->redirect(['showtopcategory']);
        }

        if(!empty($model->category_priority)) {
            $topTen = json_decode($model->category_priority);
            $count = count($topTen);
            for($i=$count;$i < 10 ; $i++) {
                $topTen[] = 'empty';
            }
            if($topTen[0] == 'empty') {
             
                $curs = Categories::find()->where(['parentCategory' => 0])->limit(10)->all();
                $count = count($curs);
                $topTen = array();
              //  foreach($curs as $cur):
              //   $topTen[] = $cur->categoryId;
              // endforeach;
                for($i=0;$i < 10 ; $i++) {
                    $topTen[] = 'empty';
                }
               
            }
        } else {
                // print_r($model->category_priority);exit;
            $curs = Categories::find()->where(['parentCategory' => 0])->limit(10)->all();

            $count = count($curs);
            //print_r($count);exit;
            foreach($curs as $cur):
            $topTen[] = $cur->categoryId;
            endforeach;
            //print_r($topTen);exit;
            for($i=$count;$i < 10 ; $i++) {
                $topTen[] = 'empty';
            }
           
        }
        // print_r($topTen);exit;
 return $this->render('showtopcategory', [
            'categories' => $categories,'topTen' => $topTen,
        ]);
       
        }


    public function actionAdd()
    {
        $model=new Categories();
    
        $parentCategory = array();
        $parentCategory = Categories::find()->where(['parentCategory' => 0])->all();
        if (!empty($parentCategory)){
            $parentCategory = ArrayHelper::map($parentCategory, 'categoryId', 'name');
        }

        $filters = Filter::find()->where(['status' => 1])->all();

        $getParentdata = Categories::find()->where(['categoryId'=>$_GET['id']])->one();
        $parentAttributes = explode(',', $getParentdata->categoryAttributes);

        if(isset($_POST['Categories']))
        {
            $existcategory = Categories::find()->where(['name'=>$_POST['Categories']['name'], 'parentCategory'=>$_POST['Categories']['parentCategory']])->all();  

            if(count($existcategory)==0)
            {
              
                $model->attributes=$_POST['Categories'];
                if ($model->parentCategory == ''){
                    $model->parentCategory = 0;
                    $model->subcategoryVisible=0;
                }
                else
                {
                     $model->subcategoryVisible=0;
                }
                $catImage = UploadedFile::getInstances($model,'image');
                if(!empty($catImage)) {
                    $imageName = explode(".",$catImage[0]->name);
                    $model->image = rand(000,9999).'-'.yii::$app->Myclass->productSlug($imageName[0]).'.'.$catImage[0]->extension;
                } 
            
                $categoryProperty = array();
                if ($_POST['Categories']['itemCondition'] == 1) {
                    $categoryProperty['itemCondition'] = 'enable';
                } elseif ($_POST['Categories']['itemCondition'] == 0) {
                    $categoryProperty['itemCondition'] = 'disable';
                }

                if ($_POST['Categories']['exchangetoBuy'] == 1) {
                    $categoryProperty['exchangetoBuy'] = 'enable';
                } elseif ($_POST['Categories']['exchangetoBuy'] == 0) {
                    $categoryProperty['exchangetoBuy'] = 'disable';
                }

                if(isset($_POST['Categories']['buyNow'])) {
                    if ($_POST['Categories']['buyNow'] == 1) {
                        $categoryProperty['buyNow'] = 'enable';
                    } elseif ($_POST['Categories']['buyNow'] == 0) {
                        $categoryProperty['buyNow'] = 'disable';
                    } 
                }

                if ($_POST['Categories']['myOffer'] == '1') {
                    $categoryProperty['myOffer'] = 'enable';
                } elseif ($_POST['Categories']['myOffer'] == 0) {
                    $categoryProperty['myOffer'] = 'disable';
                }

                /*if ($_POST['Categories']['contactSeller'] == '1'){
                    $categoryProperty['contactSeller'] = 'enable';
                }elseif ($_POST['Categories']['contactSeller'] == 0){
                    $categoryProperty['contactSeller'] = 'disable';
                }*/
            
                $model->categoryProperty = json_encode($categoryProperty);
                $model->slug = yii::$app->Myclass->productSlug($model->name);            
                $model->createdDate = date('Y-m-d h:m:s');
            
                 
                if(!empty($catImage)) {
                    $catImage[0]->saveAs('uploads/'. $model->image);
                } else {
                    $model->image="";
                }
                $model->filters="";
                if(!empty($_POST['attributes']))
                {
                    $model->categoryAttributes = implode(',', $_POST['attributes']);   
                }
                if ($model->validate()) {
                    $model->save(false);
                    Yii::$app->session->setFlash('success',Yii::t('app','Subcategory Created'));
                    return $this->redirect(['subcategory','id'=>$_GET['id']]);
                }
            
            } else {
                Yii::$app->session->setFlash('error',Yii::t('app','Subcategory already exists')); 
              return $this->redirect(['subcategory','id'=>$_GET['id']]);
            }
        }

        return $this->render('add', [
            'model'=> $model, 
            'parentCategory'=> $parentCategory,
            'attributes'=> $filters,
            'parentAttributes'=> $parentAttributes
        ]);
    }


    public function actionSubadd()
    {
        $model=new Categories();
    
        $parentCategory = array();
        $parentCategory = Categories::find()->where(['parentCategory' => 0])->all();
        if (!empty($parentCategory)){
            $parentCategory = ArrayHelper::map($parentCategory, 'categoryId', 'name');
        }

        $filters = Filter::find()->where(['status' => 1])->all();

        $sub_getParentdata = Categories::find()->where(['categoryId'=>$_GET['id']])->one();
        $getParentdata = Categories::find()->where(['categoryId'=>$sub_getParentdata->parentCategory])->one();

        $sub_parentAttributes = explode(',', $sub_getParentdata->categoryAttributes);
        $parentAttributes = explode(',', $getParentdata->categoryAttributes);

        $attributes = array_merge($parentAttributes, $sub_parentAttributes);

        if(isset($_POST['Categories']))
        {
            $existcategory = Categories::find()->where(['name'=>$_POST['Categories']['name'], 'parentCategory'=>$_POST['Categories']['parentCategory']])->all();  

            if(count($existcategory)==0)
            {
              
                $model->attributes=$_POST['Categories'];
                if ($model->parentCategory == ''){
                    $model->parentCategory = 0;
                    $model->subcategoryVisible=0;
                }
                else
                {
                     $model->subcategoryVisible=0;
                }
                $catImage = UploadedFile::getInstances($model,'image');
                if(!empty($catImage)) {
                    $imageName = explode(".",$catImage[0]->name);
                    $model->image = rand(000,9999).'-'.yii::$app->Myclass->productSlug($imageName[0]).'.'.$catImage[0]->extension;
                } 
            
                $categoryProperty = array();
                if ($_POST['Categories']['itemCondition'] == 1) {
                    $categoryProperty['itemCondition'] = 'enable';
                } elseif ($_POST['Categories']['itemCondition'] == 0) {
                    $categoryProperty['itemCondition'] = 'disable';
                }

                if ($_POST['Categories']['exchangetoBuy'] == 1) {
                    $categoryProperty['exchangetoBuy'] = 'enable';
                } elseif ($_POST['Categories']['exchangetoBuy'] == 0) {
                    $categoryProperty['exchangetoBuy'] = 'disable';
                }

                if(isset($_POST['Categories']['buyNow'])) {
                    if ($_POST['Categories']['buyNow'] == 1) {
                        $categoryProperty['buyNow'] = 'enable';
                    } elseif ($_POST['Categories']['buyNow'] == 0) {
                        $categoryProperty['buyNow'] = 'disable';
                    } 
                }

                if ($_POST['Categories']['myOffer'] == '1') {
                    $categoryProperty['myOffer'] = 'enable';
                } elseif ($_POST['Categories']['myOffer'] == 0) {
                    $categoryProperty['myOffer'] = 'disable';
                }

                /*if ($_POST['Categories']['contactSeller'] == '1'){
                    $categoryProperty['contactSeller'] = 'enable';
                }elseif ($_POST['Categories']['contactSeller'] == 0){
                    $categoryProperty['contactSeller'] = 'disable';
                }*/
            
                $model->categoryProperty = json_encode($categoryProperty);
                $model->slug = yii::$app->Myclass->productSlug($model->name);            
                $model->createdDate = date('Y-m-d h:m:s');
            
                 
                if(!empty($catImage)) {
                    $catImage[0]->saveAs('uploads/'. $model->image);
                } else {
                    $model->image="";
                }
                $model->filters="";
                if(!empty($_POST['attributes']))
                {
                    $model->categoryAttributes = implode(',', $_POST['attributes']);   
                }
                if ($model->validate()) {
                    $model->save(false);
                    Yii::$app->session->setFlash('success',Yii::t('app','Subcategory Created'));
                    return $this->redirect(['sub_subcategory','id'=>$_GET['id']]);
                }
            
            } else {
                Yii::$app->session->setFlash('error',Yii::t('app','Subcategory already exists')); 
                   return $this->redirect(['sub_subcategory','id'=>$_GET['id']]);
            }
        }

        return $this->render('sub_add', [
            'model'=> $model, 
            'parentCategory'=> $parentCategory,
            'attributes'=> $filters,
            'parentAttributes'=> $attributes
        ]);
    }


   public function actionUpdatesubcategory($id,$cat)
   {
       
      $model=$this->findModel($id);
      $model->setScenario('update');

      $parentCategory = array();
      $parentCategory = Categories::find()->where(['parentCategory' => 0])->all();

      if (!empty($parentCategory)){
         $parentCategory =\yii\helpers\ArrayHelper::map($parentCategory, 'categoryId', 'name');
      }

      $oldImage = $model->image;
      $categoryProperty = json_decode($model->categoryProperty, true);

      if ($categoryProperty['itemCondition'] == 'enable'){
         $model->itemCondition = '1';
      } elseif ($categoryProperty['itemCondition'] == 'disable'){
         $model->itemCondition = '0';
      }
      
      if ($categoryProperty['exchangetoBuy'] == 'enable'){
         $model->exchangetoBuy = '1';
      } elseif ($categoryProperty['exchangetoBuy'] == 'disable'){
         $model->exchangetoBuy = '0';
      }

      if ($categoryProperty['buyNow'] == 'enable'){
         $model->buyNow = '1';
      } elseif ($categoryProperty['buyNow'] == 'disable'){
         $model->buyNow = '0';
      }
      
      if ($categoryProperty['myOffer'] == 'enable'){
         $model->myOffer = '1';
      } elseif ($categoryProperty['myOffer'] == 'disable'){
         $model->myOffer = '0';
      }

      $getattributes = Filter::find()->where(['status' => 1])->all();

      if(isset($_POST['Categories']))
      {
         $existcategory = Categories::find()->where(['<>','categoryId', $id])->andWhere(['name' => $_POST['Categories']['name'], 'parentCategory'=>$cat])->all();

         if(count($existcategory)==0)
         {
            $model->name=$_POST['Categories']['name']; 
        
            if(!isset($_POST['Categories']['parentCategory']) || $_POST['Categories']['parentCategory'] == ""){
               $model->parentCategory = 0;
            }
            if(isset($_POST['Categories']['subcategoryVisible']) || $_POST['Categories']['subcategoryVisible'] != ""){
               $model->subcategoryVisible = $_POST['Categories']['subcategoryVisible'];
            }
            $catImage = UploadedFile::getInstances($model,'image');

            if(!empty($catImage)) {
               $imageName = explode(".",$catImage[0]->name);
               $model->image = rand(000,9999).'-'.yii::$app->Myclass->productSlug($imageName[0]).'.'.$catImage[0]->extension;
            } else {
               $model->image = $oldImage;
            }

            $categoryProperty = array();
            if ($_POST['Categories']['itemCondition'] == 1){
               $categoryProperty['itemCondition'] = 'enable';
            } elseif ($_POST['Categories']['itemCondition'] == 0){
               $categoryProperty['itemCondition'] = 'disable';
            }
           
            if ($_POST['Categories']['exchangetoBuy'] == 1){
               $categoryProperty['exchangetoBuy'] = 'enable';
            }  elseif ($_POST['Categories']['exchangetoBuy'] == 0){
               $categoryProperty['exchangetoBuy'] = 'disable';
            }
          
            if ($_POST['Categories']['buyNow'] == 1){
               $categoryProperty['buyNow'] = 'enable';
            }  elseif ($_POST['Categories']['buyNow'] == 0){
               $categoryProperty['buyNow'] = 'disable';
            }
           
            if ($_POST['Categories']['myOffer'] == '1'){
               $categoryProperty['myOffer'] = 'enable';
            }  elseif ($_POST['Categories']['myOffer'] == 0){
               $categoryProperty['myOffer'] = 'disable';
            }
            
            /*   if ($_POST['Categories']['contactSeller'] == '1'){
               $categoryProperty['contactSeller'] = 'enable';
            }elseif ($_POST['Categories']['contactSeller'] == 0){
               $categoryProperty['contactSeller'] = 'disable';
            }*/
   
            $model->categoryProperty = json_encode($categoryProperty);
            $model->slug = yii::$app->Myclass->productSlug($model->name);
            $model->categoryAttributes = implode(',', $_POST['attributes']);
          
            if(!empty($catImage)) {
               $catImage[0]->saveAs('uploads/'.$model->image);
            }
            $model->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','SubCategory Updated'));
                 return $this->redirect(['subcategory','id'=>$cat]);
         }  else  {
            Yii::$app->session->setFlash('warning',Yii::t('app','SubCategory not exists'));
         }
      }
      
      $getParentdata = Categories::find()->where(['categoryId'=>$_GET['cat']])->one();
      $parentAttributes = explode(',', $getParentdata->categoryAttributes);
       
      return $this->render('add', [
         'model' => $model, 
         'parentCategory'=>$parentCategory,
         'attributes'=>$getattributes,
         'parentAttributes'=> $parentAttributes 
      ]);
   }


   public function actionUpdatesub_subcategory($id,$cat)
   {

      $model=$this->findModel($id);
      $model->setScenario('update');

      $parentCategory = array();
      $parentCategory = Categories::find()->where(['parentCategory' => 0])->all();

      if (!empty($parentCategory)){
         $parentCategory =\yii\helpers\ArrayHelper::map($parentCategory, 'categoryId', 'name');
      }

      $oldImage = $model->image;
      $categoryProperty = json_decode($model->categoryProperty, true);

      $getattributes = Filter::find()->where(['status' => 1])->all();

      if(isset($_POST['Categories']))
      {
         $existcategory = Categories::find()->where(['<>','categoryId', $id])->andWhere(['name' => $_POST['Categories']['name'], 'parentCategory'=>$cat])->all();

         if(count($existcategory)==0)
         {
            $model->name=$_POST['Categories']['name']; 
        
            if(!isset($_POST['Categories']['parentCategory']) || $_POST['Categories']['parentCategory'] == ""){
               $model->parentCategory = 0;
            }
            if(isset($_POST['Categories']['subcategoryVisible']) || $_POST['Categories']['subcategoryVisible'] != ""){
               $model->subcategoryVisible = $_POST['Categories']['subcategoryVisible'];
            }
            $catImage = UploadedFile::getInstances($model,'image');

            if(!empty($catImage)) {
               $imageName = explode(".",$catImage[0]->name);
               $model->image = rand(000,9999).'-'.yii::$app->Myclass->productSlug($imageName[0]).'.'.$catImage[0]->extension;
            } else {
               $model->image = $oldImage;
            }

            $categoryProperty = array();
            if ($_POST['Categories']['itemCondition'] == 1){
               $categoryProperty['itemCondition'] = 'enable';
            } elseif ($_POST['Categories']['itemCondition'] == 0){
               $categoryProperty['itemCondition'] = 'disable';
            }
           
            if ($_POST['Categories']['exchangetoBuy'] == 1){
               $categoryProperty['exchangetoBuy'] = 'enable';
            }  elseif ($_POST['Categories']['exchangetoBuy'] == 0){
               $categoryProperty['exchangetoBuy'] = 'disable';
            }
          
            if ($_POST['Categories']['buyNow'] == 1){
               $categoryProperty['buyNow'] = 'enable';
            }  elseif ($_POST['Categories']['buyNow'] == 0){
               $categoryProperty['buyNow'] = 'disable';
            }
           
            if ($_POST['Categories']['myOffer'] == '1'){
               $categoryProperty['myOffer'] = 'enable';
            }  elseif ($_POST['Categories']['myOffer'] == 0){
               $categoryProperty['myOffer'] = 'disable';
            }
            
            /*   if ($_POST['Categories']['contactSeller'] == '1'){
               $categoryProperty['contactSeller'] = 'enable';
            }elseif ($_POST['Categories']['contactSeller'] == 0){
               $categoryProperty['contactSeller'] = 'disable';
            }*/
   
            $model->categoryProperty = json_encode($categoryProperty);
            $model->slug = yii::$app->Myclass->productSlug($model->name);
            $model->categoryAttributes = implode(',', $_POST['attributes']);
          
            if(!empty($catImage)) {
               $catImage[0]->saveAs('uploads/'.$model->image);
            }
            $model->save(false);
            Yii::$app->session->setFlash('success',Yii::t('app','SubCategory Updated'));
                 return $this->redirect(['sub_subcategory','id'=>$cat]);
         }  else  {
            Yii::$app->session->setFlash('warning',Yii::t('app','SubCategory not exists'));
         }
      }
      
      $sub_getParentdata = Categories::find()->where(['categoryId'=>$_GET['cat']])->one();
      $getParentdata = Categories::find()->where(['categoryId'=>$sub_getParentdata->parentCategory])->one();

      $sub_parentAttributes = explode(',', $sub_getParentdata->categoryAttributes);
      $parentAttributes = explode(',', $getParentdata->categoryAttributes);

      $attributes = array_merge($parentAttributes, $sub_parentAttributes);
       
      return $this->render('sub_add', [
         'model' => $model, 
         'parentCategory'=>$parentCategory,
         'attributes'=>$getattributes,
         'parentAttributes'=> $attributes 
      ]);
   }


   public function actionRemove($id)
   {
       $model = $this->findModel($id);
      
       $products = Products::find()->where(['category'=>$id])
                   ->orWhere(['subCategory'=>$id])->orWhere(['sub_subCategory'=>$id])->all();

                  


       if(empty($products)) {
       $siteSettings =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
           $priorityCategories = $siteSettings->category_priority;
           if(!empty($priorityCategories)){
               $priorityCategories = Json::decode($priorityCategories, true);
               if(in_array($id, $priorityCategories)){
                   $restricedCategories = array();
                   foreach($priorityCategories as $priorityKey => $priorityCategory){
                       if($priorityCategory != $id)
                           $restricedCategories[] = $priorityCategory;
                   }
                   $filteredCategories = "";
                   if(!empty($restricedCategories))
                       $filteredCategories = Json::encode($restricedCategories);
                   $siteSettings->category_priority = $filteredCategories;
                   $siteSettings->save(false);
               }
           }
           $subcategories = Categories::find()->where(['parentCategory' => $id])->all();
           foreach($subcategories as $subcategory):
           $subcategory->delete();
           endforeach;
           $model->delete();
           $val = 0; 
       } else {
           $val = 1;
       }
       

    
           if($val == 1) {
               Yii::$app->session->setFlash('error',Yii::t('app', 'One or more products has been added to this category.You cannot delete this category'));
               return $this->redirect(Yii::$app->request->referrer);

           } else {
               Yii::$app->session->setFlash('success',Yii::t('app', 'Subcategory Deleted'));
               return $this->redirect(Yii::$app->request->referrer);

           }
      
   }

   public function actionRemovesub($id)
   {
       $model = $this->findModel($id);
      
       $products = Products::find()->where(['category'=>$id])
                   ->orWhere(['subCategory'=>$id])->all();

                  


       if(empty($products)) {
       $siteSettings =  Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
           $priorityCategories = $siteSettings->category_priority;
           if(!empty($priorityCategories)){
               $priorityCategories = Json::decode($priorityCategories, true);
               if(in_array($id, $priorityCategories)){
                   $restricedCategories = array();
                   foreach($priorityCategories as $priorityKey => $priorityCategory){
                       if($priorityCategory != $id)
                           $restricedCategories[] = $priorityCategory;
                   }
                   $filteredCategories = "";
                   if(!empty($restricedCategories))
                       $filteredCategories = Json::encode($restricedCategories);
                   $siteSettings->category_priority = $filteredCategories;
                   $siteSettings->save(false);
               }
           }
           $subcategories = Categories::find()->where(['parentCategory' => $id])->all();
           foreach($subcategories as $subcategory):
           $subcategory->delete();
           endforeach;
           $model->delete();
           $val = 0; 
       } else {
           $val = 1;
       }
       

    
           if($val == 1) {
               Yii::$app->session->setFlash('error',Yii::t('app', 'One or more products has been added to this category.You cannot delete this category'));
               return $this->redirect(Yii::$app->request->referrer);

           } else {
               Yii::$app->session->setFlash('success',Yii::t('app', 'Subcategory Deleted'));
               return $this->redirect(Yii::$app->request->referrer);

           }
      
   }

    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCancel()
    {
        return $this->redirect(['index']);
    }

    public function actionGetsublevel()
    {
        
        $parentLevel = $_POST['parentlevel'];
        $loadFilter = Filter::find()->select('value')->where(['id'=>$parentLevel])->one();
        $splitvals = explode(',', $loadFilter->value);
        $options = '<div class="form-group">';
        $options.= '<label class="control-label">Sub level values</label>';
        $options.= '<select name="sublevel" class="form-control">';
        $options.= '<option value="">Select sublevel<options>';
        foreach($splitvals as $subval)
        {
            $options.='<option value="'.$subval.'">'.$subval.'</options>';
        }
        $options.= '</select>';
        $options.= '</div>';
        return $options;
    }


    public function actionCancels($id)
    {
        return $this->redirect(['subcategory','id'=>$id]);
    }
}
