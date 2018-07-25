<?php
/** yii\easyii\modules\lineup\models\Item $model */
use yii\easyii\helpers\Image;
use yii\easyii\widgets\DateTimePicker;
use yii\easyii\widgets\TagsInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;
use \yii\easyii\components\helpers\LanguageHelper;

$module = $this->context->module->id;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>


<ul class="nav nav-tabs">
    <li class="active">
        <a data-toggle="tab" href="#uk"><?= LanguageHelper::getLanguages()[LanguageHelper::LANG_UA] ?></a>
    </li>
    <li><a data-toggle="tab" href="#en"><?= LanguageHelper::getLanguages()[LanguageHelper::LANG_EN] ?></a></li>
</ul>

<div class="tab-content">
    <div id="uk" class="tab-pane fade in active">
        <br>
        <?= $form->field($model, 'title') ?>

        <?php if($this->context->module->settings['enableShort']) : ?>
            <?= $form->field($model, 'short')->textarea() ?>
        <?php endif; ?>

        <?= $form->field($model, 'text')->widget(Redactor::className(),[
            'options' => [
                'minHeight' => 100,
                'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'items']),
                'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'items']),
                'plugins' => ['fullscreen']
            ]
        ]) ?>
    </div>
    <div id="en" class="tab-pane fade">
        <br>
        <?= $form->field($model, 'title_en')->label(LanguageHelper::getMultilingualFieldLabel($model, 'title_en')) ?>

        <?php if($this->context->module->settings['enableShort']) : ?>
            <?= $form->field($model, 'short_en')->label(LanguageHelper::getMultilingualFieldLabel($model, 'short_en'))->textarea() ?>
        <?php endif; ?>

        <?= $form->field($model, 'text_en')
            ->label(LanguageHelper::getMultilingualFieldLabel($model, 'text_en'))
            ->widget(Redactor::className(),[
                'options' => [
                    'minHeight' => 100,
                    'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'items']),
                    'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'items']),
                    'plugins' => ['fullscreen']
                ]
            ]) ?>
    </div>
</div>

<?php if($this->context->module->settings['lineupThumb']) : ?>
    <?= $form->field($model, 'image')->widget(\kartik\file\FileInput::className(), [
        'options' => [
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'initialPreview' => $model->image,
            'initialPreviewAsData' => true,
            'initialPreviewConfig' => [
                [
                    'url' => Url::to(['/admin/'.$module.'/a/clear-image', 'id' => $model->primaryKey]),
                ],
            ],
        ]
    ]); ?>
<?php endif; ?>

<div class="col-lg-12">
    <div class="col-lg-6">
        <?= $form->field($model, 'fb_link') ?>

        <?= $form->field($model, 'tw_link') ?>

        <?= $form->field($model, 'youtube_link') ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'instagram_link') ?>

        <?= $form->field($model, 'soundcloud_link') ?>
    </div>
</div>

<div class="col-lg-12">
    <div class="col-lg-4">
        <?= $form->field($model, 'is_set')->widget(\kartik\switchinput\SwitchInput::className(), [
            'type' => \kartik\switchinput\SwitchInput::CHECKBOX
        ]); ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'position') ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'date')->widget(\kartik\daterange\DateRangePicker::className(), [
            'convertFormat' => true,
            'pluginOptions' => [
                'timePicker' => true,
                'timePickerIncrement' => 30,
                'startAttribute'      =>'start_time',
                'endAttribute'        =>'end_time',
                'locale' => [
                    'format' => 'd.m.Y H:i'
                ]
            ]
        ]); ?>
    </div>
</div>

<?php if($this->context->module->settings['enableTags']) : ?>
    <?= $form->field($model, 'tagNames')->widget(TagsInput::className()) ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>