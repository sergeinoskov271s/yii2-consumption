<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use app\assets\AdminOutlayAssets;
AdminOutlayAssets::register($this); 
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;



//MainAssets::register($this);
$this->title = Yii::t('app', 'Admin Outlay');
?>
<h1>admin/outlay/index</h1>
<?php


?>