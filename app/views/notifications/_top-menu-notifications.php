<?php
/** @var \yii\web\View $this */

use acp\components\AcpMsg;
use yii\helpers\Html;

$notificationCounters = $this->context->getUserNotificationCounters();
$lastNotification     = $this->context->getUserLastNotifications();
$notificationTitle    = AcpMsg::t('Уведомления (<span class="total-notifications">{0}</span>)', $notificationCounters['total']);


?>

<li class="dropdown dropdown-velocity" id="toolbar-notifications">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon-bell2"></i>
        <span class="visible-xs-inline-block position-right"><?= AcpMsg::t('Уведомления'); ?></span>
        <span class="badge bg-warning-400 total-notifications"><?= $notificationCounters['total'] ?: ''; ?></span>
    </a>

    <div class="dropdown-menu dropdown-content">
        <div class="dropdown-content-heading">
            <?= AcpMsg::t('Уведомления'); ?>
        </div>
        <ul class="media-list media-list-linked width-350" id="toolbar-list-notifications">
            <?= $this->render('_top-menu-notification-items'); ?>
        </ul>

        <div class="dropdown-content-footer">
            <?= Html::a('<i class="icon-menu display-block"></i>',
                ['/acp/notification'],
                ['title' => AcpMsg::t('Все уведомления'), 'data-acp-toggle' => 'tooltip']
            ); ?>
        </div>
    </div>
</li>