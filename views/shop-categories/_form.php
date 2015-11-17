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

    <?php echo $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map($model::find()
	                                                    ->orderBy(['parent_id' => SORT_ASC, 'name' => SORT_ASC])->all(),
													    'id', 'name'),
													    ['prompt'=>' - нет - ']);?>
	<?php /*echo Html::dropDownList('ShopCategories[parent_id]',($model->isNewRecord)?'0':($model->parent_id+1),
									array_merge(
										['0'=>' - нет - '],
										ArrayHelper::map($model::find()
											->orderBy(['parent_id' => SORT_ASC, 'name' => SORT_ASC])->all(),
											'id', 'name')
									))*/?>

    <?php
    if ($model->isNewRecord) echo Html::checkbox('ShopCategories[status]', true, array('label' => 'статус'));
    else echo $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
