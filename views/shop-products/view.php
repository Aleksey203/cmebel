<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ShopProducts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены в удалении?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'opencart_id',
            'name',
            'model',
	        [
		        'attribute' => 'category_id',
		        'value' => $model->getCategoryName($model['category_id']),
	        ],
            'price',
            'quantity',
            'image',
	        [
		        'attribute' => 'status',
		        'value' => $model['status']==1 ? 'активен' : 'выключен'
	        ],
            'date_added:datetime',
            'date_modified:datetime',
        ],
    ]) ?>

</div>
