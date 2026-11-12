<?php
use yii\grid\GridView;
use yii\helpers\Html;
//use Yii;
/* @var $this yii\web\View */
//$this->title = Yii::t('app', 'Outlay');
/** @var string $title */
$this->title = Yii::t('app', 'Outlay date');
?>
<p>outlay/view/date</p>


<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
/** @var object $dataProvider */
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
        [
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
        [
            'attribute' => 'cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->cat;
            },
        ],
		
		
    ],
    'caption'=>$caption,
	'summary'=>$total_summ
	
]);
echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
?>
