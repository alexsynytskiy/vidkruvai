<?php

use yii\helpers\Url;

/* @var $showUserInfo bool */

$totalNews = \yii\easyii\modules\news\models\News::getUserNewsCounters();
$totalNews = $totalNews > 0 ? $totalNews : null;

$showUserInfo = isset($showUserInfo) ? $showUserInfo : true;
$user = \Yii::$app->siteUser->identity;

$currentPage = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;

$profile = '';
$team = '';
$tasks = '';
$messages = '';
$news = '';

switch ($controller) {
    case 'profile':
        if($currentPage === 'news' || $currentPage === 'news-item') {
            $news = 'active';
        }
        else {
            $profile = 'active';
        }

        break;
    case 'team':
        if($currentPage === 'tasks') {
            $tasks = 'active';
        }
        else {
            $team = 'active';
        }

        break;
}

?>

<div class="cabinet-menu nopadding">
    <div class="wrapper">
        <?php if ($showUserInfo): ?>
            <a href="<?= Url::to('/profile') ?>">
                <div class="profile-info">
                    <img src="<?= $user->avatar ?>" class="avatar">
                    <div class="name"><?= $user->getFullName() ?></div>
                    <div class="school"><?= $user->school ?></div>
                    <div class="rating"><?= $user->total_experience ?></div>
                </div>
            </a>
        <?php endif; ?>

        <div class="menu-link <?= $profile ?>">
            <div class="icon"></div>
            <a href="<?= Url::to(['profile/']) ?>">
                <div class="text">Профіль</div>
            </a>
        </div>
        <div class="menu-link <?= $team ?>">
            <div class="icon"></div>
            <a href="<?= Url::to([$user->team ? '/team' : '/team/create-team']) ?>">
                <div class="text">Команда</div>
            </a>
        </div>
        <div class="menu-link <?= $tasks ?>">
            <div class="icon"></div>
            <div class="text">Завдання</div>
        </div>
        <div class="menu-link <?= $messages ?>">
            <div class="icon"></div>
            <div class="text">Повідомлення</div>
        </div>
        <div class="menu-link <?= $news ?>">
            <div class="icon">
                <?php if ($totalNews): ?>
                    <div class="new-count news-unread"><?= $totalNews ?></div><?php endif; ?>
            </div>
            <a href="/profile/news/">
                <div class="text">Новини</div>
            </a>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <a href="<?= Url::to(['profile/logout']) ?>">
                <div class="text">Вихід</div>
            </a>
        </div>
    </div>
</div>
