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

<?= $form->field($model, 'status')->widget(\kartik\select2\Select2::className(), [
    'data' => \app\models\definitions\DefTeam::getStatuses(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => \app\components\AppMsg::t('Статус')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

<div class="labels-text">
    <div class="team-items mb-0">
        <div class="row">
            <div class="col-md-12 clearfix">
                <?php foreach ($model->teamUsers as $member): ?>
                    <?= $this->render('_team-member', [
                        'member' => $member,
                    ]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        Школа
        <div class="text-block"><?= 'Група ' . $model->getSchool()->getFullName() ?></div>
    </div>
    <div class="col-md-6">
        Уровень
        <div class="text-block"><?= 'Група ' . $model->level->levelgroup->name . ', Уровень ' .
            $model->level->num ?></div>
    </div>
    <div class="col-md-6">
        Опыта на уровне
        <div class="text-block"><?= $model->level_experience ?></div>
    </div>
    <div class="col-md-6">
        Опыта всего
        <div class="text-block"><?= $model->total_experience ?></div>
    </div>
</div>
    <br><br>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?php
$pageOptions = \yii\helpers\Json::encode([
]);

$this->registerJs('SiteUserForm(' . $pageOptions . ')');
?>