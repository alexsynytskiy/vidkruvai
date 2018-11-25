<?php

/* @var $this yii\web\View */
/* @var $task \app\models\WrittenTask */
/* @var $answer \app\models\WrittenTaskAnswer */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile task">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <div class="profile-info-main written-task clearfix">
                            <div class="title-part">
                                <div class="title written-title"><?= $task->name ?></div>
                            </div>
                            <div class="text">
                                <?= $task->description ?>
                            </div>

                            <?php if(!$answer->text && Yii::$app->siteUser->identity->isCaptain()): ?>
                                <?php $form = \yii\widgets\ActiveForm::begin([
                                    'enableAjaxValidation' => true,
                                    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
                                ]); ?>

                                <?= $form->field($answer, 'text')->widget(\yii\easyii\widgets\Redactor::className(), [
                                    'options' => [
                                        'minHeight' => 400,
                                        'imageUpload' => \yii\helpers\Url::to(['/admin/redactor/upload', 'dir' => 'tasks']),
                                        'fileUpload' => \yii\helpers\Url::to(['/admin/redactor/upload', 'dir' => 'tasks']),
                                        'plugins' => ['fullscreen']
                                    ]
                                ]) ?>

                                <?= \yii\helpers\Html::submitButton(Yii::t('easyii', 'Відправити відповідь'),
                                    ['class' => 'btn btn-primary send-written-task']) ?>
                                <?php \yii\widgets\ActiveForm::end(); ?>

                            <?php elseif($answer->text): ?>
                                <br>
                                <div class="title written-title">Ваша відповідь, яку ми отримали:</div>
                                <div class="text">
                                    <?= $answer->text ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>
