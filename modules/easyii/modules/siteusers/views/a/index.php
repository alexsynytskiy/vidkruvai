<?php

/* @var $this yii\web\View */

/* @var $data yii\data\ActiveDataProvider */
/* @var $stateStatistics array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Users';

$gridColumns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:80px'],
    ],
    [
        'attribute' => 'name',
        'content' => function ($model) {
            /** @var \app\models\SiteUser $model */
            return Html::a($model->getFullName(), Url::to(['/admin/' . $this->context->module->id . '/a/edit/', 'id' => $model->primaryKey]), [
                'data' => [
                    'pjax' => 0,
                ],]);
        },
    ],
    'email',
    [
        'attribute' => 'role',
        'content' => function ($model) {
            return \app\models\definitions\DefSiteUser::getUserRoleText($model->role);
        },
        'filter' => \kartik\select2\Select2::widget([
            'model' => $searchModel,
            'attribute' => 'role',
            'data' => \app\models\definitions\DefSiteUser::getListUserRoles(),
            'language' => Yii::$app->language,
            'options' => [
                'placeholder' => \app\components\AppMsg::t('Все'),
                'class' => 'reload-grid',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]),
    ],
    [
        'attribute' => 'school_id',
        'content' => function ($model) {
            return $model->school ? $model->school->getFullName() : '';
        },
    ],
    [
        'label' => 'Switch status',
        'content' => function ($model) {
            return Html::checkbox('', $model->status === \app\models\SiteUser::STATUS_ACTIVE, [
                'class' => 'switch',
                'data-id' => $model->primaryKey,
                'data-link' => Url::to(['/admin/' . $this->context->module->id . '/a']),
            ]);
        },
    ],
];

$asset = \yii\easyii\modules\siteusers\assets\SiteUserAsset::register($this);
?>

<?= $this->render('_menu') ?>
    <br>
    Статистика по областям
    <div style="height: 10px"></div>
<?php if (count($stateStatistics) > 0) : ?>
    <table class="table table-hover" style="width: 30%">
        <thead>
        <tr>
            <th><?= Yii::t('easyii', 'State') ?></th>
            <th><?= Yii::t('easyii', 'Users count') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($stateStatistics as $stateData) : ?>
            <tr>
                <td><?= $stateData['name'] ?></td>
                <td><?= $stateData['count'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>

<br>
<?php \yii\widgets\Pjax::begin(['timeout' => 5000, 'id' => 'site-users']); ?>
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
]);

$this->registerJs('SiteUserForm(' . $pageOptions . ')');
?>