<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-search">
	<div class="row">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<div class="col-xs-4 col-sm-4">
	<?= Html::label('Клиент', 'client')?>
	<?= Html::input('text','client',@$_GET['client'],
		 [
			'class'=>'form-control',
		]
	)?>
	</div>
		<div class="col-xs-2 col-sm-2">
	<?= Html::label('Дата от...', 'Orders[from_date]')?>
    <?= DatePicker::widget([
	    'name'  => 'Orders[from_date]',
	    'value'  => @$_GET['Orders']['from_date'],
	    'language' => 'ru',
	    'dateFormat' => 'dd.MM.yyyy',
	    'options' => [
		    'class'=>'form-control form-group',
	    ]
    ]) ?>
	</div>
		<div class="col-xs-2 col-sm-2">
	<?= Html::label('Дата до...', 'Orders[to_date]')?>
	<?= DatePicker::widget([
		'name'  => 'Orders[to_date]',
		'value'  => @$_GET['Orders']['to_date'],
		'language' => 'ru',
		'dateFormat' => 'dd.MM.yyyy',
		'options' => [
			'class'=>'form-control form-group',
		]
	]) ?>
	</div>
		<div class="col-xs-2 col-sm-2">
	    <div class="form-group">
	        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary','style'=>'margin-top: 25px;']) ?>
	    </div>
    </div>

    <?php ActiveForm::end(); ?>
	</div>
</div>
