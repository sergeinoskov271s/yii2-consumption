<?php
use app\components\widgets\SearchFormWidget;
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


echo SearchFormWidget::widget(['model'=>$this->params['search_form_model']]);
//

