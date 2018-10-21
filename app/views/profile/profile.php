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
/* @var array $entityCredentials */

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
                                    <div class="image-cropper">
                                        <img src="<?= $user->avatar ?: $baseUrl . '/img/default-avatar-user.jpg' ?>" class="avatar">
                                    </div>
                                    <div class="status"><?= $user->total_experience ?></div>
                                </div>
                                <div class="right-side">
                                    <div class="clearfix block-title-dynamic">
                                        <div class="name">
                                            <?= $user->getFullName() ?>
                                            <a href="<?= \yii\helpers\Url::to(['profile/update-profile']) ?>">
                                                <div class="profile-edit"></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="school"><?= $user->school ? $user->school->getFullName() : '' ?></div>

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
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 text-center">
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
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
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
                                                                [$preview ? "/profile/{$entityCredentials['id']}/levels" : '/profile/levels'],
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
                                                                'userId' => $preview ? $entityCredentials['id'] : Yii::$app->siteUser->id,
                                                            ]) ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <?= \yii\helpers\Html::a(AppMsg::t('Всі досягнення'),
                                                                [$preview ? "/profile/{$entityCredentials['id']}/achievements" : '/profile/achievements'],
                                                                [
                                                                    'class' => 'button mt-0',
                                                                ]) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
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
