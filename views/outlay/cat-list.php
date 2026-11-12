<?php
/* @var $this yii\web\View */
//use app\assets\MainAssets;
use yii\widgets\ListView;

//MainAssets::register($this);
$this->title = Yii::t('app', 'Outlay');
?>
<h1>outlay/viewcat</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?php
use Yii;
use yii\helpers\VarDumper;

Yii::info(VarDumper::dumpAsString($dataProvider->getModels()));
echo ListView::widget([
 'dataProvider' => $dataProvider,
 'options' => [
	 'tag' => 'div',
	 'class' => 'list-wrapper',
	 'id' => 'list-wrapper',
 ],
 'layout' => "{pager}\n{items}\n{summary}",
 'itemView' => function ($model, $key, $index, $widget) {
	Yii::info(VarDumper::dumpAsString($model));
	return $this->render('_list_item',['model' => $model]);

 // or just do some echo
 // return $model->title . ' posted by ' . $model->author;
 },
]);












?>
