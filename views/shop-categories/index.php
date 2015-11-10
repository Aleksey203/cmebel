<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ShopCategories;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ShopCategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'opencart_id',
            'name',
	        [
		        'attribute' => 'parent_id',
		        'filter' =>  ArrayHelper::map(ShopCategories::find()->all(), 'id', 'name'),
		        'value' => function ($data) {
			        $parent = ShopCategories::find()->where(['id' => $data->parent_id])->one();
			        return  $parent['name'];
		        },
	        ],
	        [
		        'attribute' => 'status',
		        'filter' =>  array( '0'=>'выключен', '1'=>'активен'),
		        'value' => function ($data) {
			        $status = ($data->status==1) ? 'активен' : 'выключен';
			        return  $status;
		        },
	        ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
