<?php

namespace app\components;

use Yii;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use yii\helpers\VarDumper;
use app\models\Budget;

class TemplateUrlRule extends BaseObject  implements UrlRuleInterface
{
	public function init()
    {
   /*       $this->pattern = 'budget';
        $this->route = 'budget';  */
        parent::init();
    }

    public function createUrl($manager, $route, $params)//create route for Html::a('Click Me', ['news/items-list', 'year' => 2015])
    {		
        //If a parameter is defined and not empty - add it to the URL
		//Yii::info('UrlRuleInterface'.'route'.$route);
        $url = 'template/';
        if ($route === 'template'){
			//Yii::info(VarDumper::dumpAsString($params));
			//if ($route === 'budget/index') {
           //     return 'main/'.$myCurrentController.'/index'; 
			
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
		//Yii::info('queryString '.$queryString);
		$pathInfo = Yii::$app->request->pathInfo;
		//Yii::info(VarDumper::dumpAsString($pathInfo)); 
		//Yii::info('url '.$url);
//		Yii::info('param '.VarDumper::dumpAsString($manager));
		if (preg_match('%^template\/(?P<action>[A-Za-z-]+)\/(?P<id>[0-9]+)?%',$pathInfo, $matches)) 
		{
		//	Yii::info('match');
        //    Yii::info(VarDumper::dumpAsString($matches));
            if(!isset($matches['action'])){
                return false;
            }
            if(!in_array($matches['action'],['index','create','view','select','update','delete'])){
                return false;
            }
			
            return ['template/'.$matches['action'],
				[
					'id'=>$matches['id'],
					//'order'=>$matches['order'],
					//'date'=>isset($matches['date']) ? $matches['date'] : '2025-11-01',
					//'date'=>$matches['date'],
					//'pole'=> isset($matches[5]) ? $matches[5] : '',
				]
            ];
        }
		
		if (preg_match('%^\/yii2-consumption\/template%',$pathInfo))
		{
			return ['template/index',[]];		
		}

        return false;  // this rule does not apply
    }
}
