<?php
use yii\grid\GridView;
use yii\helpers\Html;
//use Yii;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Department');
?>
<h1>admin/employee/grid</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php

/** @var string $caption */
/** @var object $dataProvider */
/** @var object $data */
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '',
    ],
    'columns'=>[
           /* 'id',*/
        [
            'label'=>Yii::t('app', 'Tab Number'),
            'format' => 'raw',
            'value'=>$data->tab_number,
            /*'value'=>function ($data) {
                return Html::a($data->tab_number,
                    Yii::$app->urlManager->createUrl(
                        [
                            'personalviewer/view',
                            'id'=>$data->id,
                            'pole'=>'id'
                        ])
                );
            },*/
        ],
        [
                'attribute'=>'name',
                'label' =>Yii::t('app', 'Name'),
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'value' => function ($data) {
                    return $data->first_name.' '.$data->last_name; // $data['name'] for array data, e.g. using SqlDataProvider.
                },
        ],
        [
            'label'=>Yii::t('app', 'Position'),
            'format' => 'raw',
            'value'=>function ($data) {
                return $data->position;
            },
        ],
        [
            'label'=>Yii::t('app', 'Room'),
            'format' => 'raw',
           /* 'value'=>function ($data) {
                return Html::a($data->room,
                    Yii::$app->urlManager->createUrl(['personalviewer/room','id'=>$data->room]));
            },*/
        ],
        [
            'attribute'=>'phone',
            'label' =>Yii::t('app', 'Phone'),
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
           /* 'value'=> function($data){
                return $data->phone;
            }*/
        ],
 //       'email',
       /* [
            'label'=>Yii::t('app', 'Office'),
            'format' => 'raw',
            'value'=>function ($data) {
                return Html::a($data->office->title,
                    Yii::$app->urlManager->createUrl(['department/office-grid','id'=>$data->office->id]));
            },
        ],
        [
            'label'=>Yii::t('app', 'Section'),
            'format' => 'raw',
            'value'=>function ($data) {
                return Html::a($data->section->title,
                    Yii::$app->urlManager->createUrl(['department/section-grid','id'=>$data->section->id]));
            },
        ],*/
        /*[
            'attribute' => 'enterprise',
            'value' => 'enterprise.title'
        ],*/
    ],
    'caption'=>$caption
]);

?>
