<?php
/** File: _add_product.php Date: 16.11.2015 Time: 15:17 */
use yii\helpers\Html;

for ($i = 1; $i <= 15; $i++) {
	$values[$i]=$i;
}
?>
<tr class="product">
	<td><?=$product->name;?></td>
	<td class="price"><?=$product->price;?></td>
	<td><?php
		echo Html::input('text','Orders[products][new]['.$product->id.'][quantity]',$get['product_quantity'],['class'=>'form-control product-quantity']);
		?></td>
	<td class="product-cost"><?=($product->price*$get['product_quantity']);?></td>
	<td>
		<a href="#0" class="remove_product" title="Удалить" aria-label="Удалить">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>
