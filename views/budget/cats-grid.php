<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
/* $this->registerJsFile(
    '@web/build/js/outlay-cats.js',
    ['depends' => [\yii\web\JqueryAsset::class]],
	//View::POS_LOAD
); */

//MainAssets::register($this);
$this->title = Yii::t('app', 'Budget cats');
?>
<p>budget/view/cat/</p>
<h1>Budget Cats  Group</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
	'showFooter' => false,
	//'tableOptions'=>['id'=>'cats-grid'],
	
    'columns'=>[
		[
		    'attribute' => 'group_cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function($data){
				return Html::a($data->group_cat,
                        Yii::$app->urlManager->createUrl(['budget/viewcat',
						'date'=> $data->dt1,
						'cat'=>$data->group_cat,
					]));
			} , 
		],
		[
            'attribute' => 'group_summ',
			'headerOptions' => ['width' => '150',],
            'label' =>Yii::t('app', 'budget'),
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
				$number = number_format($data->group_summ, 0, ',', ' ');
                return $number;
            },
        ],
		/* [
        'class' => 'yii\grid\CheckboxColumn',
		'headerOptions' => ['width' => '50','style' => 'text-align: center;'],
		'name' => 'selection_total',
		'contentOptions' => ['class' => 'text-center'],
        // you may configure additional properties here
		], */
		// [
            // 'attribute' => 'group_avg',
			// 'headerOptions' => ['width' => '150'],
            // 'label' =>Yii::t('app', 'group_avg'),
			// 'contentOptions'=>['class' => 'price'],
            // 'format' => 'raw',
			// 'footer'=>0,
			// 'footerOptions'=>['class' => 'price'],
            // 'value'=>function ($data) {
				// $number = number_format($data->group_avg, 0, ',', ' ');
                // return $number;
            // },
        // ],
		/* [
            'attribute' => 'main_count',
			'headerOptions' => ['width' => '150'],
            'label' =>Yii::t('app', 'main count'),
			'contentOptions'=>['class' => 'price'],
            'format' => 'raw',
			'footer'=>0,
			'footerOptions'=>['class' => 'price'],
            'value'=>function ($data) {
				$number = number_format($data->main_count, 0, ',', ' ');
                return $number;
            },
        ], */
		[
            'attribute' => 'group_summ_balance',
			'headerOptions' => ['width' => '150'],
            'label' =>Yii::t('app', 'balance'),
			'contentOptions'=>['class' => 'price'],
            'format' => 'raw',
			'footer'=>0,
			'footerOptions'=>['class' => 'price'],
            'value'=>function ($data) {
				$number = number_format($data->group_summ_balance, 0, ',', ' ');
                return $number;
            },
        ],
[
            'attribute' => 'group_summ_outlay',
			'headerOptions' => ['width' => '150'],
            'label' =>Yii::t('app', 'outlay'),
			'contentOptions'=>['class' => 'price'],
            'format' => 'raw',
			'footer'=>0,
			'footerOptions'=>['class' => 'price'],
            'value'=>function ($data) {
				$number = number_format($data->group_summ_outlay, 0, ',', ' ');
                return $number;
            },
        ], 		 	
		[
            'attribute' => 'group_summ_template',
			'headerOptions' => ['width' => '150'],
            'label' =>Yii::t('app', 'template'),
			'contentOptions'=>['class' => 'price'],
            'format' => 'raw',
			'footer'=>0,
			'footerOptions'=>['class' => 'price'],
            'value'=>function ($data) {
				$number = number_format($data->group_summ_template, 0, ',', ' ');
                return $number;
            },
        ], 		 			
		/* [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Действия',
			'visible'=>false,
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {update} {delete}{link}',
        ], */

    ],
    'caption'=>$caption
]);
echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
//echo '<h1>'.' months of year '.round($month_count).'</h1>';
//echo '<h1>'.' avg on year '.number_format($avg_year, 0, ',', ' ').'</h1>'; 



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
