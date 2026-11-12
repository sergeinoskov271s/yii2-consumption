<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;  

//MainAssets::register($this);
$this->title = Yii::t('app', 'Budget');
?>
<p>budget/view/towhom/[year]</p>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
        /* [
            'attribute' => 'cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->cat;
            },
        ], */
		[
		    'attribute' => 'group_towhom',
            'label' =>Yii::t('app', 'towhom'),
            'format' => 'raw',
            'value'=>function($data){
				return Html::a($data->group_towhom,
                        Yii::$app->urlManager->createUrl(['budget/view',
						'cat'=>$data->group_towhom,
					]));
			} , 
		],
		[
            'attribute' => 'group_summ',
			'headerOptions' => ['width' => '150',],
            'label' =>Yii::t('app', 'group_summ'),
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
		[
            'attribute' => 'group_avg',
			'headerOptions' => ['width' => '150'],
            'label' =>Yii::t('app', 'group_avg'),
			'contentOptions'=>['class' => 'price'],
            'format' => 'raw',
			'footer'=>0,
			'footerOptions'=>['class' => 'price'],
            'value'=>function ($data) {
				$number = number_format($data->group_avg, 0, ',', ' ');
                return $number;
            },
        ],
[
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
        ], 		
		
    ],
    'caption'=>$caption
]);
echo '<h1>'.'total of summ '.number_format($total_summ, 0, ',', ' ').'</h1>';


?>
