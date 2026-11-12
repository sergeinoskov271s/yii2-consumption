<?php

namespace app\components;

use Yii;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use yii\helpers\VarDumper;
use app\models\Outlay;

class OutlayUrlRule extends BaseObject  implements UrlRuleInterface
{
	public function init()
    {
   /*       $this->pattern = 'outlay';
        $this->route = 'outlay';  */
        parent::init();
    }

    public function createUrl($manager, $route, $params)//create route for Html::a('Click Me', ['news/items-list', 'year' => 2015])
    {		
        //If a parameter is defined and not empty - add it to the URL
		//Yii::info('UrlRuleInterface'.'route'.$route);
        $url = 'outlay/';
        if ($route === 'outlay'){
			//Yii::info(VarDumper::dumpAsString($params));
			//if ($route === 'outlay/index') {
           //     return 'main/'.$myCurrentController.'/index'; 
			
            if (array_key_exists('action', $params) && !empty($params['action'])) {
                $url .= $params['action'].'/';
            }
            if (array_key_exists('order', $params) && !empty($params['order'])) {
                $url .= $params['order'].'/';
            }
            if (array_key_exists('date', $params) && !empty($params['date'])) {
                $url .= $params['date'];
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
		if (preg_match('%^outlay\/(?P<action>[A-Za-z-]+)\/(?P<order>[A-Za-z-0-9]+)\/(?P<date>[A-Za-z-0-9]+)?(/(\S+)?)?%',$pathInfo, $matches)) 
		{
		//	Yii::info('match');
        //    Yii::info(VarDumper::dumpAsString($matches));
            if(!isset($matches['action'])){
            //    return false;
            }
            if(!in_array($matches['action'],['index','create','view','select'])){
                return false;
            }
			if(!in_array($matches['order'],['dt1','cat','towhom','forwhat','price','groupcat'])){
                return false;
            }
			
			/* if($matches['order'] == 'dt1'){
                return ['outlay/dt1',
                    [
                        'date'=> $matches['date'],
						'pole'=> isset($matches[5]) ? $matches[5] : '',
                    ]
                ];
			}  */
            return ['outlay/'.$matches['action'],
				[
					'order'=>$matches['order'],
					'date'=>$matches['date'],
					'pole'=> isset($matches[5]) ? $matches[5] : '',
				]
            ];
        }
		
		if (preg_match('%^\/yii2-consumption\/outlay%',$pathInfo))
		{
			return ['outlay/index',[]];		
		}

        return false;  // this rule does not apply
    }
}
