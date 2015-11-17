<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\jui\DatePicker;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
//        $form->field($model, 'user_id', [
//            'template' => "{label}:\n".User::getNameById($model->user_id)."\n{hint}\n{error}"] )

        $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(\app\models\User::find()->all(), 'id', 'username'),
            [
                'prompt'=>'Выберите исполнителя',
                'disabled'=>'disabled'
            ]
        )

    ?>

    <?= $form->field($model, 'order_opencart_id')->dropDownList(ArrayHelper::map(\app\models\Orders::find()->all(), 'order_opencart_id', 'order_opencart_id'),[
        'prompt'=>'Выберите заказ',
        'onchange'=>'
                $.get( "index.php?r=tasks/get-next-number-task&idOrder="+$(this).val(), function( rawData ) {
                    var data = JSON.parse(rawData);
                  $( "#tasks-serial_number" ).val( data.nextNumberTask );
                });
            ',
        'disabled'=>'disabled'
    ]) ?>

    <?= $form->field($model, 'serial_number')->textInput(['disabled'=>'disabled']) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6,'disabled'=>'disabled']) ?>

    <?= $form->field($model, 'date_added', [
        'template' => "{label}:\n".date("d.m.Y H:i",strtotime($model->date_added))."\n{hint}\n{error}"] ); ?>

    <?=
        $form->field($model, 'date_start')->widget(DatePicker::className(), [
            'value'=>date("d.m.Y", strtotime($model->date_start)),
            'options'=>['class'=>'form-control', 'disabled'=>'disabled'],
            'dateFormat' => 'dd.MM.yyyy',
            'clientOptions' => [
                'language' => 'ru',
            ],
        ]);

    ?>

    <?=

        $form->field($model, 'date_end')->widget(DatePicker::className(), [
            'value'=>date("d.m.Y", strtotime($model->date_end)),
            'options'=>['class'=>'form-control', 'disabled'=>'disabled'],
            'dateFormat' => 'dd.MM.yyyy',
            'clientOptions' => [
                'language' => 'ru',
            ],
        ]);

    ?>

    <?=

        $form->field($model, 'date_closed')->widget(DatePicker::className(), [
            'value'=>date("d.m.Y", strtotime($model->date_closed)),
            'options'=>array('class'=>'form-control'),
            'dateFormat' => 'dd.MM.yyyy',
            'clientOptions' => [
                'language' => 'ru',
            ],
        ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
