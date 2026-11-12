<?php
declare(strict_types=1);


namespace app\assets;
use yii\base\ErrorException;

/**
 * Class AdminAssets
 * @package app\assets
 */
class AdminAssets extends \yii\web\AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/personalviewer.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public function init()
    {
        parent::init();

        $content = @file_get_contents(\Yii::getAlias('@app/web/styles.versions.json'));
        //       $content = file_get_contents(Yii::getPathOfAlias('webroot/assets') . DIRECTORY_SEPARATOR ."styles.versions.json");
        if (!$content) {
            //          throw new ErrorException('Could not read assests from path ' . \Yii::getAlias('@app/assets/styles.versions.json'));
        }
        $assets_data = json_decode($content, true);

        if (!empty($assets_data['styles'])) {
            //               if (in_array($style['name'], $this->packages, true) && $this->loadInAjax($style)) {
            $this->css[] = 'build/css/' . $assets_data['styles']['admin'];
            //              }
        }
        $content_js = @file_get_contents(\Yii::getAlias('@app/web/scripts.versions.json'));
        //       $content = file_get_contents(Yii::getPathOfAlias('webroot/assets') . DIRECTORY_SEPARATOR ."styles.versions.json");
        if (!$content_js) {
            //          throw new ErrorException('Could not read assests from path ' . \Yii::getAlias('@app/assets/styles.versions.json'));
        }
        $assets_data = json_decode($content_js, true);
        //      Yii::info(VarDumper::dumpAsString($assetsData));
        //     Yii::info(VarDumper::dumpAsString('-------------=============='));
        if (!empty($assets_data['scripts'])) {
            //           foreach ($assetsData['scripts'] as $script) {
            //               if (in_array($script['name'], $this->packages, true) && $this->loadInAjax($script)) {
            $this->js[] = 'build/js/' . $assets_data['scripts']['admin'];
            //                }
            //           }
        }
    }
}