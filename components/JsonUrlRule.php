<?php

namespace app\components;

use Yii;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use yii\helpers\VarDumper;
use app\models\Product;

class JsonUrlRule extends BaseObject  implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)//create route for Html::a('Click Me', ['news/items-list', 'year' => 2015])
    {//		
		/* if ($route !== 'news/items-list') {
          return false;
		} */
        //If a parameter is defined and not empty - add it to the URL
        $url = 'json/';
		if ($route === 'json'){
			if (array_key_exists('controller', $params) && !empty($params['controller'])) {
				$url .= $params['controller'];
			}
			if (array_key_exists('action', $params) && !empty($params['action'])) {
				$url .= '/'.$params['action'];
			}
			/*if (array_key_exists('id', $params) && !empty($params['id'])) {
				$url .= '/'.$params['id'];
			}*/
			return $url;
		}
       /*  if (array_key_exists('subcategory', $params) && !empty($params['subcategory'])) {
            $url .= ',' . $params['subcategory'];
        } */
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)//for controller
    {
        $pathInfo = Yii::$app->request->getPathInfo();
		//Yii::info(VarDumper::dumpAsString($pathInfo));
		$url = Yii::$app->request->url;
		//Yii::info(VarDumper::dumpAsString($url));
        if (preg_match('%^\/yii2-consumption\/json\/(?P<controller>[A-Za-z-]+)\/(?P<action>[A-Za-z]+)%', $url, $matches)) {
			Yii::info(VarDumper::dumpAsString($matches));
			if(!isset($matches['controller'])){
				return false;
			}

			/*if(!in_array($matches['controller'], array('help'))){
				return false;
			}*/
			if(!isset($matches['action'])){
				return false;
			}
			//return ['json/hello/info',[]];
			return ['json/'.$matches['controller'].'/'.$matches['action'],[]];
            
			/*if(!isset($matches['id'])){
				return false;
			}*/
			/* $category = Product::find()->where(['uri' => $matches['url']])->one();
			if($category == NULL)
				return false; */
//			Yii::info(VarDumper::dumpAsString($matches));
           /*  $route = Route::findRouteByUrl($matches['url']);
            if (!$route) {
                return false;
            }

            $params = $route['params'];
            $params['url'] = $route['url']; */
//			return ['json/json-product/index'];
//			return ['json/'.$matches['controller'].'/'.$matches['action'],['id'=> $matches['id']]];
        }
		
       /*  if (preg_match('%^json/(?P<controller>[A-Za-z-]+)\/(?P<action>[A-Za-z]+)?%', $pathInfo, $matches)) {			
			if(!isset($matches['controller'])){
				return false;
			}
			if(!in_array($matches['controller'], array('json-product','json-user'))){
				return false;
			}
			if(!isset($matches['action'])){
				return false;
			}
			return ['json/'.$matches['controller'].'/'.$matches['action']];
        } */
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
        return false;  // this rule does not apply
    }
}
