<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\ShopCategories;

/* @var $this yii\web\View */
/* @var $model app\models\ShopProducts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'category_id')->dropDownList( ArrayHelper::map(ShopCategories::find()->orderBy(['parent_id' => SORT_ASC, 'name' => SORT_ASC])->all(), 'id', 'name')); ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

	<?php
	if ($model->isNewRecord) echo Html::checkbox('ShopProducts[status]', true, array('label' => 'статус'));
	else echo $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
	    <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
