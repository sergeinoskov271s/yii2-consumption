<?php

namespace app\components;

use Yii;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use yii\helpers\VarDumper;
use app\models\Employee;

class JsonOutlayUrlRule extends BaseObject  implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)//create route for Html::a('Click Me', ['news/items-list', 'year' => 2015])
    {//		
		/* if ($route !== 'news/items-list') {
          return false;
		} */
        //If a parameter is defined and not empty - add it to the URL
        $url = 'json/outlay/';
        if ($route === 'json/outlay'){
			Yii::info(VarDumper::dumpAsString($params));
            if (array_key_exists('action', $params) && !empty($params['action'])) {
                $url .= $params['action'].'/';
            }
            /* if (array_key_exists('enterprise', $params) && !empty($params['enterprise'])) {
                $url .= $params['enterprise'].'/';
            } */

            return $url;
        }
       /*  if (array_key_exists('subcategory', $params) && !empty($params['subcategory'])) {
            $url .= ',' . $params['subcategory'];
        } */
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)//for controller
    {
       // $pathInfo = $request->getPathInfo();
		//Yii::info(VarDumper::dumpAsString($pathInfo));
		$url = Yii::$app->request->url;
		Yii::info(VarDumper::dumpAsString($url));
		/* $route = Route::findRouteByUrl($pathInfo);
            if (!$route) {
                return false;
            } */
		/* if (preg_match('%^product/$%', $pathInfo, $matches)) {
			return ['product/view'];
		} */
        if (preg_match('%^json\/outlay\/(?P<action>[A-Za-z-]+)%', $url, $matches)) {
            if(!isset($matches['action'])){
                return false;
            }
            if(!in_array($matches['action'],['create','select','index','view','info','ajaxhadler'])){
                return false;
            }

           /* $employee = Employee::find()->where(['id' => $matches['id']])->one();

            if($employee == NULL)
                return false;*/
            return ['json/outlay/'.$matches['action'],
                [
                    //'id'=>$matches['enterprise'],

                ]
            ];
        }
		/* if (preg_match('%^([^\/]*)\/([^\/]*)$%', $pathInfo, $matches)) {
			if($matches[1] == 'news')
			{
				$params = [ 'title' => $matches[2]];
				return ['news/item-detail', $params];
			}
			else
			{
				return false;
			}
		} */
		return ['json/hello/info'];
        return false;  // this rule does not apply
    }
}
