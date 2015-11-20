<?php
/** File: _add_product.php Date: 16.11.2015 Time: 15:17 */
use yii\helpers\Html;
?>

<tr class="file">
	<td>
		<?=$get['filename'];?>
		<?=Html::hiddenInput('Orders[files]['.md5($get['file']).']', $get['file']);?>
	</td>
	<td>
		<a href="#" class="remove_file" title="Удалить" aria-label="Удалить">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>