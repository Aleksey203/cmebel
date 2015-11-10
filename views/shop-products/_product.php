<?php
/** File: _product.php Date: 09.11.2015 Time: 15:12 */
/* @var $model app\models\ShopProducts */

?>
<tr data-key="<?=$key?>">
	<td><?=$model->id;?></td>
	<td><a href="/index.php?r=shop-products%2Fview&amp;id=1" title="Редактировать"><?=$model->name;?></a></td>
	<td><?=$model->model;?></td>
	<td>Ноутбуки</td>
	<td><?=$model->price;?></td>
	<td><?=$model->quantity;?></td>
	<td><?=($model->status==1)?'активен':'выключен';?></td>
</tr>
