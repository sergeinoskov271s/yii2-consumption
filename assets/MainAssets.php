<?php


namespace app\assets;
use yii\web\AssetBundle;
use yii\base\ErrorException;
use yii\helpers\VarDumper;
use Yii;

/**
 * Class MainAssets
 * @package app\assets
 */
class MainAssets extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\web\JqueryAsset',
//        'yii\widgets\ActiveFormAsset',
//        'yii\validators\ValidationAsset'
    ];

    public $packages = [];

    public function init()
    {
        parent::init();

        $content = @file_get_contents(\Yii::getAlias('@app/web/styles.versions.json'));

 //       $content = file_get_contents(Yii::getPathOfAlias('webroot/assets') . DIRECTORY_SEPARATOR ."styles.versions.json");
        if (!$content) {
            throw new ErrorException('Could not read assests from path ' . \Yii::getAlias('@app/assets/styles.versions.json'));
        }
        $assets_data = json_decode($content, true);
        if (!empty($assets_data['styles'])) {
            //               if (in_array($style['name'], $this->packages, true) && $this->loadInAjax($style)) {
            $this->css[] = 'build/css/' . $assets_data['styles']['main'];
            //              }
        }


    }


}