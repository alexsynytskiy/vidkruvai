<?php
/** @var \yii\web\View $this */

use app\components\AppMsg;
use yii\helpers\Html;

$notificationCounters = $this->context->getUserNotificationCounters();
$lastNotification = $this->context->getUserLastNotifications();
$notificationTitle = AppMsg::t('Сповіщення (<span class="total-notifications">{0}</span>)',
    $notificationCounters['total']);
?>

<li class="last dropdown dropdown-velocity" id="toolbar-notifications">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
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
            <?= Html::a('<i class="icon-menu display-block fa fa-ellipsis-h"></i>',
                ['/profile/notifications'],
                ['title' => AppMsg::t('Всі сповіщення'), 'data-acp-toggle' => 'tooltip']
            ); ?>
        </div>
    </div>
</li>
