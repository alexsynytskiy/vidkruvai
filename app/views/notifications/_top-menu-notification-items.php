<?php
/** @var \yii\web\View $this */

use app\components\AppMsg;
use app\components\notification\NotificationSettings;
use yii\helpers\Url;

$lastNotification = $this->context->getUserLastNotifications();
$notificationCounters = $this->context->getUserNotificationCounters();
?>

<?php if ($notificationCounters['total']): ?>
    <?php foreach ($lastNotification as $item): ?>
        <li class="media notification-item<?= ($item['target_link'] ? ' target_link' : ''); ?>"
            data-notification-id="<?= $item['id']; ?>">
            <a class="media-link"
               href="<?= ($item['target_link'] ? Url::to(['/notification/mn', 'id' => $item['id']]) : '#'); ?>">
                <div class="media-left">
                    <span class="btn <?= NotificationSettings::getParam($item['type'] . '.icon-border-color') .
                    ' ' . NotificationSettings::getParam($item['type'] . '.icon-color'); ?> btn-flat btn-rounded btn-icon btn-sm">
                        <i class="<?= NotificationSettings::getParam($item['type'] . '.icon'); ?>"></i></span>
                </div>

                <div class="media-body">
                    <?= AppMsg::t(NotificationSettings::getParam($item['type'] . '.short-title')); ?>

                    <?php $createdAt = strtotime($item['created_at']); ?>
                    <div class="media-annotation"><?= date('d.m.Y H:i:s', $createdAt); ?></div>
                </div>
            </a>
        </li>

    <?php endforeach; ?>
<?php else: ?>
    <li class="media">
        <span class="media-link">
            <div class="media-body">
                <?= AppMsg::t('Нових сповіщень немає.'); ?>
            </div>
        </span>
    </li>
<?php endif; ?>
