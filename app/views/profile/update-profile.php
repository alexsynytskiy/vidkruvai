<?php
/** @var \app\models\SiteUser $model */

use app\components\AppMsg;
use yii\widgets\ActiveForm;

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet update-profile">
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
                                <div class="step-title"><?= 'Редагування профілю' ?></div>
                                <?php
                                    $form = ActiveForm::begin([
                                        'id' => 'user-form',
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
                                        <?= $form->field($model, 'email')->textInput([
                                            'maxlength' => true,
                                            'placeholder' => AppMsg::t('E-Mail')
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
                                                    isset($model->avatar) ? \yii\easyii\helpers\Image::thumb($model->avatar, 240) : null
                                                ],
                                                'initialPreviewAsData' => true,
                                                'initialPreviewConfig' => [
                                                    [
                                                        'url' => \yii\helpers\Url::to(['/profile/clear-image/',
                                                            ['id' => $model->primaryKey, 'className' => \app\models\SiteUser::className()]
                                                        ]),
                                                    ],
                                                ],
                                            ] :
                                                [
                                                    'showRemove' => false,
                                                    'initialPreviewAsData' => true,
                                                ]
                                        ]); ?>
                                    </div>

                                    <?php if(isset($model->avatar)): ?>
                                        <?= '<div id="preview"></div>' ?>
                                    <?php endif; ?>

                                    <div class="form-group clearfix">
                                        <?= $form->field($model, 'userPassword')->passwordInput([
                                            'maxlength' => true,
                                            'placeholder' => AppMsg::t('Новый пароль'),
                                        ]) ?>
                                    </div>

                                    <div class="form-group clearfix">
                                        <?= $form->field($model, 'passwordRepeat')->passwordInput([
                                            'maxlength' => true,
                                            'placeholder' => AppMsg::t('Повторіть новий пароль'),
                                        ]) ?>
                                    </div>

                                    <?= \yii\helpers\Html::submitButton(AppMsg::t('Зберегти'), ['class' => 'link-button']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>
