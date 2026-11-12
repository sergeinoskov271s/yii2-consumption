<?php

namespace app\controllers;
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
        $this->layout = '@app/views/layouts/main-outlay.php';
        return parent::beforeAction($action);
    }

    /**
     * @return array|string|Response
     * @throws base\InvalidConfigException
     */
    public function actionIndex()
    {
        /** @var 'yii/base/model' $model */
		//Yii::info('outlay index');
        $model = new SearchForm();
        $this->view->params['search_form_model'] =$model;
         
        if(Yii::$app->request->post('SearchForm'))
        {
			$model->attributes = Yii::$app->request->post('SearchForm');
			$date = $model->date;
			$order= $model->order;
			$pole= $model->pole;			
			Yii::info($date.$order.$pole);
			
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'navigation',
                'value' => $order,
            ]));
			$cookies->add(new \yii\web\Cookie([
                'name' => 'date_navigation',
                'value' => $date,
            ]));

            $model ->validate();

            if ($model->hasErrors()){
                $this->view->params['search_form_model'] = $model;
                $this->view->params['selected_form_option'] = $model->order;
                return $this->render('index',['model' => $model,'order'=>$model->attributeLabels()]);
            }
			return $this->redirect('view'.'/'.$order.'/'.$date.'/'.$pole);
        }
        return $this->render('index');
    }
	
	public function actionView($pole, $order, $date)
    {
		$model = new SearchForm();
		$this->view->params['search_form_model'] = $model;
		$caption = Yii::t('app','search {order} {pole} {date}',['order'=>$order,'pole'=>$pole,'date'=>$date]);
		if(preg_match('%^(?P<year>[12][09][012][0-9])(\-(?P<month>[01][0-9]))?(\-(?P<day>[0-3][0-9]))?%', $date, $matches)){
			if(isset($matches['year']) && isset($matches['month']) && isset($matches['day'])){
				$outlay = null;
				switch ($order){
					case 'dt1':
						if($pole === ''){
							$query = Outlay::find()->where(['dt1' => $date])->orderBy('dt1');
						} else {
							$query = Outlay::find()->where(['dt1' => $date])->orderBy('dt1');
						}
						break;
					case 'forwhat':
						if($pole === ''){
							$query = Outlay::find()->where(['dt1' => $date])->orderBy('forwhat'); 
						} else {
							$query = Outlay::find()->where(['dt1' => $date,'forwhat'=>$pole])->orderBy('cat');
						}
						break;
					case 'towhom':
						if($pole === ''){
							$query = Outlay::find()->where(['dt1' => $date])->orderBy('dt1')->orderBy('towhom');
						} else {
							$query = Outlay::find()->where(['dt1' => $date,'towhom'=>$pole])->orderBy('dt1')->orderBy('cat');
						}
						break;
					case 'price':
						if($pole === ''){
							$query = Outlay::find()->where(['dt1' => $date])->orderBy('price');
						} else {
							$query = Outlay::find()->where(['price' => $pole,'dt1' => $date,]);
						}
						break;
					case 'cat':
						if($pole === ''){
							$query = Outlay::find()->where(['dt1' => $date])->orderBy('cat');
						} else {
							$query = Outlay::find()->where(['cat' => $pole,'dt1' => $date,]);
						}
						break;	
					
					/* case 'cat':
						if($pole === ''){
							$query = Outlay::find()->where(['dt1' => $date])->orderBy('cat');
						} else {
							 $query = Outlay::find()->where(['dt1' => $date,'cat'=>$pole])->orderBy('forwhat');
						}	   
						break;		 */		
				}
				if($order == 'cat' && empty($pole)){
					// out all cats for year month day
					
					$caption = $date;
					$command = Yii::$app->db->createCommand("call get_cats(:id)");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					$models_view =[];
				
					$columns=array('cat','main_count','total_summ',); 
					$count=count($columns); 
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->cat = $outlay['cat'];
							$model_view->main_count = $outlay['main_count'];
							$model_view->total_summ = $outlay['total_summ'];
							$model_view->date = $date;
							$models_view[] = $model_view; 	  
						}
					} else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
						
					}
					
					$total_summ = 0;

					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);
					$total_summ = View::getTotal($provider->models, 'total_summ');
					
					$title = Yii::t('app', 'date {date}');
					return $this->render('cats-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'month_count'=>1,'avg_year'=>1]);
						
				} elseif($order == 'cat' && !empty($pole)){
					$title = Yii::t('app', 'date {date}');
					$caption = $date.' '.$pole;
				
					$command = Yii::$app->db->createCommand("call get_cat(:id,:pole)");
					$command->bindParam(':id', $date);
					$command->bindParam(':pole', $pole);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->towhom = $outlay['towhom'];
							$model_view->forwhat = $outlay['forwhat'];
							$model_view->price = $outlay['price'];
							$model_view->dt1 = $outlay['dt1'];
							$models_view[] = $model_view; 	  
						}
					} else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
					}
										
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'price');
					return $this->render('cat-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);

				} elseif($order == 'towhom' && !empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date.' '.$pole;
					
					$command = Yii::$app->db->createCommand("call get_forwhat(:id,:pole)");
					$command->bindParam(':id', $date);
					$command->bindParam(':pole', $pole);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->towhom = $outlay['towhom'];
							$model_view->main_count = $outlay['main_count'];
							$model_view->total_summ = $outlay['total_summ'];
							$models_view[] = $model_view;
						}
					} else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);	
					}

					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'total_summ');
					return $this->render('cat-towhom-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);

				} elseif($order == 'groupcat' && !empty($pole)){
					// out one groupcat for year month day
					$title = Yii::t('app', 'date {date}');
					$caption = $date.' '.$pole;
					
					$command = Yii::$app->db->createCommand("call get_groupforwhatstowhomsfromcat_outlay(:id,:pole)");
					$command->bindParam(':id', $date);
					$command->bindParam(':pole', $pole);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->group_towhom = $outlay['group_towhom'];
							$model_view->group_summ = $outlay['group_summ'];
							$model_view->group_forwhat = $outlay['group_forwhat'];		
							
							$models_view[] = $model_view; 	  
						}
					} else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
					}
									
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'group_summ');
					return $this->render('groupcat-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);
				} elseif($order == 'groupcat' && empty($pole)){
					// out all groupcats for year month day
				
					$caption = $date;
					$command = Yii::$app->db->createCommand("call get_groupscats_outlay(:id)");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					$models_view =[];
				
					$columns=array('cat','main_count','total_summ',); 
					$count=count($columns); 
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->group_cat = $outlay['group_cat'];
							//$model_view->dt1 = $outlay['dt1'];
							$model_view->group_summ = $outlay['group_summ'];
							$model_view->date = $date;
							$models_view[] = $model_view; 	  
						}
					} else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
					}
					
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);
					$total_summ = View::getTotal($provider->models, 'group_summ');
					
					$title = Yii::t('app', 'date {date}');
					return $this->render('groupcats-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'month_count'=>1,'avg_year'=>1,'date'=>$date]);

				} else {
					
					$data_provider = new ActiveDataProvider([
						'query' => $query,
						'pagination' => false,
						/* 'pagination' => [
							'pageSize' => 10,
						], */
						'sort' => ['attributes' => [$order]]
					]);
					
					$total_summ = Outlay::getTotal($data_provider->models, 'price');
					
					$title = Yii::t('app', 'date {date}');
					return $this->render('grid', [ 'dataProvider' => $data_provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);				
				}				
								
			} elseif(isset($matches['year'])){
				switch ($order){
					case 'dt1':
						if($pole === ''){
							$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->orderBy('cat');
						} else {
							$query = Outlay::find()->where(['dt1' => $pole])->orderBy('cat');
						}
						break;
					case 'forwhat':
						if($pole === ''){
							$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->orderBy('forwhat'); 
						} else {
							$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->where(['forwhat'=>$pole])->orderBy('cat');
						}
						break;
					case 'towhom':
						if($pole === ''){
							$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->orderBy('dt1')->orderBy('towhom');
						} else {
							$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->where(['towhom'=>$pole])->orderBy('cat');
						}
						break;
					case 'price':
						if($pole === ''){
							$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->orderBy('dt1')->orderBy('price');
						} else {
							$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->where(['>=', 'price', $pole])->orderBy('price');
							
						}
						break;
					case 'cat':
						if($pole === ''){
							//$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->where(['tab_number'=>new Expression('sp_id_number( :id)')]) ->orderBy('cat');
						} else {
							$query = Outlay::find()->where(['like', 'dt1', $date."%", false])->where(['cat'=>$pole])->orderBy('cat');
						}
						break;				
				}
				if($order == 'cat' && empty($pole)){
					
					$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count from outlay  where dt1 like concat(:id,'%')");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					$month_count = $outlays[0]['month_count'];
					$command = Yii::$app->db->createCommand("call get_cats(:id)");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0 && $month_count>0){
						
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->cat = $outlay['cat'];
							$model_view->date = $date;
							$model_view->main_count = $outlay['main_count'];
							$model_view->total_summ = $outlay['total_summ'];
							$model_view->date = $date;
							//$model_view->group_avg = $outlay['group_avg'];
							$model_view->month_avg = floor($outlay['total_summ']/$month_count);
							$models_view[] = $model_view; 	  
						}
					}
					else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
					}
					
					$total = 0;
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);
					$total_summ = View::getTotal($provider->models, 'total_summ');
				//Yii::info(VarDumper::dumpAsString($models_view));
					$title = Yii::t('app', 'date {date}');
					$avg_year = round($total_summ/$month_count);
					return $this->render('cats-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'month_count'=>$month_count,'avg_year'=>$avg_year]);
						
				} elseif($order == 'cat' && !empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date.' '.$pole;

					$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count from outlay  where dt1 like concat(:id,'%') and cat=:pole");
					$command->bindParam(':id', $date);
					$command->bindParam(':pole', $pole);
					$outlays = $command->queryAll();
					$month_count = $outlays[0]['month_count'];
					$command = Yii::$app->db->createCommand("call get_cat(:id,:pole)");
					$command->bindParam(':id', $date);
					$command->bindParam(':pole', $pole);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->towhom = $outlay['towhom'];
							$model_view->forwhat = $outlay['forwhat'];
							$model_view->dt1 = $outlay['dt1'];
							$model_view->price = $outlay['price'];
							//$model_view->id = $outlay['id'];
							$models_view[] = $model_view; 	  
						}
					}else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
					}

					$total_summ = 0;
					
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						//'totalCount' => 5,
						'sort' => [
							'attributes' => ['dt1','forwhat','towhom','price'],
							//'defaultOrder' => [
							//	'dt1' => SORT_DESC,
							//	'forwhat' => SORT_ASC, 
							//]
						],
					]);	
					
					$total_summ = View::getTotal($provider->models, 'price');
					$avg_year = round($total_summ/$month_count);
					return $this->render('cat-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'month_count'=>$month_count,'avg_year'=>$avg_year]);

				} elseif($order == 'towhom' && empty($pole)) {
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date;
					$command = Yii::$app->db->createCommand("call get_towhoms(:id)");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->towhom = $outlay['towhom'];
							$model_view->date = $date;
							$model_view->main_count = $outlay['main_count'];
							$model_view->total_summ = $outlay['total_summ'];
							$model_view->avg_summ = (int)($outlay['total_summ']/$outlay['main_count']);
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
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'total_summ');
					$avg_summ = View::getTotal($provider->models, 'avg_summ');
					return $this->render('towhoms-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'avg_summ'=>$avg_summ]);
				} elseif($order == 'towhom' && !empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date.' '.$pole;

					$command = Yii::$app->db->createCommand("call get_towhom(:id,:pole)");
					$command->bindParam(':id', $date);
					$command->bindParam(':pole', $pole);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->forwhat = $outlay['forwhat'];
							$model_view->dt1 = $outlay['dt1'];
							$model_view->price = $outlay['price'];
							$model_view->cat = $outlay['cat'];
							
							$models_view[] = $model_view;
						}
					}else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
					}
					
					$total_summ = 0;
					
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'price');
					return $this->render('towhom-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);

				} elseif($order == 'dt1' && empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date;
					$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count from outlay  where dt1 like concat(:id,'%')");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					$month_count = $outlays[0]['month_count'];
					$command = Yii::$app->db->createCommand("call get_date_year(:id)");
					$command->bindParam(':id', $date);

					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();
							$model_view->date = $date;
							$model_view->dt1 = $outlay['dt1'];
							$model_view->total_summ = $outlay['total_summ'];
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
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'total_summ');
					$avg_year = round($total_summ/$month_count);
					return $this->render('date-year-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'month_count'=>$month_count,'avg_year'=>$avg_year,]);	

				} elseif($order == 'dt1' && !empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date.' '.$pole;
					$this->view->params['search_form_model'] = $model;
					$command = Yii::$app->db->createCommand("call get_date(:id)");
					$command->bindParam(':id', $pole);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();
							$model_view->cat = $outlay['cat'];
							$model_view->towhom = $outlay['towhom'];
							$model_view->forwhat = $outlay['forwhat'];
							$model_view->price = $outlay['price'];
							$model_view->id = $outlay['id'];
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
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'price');
			
					$title = Yii::t('app', 'date {date}');
					return $this->render('date-grid', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);	
							
					
				} elseif($order == 'price' && !empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date.' '.$pole;
					$command = Yii::$app->db->createCommand("call get_price_value(:id,:pole)");
					$command->bindParam(':id', $date);
					$command->bindParam(':pole', $pole);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();
							$model_view->cat = $outlay['cat'];
							$model_view->dt1 = $outlay['dt1'];
							$model_view->towhom = $outlay['towhom'];
							$model_view->forwhat = $outlay['forwhat'];
							$model_view->price = $outlay['price'];
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
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'price');
					$title = Yii::t('app', 'date {date}');
					return $this->render('price-grid', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);	
					
				} elseif($order == 'price' && empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date.' '.$pole;
					$command = Yii::$app->db->createCommand("call get_prices(:id)");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();
							$model_view->cat = $outlay['cat'];
							$model_view->dt1 = $outlay['dt1'];
							$model_view->towhom = $outlay['towhom'];
							$model_view->forwhat = $outlay['forwhat'];
							$model_view->price = $outlay['price'];
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
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'price');

					$title = Yii::t('app', 'date {date}');
					return $this->render('price-grid', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);	
				} elseif($order == 'forwhat' && empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date;

					$command = Yii::$app->db->createCommand("call get_forwhats(:id)");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();
							$model_view->main_count = $outlay['main_count'];
							$model_view->total_summ = $outlay['total_summ'];
							$model_view->date = $date;
							$model_view->forwhat = $outlay['forwhat'];
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
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'total_summ');

					$title = Yii::t('app', 'date {date}');
					return $this->render('forwhat-grid-year', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);	
					
					
				} elseif($order == 'forwhat' && !empty($pole)){
					$title = Yii::t('app', 'date {date}',['date'=>'date']);
					$caption = $date.' '.$pole;
					$command = Yii::$app->db->createCommand("call get_forwhat(:id,:pole)");
					$command->bindParam(':id', $date);
					$command->bindParam(':pole', $pole);
					$outlays = $command->queryAll();
					
					$models_view =[];
				
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();
							$model_view->cat = $outlay['cat'];
							$model_view->dt1 = $outlay['dt1'];
							$model_view->towhom = $outlay['towhom'];
							$model_view->price = $outlay['price'];
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
						'pagination' => false,
						'totalCount' => 5,
					]);	
					
					$total_summ = View::getTotal($provider->models, 'price');
			
					$title = Yii::t('app', 'date {date}');
					return $this->render('forwhat-grid', [ 'dataProvider' => $provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);	
					
					
				} elseif($order == 'groupcat' && empty($pole)){
					// out all groupcats for year month day
					
					$caption = $date;
					$command = Yii::$app->db->createCommand("call get_groupscats_outlay(:id)");
					$command->bindParam(':id', $date);
					$outlays = $command->queryAll();
					$models_view =[];
				
					$columns=array('cat','main_count','total_summ',); 
					$count=count($columns); 
					if(count($outlays) !== 0){
						foreach ($outlays as $outlay){
							$model_view = new View();		
							$model_view->group_cat = $outlay['group_cat'];
							$model_view->group_summ = $outlay['group_summ'];
							$model_view->date = '2027';	  
						}
					} else {
						return $this->render('empty-view',
							['message'=> Yii::t('app',
								' not found')
							]);
					}
					//Yii::info(VarDumper::dumpAsString($models_view));
					
					$total_summ = 0;
					$provider = new ArrayDataProvider([
						'allModels' => $models_view,
						'pagination' => false,
						'totalCount' => 5,
					]);
					$total_summ = View::getTotal($provider->models, 'group_summ');
					
					$title = Yii::t('app', 'date {date}');				
					return $this->render('groupcats-grid', [ 'dataProvider' => $provider,
						'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ,'month_count'=>1,'avg_year'=>1,'date'=>$date]);

				}
					else {
					
					$caption = $date.' '.$pole;
					$data_provider = new ActiveDataProvider([
						'query' => $query,
						//'pagination' => false,
						'pagination' => [
							'pageSize' => 10,
						],
						'sort' => ['attributes' => [$order]]
					]);
					$total_summ = Outlay::getTotal($data_provider->models, 'price');
					$title = Yii::t('app', 'date {date}');
					return $this->render('grid', [ 'dataProvider' => $data_provider,
					'caption'=>$caption,'title'=>$title,'total_summ'=>$total_summ]);				
				}
			}
			
		} else {		
			return $this->render('empty-view',
				['message'=> Yii::t('app',
						'date not matches')
				]);
		}
		
    }
	

	
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

}
