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
use app\models\Outlay;
use app\models\SearchForm;
use app\models\View;
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

class OutlayController extends Controller
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
    
    public function actionAddone($date, $id){
        $dt1 = $date;
        $towhom = 'towhom '.$id;
        $forwhat = 'forwhat '.#id;
		$price = '2000';
        $cat ='cat'.$id;

        $this->add($dt1,$towhom, $forwhat, $price, $cat);
    }
    protected function add($dt1,$towhom, $forwhat,$price, $cat){
        try{
            $outlay = new Outlay();
            $outlay->towhom = $towhom;
            $outlay->forwhat = $forwhat;
			$outlay->price = $price;
            $outlay->cat = $cat;
            $outlay->dt1 = $dt1;
      
            if ($outlay->validate()) {
//					$product->setDefaultValues();
//					Yii::info(VarDumper::dumpAsString($product));
                $outlay->save();

                echo "outlay add $outlay->id \n";
            } else {
                $errors = $outlay->errors;
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
        $outlay = Outlay::findone($id);
        if($outlay == NULL){
            echo "not found outlay id $id\n";
            return ExitCode::OK;
        }

        $model = new Outlay();
        $attributes = $model->getAttributes();
        foreach(array_keys($attributes) as $key){
            echo "OldAttributes $key => ".$this->ansiFormat($outlay->getOldAttributes()[$key], Console::FG_YELLOW)."\n";
        }
        $query = new Query();
        $query
            ->select(['towhom', 'forwhat', 'cat','dt1'])
            ->from('outlay')
            ->where(['outlay.id'=>$id])
            ->orderBy('outlay.id DESC');
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

    public function actionGetall() {//product/get-all
        $model = new Outlay();
        $outlays = Outlay::find()
            ->select('outlay.*')
            ->all();
        $attributes_name = $model->getAttributes();
        $message = '';
        if(count($outlays) !== 0){
            foreach ($outlays as $outlay){
                foreach(array_keys($attributes_name) as $key){
                    echo "$key => ".$this->ansiFormat($outlay->getOldAttributes()[$key], Console::FG_YELLOW)."\n";
                }
            }
        }
        return ExitCode::OK;
    }
	
	public function actionGetcat($id='2025-10-01') {//outlay/getcats '2025-10-01'
		//$query = new Query;
		$command = Yii::$app->db->createCommand("call get_cats(:id)");
		$command->bindParam(':id', $id);
		$outlays = $command->queryAll();
        $model_view = new View();
        // $outlays = Outlay::find()
            // ->select('outlay.cat')
			// ->where(['outlay.dt1'=>$id])
			// ->distinct()
			// ->orderBy('outlay.cat')
            // ->all();
        //$attributes_name = $model->getAttributeLabels();

		$result = [];
		$columns=array('cat','main_count','total_summ',); 
		$count=count($columns); 
        if(count($outlays) !== 0){
            foreach ($outlays as $outlay){
				$model_view = new View();		
				$model_view->cat = $outlay['cat'];
				$model_view->main_count = $outlay['main_count'];
				$model_view->total_summ = $outlay['total_summ'];
				$result[] = $model_view; 	  
            }
        }		
		print_r($result[0]);
		echo $result[0]->total_summ."\n";
		echo number_format( $result[0]->total_summ, 2, ',', ' '), PHP_EOL;
		echo number_format( $result[0]->total_summ * 200, 2, ',', ' '), PHP_EOL;
		echo "\n";
//		$products = Product::find()->joinWith('photos', false, 'INNER JOIN')->all();
	//Yii::info(VarDumper::dumpAsString($outlays));
//		Yii::info(VarDumper::dumpAsString(\Yii::$app->params['thumbnail.size']));//from params config
		$myArray = [
		'item1' => 'value1',
		'item2' => 'value2',
		];
		$jsonString = Json::encode($myArray);
		//echo $jsonString."\n";
		$arr1 = array(1,2,3);
			$var1 = json_encode($arr1, JSON_FORCE_OBJECT);
		//echo $var1."\n";
        return ExitCode::OK;
    }
	
	public function actionGetmonthsfromyear($id='2025'){
		$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count from outlay  where dt1 like concat(:id,'%')");
		$command->bindParam(':id', $id);
		$outlays = $command->queryAll();
		echo $outlays[0]['month_count']."\n";
	}
	
	public function actionGetavgcatsfromyear($id='2025'){
		$command = Yii::$app->db->createCommand("select count(distinct month(dt1)) as month_count from outlay  where dt1 like concat(:id,'%')");
		$command->bindParam(':id', $id);
		$outlays = $command->queryAll();
		$month_count = $outlays[0]['month_count'];
		echo $outlays[0]['month_count']."\n";
		$command = Yii::$app->db->createCommand("call get_cats_year(:id)");
		$command->bindParam(':id', $id);
		$outlays = $command->queryAll();
        $model_view = new View();

		$result = [];
        if(count($outlays) !== 0){
            foreach ($outlays as $outlay){
				$model_view = new View();		
				$model_view->cat = $outlay['cat'];
				$model_view->main_count = $outlay['main_count'];
				$model_view->total_summ = $outlay['total_summ'];
				$model_view->avg_summ = round($outlay['total_summ']/$month_count);
				$result[] = $model_view; 	  
            
            }
        }		
		print_r($result);
	}
	
    public function actionTestexistdate($id='2025'){//employee/delete-one <id>

        try{
            $model = new Outlay();
			$outlay = Outlay::find()
            ->where(['dt1','=',$id, false])
            ->one();
            if($model){ 
                echo "model with ".$id." exist\n";
            } else {
                echo "not model with dt1 => ".$id;
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
	
	public function actionDeleteOne($id=1){//employee/delete-one <id>

        try{
            $model = Outlay::findOne($id);
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

	public function actionDeletedate($id){
		try{
			$outlays = Outlay::find()
            ->where(['like','dt1',$id."%", false])
            ->all();
			foreach ($outlays as $outlay){
				echo $outlay->id." delete "."\n";
				$outlay->delete();
				
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
	public function actionGet_groupforwhatstowhomsfromcat_outlay($id='2025-10-01'){
		$command = Yii::$app->db->createCommand("call get_groupforwhatstowhomsfromcat_outlay(:id,:cat)");
		$cat='к';
		$command->bindParam(':id', $id);
		$command->bindParam(':cat', $cat);
		$outlays = $command->queryAll();
        $model_view = new View();

		$result = [];
        if(count($outlays) !== 0){
            foreach ($outlays as $outlay){
				$model_view = new View();		
				$model_view->group_towhom = $outlay['group_towhom'];
				$model_view->group_summ = $outlay['group_summ'];
				$model_view->group_forwhat = $outlay['group_forwhat'];
				$result[] = $model_view; 	  
            
            }
        }		
		print_r($result);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
   
}
