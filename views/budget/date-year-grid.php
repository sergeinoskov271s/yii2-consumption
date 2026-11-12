<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm; 

//MainAssets::register($this);
$this->title = Yii::t('app', 'Budget year');
?>
<h1>budget/view/dt1/year</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
		[
            'attribute' => 'date',
            'label' =>Yii::t('app', 'date'),
            'format' => 'raw',
            'value'=>function($data){
				return Html::a($data->dt1,
                        Yii::$app->urlManager->createUrl(['budget/view',
						'order'=>'dt1',
						'date'=>$data->dt1,
						
					]));
			} ,
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
            'attribute' => 'delete',
            'label' =>Yii::t('app', 'delete'),
            'format' => 'raw',
            'value'=>function($data){
				return Html::a($data->dt1,
                        Yii::$app->urlManager->createUrl(['budget/deletefromdate',
						//'order'=>'dt1',
						'date'=>$data->dt1,
						
					]));
			} ,
        ],
		[
            'attribute' => 'update',
            'label' =>Yii::t('app', 'update'),
            'format' => 'raw',
            'value'=>function($data){
				return Html::a($data->dt1,
                        Yii::$app->urlManager->createUrl(['budget/updatefromdate',
						//'order'=>'dt1',
						'date'=>$data->dt1,
						
					]));
			} ,
        ],
		
    ],
    'caption'=>$caption
]);














?>
<dl class="row">
	<dt class="col-sm-3">total for period</dt>
	<dd class="col-sm-9"><?php echo number_format($total_summ, 0, ',', ' '); ?></dd>
</dl>
<dl class="row">
	<dt class="col-sm-3">count outlay data year</dt>
	<dd class="col-sm-9"><?php echo number_format($count_outlay_data_year, 0, ',', ' '); ?></dd>
</dl>
<dl class="row">
	<dt class="col-sm-3">total outlay</dt>
	<dd class="col-sm-9"><?php echo number_format($total_outlay, 0, ',', ' '); ?></dd>
</dl>
<dl class="row">
	<dt class="col-sm-3">count_budget data year</dt>
	<dd class="col-sm-9"><?php echo number_format($count_budget_data_year, 0, ',', ' '); ?></dd>
</dl>
<dl class="row">
	<dt class="col-sm-3">total budget</dt>
	<dd class="col-sm-9"><?php echo number_format($total_budget, 0, ',', ' '); ?></dd>
</dl>
<dl class="row">
	<dt class="col-sm-3">avg summ</dt>
	<dd class="col-sm-9"><?php echo number_format($avg_summ, 0, ',', ' '); ?></dd>
</dl>