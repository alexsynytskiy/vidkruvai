<?php

use app\components\AppMsg;
use \app\models\definitions\DefTeam;

/* @var $this yii\web\View */

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
                                <div class="profile-team-preview clearfix">
                                    <div class="left-side">
                                        <img src="<?= $user->team->avatar ?: $baseUrl . '/img/default-avatar.png' ?>" class="avatar">
                                        <div class="status"><?= $user->team->total_experience ?></div>
                                    </div>
                                    <div class="right-side">
                                        <div class="clearfix">
                                            <div class="name">
                                                <?= $user->team->name ?>
                                            </div>
                                            <?php if ($user->team->status !== DefTeam::STATUS_ACTIVE): ?>
                                                <div class="team-status">
                                                    <?= AppMsg::t('(' . DefTeam::getStatusText($user->team->status)) . ')'?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($user->isCaptain()): ?>
                                                <a href="<?= \yii\helpers\Url::to(['team/update-team']) ?>">
                                                    <div class="profile-edit"></div>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="school"><?= $user->school->getFullName() ?></div>

                                        <div class="black-panel mb-50 pb-20 pt-20-i personal-team">
                                            <div class="progress-block pt-0 mt-0">
                                                <div class="progress-top mb-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="progress-title mb-10">
                                                                <h3>
                                                                    <?= AppMsg::t('Учасники'); ?>
                                                                </h3>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="team-items mb-0">
                                                    <div class="row">
                                                        <div class="col-md-12 clearfix">
                                                            <?php foreach ($user->team->teamUsers as $member): ?>
                                                                <?= $this->render('/_blocks/_team-member', [
                                                                    'member' => $member,
                                                                ]) ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="profile-personal-preview clearfix">
                                    <div class="right-side">
                                        <div class="black-panel mb-50 pb-20 pt-20-i personal-team">
                                            <div class="progress-block pt-0 mt-0">
                                                <div class="progress-top mb-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="progress-title mb-10 pl-32">
                                                                <h3>
                                                                    <?= AppMsg::t('Команда'); ?>
                                                                </h3>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="team-items mb-0 pl-32">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <?php if (!$user->team && !$preview): ?>
                                                                <?= \yii\helpers\Html::a(AppMsg::t('Створити команду'),
                                                                    ['/team/create-team'],
                                                                    [
                                                                        'class' => 'button mt-0',
                                                                    ]) ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
]);

$this->registerJs('ProfilePage(' . $pageOptions . ')');
?>
