<?php

namespace app\components\widgets;
use app\models\BudgetFormCreate;
use Yii;

/**
 * Class SearchFormWidget
 * @package app\components\widgets
 */
class BudgetFormCreateWidget extends \yii\base\Widget
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
        $model = new BudgetFormCreate();
      
        return $this->render('budget-form-create-widget',[
            'model' => $this->model,
            
        ]);
    }


}