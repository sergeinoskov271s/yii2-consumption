<?php

namespace app\controllers\admin;
use app\components\validators\OutlayValidator;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\d99b\Exception;
use yii\helpers\Console;
use Yii;
use yii\base;
use yii\db\Query;
use yii\helpers\VarDumper;
use app\models\Outlay;
use app\models\View;
use app\models\OutlayForm;
use app\models\SearchAdminOutlayForm;
use app\models\SearchForm;
use yii\db\ActiveRecord;
use yii\console\ErrorHandler;
use yii\db\IntegrityException;
use yii\helpers\ArrayHelper;
//use app\common\components\CurlGetHelpers;
use yii\helpers\BaseUrl;
use yii\helpers\FileHelper;
use yii\validators\Validator;
use yii\web\HttpException;
use yii\db\Expression;
//use app\common\components\EmailValidator;
use yii\validators\NumberValidator;
use yii\validators\RegularExpressionValidator;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\helpers\Json;

/**
 * Class OutlayController
 * @package app\controllers
 */
class OutlayController extends \yii\web\Controller
{
    /*public function actions()
    {
        return [
            'error' => 'yii\web\ErrorAction',
            'view' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '',
            ],
        ];
    }*/
    /**
     * @param base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->layout = '@app/views/layouts/main-admin-outlay.php';
        return parent::beforeAction($action);
    }
	public function getViewPath(): string
    {
        return '@app/views/admin/outlay';
    }
    /**
     * @return array|string|Response
     * @throws base\InvalidConfigException
     */
    public function actionIndex()
    {
		$model = new SearchAdminOutlayForm();
        $this->view->params['search_admin_outlay_form_model'] =$model;
		
       /*  if(Yii::$app->request->post())
        {			
			$model->attributes = Yii::$app->request->post('SearchAdminOutlayForm');
			$pole = $model->pole;
			$order= $model->order;
					
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'navigation',
                'value' => $order,
            ]));
			
            $model ->validate();

            if ($model->hasErrors()){
                $this->view->params['search_form_model'] = $model;
                $this->view->params['selected_form_option'] = $model->order;
				
                return $this->render('index',['model' => $model,'order'=>$model->attributeLabels()]);
            } 
			$url =  Yii::$app->urlManager->createUrl(['admin/outlay/select',
						'order'=>$order
						'pole'=>$pole]);
			return $this->redirect('select/'.$order.'/'.$pole);
        } */
		return $this->render('index');
        //return $this->render('index', [ 'dataProvider' => $provider,
		//				'caption'=>$caption,'total_summ'=>$total_summ]);
    }
	
	
	public function actionCreate()
	{
		$model = new SearchAdminOutlayForm();
        $this->view->params['search_admin_outlay_form_model'] =$model;
        $outlay = new Outlay();
		$model_outlay_form =  new OutlayForm();
		
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
            $outlay->attributes = Yii::$app->request->post('OutlayForm');
            Yii::info(VarDumper::dumpAsString($outlay));
			$dt1 = $outlay->dt1;
			$cat = $outlay->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'dt1',
                'value' => $dt1,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
           
            $outlay->validate();
            if ($outlay->hasErrors()) {
                // validation fails
                $model_outlay_form->addErrors($outlay->errors);
				$model_outlay_form->towhom = $outlay->towhom;
				$model_outlay_form->forwhat = $outlay->forwhat;
				$model_outlay_form->cat = $outlay->cat;
				$model_outlay_form->dt1 = $outlay->dt1;
				$model_outlay_form->price = $outlay->price;
                Yii::info(VarDumper::dumpAsString($outlay->errors));
            } else {
                $outlay->save();
                Yii::$app->getSession()->setFlash('success', "outlay has been created");

                // file_put_contents($path_file,
                    // implode("\t", $array_properties)."\n", FILE_APPEND | LOCK_EX);
                return $this->redirect(['admin/outlay/view/'.$outlay->id]);
            }
        }		
		return $this->render('create', [
            'model'=>$model_outlay_form,]);
	}
	
	public function actionView($id)
	{
		$model = new SearchAdminOutlayForm();
        $this->view->params['search_admin_outlay_form_model'] =$model;
        $outlay = Outlay::findOne($id);
        if($outlay == null) {
            throw new HttpException(404, "model  $id not exists");
        } 
		
		return $this->render('view',
            [
                'id'=>$id,
                'outlay'=>$outlay,
            ]);
	}
	
	public function actionUpdate($id)
	{
		$model = new SearchAdminOutlayForm();
        $this->view->params['search_admin_outlay_form_model'] =$model;
        $outlay = Outlay::findOne($id);
		if($outlay == null) {
            throw new HttpException(404, "model  $id not exists");
        }
		
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
			$outlay->setAttributes(Yii::$app->request->post('Outlay'),
                $safeOnly = false);
			Yii::info(VarDumper::dumpAsString($outlay));
			$dt1 = $outlay->dt1;
			$cat = $outlay->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'dt1',
                'value' => $dt1,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
           
            $outlay->validate();
            if ($outlay->hasErrors()) {
                // validation fails
				$model_outlay_form->addErrors($outlay->errors);
				$model_outlay_form->towhom = $outlay->towhom;
				$model_outlay_form->forwhat = $outlay->forwhat;
				$model_outlay_form->cat = $outlay->cat;
				$model_outlay_form->dt1 = $outlay->dt1;
				$model_outlay_form->price = $outlay->price;
				$model_outlay_form->id = $outlay->id;
                Yii::info(VarDumper::dumpAsString($outlay->errors));
            } else {
                $outlay->save();
                Yii::$app->getSession()->setFlash('success', "outlay has been updated");
				
                return $this->redirect(['admin/outlay/view/'.$outlay->id]);
            }
        }		
		return $this->render('update', [
            'model'=>$outlay,]);

	}
	public function actionDelete($id)
	{
		$model = new SearchAdminOutlayForm();
        $this->view->params['search_admin_outlay_form_model'] =$model;
        $outlay = Outlay::findOne($id);
		if($outlay == null) {
            throw new HttpException(404, "model  $id not exists");
        }
		
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
			$outlay->setAttributes(Yii::$app->request->post('Outlay'),
                $safeOnly = false);
			//Yii::info(VarDumper::dumpAsString($outlay));
			$dt1 = $outlay->dt1;
			$cat = $outlay->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'dt1',
                'value' => $dt1,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
           
            $outlay->validate();
            if ($outlay->hasErrors()) {
                // validation fails
				$model_outlay_form->addErrors($outlay->errors);
				$model_outlay_form->towhom = $outlay->towhom;
				$model_outlay_form->forwhat = $outlay->forwhat;
				$model_outlay_form->cat = $outlay->cat;
				$model_outlay_form->dt1 = $outlay->dt1;
				$model_outlay_form->price = $outlay->price;
                Yii::info(VarDumper::dumpAsString($outlay->errors));
            } else {
                $outlay->delete();
                Yii::$app->getSession()->setFlash('success', "outlay has been deleted");
				
                return $this->redirect(['admin/outlay/index']);
            }
        }		
		return $this->render('delete', [
            'model'=>$outlay,]);		


	}
	
   
	
   
	
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

}
