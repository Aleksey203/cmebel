<?php
/* @var $product app\models\ShopProducts */

use app\models\ShopCategories;
if (isset($products[0])) {
?>
<table class="table table-striped table-bordered">
	<thead>
	<tr>
		<th>номер</th>
		<th>название</th>
		<th>название модели</th>
		<th>категория</th>
		<th>цена</th><th>количество</th><th>статус</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($products as $k=>$product) { ?>
		<tr data-key="2">
			<td><?=$product->id;?></td>
			<td><?=$product->name;?></td>
			<td><?=$product->model;?></td>
			<td><?=ShopCategories::getCategoryName($product->category_id);?></td>
			<td><?=$product->price;?></td>
			<td><?=$product->quantity;?></td>
			<td><?=($product->status==1) ? 'активен' : 'выключен';?></td>
			<td>
				<a href="/index.php?r=shop-products%2Fview&amp;id=<?=$product->id;?>" title="Просмотр" aria-label="Просмотр" data-pjax="0">
					<span class="glyphicon glyphicon-eye-open"></span>
				</a>
				<a href="/index.php?r=shop-products%2Fupdate&amp;id=<?=$product->id;?>" title="Редактировать" aria-label="Редактировать" data-pjax="0">
					<span class="glyphicon glyphicon-pencil"></span>
				</a>
				<a href="/index.php?r=shop-products%2Fdelete&amp;id=<?=$product->id;?>" title="Удалить" aria-label="Удалить" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" data-pjax="0">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
	<?php } else { ?>
	<p>В данной категории нет товаров</p>
	<?php } ?>