<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\assets;
 
use yii\base\ErrorException;
use yii\web\AssetBundle;
 
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BudgetAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/personalviewer.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
    public function init()
    {
		
        parent::init();
		$this->js[] = 'js/bootstrap-typeahead.js';
		$this->css[] = 'build/css/budget1.css';
		
        $content_js = @file_get_contents(\Yii::getAlias('@app/web/js/budget/scripts.json'));
		if (!$content_js) {
            throw new ErrorException('Could not read assests from path ' . \Yii::getAlias('@app/assets/styles.versions.json'));
        }
		$assets_data = json_decode($content_js, true);
		foreach($assets_data as $key=>$value){
			if($key == 'index'){
				//$this->js[] = substr($value, 4);
			}				
		}


    }
}
