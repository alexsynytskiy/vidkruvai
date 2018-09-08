<?php
use yii\helpers\ArrayHelper;
use \yii\helpers\HtmlPurifier;
use \app\components\AppMsg;
use \app\models\definitions\DefNotificationUser;
use yii\helpers\Url;

/** @var array $notificationSettings */
/** @var \app\models\NotificationUser $model */

/** @var \app\models\Notification $notification */
$notification = $model->notification;

// Не понятно что это вообще такое (не используется)
$notificationBg       = ArrayHelper::getValue($notificationSettings[$notification->type], 'bg', '');

// Current notification icon info
$notificationIcon      = ArrayHelper::getValue($notificationSettings[$notification->type],  'icon', '');
$notificationIconColor      = ArrayHelper::getValue($notificationSettings[$notification->type],  'icon-color', '');
$notificationIconBorderColor      = ArrayHelper::getValue($notificationSettings[$notification->type],  'icon-border-color', '');

// Current category info
//$categoryTitle      = ArrayHelper::getValue($listCategories[$notification->category],  'title', '');
$categoryColor      = ArrayHelper::getValue($listCategories[$notification->category],  'color', '');

// Build right label
$notificationStatuses = DefNotificationUser::getListStatuses();
$rightLabelTitle = ''; //$notificationStatuses[DefNotificationUser::STATUS_READ]
$rightLabelColor = ''; //'default';

// Add css class depends on type
$notificationCssClass = '';
if($model->status == DefNotificationUser::STATUS_NEW) {
    $notificationCssClass .= 'status-new';

    $rightLabelTitle = $notificationStatuses[DefNotificationUser::STATUS_NEW];
    $rightLabelColor = 'success';

} elseif($model->status == DefNotificationUser::STATUS_ARCHIVED) {
    $notificationCssClass = 'status-archived';

    $rightLabelTitle = $notificationStatuses[DefNotificationUser::STATUS_ARCHIVED];
    $rightLabelColor = 'warning';
}

$notificationClasses = $notificationCssClass;
if($rightLabelColor) {
    $notificationClasses .= ' border-left-lg border-left-' . $rightLabelColor;
}

?>
<li class="cursor-pointer list-group-item media <?= $notificationClasses ?>" data-notification-id="<?= $notification->id; ?>">
    <span class="media-link">

        <div class="media-left media-middle">
            <?php if($model->status != DefNotificationUser::STATUS_ARCHIVED): ?>
                <input type="checkbox" class="styled" name="notification-ids[]" data-checked="notification-checkbox" value="<?= $notification->id; ?>"/>
            <?php endif; ?>
        </div>

        <div class="media-left media-middle">
            <span class="btn btn-flat btn-icon btn-rounded btn-sm <?= $notificationIconColor; ?> <?= $notificationIconBorderColor; ?> ">
                <i class="<?= $notificationIcon ?>"></i>
            </span>
        </div>

        <div class="media-body">

            <h6 class="media-heading">
                <?php
                $title = HtmlPurifier::process($notification->title);
                if($model->status === DefNotificationUser::STATUS_NEW ||
                    ($model->status !== DefNotificationUser::STATUS_NEW && $notification->target_link)) {
                    if($model->status === DefNotificationUser::STATUS_NEW) {
                        $link = ($notification->target_link ? Url::to(['/acp/notification/mn', 'id' => $notification->id]) : '#');
                    } else {
                        $link = ($notification->target_link ?: '#');
                    }
                    echo \yii\helpers\Html::a($title, $link, [
                        'class'     => $notification->target_link ? 'target_link' : '',
                        'data-pjax' => (int)$notification->target_link,
                    ]);
                } else {
                    echo $title;
                } ?>

                <?php $createdAt = strtotime($notification->created_at); ?>
                <span class="media-annotation dotted"><?= date('d.m.Y H:i:s', $createdAt); ?></span>
            </h6>

            <?= HtmlPurifier::process($notification->message, function ($config) {
                     /** HTMLPurifier_Config $config */
                     $config->getHTMLDefinition(true)
                            ->addAttribute('a', 'data-pjax', 'Text');
            }); ?>
        </div>

        <?php if($rightLabelTitle && $rightLabelColor): ?>
            <div class="media-right media-middle">
                <span class="label bg-<?= $rightLabelColor; ?>"><?= $rightLabelTitle; ?></span>
            </div>
        <?php endif; ?>

    </span>
</li>



<!--<li class="list-group-item <?= $notificationBg . $notificationCssClass; ?>" data-notification-id="<?= $notification->id; ?>">
    <div class="email-checkbox">
        <label>
            <?php if($model->status !== DefNotificationUser::STATUS_ARCHIVED): ?>
                <i class="fa fa-square-o check-notification"></i>
                <input type="checkbox" name="notification-ids[]" data-checked="notification-checkbox" value="<?= $notification->id; ?>"/>
            <?php endif; ?>
        </label>
    </div>

    <span class="email-user <?= ArrayHelper::getValue($notificationSettings[$notification->type], 'bg-color', ''); ?>">
        <i class="fa <?= $notificationSettings[$notification->type]['icon']; ?> color-white"></i>
    </span>

    <div class="email-info">
        <span class="email-time"><?= $notification->created_at; ?></span>
        <h5 class="email-title">
            <?php
            $title = HtmlPurifier::process($notification->title);
            if($model->status === DefNotificationUser::STATUS_NEW ||
                ($model->status !== DefNotificationUser::STATUS_NEW && $notification->target_link)) {
                if($model->status === DefNotificationUser::STATUS_NEW) {
                    $link = ($notification->target_link ? Url::to(['/acp/notification/mn', 'id' => $notification->id]) : '#');
                } else {
                    $link = ($notification->target_link ?: '#');
                }
                echo \yii\helpers\Html::a($title, $link, [
                    'class'     => ($notification->target_link ? 'target_link' : ''),
                    'data-pjax' => (int)$notification->target_link,
                ]);
            } else {
                echo $title;
            }?>
        </h5>

        <p class="email-desc">
            <?= HtmlPurifier::process($notification->message); ?>
        </p>
    </div>
</li>-->