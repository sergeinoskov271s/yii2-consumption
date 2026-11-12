<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use app\assets\TemplateAssets;
TemplateAssets::register($this); 
use unclead\multipleinput\MultipleInput; 
use unclead\multipleinput\assets\MultipleInputAsset;
MultipleInputAsset::register($this); 
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\datetime\DateTimePicker; 
use yii\helpers\VarDumper;

use yii\grid\GridView;

//MainAssets::register($this);
$this->title = Yii::t('app', 'Template');
?>
<h1>template/index</h1>
<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
	'showFooter' => true,
	//'tableOptions'=>['id'=>'cats-grid'],
	
    'columns'=>[
       /* [
            'attribute'=>'id',
            'label' =>Yii::t('app', 'id'),
            //'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value'=> function($data){
                return $data->id;
            }
        ], */
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
            'attribute' => 'cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->cat;
            },
        ],
		[
            'attribute'=>'price',
            'label' =>Yii::t('app', 'price'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
			'contentOptions' => ['style' => 'text-align: right;'],
            'value'=> function($data){
                return number_format($data->price, 0, ',', ' ');
            }
        ], 
		[
            'attribute'=>'price',
            'label' =>Yii::t('app', 'price for year'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
			'contentOptions' => ['style' => 'text-align: right;'],
            'value'=> function($data){
				return number_format($data->group_summ_template, 0, ',', ' ');
            }
        ], 
		[
		    'attribute' => 'monthly',
            'label' =>Yii::t('app', 'monthly'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->monthly;
            },
		],
		[
		    'attribute' => 'count_month',
            'label' =>Yii::t('app', 'count of year'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->month;
            },
		],
	
		/* [
        'class' => 'yii\grid\CheckboxColumn',
		'headerOptions' => ['width' => '50','style' => 'text-align: center;'],
		'name' => 'selection_total',
		
		'contentOptions' => ['class' => 'text-center'],
        // you may configure additional properties here
		], */
		
		[
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Действия',
			//'visible'=>true,
            'headerOptions' => ['width' => '180'],
            //'template' => '{view} {update} {delete}{link}',
			'buttons' => [
				'view' => function ($url, $model, $key) {
					return Html::a("view", 'view/'.$model->id, [
						'title' => "View",
						//'class' => 'btn btn-outline-secondary',
						'data' => [
							'method' => 'get',
							//'confirm' => 'Are you sure? This will Activate this.',
						],
					]);
				},
				
				'update' => function ($url, $model, $key) {
					return Html::a("update", 'update/'.$model->id, [
						'title' => "Update",
						//'class' => 'btn btn-outline-success',
						'data' => [
							'method' => 'get',
							//'confirm' => 'Are you sure? This will Activate this.',
						],
					]);
				},
				'delete' => function ($url, $model, $key) {
					return Html::a("delete", 'delete/'.$model->id, [
						'title' => "Delete",
						//'class' => 'btn btn-outline-warning',
						'data' => [
							'method' => 'get',
							//'confirm' => 'Are you sure? This will Activate this.',
						],
					]);

  
				},
				 
			]

        ],
		
    ],
    'caption'=>$caption
]);

?>



<dl class="row">
	<dt class="col-sm-3">total summ </dt>
		<dd class="col-sm-9"><?php echo number_format($total_summ, 0, ',', ' '); ?></dd>
	
</dl>