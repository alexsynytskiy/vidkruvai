<?php
/** @var \app\models\forms\TeamCreateForm $model */
/** @var string $button */

use app\components\AppMsg;
use yii\widgets\ActiveForm;

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<?php
$form = ActiveForm::begin([
    'id' => 'create-team-form',
    'options' => [
        'class' => 'link-form clearfix',
        'enctype' => 'multipart/form-data',
    ],
    'fieldConfig' => [
        'template' => "{input}\n{error}",
    ],
    'enableClientValidation' => true,
]);
?>

    <div class="col-md-12 form-z-index">
        <div class="form-group clearfix">
            <?= $form->field($model, 'name')->textInput([
                'maxlength' => true,
                'placeholder' => AppMsg::t('Назва команди')
            ]) ?>
        </div>

        <div class="form-group clearfix">
            <?= $form->field($model, 'avatar')->widget(\kartik\file\FileInput::className(), [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => false
                ],
                'pluginOptions' => $model->avatar ? [
                    'showRemove' => false,
                    'initialPreview' => [
                        \yii\easyii\helpers\Image::thumb($model->avatar, 240)
                    ],
                    'initialPreviewAsData' => true,
                    'initialPreviewConfig' => [
                        [
                            'url' => \yii\helpers\Url::to([
                                    '/team/clear-image/' . $model->id . '/team/'
                            ]),
                        ],
                    ],
                ] : ['showRemove' => false, 'initialPreviewAsData' => false]
            ]); ?>
        </div>

        <div class="form-group clearfix" id="team-members-emails">
            <div class="col-md-12 col-xs-12">
                <?php if($model->isNewRecord): ?>
                    <?= $form->field($model, 'emails[]')->hiddenInput([
                        'value' => Yii::$app->siteUser->identity->email, 'id' => 'captain-id']) ?>
                    <?php for ($i = 1; $i <= 9; $i++): ?>
                        <div class="col-md-6 col-xs-12">
                            <?= $form->field($model, 'emails[]')->textInput([
                                'placeholder' => AppMsg::t('Учасник ') . $i
                            ]) ?>
                        </div>
                    <?php endfor; ?>
                <?php else: ?>
                    <?= $form->field($model, 'emails[]')->hiddenInput() ?>
                    <?php foreach ($model->emails as $email): ?>
                        <div class="col-md-6 col-xs-12">
                            <?= $form->field($model, 'emails[]')->textInput(['value' => $email]) ?>
                        </div>
                    <?php endforeach; ?>
                    <?php for ($i = 1 + count($model->emails); $i <= 9; $i++): ?>
                        <div class="col-md-6 col-xs-12">
                            <?= $form->field($model, 'emails[]')->textInput([
                                'placeholder' => AppMsg::t('Учасник ') . $i
                            ]) ?>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>

        <?= $form->field($model, 'captchaTeam')->widget(\yii\captcha\Captcha::className(), [
            'captchaAction' => 'validation/captcha',
            'options' => [
                'placeholder' => 'Код перевірки',
                'autocomplete' => 'off',
            ],
            'imageOptions' => [
                'data-toggle' => "tooltip",
                'data-placement' => "top",
                'title' => 'Оновити картинку',
            ],
            'template' => '<div class="media-body"><div class="pl-0" style="padding-right: 10px;">{input}</div></div><div class="media-right">{image}</div>',
        ]) ?>

        <?= \yii\helpers\Html::submitButton($button, ['class' => 'link-button', 'style' => ['float' => 'left']]) ?>
    </div>

<?php ActiveForm::end(); ?>