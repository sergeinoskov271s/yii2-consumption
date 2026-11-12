<?php

namespace app\components\widgets;
use app\models\SearchForm;
use Yii;

/**
 * Class SearchFormWidget
 * @package app\components\widgets
 */
class SearchFormWidget extends \yii\base\Widget
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
        $model = new SearchForm();
      
  //      $model->load(Yii::$app->request->get());

        return $this->render('search-form-widget',[
            'model' => $this->model,
            'order'=> [
                'dt1' =>self::t('search_form',$this->model->getAttributeLabel('date')),
                'towhom' =>self::t('search_form',$this->model->getAttributeLabel('to whom')),
                'forwhat' =>self::t('search_form',$this->model->getAttributeLabel('for what')),
                'price' =>self::t('search_form',$this->model->getAttributeLabel('price')),
                'cat' =>self::t('search_form',$this->model->getAttributeLabel('cat')),
				'groupcat' =>self::t('search_form',$this->model->getAttributeLabel('group of cat')),
            ],

            'order_label'=>self::t('search_form',$this->model->getAttributeLabel('order')),
            'id_label'=>self::t('search_form',$this->model->getAttributeLabel('id')),
			'pole_label'=>self::t('search_form',$this->model->getAttributeLabel('pole')),
			'date_label'=>self::t('search_form',$this->model->getAttributeLabel('date')),
        ]);
    }


}