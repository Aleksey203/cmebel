<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use dosamigos\fileupload\FileUpload;


/* @var $file app\models\OrderFiles */
/* @var $orderFile app\models\OrderFiles */

?>

<?=Html::hiddenInput('Orders[files][empty]', 'empty');?>
<table class="table table-striped table-bordered order_files">
	<thead>
	<tr>
		<th>название</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if (isset($orderFiles[0])) {
		foreach ($orderFiles as $k=>$orderFile) { ?>
			<tr class="file">
				<td>
					<a target="_blank" href="/files/orders/<?=$model->id?>/<?=$orderFile->file;?>"><?=$orderFile->name;?></a>

					<?=Html::hiddenInput('Orders[files]['.md5($orderFile->file).']', $orderFile->file);?>
				</td>
				<td>
					<a href="#" class="remove_file" title="Удалить" aria-label="Удалить">
						<span class="glyphicon glyphicon-trash"></span>
					</a>
				</td>
			</tr>
		<?php } ?>
	<?php } else { ?>
			<tr class="no_files">
				<td><p>В данном заказе нет файлов</p></td>
				<td></td>
			</tr>
	<?php } ?>
	</tbody>
</table>



<?php
/*Modal::begin([
	'options' => [
		'id' => 'add-file-modal',
		'tabindex' => false // important for Select2 to work properly
	],
	'header' => '<h4 style="margin:0; padding:0">Добавить файл к заказу</h4>',
	'toggleButton' => ['label' => 'Добавить файл', 'class' => 'btn btn-warning pull-right', 'id'=>'add_file'],
]);*/
?>
<div id="add_file_box" data-order-id="<?=$model->id?>">
	<?php
			//echo Html::fileInput('file');
	echo '<h4>Добавить файл: </h4>';
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
			var file = data.files[0].name;
                                console.log(file);
                                console.log(e);
                                console.log(data);
                                add_file('.$model->id.',file);
                            }',
			'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
		],
	]);?>
</div>
<?php
/*echo Html::submitButton('Сохранить файл', ['class' => 'btn btn-success pull-right', 'id'=>'saveOrderFile' ]);
echo '<div class="clear"></div>';
Modal::end();*/
?>

<div class="clear"></div>

