<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ShopCategories */

$this->title = 'Создать категорию';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
