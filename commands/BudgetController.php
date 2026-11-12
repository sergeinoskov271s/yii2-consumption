<?php

namespace app\commands;
use phpDocumentor\Reflection\Types\Null_;
use app\common\components\UriGetHelpers;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\helpers\Console;
use Yii;
use yii\base;
use yii\db\Query;
use yii\helpers\VarDumper;
use app\models\Budget;
use app\models\BudgetForm;
use app\models\SearchForm;
use app\models\View;
use app\models\Template;
use yii\db\ActiveRecord;
use yii\console\ErrorHandler;
use yii\db\IntegrityException;
use yii\helpers\ArrayHelper;
use app\common\components\CurlGetHelpers;
use yii\helpers\BaseUrl;
use yii\helpers\FileHelper;
//use dastin\csv\models\CsvData;
//use app\models\CsvBasic;
use yii\helpers\Json;

class BudgetController extends Controller
{
    public function options($actionID)
    {
        return ['id'];
    }

    public function optionAliases()
    {
        return ['id' => 'id'];
    }
    protected function beforeSave($insert) {
//		Yii::info(VarDumper::dumpAsString('-----------$insert------'));

        return parent::beforeSave($insert);
    }
    protected function beforeValidate() {

        return parent::beforeValidate();
    }
    
    public function actionAddone($id){
        $dt1 = '2026-11-01';
        $towhom = 'towhom '.$id;
        $forwhat = 'forwhat '.#id;
		$price = '2000';
        $cat ='cat'.$id;

        $this->add($dt1,$towhom, $forwhat, $price, $cat);
    }
    protected function add($dt1,$towhom, $forwhat,$price, $cat){
        try{
            $budget = new Budget();
            $budget->towhom = $towhom;
            $budget->forwhat = $forwhat;
			$budget->price = $price;
            $budget->cat = $cat;
            $budget->dt1 = $dt1;
      
            if ($budget->validate()) {
//					$product->setDefaultValues();
//					Yii::info(VarDumper::dumpAsString($product));
                $budget->save();

                echo "budget add $budget->id \n";
            } else {
                $errors = $budget->errors;
                foreach($errors as $key=>$value){
                    echo "$key => $value[0]\n";
                }
                /* $message_error = $this->ansiFormat($errors['path_fullsize'][0], Console::BOLD);
                echo $message_error."\n";
                Yii::info(VarDumper::dumpAsString($attributes)); */
            }

        } catch(IntegrityException $e){
 //           Yii::info(VarDumper::dumpAsString($e));
//				echo $e->getCode();
//				echo $e->getMessage()."\n";
            $message_error = $this->ansiFormat($e->errorInfo[2], Console::BOLD);
            echo $message_error."\n";
        }
    }
   
    public function actionGetone($id){
        $budget = Budget::findone($id);
        if($budget == NULL){
            echo "not found budget id $id\n";
            return ExitCode::OK;
        }

        $model = new budget();
        $attributes = $model->getAttributes();
        foreach(array_keys($attributes) as $key){
            echo "OldAttributes $key => ".$this->ansiFormat($budget->getOldAttributes()[$key], Console::FG_YELLOW)."\n";
        }
        $query = new Query();
        $query
            ->select(['towhom', 'forwhat', 'cat','dt1'])
            ->from('budget')
            ->where(['budget.id'=>$id])
            ->orderBy('budget.id DESC');
//			->limit(1);\\one
//		$command = $query->createCommand();
//		$rows = $command->queryAll();
        $rows = $query->all();

        foreach($rows as  $row){
            foreach($row as $key=>$value){
                echo "$key => ".$this->ansiFormat($value, Console::FG_YELLOW)."\n";
            }
        }
        return ExitCode::OK;
    }
	
	
	public function actionGetcatlike($id){
        $model = new budget();
        $attributes = $model->getAttributes();
       /*  foreach(array_keys($attributes) as $key){
            echo "OldAttributes $key => ".$this->ansiFormat($budget->getOldAttributes()[$key], Console::FG_YELLOW)."\n";
        } */
		$rows = Budget::find()
			->select(['cat', 'dt1'])
			->distinct()
			->where(['like', 'cat', $id])
			->where(['like','dt1', '2025'])
			->all();
			
        foreach($rows as  $row){
            foreach($row as $key=>$value){
                echo "$key => ".$this->ansiFormat($value, Console::FG_YELLOW)."\n";
            }
        }
        return ExitCode::OK;
    }

    public function actionGetall() {//product/get-all
        $model = new Budget();
        $budgets = Budget::find()
            ->select('budget.*')
            ->all();
        $attributes_name = $model->getAttributes();
        $message = '';
        if(count($budgets) !== 0){
            foreach ($budgets as $budget){
                foreach(array_keys($attributes_name) as $key){
                    echo "$key => ".$this->ansiFormat($budget->getOldAttributes()[$key], Console::FG_YELLOW)."\n";
                }
            }
        }
        return ExitCode::OK;
    }
	
