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
use app\models\Template;
use app\models\View;
use app\models\Outlay;
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
use app\components\DateHelpers;

class BalanceController extends Controller
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
    public function actionIndex($message = 'hello from balance')
    {
        echo $message . "\n";
		// Accessing the request object
        $request = \Yii::$app->request;

        // Getting command-line arguments (excluding the script name)
        $params = $request->getParams();
        echo "Command line parameters: " . implode(', ', $params) . "\n";

		Yii::info(VarDumper::dumpAsString('test log from hello  controller'));
        return ExitCode::OK;
    } 
   
	public function actionGet()
	{
			echo "-------------------------------"."\n";
			$date = "2026-03-01";
			$months_list_after = DateHelpers::getDateAfterYearDateTimeForRegexp($date);
			echo "after with current month".$date."\n";//with current month
			print_r($months_list_after);
			echo "\n";
			
		
		$months_list_before = DateHelpers::getDateBeforeYearDateTimeForRegexp($date);//without current month
		echo "before without current month".$date."\n";
		echo($months_list_before);
		echo "\n";
	
		$month_outlay_regexp ='';
		if ($months_list_before){
			$outlays = Yii::$app->db->createCommand('select distinct substring(dt1,1,7) as month_outlay_regexp from outlay where dt1 regexp :id', [':id' =>$months_list_before])->queryAll();
			foreach ($outlays as $outlay){
				$month_outlay_regexp .= '|'.$outlay['month_outlay_regexp'];
			}
		}
		
		$month_outlay_regexp = mb_substr( $month_outlay_regexp,1);
	
		echo "month_outlay_regexp".$date."\n";
		echo $month_outlay_regexp."month_outlay_regexp"."\n";
		
		$month_all_regexp = DateHelpers::getDateYearDateTimeForRegexp($date);
		echo "month_all_regexp".$date."\n";
		echo($month_all_regexp);
		echo "\n";
		
	
		$month_without_outlay_regexp = str_replace($month_outlay_regexp, '', $month_all_regexp);
		$month_without_outlay_regexp = ltrim($month_without_outlay_regexp,'|');
		$command = Yii::$app->db->createCommand('select distinct substring(dt1,1,7) as dt from budget where dt1 regexp :id',[':id' =>$month_without_outlay_regexp]);
		$budgets =  $command->queryAll();
		$month_budget_without_outlay_regexp_exist = '';
		foreach($budgets as $budget){
			$month_budget_without_outlay_regexp_exist .= '|'.$budget['dt'];
		}
		$month_budget_without_outlay_regexp_exist = ltrim($month_budget_without_outlay_regexp_exist,'|');
		
		
		echo "month_budget_without_outlay_regexp_exist".$date."\n";
		echo($month_budget_without_outlay_regexp_exist);
		echo "\n";
		
		
		$month_remain = str_replace($month_budget_without_outlay_regexp_exist, '', $month_without_outlay_regexp);
		$month_remain = ltrim($month_remain,'|');
		echo "month_remain".$date."\n";
		echo($month_remain);
		echo "\n";
		
	
		$command = Yii::$app->db->createCommand("select sum(price) as total_month_template from template");
		
		$template =  $command->queryOne();
		$total_month_template = $template['total_month_template'];
					
		echo "total_month_template"."\n";
		echo($total_month_template);
		echo "\n";			
	
	
		$total_template = $total_month_template * 12;
	
		echo "total_template for year"."\n";
		echo($total_template);
		echo "\n";
	
		$command = Yii::$app->db->createCommand('select sum(price) as total_budget from budget where dt1 regexp :id',[':id' =>$month_without_outlay_regexp]);
		
		$budget =  $command->queryOne();
		$total_budget = $budget['total_budget'];
					
		echo "total_budget".$month_without_outlay_regexp."\n";
		echo($total_budget);
		echo "\n";			
	
	
		$command = Yii::$app->db->createCommand('select sum(price) as total_outlay from outlay where dt1 regexp :id',[':id' =>$month_outlay_regexp]);
		
		$outlay =  $command->queryOne();
		$total_outlay = $outlay['total_outlay'];
					
		echo "total_outlay".$date."\n";
		echo($total_outlay);
		echo "\n";		
	
		$array = explode("|", $months_list_after);
		$count_month_balance = count($array);
		echo "count_month_balance".$date."\n";
		echo($count_month_balance);
		echo "\n";
	
		$total_balance = $total_template - $total_budget - $total_outlay ;
		echo "total_balance".$date."\n";
		echo($total_balance);
		echo "\n";
	
	
	
	
	
	
	
	
		//get outlay
		//$date = "2026-02-01";
		$months_list = DateHelpers::getDateBeforeYearDateTimeForArray($date);
		echo "before".$date."\n";
		//print_r($months_list);
		echo "\n";
		
		//$date = "2026-01-01";
		//$command = Yii::$app->db->createCommand("select * from outlay where dt1=:id");
		//$command->bindParam(':id', $date);	
		//$outlys = $command->queryAll();
			$total_summ = 0; 
			/* foreach ($outlys as $outly){
					$total_summ += isset($outly['price']) ? (int)$outly['price'] :0;  
			}  */
		echo 	$total_summ."\n";
		$cats = array(); 
			$outlays = Outlay::find()
					->select('outlay.cat')
					//->where(['like','outlay.dt1',$param2])
					//->where(['outlay.dt1'=>'2025-10-01'])
					->distinct()
					->orderBy('outlay.cat')
					->all();				
				if(count($outlays) !== 0){
					foreach ($outlays as $outlay){
						$cats[] = $outlay['cat'];
					}
				}
		//print_r($cats);
		$outlay_price = array();
		foreach($cats as $cat){
			//$command = Yii::$app->db->createCommand("select * from outlay where cat=:cat and dt1 like concat(:id,'%')");
			//$command->bindParam(':id', $date);	
			//$command->bindParam(':cat', $cat);	
			//$outlys = $command->queryAll();
				$total_summ_price = 0; 
				//foreach ($outlys as $outly){
						//$total_summ_price += isset($outly['price']) ? (int)$outly['price'] :null;  
				//}
			$outlay_price[$cat]	= $total_summ_price;
		}
		//print_r($outlay_price);
		
		$cats_template = array();
				$templates = Template::find()
					->select('template.cat')
					->distinct()
					->all();				
				if(count($templates) !== 0){
					foreach ($templates as $template){
						$cats_template[] = $template['cat'];
					}
				}
		echo 'cats_template'."\n";
		
		print_r($cats_template);
		$budget_price = array();
		$date = '2026-03-01';
		foreach($cats as $cat){
			$command = Yii::$app->db->createCommand("select * from budget where cat=:cat and dt1 like concat(:id,'%')");
			$command->bindParam(':id', $date);	
			$command->bindParam(':cat', $cat);	
			$budgets = $command->queryAll();
				$total_summ_price = 0; 
				foreach ($budgets as $budget){
						$total_summ_price += isset($budget['price']) ? (int)$budget['price'] :null;  
				}
			$budget_price[$cat]	= $total_summ_price;
		}
		//print_r($budget_price);
		
		//$hello = DateHelpers::getDateAfterYearDateTimeForRegexp($date);
		//echo $hello."\n";
		
		
		
		$wordsArray = explode("-", $date);
		$date_year = $wordsArray[0];
		$date_month = $wordsArray[1];
		$dates = DateHelpers::getDateMonthYearDateTimeForArray('2025-01-01');
		//print_r($dates);
		$months = array('01','02','03','04','05','06','07','08','09','10','11','12');
		$months_balance = array();
		foreach($cats_template as $cat){
			echo $cat."\n";
		
				//$command = Yii::$app->db->createCommand("call get_cat(:id,:cat), [':id' => '2026-01',':cat'=>$cat]")->queryOne();
				//$o = Yii::$app->db->createCommand('call get_cat(:id,:cat)', [':id' =>$date_year.'-'.$month ,':cat'=>$cat])->queryOne();
				
				//if($o ){
					//print_r($o);
				//	//$months_balance[] = $date_year.'-'.$month;
				//	$months_balance[$date_year.'-'.$month][] =  $cat;
					//echo $date_year.'-'.$month;
				//}	else {
					//echo $date_year.'-'.$month.$cat."\n";
				//}
			$command = Yii::$app->db->createCommand("select sum(price) as total_month_template_cat from template where cat=:cat");
			$command->bindParam(':cat', $cat);
			$template =  $command->queryOne();
			$total_month_template_cat = $template['total_month_template_cat'];
			$total_template_cat = $total_month_template_cat * 12;
						
			echo "total_template_cat"."\n";
			echo($total_template_cat);
			echo "\n";			
			






			$command = Yii::$app->db->createCommand('select sum(price) as total_outlay_cat from outlay where dt1 regexp :id and cat=:cat',[':id' =>$month_outlay_regexp,':cat'=>$cat]);
			$outlay =  $command->queryOne();
			$total_outlay_cat = $outlay['total_outlay_cat'] ? $outlay['total_outlay_cat'] : 0;
			
			echo "total_outlay_cat".$date."\n";
			echo($total_outlay_cat);
			echo "\n";		
			
				
			$command = Yii::$app->db->createCommand('select sum(price) as total_budget_cat from budget where dt1 regexp :id and cat=:cat',[':id' =>$month_without_outlay_regexp,':cat'=>$cat]);
			$budget =  $command->queryOne();
			$total_budget_cat = $budget['total_budget_cat'];
						
			echo "total_budget_cat".$date."\n";
			echo($total_budget_cat);
			echo "\n";	


			$command = Yii::$app->db->createCommand('select distinct substring(dt1,1,7) as dt from budget where dt1 regexp :id and cat=:cat',[':id' =>$month_without_outlay_regexp,':cat'=>$cat]);
			$budgets =  $command->queryAll();
			$month_budget_without_outlay_regexp_exist_cat = '';
			foreach($budgets as $budget){
				$month_budget_without_outlay_regexp_exist_cat .= '|'.$budget['dt'];
			}
			$month_budget_without_outlay_regexp_exist_cat = ltrim($month_budget_without_outlay_regexp_exist_cat,'|');
			
			
			echo "month_budget_without_outlay_regexp_exist_cat".$date."\n";
			echo($month_budget_without_outlay_regexp_exist_cat);
			echo "\n";


			$month_outlay_regexp_cat ='';
			if ($months_list_before){
				$outlays = Yii::$app->db->createCommand('select distinct substring(dt1,1,7) as month_outlay_regexp_cat from outlay where dt1 regexp :id and cat=:cat', [':id' =>$months_list_before,':cat'=>$cat])->queryAll();
				foreach ($outlays as $outlay){
					$month_outlay_regexp_cat .= '|'.$outlay['month_outlay_regexp_cat'];
				}
			}
		
			$month_outlay_regexp_cat = mb_substr( $month_outlay_regexp_cat,1);
			echo "month_outlay_regexp_cat".$date."\n";
			echo($month_outlay_regexp_cat);
			echo "\n";


			$month_without_outlay_cat = str_replace($month_outlay_regexp_cat, '', $month_all_regexp);
			$month_without_outlay_cat = ltrim($month_without_outlay_cat,'|');
			$month_remain_cat = str_replace($month_budget_without_outlay_regexp_exist_cat, '', $month_without_outlay_cat);
			$month_remain_cat = ltrim($month_remain_cat,'|');
			echo "month_remain_cat".$date."\n";
			echo($month_remain_cat);
			echo "\n";


			$array = explode("|", $month_remain_cat);
			$month_remain_cat = count($array);
			echo "month_remain_cat".$date."\n";
			echo($month_remain_cat);
			echo "\n";


			$total_balance_cat = $total_template_cat - $total_budget_cat - $total_outlay_cat ;
			echo "total_balance_cat".$date."\n";
			echo($total_balance_cat);
			echo "\n";
			
			$total_balance_month_cat = $total_balance_cat/$month_remain_cat;
			echo "total_balance_month_cat".$date."\n";
			echo($total_balance_month_cat);
			echo "\n";	
				
				
				
				
				
				//Yii::info(VarDumper::dumpAsString($o));
		
			
			
			
			/* $command = Yii::$app->db->createCommand("select * from budget where cat=:cat and dt1 like concat(:id,'%')");
			$command->bindParam(':id', $date);	
			$command->bindParam(':cat', $cat);	
			$budgets = $command->queryAll();
				$total_summ_price = 0; 
				foreach ($budgets as $budget){
						$total_summ_price += isset($budget['price']) ? (int)$budget['price'] :null;  
				}
			$budget_price[$cat]	= $total_summ_price; */
		}
		
		//Yii::info(VarDumper::dumpAsString($months_balance));
		
		
			
	}
	
	
	public function actionGetbudgetoutlay($date_year)
	{
		echo $date_year."\n";
		$month_outlay_regexp ='';
	
		$outlays = Yii::$app->db->createCommand('select distinct substring(dt1,1,7) as month_outlay_regexp from outlay where dt1 like concat(:id,"%")', [':id' =>$date_year])->queryAll();
		foreach ($outlays as $outlay){
			$month_outlay_regexp .= '|'.$outlay['month_outlay_regexp'];
			
		}
		$month_outlay_regexp = mb_substr( $month_outlay_regexp,1);
		echo "month_outlay_regexp";
		print_r($month_outlay_regexp);
		echo "\n";
		$months_list = DateHelpers::getDateBeforeYearDateTimeForArray($date_year."-01-01");
		//echo "before".$date."\n";
		print_r($months_list);
		$months_list_after = DateHelpers::getDateAfterYearDateTimeForRegexp($date_year."-01-01");
			//echo "after with current month".$date."\n";//with current month
		
		echo "months_list_after";
		print_r($months_list_after);
		echo "\n";
		
		$outlay = Yii::$app->db->createCommand('select count(distinct dt1) as count_outlay_data_year from outlay where dt1 like concat(:id,"%")', [':id' =>$date_year])->queryOne();
		$count_outlay_data_year = $outlay['count_outlay_data_year'];
		echo 'count_outlay_data_year '.$count_outlay_data_year."\n";
		
		$command = Yii::$app->db->createCommand('select sum(price) as total_outlay from outlay where dt1 regexp :id',[':id' =>$month_outlay_regexp]);
		$outlay =  $command->queryOne();
		$total_outlay = $outlay['total_outlay'];
		echo "total_outlay ".$total_outlay."\n";
		
		$month_budget_regexp ='';
	
		$budgets = Yii::$app->db->createCommand('select distinct substring(dt1,1,7) as month_budget_regexp from budget where dt1 like concat(:id,"%")', [':id' =>$date_year])->queryAll();
		foreach ($budgets as $budget){
			$month_budget_regexp .= '|'.$budget['month_budget_regexp'];	
		}
		
		echo "month_budget_regexp ".$month_budget_regexp."\n";
	
		$budget = Yii::$app->db->createCommand('select count(distinct dt1) as count_budget_data_year from budget where dt1 like concat(:id,"%")', [':id' =>$date_year])->queryOne();
		$count_budget_data_year = $budget['count_budget_data_year'];
		echo 'count_budget_data_year '.$count_budget_data_year."\n";


		$command = Yii::$app->db->createCommand('select sum(price) as total_budget from budget where dt1 regexp :id',[':id' =>$month_budget_regexp]);
		$budget =  $command->queryOne();
		$total_budget = $budget['total_budget'];
		echo "total_budget ".$total_budget."\n";

		$total_summ = $total_budget + $total_outlay;

		echo "total_summ ".$total_summ."\n";
		$avg_summ = $total_summ/($count_outlay_data_year +$count_budget_data_year);
		echo $avg_summ."\n";


		
		
		
		
		
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
   
}
