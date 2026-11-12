<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;

//MainAssets::register($this);

use app\assets\BudgetcreatemonthAssets;
BudgetcreatemonthAssets::register($this); 
use unclead\multipleinput\MultipleInput; 
use unclead\multipleinput\assets\MultipleInputAsset;

MultipleInputAsset::register($this); 
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\datetime\DateTimePicker; 
use yii\helpers\VarDumper;
use app\components\widgets\CatGridView;

$this->title = Yii::t('app', 'Budget create month');
?>
<h1>Budget create month</h1>
<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<div id='result'></div>


<div class="budget-form"  style="max-width: 1000px;">
    <?php $form= ActiveForm::begin([
        'id' => 'budget-month-create',
    ]); ?> 
<fieldset>
        <legend>Budget create monthly</legend> 

 <?= $form->field($model, 'budgets')->widget(MultipleInput::className(), [
        'min' => 0,
        //'max' => 4,
		
		'addButtonPosition' => MultipleInput::POS_HEADER,
        'columns' => [
			[
				'name'  => 'dt1',
				'title' => 'dt1',
				'type' => 'textInput',
				'options' => [
					'readonly'=>"readonly",
					'style' => 'width: 110px;'
				],
			],
			[
				'name'  => 'forwhat',
				'title' => 'forwhat',
				'type' => 'textInput',
				'options' => [
					'style' => 'width: 200px;',
					//'readonly'=>"readonly",
					//    'onchange' => $onchange,
				],
			],
			[
				'name'  => 'towhom',
				'title' => 'towhom',
				'type' => 'textInput',
				'options' => [
					'style' => 'width: 200px;',
					//'readonly'=>"readonly",
				],
			],
			[
				'name'  => 'price',
				'title' => 'price',
				'type' => 'textInput',				
				'options' => [
					'style' => 'width: 80px;',
					'class' => 'input-price',
					//'readonly'=>"readonly",
				],
			],
			[
				'name'  => 'cat',
				'title' => 'cat',
				'type' => 'textInput',
				'options' => [
					//    'onchange' => $onchange,
					'style' => 'width: 80px;',
					//'readonly'=>"readonly",
				],
			],

		]
    ])->label(false);
?>
</fieldset>
<fieldset>
        <legend>Budget create not monthly</legend> 

 <?= $form->field($model, 'budgets_notmonthly')->widget(MultipleInput::className(), [
        'min' => 0,
        //'max' => 4,
		
		'addButtonPosition' => MultipleInput::POS_HEADER,
        'columns' => [
			[
				'name'  => 'dt1',
				'title' => 'dt1',
				'type' => 'textInput',
				'options' => [
					'readonly'=>"readonly",
					'style' => 'width: 110px;'
				],
			],
			[
				'name'  => 'forwhat',
				'title' => 'forwhat',
				'type' => 'textInput',
				'options' => [
					'style' => 'width: 200px;',
					//'readonly'=>"readonly",
					//    'onchange' => $onchange,
				],
			],
			[
				'name'  => 'towhom',
				'title' => 'towhom',
				'type' => 'textInput',
				'options' => [
					'style' => 'width: 200px;',
					//'readonly'=>"readonly",
				],
			],
			[
				'name'  => 'price',
				'title' => 'price',
				'type' => 'textInput',				
				'options' => [
					'style' => 'width: 80px;',
					'class' => 'input-price',
					//'readonly'=>"readonly",
				],
			],
			[
				'name'  => 'cat',
				'title' => 'cat',
				'type' => 'textInput',
				'options' => [
					//    'onchange' => $onchange,
					'style' => 'width: 80px;',
					//'readonly'=>"readonly",
				],
			],

		]
    ])->label(false);
?>
</fieldset>



<?= Html::submitButton('Create', ['class' => 'btn btn-success']);?>


<?php ActiveForm::end(); ?>
	
	

	
	

</div> 



<dl class="row">
	
	<dt class="col-sm-3">total for month </dt>
		<dd class="col-sm-9" id="total-summ" ></dd>
	
</dl>







<script id="name-table"   type="text/x-handlebars-template">
{{!}}
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

