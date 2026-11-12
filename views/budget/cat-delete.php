<?php
//use app\assets\BudgeteditAssets;
//BudgeteditAssets::register($this); 
use unclead\multipleinput\MultipleInput; 
use unclead\multipleinput\assets\MultipleInputAsset;
MultipleInputAsset::register($this); 
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\datetime\DateTimePicker; 
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Budget');
?>
<p>grid</p>
<h1>Budget edit Cat from date</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>


<div id='result'>

</div>
<div class="budget-form"  style="max-width: 1000px;">
    <?php $form= ActiveForm::begin([
        'id' => 'budget-delete',
    ]); ?> 
<fieldset>
        <legend>Budget delete</legend> 
<?= $form->field($model, 'cats')->widget(MultipleInput::className(), [
    'max' => 4,
 ])->label(false);?>

 <?= $form->field($model, 'budgets')->widget(MultipleInput::className(), [
        'min' => 0,
        'max' => 4,
		
		//'addButtonPosition' => MultipleInput::POS_HEADER,
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
				/* 'value' => function($data) {
						return $data['dt1'];
				}, */
			],
			[
				'name'  => 'price',
				'title' => 'price',
				'type' => 'textInput',
				'value' => function($data) {
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
				/* 'value' => function($data) {
						return $data['dt1'];
				}, */
				'options' => [
					'style' => 'width: 80px;'
				],
			],
			[
				'name'  => 'cat',
				'title' => 'cat',
				'type' => 'textInput',
				/* 'value' => function($data) {
						return $data['dt1'];
				}, */
				'options' => [
					//    'onchange' => $onchange,
					'style' => 'width: 120px;'
				],
			],
				/* [
					'name'  => 'date',
					//'type'  => \kartik\date\DatePicker::className(),
					'title' => 'Date',
					'value' => function($data) {
						//return $data['dt1'];
					},

				], */

		]
    ])->label(false);
?>
</fieldset>
<?= Html::submitButton('Delete', ['class' => 'btn btn-success']);?>


    <?php ActiveForm::end(); ?>
	
	

	
	

</div> 











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














<?php

//echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
//echo '<h1>'.' months of year '.round($month_count).'</h1>';
//echo '<h1>'.' avg on year '.number_format($avg_year, 0, ',', ' ').'</h1>'; 
?>
