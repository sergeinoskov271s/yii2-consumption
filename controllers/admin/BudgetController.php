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
use app\models\Budget;
use app\models\View;
use app\models\BudgetForm;
use app\models\BudgetFormCreate;
use app\models\BudgetFormEdit;
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

/**
 * Class OutlayController
 * @package app\controllers
 */
class BudgetController extends \yii\web\Controller
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
        $this->layout = '@app/views/layouts/main-admin.php';
        return parent::beforeAction($action);
    }
	public function getViewPath(): string
    {
        return '@app/views/admin/budget';
    }
    /**
     * @return array|string|Response
     * @throws base\InvalidConfigException
     */
    public function actionIndex()
    {
        /** @var 'yii/base/model' $model */
		//Yii::info('outlay index');
        //$model = new BudgetForm();
        //$this->view->params['budget_form_model'] =$model;
        $budgets = Budget::find()
            ->select('budget.*')
			->orderBy('dt1')
            ->all();
		if(count($budgets) == 0){
			throw new HttpException(404, "models not exists");
		}
		
		foreach ($budgets as $budget){
			$model_view = new View();		
			$model_view->forwhat = $budget['forwhat'];
			$model_view->towhom = $budget['towhom'];
			$model_view->dt1 = $budget['dt1'];
			$model_view->price = $budget['price'];
			$model_view->cat = $budget['cat'];
			$model_view->id = $budget['id'];							
			$models_view[] = $model_view;
		}

		$provider = new ArrayDataProvider([
			'allModels' => $models_view,
			'pagination' => false,
			'totalCount' => 5,
		]);	
		$caption = 'total modals of budget';
		$total_summ = Budget::getTotal($provider->models, 'price');
        /* if(Yii::$app->request->post())
        {
			$model->load(Yii::$app->request->post());
			$dt1 = $model->dt1;
			$cat= $model->cat;			
		
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'dt1',
                'value' => $dt1,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));

            $model ->validate();

            if ($model->hasErrors()){
                $this->view->params['budget_form_model'] = $model;
                return $this->render('index',['model' => $model,'order'=>$model->attributeLabels()]);
            }
			return $this->redirect('view'.'/'.$order.'/'.$date.'/'.$pole);
        } */
        return $this->render('index', [ 'dataProvider' => $provider,
						'caption'=>$caption,'total_summ'=>$total_summ]);
    }
	
	public function actionCreate()
	{
		$model = new BudgetForm();
        $this->view->params['budget_form_model'] =$model;
		
		$model_budget_form = new BudgetForm();
		$budget = new Budget();
		
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
            $budget->attributes = Yii::$app->request->post('BudgetForm');
            //Yii::info(VarDumper::dumpAsString($budget));
			$dt1 = $budget->dt1;
			$cat = $budget->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'dt1',
                'value' => $dt1,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
           
            $budget->validate();
            if ($budget->hasErrors()) {
                // validation fails

                $model_budget_form->addErrors($budget->errors);
				$model_budget_form->towhom = $budget->towhom;
				$model_budget_form->forwhat = $budget->forwhat;
				$model_budget_form->cat = $budget->cat;
				$model_budget_form->dt1 = $budget->dt1;
				$model_budget_form->price = $budget->price;
                Yii::info(VarDumper::dumpAsString($budget->errors));
            } else {
                $budget->save();
                Yii::$app->getSession()->setFlash('success', "budget has been created");

                // file_put_contents($path_file,
                    // implode("\t", $array_properties)."\n", FILE_APPEND | LOCK_EX);
                return $this->redirect(['admin/budget/view/'.$budget->id]);
            }
        }		
		return $this->render('create', [
            'model'=>$model_budget_form,]);
	}
	
	public function actionCreatecat($cat)
	{
		$model = new BudgetForm();
        $this->view->params['budget_form_model'] =$model;
		
		$model_budget_form = new BudgetForm();
		$model_budget_form->cat = $cat;
		
		$budget = new Budget();
		
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
            $budget->attributes = Yii::$app->request->post('BudgetForm');
            //Yii::info(VarDumper::dumpAsString($budget));
			$dt1 = $budget->dt1;
			$cat = $budget->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'dt1',
                'value' => $dt1,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
           
            $budget->validate();
            if ($budget->hasErrors()) {
                // validation fails

                $model_budget_form->addErrors($budget->errors);
				$model_budget_form->towhom = $budget->towhom;
				$model_budget_form->forwhat = $budget->forwhat;
				$model_budget_form->cat = $budget->cat;
				$model_budget_form->dt1 = $budget->dt1;
				$model_budget_form->price = $budget->price;
                Yii::info(VarDumper::dumpAsString($budget->errors));
            } else {
                $budget->save();
                Yii::$app->getSession()->setFlash('success', "budget has been created");

                // file_put_contents($path_file,
                    // implode("\t", $array_properties)."\n", FILE_APPEND | LOCK_EX);
                return $this->redirect(['admin/budget/view/'.$budget->id]);
            }
        }		
		return $this->render('createcat', [
            'model'=>$model_budget_form,]);
	}
	
	public function actionView($id)
	{
		$model = new BudgetForm();
        $this->view->params['budget_form_model'] =$model;
        $budget = Budget::findOne($id);
        if($budget == null) {
            throw new HttpException(404, "model  $id not exists");
        } 
		
		return $this->render('view',
            [
                'id'=>$id,
                'budget'=>$budget,
            ]);
	}
	
	public function actionUpdate($id)
	{
		$model = new BudgetForm();
        $this->view->params['budget_form_model'] =$model;
		
		$model_budget_form = new BudgetForm();
		$budget = Budget::findOne($id);
		if($budget == null) {
            throw new HttpException(404, "model  $id not exists");
        }
		$model_budget_form->towhom = $budget->towhom;
		$model_budget_form->forwhat = $budget->forwhat;
		$model_budget_form->cat = $budget->cat;
		$model_budget_form->dt1 = $budget->dt1;
		$model_budget_form->price = $budget->price;
		
		
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
            $budget->attributes = Yii::$app->request->post('BudgetForm');
            //Yii::info(VarDumper::dumpAsString($budget));
			$dt1 = $budget->dt1;
			$cat = $budget->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'dt1',
                'value' => $dt1,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
           
            $budget->validate();
            if ($budget->hasErrors()) {
                // validation fails

                $model_budget_form->addErrors($budget->errors);
				$model_budget_form->towhom = $budget->towhom;
				$model_budget_form->forwhat = $budget->forwhat;
				$model_budget_form->cat = $budget->cat;
				$model_budget_form->dt1 = $budget->dt1;
				$model_budget_form->price = $budget->price;
                //Yii::info(VarDumper::dumpAsString($budget->errors));
            } else {
                $budget->save();
                Yii::$app->getSession()->setFlash('success', "budget has been created");

                // file_put_contents($path_file,
                    // implode("\t", $array_properties)."\n", FILE_APPEND | LOCK_EX);
                return $this->redirect(['admin/budget/view/'.$budget->id]);
            }
        }		
		return $this->render('update', [
            'model'=>$model_budget_form,]);

	}
	public function actionDelete($id)
	{
		$model = new BudgetForm();
        $this->view->params['budget_form_model'] =$model;
		
		$model_budget_form = new BudgetForm();
		$budget = Budget::findOne($id);
		if($budget == null) {
            throw new HttpException(404, "model  $id not exists");
        }
		$model_budget_form->towhom = $budget->towhom;
		$model_budget_form->forwhat = $budget->forwhat;
		$model_budget_form->cat = $budget->cat;
		$model_budget_form->dt1 = $budget->dt1;
		$model_budget_form->price = $budget->price;
		
		
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
            $budget->attributes = Yii::$app->request->post('BudgetForm');
            //Yii::info(VarDumper::dumpAsString($budget));
			$dt1 = $budget->dt1;
			$cat = $budget->cat;
			$cookies = Yii::$app->response->cookies;
			$cookies->add(new \yii\web\Cookie([
                'name' => 'dt1',
                'value' => $dt1,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'cat',
                'value' => $cat,
            ]));
           
            $budget->delete();
            if ($budget->hasErrors()) {
                // validation fails

                $model_budget_form->addErrors($budget->errors);
				$model_budget_form->towhom = $budget->towhom;
				$model_budget_form->forwhat = $budget->forwhat;
				$model_budget_form->cat = $budget->cat;
				$model_budget_form->dt1 = $budget->dt1;
				$model_budget_form->price = $budget->price;
                //Yii::info(VarDumper::dumpAsString($budget->errors));
            } else {
               
                Yii::$app->getSession()->setFlash('success', "budget cat has been deleted");

                // file_put_contents($path_file,
                    // implode("\t", $array_properties)."\n", FILE_APPEND | LOCK_EX);
                return $this->redirect(['admin/budget/index']);
            }
        }		
		return $this->render('delete', [
            'model'=>$model_budget_form,]);

	}
	
   
	
   
	
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

}
