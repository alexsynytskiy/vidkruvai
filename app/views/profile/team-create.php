<?php
/** @var \app\models\forms\TeamCreateForm $model */

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
                    <div class="step-title"><?= 'Створити команду' ?></div>
                    <div class="user-form-edit">
                        <div class="row">
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'create-team-form',
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
                                    <?= $form->field($model, 'name')->textInput([
                                        'maxlength' => true,
                                        'placeholder' => AppMsg::t('Назва команди')
                                    ]) ?>
                                </div>

                                <div class="form-group clearfix">
                                    <?= $form->field($model, 'emails')->hiddenInput() ?>
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
                                                    'url' => \yii\helpers\Url::to(['/profile/clear-image/' . $model->primaryKey]),
                                                ],
                                            ],
                                        ]
                                    ]); ?>
                                </div>

                                <?= \yii\helpers\Html::submitButton(AppMsg::t('Підтвердити та створити команду'), ['class' => 'link-button']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>

