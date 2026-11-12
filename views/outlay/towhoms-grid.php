<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;  

//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay');
?>
<p>outlay/view/towhom/[year]</p>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
        [
            'attribute' => 'towhom',
            'label' =>Yii::t('app', 'towhom'),
            'format' => 'raw',
            'value'=>function($data){
				return Html::a($data->towhom,
                        Yii::$app->urlManager->createUrl(['outlay/view',
						'order'=>'towhom',
						'date'=>$data->date,
						'pole'=>$data->towhom,
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
            'label' =>Yii::t('app', 'total_summ'),
			'contentOptions'=>['class' => 'price'],
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->total_summ, 0, ',', ' ');
                return $number;
            },
        ],	
		[
            'attribute' => 'avg_summ',
            'label' =>Yii::t('app', 'avg_summ'),
			'contentOptions'=>['class' => 'price'],
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->avg_summ, 0, ',', ' ');
                return $number;
            },
        ],
		
    ],
    'caption'=>$caption
]);

?>
<dl class="row">
	<dt class="col-sm-3">total for period</dt>
	<dd class="col-sm-9"><div id="total_summ" class="form-text"><?php echo number_format($total_summ, 0, ',', ' '); ?> </div></dd>
	
</dl>