<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = 'Редактировать заказ номер: ' . ' ' . $model->order_opencart_id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="orders-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelFile' => $modelFile,
        'orderProducts' => $orderProducts,
        'orderFiles' => $orderFiles,
    ]) ?>

</div>
