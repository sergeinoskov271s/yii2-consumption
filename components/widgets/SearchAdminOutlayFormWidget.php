<?php

namespace app\components\widgets;
use app\models\SearchAdminOutlayForm;
use Yii;

/**
 * Class SearchFormWidget
 * @package app\components\widgets
 */
class SearchAdminOutlayFormWidget extends \yii\base\Widget
{
    public object $model;
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['search_form'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath'       => '@app/messages',
            'fileMap'        => [
                'search_form' => 'search_form.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t($category , $message, $params, $language);
    }


    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run() {
        $model = new SearchAdminOutlayForm();
      
  //      $model->load(Yii::$app->request->get());

        return $this->render('search-admin-outlay-form-widget',[
            'model' => $this->model,
            'order'=> [
                'towhom' =>self::t('search_form',$this->model->getAttributeLabel('to whom')),
                'forwhat' =>self::t('search_form',$this->model->getAttributeLabel('for what')),            
                'cat' =>self::t('search_form',$this->model->getAttributeLabel('cat')),
            ],

            'order_label'=>self::t('search_form',$this->model->getAttributeLabel('order')),
			'pole_label'=>self::t('search_form',$this->model->getAttributeLabel('pole')),
			'date_label'=>self::t('search_form',$this->model->getAttributeLabel('date')),
        ]);
    }


}