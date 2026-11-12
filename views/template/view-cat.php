<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

//MainAssets::register($this);
$this->title = Yii::t('app', 'Template cat');
?>
<h1>template/view cat</h1>
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
            'label' =>Yii::t('app', 'cat'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->cat;
            },
        ],
		[
            'attribute' => 'towhom',
            'label' =>Yii::t('app', 'towhom'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->towhom;
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
			'options' => [
					'style' => 'width: 200px;'
				],
			'contentOptions' => ['style' => 'text-align: right;'],
            'value'=> function($data){
                return number_format($data->price, 0, ',', ' ');
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