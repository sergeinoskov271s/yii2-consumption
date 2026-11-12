<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay');
?>
<h1>outlay/viewcat</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
       /*  [
            'attribute'=>'total_summ',
            'label' =>Yii::t('app', 'total_summ'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value'=> function($data){
                return $data->total_summ;
            }
        ], */
        [
            'attribute' => 'cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->cat;
            },
        ],
		[
             'attribute' => 'cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function($model){
				return Html::a($model->cat,
                        Yii::$app->urlManager->createUrl(['outlay/view',
						'order'=>'cat',
						'date'=>$model->date,
						'pole'=>$model->cat,
					]));
			} ,
        ],
		/* [
            'attribute' => 'main_count',
            'label' =>Yii::t('app', 'main_count'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->main_count;
            },
        ],    */
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
        ],   	  		
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
echo '<h1>'.number_format($total, 0, ',', ' ').'</h1>';
echo '<h1>'.' months of year '.round($month_count).'</h1>';
echo '<h1>'.' avg on year '.number_format($avg_year, 0, ',', ' ').'</h1>';
?>

<dl class="row">
	<dt class="col-sm-3">total summ </dt>
	<dd class="col-sm-9"><?php echo number_format($total, 0, ',', ' '); ?></dd>
	<dt class="col-sm-3">months of year </dt>
	<dd class="col-sm-9"><?php echo round($month_count); ?></dd>
	<dt class="col-sm-3">avg on year </dt>
	<dd class="col-sm-9"><?php echo number_format($avg_year, 0, ',', ' '); ?></dd>
</dl>