<?php
use yii\easyii\helpers\Image;
use yii\easyii\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;
use \yii\easyii\components\helpers\LanguageHelper;

$settings = $this->context->module->settings;
$module = $this->context->module->id;
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form', 'enableAjaxValidation' => true,]
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

            <?php if($settings['itemDescription']) : ?>
                <?= $form->field($model, 'description')->widget(Redactor::className(),[
                    'options' => [
                        'minHeight' => 400,
                        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
                        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
                        'plugins' => ['fullscreen']
                    ]
                ]) ?>
            <?php endif; ?>
        </div>
        <div id="en" class="tab-pane fade">
            <br>
            <?= $form->field($model, 'title_en')->label(LanguageHelper::getMultilingualFieldLabel($model, 'title_en')) ?>

            <?php if($settings['itemDescription']) : ?>
                <?= $form->field($model, 'description_en')->widget(Redactor::className(),[
                    'options' => [
                        'minHeight' => 400,
                        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
                        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
                        'plugins' => ['fullscreen']
                    ]
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

<?php if($settings['itemThumb']) : ?>
    <?= $form->field($model, 'image')->widget(\kartik\file\FileInput::className(), [
        'options' => [
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'initialPreview' => [
                (isset($model->image)) ? $model->image : null
            ],
            'initialPreviewAsData' => true,
            'initialPreviewConfig' => [
                [
                    'url' => Url::to(['/admin/'.$module.'/a/clear-image', 'id' => $model->primaryKey]),
                ],
            ],
        ]
    ]); ?>
<?php endif; ?>


<?= $dataForm ?>

<?= $form->field($model, 'available') ?>
<?= $form->field($model, 'price') ?>
<?= $form->field($model, 'discount') ?>

<?= $form->field($model, 'time')->widget(DateTimePicker::className()); ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>