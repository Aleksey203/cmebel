<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use dosamigos\fileupload\FileUpload;


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
			//echo Html::fileInput('file')
				?>
	<?= FileUpload::widget([
		'model' => $modelFile,
		'attribute' => 'file',
		'url' => ['orders/uploadfile', 'id' => $model->id], // your url, this is just for demo purposes,
		'options' => ['accept' => 'image/*'],
		'clientOptions' => [
			'maxFileSize' => 8000000
		],
		// Also, you can specify jQuery-File-Upload events
		// see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
		'clientEvents' => [
			'fileuploaddone' => 'function(e, data) {
                                console.log(123);
                                console.log(e);
                                console.log(data);
                            }',
			'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
		],
	]);?>
</div>
<?php
echo Html::submitButton('Сохранить файл', ['class' => 'btn btn-success pull-right', 'id'=>'saveOrderFile' ]);
echo '<div class="clear"></div>';
Modal::end();
?>

<div class="clear"></div>

