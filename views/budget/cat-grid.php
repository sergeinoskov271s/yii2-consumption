<?php
use yii\grid\GridView;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Budget');
?>
<p>grid</p>
<h1>Cat from date</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
       
       /*  [
            'attribute'=>'towhom',
            'label' =>Yii::t('app', 'to whom'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value' => function ($data) {
                return $data->towhom;
            },
        ],
		[
            'attribute' => 'total_summ',
            'label' =>Yii::t('app', 'total_summ'),
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->total_summ, 0, ',', ' ');
                return $number;
            },
        ],
		[
            'attribute' => 'avg_summ',
            'label' =>Yii::t('app', 'avg_summ'),
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->avg_summ, 0, ',', ' ');
                return $number;
            },
        ],    */
        /* [
            'attribute' => 'dt1',
            'label' =>Yii::t('app', 'dt1'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->dt1;
            },
        ], */
		[
            'attribute'=>'towhom',
            'label' =>Yii::t('app', 'to whom'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value' => function ($data) {
                return $data->towhom;
            },
        ],
        [
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
            'value'=> function($data){
                return number_format($data->price, 0, ',', ' ');
            }
        ],
		/* [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Действия',
			'visible'=>true,
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {update} {delete}{link}',
        ], */
		
    ],
    'caption'=>$caption
]);
echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
//echo '<h1>'.' months of year '.round($month_count).'</h1>';
//echo '<h1>'.' avg on year '.number_format($avg_year, 0, ',', ' ').'</h1>'; 
?>
