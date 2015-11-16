<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\OrderStatus;
use yii\widgets\DetailView;
use yii\grid\GridView;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'order_opencart_id',
			'version',
			'client.name',
			'total',
			'date_added:datetime',
			'date_modified:datetime',
		],
	]) ?>
	<?php $form = ActiveForm::begin(); ?>
	<?= $this->render('_products_form', [
		'orderProducts' => $orderProducts,
		'model' => $model,
	]) ?>

	<?php /* echo GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],

			//'id',
			//'opencart_id',
			'name',
			'model',
			'price',
			'quantity',
			// 'image',
			//'date_added:datetime',
			//'date_modified:datetime',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); */?>



    <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(OrderStatus::find()->orderBy(['id' => SORT_ASC])->all(), 'id', 'name')) ?>


	<?php /*echo FileUploadUI::widget([
		'model' => $model,
		'attribute' => 'total',
		'url' => ['orders/upload', 'id' => $model->id],
		'gallery' => false,
		'fieldOptions' => [
			'accept' => 'image/*'
		],
		'clientOptions' => [
			'maxFileSize' => 2000000
		],
		// ...
		'clientEvents' => [
			'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
			'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
		],
	]);*/
	?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить в этой версии', ['class' => 'btn btn-success' ]) ?>
        <?= Html::submitButton('Сохранить в новой версии', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
