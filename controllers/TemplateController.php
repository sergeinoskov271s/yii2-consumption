<?php

namespace app\controllers;
use app\components\validators\TemplateValidator;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\d99b\Exception;
use yii\helpers\Console;
use Yii;
use yii\base;
use yii\db\Query;
use yii\helpers\VarDumper;
use app\models\Template;
use app\models\Outlay;
use app\models\View;
use app\models\TemplateForm;
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
use yii\validators\EmailValidator;
use yii\validators\NumberValidator;
use yii\validators\RegularExpressionValidator;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class BudgetController
 * @package app\controllers
 */
class TemplateController extends \yii\web\Controller
{
    /**
     * @param base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->layout = '@app/views/layouts/main-template.php';
        return parent::beforeAction($action);
    }

    /**
     * @return array|string|Response
     * @throws base\InvalidConfigException
     */
    public function actionIndex()
    {
        /** @var 'yii/base/model' $model */
        $model = new TemplateForm();
        $this->view->params['template_form_model'] =$model;
		
        $templates = Template::find()
            ->select('template.*')
			->orderBy('cat')
            ->all();
		if(count($templates) == 0){
			//throw new HttpException(404, "models not exists");
			return $this->render('empty-view',
					['message'=> Yii::t('app',
						'not cat of select order')
					]); 
		}
		
		foreach ($templates as $template){
			$model_view = new View();		
			$model_view->forwhat = $template['forwhat'];
			$model_view->towhom = $template['towhom'];
			$model_view->price = $template['price'];
			$model_view->cat = $template['cat'];
			$model_view->id = $template['id'];
			$model_view->monthly = $template['monthly'];
			$model_view->month = $template['month'];
			$model_view->group_summ_template = 	($template['monthly'] == 'yes')? $template['price'] : $template['price'] *  $template['month'];		
			$models_view[] = $model_view;
		}

		$provider = new ArrayDataProvider([
			'allModels' => $models_view,
			'pagination' => false,
			'totalCount' => 5,
			'sort' => [
				'attributes' => ['id', 'cat','towhom'], // Explicitly list sortable attributes
    ],
		]);	
		$caption = 'total modals of template';
		//$total_summ = Template::getTotal($provider->models, 'price');
		$command = Yii::$app->db->createCommand("select * from template");
		$templates = $command->queryAll();
       
		$total_summ = 0;
        if(count($templates) !== 0){
            foreach ($templates as $template){
				if($template['monthly'] == 'yes'){
					$total_summ	 += $template['price'] * 12;
				} elseif($template['monthly'] == 'no') {
					$total_summ	 += $template['price'] * $template['month'];
				}
            }
        }
		$total_summ	= $total_summ /12;
		

        return $this->render('index', [ 'dataProvider' => $provider,
						'caption'=>$caption,'total_summ'=>$total_summ]);
    }

	public function actionView($id)
	{
		$model = new TemplateForm();
        $this->view->params['template_form_model'] =$model;
		
		
        $template = Template::findOne($id);
        if($template == null) {
            throw new HttpException(404, "model  $id not exists");
        } 
		
		return $this->render('view',
            [
                'id'=>$id,
                'template'=>$template,
            ]);
	}
	
