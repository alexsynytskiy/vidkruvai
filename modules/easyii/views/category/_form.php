<?php
use yii\easyii\helpers\Image;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;
use \yii\easyii\components\helpers\LanguageHelper;

$class = $this->context->categoryClass;
$settings = $this->context->module->settings;

?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>

<?php if(!empty($parent)) : ?>
    <div class="form-group field-category-title required">
        <label for="category-parent" class="control-label"><?= Yii::t('easyii', 'Parent category') ?></label>
        <select class="form-control" id="category-parent" name="parent">
            <option value="" class="smooth"><?= Yii::t('easyii', 'No') ?></option>
            <?php foreach($class::find()->asArray()->all() as $node) : ?>
                <option
                    value="<?= $node['category_id'] ?>"
                    <?php if($parent == $node['category_id']) echo 'SELECTED' ?>
                    style="padding-left: <?= $node['depth']*20 ?>px;"
                ><?= $node['title'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>

<?php if(isset($settings['color'])) : ?>
    <?= $form->field($model, 'color')->widget(\kartik\color\ColorInput::classname(), [
        'options' => ['placeholder' => 'Select color ..'],
    ]); ?>
<?php endif; ?>

<?php if($settings['categoryThumb']) : ?>
    <?= $form->field($model, 'image')->widget(\kartik\file\FileInput::className(), [
        'options' => [
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showRemove' => false,
            'initialPreview' => [
                (isset($model->image)) ? Image::thumb($model->image, 240) : null
            ],
            'initialPreviewAsData' => true,
            'initialPreviewConfig' => [
                [
                    'url' => Url::to(['/admin/'.$this->context->moduleName.'/a/clear-image', 'id' => $model->primaryKey]),
                ],
            ],
        ]
    ]); ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>