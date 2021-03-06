<?php
/** @var \yii\web\View $this */

use app\components\AppMsg;
use yii\helpers\Html;

$notificationCounters = $this->context->getUserNotificationCounters();
$lastNotification = $this->context->getUserLastNotifications();
$notificationTitle = AppMsg::t('Сповіщення (<span class="total-notifications">{0}</span>)',
    $notificationCounters['total']);
?>

<li class="dropdown dropdown-velocity last clearfix" id="toolbar-notifications">
    <a id="mobile-name" href="<?= \yii\helpers\Url::to(['/profile']) ?>">
        <?= \Yii::$app->siteUser->identity->getFullName() ?>
    </a>
    <a href="<?= \yii\helpers\Url::to('/profile/notifications') ?>">
        <i class="fa fa-bell heading-icon"></i>
        <span class="visible-xs-inline-block position-right"></span>
        <span class="badge bg-warning-400 total-notifications"><?= $notificationCounters['total'] ?: ''; ?></span>
    </a>
    <div class="dropdown-menu dropdown-content">
        <div class="dropdown-content-heading">
            <?= AppMsg::t('Сповіщення'); ?>
        </div>
        <ul class="media-list media-list-linked width-350" id="toolbar-list-notifications">
            <?= $this->render('_top-menu-notification-items'); ?>
        </ul>
        <div class="dropdown-content-footer">
            <?= Html::a('Всі сповіщення',
                ['/profile/notifications'],
                ['title' => AppMsg::t('Всі сповіщення'), 'data-acp-toggle' => 'tooltip']
            ); ?>
        </div>
    </div>
</li>
