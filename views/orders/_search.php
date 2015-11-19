<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


	<?= Html::label('Клиент', 'client')?>
	<?= Html::input('text','client',@$_GET['client'],
		 [
			'class'=>'form-control',
			'style'=>'width:300px; display:inline-block; margin-right:50px;'
		]
	)?>

	<?= Html::label('Дата от...', 'Orders[from_date]')?>
    <?= DatePicker::widget([
	    'name'  => 'Orders[from_date]',
	    'value'  => @$_GET['Orders']['from_date'],
	    'language' => 'ru',
	    'dateFormat' => 'dd.MM.yyyy',
	    'options' => [
		    'class'=>'form-control form-group',
		    'style'=>'width:100px; display:inline-block; margin-right:30px;'
	    ]
    ]) ?>

	<?= Html::label('Дата до...', 'Orders[to_date]')?>
	<?= DatePicker::widget([
		'name'  => 'Orders[to_date]',
		'value'  => @$_GET['Orders']['to_date'],
		'language' => 'ru',
		'dateFormat' => 'dd.MM.yyyy',
		'options' => [
			'class'=>'form-control form-group',
			'style'=>'width:100px; display:inline-block; margin-right:30px;'
		]
	]) ?>


    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
