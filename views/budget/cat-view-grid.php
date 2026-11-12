<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Budget');
$this->title = 'Budget';
?>
<p>grid</p>
<h1>Budget Cat from date</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'=>[
		[                     
            'label' =>Yii::t('app', 'year'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->group_year;
            },
        ], 
		[                     
            'label' =>Yii::t('app', 'editcat'),
            'format' => 'raw',
            //'value'=>function ($data) {
            //   return $data->group_month;
            //},
			
			'value'=> function($data){
				return Html::a($data->group_month,
					Yii::$app->urlManager->createUrl(['budget/editcat',
                    'date'=>$data->group_year.'-'.$data->group_month.'-01','cat'=>$data->cat]));
			}
        ],
[                     
            'label' =>Yii::t('app', 'addcat'),
            'format' => 'raw',
            //'value'=>function ($data) {
            //   return $data->group_month;
            //},
			
			'value'=> function($data){
				return Html::a($data->group_month,
					Yii::$app->urlManager->createUrl(['budget/addcat',
                    'date'=>$data->group_year.'-'.$data->group_month.'-01','cat'=>$data->cat]));
			}
        ], 		
        // [
            // 'label'=>Yii::t('app', 'for what'),
            // 'format' => 'raw',
            // 'value'=>function ($data) {
                // return $data->group_forwhat;
            // },
        // ],
		[
            'attribute' => 'group_summ',
            'label' =>Yii::t('app', 'group summ'),
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->group_summ, 0, ',', ' ');
                return $number;
            },
        ],
		/* [
            'attribute' => 'main_count',
            'label' =>Yii::t('app', 'main count'),
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->main_count, 0, ',', ' ');
                return $number;
            },
        ],
        [
            'attribute' => 'group_avg',
            'label' =>Yii::t('app', 'group avg'),
            'format' => 'raw',
            'value'=>function ($data) {
				$number = number_format($data->group_avg, 0, ',', ' ');
                return $number;
            },
        ],  */
		/* ['class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 7%'],
            'visible'=> true,
            'buttons'=>[
                'view'=>function ($url) {
                    return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button']);
                },
                'update'=>function ($url) {
                    return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button']);
                },
            ],
        ], */
		/* [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Действия',
			'visible'=>true,
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {update} {delete}{link}',
        ], */
		
    ],
    'caption'=>$caption
]);
echo '<h1>'.number_format($total_summ, 0, ',', ' ').'</h1>';
//echo '<h1>'.' months of year '.round($month_count).'</h1>';
//echo '<h1>'.' avg on year '.number_format($avg_year, 0, ',', ' ').'</h1>'; 
?>
