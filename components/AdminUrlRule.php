<?php

namespace app\components;

use Yii;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use yii\helpers\VarDumper;
use app\models\Employee;

/**
 * Class AdminUrlRule
 * @package app\components
 */
class AdminUrlRule extends BaseObject  implements UrlRuleInterface
{

    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|bool the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)//create route for Html::a('Click Me', ['news/items-list', 'year' => 2015])
    {//		
		/* if ($route !== 'news/items-list') {
          return false;
		} */
        //If a parameter is defined and not empty - add it to the URL
        $url = 'admin/';
        if ($route === 'admin'){
//			Yii::info(VarDumper::dumpAsString($params));
            if (array_key_exists('controller', $params) && !empty($params['controller'])) {
                $url .= $params['controller'].'/';
            }
            if (array_key_exists('action', $params) && !empty($params['action'])) {
                $url .= $params['action'].'/';
            }
            if (array_key_exists('id', $params) && !empty($params['id'])) {
                $url .= $params['id'];
            }
            return $url;
        }
       /*  if (array_key_exists('subcategory', $params) && !empty($params['subcategory'])) {
            $url .= ',' . $params['subcategory'];
        } */
        return false;  // this rule does not apply
    }

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request)//for controller
    {
        /** @var string $pathInfo */
        $pathInfo = Yii::$app->request->pathInfo;
//		Yii::info(VarDumper::dumpAsString($pathInfo));
		/* $route = Route::findRouteByUrl($pathInfo);
            if (!$route) {
                return false;
            } */
		/* if (preg_match('%^product/$%', $pathInfo, $matches)) {
			return ['product/view'];
		} */
        if (preg_match('%^admin\/(?P<controller>[A-Za-z-]+)\/(?P<action>[A-Za-z-]+)\/(?P<id>[0-9]+)?%', $pathInfo, $matches)) {
 //           Yii::info(VarDumper::dumpAsString($matches));
            if(!isset($matches['action'])){
                return false;
            }
            if(!in_array($matches['action'],['create','select','index','view','grid','update','delete'])){
                return false;
            }

            return ['admin/'.$matches['controller'].'/'.$matches['action'],
                [
                    'id'=>$matches['id'],
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
        return false;  // this rule does not apply
    }
}
