<?php
use app\assets\BudgetupdateAssets;
BudgetupdateAssets::register($this); 
use unclead\multipleinput\MultipleInput; 
use unclead\multipleinput\assets\MultipleInputAsset;
MultipleInputAsset::register($this); 
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\datetime\DateTimePicker; 
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Budget update');
?>
<p>grid</p>
<h1>Budget update from date</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>


<div id='result'>

</div>
<div class="budget-form"  style="max-width: 1000px;">
    <?php $form= ActiveForm::begin([
        'id' => 'budget-date-update',
    ]); ?> 
<fieldset>
        <legend>Budget update</legend> 
<?= $form->field($model, 'dates')->widget(MultipleInput::className(), [
    //'max' => 554,
 ])->label(false);?>

 <?= $form->field($model, 'budgets')->widget(MultipleInput::className(), [
        'min' => 0,
        //'max' => 4,
		
		'addButtonPosition' => MultipleInput::POS_HEADER,
        'columns' => [
			[
				'name'  => 'id',
				'title' => 'id',
				'type' => 'textInput',
				'options' => [
					'readonly'=>"readonly",
					'style' => 'width: 50px;'
				],
			],
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
					'class'=>'price',
					//'readonly'=>"readonly",
				],
			],
			[
				'name'  => 'template',
				'title' => 'template',
				'type' => 'textInput',				
				'options' => [
					'style' => 'width: 80px;',
					'class'=>'date-template',
					'readonly'=>"readonly",
				],
			],
			[
				'name'  => 'change',
				'title' => 'change',
				'type' => 'textInput',				
				'options' => [
					'style' => 'width: 80px;',
					'class'=>'date-change',
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
<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>

    <?php ActiveForm::end(); ?>
	
</div> 


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
	<caption>
    He-Man and Skeletor facts
	</caption>
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















