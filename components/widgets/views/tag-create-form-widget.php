<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\VarDumper;
use yii\helpers\Url;
use yii\widgets\Pjax;

$last_name = '';
$cookies = Yii::$app->request->cookies;

if(($cookie = $cookies->get('budget_date_navigation')) !== null){
    $last_name = $cookie->value;
} 


$url = Url::to(['index']);
if(isset($this->params['selected_form_option'])){
    $last_option = $this->params['selected_create_form_option'];
}
?>

<?php $form = ActiveForm::begin(
        [
            'id' => 'tag-create-form',
  //          'encodeErrorSummary' => false,
            'errorSummaryCssClass' => 'help-block',
            'action'=>$url,
  //          'validateOnBlur' => false,
  //          'validateOnChange' => false,
  //          'validateOnType' => false,
         /*   'enableAjaxValidation' => true,
            'validateOnSubmit' => true,
            'validationUrl' => Url::to(['performAjaxValidation']),*/
        ]
);
?>

<?php /** @var string $order_label */
/** @var array $navigation */
//echo $form->field($model, 'order')->label($order_label)->dropDownList($navigation,
 //   ['options' =>[$last_option ? $last_option : 'cat' => ['Selected' => true]], 'onchange' => 'changeOrder()']);
	

 ?>
<?= /** @var string $id_label */
$form->field($model, 'date', ['enableAjaxValidation' => false])->textInput(['value' => $last_name ? $last_name : $model->name]);?> 

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>