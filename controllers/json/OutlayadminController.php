<?php

namespace app\controllers\json;
use app\components\validators\OutlayValidator;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\d99b\Exception;
use yii\helpers\Console;
use Yii;
use yii\base;
use yii\db\Query;
use yii\helpers\VarDumper;
use app\models\Outlay;
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
use yii\helpers\Json;

/**
 * Class OutlayController
 * @package app\controllers
 */
class OutlayadminController extends \yii\web\Controller
{
    public function beforeAction($action)
    { 
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
	public function behaviors()
    {
        $behaviors = parent::behaviors();
       /*  $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className(),
        ];
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ]; */
        return $behaviors;
    } 
    /**
     * @return array|string|Response
     * @throws base\InvalidConfigException
     */
    public function actionIndex()
    {
		
    }

	
	public function actionAjaxhandler()
    {
        // Set the response format to JSON
        //$query = Outlay::find()->select('cat')->distinct()->count();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post(); // Get POST data
            // Or use $data = Yii::$app->request->get(); for GET requests

            // Access specific parameters:
            $orderValue = $data['order'] ?? 'cat';
            //$param2 = $data['param2'] ?? null;
			$array = array();
			//Yii::info(VarDumper::dumpAsString($orderValue));
			$model = new Outlay();
			if($orderValue === 'cat'){				
				$outlays = Outlay::find()
					->select('outlay.cat')
					->distinct()
					->orderBy('outlay.cat')
					->all();				
				if(count($outlays) !== 0){
					foreach ($outlays as $outlay){
						$array[$outlay['cat']] = $model->getAttributeLabel($outlay['cat']);
					}
				}					
			} elseif($orderValue == 'towhom'){
				$outlays = Outlay::find()
					->select('outlay.towhom')
					->distinct()
					->orderBy('outlay.towhom')
					->all();
				if(count($outlays) !== 0){
					foreach ($outlays as $outlay){
						$array[$outlay['towhom']] = $model->getAttributeLabel($outlay['towhom']);
					}
				}							
			} elseif($orderValue == 'forwhat'){
				$outlays = Outlay::find()
					->select('outlay.forwhat')
					->distinct()
					->orderBy('outlay.forwhat')
					->all();

				if(count($outlays) !== 0){
					foreach ($outlays as $outlay){
						$array[$outlay['forwhat']] = $model->getAttributeLabel($outlay['forwhat']);
					}
				}							
				
			} else {
				$array = array('order'=>'not data'.$orderValue);
				
			}
		
            // Perform your logic here based on the received data
            //$result = ['order' =>  $orderValue];
			//Yii::info(VarDumper::dumpAsString($array));
			//Yii::$app->response->format = Response::FORMAT_JSON;
            return  \yii\helpers\Json::encode($array);// Return data as JSON			
        }
        // If not an AJAX request, you might want to handle it differently or throw an exception
        return ['error' => 'This action only accepts AJAX requests.'];
    }
	
    public function actionInfo()
    {
       Yii::info('--------------info');
        Yii::info(VarDumper::dumpAsString(Yii::$app->getRequest()->getBodyParams()));
 //       $body = json_decode(Yii::$app->getRequest()->getRawBody(), true);
        $body = Yii::$app->getRequest()->getBodyParams();
//        Yii::info(VarDumper::dumpAsString(Yii::$app->getRequest()->getRawBody(), true));
//        Yii::info(VarDumper::dumpAsString($body));
        if (Yii::$app->request->isAjax){
            Yii::info('------ajax');
        }
        if (Yii::$app->request->isAjax){
            if (Yii::$app->request->post() ){
                $uid = array();
				$uid['id'] = 'hello6';
				$uid['title'] = 'hello7';
			
                return \Yii::createObject([
                    'class' => 'yii\web\Response',
                    'format' => \yii\web\Response::FORMAT_JSON,
					'data' => $uid
                    /* 'data' => [
                        'message' => 'hello world',
                        'code' => 1030,
						'message1' => 'hello world5',
                    ], */
                ]);
            }
        }
        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'message' => 'hello world',
                'code' => 100
            ],
        ]);
    }
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

}
