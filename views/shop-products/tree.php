<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ShopCategories;
use execut\widget\TreeView;
use yii\web\JsExpression;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ShopProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Номенклатура';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>
		<?= Html::a('Создать новый товар', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<div class="col-sm-4 col-md-3">
		<?php
		$onSelect = new JsExpression(<<<JS
function (undefined, item) {
    console.log(item);
    console.log(item.href);
    var arr = Array();
    arr = item.href.toString().split('#'.toString() );
        var urlUpdateDay = '/index.php?r=shop-products/tree',
        category_id = arr[1];
		$.ajax({
			url: urlUpdateDay,
			type: 'POST',
			dataType: 'json',
			data: {
				category_id: category_id
			},
			success: function(data) {
				if (data.success) {
					$('.shop-products-index').html('');
					spi = $('.shop-products-index');
					setTimeout(function () {spi.html(data.html)}, 300);
					//$('.shop-products-index').html(data.html);
				} else {
					alert(data.data);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
    return false;
}
JS
		);
		echo TreeView::widget([
			'data' => $categoriesTree,
			'size' => TreeView::SIZE_SMALL,
			'clientOptions' => [
				'onNodeSelected' => $onSelect,
				'selectedBackColor' => 'rgb(40, 153, 57)',
				'borderColor' => '#fff',
				'levels' => 1,
				'enableLinks' => true,
			],
		]);?>
	</div>

	<div class="shop-products-index col-sm-8 col-md-9">

    <?= $this->render('_products', [
	    'products' => $products,
    ]) ?>

	</div>
</div>