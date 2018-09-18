<?php
/* @var \yii\web\View $this */
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var string $category */
/* @var string $status */
/* @var array $userCategories */

/* @var array $listCategories */

use app\components\AppMsg;
use app\models\definitions\DefNotificationUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$asset = \app\assets\AppAsset::register($this);

$notificationSettings = \app\components\notification\NotificationSettings::getSettings();
$this->title = AppMsg::t('Сповіщення');
$subTitle = '';

// pager settings update for this page
$pagerSettings = [
    'firstPageLabel' => '&laquo;',
    'lastPageLabel' => '&raquo;',

    'prevPageLabel' => '&lsaquo;',
    'nextPageLabel' => '&rsaquo;',

    'options' => [
        'class' => 'pagination pagination-flat pagination-xs',
    ],
];

// counters
$notificationCounters = $this->context->getUserNotificationCounters();
$totalNotifications = ArrayHelper::getValue($notificationCounters, 'total', '');
$totalNotifications = $totalNotifications > 0 ? $totalNotifications : '';

$dataStatus = !empty($status) ? $status : '';

// Build categories dropdown
$categoriesMenu = [
    [
        'isActive' => empty($category) || $category === 'all',
        'link' => Html::a('<span class="badge badge-default pull-right total-notifications">' .
            $totalNotifications . '</span>' . AppMsg::t('Все'),
            ['notification/index/all'],
            [
                'data-status' => $dataStatus,
            ]
        ),
    ],
];

$userCategoriesLabel = AppMsg::t('Все');
$userCategoriesLabelColor = 'success';
foreach ($userCategories as $cat) {
    $isActive = $category === $cat;

    if ($isActive) {
        $subTitle .= ' - ' . Html::encode($listCategories[$cat]['title']);
        $userCategoriesLabel = $listCategories[$cat]['title'];
    }

    $totalNotificationInCat = ArrayHelper::getValue($notificationCounters, $cat, '');
    $totalNotificationInCat = $totalNotificationInCat > 0 ? $totalNotificationInCat : '';

    $categoriesMenu[] = [
        'isActive' => $isActive,
        'link' => Html::a('<span class="badge badge-default pull-right total-' . $cat . '-notifications">' .
            $totalNotificationInCat . '</span>' . $listCategories[$cat]['title'],
            ['notification/index/' . $cat],
            [
                'data-status' => $dataStatus,
            ]
        ),
    ];
}

// Build type dropdown
$dropDownFilter = [
    [
        'isActive' => empty($status),
        'isAll' => true,
        'label' => AppMsg::t('Все'),
        'url' => Url::to(['/profile/notifications', 'category' => $category, 'status' => null]),
        'status' => null,
    ],
    [
        'isActive' => $status === DefNotificationUser::STATUS_NEW,
        'label' => AppMsg::t('Непрочитанные'),
        'url' => Url::to(['/profile/notifications', 'category' => $category, 'status' => DefNotificationUser::STATUS_NEW]),
        'status' => DefNotificationUser::STATUS_NEW,

    ],
    [
        'isActive' => $status === DefNotificationUser::STATUS_READ,
        'label' => AppMsg::t('Прочитанные'),
        'url' => Url::to(['/profile/notifications', 'category' => $category, 'status' => DefNotificationUser::STATUS_READ]),
        'status' => DefNotificationUser::STATUS_READ,

    ],
    [
        'isActive' => $status === DefNotificationUser::STATUS_ARCHIVED,
        'label' => AppMsg::t('Архивные'),
        'url' => Url::to(['/profile/notifications', 'category' => $category, 'status' => DefNotificationUser::STATUS_ARCHIVED]),
        'status' => DefNotificationUser::STATUS_ARCHIVED,
    ],
];

$dropDownFilterLabel = '';
$hasFilterSubtitle = false;
foreach ($dropDownFilter as $key => $item) {
    if ($item['isActive']) {
        if ($key !== 0) {
            $subTitle .= '<span id="subtitle-filter-label"> - ' . Html::encode($item['label']) . '</span>';
            $hasFilterSubtitle = true;
        }

        $dropDownFilterLabel = $item['label'];
        break;
    }
}

if (!$hasFilterSubtitle) {
    $subTitle .= '<span id="subtitle-filter-label"></span>';
}

// Action button dropdown
$dropDownActions = [
    [
        'link' => Html::a('<i class="icon-checkmark4"></i> ' . AppMsg::t('Прочитать все'), null,
            ['id' => 'mark-all-as-read', 'class' => 'no-spinner']),
    ],
];

//page counting
$pageCounterTitle = AppMsg::t('Список уведомлений');
if (($page = ArrayHelper::getValue($_GET, 'page', 0)) > 1) {
    $pageCounterTitle .= '<small class="text-light">' . AppMsg::t('страница: {page}',
            ['page' => ArrayHelper::getValue($_GET, 'page')]) . '</small>';
}

