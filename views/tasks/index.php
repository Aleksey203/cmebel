<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать задачу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'id'=>'tasks-grid',
        'rowOptions' => function($model, $key, $index, $grid){
                if($model->date_closed){
                    return ['class' => 'complete-task'];
                } else {
                    return ['class' => 'not-complete-task'];
                }
        },
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'order_opencart_id',
            'serial_number',
            'text:ntext',

            [
                'label'=>'Добавил',
                'attribute'=>'ownerUser'
            ],
             'comment:ntext',
            [
                'label'=>'Начало',
                'attribute'=>'date_start',
                'format'=>['date', 'php:d.m.Y']
            ],
            [
                'label'=>'Конец',
                'attribute'=>'date_end',
                'format'=>['date', 'php:d.m.Y']
            ],
            [
                //'label'=>'Конец',
                'attribute'=>'date_closed',
                'format'=>['date', 'php:d.m.Y']
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}  {delete}',
            ],
        ],
    ]); ?>

</div>
