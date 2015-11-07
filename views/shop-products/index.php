<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ShopProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shop Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Shop Products', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'opencart_id',
            'name',
            'model',
            'category_id',
            // 'price',
            // 'quantity',
            // 'image',
            // 'status',
            // 'date_added',
            // 'date_modified',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
