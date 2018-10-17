<?php
/** @var \app\models\SiteUser $model */

use app\components\AppMsg;
use yii\widgets\ActiveForm;

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile update-profile">
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
                                                    isset($model->avatar) ?
                                                        \yii\easyii\helpers\Image::thumb($model->avatar, 240) :
                                                        null
                                                ],
                                                'initialPreviewAsData' => true,
                                                'initialPreviewConfig' => [
                                                    [
                                                        'url' => \yii\helpers\Url::to([
                                                            '/profile/clear-image/' . $model->primaryKey . '/siteuser/'
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

                                    <?php if(!$model->school_id): ?>
                                        <div class="school-not-found">Школу, вказану при реєстрації не знайдено!</div>
                                    <?php endif; ?>

                                    <?= $form->field($model, 'school_id')->widget(\kartik\select2\Select2::className(), [
                                        'data' => \app\models\School::getList(),
                                        'language' => Yii::$app->language,
                                        'options' => ['placeholder' => \app\components\AppMsg::t('Школа')],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]); ?>

                                    <?php if(!$model->school_id): ?>
                                        <div class="create-new-school">
                                            Якщо ти не знайшов свою школу у списку - переходь за посиланням та створи її, після цього повернись,
                                            та спробуй знайти ще раз.
                                            <?= \yii\helpers\Html::a('Додати школу',
                                                \yii\helpers\Url::to(['/add-school']), ['class' => 'link-button']) ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($model->avatar)): ?>
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
