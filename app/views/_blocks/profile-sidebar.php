<?php

use yii\helpers\Url;

/* @var $showUserInfo bool */

$asset = \app\assets\AppAsset::register($this);
$baseUrl = $asset->baseUrl;

$totalNews = \yii\easyii\modules\news\models\News::getUserNewsCounters();
$totalNews = $totalNews > 0 ? $totalNews : null;

$totalTasks = \app\models\Task::getUserTasksCounters();
$totalTasks = $totalTasks > 0 ? $totalTasks : null;

$showUserInfo = isset($showUserInfo) ? $showUserInfo : true;
$user = \Yii::$app->siteUser->identity;

$currentPage = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;

$profile = '';
$team = '';
$tasks = '';
$messages = '';
$news = '';
$store = '';
$rating = '';
$progress = '';

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
        $team = 'active';
        break;
    case 'tasks':
        $tasks = 'active';
        break;
    case 'store':
        $store = 'active';
        break;
    case 'rating':
        $rating = 'active';
        break;
    case 'progress':
        $progress = 'active';
        break;
}

?>

<div class="cabinet-menu nopadding">
    <div class="wrapper">
        <?php if ($showUserInfo): ?>
            <a href="<?= Url::to('/profile') ?>">
                <div class="profile-info clearfix">
                    <div class="image-cropper">
                        <img src="<?= $user->avatar ?: $baseUrl . '/img/default-avatar-user.jpg' ?>" class="avatar">
                    </div>
                    <div class="name"><?= $user->getFullName() ?></div>
                </div>
            </a>
        <?php endif; ?>

        <div class="menu-link <?= $profile ?>">
            <div class="icon">
                <i class="fa fa-user" style="font-size: 21px;"></i>
            </div>
            <a href="<?= Url::to(['profile/']) ?>">
                <div class="text">Профіль</div>
            </a>
        </div>
        <div class="menu-link <?= $team ?>">
            <div class="icon">
                <i class="fa fa-users" style="font-size: 17px;"></i>
            </div>
            <a href="<?= Url::to([$user->team ? '/team' : '/team/create-team']) ?>">
                <div class="text">Команда</div>
            </a>
        </div>
        <div class="menu-link <?= $tasks ?>">
            <div class="icon">
                <i class="fa fa-list" style="font-size: 17px;"></i>
                <?php if ($totalTasks): ?>
                    <div class="tasks-count tasks-unread"><?= $totalTasks ?></div>
                <?php endif; ?>
            </div>
            <a href="<?= Url::to(['/tasks']) ?>">
                <div class="text">Завдання</div>
            </a>
        </div>
        <div class="menu-link <?= $store ?>">
            <div class="icon">
                <i class="fa fa-shopping-basket" style="font-size: 16px;"></i>
            </div>
            <a href="<?= Url::to(['store/']) ?>">
                <div class="text">Магазин</div>
            </a>
        </div>
        <div class="menu-link <?= $progress ?>">
            <div class="icon">
                <i class="fa fa-tasks" style="font-size: 17px;"></i>
            </div>
            <a href="<?= Url::to(['progress/']) ?>">
                <div class="text">Прогрес</div>
            </a>
        </div>
        <div class="menu-link <?= $rating ?> disabled">
            <div class="icon">
                <i class="fa fa-star" style="font-size: 19px;"></i>
            </div>
            <a href="#">
                <div class="text">Рейтинг</div>
            </a>
        </div>
        <div class="menu-link <?= $messages ?> disabled">
            <div class="icon">
                <i class="fa fa-comments" style="font-size: 19px;"></i>
            </div>
            <a href="#">
                <div class="text">Повідомлення</div>
            </a>
        </div>
        <div class="menu-link <?= $news ?>">
            <div class="icon">
                <i class="fa fa-newspaper-o" style="font-size: 16px;"></i>
                <?php if ($totalNews): ?>
                    <div class="news-count news-unread"><?= $totalNews ?></div>
                <?php endif; ?>
            </div>
            <a href="/profile/news/">
                <div class="text">Новини</div>
            </a>
        </div>
        <div class="menu-link">
            <div class="icon">
                <i class="fa fa-sign-out" style="font-size: 21px;"></i>
            </div>
            <a href="<?= Url::to(['profile/logout']) ?>">
                <div class="text">Вихід</div>
            </a>
        </div>
    </div>
</div>
