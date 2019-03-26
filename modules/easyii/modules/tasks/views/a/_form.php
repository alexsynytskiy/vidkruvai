<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;
use \yii\easyii\widgets\DateTimePicker;

$module = $this->context->module->id;

$asset = \yii\easyii\modules\tasks\assets\TasksAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

<div class="col-md-12">
    <?= $form->field($model, 'task_name')->textInput(['maxlength' => true, 'placeholder' => 'Назва завдання']) ?>

    <?= $form->field($model, 'task_short')->textInput(['maxlength' => true, 'placeholder' => 'Короткий опис']) ?>

    <?= $form->field($model, 'task_description')->widget(\yii\easyii\widgets\Redactor::className(), [
        'options' => [
            'minHeight' => 400,
            'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'tasks']),
            'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'tasks']),
            'plugins' => ['fullscreen']
        ]
    ]) ?>

    <?= $form->field($model, 'task_image')->widget(\kartik\file\FileInput::className(), [
        'options' => [
            'accept' => 'image/*'
        ],
        'pluginOptions' => $model->task && $model->task->image ? [
            'showRemove' => false,
            'initialPreview' => [
                \yii\easyii\helpers\Image::thumb($model->task_image, 240)
            ],
            'initialPreviewAsData' => true,
            'initialPreviewConfig' => [
                [
                    'url' => Url::to(['/admin/' . $module . '/a/clear-image', 'id' => $model->task->primaryKey]),
                ],
            ],
        ] : ['showRemove' => false, 'initialPreviewAsData' => false],
    ]); ?>

    <div class="col-md-4"><?= $form->field($model, 'task_required')->widget(\kartik\select2\Select2::className(), [
        'data' => \app\models\definitions\DefTask::getRequired(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => \app\components\AppMsg::t('Обов\'язкове завдання?')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?></div>

    <div class="col-md-4"><?= $form->field($model, 'task_type')->widget(\kartik\select2\Select2::className(), [
        'data' => \app\models\definitions\DefTask::getTypes(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => \app\components\AppMsg::t('Тип завдання')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?></div>

    <div class="col-md-4"><?= $form->field($model, 'task_award')->widget(\kartik\select2\Select2::className(), [
        'data' => \app\models\Award::getListAwards(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => \app\components\AppMsg::t('Нагорода')],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true
        ],
    ]); ?></div>

    <div class="col-md-6"><?= $form->field($model, 'task_starting_at')->widget(DateTimePicker::className()); ?></div>
    <div class="col-md-6"><?= $form->field($model, 'task_ending_at')->widget(DateTimePicker::className()); ?></div>

    <div class="clearfix"></div>

    <?= $form->field($model, 'task_status')->widget(\kartik\select2\Select2::className(), [
        'data' => \app\models\definitions\DefTask::getStatuses(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => \app\components\AppMsg::t('Статус')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
