<?php

namespace app\controllers;
use app\components\validators\BudgetValidator;
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
use app\models\Template;
use app\models\View;
use app\models\Outlay;
use app\models\BudgetForm;
use app\models\BudgetFormCreate;
use app\models\SearchForm;
use app\models\BudgetSearchForm;
use app\models\BudgetCreateForm;
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
use app\components\DateHelpers;

/**
 * Class BudgetController
 * @package app\controllers
 */
class BudgetController extends \yii\web\Controller
{
    /**
     * @param base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->layout = '@app/views/layouts/main-budget.php';
        return parent::beforeAction($action);
    }

    /**
     * @return array|string|Response
     * @throws base\InvalidConfigException
     */
    public function actionIndex()
    {
        /** @var 'yii/base/model' $model */

        $model = new BudgetSearchForm();
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
         
        if(Yii::$app->request->post())
        {
			if(Yii::$app->request->post('BudgetSearchForm')){
				$model->load(Yii::$app->request->post('BudgetSearchForm'));
				$model->attributes = Yii::$app->request->post('BudgetSearchForm');
				
				$order= $model->order;
				$date = $model->date;
				
				$cookies = Yii::$app->response->cookies;
				$cookies->add(new \yii\web\Cookie([
					'name' => 'navigation',
					'value' => $order,
				]));
				$cookies->add(new \yii\web\Cookie([
					'name' => 'budget_date_navigation',
					'value' => $date,
				]));
				$model ->validate();

				if ($model->hasErrors()){
					$this->view->params['budget_form_model'] = $model;
					$this->view->params['selected_form_option'] = $model->order;
					return $this->render('index',['model' => $model,'order'=>$model->attributeLabels()]);
				}
				return $this->redirect('view'.'/'.$order.'/'.$date);
			}
			if(Yii::$app->request->post('BudgetCreateForm')){
				$date = '2026-10-01';
				return $this->redirect('create/cat/'.$date);
			}
        }
		//$cats = [0=>'cat1',1=>'cat2'];
		
        return $this->render('index');
    }

