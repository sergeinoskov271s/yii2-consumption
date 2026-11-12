<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
//MainAssets::register($this);
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\widgets\DetailWidget;
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Budget');
?>
<h1>admin/budget/view</h1>
<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>
<?php

echo DetailWidget::widget([
    'model' => $budget,
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '',
    ],
    'attributes' => [
        [
            'label'=> 'Id',
            'format' => 'raw',
            'value'=> Html::a($budget->id,
                Yii::$app->urlManager->createUrl(['admin/budget/update',
                    'id'=>$budget->id]))
        ],
        [                     
            'label' =>Yii::t('app', 'dt1'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->dt1;
            },
        ],
        [
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
            'label' =>Yii::t('app', 'price'),
            //'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
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
]);

?>