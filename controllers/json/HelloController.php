<?php

namespace app\controllers\json;
use yii\db\Query;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\helpers\VarDumper;
use yii\filters\Cors;
use Yii;

class HelloController extends \yii\web\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className(),
        ];
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];
        
        return $behaviors;
    }
    public function beforeAction($action)
    {
//		if ($action->id == 'my-method') {
        $this->enableCsrfValidation = false;
//		}

        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax){
            if (Yii::$app->request->post() ){
                Yii::info(VarDumper::dumpAsString(Yii::$app->request->post()));
                Yii::info(VarDumper::dumpAsString($_POST)); // []
                Yii::info(VarDumper::dumpAsString(Yii::$app->request->csrfParam));
                Yii::info(VarDumper::dumpAsString(Yii::$app->request->csrfToken));

//				$id = \yii\helpers\Json::decode(Yii::$app->request->post());
 //               $data = Product::findOne(Yii::$app->request->post()['id']);
//				Yii::info(VarDumper::dumpAsString($data->photos));
                return ['message'=>'hello'];
            }
        }

//        $data = Product::findOne($id);
  //      return $data;
        return $this->render('index');
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
				$search = Yii::$app->request->post()['search'];
			 
				/* $json_data = array();
				$uid = array();	
				$uid['0'] = 'hello';
				$uid['1'] = 'hello5'; 
				$json_data = array('data'=>$uid); */
				// Perform your logic here based on the received data
				//$result = ['order' =>  $orderValue];
				//Yii::info(VarDumper::dumpAsString($data));
				//Yii::$app->response->format = Response::FORMAT_JSON;
				//return  \yii\helpers\Json::encode($json_data);// Return data as JSON
                
				return \Yii::createObject([
                    'class' => 'yii\web\Response',
                    'format' => \yii\web\Response::FORMAT_JSON,
                    'data' => [
                        'message' => 'hello-world',
						'message1' => 'hello-world5',
						'message4' => 'hello-world4',
                        'code' => 1030,
                        'search'=> $search
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
