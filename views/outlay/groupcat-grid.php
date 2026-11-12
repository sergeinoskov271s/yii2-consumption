<?php
use yii\grid\GridView;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Outlay');
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
      /* [
            'attribute' => 'dt1',
            'label' =>Yii::t('app', 'dt1'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->dt1;
            },
        ], */
		[
            'attribute'=>'group_forwhat',
            'label' =>Yii::t('app', 'group forwhat'),
            //'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value' => function ($data) {
                return $data->group_forwhat;
            },
        ],
        [
			'attribute'=>'group_towhom',
            'label'=>Yii::t('app', 'group towhom'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->group_towhom;
            },
        ],
        [
            'attribute'=>'group_summ',
            'label' =>Yii::t('app', 'group summ'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value'=> function($data){
                return number_format($data->group_summ, 0, ',', ' ');
            }
        ], 
		
    ],
    'caption'=>$caption
]);
echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
//echo '<h1>'.' months of year '.round($month_count).'</h1>';
//echo '<h1>'.' avg on year '.number_format($avg_year, 0, ',', ' ').'</h1>'; 
?>
