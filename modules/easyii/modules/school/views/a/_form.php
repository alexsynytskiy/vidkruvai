<?php
/** @var $model \app\models\forms\AddSchoolForm */

/** @var $cities array */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$module = $this->context->module->id;

$asset = \yii\easyii\modules\school\assets\SchoolAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

<div class="col-md-12 form-z-index clearfix">
    <?= $form->field($model, 'state_id')->widget(\kartik\select2\Select2::className(), [
        'data' => \app\models\State::getList(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => \app\components\AppMsg::t('Область')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'city_id')->widget(\kartik\select2\Select2::className(), [
        'data' => $cities,
        'language' => Yii::$app->language,
        'options' => ['class' => 'hidden', 'placeholder' => \app\components\AppMsg::t('Місто')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= Html::a('Міста немає у списку? Створи!', '#',
        ['id' => 'add-new-city', 'class' => 'hidden-link']) ?>

    <div class="create-new-city-form">
        <div class="info">Назва міста/села(без уточнюючих аббревіатур м, с, село, і тд.), назва району також не
            потрібна
        </div>
        <?= $form->field($model, 'city_name')->textInput(['maxlength' => true, 'placeholder' => 'Назва міста/села']) ?>
    </div>

    <?= $form->field($model, 'type_id')->widget(\kartik\select2\Select2::className(), [
        'data' => \app\models\SchoolType::getList(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => \app\components\AppMsg::t('Тип учбового закладу')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'school_number')->textInput(['maxlength' => true, 'placeholder' => '№ Учбового закладу']) ?>

    <?= $form->field($model, 'school_name')->textInput(['maxlength' => true, 'placeholder' => 'Назва учбового закладу']) ?>

    <?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

    <?php
    $pageOptions = \yii\helpers\Json::encode([
        'getStateCitiesUrl' => Url::to(['/admin/' . $module . '/a/get-state-cities'])
    ]);

    $this->registerJs('CreateSchoolPage(' . $pageOptions . ')');
    ?>
