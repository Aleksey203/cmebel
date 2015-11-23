<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\ShopProducts;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
/* @var $product app\models\ShopProducts */
/* @var $orderProduct app\models\OrderProduct */

if (isset($orderProducts)) {
	for ($i = 1; $i <= 15; $i++) {
		$values[$i]=$i;
	}
?>
<table class="table table-striped table-bordered order_products">
	<thead>
	<tr>
		<th>название</th>
		<th>цена</th>
		<th>количество</th>
		<th>стоимость</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orderProducts as $k=>$orderProduct) { ?>
		<tr class="product">
			<td><?=$orderProduct->product->name;?></td>
			<td class="price"><?=$orderProduct->product->price;?></td>
			<td><?php
				echo Html::input('text','Orders[products]['.$orderProduct->id.']['.$orderProduct->product_id.'][quantity]',
					$orderProduct->quantity,['class'=>'form-control product-quantity']);
				?></td>
			<td class="product-cost"><?=($orderProduct->product->price*$orderProduct->quantity);?></td>
			<td>
				<a href="#<?=$orderProducts[$k]->id;?>" class="remove_product" title="Удалить" aria-label="Удалить">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</td>
		</tr>
	<?php } ?>

	</tbody>
</table>
	<?php
	Modal::begin([
		'options' => [
			'id' => 'add-product-modal',
			'tabindex' => false // important for Select2 to work properly
		],
		'header' => '<h4 style="margin:0; padding:0">Добавить товар к заказу</h4>',
		'toggleButton' => ['label' => 'Добавить товар', 'class' => 'btn btn-warning pull-right', 'id'=>'add_product'],
	]);
	 ?>
	<div id="add_product_box" data-order-id="<?=$model->id?>">
		<table class="table table-striped table-bordered">
			<thead>
			<tr>
				<th>название</th>
				<th>количество</th>
			</tr>
			</thead>
			<tbody>
			<tr class="product" >
				<td><?php
					echo Select2::widget([
						'name' => 'product_id',
						'data' => array_merge(['0'=> ' - '], ArrayHelper::map(ShopProducts::find()->orderBy(['id' => SORT_ASC])->all(), 'id', 'name')),
						'options' => ['placeholder' => 'Выберите товар ...'],
						'pluginOptions' => [
							'allowClear' => true
						],
					]);
					?></td>
				<td><?php
					echo Html::input('text','product_quantity',1,['class'=>'form-control product-quantity']);
					?></td>
			</tr>
			</tbody>
		</table>
	</div>
	<?php
	echo Html::submitButton('Сохранить товар', ['class' => 'btn btn-success pull-right', 'id'=>'saveOrderProduct' ]);
	echo '<div class="clear"></div>';
	Modal::end();
	?>

	<div class="clear"></div>
	<?php } else { ?>
	<p>В данном заказе нет товаров</p>
	<?php } ?>

