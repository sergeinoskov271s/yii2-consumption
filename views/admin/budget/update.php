<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;


//MainAssets::register($this);
$this->title = Yii::t('app', 'Budget Update');
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
<h1>admin/budget/update</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>
<div class="budget-form">
    <?php $form= ActiveForm::begin([
        'id' => 'budget-form',
    ]); ?>
    <fieldset>
        <legend>Budget update</legend>
		<?=$form->field($model, 'dt1', ['enableAjaxValidation' => false])->textInput(['value' => $model->dt1 ? $model->dt1 :$last_dt1,'autocomplete'=>'off','class'=>['form-control','data-dt1']]);?>
		<?=$form->field($model, 'towhom', ['enableAjaxValidation' => false])->textInput(['value' =>$model->towhom,'autocomplete'=>'off','class'=>['form-control','data-towhom']]);?>
        <?=$form->field($model, 'forwhat', ['enableAjaxValidation' => false])->textInput(['value' =>$model->forwhat,'autocomplete'=>'off','class'=>['form-control','data-forwhat']]);?>
        <?=$form->field($model, 'price', ['enableAjaxValidation' => false])->textInput(['value' =>$model->price,'autocomplete'=>'off','class'=>['form-control','data-price']]);?>
        <?=$form->field($model, 'cat', ['enableAjaxValidation' => false])->textInput(['value' =>$model->cat,'autocomplete'=>'off','class'=>['typeahead','form-control','data-cat']]);?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </fieldset>

    <?php ActiveForm::end(); ?>

</div>