	public function actionGetgroupcats($id) {//budget/getcats '2025-10-01'
		
		$command = Yii::$app->db->createCommand("call get_groupcats(:id)");
		$command->bindParam(':id', $id);
		$model = new Budget();
		$attributes_name = $model->getAttributes();
		$result = [];
		$budgets = $command->queryAll();
		$models_view =[]; 
        if(count($budgets) !== 0){
            foreach ($budgets as $budget){
				$model_view = new View();
							$model_view->group_summ = $budget['group_summ'];
							$model_view->main_count = $budget['main_count'];
							$model_view->group_avg = $budget['group_avg'];
							$model_view->group_cat = $budget['group_cat'];
							
							$models_view[] = $model_view; 	   
			//	$result['cat'] = $budget->group_cat;
			//	$result['price'] = $budget->price;
                /* foreach(array_keys($attributes_name) as $key){
					if(array_key_exists($key,$budget->getOldAttributes())){
						echo "$key => ".$this->ansiFormat($budget->getOldAttributes()[$key], Console::FG_YELLOW)."\n";
					}
                } */
            }
        }
		print_r($budgets);
		echo $models_view[0]['group_cat'];
		//echo number_format( $result[0]->total_summ, 2, ',', ' '), PHP_EOL;
		//echo number_format( $result[0]->total_summ * 200, 2, ',', ' '), PHP_EOL;
		//echo "\n";
//		$products = Product::find()->joinWith('photos', false, 'INNER JOIN')->all();
	//Yii::info(VarDumper::dumpAsString($budgets));
//		Yii::info(VarDumper::dumpAsString(\Yii::$app->params['thumbnail.size']));//from params config
		
        return ExitCode::OK;
    }
	
	public function actionGetmonthsfromyear($id='2025'){
		$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count from budget  where dt1 like concat(:id,'%')");
		$command->bindParam(':id', $id);
		$budgets = $command->queryAll();
		echo $budgets[0]['month_count']."\n";
	}
	
	public function actionGetavgcatsfromyear($id='2025'){
		$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count from budget  where dt1 like concat(:id,'%')");
		$command->bindParam(':id', $id);
		$budgets = $command->queryAll();
		$month_count = $budgets[0]['month_count'];
		echo $budgets[0]['month_count']."\n";
		$command = Yii::$app->db->createCommand("call budget_get_cats_year(:id)");
		$command->bindParam(':id', $id);
		$budgets = $command->queryAll();
        $model_view = new View();

		$result = [];
        if(count($budgets) !== 0){
            foreach ($budgets as $budget){
				$model_view = new View();		
				$model_view->cat = $budget['cat'];
				$model_view->main_count = $budget['main_count'];
				$model_view->total_summ = $budget['total_summ'];
				$model_view->avg_summ = round($budget['total_summ']/$month_count);
				$result[] = $model_view; 	  
            
            }
        }		
		print_r($result);
	}
	
    public function actionDeleteOne($id=1){//employee/delete-one <id>

        try{
            $model = Budget::findOne($id);
            if($model){
                $model->delete();
                echo "model delete $id\n";
            } else {
                echo "not model with id => ".$id;
            }

        } catch(IntegrityException $e){
            Yii::info(VarDumper::dumpAsString($e));
//				echo $e->getCode();
//				echo $e->getMessage()."\n";
            $message_error = $this->ansiFormat($e->errorInfo[2], Console::BOLD);
            echo $message_error."\n";
        }
        return ExitCode::OK;
    }

	public function actionDeleteyear($id){
		try{
			$budgets = Budget::find()
            ->where(['like','dt1',$id."%", false])
            ->all();
			foreach ($budgets as $budget){
				echo $budget->id." delete "."\n";
				$budget->delete();
				
			}
		} catch(IntegrityException $e){
            Yii::info(VarDumper::dumpAsString($e));
//				echo $e->getCode();
//				echo $e->getMessage()."\n";
            $message_error = $this->ansiFormat($e->errorInfo[2], Console::BOLD);
            echo $message_error."\n";
        }
		
		return ExitCode::OK;
	}

    public function actionIndex()
    {
        echo 'ok' . "\n";
   //     Yii::info('message log');
	//	Yii::debug('начало вычисления среднего дохода');
  //      Yii::info(VarDumper::dumpAsString(array('q'=>4)));
 //       echo Department::className()."\n";
        return ExitCode::OK;
    }
	public function actionTestsearchmodel()
	{
		$model = new SearchForm();
		$model ->validate();
		$errors = $model->errors;
		print_r($errors);
		
		$model->order='dt1';
		$model->date = '2025-10-01';
		echo $model->date;
		echo $model->order;
		$model ->validate();
		
		
		$errors = $model->errors;
		print_r($errors);
		//print_r($model->attributeLabels());
	
	}
	
	public function actionCreatemonth($date)
	{
		echo 'Createmonth '.$date."\n";//------------test date validation---------------------
		$budget = new BudgetForm();
		$budget->date = $date;
		
		if ($budget->validate()){
			echo "date ok"."\n";
		} else {
			$errors = $model->errors;
                foreach($errors as $key=>$value){
                    echo "$key => $value[0]\n";
                }
			exit;
		}
		
		Budget::deleteAll(['like', 'dt1', $date]);
		
		$model = new Template();
        $templates = Template::find()
            ->select('template.*')
            ->all();
        $attributes_name = $model->getAttributes();
        $message = '';
        if(count($templates) !== 0){
            foreach ($templates as $template){
					if($template->monthly == 'yes'){//------test on monthly ---------------
							$budget = new Budget();//---------------create model-------------------
							$budget->dt1 = $date;
							$budget->towhom = $template->towhom;
							$budget->forwhat = $template->forwhat;
							$budget->price = $template->price;
							$budget->cat = $template->cat;
							if ($budget->validate()) {//------validate model-----------------
								$budget->save();
							} else {
								$errors = $budget->errors;
								foreach($errors as $key=>$value){
									echo "$key => $value[0]\n";
								}
							}
					} else {
						echo $template->towhom." ".$template->forwhat." ".$template->cat." "."\n";
						
					}  
            }
        }
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
   
}
