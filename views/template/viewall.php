<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

//MainAssets::register($this);
$this->title = Yii::t('app', 'Admin Outlay');
?>
<h1>template/index</h1>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
	'showFooter' => true,
	//'tableOptions'=>['id'=>'cats-grid'],
	
    'columns'=>[
       [
            'attribute'=>'id',
            'label' =>Yii::t('app', 'id'),
            //'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value'=> function($data){
                return $data->id;
            }
        ],
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
            'value'=> function($data){
                return number_format($data->price, 0, ',', ' ');
            }
        ], 
		
		/* [
		    'attribute' => 'cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function($data){
				return Html::a($data->cat,
                        Yii::$app->urlManager->createUrl(['outlay/view',
						'order'=>'cat',
						'date'=>$data->date,
						'pole'=>$data->cat,
					]));
			} , 
		], */
	
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
			'visible'=>true,
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {update} {delete}{link}',
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
    // return the button HTML code
				}
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