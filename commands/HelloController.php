<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use Yii;
use yii\base;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
		// Accessing the request object
        $request = \Yii::$app->request;

        // Getting command-line arguments (excluding the script name)
        $params = $request->getParams();
        echo "Command line parameters: " . implode(', ', $params) . "\n";
		Yii::info('ff');
		Yii::info(VarDumper::dumpAsString('test log from hello  controller'));
        return ExitCode::OK;
    }
	
	public function actionMerge(){
		$array1 = [];
		$array2 = [];
		
		$array1[] = ['cat'=>'a', 'gs'=>50];
		$array1[] = ['cat'=>'b', 'gs'=>150];
		
		$array2[] = ['cat'=>'a', 'gs1'=>500];
		$array2[] = ['cat'=>'b', 'gs1'=>600];
		$array2[] = ['cat'=>'c', 'gs1'=>700];
		
		//print_r($array2);
		
		
		
		
		$output = [];
		$keys = [];
		foreach($array2 as $ar){
			$keys[] = $ar['cat'];
		};
		//print_r($keys);	
		
		
		//print_r(array_merge($array1,$array2));	
		
		function merge($idKey, $mergeKeys, ...$data) {
			$merged = [];

			foreach (array_merge(...$data) as $item) {
				$id = $item[$idKey];
				//echo($id);
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
							print_r($item[$k]);
						  }
						}
					}
				}
				//print_r($merged);
				foreach ($mergeKeys as $k) {
					if(isset($item[$k])){
						//$merged[$id]['item'][$k][] = $item[$k];
					}
				}
				//print_r( array_column($merged, 'item'));
			}
			//print_r( array_column($merged, 'item'));
			return array_column($merged, 'item');
		}


		$merged = merge('cat', [ 'gs', 'gs1' ], $array1, $array2);
		print_r($merged);
		
		
	}
	
}
