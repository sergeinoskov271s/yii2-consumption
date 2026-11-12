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
$last_date='';
if(($cookie = $cookies1->get('date_budget_navigation')) !== null){
    $last_date = $cookie->value;
}
//Yii::info($last_option.'------------------');

$url = Url::to(['index']);

?>


<?php $form = ActiveForm::begin(
        [
            'id' => 'budget-form',
	//		'action' => ['outlay'],
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

 ?>
<?= /** @var string $id_label */
$form->field($model, 'dt1', ['enableAjaxValidation' => false])->textInput(['value' => $last_date ? $last_date : $model->dt1]);?>
<?=$form->field($model, 'cat', ['enableAjaxValidation' => false])->textInput(['value' =>$model->cat,'autocomplete'=>'off','class'=>['typeahead','form-control','data-cat']]);?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>