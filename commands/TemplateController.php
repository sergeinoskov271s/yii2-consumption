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
use app\models\Template;
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

class TemplateController extends Controller
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
        $towhom = 'towhom '.$id;
        $forwhat = 'forwhat '.#id;
		$price = '2000';
        $cat ='cat'.$id;

        $this->add($towhom, $forwhat, $price, $cat);
    }
    protected function add($towhom, $forwhat,$price, $cat){
        try{
            $template = new template();
            $template->towhom = $towhom;
            $template->forwhat = $forwhat;
			$template->price = $price;
            $template->cat = $cat;
         
            if ($template->validate()) {
//					$product->setDefaultValues();
//					Yii::info(VarDumper::dumpAsString($product));
                $template->save();

                echo "template add $template->id \n";
            } else {
                $errors = $template->errors;
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
        $template = Template::findone($id);
        if($template == NULL){
            echo "not found template id $id\n";
            return ExitCode::OK;
        }

        $model = new Template();
        $attributes = $model->getAttributes();
        foreach(array_keys($attributes) as $key){
            echo "OldAttributes $key => ".$this->ansiFormat($template->getOldAttributes()[$key], Console::FG_YELLOW)."\n";
        }
        $query = new Query();
        $query
            ->select(['towhom', 'forwhat', 'cat'])
            ->from('template')
            ->where(['template.id'=>$id])
            ->orderBy('template.id DESC');
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
        $model = new Template();
        $templates = Template::find()
            ->select('template.*')
            ->all();
        $attributes_name = $model->getAttributes();
        $message = '';
        if(count($templates) !== 0){
            foreach ($templates as $template){
                foreach(array_keys($attributes_name) as $key){
                    echo "$key => ".$this->ansiFormat($template->getOldAttributes()[$key], Console::FG_YELLOW)."\n";
                }
            }
        }
        return ExitCode::OK;
    }
	
	public function actionGetcat() {//template/getcats 
		$command = Yii::$app->db->createCommand("call get_cats(:id)");
		$command->bindParam(':id', $id);
		$templates = $command->queryAll();
        $model_view = new View();
       
		$result = [];
		$columns=array('cat','main_count','total_summ',); 
		$count=count($columns); 
        if(count($templates) !== 0){
            foreach ($templates as $template){
				$model_view = new View();		
				$model_view->cat = $template['cat'];
				$model_view->main_count = $template['main_count'];
				$model_view->total_summ = $template['total_summ'];
				$result[] = $model_view; 	  
            }
        }		
		print_r($result[0]);
		echo $result[0]->total_summ."\n";
		echo number_format( $result[0]->total_summ, 2, ',', ' '), PHP_EOL;
		echo number_format( $result[0]->total_summ * 200, 2, ',', ' '), PHP_EOL;
		echo "\n";
//		$products = Product::find()->joinWith('photos', false, 'INNER JOIN')->all();
	//Yii::info(VarDumper::dumpAsString($templates));
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
	
    public function actionDeleteOne($id=1){//employee/delete-one <id>

        try{
            $model = template::findOne($id);
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

    public function actionIndex()
    {
        echo 'ok' . "\n";
   //     Yii::info('message log');
	//	Yii::debug('начало вычисления среднего дохода');
  //      Yii::info(VarDumper::dumpAsString(array('q'=>4)));
 //       echo Department::className()."\n";
        return ExitCode::OK;
    }
	
	public function actionGet_groupforwhatstowhomsfromcat_template($id='2025-10-01'){
		$command = Yii::$app->db->createCommand("call get_groupforwhatstowhomsfromcat_template(:id,:cat)");
		$cat='к';
		$command->bindParam(':id', $id);
		$command->bindParam(':cat', $cat);
		$templates = $command->queryAll();
        $model_view = new View();

		$result = [];
        if(count($templates) !== 0){
            foreach ($templates as $template){
				$model_view = new View();		
				$model_view->group_towhom = $template['group_towhom'];
				$model_view->group_summ = $template['group_summ'];
				$model_view->group_forwhat = $template['group_forwhat'];
				$result[] = $model_view; 	  
            
            }
        }		
		print_r($result);
	}
	
	public function actionGet_total_summ($id='test_cat'){
		$command = Yii::$app->db->createCommand("select * from template");
		$templates = $command->queryAll();
       
		$total_summ = 0;
        if(count($templates) !== 0){
            foreach ($templates as $template){
				if($template['monthly'] == 'yes'){
					$total_summ	 += $template['price'] * 12;
				} elseif($template['monthly'] == 'no') {
					$total_summ	 += $template['price'];
				}
            }
        }
		$total_summ	= $total_summ /12;
		print_r(round($total_summ));
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
   
}