?>

    <div class="steps-block profile clearfix">
        <div class="cabinet">
            <article>
                <div class="sidebar-right-fixed">
                    <?= $this->render('/_blocks/profile-header') ?>
                </div>
                <div class="content-left-fixed">
                    <div class="notification-index notification-page">
                        <!-- Content area -->
                        <div class="content">

                            <!-- Navbar -->
                            <div class="navbar navbar-default navbar-component navbar-xs navbar-second">
                                <ul class="nav navbar-nav no-border visible-xs-block">
                                    <li>
                                        <a class="text-center collapsed" data-toggle="collapse"
                                           data-target="#navbar-second">
                                            <i class="icon-circle-down2"></i></a>
                                    </li>
                                </ul>

                                <div class="navbar-collapse collapse" id="navbar-second">
                                    <ul class="nav navbar-nav">

                                        <li class="dropdown dropdown-velocity" id="notification-filter-status">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-eye position-left"></i>
                                                <?= AppMsg::t('Показать:') ?>
                                                <span class="label label-success label-inline label-rounded position-right"
                                                      id="filter-btn-title"><?= $dropDownFilterLabel; ?></span>
                                                <span class="caret"></span>
                                            </a>

                                            <ul class="dropdown-menu dropdown-menu-left">
                                                <?php foreach ($dropDownFilter as $item): ?>
                                                    <li<?= $item['isActive'] ? ' class="active"' : ''; ?>>
                                                    <li class="<?= ($item['isActive'] ? 'active' : ''); ?>">
                                                        <a href="<?= $item['url'] ?>"
                                                           class="<?= (isset($item['isAll']) ? ' all-list' : ''); ?>"
                                                           id="<?= (isset($item['status']) ? $item['status'] : ''); ?>">
                                                            <?= $item['label']; ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach ?>
                                            </ul>
                                        </li>

                                        <li class="dropdown dropdown-velocity" id="notification-filter-type">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-list2 position-left"></i> <?= AppMsg::t('Категория:'); ?>
                                                <span class="label bg-<?= $userCategoriesLabelColor; ?> label-inline label-rounded position-right"><?= $userCategoriesLabel; ?></span>
                                                <span class="caret"></span>
                                            </a>

                                            <ul class="dropdown-menu dropdown-menu-left">
                                                <?php foreach ($categoriesMenu as $item): ?>
                                                    <li<?= $item['isActive'] ? ' class="active"' : ''; ?>>
                                                        <?= $item['link']; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>

                                    </ul>

                                    <div class="navbar-right" id="notification-action-buttons">
                                        <ul class="nav navbar-nav">
                                            <li>
                                                <?= \yii\helpers\Html::a(\app\components\AppMsg::t('Відмітити як прочитані'),
                                                    '#',
                                                    ['data-notification-action' => 'read', 'class' => 'hide']) ?>
                                            </li>
                                            <li>
                                                <a href="#" data-notification-action="new" class="hide"
                                                   data-acp-toggle="tooltip"
                                                   title="<?= AppMsg::t('Отметить как непрочитанные'); ?>">
                                                    <i class="icon-history"></i>
                                                    <span class="visible-xs-inline-block position-right">
                                                        <?= AppMsg::t('Отметить как непрочитанные'); ?>
                                                    </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" data-notification-action="archived" class="hide"
                                                   data-acp-toggle="tooltip" title="<?= AppMsg::t('Архивировать'); ?>">
                                                    <i class="icon-bin"></i>
                                                    <span class="visible-xs-inline-block position-right">
                                                        <?= AppMsg::t('Архивировать'); ?>
                                                    </span>
                                                </a>
                                            </li>
                                            <li>
                                                <div class="helpers-header clearfix">
                                                    <?= \yii\helpers\Html::a('<i class="fa fa-check"></i>' . \app\components\AppMsg::t('Прочитати все'),
                                                        null,
                                                        ['id' => 'mark-all-news-as-read', 'class' => 'no-spinner']) ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /navbar -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-flat">
                                        <div id="full-notification-block">
                                            <?php Pjax::begin(['id' => 'full-notification-block']); ?>
                                            <div id="list-notifications">
                                                <?= \yii\widgets\ListView::widget([
                                                    'dataProvider' => $dataProvider,
                                                    'itemView' => '_notification-item',
                                                    'layout' => '{items}',
                                                    'emptyText' => '<span class="media-link">' . AppMsg::t('Уведомления отсутствуют.') . '</span>',
                                                    'emptyTextOptions' => [
                                                        'tag' => 'li',
                                                        'class' => 'media empty-text text-center',
                                                    ],
                                                    'viewParams' => [
                                                        'notificationSettings' => $notificationSettings,
                                                        'listCategories' => $listCategories,
                                                    ],
                                                    'options' => [
                                                        'tag' => 'ul',
                                                        'class' => 'media-list media-list-linked media-list-bordered',
                                                    ],
                                                    'itemOptions' => [
                                                        'tag' => false,
                                                    ],
                                                ]); ?>

                                            </div>

                                            <div class="panel-footer">
                                                <div class="heading-elements">

                                                    <?php if (($notificationCount = $dataProvider->getTotalCount())) : ?>
                                                        <span class="heading-text text-semibold">
                                                        <?= AppMsg::t('{notificationCount, plural, one{# уведомление} few{# уведомления} many{# уведомлений} other{# уведомлений} }',
                                                            ['notificationCount' => $notificationCount]); ?></span>
                                                    <?php endif; ?>

                                                    <div class="pull-right">
                                                        <?= \yii\widgets\LinkPager::widget([
                                                                'pagination' => $dataProvider->getPagination(),
                                                            ] + $pagerSettings) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php Pjax::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /content area -->

                    </div>
                </div>
            </article>
        </div>
    </div>

<?php
$this->registerJs('Notifications.NotificationPage().init()');
?>