	public function actionUpdate($id)
	{
		$model = new TemplateForm();
        $this->view->params['template_form_model'] =$model;
		
		$model_template_form = new TemplateForm();
		$template = Template::findOne($id);
		if($template == null) {
            throw new HttpException(404, "model  $id not exists");
        }
		$model_template_form->towhom = $template->towhom;
		$model_template_form->forwhat = $template->forwhat;
		$model_template_form->cat = $template->cat;
		$model_template_form->price = $template->price;
		$model_template_form->monthly = $template->monthly;
		$model_template_form->month = $template->month;
		
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
            //$outlay->attributes = Yii::$app->request->post('OutlayForm');
			$template->setAttributes(Yii::$app->request->post('TemplateForm'),
                $safeOnly = false);
		
			$cat = $template->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
			
           
            $template->validate();
            if ($template->hasErrors()) {
                // validation fails

                $model_template_form->addErrors($template->errors);
				$model_template_form->towhom = $template->towhom;
				$model_template_form->forwhat = $template->forwhat;
				$model_template_form->cat = $template->cat;
				$model_template_form->price = $template->price;
				$model_template_form->monthly = $template->monthly;
				$model_template_form->month = $template->month;
                //Yii::info(VarDumper::dumpAsString($outlay->errors));
            } else {
                $template->save();
                Yii::$app->getSession()->setFlash('success', "template has been update");

                // file_put_contents($path_file,
                    // implode("\t", $array_properties)."\n", FILE_APPEND | LOCK_EX);
                return $this->redirect(['template/view/'.$template->id]);
            }
        }		
		return $this->render('update', [
            'model'=>$model_template_form,]);

	}
	public function actionDelete($id)
	{
		$model = new TemplateForm();
        $this->view->params['template_form_model'] =$model;
		
		$model_template_form = new TemplateForm();
		$template = Template::findOne($id);
		if($template == null) {
            throw new HttpException(404, "model  $id not exists");
        }
		$model_template_form->towhom = $template->towhom;
		$model_template_form->forwhat = $template->forwhat;
		$model_template_form->cat = $template->cat;
		$model_template_form->price = $template->price;
		$model_template_form->id = $template->id;
		$model_template_form->monthly = $template->monthly;
		
		if (Yii::$app->request->post())
        {
           $template->setAttributes(Yii::$app->request->post('TemplateForm'),
                $safeOnly = false);
		
			$cat = $template->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
			
           
            $template->validate();
            if ($template->hasErrors()) {
                // validation fails

                $model_template_form->addErrors($template->errors);
				$model_template_form->towhom = $template->towhom;
				$model_template_form->forwhat = $template->forwhat;
				$model_template_form->cat = $template->cat;
				$model_template_form->price = $template->price;
				$model_template_form->id = $template->id;
				$model_template_form->monthly = $template->monthly;
                //Yii::info(VarDumper::dumpAsString($outlay->errors));
            } else {
                $template->delete();
                Yii::$app->getSession()->setFlash('success', "template has been deleted");

                // file_put_contents($path_file,
                    // implode("\t", $array_properties)."\n", FILE_APPEND | LOCK_EX);
                return $this->redirect(['template/index']);
            } 
        }		
		return $this->render('delete', [
            'model'=>$model_template_form,]);

	}
	public function actionCreate()
	{
		//$model = new TemplateForm();
       //$this->view->params['template_form_model'] =$model;
		
		$model_template_form = new TemplateForm();
		$template = new Template();
		
		if (Yii::$app->request->post())
        {
            $template->setAttributes(Yii::$app->request->post('TemplateForm'),
                $safeOnly = false);
		
			$cat = $template->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
			
			
			
			
           
            $template->validate();
            if ($template->hasErrors()) {
				$model_template_form->towhom = $template->towhom;
				$model_template_form->forwhat = $template->forwhat;
				$model_template_form->cat = $template->cat;
				$model_template_form->price = $template->price;
				$model_template_form->monthly = $template->monthly;
                //Yii::info(VarDumper::dumpAsString($outlay->errors));
            } else {
                $template->save();
                Yii::$app->getSession()->setFlash('success', "template has been created");
                return $this->redirect(['template/view/'.$template->id]);
            }
        }
		return $this->render('create', [
            'model'=>$model_template_form,]);
	}
	
	public function actionViewgroup()
	{
		//$model = new TemplateForm();
        //$this->view->params['template_form_model'] =$model;
		
		
       // $template = Template::findOne($id);
		$caption = Yii::t('app','Template group cat ');
       
	    $command = Yii::$app->db->createCommand("SELECT ANY_VALUE(cat) as group_cat, sum(price) as group_summ FROM template group by cat order by group_summ DESC");
			
			$models_view =[]; 
			$templates = $command->queryAll();
			if(count($templates) !== 0){
				foreach ($templates as $template){
					$model_view = new View();
					$model_view->group_summ = (int) $template['group_summ'];
					$model_view->group_cat = $template['group_cat'];
					$model_view->total_summ = (int)$template['group_summ'] * 12;
					$models_view[] = $model_view; 	  
				} 
				$provider = new ArrayDataProvider([
							'allModels' => $models_view,
							'pagination' => false,
							'totalCount' => 5,
						]);	
						
				$total_summ = View::getTotal($provider->models, 'group_summ');
				$total_summ_year = $total_summ * 12;
				$title = Yii::t('app', 'group cat');
					return $this->render('view-group', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'total_summ_year'=>$total_summ_year]);
			} else {
				return $this->render('empty-view',
					['message'=> Yii::t('app',
						'not cat')
					]);
			}
	   
	    
	   
	   
	   
	}
	public function actionViewcat($id)
	{
		$caption = Yii::t('app','Template view cat');
		$command = Yii::$app->db->createCommand("SELECT cat,towhom,forwhat,price FROM template  where cat=:cat order by price DESC");
		$command->bindParam(':cat', $id);	
			$models_view =[]; 
			$templates = $command->queryAll();
			if(count($templates) !== 0){
				foreach ($templates as $template){
					$model_view = new View();
					$model_view->price = (int) $template['price'];
					$model_view->cat = $template['cat'];
					$model_view->towhom = $template['towhom'];
					$model_view->forwhat = $template['forwhat'];
					$models_view[] = $model_view; 	  
				} 
				$provider = new ArrayDataProvider([
							'allModels' => $models_view,
							'pagination' => false,
							'totalCount' => 5,
						]);	
						
				$total_summ = View::getTotal($provider->models, 'price');
				$total_summ_year = $total_summ * 12;
				$title = Yii::t('app', 'template cat');
					return $this->render('view-cat', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'total_summ_year'=>$total_summ_year]);
			} else {
				return $this->render('empty-view',
					['message'=> Yii::t('app',
						'not cat')
					]);
			}
		
		
		
	}
	
	
	
	

}	
	
	
   
	
   
	
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   


