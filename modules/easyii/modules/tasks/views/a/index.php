<?php

/* @var $this yii\web\View */

/* @var $data yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Список завдань';

$gridColumns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:80px'],
    ],
    [
        'attribute' => 'hash',
        'headerOptions' => ['style' => 'width:100px'],
    ],
    [
        'attribute' => 'taskName',
        'content' => function ($model) {
            /** @var \app\models\Task $model */
            return Html::a($model->object->name,
                Url::to(['/admin/tasks/a/edit/' . $model->id]),
                [
                    'data-id' => $model->primaryKey,
                ]);
        },
    ],
    [
        'attribute' => 'item_type',
        'content' => function ($model) {
            /** @var \app\models\Task $model */
            return \app\models\definitions\DefTask::getTypeText($model->item_type);
        },
        'filter' => \kartik\select2\Select2::widget([
            'model' => $searchModel,
            'attribute' => 'item_type',
            'data' => \app\models\definitions\DefTask::getTypes(),
            'language' => Yii::$app->language,
            'options' => [
                'placeholder' => \app\components\AppMsg::t('Всі'),
                'class' => 'reload-grid',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]),
    ],
    [
        'attribute' => 'required',
        'content' => function ($model) {
            /** @var \app\models\Task $model */
            return $model->required ? '<span class="label btn btn-danger task-status">Обов\'язкове</span>' :
                '<span class="label btn btn-success task-status">Не обов\'язкове</span>';
        },
        'filter' => \kartik\select2\Select2::widget([
            'model' => $searchModel,
            'attribute' => 'required',
            'data' => \app\models\definitions\DefTask::getRequired(),
            'language' => Yii::$app->language,
            'options' => [
                'placeholder' => \app\components\AppMsg::t('Всі'),
                'class' => 'reload-grid',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]),
    ],
    [
        'label' => 'Статус',
        'content' => function ($model) {
            return Html::checkbox('', $model->status === 1, [
                'class' => 'switch',
                'data-id' => $model->primaryKey,
                'data-link' => Url::to(['/admin/' . $this->context->module->id . '/a']),
            ]);
        },
    ],
    [
        'attribute' => 'starting_at',
        'content' => function ($model) {
            /** @var \app\models\Task $model */
            return $model->starting_at;
        },
    ],
    [
        'attribute' => 'ending_at',
        'content' => function ($model) {
            /** @var \app\models\Task $model */
            return $model->ending_at;
        },
    ]
];

$asset = \yii\easyii\modules\tasks\assets\TasksAsset::register($this);
?>

<?= $this->render('_menu') ?>
    <br>
<?php \yii\widgets\Pjax::begin(['timeout' => 5000, 'id' => 'tasks']); ?>
    <div class="form-group">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $data,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
        ]);
        ?>
    </div>
<?php \yii\widgets\Pjax::end(); ?>

<?php
$pageOptions = \yii\helpers\Json::encode([]);

$this->registerJs('TasksIndex(' . $pageOptions . ')');
?>