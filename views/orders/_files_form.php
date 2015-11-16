<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\OrderFiles;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
/* @var $file app\models\OrderFiles */
/* @var $orderFile app\models\OrderFiles */

if (isset($orderFiles[0])) {

?>
<table class="table table-striped table-bordered order_files">
	<thead>
	<tr>
		<th>название</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orderFiles as $k=>$orderFile) { ?>
		<tr class="file">
			<td><?=$orderFile->name;?></td>
			<td>
				<a href="#" class="remove_file" title="Удалить" aria-label="Удалить">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</td>
		</tr>
	<?php } ?>

	</tbody>
</table>

	<?php } else { ?>
	<p>В данном заказе нет файлов</p>
	<?php } ?>

<?php
Modal::begin([
	'options' => [
		'id' => 'add-file-modal',
		'tabindex' => false // important for Select2 to work properly
	],
	'header' => '<h4 style="margin:0; padding:0">Добавить файл к заказу</h4>',
	'toggleButton' => ['label' => 'Добавить файл', 'class' => 'btn btn-warning pull-right', 'id'=>'add_file'],
]);
?>
<div id="add_file_box" data-order-id="<?=$model->id?>">
	<?php
			echo Html::fileInput('file')
				?>
</div>
<?php
echo Html::submitButton('Сохранить файл', ['class' => 'btn btn-success pull-right', 'id'=>'saveOrderFile' ]);
echo '<div class="clear"></div>';
Modal::end();
?>

<div class="clear"></div>

