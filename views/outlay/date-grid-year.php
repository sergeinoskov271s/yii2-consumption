<?php
use yii\grid\GridView;
use yii\helpers\Html;
//use Yii;
/* @var $this yii\web\View */
//$this->title = Yii::t('app', 'Outlay');
/** @var string $title */
$this->title = $title;
?>
<h2>outlay/date</h2>


<?php
/** @var object $dataProvider */
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
         /*   'id',*/
        [
            'label'=>Yii::t('app', 'data'),
            'format' => 'raw',
			'attribute'=>'date',
			'value'=> function($data){
                return $data->dt1;
            }
           /*  'value'=>function ($data) {
                return Html::a($data->tab_number,
                    Yii::$app->urlManager->createUrl(
                        [
                            'personalviewer/view',
                            'id'=>$data->id,
                            'pole'=>'id'
                        ])
                );
            }, */
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
                return $data->price;
            }
        ],
 //       'email',
        [
            'attribute' => 'cat',
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->cat;
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
    'caption'=>$caption,
	'summary'=>$total_summ
	
]);
echo '<h1>'.$total_summ.'</h1>';
?>
