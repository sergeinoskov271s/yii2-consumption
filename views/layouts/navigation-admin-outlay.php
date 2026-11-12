<?php
use app\components\widgets\SearchAdminOutlayFormWidget;

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

//echo SearchFormWidget::widget(['model'=>$this->params['search_form_model']]);
echo SearchAdminOutlayFormWidget::widget(['model'=>$this->params['search_admin_outlay_form_model']]);
//

