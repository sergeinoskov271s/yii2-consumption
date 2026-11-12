<?php
use yii\grid\GridView;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Outlay');
?>

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
            'label'=>Yii::t('app', 'id'),
            'format' => 'raw',
			'attribute'=>'id',
            'value'=>function ($data) {
                return Html::a($data->id,
                    Yii::$app->urlManager->createUrl(
                        [
                            'admin/outlay/view',
                            'id'=>$data->id,
                        ])
                );
            },
        ], */
      [
            'attribute' => 'dt1',
            'label' =>Yii::t('app', 'dt1'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->dt1;
            },
        ],
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
		
    ],
    'caption'=>$caption
]);
echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
//echo '<h1>'.' months of year '.round($month_count).'</h1>';
//echo '<h1>'.' avg on year '.number_format($avg_year, 0, ',', ' ').'</h1>'; 
?>

<dl class="row">
	<dt class="col-sm-3">total for period</dt>
	<dd class="col-sm-9"><div id="total_summ" class="form-text"><?php echo number_format($total_summ, 0, ',', ' '); ?> </div></dd>
	<dt class="col-sm-3">months of year</dt>
	<dd class="col-sm-9"><div id="month_count" class="form-text"><?php echo $month_count; ?> </div></dd>
	<dt class="col-sm-3">avg year</dt>
	<dd class="col-sm-9"><div id="avg_year" class="form-text"><?php echo $avg_year; ?> </div></dd>
</dl>