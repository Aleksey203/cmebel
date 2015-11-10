<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ShopProducts */

$this->title = 'Create Shop Products';
$this->params['breadcrumbs'][] = ['label' => 'Shop Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-products-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
