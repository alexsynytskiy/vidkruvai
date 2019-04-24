<?php
/** @var $model \app\models\SiteUser */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$module = $this->context->module->id;

$asset = \yii\easyii\modules\category\assets\CategoryAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

    <div class="col-md-12 form-z-index clearfix">

<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => "Назва"]) ?>

<?= $form->field($model, 'description')->textInput(['maxlength' => true, 'placeholder' => 'Опис']) ?>

<?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
    'data' => \app\models\definitions\DefCategory::getListStatuses(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => \app\components\AppMsg::t('Статус')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

<?= $form->field($model, 'enabled_after')->widget(\yii\easyii\widgets\DateTimePicker::className()); ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?php
$pageOptions = \yii\helpers\Json::encode([
]);

$this->registerJs('SiteUserForm(' . $pageOptions . ')');
?>