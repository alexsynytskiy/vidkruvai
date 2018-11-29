<?php
/** @var $model \app\models\Team */

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
<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => "Ім'я"]) ?>

<?= $form->field($model, 'avatar')->widget(\kartik\file\FileInput::className(), [
    'options' => [
        'accept' => 'image/*'
    ],
    'pluginOptions' => $model->avatar ? [
        'showRemove' => false,
        'initialPreview' => [
            isset($model->avatar) ?
                \yii\easyii\helpers\Image::thumb($model->avatar, 240) :
                null
        ],
        'initialPreviewAsData' => true,
        'initialPreviewConfig' => [
            [
                'url' => \yii\helpers\Url::to(['/admin/' . $module . '/a/clear-image', 'id' => $model->primaryKey]),
            ],
        ],
    ] :
        [
            'showRemove' => false,
            'initialPreviewAsData' => true,
        ]
]); ?>

<?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
    'data' => \app\models\definitions\DefTeam::getStatuses(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => \app\components\AppMsg::t('Статус верифікації')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

    <div class="labels-text">
        <div class="team-items" style="margin: 30px 0 20px;">
            <?= $this->render('_team-members', [
                'members' => $model->teamUsers,
            ]) ?>
        </div>
        <div class="col-md-6">
            Школа
            <div class="text-block"><?= $model->getSchool()->getFullName() ?></div>
        </div>
        <div class="col-md-6">
            Рівень команди
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
    <br><br>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?php
$pageOptions = \yii\helpers\Json::encode([
]);

$this->registerJs('TeamsForm(' . $pageOptions . ')');
?>