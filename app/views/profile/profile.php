<?php

use app\components\AppMsg;
use app\components\helpers\StockHelper;
use yii\helpers\Html;
use \app\models\definitions\DefTeam;

/* @var $this yii\web\View */
/* @var $showUserInfo bool */
/* @var $achievements array */
/* @var $levelInfo array */
/* @var $previousLevels array */
/* @var $nextLevels array */
/* @var $preview boolean */
/* @var array $userCredentials */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar', ['showUserInfo' => $showUserInfo]) ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <div class="profile-info-main clearfix">
                            <div class="profile-personal-preview clearfix">
                                <div class="left-side">
                                    <img src="<?= $user->avatar ?>" class="avatar">
                                    <div class="status"><?= $user->total_experience ?></div>
                                </div>
                                <div class="right-side">
                                    <div class="clearfix block-title-dynamic">
                                        <div class="name"><?= $user->getFullName() ?></div>
                                        <a href="<?= \yii\helpers\Url::to(['profile/update-profile']) ?>">
                                            <div class="profile-edit"></div>
                                        </a>
                                    </div>
                                    <div class="school"><?= $user->school ?></div>

                                    <?php if (count($previousLevels) || count($nextLevels) || !empty($levelInfo)): ?>
                                        <div class="black-panel mb-50 pb-20 pt-20-i personal-levels">
                                            <div class="progress-block pt-0 mt-0">
                                                <div class="progress-top mb-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="progress-title level-title mt-30">
                                                                <h3>
                                                                    <?= AppMsg::t('Рівні'); ?>
                                                                </h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress-items mb-0">
                                                    <div class="row">
                                                        <?php StockHelper::renderLevelsList($this, $previousLevels) ?>

                                                        <?php if (!empty($levelInfo)): ?>
                                                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="progress-item">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                                                            <div class="knob-block">
                                                                                <input type="text"
                                                                                       value="<?= $levelInfo['currentLevelExp'] ?>"
                                                                                       data-min="0"
                                                                                       data-max="<?= $levelInfo['currentLevelMaxExpProfile'] ?>"
                                                                                       class="dial">
                                                                                <div class="counter">
                                                                                    <?= AppMsg::t('{currentExp} <span>/ {maxExp}</span><span class="descr">досвіду</span>', [
                                                                                        'currentExp' => $levelInfo['currentLevelExp'],
                                                                                        'maxExp' => $levelInfo['currentLevelMaxExpProfile'],
                                                                                    ]); ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                            <div class="progress-descr <?= isset($levelInfo['currentLevelAward'][0]) ? '' : 'empty' ?>">
                                                                                <h5><?= AppMsg::t('{num}-й рівень', ['num' => $levelInfo['currentLevel']]); ?></h5>
                                                                                <?php if (isset($levelInfo['currentLevelAward'][0]) && $levelInfo['currentLevelAward'][0]->name): ?>
                                                                                    <p>
                                                                                        <?= AppMsg::t('Нагорода: <span>{award}</span>', [
                                                                                            'award' => Html::encode($levelInfo['currentLevelAward'][0]->name),
                                                                                        ]); ?>
                                                                                    </p>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php StockHelper::renderLevelsList($this, $nextLevels) ?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <?= Html::a(AppMsg::t('Всі рівні'),
                                                                [$preview ? "/profile/{$userCredentials['id']}/levels" : '/profile/levels'],
                                                                [
                                                                    'class' => 'button m-0-auto-i',
                                                                ]) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (count($achievements)): ?>
                                        <div class="black-panel mb-50 pb-20 pt-20-i personal-achievements">
                                            <div class="progress-block pt-0 mt-0">
                                                <div class="progress-top mb-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="progress-title mb-10">
                                                                <h3>
                                                                    <?= AppMsg::t('Досягнення'); ?>
                                                                </h3>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="progress-items mb-0">
                                                    <div class="row">
                                                        <?php foreach ($achievements as $achievement): ?>
                                                            <?= $this->render('_achievement-item', [
                                                                'model' => $achievement,
                                                                'userId' => $preview ? $userCredentials['id'] : Yii::$app->siteUser->id,
                                                            ]) ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <?= \yii\helpers\Html::a(AppMsg::t('Всі досягнення'),
                                                                [$preview ? "/profile/{$userCredentials['id']}/achievements" : '/profile/achievements'],
                                                                [
                                                                    'class' => 'button mt-0',
                                                                ]) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!$user->team): ?>
                                        <div class="black-panel mb-50 pb-20 pt-20-i personal-team">
                                            <div class="progress-block pt-0 mt-0">
                                                <div class="progress-top mb-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="progress-title mb-10">
                                                                <h3>
                                                                    <?= AppMsg::t('Команда'); ?>
                                                                </h3>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="team-items mb-0">
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
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if ($user->team): ?>
                                <div class="profile-team-preview clearfix">
                                    <div class="left-side">
                                        <img src="<?= $user->team->avatar ?>" class="avatar">
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
                                        <div class="school"><?= $user->school ?></div>

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
                                                        <div class="col-md-12">
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
