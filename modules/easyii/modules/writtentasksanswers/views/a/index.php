<?php

/* @var $this yii\web\View */

/* @var $data yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Письмові відповіді';

$gridColumns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:80px'],
    ],
    [
        'attribute' => 'teamName',
        'content' => function ($model) {
            /** @var \app\models\WrittenTaskAnswer $model */
            return Html::a($model->team->name, Url::to(['/admin/teams/a/edit/' . $model->team->id]), [
                'data' => [
                    'pjax' => 0,
                ],
            ]) . ' (' . $model->team->school->getFullName() . ')';
        },
    ],
    [
        'attribute' => 'task_id',
        'content' => function ($model) {
            /** @var \app\models\WrittenTaskAnswer $model */
            return $model->task->name;
//            return Html::a($model->task->name, Url::to(['/admin/tasks/a/edit/', 'id' => $model->task->task->id]), [
//                'data' => [
//                    'pjax' => 0,
//                ],
//            ]);
        },
        'filter' => \kartik\select2\Select2::widget([
            'model' => $searchModel,
            'attribute' => 'task_id',
            'data' => \app\models\WrittenTaskAnswer::getTasksList(),
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
        'attribute' => 'status',
        'content' => function ($model) {
            /** @var \app\models\WrittenTaskAnswer $model */
            return $model->text && $model->text !== '' ? '<span class="label btn btn-success task-status">Виконано</span>' :
                '<span class="label btn btn-danger task-status">Не виконано</span>';
        },
        'filter' => \kartik\select2\Select2::widget([
            'model' => $searchModel,
            'attribute' => 'status',
            'data' => \app\models\definitions\DefWrittenTaskAnswers::getStatuses(),
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
        'attribute' => 'updated_at',
        'content' => function ($model) {
            /** @var \app\models\WrittenTaskAnswer $model */
            return $model->text ? $model->updated_at : '';
        },
    ],
    [
        'label' => 'Дії',
        'content' => function ($model) {
            return $model->text && $model->text !== '' ? Html::a('Переглянути повну відповідь',
                Url::to(['/admin/writtentasksanswers/a/view/' . $model->id]),
                [
                    'data-id' => $model->primaryKey,
                ])
            : '';
        },
    ]
];

$asset = \yii\easyii\modules\writtentasksanswers\assets\WrittenTasksAnswersAsset::register($this);
?>

<?= $this->render('_menu') ?>
    <br>
<?php \yii\widgets\Pjax::begin(['timeout' => 5000, 'id' => 'written-tasks']); ?>
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

$this->registerJs('WrittenTasksAnswersIndex(' . $pageOptions . ')');
?>