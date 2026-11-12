<?php
use yii\grid\GridView;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Outlay');
?>
<h1>grid</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
		[
            'label'=>Yii::t('app', 'id'),
            'format' => 'raw',
			'attribute'=>'id',
			//'value'=> function($data){
            //    return $data->id;
            //}
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
echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
?>
