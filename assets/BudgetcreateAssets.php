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
class BudgetcreateAssets extends AssetBundle
{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/personalviewer.css'
    ];
	public $js = [
		'js/handlebars-latest.js'
	];
	 public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset', // Explicitly depends on JqueryAsset
        // 'yii\bootstrap\BootstrapAsset', // Example of another dependency
    ];
	
    public function init()
    {	
        parent::init();
		
        /* $content = @file_get_contents(\Yii::getAlias('@app/web/styles.versions.json'));
        if (!$content) {
            throw new ErrorException('Could not read assests from path ' . \Yii::getAlias('@app/assets/styles.versions.json'));
        }
        $assets_data = json_decode($content, true);

        if (!empty($assets_data['styles'])) {
            $this->css[] = 'build/css/' . $assets_data['styles']['personalviewer'];
        } */
        $content_js = @file_get_contents(\Yii::getAlias('@app/web/js/budget/scripts.json'));
		if (!$content_js) {
            throw new ErrorException('Could not read assests from path ' . \Yii::getAlias('@app/assets/styles.versions.json'));
        }
		$assets_data = json_decode($content_js, true);
		foreach($assets_data as $key=>$value){
			if($key == 'create'){
				$this->js[] = substr($value, 4);
			}		
		}
		//Yii::info(VarDumper::dumpAsString($content_js));
       /*  if (!$content_js) {
            throw new ErrorException('Could not read assests from path ' . \Yii::getAlias('@app/assets/styles.versions.json'));
        }
        $assets_data = json_decode($content_js, true);
        if (!empty($assets_data['scripts'])) {
            $this->js[] = 'build/js/' . $assets_data['scripts']['personalviewer'];
        } */


    }
}
