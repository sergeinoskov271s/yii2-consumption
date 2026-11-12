<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
$this->registerJsFile(
    '@web/build/js/outlay-cats.js',
    ['depends' => [\yii\web\JqueryAsset::class]],
	//View::POS_LOAD
);

//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay');
?>
<p>outlay/view/cat/[]</p>
<h1>Cats from year</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
	//'showFooter' => true,
	//'tableOptions'=>['id'=>'cats-grid'],
	
    'columns'=>[
		[
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
		],
		[
            'attribute' => 'main_count',
            'label' =>Yii::t('app', 'main_count'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->main_count;
            },
        ],
		[
            'attribute' => 'total_summ',
			'headerOptions' => ['width' => '150',],
            'label' =>Yii::t('app', 'total_summ'),
            'format' => 'raw',
			'footer'=>0,
			'contentOptions'=>['class' => 'price'],
			'footerOptions'=>['id'=>'total_footer','class' => 'price'],
			// 'content' => function ($model, $key, $index, $column) {
				// return 'a string';
			// }
			//'class' => 'yii\grid\DataColumn',
			//'options'=>['style'=>'text-align:right;'],
			//'contentOptions' => ['class' => 'text-center'],
            'value'=>function ($data) {
				$number = number_format($data->total_summ, 0, ',', ' ');
                return $number;
            },
        ],
		
		/* [
        'class' => 'yii\grid\CheckboxColumn',
		'headerOptions' => ['width' => '50','style' => 'text-align: center;'],
		'name' => 'selection_total',	
		'contentOptions' => ['class' => 'text-center'],
		], */
		[
            'attribute' => 'month_avg',
			//'headerOptions' => ['width' => '150'],
           // 'label' =>Yii::t('app', 'group_avg'),
			//'contentOptions'=>['class' => 'price'],
            'format' => 'raw',
			//'footer'=>0,
			//'footerOptions'=>['id'=>'avg_footer','class' => 'price'],
            'value'=>function ($data) {
				$number = number_format($data->month_avg, 0, ',', ' ');
                return $number;
            },
        ],
		/* [
            'attribute' => 'group_avg',
            'value'=>function ($data) {
				$number = number_format($data->group_avg, 0, ',', ' ');
                return $number;
            },
        ], */
		
       /* [
            'label'=>'url',
            'format' => 'raw',
            'value'=>function ($data) {
                return Html::a($data->department->title,
                    Yii::$app->urlManager->createUrl(['department/grid','id'=>$data->department->id]));
            },
        ],*/
		
    ],
    'caption'=>$caption
]);


$this->registerJs(<<<JS
    // Use event delegation on the document or a container
    $(document).on('click', '.action-button-class', function() {
        // Find the closest parent <tr> element
        var row = $(this).closest('tr');
        // Get the value of the data-key attribute
        var rowId = row.data('key'); 

        alert('The ID of the clicked row is: ' + rowId);
        // You can now use rowId in your AJAX calls or further logic
    });
JS);
?>

<dl class="row">
	<dt class="col-sm-3">total for period</dt>
	<dd class="col-sm-9"><div id="total_summ" class="form-text"><?php echo number_format($total_summ, 0, ',', ' '); ?> </div></dd>
	<dt class="col-sm-3"> months of year</dt>
	<dd class="col-sm-9"><div id="total_summ" class="form-text"><?php echo round($month_count); ?> </div></dd>
	<dt class="col-sm-3">avg on yeard</dt>
	<dd class="col-sm-9"><div id="total_summ" class="form-text"><?php echo number_format($avg_year, 0, ',', ' '); ?> </div></dd>
</dl>
