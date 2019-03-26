<?php

/* @var $this yii\web\View */

/* @var $data yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Користувачі';

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
        'filter'    => \kartik\select2\Select2::widget([
            'model' => $searchModel,
            'attribute' => 'school_id',
            'data' => \app\models\School::getList(),
            'language' => Yii::$app->language,
            'options' => [
                'placeholder' => \app\components\AppMsg::t('Все'),
                'class' => 'reload-grid',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]),
        'content' => function ($model) {
            return $model->school ? $model->school->getFullName() : '';
        },
    ],
    [
        'label' => 'Статус',
        'content' => function ($model) {
            return Html::checkbox('', $model->status === \app\models\definitions\DefSiteUser::STATUS_ACTIVE, [
                'class' => 'switch',
                'data-id' => $model->primaryKey,
                'data-link' => Url::to(['/admin/' . $this->context->module->id . '/a']),
            ]);
        },
    ],
    [
        'label' => 'Дії',
        'content' => function ($model) {
            return Html::a('Скинути пароль', '#', [
                'data-pjax' => 0,
                'data-user-id' => $model->id,
                'class' => 'drop-user-password']
            );
        },
    ],
];

$asset = \yii\easyii\modules\siteusers\assets\SiteUserAsset::register($this);
?>

<?= $this->render('_menu') ?>

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