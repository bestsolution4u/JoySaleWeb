<?php

namespace backend\controllers;

use Yii;
use common\models\Filter;
use common\models\Productfilters;
use common\models\Filtervalues;
use backend\models\FilterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Categories;
use yii\helpers\Json;
class FilterController extends Controller
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

    public function beforeAction($action) {
            if (Yii::$app->user->isGuest) {            
                return $this->goHome();          
            }
            return true;
    }

    public function actionIndex($id)
    {
        $this->layout = "page";
        $query= Categories::find()->where(['categoryId'=>$id])->one();
        $array[] = json::decode($query->filters);
        $searchModel = new FilterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['in','id',$array[0]]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionManagement()
    {
        $this->layout = "page";
        $query= Filter::find()->all();
        $searchModel = new FilterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       return $this->render('management', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionAddfilter()
    {
        $model = new Filter();



        if ($model->load(Yii::$app->request->post())  && $model->validate()) 
        {

            //echo 'test'; exit;
            $formvalues = $_POST['Filter'];


            if($formvalues['type'] == 'dropdown'){
                $inputType = 'dropdown';
                $values = $formvalues['dynamic']['dropdown'];
                if(empty($values))
                {
                    Yii::$app->session->setFlash('error',Yii::t('app','Value cannot be empty'));
                    return $this->render('addfilter', [
                        'model' => $model,
                    ]);
                }
            }elseif($formvalues['type'] == 'range'){
                $inputType = 'text';
                $values = $formvalues['dynamic']['min'].';'.$formvalues['dynamic']['max'];
                if(empty($values))
                {
                    Yii::$app->session->setFlash('error',Yii::t('app','Value cannot be empty'));
                    return $this->render('addfilter', [
                        'model' => $model,
                    ]);
                }
            }elseif($formvalues['type'] == 'multilevel'){
                $inputType = 'multilevel';
                $values = json_encode($formvalues['dynamic']['child']);
                if(empty($values))
                {
                    Yii::$app->session->setFlash('error',Yii::t('app','Value cannot be empty'));
                    return $this->render('addfilter', [
                        'model' => $model,
                    ]);
                }
            }

            if($formvalues['type'] == 'multilevel')
            {
                $resultss = array();
              foreach($formvalues['dynamic']['parent'] as $key=>$val)
              {
                $resultss[$key]['parent'] = $val;
                $resultss[$key]['child'] = $formvalues['dynamic']['child'][$key];
                
              }
              $values = json_encode($resultss,JSON_UNESCAPED_UNICODE);  
          
            }
            //echo $values; exit;

            
            $model = new Filter();
            $model->name = $formvalues['name'];
            $model->type = $formvalues['type'];
            $model->inputtype = $inputType;
            $model->value = $values;
            $model->isRequired=1;
            $model->status=1;
            $model->save();
            //if($model->save())
              //  return $this->redirect('/admin/filter/management');
            $filterId = $model->id;

            if($formvalues['type'] == 'dropdown')
            {
                $valuesModel = new Filtervalues;
                $filterName = $formvalues['name'];
                $filterValues = explode(',', $values);

                //echo $model->id.'get fillters';
                //exit;
                $valuesModel->name = $formvalues['name'];
                $valuesModel->type = $formvalues['type'];
                $valuesModel->filter_id = $model->id;
                $valuesModel->inputtype = $inputType;
                $valuesModel->parentid = 0;
                $valuesModel->parentlevel = 0;
                $valuesModel->status = 0;
                $valuesModel->save(false);

                $insertId = $valuesModel->id;

                    foreach($filterValues as $eachVal)
                    {
                        $ChildModel = new Filtervalues;
                        $ChildModel->name = $eachVal;
                        $ChildModel->type = $formvalues['type'];
                        $ChildModel->filter_id = $filterId;
                        $ChildModel->inputtype = $inputType;
                        $ChildModel->parentid = $insertId;
                        $ChildModel->parentlevel = 1;
                        $ChildModel->status = 0;
                        $ChildModel->save(false);
                    }
            }elseif($formvalues['type'] == 'range')
            {
                $valuesModel = new Filtervalues;
                $filterName = $formvalues['name'];
                $filterValues = explode(';', $values);

                $valuesModel->name = $filterName;
                $valuesModel->type = $formvalues['type'];
                $valuesModel->filter_id = $model->id;
                $valuesModel->inputtype = $inputType;
                $valuesModel->parentid = 0;
                $valuesModel->parentlevel = 0;
                $valuesModel->status = 0;
                $valuesModel->save(false);

                $insertId = $valuesModel->id;

                    foreach($filterValues as $eachVal)
                    {
                        $ChildModel = new Filtervalues;
                        $ChildModel->name = $eachVal;
                        $ChildModel->type = $formvalues['type'];
                        $ChildModel->inputtype = $inputType;
                        $ChildModel->filter_id = $filterId;
                        $ChildModel->parentid = $insertId;
                        $ChildModel->parentlevel = 2;
                        $ChildModel->status = 0;
                        $ChildModel->save(false);
                    }
            }elseif($formvalues['type'] == 'multilevel')
            {
                $valuesModel = new Filtervalues;
                $filterName = $formvalues['name'];
                $filterParentchild = $formvalues['dynamic'];

                $valuesModel->name = $filterName;
                $valuesModel->type = $formvalues['type'];
                $valuesModel->inputtype = $inputType;
                $valuesModel->filter_id = $model->id;
                $valuesModel->parentid = 0;
                $valuesModel->parentlevel = 0;
                $valuesModel->status = 0;
                $valuesModel->save(false);

                $insertId = $valuesModel->id;
                foreach($filterParentchild['parent'] as $key => $eachVal)
                {
                    $addLevelModel = new Filtervalues;
                    $addLevelModel->name = $eachVal;
                    $addLevelModel->type = $formvalues['type'];
                    $addLevelModel->inputtype = $inputType;
                    $addLevelModel->filter_id = $filterId;
                    $addLevelModel->parentid = $insertId;
                    $addLevelModel->parentlevel = 3;
                    $addLevelModel->status = 0;
                    $addLevelModel->save(false);

                    $insertLevel = $addLevelModel->id;
                    $Getchild_level = explode(',', $filterParentchild['child'][$key]);

                    foreach( $Getchild_level as $childKey => $eachchildVal )
                    {
                        $childLevelModel = new Filtervalues;
                        $childLevelModel->name = $eachchildVal;
                        $childLevelModel->type = $formvalues['type'];
                        $childLevelModel->inputtype = $inputType;
                        $childLevelModel->filter_id = $filterId;
                        $childLevelModel->parentid = $insertLevel;
                        $childLevelModel->parentlevel = 4;
                        $childLevelModel->status = 0;
                        $childLevelModel->save(false);    
                    }
                }
            }
            //return $this->redirect(Yii::$app->urlManager->createUrl(['filter/management']));
            return $this->redirect(['view', 'id' => $model->id]);
        }        
            return $this->render('addfilter', [
                'model' => $model,
            ]); //return $this->redirect('/admin/filter/management');

    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    
    public function actionCreate($id)
    {
        $model = new Filter();
         
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $modal->subcategoryID=$_GET['id'];
            $model->isRequired=1;
            $model->status=1;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

   
    public function actionUpdate($id)
    {
        //echo 'stop here'; exit;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            //Save Filters.
            $formvalues = $_POST['Filter'];
            if($formvalues['type'] == 'dropdown'){
                $inputType = 'dropdown';
                $values = $formvalues['dynamic']['dropdown'];
                if(empty($values))
                {
                    Yii::$app->session->setFlash('info',Yii::t('app','Value cannot be empty'));
                    return $this->render('update_form', [
                        'model' => $model,
                    ]);
                }
            }elseif($formvalues['type'] == 'range'){
                $inputType = 'text';
                $values = $formvalues['dynamic']['min'].';'.$formvalues['dynamic']['max'];
                if(empty($values))
                {
                    Yii::$app->session->setFlash('info',Yii::t('app','Value cannot be empty'));
                    return $this->render('update_form', [
                        'model' => $model,
                    ]);
                }
            }elseif($formvalues['type'] == 'multilevel'){
                $inputType = 'multilevel';

                if(!isset($formvalues['dynamic']['child']) || empty($formvalues['dynamic']['child']))
                {
                    Yii::$app->session->setFlash('info',Yii::t('app','Parent and child values cannot be empty'));
                    return $this->render('update_form', [
                        'model' => $model,
                    ]);
                }
                $values = json_encode($formvalues['dynamic']['child']);
            }
            
            $model = $this->findModel($id);
            $model->name = $formvalues['name'];
            $model->type = $formvalues['type'];
            $model->inputtype = $inputType;
            $model->value = $values;
            $model->isRequired=1;
            $model->status=1;
            $model->save(false);

            $filterId = $model->id;
            /*
                $model->name = $formvalues['name'];
                $model->type = $formvalues['type'];
                $model->inputtype = $inputtypeField;
                $model->value = $values;
                $model->isRequired=1;
                $model->status=1;
                $model->save(false);
            */
            Filtervalues::deleteAll(['filter_id' => $id]);
            if($formvalues['type'] == 'dropdown')
            {
                $valuesModel = new Filtervalues;
                $filterName = $formvalues['name'];
                $filterValues = explode(',', $values);

                $valuesModel->name = $formvalues['name'];
                $valuesModel->type = $formvalues['type'];
                $valuesModel->filter_id = $filterId;
                $valuesModel->inputtype = $inputType;
                $valuesModel->parentid = 0;
                $valuesModel->parentlevel = 0;
                $valuesModel->status = 0;
                $valuesModel->save(false);

                $insertId = $valuesModel->id;

                    foreach($filterValues as $eachVal)
                    {
                        $ChildModel = new Filtervalues;
                        $ChildModel->name = $eachVal;
                        $ChildModel->type = $formvalues['type'];
                        $ChildModel->filter_id = $filterId;
                        $ChildModel->inputtype = $inputType;
                        $ChildModel->parentid = $insertId;
                        $ChildModel->parentlevel = 1;
                        $ChildModel->status = 0;
                        $ChildModel->save(false);
                    }
            }elseif($formvalues['type'] == 'range')
            {
                $valuesModel = new Filtervalues;
                $filterName = $formvalues['name'];
                $filterValues = explode(';', $values);

                $valuesModel->name = $filterName;
                $valuesModel->type = $formvalues['type'];
                $valuesModel->filter_id = $filterId;
                $valuesModel->inputtype = $inputType;
                $valuesModel->parentid = 0;
                $valuesModel->parentlevel = 0;
                $valuesModel->status = 0;
                $valuesModel->save(false);

                $insertId = $valuesModel->id;

                    foreach($filterValues as $eachVal)
                    {
                        $ChildModel = new Filtervalues;
                        $ChildModel->name = $eachVal;
                        $ChildModel->type = $formvalues['type'];
                        $ChildModel->inputtype = $inputType;
                        $ChildModel->filter_id = $filterId;
                        $ChildModel->parentid = $insertId;
                        $ChildModel->parentlevel = 2;
                        $ChildModel->status = 0;
                        $ChildModel->save(false);
                    }
            }elseif($formvalues['type'] == 'multilevel')
            {
                $valuesModel = new Filtervalues;
                $filterName = $formvalues['name'];
                $filterParentchild = $formvalues['dynamic'];

                $valuesModel->name = $filterName;
                $valuesModel->type = $formvalues['type'];
                $valuesModel->inputtype = $inputType;
                $valuesModel->filter_id = $filterId;
                $valuesModel->parentid = 0;
                $valuesModel->parentlevel = 0;
                $valuesModel->status = 0;
                $valuesModel->save(false);

                $insertId = $valuesModel->id;
                foreach($filterParentchild['parent'] as $key => $eachVal)
                {
                    $addLevelModel = new Filtervalues;
                    $addLevelModel->name = $eachVal;
                    $addLevelModel->type = $formvalues['type'];
                    $addLevelModel->inputtype = $inputType;
                    $addLevelModel->filter_id = $filterId;
                    $addLevelModel->parentid = $insertId;
                    $addLevelModel->parentlevel = 3;
                    $addLevelModel->status = 0;
                    $addLevelModel->save(false);

                    $insertLevel = $addLevelModel->id;
                    $Getchild_level = explode(',', $filterParentchild['child'][$key]);

                    foreach( $Getchild_level as $childKey => $eachchildVal )
                    {
                        $childLevelModel = new Filtervalues;
                        $childLevelModel->name = $eachchildVal;
                        $childLevelModel->type = $formvalues['type'];
                        $childLevelModel->inputtype = $inputType;
                        $childLevelModel->filter_id = $filterId;
                        $childLevelModel->parentid = $insertLevel;
                        $childLevelModel->parentlevel = 4;
                        $childLevelModel->status = 0;
                        $childLevelModel->save(false);    
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update_form', [
            'model' => $model,
        ]);
    }

 
    public function actionDelete($id)
    {
        //Delete filter by
        $getProductfilter = Productfilters::find()->where(['filter_id'=>$id])->count();
        if($getProductfilter > 0)
        {
            Yii::$app->session->setFlash('warning',Yii::t('app','Already filter assigned to products, so you cant delete.'));
        }else{
            Yii::$app->session->setFlash('warning',Yii::t('app','Successfully deleted'));
            $this->findModel($id)->delete();
        }
        return $this->redirect(['filter/management']);
    }
    public function actionAdd()
    {
        $query= Categories::find()->where(['categoryId'=>$_GET['id']])->one();
        $array[] = json::decode($query->filters);

        if(empty($query->filters)){
            $model = Filter::find()->all();
        }else{
            $model = Filter::find()->where(['not in','id',$array[0]])->all();
        }
        
        // $model = Filter::find()->all();
        // if(isset($_POST['save']))
        // {
        //     $catID = $_GET['id'];
        //     $filterID =$_POST['filter'];
        //     $catModal =Categories::findone($_GET['id']);
        //     $filter = json::decode($catModal['filters']);
            
        //     if(empty($catModal['filters'])){
        //     $catModal->filters=Json::encode(array_merge(array($_POST['filter']))); 
        //     }
        //     else
        //     {
        //     $catModal->filters=Json::encode(array_merge($filter,array($_POST['filter']))); 
        //     }
        //    $catModal->save();
        //    return  $this->redirect(['/filter/index/'.$_GET['id']]);
        // }
      
         if(isset($_POST['save']))
        {
            $catID = $_GET['id'];
            $filterID =$_POST['filter'];
            $catModal =Categories::findone($_GET['id']);        
            $filter = json::decode($catModal['filters']);
             
            $getFiter=Filter::find()->where(['id'=>$filterID])->one();
            $getidFilter=$getFiter->type;
          
            if(!empty($catModal['filters'])){
             $dataCount=0;   
             foreach($filter as $dataFilter)
             {   
                $getType=Filter::find()->where(['id'=>$dataFilter])->one();
                $dBFilter =$getType->type; 
        
                if($getidFilter==$dBFilter)
                {
                    $dataCount++;
                }
            }  
             if($dataCount==0)
             {
                $catModal->filters=Json::encode(array_merge($filter,array($_POST['filter']))); 
                $catModal->save();
                return  $this->redirect(['/filter/index/'.$_GET['id']]);
             }
             else
             {
                Yii::$app->session->setFlash('info',Yii::t('app','This type filter has already exists'));
             }
             
            }
            else
            {
                $catModal->filters=Json::encode(array_merge(array($_POST['filter']))); 
                $catModal->save();
                return  $this->redirect(['/filter/index/'.$_GET['id']]);
            }
       }
        return $this->render('add',['model'=>$model]);
    }
  
    protected function findModel($id)
    {
        if (($model = Filter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
