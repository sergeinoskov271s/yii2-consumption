<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;

//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay');
?>
<p>outlay/view/towhom/[]</p>
<h1>To whom</h1>

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
            'attribute' => 'dt1',
            'label' =>Yii::t('app', 'dt1'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->dt1;
            },
        ],
        [
            'attribute' => 'forwhat',
            'label' =>Yii::t('app', 'forwhat'),
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