	public function actionView($order,$date)
	{
		$model = new BudgetForm();
		$model->date = $date;
		$model->order = $order;
		//Yii::info(VarDumper::dumpAsString($model));
		$caption = Yii::t('app','budget {order} {date}',['order'=>$order,'date'=>$date]);
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		if($order == 'cat'){
			$command = Yii::$app->db->createCommand("call get_groupcats(:id)");
			$command->bindParam(':id', $date);

			$models_view =[]; 
			$budgets = $command->queryAll();
			
			$wordsArray = explode("-", $date);
			if(count($wordsArray) < 3){
				
				return $this->render('empty-view',
					['message'=> Yii::t('app',
						'not cat of select order')
					]);
				
			}
			
			$year = $wordsArray[0];
			$month = $wordsArray[1];
			// Start date: first day of January of the given year
			$start = new \DateTimeImmutable("$year-01-01");
			// End date: first day of January of the *next* year
			$end = new \DateTimeImmutable("$year-$month"."-01");
			// Interval of one month
			$interval = new \DateInterval('P1M');
			// Create a DatePeriod
			$period = new \DatePeriod($start, $interval, $end);
			$months = [];
			$datesrexep = "$year-00";

			foreach ($period as $d) {	   
				$datesrexep.= "|$year-".    sprintf('%02d',(int)$d->format('n')); // Numeric month without leading zeros		   
			}
			
			function getMonthsAfterYearDateTimeForRegexp($date_dt) {
				$wordsArray = explode("-", $date_dt);
				$year = $wordsArray[0];
				$month = $wordsArray[1];
				// Start date: first day of January of the given year
				$start = new \DateTimeImmutable("$year-$month-01");
				// End date: first day of January of the *next* year
				++$year;
				$end = new \DateTimeImmutable("$year-01-01");
				// Interval of one month
				$interval = new \DateInterval('P1M');
				// Create a DatePeriod
				$period = new \DatePeriod($start, $interval, $end);
				$months = [];
				//$datesrexep = '2025-00';

				foreach ($period as $date) {
					$months[] = 
						sprintf('%02d',(int)$date->format('n')) // Numeric month without leading zeros
					;
				}
				return $months;
			}
			$months_list_dt = getMonthsAfterYearDateTimeForRegexp($date);
			$month_count = count($months_list_dt);
			//Yii::info(VarDumper::dumpAsString($month_count));
			
			$command = Yii::$app->db->createCommand("call get_groupscatsregexp_outlay(:id)");
			$command->bindParam(':id', $datesrexep);
			$outlays = $command->queryAll();
			//Yii::info(VarDumper::dumpAsString($outlays));//array('group_cat'=>,'group_summ'=>)
			
			$command = Yii::$app->db->createCommand("SELECT ANY_VALUE(cat) as group_cat, sum(price) as group_summ_template FROM template group by cat");
			$templates = $command->queryAll();
			//Yii::info(VarDumper::dumpAsString($templates));
			
			function merge($idKey, $mergeKeys, ...$data) {
				$merged = [];
				foreach (array_merge(...$data) as $item) {
					$id = $item[$idKey];
					if (!array_key_exists($id, $merged)) {
						$merged[$id] = [
							'unique' => true,
							'item' => $item,
						];
					} else {
						if ($merged[$id]['unique']) {
							$merged[$id]['unique'] = false;
							foreach ($mergeKeys as $k) {
								//$merged[$id]['item'][$k] = [ $merged[$id]['item'][$k] ];
								if(isset( $item[$k])){
									$merged[$id]['item'][$k] = $item[$k];
									// $merged[$id]['item'][$k] = [ $merged[$id]['item'][$k] ];
									//print_r($item[$k]);
								}
							}
						}
					}

					foreach ($mergeKeys as $k) {
						if(isset($item[$k])){
							//$merged[$id]['item'][$k][] = $item[$k];
						}
					}	
				}
				return array_column($merged, 'item');
			}
			$merged = merge('group_cat', [ 'group_summ_template', 'group_summ_outlay'], $outlays, $templates);
			$merged = merge('group_cat', [ 'group_summ_template', 'group_summ_outlay', 'group_summ'],$merged,$budgets);
			//Yii::info(VarDumper::dumpAsString($merged));
			//Yii::info(VarDumper::dumpAsString($budgets));
			
			if(count($budgets) !== 0){
				foreach ($merged as $budget){
					$model_view = new View();
					$model_view->group_cat = $budget['group_cat'];
					$model_view->group_summ_template = $budget['group_summ_template'];
					$model_view->group_summ_outlay = isset($budget['group_summ_outlay']) ? $budget['group_summ_outlay'] : 0;
					$model_view->group_summ = isset($budget['group_summ']) ? (int)$budget['group_summ'] : 0;
					//$model_view->main_count = (int)$budget['main_count'];
					$model_view->group_avg = isset($budget['group_avg']) ? (int)$budget['group_avg'] :0;
					$budget['group_summ_outlay'] = isset($budget['group_summ_outlay'])? $budget['group_summ_outlay'] : 0;
					//$model_view->group_summ_balance = isset($budget['group_summ_template']) && isset($budget['group_summ_outlay'])? (int) round((($budget['group_summ_template'] * 12)-$budget['group_summ_outlay'])/$month_count) : 0;
					$model_view->group_summ_balance =  (int) round(($budget['group_summ_template'] * 12 -  $budget['group_summ_outlay'])/$month_count);
					//$model_view->group_summ_balance =  $model_view->group_summ_balance ==0 && !isset($budget['group_summ_outlay'])? $budget['group_summ_template'] : 0;
					$model_view->dt1 = $date;
					$models_view[] = $model_view; 	  
				} 
				$provider = new ArrayDataProvider([
							'allModels' => $models_view,
							'pagination' => false,
							'totalCount' => 5,
						]);	
						
				$total_summ = View::getTotal($provider->models, 'group_summ'); 	
				$title = Yii::t('app', 'cat');
					return $this->render('cats-grid', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'date'=>$date]);
			} else {
				

				return $this->render('empty-view',
					['message'=> Yii::t('app',
						'not cat of select order')
					]);
			}	
		} else if($order == 'towhom'){
			$command = Yii::$app->db->createCommand("call get_grouptowhoms(:id)");
			$command->bindParam(':id', $date);

			$result = [];
			$models_view =[]; 
			$budgets = $command->queryAll();
			if(count($budgets) !== 0){
				foreach ($budgets as $budget){
					$model_view = new View();
					$model_view->group_towhom = $budget['group_towhom'];
					$model_view->group_summ = (int)$budget['group_summ'];
					$model_view->main_count = (int)$budget['main_count'];
					$model_view->group_avg = (int)$budget['group_avg'];
					$models_view[] = $model_view; 	  
				} 
				$provider = new ArrayDataProvider([
							'allModels' => $models_view,
							'pagination' => false,
							'totalCount' => 5,
						]);	
						
				$total_summ = View::getTotal($provider->models, 'group_summ'); 	
				$title = Yii::t('app', 'towhom');
					return $this->render('towhoms-grid', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);
			
				
			}else {
				return $this->render('empty-view',
					['message'=> Yii::t('app',
						'not found of select order')
					]);
			}
		} else if($order == 'dt1'){
			if(preg_match('%^(?P<year>[12][09][012][0-9])(\-(?P<month>[01][0-9]))?(\-(?P<day>[0-3][0-9]))?%', $date, $matches)){
				if(isset($matches['year']) && isset($matches['month']) && isset($matches['day'])){
			
			
					$query = Budget::find()->where(['like', 'dt1', $date."%", false])->orderBy('cat');
					
						$data_provider = new ActiveDataProvider([
								'query' => $query,
								'pagination' => false,
								//'pagination' => [
								//	'pageSize' => 10,
								//],
								//'sort' => ['attributes' => [$order]]
							]);
					
								
						$total_summ = Budget::getTotal($data_provider->models, 'price');	
						$title = Yii::t('app', 'date {date}',['date'=>'date']);
							return $this->render('date-grid', [ 'dataProvider' => $data_provider,
							'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);
				} elseif (isset($matches['year'])){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date;
					
					$command = Yii::$app->db->createCommand('SELECT dt1, SUM(price) as total_summ FROM budget where dt1 regexp :id group by dt1 order by dt1',[':id' =>$date] );
				
					//$command->bindParam(':pole', $pole);
					$items = $command->queryAll();
					
					$models_view =[];
				
					if(count($items) !== 0){
						foreach ($items as $item){
							$model_view = new View();
							$model_view->date = $date;
							$model_view->dt1 = $item['dt1'];
							$model_view->total_summ = $item['total_summ'];
							$models_view[] = $model_view; 	  
						}
					}else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
					}
					
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						/* 
						'sort' => [
							'defaultOrder' => ['date' => SORT_ASC],
							'attributes' => ['date', ],
						], */
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'total_summ');
					
					$date_year = $date;
					$month_outlay_regexp ='';
					$month_budget_regexp ='';
					
					$outlay = Yii::$app->db->createCommand('select count(distinct dt1) as count_outlay_data_year from outlay where dt1 like concat(:id,"%")', [':id' =>$date_year])->queryOne();
					$count_outlay_data_year = $outlay['count_outlay_data_year'];

					$outlays = Yii::$app->db->createCommand('select distinct substring(dt1,1,7) as month_outlay_regexp from outlay where dt1 like concat(:id,"%")', [':id' =>$date_year])->queryAll();
					foreach ($outlays as $outlay){
						$month_outlay_regexp .= '|'.$outlay['month_outlay_regexp'];
						
					}
					$month_outlay_regexp = mb_substr( $month_outlay_regexp,1);
					
					$command = Yii::$app->db->createCommand('select sum(price) as total_outlay from outlay where dt1 regexp :id',[':id' =>$month_outlay_regexp]);
					$outlay =  $command->queryOne();
					$total_outlay = $outlay['total_outlay'];
		
					$budgets = Yii::$app->db->createCommand('select distinct substring(dt1,1,7) as month_budget_regexp from budget where dt1 like concat(:id,"%")', [':id' =>$date_year])->queryAll();
					foreach ($budgets as $budget){
						$month_budget_regexp .= '|'.$budget['month_budget_regexp'];	
					}
					
					$budget = Yii::$app->db->createCommand('select count(distinct dt1) as count_budget_data_year from budget where dt1 like concat(:id,"%")', [':id' =>$date_year])->queryOne();
					$count_budget_data_year = $budget['count_budget_data_year'];

					$command = Yii::$app->db->createCommand('select sum(price) as total_budget from budget where dt1 regexp :id',[':id' =>$month_budget_regexp]);
					$budget =  $command->queryOne();
					$total_budget = $budget['total_budget'];

					$total_summ = $total_budget + $total_outlay;

					$avg_summ = $total_summ/($count_outlay_data_year +$count_budget_data_year);

								
					
					
					return $this->render('date-year-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'count_outlay_data_year'=>$count_outlay_data_year,'total_outlay'=>$total_outlay,'total_budget'=>$total_budget,
						'avg_summ'=>$avg_summ,'count_budget_data_year'=>$count_budget_data_year]);	
	
				}
				return $this->render('empty-view',
								['message'=> Yii::t('app',
									'date not matches')
							]);	
			}
		}
			else {
			
			return $this->render('empty-view',
					['message'=> Yii::t('app',
						'not order')
					]);
		}
    }

	public function actionViewcat($date,$cat){
		$model = new BudgetForm();
		$model->cat = $cat;
		$model->dt1 = $date;
		//Yii::info(VarDumper::dumpAsString($model));
		$caption = Yii::t('app','search {cat} {date}',['cat'=>$cat,'date'=>$date]);
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		//Yii::$app->view->title = 'Budget';
		
		$command = Yii::$app->db->createCommand("call get_groupdatecat(:date,:cat)");
			$command->bindParam(':date', $date);
			$command->bindParam(':cat', $cat);

			$models_view =[]; 
			$budgets = $command->queryAll();
			if(count($budgets) !== 0){
				foreach ($budgets as $budget){
					$model_view = new View();
					//$model_view->date = $date;
					//$model_view->group_forwhat = $budget['group_forwhat'];
					$model_view->group_summ = (int) $budget['group_summ'];
					$model_view->cat = $cat;
					$model_view->date = $date;
					//$model_view->main_count = (int)$budget['main_count'];
					//$model_view->group_avg = (int)$budget['group_avg'];
					$model_view->group_year = $budget['group_year'];
					$group_month = $budget['group_month'];
					$formatted_string = sprintf('%02d', $group_month);
					$model_view->group_month = $formatted_string;
					$models_view[] = $model_view; 	  
				} 
				$provider = new ArrayDataProvider([
							'allModels' => $models_view,
							'pagination' => false,
							'totalCount' => 5,
						]);	
						
				$total_summ = View::getTotal($provider->models, 'group_summ'); 	
				$title = Yii::t('app', 'forwhat');
					return $this->render('cat-view-grid', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'date'=>$date]);
			} else {
				/* return $this->render('empty-view',
					['message'=> Yii::t('app',
						'not cat')
					]); */
				$wordsArray = explode("-", $date);
				$date_year = $wordsArray[0];
				$date_month = $wordsArray[1];
				
				$model_view = new View();
					
					$model_view->group_summ = 0;
					$model_view->cat = $cat;
					$model_view->date = $date;
					
					$model_view->group_year = $date_year;
					//$group_month = $budget['group_month'];
					//$formatted_string = sprintf('%02d', $group_month);
					$model_view->group_month = $date_month;
					$models_view[] = $model_view; 	  	
					
					
					
				$provider = new ArrayDataProvider([
							'allModels' => $models_view,
							'pagination' => false,
							'totalCount' => 5,
						]);	
						
				$total_summ = View::getTotal($provider->models, 'group_summ'); 	
				$title = Yii::t('app', 'forwhat');
					return $this->render('cat-view-non-existent', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'date'=>$date]);	
					
					
			}
		
	}
	
	public function actionEditcat($date,$cat){
		$model = new BudgetForm();
		$model->cat = $cat;
		$model->dt1 = $date;
		$title = Yii::t('app', 'cat');
		//Yii::info(VarDumper::dumpAsString($model));
		$caption = Yii::t('app','edit {cat} {date}',['cat'=>$cat,'date'=>$date]);
		$items = [
                1=> 'product1',
                2 =>'product2',
                
            ];
		$this->view->params['items'] = $items;
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		
		$budget = new Budget();
		$budget->budgets = array();

		//$query = Budget::find()->where(['like', 'dt1', $date."%", false])->orderBy('dt1');
		$command = Yii::$app->db->createCommand("select * from budget where dt1 like concat(:id,'%') and cat=:cat");
		$command->bindParam(':id', $date);
		$command->bindParam(':cat', $cat);
		$budgets = $command->queryAll();
					if(count($budgets) !== 0){
						foreach ($budgets as $bd){
							$keys = array("id","dt1", "towhom","forwhat","price","cat");
							$values = array_values($bd);
							$associative_array = array_combine($keys, $values);
							//$associative_array['dt1'] = $date;
							//$associative_array['cat'] = $cat;
							$budget->budgets[] = $associative_array;
						}
					}	

		if (Yii::$app->request->post())
        {
            $budget_post = Yii::$app->request->post('Budget');
			//if( Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) ){
			//	$model->schedule = \yii\helpers\Json::encode($model->schedule);
			//	$model->save();
			//}
			//$post = serialize( htmlspecialchars(trim(Yii::$app->request->post['Budget'])).' '.htmlspecialchars(trim(Yii::$app->request->post['Budget'])) );
            //Yii::info(VarDumper::dumpAsString($budget_post['budgets']));
			foreach($budget_post['budgets'] as $v){
				//Yii::info(VarDumper::dumpAsString($v));
				//Yii::info(VarDumper::dumpAsString($v));
				if($v['id'] != ''){
					$budget = Budget::findOne($v['id']);
					$budget->towhom = $v['towhom'];
					$budget->forwhat = $v['forwhat'];
					$budget->price = (int)str_replace(' ', '', $v['price']);
					$budget->dt1 = $v['dt1'];
					if ($budget->validate()) {
						$budget->update();  
					} else {
						$errors = $budget->errors;
						foreach($errors as $key=>$value){					
							Yii::$app->session->setFlash('error',"$key => $value[0]");					
						   // echo "$key => $value[0]\n";
						}						 
					}
				}
			}
			
			$url = Yii::$app->urlManager->createUrl(['budget/viewcat',
				'cat'=>$cat,
				'date'=>$date,
			]);
              return $this->redirect(['budget/index']);
			//return $this->redirect($url ); 
        }		

		return $this->render('cat-edit', [ 
			'caption'=>$caption,'title'=>$title,'model'=>$budget]);		
	
	}
	
	public function actionAddcat($date,$cat){
		$model = new BudgetForm();
		$model->cat = $cat;
		$model->dt1 = $date;
		$title = Yii::t('app', 'cat');
		//Yii::info(VarDumper::dumpAsString($model));
		$caption = Yii::t('app','edit {cat} {date}',['cat'=>$cat,'date'=>$date]);
		$items = [
                1=> 'product1',
                2 =>'product2',   
            ];
		$this->view->params['items'] = $items;
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		
		$budget = new Budget();
		$budget->budgets = array();

		//$query = Budget::find()->where(['like', 'dt1', $date."%", false])->orderBy('dt1');
		$command = Yii::$app->db->createCommand("select * from template where cat=:cat");
		//$command->bindParam(':id', $date);
		$command->bindParam(':cat', $cat);
		$budgets = $command->queryAll();
					if(count($budgets) !== 0){
						foreach ($budgets as $bd){
							//$keys = array("towhom","forwhat","price","cat");
							//$values = array_values($bd);
							$associative_array['towhom'] = $bd['towhom'];
							$associative_array['forwhat'] = $bd['forwhat'];
							$associative_array['price'] = $bd['price'];
							$associative_array['dt1'] = $date;
							$associative_array['cat'] = $cat;
							$budget->budgets[] = $associative_array;
						}
					}	
		
		if (Yii::$app->request->post())
        {
            $budget_post = Yii::$app->request->post('Budget');
			//if( Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) ){
			//	$model->schedule = \yii\helpers\Json::encode($model->schedule);
			//	$model->save();
			//}
			//$post = serialize( htmlspecialchars(trim(Yii::$app->request->post['Budget'])).' '.htmlspecialchars(trim(Yii::$app->request->post['Budget'])) );
			//$json_output = json_encode($data, JSON_PRETTY_PRINT);
            //Yii::info(VarDumper::dumpAsString($budget_post['budgets']));
			foreach($budget_post['budgets'] as $v){
					$budget = new Budget();
					$budget->towhom = $v['towhom'];
					$budget->forwhat = $v['forwhat'];
					$budget->price = (int)str_replace(' ', '', $v['price']);
					$budget->dt1 =$date;
					$budget->cat =$cat;
					if ($budget->validate()) {
						$budget->save();  
					} else {
						$errors = $budget->errors;
						foreach($errors as $key=>$value){					
							Yii::$app->session->setFlash('error',"$key => $value[0]");					
						}						 
					}
			}
			
			$url = Yii::$app->urlManager->createUrl(['budget/viewcat',
				'cat'=>$cat,
				'date'=>$date,
			]);
              return $this->redirect(['budget/index']);
			//return $this->redirect($url ); 
        }		

		
		return $this->render('cat-create-non-existent',[
			'caption'=>$caption,'title'=>$title,'model'=>$budget,'total_summ_for_year'=>0,'avg_year'=>0,'month_count'=>0,'total_for_month'=>0,'cat'=>$cat,'date'=>$date,'total_summ_budget'=>0]);
					
		
	}
		
	public function actionCreatecat($date,$cat){
		$model = new BudgetForm();
		//$caption = Yii::t('app','outlay');
        $this->view->params['budget_form_model'] =$model;
		
		$model = new BudgetForm();
		$model->cat = $cat;
		$model->dt1 = $date;
		$year = substr($date, 0, 4);
		$year = intval(substr($date, 0, 4));
		$year_budget = $year +1;
		$date_budget = (string)$year_budget.substr($date, 4, 6);
		$title = Yii::t('app', 'create cat');
		//Yii::info(VarDumper::dumpAsString($model));
		$caption = Yii::t('app','outlay {cat} {date}',['cat'=>$cat,'date'=>$date]);
		$items = [
                1=> 'product1',
                2 =>'product2', 
            ];
		$this->view->params['items'] = $items;
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		
		$budget = new Budget();
		$budget->budgets = array();

		$command = Yii::$app->db->createCommand("select forwhat, towhom, price from template where cat=:cat");
		$command->bindParam(':cat', $cat);
		$templates =  $command->queryAll();
					if(count($templates) !== 0){
						foreach ($templates as $template){
							$keys = array("forwhat", "towhom", "price");
							$values = array_values($template);
							$associative_array = array_combine($keys, $values);
							$associative_array['dt1'] = $date_budget;
							$associative_array['cat'] = $cat;
							$budget->budgets[] = $associative_array;
						}
					}

		$budget->dt1 = $date;
		//Yii::info(VarDumper::dumpAsString($budget));
		$total_summ_budget = Budget::getTotal($budget['budgets'],'price');
		//Yii::info(VarDumper::dumpAsString($total_summ_budget));
		$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count,cat,sum(price) as total_summ_for_year from outlay  where dt1 like concat(:id,'%') and cat=:cat");
					$command->bindParam(':id', $year);
					$command->bindParam(':cat', $cat);
					$outlays = $command->queryAll();
					$month_count = 0;
					$total_summ_for_year = 0;
					$avg_year = 0;
					if(count($outlays) != 0){
						$month_count = $outlays[0]['month_count'];
						$total_summ_for_year = $outlays[0]['total_summ_for_year'];
						$avg_year = intval($total_summ_for_year/$month_count);	
					}
					//$month_count = $outlays[0]['month_count'];
					//$total_summ_for_year = $outlays[0]['total_summ_for_year'];
					
		$total = 0;
			
				$command = Yii::$app->db->createCommand("call get_groupforwhatstowhomsfromcat_outlay(:id,:cat)");
				$command->bindParam(':id', $date);
				$command->bindParam(':cat', $cat);
				$outlays = $command->queryAll();
				$models_view =[];				
		
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							//$model_view->group_cat = $outlay['group_cat'];
							$model_view->forwhat = $outlay['group_forwhat'];
							$model_view->towhom = $outlay['group_towhom'];
							$model_view->price = $outlay['group_summ'];
							//$model_view->date = $date;
							$models_view[] = $model_view; 	  
						}
					} else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								'not outlays')
							]);
					}
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);
		
					$total_summ_for_month = 0;
					$total_summ_for_month = Outlay::getTotal($provider->models, 'price');
					
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
            $budget_post = Yii::$app->request->post('Budget');
			
			foreach($budget_post['budgets'] as $v){
				//Yii::info(VarDumper::dumpAsString($v));
					$budget = new Budget();
					$budget->towhom = $v['towhom'];
					$budget->forwhat = $v['forwhat'];
					$budget->price = (int)str_replace(' ','',$v['price']);
					$budget->dt1 = $v['dt1'];
					$budget->cat = $cat;
					if ($budget->validate()) {
						$budget->save();  
					} else {
						$errors = $budget->errors;
						foreach($errors as $key=>$value){					
							Yii::$app->session->setFlash('error',"$key => $value[0]");					
						   // echo "$key => $value[0]\n";
						}						 
					}
			}
			
			$url = Yii::$app->urlManager->createUrl(['budget/viewcat',
				'cat'=>$cat,
				'date'=>$date,
			]);
              return $this->redirect(['budget/index']);
			//return $this->redirect($url );  
        }
		//$template_total_summ = Budget::getTotal($provider->models, 'group_summ');
		
		return $this->render('cat-create',['dataProvider' => $provider,
			'caption'=>$caption,'title'=>$title,'model'=>$budget,'total_summ_for_year'=>$total_summ_for_year,'avg_year'=>$avg_year,'month_count'=>$month_count,'total_for_month'=>$total_summ_for_month,'cat'=>$cat,'date'=>$date,'total_summ_budget'=>$total_summ_budget]);
		
	}
	
	public function actionDeletefromdate($date)
	{
		$model = new BudgetForm();
		$model->cat = '';
		$model->dt1 = $date;
		$title = Yii::t('app', 'cat');
		
		$caption = Yii::t('app','edit  {date}',['date'=>$date]);
		$items = [
                1=> 'product1',
                2 =>'product2',  
            ];
		$this->view->params['items'] = $items;
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		
		$message = 'not validate of date';
		
		
		if(preg_match('%^(?P<year>[12][09][012][0-9])(\-(?P<month>[01][0-9]))?(\-(?P<day>[0-3][0-9]))?%', $date, $matches))
		{
			if(isset($matches['year']) && isset($matches['month']) && isset($matches['day']))
			{
				$message = 'validate of data is ok';
				
				$title = Yii::t('app', 'budget delete');
				//Yii::info(VarDumper::dumpAsString($model));
				$caption = Yii::t('app','delete  {date}',['date'=>$date]);
				$items = [
						1=> 'product1',
						2 =>'product2',	
					];
				
				$budget = new BudgetForm();
				$budget->budgets = array();

				$command = Yii::$app->db->createCommand("select * from budget where dt1 like concat(:id,'%')");
				$command->bindParam(':id', $date);
				$budgets = $command->queryAll();
					if(count($budgets) !== 0){
						foreach ($budgets as $bd){
							$keys = array("id","dt1", "towhom","forwhat","price","cat");
							$values = array_values($bd);
							$associative_array = array_combine($keys, $values);
							$budget->budgets[] = $associative_array;
						}
					} else {
						$message = 'not data';
						return $this->render('empty-view',['message'=> Yii::t('app',
							$message)
						]);
					}
				//Yii::info(VarDumper::dumpAsString($budget));	
					
				if (Yii::$app->request->post())
				{
					$budget_post = Yii::$app->request->post('BudgetForm');
					//Yii::info(VarDumper::dumpAsString($budget_post));
					if(is_array($budget_post['budgets'])){
						foreach($budget_post['budgets'] as $v){
							if($v['id'] != ''){
								$budget = Budget::findOne($v['id']);
								/* $budget->towhom = $v['towhom'];
								$budget->forwhat = $v['forwhat'];
								$budget->cat = $v['cat'];
								$budget->price = (int)str_replace(' ', '', $v['price']);
								$budget->dt1 = $v['dt1']; */
								if ($budget->validate()) {
									$budget->delete();  
								} else {
									$errors = $budget->errors;
									foreach($errors as $key=>$value){					
										Yii::$app->session->setFlash('error',"$key => $value[0]");					
									   // echo "$key => $value[0]\n";
									}						 
								}
							}
						}
					} else {
						Yii::$app->session->setFlash('error',"not found for delete");
						return $this->redirect(['budget/index']);
					}
					
					$url = Yii::$app->urlManager->createUrl(['budget/view/dt1/'.$matches['year'],
						
					]);
             // return $this->redirect(['budget/index']);
					return $this->redirect($url ); 
					
				}
				
				return $this->render('date-delete', [
				'caption'=>$caption,'title'=>$title,'model'=>$budget]);
				
			} else {
				
				return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	
			}	
			
		} 
			
		return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	
		
	}
	
	public function actionUpdatefromdate($date)
	{
		$model = new BudgetForm();
		$model->cat = '';
		$model->dt1 = $date;
		$title = Yii::t('app', 'cat');
		
		$caption = Yii::t('app','edit  {date}',['date'=>$date]);
		$items = [
                1=> 'product1',
                2 =>'product2',
            ];
		$this->view->params['items'] = $items;
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		
		$message = 'not validate of date';
	
		if(preg_match('%^(?P<year>[12][09][012][0-9])(\-(?P<month>[01][0-9]))?(\-(?P<day>[0-3][0-9]))?%', $date, $matches))
		{
			if(isset($matches['year']) && isset($matches['month']) && isset($matches['day'])){
				$message = 'validate of data is ok';
					
				//$model = new Budget();
				//$model->dt1 = $date;
				$title = Yii::t('app', 'budget delete');
				//Yii::info(VarDumper::dumpAsString($model));
				$caption = Yii::t('app','delete  {date}',['date'=>$date]);
				$items = [
						1=> 'product1',
						2 =>'product2',	
					];
				
				$budget = new BudgetForm();
				$budget->dates[] = $date;
				$budget->budgets = array();

				$command = Yii::$app->db->createCommand("select * from budget where dt1 like concat(:id,'%') order by cat");
				$command->bindParam(':id', $date);
				$budgets = $command->queryAll();
					if(count($budgets) !== 0){
						foreach ($budgets as $bd){
							$keys = array("id","dt1", "towhom","forwhat","price","cat");
							$values = array_values($bd);
							$associative_array = array_combine($keys, $values);
							$associative_array['template'] = $bd['price'];
							$budget->budgets[] = $associative_array;
						}
					} else {
						$message = 'not data';
						return $this->render('empty-view',['message'=> Yii::t('app',
							$message)
						]);
					}

				if (Yii::$app->request->post())
				{
					$budget_post = Yii::$app->request->post('BudgetForm');
					//if( Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) ){
					//	$model->schedule = \yii\helpers\Json::encode($model->schedule);
					//	$model->save();
					//}
					//$post = serialize( htmlspecialchars(trim(Yii::$app->request->post['Budget'])).' '.htmlspecialchars(trim(Yii::$app->request->post['Budget'])) );
					//$json_output = json_encode($data, JSON_PRETTY_PRINT);
					//Yii::info(VarDumper::dumpAsString($budget_post));
					$date = !isset($budget_post['dates']) ? 0 : $budget_post['dates'][0];
						//$date = $budget_post['dates'][0];
					if(preg_match('%^(?P<year>[12][09][012][0-9])(\-(?P<month>[01][0-9]))?(\-(?P<day>[0-3][0-9]))?%', $date, $matches))
					{
						if(isset($matches['year']) && isset($matches['month']) && isset($matches['day']))
						{
							$message = 'validate of data is ok';
							
								$budgets = Budget::find()
								->where(['like','dt1',$date."%", false])
								->all();
								foreach ($budgets as $budget){
									//echo $budget->id." delete "."\n";
									$budget->delete();
								}
							
							foreach($budget_post['budgets'] as $v){
								$budget = new Budget();
								$budget->towhom = $v['towhom'];
								$budget->forwhat = $v['forwhat'];
								$budget->cat = $v['cat'];
								$budget->price = (int)str_replace(' ','',$v['price']);
								$budget->dt1 = $date;
								if ($budget->validate()) {
									$budget->save();  
								} else {
									$errors = $budget->errors;
									foreach($errors as $key=>$value){					
										Yii::$app->session->setFlash('error',"$key => $value[0]");					
									}						 
								}
							}
							return $this->redirect(['budget/index']);
								
						} else {
							$message = 'validate of data is not valid';
						return $this->render('empty-view',['message'=> Yii::t('app',
								$message)
							]);	
						}
					} else {
						$message = 'validate of data is not valid';
						return $this->render('empty-view',['message'=> Yii::t('app',
								$message)
							]);	
					}
				}
				
				return $this->render('date-update', [
				'caption'=>$caption,'title'=>$title,'model'=>$budget,]);
				
			} else {
				
				return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	
			}	
			
		} 
			
		return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	
		
	}
	
	public function actionDeletecat($date,$cat)
	{
		$title = Yii::t('app', 'cat');
		//Yii::info(VarDumper::dumpAsString($model));
		$caption = Yii::t('app','edit {cat} {date}',['cat'=>$cat,'date'=>$date]);
		$model = new BudgetSearchForm();
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		
		$budget = new BudgetForm();
		$budget->budgets = array();

		//$query = Budget::find()->where(['like', 'dt1', $date."%", false])->orderBy('dt1');
		$command = Yii::$app->db->createCommand("select * from budget where dt1 like concat(:id,'%') and cat=:cat");
		$command->bindParam(':id', $date);
		$command->bindParam(':cat', $cat);
		$budgets = $command->queryAll();
					if(count($budgets) !== 0){
						foreach ($budgets as $bd){
							$keys = array("id","dt1", "towhom","forwhat","price","cat");
							$values = array_values($bd);
							$associative_array = array_combine($keys, $values);
							$budget->budgets[] = $associative_array;
						}
					}	
		
		if (Yii::$app->request->post())
        {
            $budget_post = Yii::$app->request->post('BudgetForm');
			//Yii::info(VarDumper::dumpAsString($budget_post));
			if(isset($budget_post['budgets'])){
				foreach($budget_post['budgets'] as $v){
				//Yii::info(VarDumper::dumpAsString($v));
				//Yii::info(VarDumper::dumpAsString($v));
					if($v['id'] != ''){
						$budget = Budget::findOne($v['id']);
						$budget->towhom = $v['towhom'];
						$budget->forwhat = $v['forwhat'];
						$budget->price = (int)str_replace(' ', '', $v['price']);
						$budget->dt1 = $v['dt1'];
						if ($budget->validate()) {
							$budget->delete();  
						} else {
							$errors = $budget->errors;
							foreach($errors as $key=>$value){					
								Yii::$app->session->setFlash('error',"$key => $value[0]");					
							   // echo "$key => $value[0]\n";
							}						 
						}
					}
				}
			} else {
				Yii::$app->session->setFlash('error',"not found");
				return $this->redirect(['budget/index']);
			}
			
			$url = Yii::$app->urlManager->createUrl(['budget/viewcat',
				'cat'=>$cat,
				'date'=>$date,
			]);
              return $this->redirect(['budget/index']);
			//return $this->redirect($url ); 
        }		
		return $this->render('cat-delete', [
			'caption'=>$caption,'title'=>$title,'model'=>$budget]);
	} 
   
	public function actionCreate($date){
		$model = new BudgetSearchForm();
		$caption = Yii::t('app','create ');
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		
		$model = new BudgetForm;
	
		$title = Yii::t('app', 'create budget');
		//Yii::info(VarDumper::dumpAsString($date));
		//$caption = Yii::t('app','create {cat} {date}',['cat'=>$cat,'date'=>$date]);
		$items = [
                1=> 'product1',
                2 =>'product2', 
            ];
		$this->view->params['items'] = $items;
       
		$budget = new BudgetForm();
		$budget->budgets = array();
		
		$monthly = 'yes';
		$command = Yii::$app->db->createCommand("select forwhat, towhom, cat, price, month from template where monthly=:id");
		$command->bindParam(':id',$monthly);
		
		$templates =  $command->queryAll();
			if(count($templates) !== 0){
				foreach ($templates as $template){
					$keys = array("forwhat", "towhom", "cat","price","month");
					$values = array_values($template);
					$associative_array = array_combine($keys, $values);
					$associative_array['dt1'] = $date;
							
					$budget->budgets[] = $associative_array;
				}
				
			}
			
		$monthly = 'no';
				$command = Yii::$app->db->createCommand("select forwhat, towhom, cat, price, month from template where monthly=:id");
				$command->bindParam(':id',$monthly);
		
				$templates =  $command->queryAll();
					if(count($templates) !== 0){
						foreach ($templates as $template){
							$keys = array("forwhat", "towhom", "cat","price","month");
							$values = array_values($template);
							$associative_array = array_combine($keys, $values);						
							$budget->budgets[] = $associative_array;
						}
						
					}
		$budget->dates[] = array('dates'=>$date);
				
		if (Yii::$app->request->post())
        {
            $budget_post = Yii::$app->request->post('BudgetForm');
			//if( Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) ){
			//	$model->schedule = \yii\helpers\Json::encode($model->schedule);
			//	$model->save();
			//}
			//$post = serialize( htmlspecialchars(trim(Yii::$app->request->post['Budget'])).' '.htmlspecialchars(trim(Yii::$app->request->post['Budget'])) );
			//$json_output = json_encode($data, JSON_PRETTY_PRINT);
            Yii::info(VarDumper::dumpAsString($budget_post));
			$date = !isset($budget_post['dates']) ? 0 : $budget_post['dates'][0];
				//$date = $budget_post['dates'][0];
			if(preg_match('%^(?P<year>[12][09][012][0-9])(\-(?P<month>[01][0-9]))?(\-(?P<day>[0-3][0-9]))?%', $date, $matches))
			{
				if(isset($matches['year']) && isset($matches['month']) && isset($matches['day'])){
					$message = 'validate of data is ok';
					
						$budgets = Budget::find()
						->where(['like','dt1',$date."%", false])
						->all();
						foreach ($budgets as $budget){
							//echo $budget->id." delete "."\n";
							$budget->delete();
						}
					
					foreach($budget_post['budgets'] as $v){
						$budget = new Budget();
						$budget->towhom = $v['towhom'];
						$budget->forwhat = $v['forwhat'];
						$budget->cat = $v['cat'];
						$budget->price = (int)str_replace(' ','',$v['price']);
						$budget->dt1 = $date;
						if ($budget->validate()) {
							$budget->save();  
						} else {
							$errors = $budget->errors;
							foreach($errors as $key=>$value){					
								Yii::$app->session->setFlash('error',"$key => $value[0]");					
							}						 
						}
					}
						
				} else {
					$message = 'validate of data is not valid';
				return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	
				}
			} else {
				$message = 'validate of data is not valid';
				return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	
			}
				
              return $this->redirect(['budget/index']);
			//return $this->redirect($url ); 
        }
		return $this->render('create',[ 
			'caption'=>$caption,'title'=>$title,'model'=>$budget,]);		
   
	}
   
	public function actionCreatemonth($cat,$date){
		$model = new BudgetForm();
		//$caption = Yii::t('app','outlay');
        $this->view->params['budget_form_model'] =$model;
		$model = new BudgetCreateForm();
        $this->view->params['budget_create_form_model'] =$model;
		
		$caption = Yii::t('app','outlay {cat} {date}',['date'=>$date]);
		$items = [
                1=> 'product1',
                2 =>'product2',
            ];
		$this->view->params['items'] = $items;
        $this->view->params['budget_form_model'] =$model;

		$message = 'not validate of date';
		return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	

		if(preg_match('%^(?P<year>[12][09][012][0-9])(\-(?P<month>[01][0-9]))?(\-(?P<day>[0-3][0-9]))?%', $date, $matches))
		{
			if(isset($matches['year']) && isset($matches['month']) && isset($matches['day'])){
				$message = 'validate of data is ok';
					
				//$model = new Budget();
				//$model->dt1 = $date;
				$title = Yii::t('app', 'budget delete');
				//Yii::info(VarDumper::dumpAsString($model));
				$caption = Yii::t('app','delete  {date}',['date'=>$date]);
				$items = [
						1=> 'product1',
						2 =>'product2',
						
					];
				
				$budget = new Budget();
				$budget->budgets = array();
				$budget->budgets_notmonthly = array();
				$budget->dt1 = $date;
				
				$monthly = 'yes';
				$command = Yii::$app->db->createCommand("select forwhat, towhom, cat, price from template where monthly=:id");
				$command->bindParam(':id',$monthly);
		
				$templates =  $command->queryAll();
					if(count($templates) !== 0){
						foreach ($templates as $template){
							$keys = array("forwhat", "towhom", "cat","price",);
							$values = array_values($template);
							$associative_array = array_combine($keys, $values);
							$associative_array['dt1'] = $date;
							
							$budget->budgets[] = $associative_array;
						}
					}

				$monthly = 'no';
				$command = Yii::$app->db->createCommand("select forwhat, towhom, cat, price from template where monthly=:id");
				$command->bindParam(':id',$monthly);
		
				$templates =  $command->queryAll();
					if(count($templates) !== 0){
						foreach ($templates as $template){
							$keys = array("forwhat", "towhom", "cat","price",);
							$values = array_values($template);
							$associative_array = array_combine($keys, $values);
							$associative_array['dt1'] = $date;
							
							$budget->budgets_notmonthly[] = $associative_array;
						}
					}
				
				
				
				//Yii::info(VarDumper::dumpAsString($budget));	
					
					
				if (Yii::$app->request->post())
				{
					$budget_post = Yii::$app->request->post('Budget');
					//Yii::info(VarDumper::dumpAsString($budget_post));
					/* if(is_array($budget_post['budgets'])){
						foreach($budget_post['budgets'] as $v){
							if($v['id'] != ''){
								$budget = Budget::findOne($v['id']);
								
								if ($budget->validate()) {
									$budget->delete();  
								} else {
									$errors = $budget->errors;
									foreach($errors as $key=>$value){					
										Yii::$app->session->setFlash('error',"$key => $value[0]");					
									   
									}						 
								}
							}
						}
					} else {
						Yii::$app->session->setFlash('error',"not found for delete");
						return $this->redirect(['budget/index']);
					} */
					
					$url = Yii::$app->urlManager->createUrl(['budget/view/dt1/'.$matches['year'],
						
					]);
             // return $this->redirect(['budget/index']);
					return $this->redirect($url ); 
					
				}
				
				return $this->render('month-create', [
				'caption'=>$caption,'title'=>$title,'model'=>$budget]);
				
			} else {
				
				return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	
			}	
			
		} 
			
		return $this->render('empty-view',['message'=> Yii::t('app',
						$message)
					]);	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

		
		//Yii::info(VarDumper::dumpAsString($budget));
		$total_summ_budget = Budget::getTotal($budget['budgets'],'price');
		//Yii::info(VarDumper::dumpAsString($total_summ_budget));
		$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count,cat,sum(price) as total_summ_for_year from outlay  where dt1 like concat(:id,'%') and cat=:cat");
					$command->bindParam(':id', $year);
					$command->bindParam(':cat', $cat);
					$outlays = $command->queryAll();
					$month_count = 0;
					$total_summ_for_year = 0;
					$avg_year = 0;
					if(count($outlays) != 0){
						$month_count = $outlays[0]['month_count'];
						$total_summ_for_year = $outlays[0]['total_summ_for_year'];
						$avg_year = intval($total_summ_for_year/$month_count);	
					}
					//$month_count = $outlays[0]['month_count'];
					//$total_summ_for_year = $outlays[0]['total_summ_for_year'];
					
		
		
		$total = 0;
			
				$command = Yii::$app->db->createCommand("call get_groupforwhatstowhomsfromcat_outlay(:id,:cat)");
				$command->bindParam(':id', $date);
				$command->bindParam(':cat', $cat);
				$outlays = $command->queryAll();
				$models_view =[];				
		
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							//$model_view->group_cat = $outlay['group_cat'];
							$model_view->forwhat = $outlay['group_forwhat'];
							$model_view->towhom = $outlay['group_towhom'];
							$model_view->price = $outlay['group_summ'];
							//$model_view->date = $date;
							$models_view[] = $model_view; 	  
						}
					} else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								'not outlays')
							]);
					}
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);
		
					$total_summ_for_month = 0;
					$total_summ_for_month = Outlay::getTotal($provider->models, 'price');
					
		if (Yii::$app->request->post())
        {
            /** @var array $array_properties */
            $budget_post = Yii::$app->request->post('Budget');
			//if( Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) ){
			//	$model->schedule = \yii\helpers\Json::encode($model->schedule);
			//	$model->save();
			//}
			//$post = serialize( htmlspecialchars(trim(Yii::$app->request->post['Budget'])).' '.htmlspecialchars(trim(Yii::$app->request->post['Budget'])) );
			//$json_output = json_encode($data, JSON_PRETTY_PRINT);
            //Yii::info(VarDumper::dumpAsString($budget_post['budgets']));

			foreach($budget_post['budgets'] as $v){
				//Yii::info(VarDumper::dumpAsString($v));
					$budget = new Budget();
					$budget->towhom = $v['towhom'];
					$budget->forwhat = $v['forwhat'];
					$budget->price = (int)str_replace(' ','',$v['price']);
					$budget->dt1 = $v['dt1'];
					$budget->cat = $cat;
					if ($budget->validate()) {
						$budget->save();  
					} else {
						$errors = $budget->errors;
						foreach($errors as $key=>$value){					
							Yii::$app->session->setFlash('error',"$key => $value[0]");					
						   // echo "$key => $value[0]\n";
						}						 
					}
			}
			
			$url = Yii::$app->urlManager->createUrl(['budget/viewcat',
				'cat'=>$cat,
				'date'=>$date,
			]);
              return $this->redirect(['budget/index']);
			//return $this->redirect($url );  
        }
		//$template_total_summ = Budget::getTotal($provider->models, 'group_summ');
		
		return $this->render('cat-create',['dataProvider' => $provider,
			'caption'=>$caption,'title'=>$title,'model'=>$budget,'total_summ_for_year'=>$total_summ_for_year,'avg_year'=>$avg_year,'month_count'=>$month_count,'total_for_month'=>$total_summ_for_month,'cat'=>$cat,'date'=>$date,'total_summ_budget'=>$total_summ_budget]);
		
   
   
		return $this->render('empty-view');	
   
	}
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

}
