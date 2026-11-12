<?php
use app\components\widgets\TagCreateFormWidget;
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


echo TagCreateFormWidget::widget(['model'=>$this->params['tag_create_form_model']]);
//

