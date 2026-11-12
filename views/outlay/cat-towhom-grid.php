<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
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
            'attribute' => 'towhom',
            'label' =>Yii::t('app', 'towhom'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->towhom;
            },
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
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->total_summ, 0, ',', ' ');
                return $number;
            },
        ],	
		
    ],
    'caption'=>$caption
]);
echo '<h1>'.number_format($total, 0, ',', ' ').'</h1>';


?>
