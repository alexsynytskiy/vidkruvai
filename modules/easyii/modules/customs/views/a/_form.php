<?php
/** @var $model yii\easyii\modules\customs\models\Customs */

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use \yii\easyii\components\helpers\LanguageHelper;

$module = $this->context->module->id;
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>


<?= $form->field($model, 'country')->widget(Select2::classname(), [
    'data' => LanguageHelper::getCountries(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => Yii::t('easyii', 'No')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

<?= $form->field($model, 'latitude') ?>
<?= $form->field($model, 'longitude') ?>

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
                    'minHeight' => 400,
                    'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'customs']),
                    'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'customs']),
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
                        'minHeight' => 400,
                        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'customs']),
                        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'customs']),
                        'plugins' => ['fullscreen']
                    ]
                ]) ?>
        </div>
    </div>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>