<?php
/* @var $this yii\web\View */
use app\assets\MainAssets;

MainAssets::register($this);

use app\assets\BudgetcreateAssets;
BudgetcreateAssets::register($this); 
use unclead\multipleinput\MultipleInput; 
use unclead\multipleinput\assets\MultipleInputAsset;
MultipleInputAsset::register($this); 
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\datetime\DateTimePicker; 
use yii\helpers\VarDumper;

$this->title = Yii::t('app', 'Budget create');
?>
<h1>budget/create</h1>
<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<div id='result'></div>


<div class="budget-form"  style="max-width: 1000px;">
    <?php $form= ActiveForm::begin([
        'id' => 'budget-create',
		//'enableAjaxValidation' => true,
		//'validationUrl' => 'validation-rul',
    ]); ?> 
<fieldset>
        <legend>Budget create</legend> 

<?= $form->field($model, 'dates')->widget(MultipleInput::className(), [

	
 ])->label(false);?>
 <?= $form->field($model, 'budgets')->widget(MultipleInput::className(), [
        'min' => 1,
        'max' => 104,
		'addButtonOptions' => [
			'class' => 'btn btn-success', // Adds Bootstrap success button styling
			'label' => 'Add' // Changes the button text
			// or use HTML for an icon:
			// 'label' => Html::tag('i', null, ['class' => 'glyphicon glyphicon-plus']) 
		],
		'addButtonPosition' => MultipleInput::POS_HEADER,
        'columns' => [
			[
				'name'  => 'forwhat',
				'title' => 'forwhat',
				'type' => 'textInput',
				'options' => [
					'style' => 'width: 200px;'
					//    'onchange' => $onchange,
				],
			],
			[
				'name'  => 'towhom',
				'title' => 'towhom',
				'type' => 'textInput',
				'options' => [
					'style' => 'width: 200px;'
				],
			],
			[
				'name'  => 'price',
				'title' => 'price',
				'type' => 'textInput',
				'value' => function($data) {
					//return ($data['price'] ?? 0, 0, ',', ' ');
					return number_format($data['price'] ?? 0, 0, ',', ' ');
				},
				//'type'  => 'static',
            /* 'value' => function($data) {
                return Html::tag('span', 'static content', ['class' => 'label label-info']);
            }, */
				/* 'value'=>function ( $index) {
						//$number = number_format($model->price, 0, ',', ' ');
					return $index;
				}, */
				
				'options' => [
					'style' => 'width: 80px;',
					'class'=>'price',
				],
			],
			[
				'name' => 'cat',
				//'type' => 'dropDownList',
				'type' => 'textInput',
				'title' => 'Cat',
				'defaultValue' =>'cat',
				//'items' => [], // Data for dropdown
				'options' => [
					//'prompt' => 'Select Cat',
				],
			],
			/* [
				'name'  => 'month',
				'title' => 'month',
				'type' => 'textInput',				
				'options' => [
				//	'readonly'=>"readonly",
					'style' => 'width: 110px;'
				],
			], */

		]
    ])->label(false);
?>
</fieldset>
<?= Html::submitButton('Create', ['class' => 'btn btn-success']);?>




<?php ActiveForm::end(); ?>



<dl class="row">
	<dt class="col-sm-3" >total summ</dt>
		<dd class="col-sm-9" id="total_summ"></dd>
	<dt class="col-sm-3">months of year</dt>
		<dd class="col-sm-9"> </dd>
	<dt class="col-sm-3">avg on year</dt>
		<dd class="col-sm-9"> </dd>
</dl> 
















<script id="name-table"   type="text/x-handlebars-template">
{{! Построение таблицы поиск по фамилии}}
<div id="div-show">
    <table class="table table-bordered table-striped">		
        <thead>		
		<tr>			
			<td>дата</td>
			<td>towhom</td>
			<td>forwhat</td>
			<td>cat</td>
			<td>price</td>
			<td></td>
		</tr>
        </thead>
        <tbody>
            {{#budgets}}
            <tr>
                <td  ></td>
                <td  ></td>
                <td  ></td>
                <td  >{{cat}}</td>
                <td  ></td>
				 <td  ></td>
            </tr>
            {{/budgets}}
        </tbody>
    </table>
</div>
</script>

