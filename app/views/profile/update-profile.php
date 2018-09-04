<?php
/** @var \app\models\SiteUser $model */

use app\components\AppMsg;
use yii\widgets\ActiveForm;

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-header') ?>
            </div>
            <div class="content-left-fixed">
                <div id="profile-info" class="clearfix">
                    <div class="step-title"><?= 'Редагування профілю' ?></div>
                    <div class="user-form-edit">
                        <div class="row">
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'user-form',
                                'options' => [
                                    'class' => 'link-form',
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
                                            'accept' => 'image/*'
                                        ],
                                        'pluginOptions' => [
                                            'showRemove' => false,
                                            'initialPreview' => [
                                                isset($model->avatar) ? \yii\easyii\helpers\Image::thumb($model->avatar, 240) : null
                                            ],
                                            'initialPreviewAsData' => true,
                                            'initialPreviewConfig' => [
                                                [
                                                    'url' => \yii\helpers\Url::to(['/admin/siteuser/a/clear-image', 'id' => $model->primaryKey]),
                                                ],
                                            ],
                                        ]
                                    ]); ?>
                                </div>

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
        </article>
    </div>
</div>

