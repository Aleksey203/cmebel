<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ShopCategories;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ShopProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать новый товар', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Создать новую категорию', ['shop-categories/create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'opencart_id',
            'name',
            'model',
	        [
		        'attribute' => 'category_id',
		        'filter' =>  ArrayHelper::map(ShopCategories::find()->all(), 'id', 'name'),
		        'value' => function ($data) {
			        $parent = ShopCategories::find()->where(['id' => $data->category_id])->one();
			        return  $parent['name'];
		        },
	        ],
             'price',
             'quantity',
            // 'image',
	        [
		        'attribute' => 'status',
		        'filter' =>  array( '0'=>'выключен', '1'=>'активен'),
		        'value' => function ($data) {
			        $status = ($data->status==1) ? 'активен' : 'выключен';
			        return  $status;
		        },
	        ],
             //'date_added:datetime',
             //'date_modified:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
