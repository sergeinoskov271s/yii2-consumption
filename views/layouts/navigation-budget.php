<?php
use app\components\widgets\BudgetSearchFormWidget;
use app\components\widgets\BudgetCreateFormWidget;
/*$cookies = Yii::$app->request->cookies;
if (($cookie = $cookies->get('language')) !== null) {
    $language = $cookie->value;
}
if (($cookie = $cookies->get('navigation')) !== null) {
    $navigation = $cookie->value;
    Yii::info($navigation.'------------------');
}*/

/*$cookies1 = Yii::$app->request->cookies;
if(($cookie = $cookies1->get('navigation')) !== null){
    $order = $cookie->value;
}*/


echo BudgetSearchFormWidget::widget(['model'=>$this->params['budget_form_model']]);
echo BudgetCreateFormWidget::widget(['model'=>$this->params['budget_create_form_model']]);
//

