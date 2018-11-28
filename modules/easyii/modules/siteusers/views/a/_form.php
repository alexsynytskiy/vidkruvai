<?php
/** @var $model \app\models\SiteUser */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$module = $this->context->module->id;

$asset = \yii\easyii\modules\siteusers\assets\SiteUserAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

    <div class="col-md-12 form-z-index clearfix">
<?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Електронна пошта']) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => "Ім'я"]) ?>

<?= $form->field($model, 'surname')->textInput(['maxlength' => true, 'placeholder' => 'Прізвище']) ?>

<?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
    'data' => \app\models\definitions\DefSiteUser::getListStatuses(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => \app\components\AppMsg::t('Статус')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

<?= $form->field($model, 'role')->widget(\kartik\select2\Select2::className(), [
    'data' => \app\models\definitions\DefSiteUser::getListUserRoles(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => \app\components\AppMsg::t('Роль')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

<?= $form->field($model, 'school_id')->widget(\kartik\select2\Select2::className(), [
    'data' => \app\models\School::getList(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => \app\components\AppMsg::t('Школа')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

<?= $form->field($model, 'class')->textInput(['maxlength' => true, 'placeholder' => 'Клас']) ?>

<?= $form->field($model, 'age')->textInput(['maxlength' => true, 'placeholder' => 'Вік']) ?>

<div class="labels-text">
    <div class="col-md-6">
        Кількість входів у систему
        <div class="text-block"><?= $model->login_count ?></div>
    </div>
    <div class="col-md-6">
        Прочитано правила для коричтувачів
        <div class="text-block"><?= $model->agreement_read ? 'Да' : 'Нет' ?></div>
    </div>
    <div class="col-md-6">
        Мова
        <div class="text-block"><?= $model->language ?></div>
    </div>
    <div class="col-md-6">
        Рівень
        <div class="text-block"><?= 'Група рівнів ' . $model->level->levelgroup->name . ', Рівень ' .
            $model->level->num ?></div>
    </div>
    <div class="col-md-6">
        Кількість балів на поточному рівні
        <div class="text-block"><?= $model->level_experience ?></div>
    </div>
    <div class="col-md-6">
        Сумма балів на рахунку
        <div class="text-block"><?= $model->total_experience ?></div>
    </div>
</div>
<br>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?php
$pageOptions = \yii\helpers\Json::encode([
]);

$this->registerJs('SiteUserForm(' . $pageOptions . ')');
?>