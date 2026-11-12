<?php
use yii\grid\GridView;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Budget');
?>
<p>grid</p>
<h1>Budget edit Cat from date</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
	[                     
            'label' =>Yii::t('app', 'id'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->id;
            },
        ],
		[                     
            'label' =>Yii::t('app', 'data'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->dt1;
            },
        ],
		[
            'label'=>Yii::t('app', 'to whom'),
            'format' => 'raw',
            'value'=>function ($data) {
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
            'label'=>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->cat;
            },
        ],
		
		[
            'attribute' => 'price',
            'label' =>Yii::t('app', 'price'),
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->price, 0, ',', ' ');
                return $number;
            },
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
