<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;

//MainAssets::register($this);
$this->title = Yii::t('app', 'Budget');
?>
<h1>For what</h1>
<p>budget/view/forwhat</p>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
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
            'attribute'=>'price',
            'label' =>Yii::t('app', 'price'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value'=> function($data){
                return number_format($data->price, 0, ',', ' ');
            }
        ], 
        [
            'attribute' => 'cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->cat;
            },
        ],
		
    ],
    'caption'=>$caption
]);
echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
?>
