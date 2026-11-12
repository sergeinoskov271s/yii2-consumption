<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\VarDumper;
use yii\helpers\Url;
use yii\widgets\Pjax;



$last_option = '';
$last_date = '';
$cookies = Yii::$app->request->cookies;
if(($cookie = $cookies->get('navigation')) !== null){
    $last_option = $cookie->value;
}

if(($cookie = $cookies->get('budget_date_navigation')) !== null){
    $last_date = $cookie->value;
} 


$url = Url::to(['index']);
if(isset($this->params['selected_form_option'])){
    $last_option = $this->params['selected_form_option'];
}
//Yii::info(VarDumper::dumpAsString($last_option));
?>

<?php $form = ActiveForm::begin(
        [
            'id' => 'budget-search-form',
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
	
	echo $form->field($model, 'order')->label($order_label)->dropDownList($order,
	  ['options' =>[$last_option ? $last_option : 'dt1' => ['Selected' => true]], ]);
 ?>
<?= /** @var string $id_label */
$form->field($model, 'date', ['enableAjaxValidation' => false])->textInput(['value' => $last_date ? $last_date : $model->date]);?> 

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>