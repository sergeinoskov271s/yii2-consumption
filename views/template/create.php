
<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
//MainAssets::register($this);
$this->title = Yii::t('app', 'Template Create');
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//app\assets\AdminEmployeeCreateAssets::register($this);
$cookies1 = Yii::$app->request->cookies;

if(($cookie = $cookies1->get('cat')) !== null){
    $last_cat = $cookie->value;
} else {
	$last_cat = '';
}
?>
<h1>template/create</h1>


<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>
<div class="template-form">
    <?php $form= ActiveForm::begin([
        'id' => 'template-form',
    ]); ?>
    <fieldset>
        <legend>template create</legend>
		<?=$form->field($model, 'towhom', ['enableAjaxValidation' => false])->textInput(['value' =>$model->towhom,'autocomplete'=>'off','class'=>['form-control','data-towhom']]);?>
        <?=$form->field($model, 'forwhat', ['enableAjaxValidation' => false])->textInput(['value' =>$model->forwhat,'autocomplete'=>'off','class'=>['form-control','data-forwhat']]);?>
        <?=$form->field($model, 'price', ['enableAjaxValidation' => false])->textInput(['value' =>$model->price,'autocomplete'=>'off','class'=>['form-control','data-price']]);?>
		<?=$form->field($model, 'monthly', ['enableAjaxValidation' => false])->textInput(['value' =>$model->monthly,'autocomplete'=>'off','class'=>['form-control','data-monthly']]);?>
        <?=$form->field($model, 'cat', ['enableAjaxValidation' => false])->textInput(['value' =>$model->cat,'autocomplete'=>'off','class'=>['typeahead','form-control','data-cat']]);?>
		<?=$form->field($model, 'month', ['enableAjaxValidation' => false])->textInput(['value' =>$model->month,'autocomplete'=>'off','class'=>['typeahead','form-control','data-month']]);?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </fieldset>

    <?php ActiveForm::end(); ?>

</div>
 
