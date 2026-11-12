
<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay Create');
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//app\assets\AdminEmployeeCreateAssets::register($this);
$cookies1 = Yii::$app->request->cookies;

if(($cookie = $cookies1->get('name')) !== null){
    $last_name = $cookie->value;
} else {
	$last_name = '';
}
?>
<h1>admin/tag/create</h1>


<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>
<div class="outlay-form">
    <?php $form= ActiveForm::begin([
        'id' => 'outlay-form',
    ]); ?>
    <fieldset>
        <legend>Tag create</legend>
        <?=$form->field($model, 'name', ['enableAjaxValidation' => false])->textInput(['value' =>$model->name,'autocomplete'=>'off','class'=>['typeahead','form-control','name'],'id'=>'name','data-role'=>'name']);?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </fieldset>

    <?php ActiveForm::end(); ?>

</div>
 
