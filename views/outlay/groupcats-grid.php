<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use app\components\widgets\CatGridView;
use yii\helpers\Url;
use yii\helpers\Html;
/* $this->registerJsFile(
    '@web/build/js/outlay-cats.js',
    ['depends' => [\yii\web\JqueryAsset::class]],
	//View::POS_LOAD
); */

//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay');
?>
<p>outlay/view/cat/[]</p>
<h1>Group Cats  group by from year</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
//$this->params['date'] = $date;
echo CatGridView::widget([
    'dataProvider' => $dataProvider,
	'showFooter' => true,
	//'tableOptions'=>['id'=>'groupcats-grid'],
	
    'columns'=>[
		[
		    'attribute' => 'group_cat',
            'label' =>Yii::t('app', 'group_cat'),
            'format' => 'raw',
            'value'=>function($data){
				//return $data->group_cat;
				return Html::a($data->group_cat,
                     Yii::$app->urlManager->createUrl(['outlay/view',
						'order'=>'groupcat',
						'date'=>$data->date,
						'pole'=>$data->group_cat,
					]));
			} , 
		],
		[
            'attribute' => 'group_summ',
			'headerOptions' => ['width' => '150',],
            'label' =>Yii::t('app', 'group_summ'),
            'format' => 'raw',
			//'footer'=>0,
			//'contentOptions'=>['class' => 'price'],
			//'footerOptions'=>['id'=>'total_footer','class' => 'price'],
			// 'content' => function ($model, $key, $index, $column) {
				// return 'a string';
			// }
			//'class' => 'yii\grid\DataColumn',
			//'options'=>['style'=>'text-align:right;'],
			//'contentOptions' => ['class' => 'text-center'],
            'value'=>function ($data) {
				$number = number_format($data->group_summ, 0, ',', ' ');
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
		    'attribute' => 'createcatbudget',
            'label' =>Yii::t('app', 'create cat budget'),
            'format' => 'raw',
            'value'=>function($data){
				//return $data->group_cat;
				return Html::a($data->group_cat,
                     Yii::$app->urlManager->createUrl(['budget/createcat',
						//'order'=>'groupcat',
						'date'=>$data->date,
						'cat'=>$data->group_cat,
					]));
			} , 
		],
		
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
	<dt class="col-sm-3">total summ</dt>
		<dd class="col-sm-9"><?php echo number_format($total_summ, 0, ',', ' '); ?></dd>
	<dt class="col-sm-3">months of year</dt>
		<dd class="col-sm-9"> <?php echo round($month_count);  ?></dd>
	<dt class="col-sm-3">avg on year</dt>
		<dd class="col-sm-9"> <?php echo number_format($avg_year, 0, ',', ' ');  ?></dd>
</dl>
