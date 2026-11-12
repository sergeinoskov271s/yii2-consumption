<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

//MainAssets::register($this);
$this->title = Yii::t('app', 'Template view group');
?>
<h1>template/view group</h1>
<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
	'showFooter' => true,
	//'tableOptions'=>['id'=>'cats-grid'],
	
    'columns'=>[
		
		[
            'attribute' => 'group_cat',
            'label' =>Yii::t('app', 'group_cat'),
            'format' => 'raw',
            /* 'value'=>function ($data) {
                return $data->group_cat;
            }, */
			'value'=>function($data){
				return Html::a($data->group_cat,
                     Yii::$app->urlManager->createUrl(['template/viewcat',
						'id'=>$data->group_cat,
					]));
			} ,  
        ],
		[
            'attribute'=>'group_summ',
            'label' =>Yii::t('app', 'group_summ'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
			'options' => [
					'style' => 'width: 200px;'
				],
			'contentOptions' => ['style' => 'text-align: right;'],
            'value'=> function($data){
                return number_format($data->group_summ, 0, ',', ' ');
            }
        ], 
		[
            'attribute'=>'total_summ',
            'label' =>Yii::t('app', 'total_summ'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
			'options' => [
					'style' => 'width: 200px;'
				],
			'contentOptions' => ['style' => 'text-align: right;'],
            'value'=> function($data){
                return number_format($data->total_summ, 0, ',', ' ');
            }
        ], 
		
		
    ],
    'caption'=>$caption
]);

?>



<dl class="row">
	<dt class="col-sm-3">total summ </dt>
		<dd class="col-sm-9"><?php echo number_format($total_summ, 0, ',', ' '); ?></dd>
	<dt class="col-sm-3">total summ year </dt>
		<dd class="col-sm-9"><?php echo number_format($total_summ_year, 0, ',', ' '); ?></dd>
</dl>