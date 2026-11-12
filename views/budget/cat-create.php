<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;

//MainAssets::register($this);

use app\assets\BudgetcreateAssets;
BudgetcreateAssets::register($this); 
use unclead\multipleinput\MultipleInput; 
use unclead\multipleinput\assets\MultipleInputAsset;

MultipleInputAsset::register($this); 
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\datetime\DateTimePicker; 
use yii\helpers\VarDumper;
use app\components\widgets\CatGridView;

$this->title = Yii::t('app', 'Budget create cat');
?>
<h1><h1>Budget create Cat from date</h1></h1>
<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<div id='result'></div>
<?php
echo CatGridView::widget([
    'dataProvider' => $dataProvider,
	'showFooter' => true,
	//'tableOptions'=>['id'=>'groupcats-grid'],
	
    'columns'=>[
		
		[
            'attribute' => 'towhom',
            'label' =>Yii::t('app', 'towhom'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->towhom;
            },
        ],
		[
			'attribute' => 'forwhat',
            'label'=>Yii::t('app', 'for what'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->forwhat;
            },
        ],
		[
            'attribute'=>'price',
            'label' =>Yii::t('app', 'price'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
			'contentOptions' => ['style' => 'text-align: right;'],
			'options' => [
					'style' => 'width: 80px;'
				],
            'value'=> function($data){
                return number_format($data->price, 0, ',', ' ');
            }
        ], 

    ],
	
    'caption'=>$caption
]);

?>

<dl class="row">
	<dt class="col-sm-3">cat</dt>
		<dd class="col-sm-9"><?php echo $cat; ?></dd>

	<dt class="col-sm-3">total summ year  <?php echo $date; ?></dt>
		<dd class="col-sm-9"><?php echo number_format($total_summ_for_year, 0, ',', ' '); ?></dd>
	<dt class="col-sm-3">total for month </dt>
		<dd class="col-sm-9"> <?php echo number_format($total_for_month, 0, ',', ' ');  ?></dd>
	<dt class="col-sm-3">months of year</dt>
		<dd class="col-sm-9"> <?php echo round($month_count);  ?></dd>
 <dt class="col-sm-3">avg on year</dt>
		<dd class="col-sm-9"> <?php echo number_format($avg_year, 0, ',', ' ');  ?></dd>
</dl>




<div class="budget-form-create"  style="max-width: 1000px;">
    <?php $form= ActiveForm::begin([
       // 'id' => 'budget-edit',
    ]); ?> 
<fieldset>
        <legend>Budget create cat</legend> 
<?= $form->field($model, 'cats')->widget(MultipleInput::className(), [
    'max' => 4,
 ])->label(false);?>

 <?= $form->field($model, 'budgets')->widget(MultipleInput::className(), [
        'min' => 1,
        'max' => 10,
		'addButtonOptions' => [
        'class' => 'btn btn-success', // Adds Bootstrap success button styling
        'label' => 'Add' // Changes the button text
        // or use HTML for an icon:
        // 'label' => Html::tag('i', null, ['class' => 'glyphicon glyphicon-plus']) 
    ],
		'addButtonPosition' => MultipleInput::POS_HEADER,
        'columns' => [
			[
				'name'  => 'dt1',
				'title' => 'dt1',
				'type' => 'textInput',
				'options' => [
			//		'readonly'=>"readonly",
					'style' => 'width: 110px;'
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
				'name'  => 'forwhat',
				'title' => 'forwhat',
				'type' => 'textInput',
				'options' => [
					'style' => 'width: 200px;'
					//    'onchange' => $onchange,
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

		]
    ])->label(false);
?>
</fieldset>
<?= Html::submitButton('Create', ['class' => 'btn btn-success']);?>




<?php ActiveForm::end(); ?>

<dl class="row">
	<dt class="col-sm-3">total summ budget</dt>
		<dd class="col-sm-9"><?php echo $total_summ_budget; ?></dd>
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

