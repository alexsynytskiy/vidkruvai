<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */
/* @var $wrongAnswers array */
/* @var $group \app\models\Test */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile team">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <div class="profile-info-main clearfix">
                            <?php if ($user->team): ?>
                                <div class="image-group">
                                    <img src="/app/media/img/<?= $group->hash ?>.svg">
                                </div>

                                <div class="congrads">
                                    Вітаємо з проходженням <?= $group->id ?>-го блоку запитань!
                                </div>

                                <div class="new-info about-event">
                                    <?= $group->completed_data ?>
                                </div>

                                <?php if ($wrongAnswers): ?>
                                    <div class="new-info about-event">
                                        <div class="block-finished-sub-title">Тепер ти знаеш що:</div>
                                        <?php foreach ($wrongAnswers as $answer): ?>
                                            <?= $answer ?><br>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?= \yii\helpers\Url::to(['/profile']) ?>">
                                    <?= \yii\helpers\Html::submitButton('Далі', [
                                        'class' => 'link-button',
                                    ]) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>
