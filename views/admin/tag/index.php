<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use app\assets\AdminTagAssets;
AdminTagAssets::register($this); 
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;



//MainAssets::register($this);
$this->title = Yii::t('app', 'Admin tag');
?>
<h1>admin/tag/index</h1>
<?php


?>