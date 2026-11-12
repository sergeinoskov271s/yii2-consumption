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
use app\models\Template;
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
class TemplateController extends \yii\web\Controller
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
		return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'message' => 'from index',
                'code' => 100
            ],
        ]);
    }
	//** [{"cat":"cat"}]
	public function actionGetcats()
    {
		$rows = Template::find()
			->select(['cat'])
			->distinct()
			->all();
		$command = Yii::$app->db->createCommand("select distinct cat from template");
		$uid = array();
		$rows = $command->queryAll();
		for($i=0; $i<count($rows);$i++){
			$uid[$i] = $rows[$i]['cat'];
		}
		
		//Yii::info(VarDumper::dumpAsString($rows));
		return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => $uid,
        ]);
    }
	public function actionGettotalcat()
    {
		
		if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            //if (\Yii::$app->request->post()){
				
				$post = Yii::$app->request->post();
				$cat = $post['param2'];
				//Yii::info(VarDumper::dumpAsString($cat));
				$command = Yii::$app->db->createCommand("select sum(price) as total_month_template_cat from template where cat=:cat");
				$command->bindParam(':cat', $cat);
				$template =  $command->queryOne();
				$total_month_template_cat = $template['total_month_template_cat'];
				$total_template_cat = $total_month_template_cat * 12;				
				//Yii::info(VarDumper::dumpAsString($template));	
				return \Yii::createObject([
					'class' => 'yii\web\Response',
					'format' => \yii\web\Response::FORMAT_JSON,
					'data' => ['total_template_cat'=>$total_template_cat],
				]);
			//}
		}
		return \Yii::createObject([
					'class' => 'yii\web\Response',
					'format' => \yii\web\Response::FORMAT_JSON,
					'data' => ['error'=>'This action only accepts AJAX requests'],
				]);
		
		
    }
	public function actionGettotalmonthcat()
    {
		
		if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
				$post = Yii::$app->request->post();
				$cat = $post['param2'];
				//Yii::info(VarDumper::dumpAsString($cat));
				$command = Yii::$app->db->createCommand("select sum(price) as template_cat from template where cat=:cat");
				$command->bindParam(':cat', $cat);
				$template =  $command->queryOne();
				$template_cat = $template['template_cat'];
				//$total_cat = $total_month_template_cat * 12;				
				//Yii::info(VarDumper::dumpAsString($template));	
				return \Yii::createObject([
					'class' => 'yii\web\Response',
					'format' => \yii\web\Response::FORMAT_JSON,
					'data' => ['template_cat'=>$template_cat],
				]);
		}
		return \Yii::createObject([
					'class' => 'yii\web\Response',
					'format' => \yii\web\Response::FORMAT_JSON,
					'data' => ['error'=>'This action only accepts AJAX requests'],
				]);
		
		
    }
	
	public function actionGettotalmonth()
    {
		$command = \Yii::$app->db->createCommand("select sum(price) as total_month from template");
		
		$template =  $command->queryOne();
		$total_month = $template['total_month'];				
				
		return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => ['total_month'=>$total_month],
        ]);
    }
	
	
	
	public function actionAjaxhandler()
    {
        // Set the response format to JSON
        $query = Outlay::find()->select('cat')->distinct()->count();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post(); // Get POST data
            // Or use $data = Yii::$app->request->get(); for GET requests

            // Access specific parameters:
            $orderValue = $data['order'] ?? 'cat';
            $param2 = $data['param2'] ?? null;
			//Yii::info(VarDumper::dumpAsString($param2));
			$array = array();
			
			$model = new Template();
			if($orderValue === 'cat'){				
				$templates = Template::find()
					->select('template.cat')
					->distinct()
					->orderBy('template.cat')
					->all();				
				if(count($templates) !== 0){
					foreach ($templates as $template){
						$array[$template['cat']] = $model->getAttributeLabel($template['cat']);
					}
				}					
			}  elseif($orderValue === 'groupcat'){
				$templates = Template::find()
					->select('template.cat')
					->orderBy('template.cat')
					->all();				
				if(count($templates) !== 0){
					foreach ($templates as $template){
						$array[$template['cat']] = $model->getAttributeLabel($template['cat']);
					}
				}			
				
			} elseif($orderValue == 'towhom'){
				$templates = Template::find()
					->select('template.towhom')
					->distinct()
					->orderBy('template.towhom')
					->all();
				if(count($templates) !== 0){
					foreach ($templates as $template){
						$array[$template['towhom']] = $model->getAttributeLabel($template['towhom']);
					}
				}							
			} elseif($orderValue == 'forwhat'){
				$templates = Template::find()
					->select('template.forwhat')
					->distinct()
					->orderBy('template.forwhat')
					->all();

				if(count($templates) !== 0){
					foreach ($templates as $template){
						$array[$template['forwhat']] = $model->getAttributeLabel($template['forwhat']);
					}
				}							
				
			} elseif($orderValue == 'price'){
				$templates = Template::find()
					->select('template.price')
					->distinct()
					->orderBy('template.price')
					->all();

				if(count($templates) !== 0){
					foreach ($templates as $template){
						$array[$template['price']] = $model->getAttributeLabel($template['price']);
					}
				}			
				 
				
			} else {
				$array = array('order'=>'not data'.$orderValue);
				
			}
		
            // Perform your logic here based on the received data
            //$result = ['order' =>  $orderValue];
			//Yii::info(VarDumper::dumpAsString($data));
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

        /*if ($body){
            return \Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_JSON,
                'data' => [
                    'message' => 'hello world',
                    'id' => $body['id'],

                ],
            ]);
        }*/
        if (Yii::$app->request->isAjax){
            if (Yii::$app->request->post() ){
                /* Yii::info(VarDumper::dumpAsString(Yii::$app->request->post()));
                Yii::info(VarDumper::dumpAsString($_POST)); // []
                Yii::info(VarDumper::dumpAsString(Yii::$app->request->csrfParam));
                Yii::info(VarDumper::dumpAsString(Yii::$app->request->csrfToken)); */

//				$id = \yii\helpers\Json::decode(Yii::$app->request->post());
                //               $data = Product::findOne(Yii::$app->request->post()['id']);
//				Yii::info(VarDumper::dumpAsString($data->photos));
              $id = Yii::$app->request->post()['id'];
                return \Yii::createObject([
                    'class' => 'yii\web\Response',
                    'format' => \yii\web\Response::FORMAT_JSON,
                    'data' => [
                        'message' => 'hello world',
                        'code' => 1030,
                        'id'=> $id
                    ],
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
