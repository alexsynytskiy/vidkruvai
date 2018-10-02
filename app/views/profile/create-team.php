<?php
/** @var \app\models\forms\TeamCreateForm $model */

use app\components\AppMsg;
use yii\widgets\ActiveForm;

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet create-team">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>

                        <div id="profile-info" class="clearfix">
                            <div class="user-form-edit">
                                <div class="step-title"><?= 'Створити команду' ?></div>
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
                                                'initialPreviewAsData' => false,
                                            ] : ['showRemove' => false, 'initialPreviewAsData' => false]
                                        ]); ?>
                                    </div>

                                    <div class="form-group clearfix" id="team-members-emails">
                                        <div class="col-md-12">
                                            <?php if($model->isNewRecord): ?>
                                                <?= $form->field($model, 'emails[]')->hiddenInput([
                                                        'value' => Yii::$app->siteUser->identity->email, 'id' => 'captain-id']) ?>
                                                <?php for ($i = 1; $i <= 9; $i++): ?>
                                                    <div class="col-md-6">
                                                        <?= $form->field($model, 'emails[]')->textInput([
                                                            'placeholder' => AppMsg::t('Учасник ') . $i
                                                        ]) ?>
                                                    </div>
                                                <?php endfor; ?>
                                            <?php else: ?>
                                                <?php foreach ($model->emails as $email): ?>
                                                    <div class="col-md-6">
                                                        <?= $form->field($model, 'emails[]')->textInput() ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?= $form->field($model, 'captchaTeam')->widget(\yii\captcha\Captcha::className(), [
                                        'captchaAction' => 'profile/captcha',
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

                                    <?= \yii\helpers\Html::submitButton(AppMsg::t('Підтвердити та створити команду'),
                                        ['class' => 'link-button', 'style' => ['float' => 'left']]) ?>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>
