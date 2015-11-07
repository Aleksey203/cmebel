<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\ShopCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(array_merge(array('0' => ' - нет - '), ArrayHelper::map($model::find()->all(), 'id', 'name'))); ?>

    <?php
    if ($model->isNewRecord) echo Html::checkbox('ShopCategories[status]', true, array('label' => 'статус'));
    else echo $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
