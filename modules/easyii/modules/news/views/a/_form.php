<?php
/** @var $model yii\easyii\modules\news\models\News */

use yii\easyii\helpers\Image;
use yii\easyii\widgets\DateTimePicker;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \yii\easyii\components\helpers\CategoryHelper;

$module = $this->context->module->id;
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

<?= $form->field($model, 'category')->widget(\kartik\select2\Select2::className(), [
    'data' => CategoryHelper::getCategories(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => Yii::t('easyii', 'No')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

<?= $form->field($model, 'title') ?>

<?= $form->field($model, 'short')->textarea() ?>

<?= $form->field($model, 'text')->widget(Redactor::className(), [
    'options' => [
        'minHeight' => 400,
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'news']),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'news']),
        'plugins' => ['fullscreen']
    ]
]) ?>

<?php if ($this->context->module->settings['enableThumb']) : ?>
    <?= $form->field($model, 'image')->widget(\kartik\file\FileInput::className(), [
        'options' => [
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'initialPreview' => [
                isset($model->image) ? Image::thumb($model->image, 240) : null
            ],
            'initialPreviewAsData' => true,
            'initialPreviewConfig' => [
                [
                    'url' => Url::to(['/admin/' . $module . '/a/clear-image', 'id' => $model->primaryKey]),
                ],
            ],
        ]
    ]); ?>
<?php endif; ?>

    <?= $form->field($model, 'time')->widget(DateTimePicker::className()); ?>

<?php if($this->context->module->settings['enableTags']) : ?>
    <?= $form->field($model, 'tagNames')->widget(\yii\easyii\widgets\TagsInput::className()) ?>
<?php endif; ?>

<?php if (IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
