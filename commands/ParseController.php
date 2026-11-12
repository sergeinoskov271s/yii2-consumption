<?php
declare(strict_types=1);

namespace app\commands;
use app\common\components\CreateOrgunit;
use app\common\components\UriGetHelpers;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Console;
use Yii;
use yii\base;
//use yii\db\Query;
use yii\helpers\VarDumper;
use app\models\Outlay;
use yii\db\IntegrityException;

/**
 * Class ParseController
 * @package app\commands
 */
class ParseController extends Controller
{
    
    public function options($actionID)
    {
        return ['id','file'];
    }

    /**
     * Returns option alias names.
     * Child classes may override this method to specify alias options.
     *
     * @return array the options alias names valid for the action
     * where the keys is alias name for option and value is option name.
    public function optionAliases()
    {
        return ['id' => 'id','file'=>'file'];
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * Child classes may override this method to specify the behaviors they want to behave as.
     *
     * The return value of this method should be an array of behavior objects or configurations
     * indexed by behavior names. A behavior configuration can be either a string specifying
     * the behavior class or an array of the following structure:
     *
     * ```php
     * 'behaviorName' => [
     *     'class' => 'BehaviorClass',
     *     'property1' => 'value1',
     *     'property2' => 'value2',
     * ]
     * ```
     *
     * Note that a behavior class must extend from [[Behavior]]. Behaviors can be attached using a name or anonymously.
     * When a name is used as the array key, using this name, the behavior can later be retrieved using [[getBehavior()]]
     * or be detached using [[detachBehavior()]]. Anonymous behaviors can not be retrieved or detached.
     *
     * Behaviors declared in this method will be attached to the component automatically (on demand).
     *
     * @return array the behavior configurations.
     */
    public function behaviors()
    {
        return [
           /* [
                ’class’ => ’yii\filters\HttpCache’,
                ’only’ => [’index’, ’view’],
                ’lastModified’ => function ($action, $params) {
                    $q = new \yii\db\Query();
                    return $q->from(’user’)->max(’updated_at’);
                },
            ],*/
        ];
    }

    protected function addone($dt1,$towhom, $forwhat,$price, $cat){
        try{
            $outlay = new Outlay();
//				$attributes = array_keys($photo->getAttributes());
            $outlay->towhom = preg_replace("/\s+/", "", $towhom);
            $outlay->forwhat = preg_replace("/\s+/", "", $forwhat);
            $outlay->cat = $cat;
            $outlay->dt1 = $dt1;
			$outlay->price = $price;
           
         //   $employee->updated_at = Yii::$app->formatter->asDate($updated_at, 'yyyy-MM-dd  HH::mm::ss');
         //   $date = Yii::$app->formatter->asDate($updated_at, 'yyyy-MM-dd');
        //    list($year, $month, $day) = explode('-', $date);
        //    $format = "Y-m-d  H::m::s";
            if ($outlay->validate()) {
//					$product->setDefaultValues();
//					Yii::info(VarDumper::dumpAsString($product));
                $outlay->save();

           //     echo "employee add $employee->id \n";
            } else {
                $errors = $outlay->errors;
                foreach($errors as $key=>$value){
                    echo "$key => $value[0]\n";
                }
                /* $message_error = $this->ansiFormat($errors['path_fullSize'][0], Console::BOLD);
                echo $message_error."\n";
                Yii::info(VarDumper::dumpAsString($attributes)); */
            }

        } catch(IntegrityException $error){
            Yii::info(VarDumper::dumpAsString($error));
//				echo $e->getCode();
//				echo $e->getMessage()."\n";
            $message_error = $this->ansiFormat($error->errorInfo[2], Console::BOLD);
            echo $message_error."\n";
        }
    }

    /**
     * @return int
     * @throws \Throwable
     */
    
    public function actionRaskhod($id=''){
    	$path_file = Yii::$app->basePath.'/runtime/upload/month/raskhod-'.$id.'.csv';
        echo "file search $path_file\n";

        $org_unit_array = [];
        $row = 0;
        if(file_exists ( $path_file)){
			if (($handle = fopen( $path_file, "r")) !== FALSE){
				//Yii::$app->db->createCommand()->truncateTable('outlay')->execute();
				while (($line = fgets($handle)) !== FALSE) {
					$data = str_getcsv($line, separator: '	', enclosure: '"', escape: "\\");
					//print_r($data);
					if($row < 1){
                    	$row++;
                    	continue;
                    }
                    $dt1 = $data[0];
                    $towhom = trim($data[1]);
                    $forwhat = trim($data[2]);
					$price = trim($data[3]);
                    $cat = trim($data[4]);
                    $this->addOne($dt1,$towhom, $forwhat,$price, $cat);
                    $row++;
				}
                fclose($handle);
            }
        } else {
            echo "file not found $path_file\n";
            return ExitCode::OK;
        }
  //      Yii::info(VarDumper::dumpAsString($employees));      
    }

    public function actionAddraskhod($id=''){//id - filename exclude ext
    	$path_file = Yii::$app->basePath.'/runtime/upload/month/'.$id.'.csv';
        echo "file search $path_file\n";

        $org_unit_array = [];
        $row = 0;
        if(file_exists ( $path_file)){
			if (($handle = fopen( $path_file, "r")) !== FALSE){
				while (($line = fgets($handle)) !== FALSE) {
					$data = str_getcsv($line, separator: '	', enclosure: '"', escape: "\\");
					//print_r($data);
					//if($row < 1){
                    //	$row++;
                    //	continue;
                    //}
                    $dt1 = $data[0];
                    $towhom = trim($data[1]);
                    $forwhat = trim($data[2]);
					$price = trim($data[3]);
                    $cat = trim($data[4]);
                    $this->addOne($dt1,$towhom, $forwhat,$price, $cat);
                    $row++;
				}
                fclose($handle);
            }
        } else {
            echo "file not found $path_file\n";
            return ExitCode::OK;
        }
  //      Yii::info(VarDumper::dumpAsString($employees));      
    }

	
	
    public function actionIndex()
    {
        echo 'index action ok' . "\n";
      /*  Yii::info('message log');
        Yii::info(VarDumper::dumpAsString(array('q'=>4)));
        echo Department::className()."\n";*/
        return ExitCode::OK;
    }
	
	public function actionTestcsv(){
		$path_file = Yii::$app->basePath.'/data/python/rashod.csv';
		 if(file_exists ( $path_file)){
			if (($handle = fopen( $path_file, "r")) !== FALSE){
				/* 
                while (($data = str_getcsv($handle, separator: '	', enclosure: '', escape: "\\")) !== FALSE) {
                    
                    if($row < 1){
                    	$row++;
                    	continue;
                    }
                    echo $data[1]."\n";
                    
                    $row++;
                } */
				while (($line = fgets($handle)) !== FALSE) {
					$data = str_getcsv($line, separator: '	', enclosure: '"', escape: "\\");
					print_r($data);
				}
                fclose($handle);
            }
        } else {
            echo "file not found $path_file\n";
            return ExitCode::OK;
        }
	}
}
