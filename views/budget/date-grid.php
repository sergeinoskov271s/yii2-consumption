<?php
use yii\grid\GridView;
use yii\helpers\Html;
//use Yii;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Budget');
/** @var string $title */

$this->title = Yii::t('app', 'Budget date');
?>
<h1>budget/view/date</h1>


<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
/** @var object $dataProvider */
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
            'value'=>function ($data) {
                return Html::a($data->tab_number,
                    Yii::$app->urlManager->createUrl(
                        [
                            'personalviewer/view',
                            'id'=>$data->id,
                            'pole'=>'id'
                        ])
                );
            },
        ], */
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
		[
		    'attribute' => 'editcat',
            'label' =>Yii::t('app', 'editcat'),
            'format' => 'raw',
            'value'=>function($data){
				//return $data->group_cat;
				return Html::a($data->cat,
                     Yii::$app->urlManager->createUrl(['budget/editcat',
						//'order'=>'groupcat',
						'date'=>$data->dt1,
						//'date'=>'2025',
						'cat'=>$data->cat,
					]));
			} , 
		],
		[
		    'attribute' => 'deletecat',
            'label' =>Yii::t('app', 'deletecat'),
            'format' => 'raw',
            'value'=>function($data){
				//return $data->group_cat;
				return Html::a($data->cat,
                     Yii::$app->urlManager->createUrl(['budget/deletecat',
						//'order'=>'groupcat',
						'date'=>$data->dt1,
						//'date'=>'2025',
						'cat'=>$data->cat,
					]));
			} , 
		], 
		/* [
            'attribute' => 'total_summ',
            'label' =>Yii::t('app', 'total_summ'),
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->total_summ, 0, ',', ' ');
                return $number;
            },
        ],        */		
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

?>


<dl class="row">
	<dt class="col-sm-3">total for period</dt>
		<dd class="col-sm-9"><?php echo number_format($total_summ, 0, ',', ' '); ?></dd>
</dl>