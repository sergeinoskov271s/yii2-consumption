<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\MainAssets;


AppAsset::register($this);//bootstrap5
MainAssets::register($this);
app\assets\TemplateAssets::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap" id="container">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
 //           ['label' => 'Contact', 'url' => ['/site/contact']],
            /*[
                'label' => 'Dropdown',
                'items' => [
                    ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
                    '<li class="divider"></li>',
                    '<li class="dropdown-header">Dropdown Header</li>',
                    ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
                ],
            ],*/
            ['label' => Yii::t('app', 'Outlay'), 'url' => ['/outlay']],
			['label' => Yii::t('app', 'Budget'), 'url' => ['/budget']],
			['label' => Yii::t('app', 'Budget Admin'), 'url' => ['/admin/budget']],
			['label' => Yii::t('app', 'Outlay Admin'), 'url' => ['/admin/outlay']],
            ['label' => Yii::t('app', 'Template'), 'url' => ['/template']],
			['label' => Yii::t('app', 'Template Create'), 'url' => ['/template/create']],
			['label' => Yii::t('app', 'Template Viewgroup'), 'url' => ['/template/viewgroup']],
 #           Yii::$app->user->isGuest ? (
  #              ['label' => 'Login', 'url' => ['/site/login']]
  #          ) : (
  #              '<li>'
  #              . Html::beginForm(['/site/logout'], 'post')
  #              . Html::submitButton(
  #                  'Logout (' . Yii::$app->user->identity->username . ')',
  #                  ['class' => 'btn btn-link logout']
  #              )
   #             . Html::endForm()
  #              . '</li>'
   #         )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="" id="main">
        <div id="all">
            <div id="in">
                <div id="center">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>

                </div>
            </div>
            <div id="left">
               
                <?php

//                    echo $this->context->renderPartial('_navigation');
                ?>
            </div>
        </div>

    </div>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Personal Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
