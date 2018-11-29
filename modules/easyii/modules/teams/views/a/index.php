<?php

/* @var $this yii\web\View */

/* @var $data yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Teams';

$gridColumns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:80px'],
    ],
    [
        'attribute' => 'name',
        'content' => function ($model) {
            /** @var \app\models\Team $model */
            return Html::a($model->name, Url::to(['/admin/' . $this->context->module->id . '/a/edit/', 'id' => $model->primaryKey]), [
                'data' => [
                    'pjax' => 0,
                ],]);
        },
    ],
    [
        'attribute' => 'school_name',
        'content' => function ($model) {
            /** @var \app\models\Team $model */
            return $model->school ? $model->school->getFullName() : '';
        },
    ],
    [
        'attribute' => 'captain',
        'content' => function ($model) {
            /** @var \app\models\Team $model */
            return $model->teamCaptain() ? $model->teamCaptain()->getFullName() : '';
        },
    ],
    [
        'label' => 'Верифікувати',
        'content' => function ($model) {
            return Html::checkbox('', $model->status === \app\models\definitions\DefTeam::STATUS_ACTIVE, [
                'class' => 'switch',
                'data-id' => $model->primaryKey,
                'data-link' => Url::to(['/admin/' . $this->context->module->id . '/a']),
            ]);
        },
    ],
    [
        'label' => 'Дії',
        'content' => function ($model) {
            return Html::a('Видалити команду', '#', [
                'data-id' => $model->primaryKey,
                'class' => 'remove-team'
            ]);
        },
    ]
];

$asset = \yii\easyii\modules\teams\assets\TeamAsset::register($this);
?>

<?= $this->render('_menu') ?>
    <br>
<?php \yii\widgets\Pjax::begin(['timeout' => 5000, 'id' => 'teams']); ?>
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
$pageOptions = \yii\helpers\Json::encode([
    'removeTeamUrl' => Url::to(['/admin/' . $this->context->module->id . '/a/remove-team'])
]);

$this->registerJs('TeamsIndex(' . $pageOptions . ')');
?>