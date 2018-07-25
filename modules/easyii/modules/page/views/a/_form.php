<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;
use \yii\easyii\components\helpers\LanguageHelper;

?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['class' => 'model-form']
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

        <?= $form->field($model, 'text')->widget(Redactor::className(),[
            'options' => [
                'minHeight' => 400,
                'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'page']),
                'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'page']),
                'plugins' => ['fullscreen'],
                'convertDivs'     => false,
                'removeEmptyTags' => false,
                'paragraphize'    => false,
                'pastePlainText'  => true
            ]
        ]) ?>
    </div>
    <div id="en" class="tab-pane fade">
        <br>
        <?= $form->field($model, 'title_en')->label(LanguageHelper::getMultilingualFieldLabel($model, 'title_en')) ?>

        <?= $form->field($model, 'text_en')
            ->label(LanguageHelper::getMultilingualFieldLabel($model, 'text_en'))
            ->widget(Redactor::className(),[
                'options' => [
                    'minHeight' => 400,
                    'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'page']),
                    'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'page']),
                    'plugins' => ['fullscreen'],
                    'convertDivs'     => false,
                    'removeEmptyTags' => false,
                    'paragraphize'    => false,
                    'pastePlainText'  => true
                ]
            ]) ?>
    </div>
</div>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>