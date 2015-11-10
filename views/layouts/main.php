<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'CMebel',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
	        (!Yii::$app->user->isGuest AND Yii::$app->user->identity->getIsAdmin()) ? ['label' => 'Категории', 'url' => ['/shop-categories/index']] :
		        '',
	        (!Yii::$app->user->isGuest AND Yii::$app->user->identity->getIsAdmin()) ? ['label' => 'Товары', 'url' => ['/shop-products/index']] :
		        '',
	        (!Yii::$app->user->isGuest AND Yii::$app->user->identity->getIsAdmin()) ? ['label' => 'Номенклатура', 'url' => ['/shop-products/tree']] :
		        '',
	        (!Yii::$app->user->isGuest AND Yii::$app->user->identity->getIsAdmin()) ? ['label' => 'Клиенты', 'url' => ['/clients/index']] :
		        '',
	        (!Yii::$app->user->isGuest AND Yii::$app->user->identity->getIsAdmin()) ?
		        ['label' => 'Пользователи', 'url' => ['/user/admin/index']] :
		        '',
            Yii::$app->user->isGuest ?
                '' :
                [
                    'label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
