<?php

/* @var $this yii\web\View */

/* @var $data yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Категорії';

$gridColumns = [
    [
        'attribute' => 'id',
        'headerOptions' => ['style' => 'width:80px'],
    ],
    [
        'attribute' => 'name',
        'content' => function ($model) {
            /** @var \app\models\Category $model */
            return Html::a($model->name, Url::to(['/admin/' . $this->context->module->id . '/a/edit/', 'id' => $model->primaryKey]), [
                'data' => [
                    'pjax' => 0,
                ],]);
        },
    ],
    'description',
    [
        'attribute' => 'type',
        'content' => function ($model) {
            /** @var \app\models\Category $model */
            return \app\models\definitions\DefCategory::getListType($model->type);
        },
        'filter' => \kartik\select2\Select2::widget([
            'model' => $searchModel,
            'attribute' => 'type',
            'data' => \app\models\definitions\DefCategory::getListTypes(),
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
        'attribute' => 'enabled_after',
        'content' => function ($model) {
            /** @var \app\models\Category $model */
            return $model->enabled_after;
        },
    ],
    [
        'label' => 'Статус',
        'content' => function ($model) {
            return Html::checkbox('', $model->status === \app\models\definitions\DefCategory::STATUS_ACTIVE, [
                'class' => 'switch',
                'data-id' => $model->primaryKey,
                'data-link' => Url::to(['/admin/' . $this->context->module->id . '/a']),
            ]);
        },
    ],
];

$asset = \yii\easyii\modules\category\assets\CategoryAsset::register($this);
?>

<?= $this->render('_menu') ?>
<br>
<?php \yii\widgets\Pjax::begin(['timeout' => 5000, 'id' => 'categories']); ?>
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

$this->registerJs('CategoryForm(' . $pageOptions . ')');
?>