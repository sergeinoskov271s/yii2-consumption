<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\VarDumper;
use yii\helpers\Url;
use yii\widgets\Pjax;

/*['options' =>[ '3' => ['Selected' => true]]]*/
/* @var $this yii\web\View */
/*$cookies = Yii::$app->request->cookies;
if (($cookie = $cookies->get('language')) !== null) {
    $language = $cookie->value;
}
if (($cookie = $cookies->get('navigation')) !== null) {
    $navigation = $cookie->value;
    Yii::info($navigation.'------------------');
}*/
$last_option = '';
$last_date = '';
$cookies1 = Yii::$app->request->cookies;
if(($cookie = $cookies1->get('navigation')) !== null){
    $last_option = $cookie->value;
}
if(($cookie = $cookies1->get('date_navigation')) !== null){
    $last_date = $cookie->value;
}
//Yii::info($last_option.'------------------');

$url = Url::to(['index']);

//$selected_form_option =  $this->params['selected_form_option'];
if(isset($this->params['selected_form_option'])){
    $last_option = $this->params['selected_form_option'];
}

?>


<?php $form = ActiveForm::begin(
        [
            'id' => 'search-form',
			'action' => ['outlay'],
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
<?php
//echo $form->field($model, 'address')->radioList(array(1 => 'All', 0 =>'Pl Revolushion 5'), array('class' => 'i-checks')); ;
?>

<?php /** @var string $order_label */
/** @var array $navigation */
	
echo $form->field($model, 'order')->label($order_label,['for'=>'searchform-order'])->dropDownList($order,
	  ['options' =>[$last_option ? $last_option : 'dt1' => ['Selected' => true]], 'onchange' => 'changeOrder()','id'=>'searchform-order']);
    
echo $form->field($model, 'pole')->label($pole_label,['for'=>'searchform-pole'])->dropDownList([],['options' =>['date' => ['Selected' => true]],'id'=>'searchform-pole']);
 ?>
<?= /** @var string $id_label */
$form->field($model, 'date', ['enableAjaxValidation' => false])->textInput(['value' => $last_date ? $last_date : $model->date]);?>



    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>