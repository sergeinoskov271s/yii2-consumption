
<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay Create');
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//app\assets\AdminEmployeeCreateAssets::register($this);
$cookies1 = Yii::$app->request->cookies;
if(($cookie = $cookies1->get('dt1')) !== null){
    $last_dt1 = $cookie->value;
} else {
	$last_dt1 = '';
}
if(($cookie = $cookies1->get('cat')) !== null){
    $last_cat = $cookie->value;
} else {
	$last_cat = '';
}
?>
<h1>admin/outlay/create</h1>


<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>
<div class="outlay-form">
    <?php $form= ActiveForm::begin([
        'id' => 'outlay-form',
    ]); ?>
    <fieldset>
        <legend>Outlay create</legend>
		<?=$form->field($model, 'dt1', ['enableAjaxValidation' => false])->textInput(['value' => $model->dt1 ? $model->dt1 :$last_dt1,'autocomplete'=>'off','class'=>['form-control','dt1']]);?>
		<?=$form->field($model, 'towhom', ['enableAjaxValidation' => false])->textInput(['value' =>$model->towhom,'autocomplete'=>'off','class'=>['typeahead','form-control','towhom'],'id'=>'towhom']);?>
        <?=$form->field($model, 'forwhat', ['enableAjaxValidation' => false])->textInput(['value' =>$model->forwhat,'autocomplete'=>'off','class'=>['typeahead','form-control','forwhat'],'id'=>'forwhat']);?>
        <?=$form->field($model, 'price', ['enableAjaxValidation' => false])->textInput(['value' =>$model->price,'autocomplete'=>'off','class'=>['form-control','price']]);?>
        <?=$form->field($model, 'cat', ['enableAjaxValidation' => false])->textInput(['value' =>$model->cat,'autocomplete'=>'off','class'=>['typeahead','form-control','cat'],'id'=>'cat','data-role'=>'cat']);?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </fieldset>

    <?php ActiveForm::end(); ?>

</div>
 
