<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html; 

//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay');
?>
<p>outlay/view/dt1/[year]</p>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
      /*  [
            'label'=>Yii::t('app', 'data'),
            'format' => 'raw',
			'attribute'=>'date',
			'value'=> function($data){
                return $data->dt1;
            }
        ], */
		[
            'attribute' => 'data',
            'label' =>Yii::t('app', 'data'),
            'format' => 'raw',
            'value'=>function($data){
				return Html::a($data->dt1,
                        Yii::$app->urlManager->createUrl(['outlay/view',
						'order'=>'dt1',
						'date'=>$data->date,
						'pole'=>$data->dt1,
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
		/* [
            'attribute' => 'avg_summ',
            'label' =>Yii::t('app', 'avg_summ'),
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->avg_summ, 0, ',', ' ');
                return $number;
            },
        ],       */		
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

?>

<dl class="row">
	<dt class="col-sm-3" >total summ</dt>
		<dd class="col-sm-9" id="total_summ"><?php echo number_format($total_summ, 0, ',', ' '); ?></dd>
	<dt class="col-sm-3">months of year</dt>
		<dd class="col-sm-9"><?php echo round($month_count); ?></dd>
	<dt class="col-sm-3">avg on year</dt>
		<dd class="col-sm-9"><?php echo number_format($avg_year, 0, ',', ' '); ?></dd>
</dl> 
