<?php
/* @var $showUserInfo bool */

$totalNews = \yii\easyii\modules\news\models\News::getUserNewsCounters();
$totalNews = $totalNews > 0 ? $totalNews : null;

$showUserInfo = isset($showUserInfo) ? $showUserInfo : true;
?>

<div class="cabinet-menu nopadding">
    <div class="wrapper">
        <?php if ($showUserInfo): ?>
            <a href="<?= \yii\helpers\Url::to('/profile') ?>">
                <div class="profile-info">
                    <img src="<?= \Yii::$app->siteUser->identity->avatar ?>" class="avatar">
                    <div class="name"><?= \Yii::$app->siteUser->identity->name . ' ' . \Yii::$app->siteUser->identity->surname ?></div>
                    <div class="school"><?= \Yii::$app->siteUser->identity->school ?></div>
                    <div class="rating">Рейтинг: <?= \Yii::$app->siteUser->identity->total_experience ?></div>
                </div>
            </a>
        <?php endif; ?>

        <div class="menu-link active">
            <div class="icon"></div>
            <div class="text">Завдання</div>
        </div>
        <div class="menu-link">
            <div class="icon">
                <?php if ($totalNews): ?>
                    <div class="new-count news-unread"><?= $totalNews ?></div><?php endif; ?>
            </div>
            <a href="<?= \yii\helpers\Url::to(['profile/news']) ?>">
                <div class="text">Новини</div>
            </a>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <div class="text">Повідомлення</div>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <div class="text">Команда</div>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <a href="<?= \yii\helpers\Url::to(['profile/update-profile']) ?>">
                <div class="text">Профіль</div>
            </a>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <a href="<?= \yii\helpers\Url::to(['profile/logout']) ?>">
                <div class="text">Вихід</div>
            </a>
        </div>
    </div>
</div>
