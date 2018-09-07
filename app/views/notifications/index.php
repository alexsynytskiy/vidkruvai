<?php
/* @var \yii\web\View $this */
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var string $category */
/* @var string $status */
/* @var array $userCategories */
/* @var array $listCategories */

use \acp\components\AcpMsg;
use acp\components\assets\AcpAsset;
use app\assets\SourcesAsset;
use \yii\widgets\Pjax;
use \yii\helpers\Html;
use \acp\models\definitions\DefNotificationUser;
use \yii\helpers\Url;
use \yii\helpers\ArrayHelper;

$notificationSettings = \acp\components\notification\NotificationSettings::getSettings();
$this->title          = AcpMsg::t('Уведомления');
$subTitle             = '';

// pager settings update for this page
$pagerSettings = Yii::$app->getModule('acp')->params['linkPagerSettings'];
$pagerSettings['options']['class'] .= ' pagination-flat pagination-xs';

// counters
$notificationCounters = $this->context->getUserNotificationCounters();
$totalNotifications   = ArrayHelper::getValue($notificationCounters, 'total', '');
$totalNotifications   = $totalNotifications > 0 ? $totalNotifications : '';

$dataStatus = (!empty($status)) ? $status : '';

// Build categories dropdown
$categoriesMenu = [
    [
        'isActive' => empty($category) || $category == 'all',
        'link'     => Html::a('<span class="badge badge-default pull-right total-notifications">' . $totalNotifications . '</span>' . AcpMsg::t('Все'),
            ['notification/index/all'],
            [
                'data-status' => $dataStatus,
            ]
        ),
    ],
];

$userCategoriesLabel      = AcpMsg::t('Все');
$userCategoriesLabelColor = 'success';
foreach($userCategories as $cat) {
    $isActive = $category == $cat;

    if($isActive) {
        $subTitle .= ' - ' . Html::encode($listCategories[$cat]['title']);
        $userCategoriesLabel = $listCategories[$cat]['title'];
    }

    $totalNotificationInCat = ArrayHelper::getValue($notificationCounters, $cat, '');
    $totalNotificationInCat = $totalNotificationInCat > 0 ? $totalNotificationInCat : '';

    $categoriesMenu[] = [
        'isActive' => $isActive,
        'link'     => Html::a('<span class="badge badge-default pull-right total-' . $cat . '-notifications">' . $totalNotificationInCat . '</span>' . $listCategories[$cat]['title'],
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
        'isAll'    => true,
        'label'    => AcpMsg::t('Все'),
        'url'      => Url::to(['notification/index', 'category' => $category, 'status' => null]),
        'status'   => null,
    ],
    [
        'isActive' => $status == DefNotificationUser::STATUS_NEW,
        'label'    => AcpMsg::t('Непрочитанные'),
        'url'      => Url::to(['notification/index', 'category' => $category, 'status' => DefNotificationUser::STATUS_NEW]),
        'status'   => DefNotificationUser::STATUS_NEW,

    ],
    [
        'isActive' => $status == DefNotificationUser::STATUS_READ,
        'label'    => AcpMsg::t('Прочитанные'),
        'url'      => Url::to(['notification/index', 'category' => $category, 'status' => DefNotificationUser::STATUS_READ]),
        'status'   => DefNotificationUser::STATUS_READ,

    ],
    [
        'isActive' => $status == DefNotificationUser::STATUS_ARCHIVED,
        'label'    => AcpMsg::t('Архивные'),
        'url'      => Url::to(['notification/index', 'category' => $category, 'status' => DefNotificationUser::STATUS_ARCHIVED]),
        'status'   => DefNotificationUser::STATUS_ARCHIVED,
    ],
];

$dropDownFilterLabel = '';
$hasFilterSubtitle   = false;
foreach($dropDownFilter as $key => $item) {
    if($item['isActive']) {
        if($key != 0) {
            $subTitle .= '<span id="subtitle-filter-label"> - ' . Html::encode($item['label']) . '</span>';
            $hasFilterSubtitle = true;
        }

        $dropDownFilterLabel = $item['label'];
        break;
    }
}

if(!$hasFilterSubtitle) {
    $subTitle .= '<span id="subtitle-filter-label"></span>';
}

// Action button dropdown
$dropDownActions = [
    [
        'link' => Html::a('<i class="icon-checkmark4"></i> ' . AcpMsg::t('Прочитать все'), null, ['id' => 'mark-all-as-read', 'class' => 'no-spinner']),
    ],
];

//page counting
$pageCounterTitle = AcpMsg::t('Список уведомлений');
if(($page = ArrayHelper::getValue($_GET, 'page', 0)) > 1) {
    $pageCounterTitle .= '<small class="text-light">' . AcpMsg::t('страница: {page}', ['page' => ArrayHelper::getValue($_GET, 'page')]) . '</small>';
}

?>

    <div class="notification-index notification-page">
        <div class="page-header page-header-default">
            <div class="page-header-content">
                <div class="page-title">
                    <h4>
                        <i class="icon-bell2 position-left"></i>
                        <span class="text-semibold"><?= Html::encode($this->title) ?></span>
                        <?= $subTitle ?>
                    </h4>
                </div>

                <?= $this->render('/_blocks/page-header-actions', ['dropDownActions' => $dropDownActions]); ?>
            </div>
        </div>


        <!-- Content area -->
        <div class="content">

            <!-- Navbar -->
            <div class="navbar navbar-default navbar-component navbar-xs navbar-second">
                <ul class="nav navbar-nav no-border visible-xs-block">
                    <li>
                        <a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second"><i class="icon-circle-down2"></i></a>
                    </li>
                </ul>

                <div class="navbar-collapse collapse" id="navbar-second">
                    <ul class="nav navbar-nav">

                        <li class="dropdown dropdown-velocity" id="notification-filter-status">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-eye position-left"></i>
                                <?= AcpMsg::t('Показать:'); ?>
                                <span class="label label-success label-inline label-rounded position-right" id="filter-btn-title"><?= $dropDownFilterLabel; ?></span>
                                <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-left">
                                <?php foreach($dropDownFilter as $item): ?>
                                    <li<?= $item['isActive'] ? ' class="active"' : ''; ?>>
                                    <li class="<?= ($item['isActive'] ? 'active' : ''); ?>">
                                        <a href="<?= $item['url'] ?>" class="<?= (isset($item['isAll']) ? ' all-list' : ''); ?>" id="<?= (isset($item['status']) ? $item['status'] : ''); ?>">
                                            <?= $item['label']; ?>
                                        </a>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </li>

                        <li class="dropdown dropdown-velocity" id="notification-filter-type">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-list2 position-left"></i> <?= AcpMsg::t('Категория:'); ?>
                                <span class="label bg-<?= $userCategoriesLabelColor; ?> label-inline label-rounded position-right"><?= $userCategoriesLabel; ?></span>
                                <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-left">
                                <?php foreach($categoriesMenu as $item): ?>
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
                                <a href="#" data-notification-action="read" class="hide">
                                    <i class="icon-checkmark3 position-left" data-default-fa-class="icon-checkmark3"></i>
                                    <?= AcpMsg::t('Отметить как прочитанные'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="#" data-notification-action="new" class="hide" data-acp-toggle="tooltip" title="<?= AcpMsg::t('Отметить как непрочитанные'); ?>">
                                    <i class="icon-history"></i>
                                    <span class="visible-xs-inline-block position-right">
                                        <?= AcpMsg::t('Отметить как непрочитанные'); ?>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#" data-notification-action="archived" class="hide" data-acp-toggle="tooltip" title="<?= AcpMsg::t('Архивировать'); ?>">
                                    <i class="icon-bin"></i>
                                    <span class="visible-xs-inline-block position-right">
                                        <?= AcpMsg::t('Архивировать'); ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /navbar -->

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h6 class="panel-title"><?= $pageCounterTitle ?></h6>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                    <li><a data-action="reload"></a></li>
                                </ul>
                                <?php //= $this->render('/_blocks/panel-heading-buttons'); ?>
                            </div>
                        </div>

                        <div id="full-notification-block">
                            <?php Pjax::begin(['id' => 'full-notification-block']); ?>
                            <div id="list-notifications">
                                <?= \yii\widgets\ListView::widget([
                                    'dataProvider'     => $dataProvider,
                                    'itemView'         => '_notification-item',
                                    'layout'           => '{items}',
                                    'emptyText'        => '<span class="media-link">' . AcpMsg::t('Уведомления отсутствуют.') . '</span>',
                                    'emptyTextOptions' => [
                                        'tag'   => 'li',
                                        'class' => 'media empty-text text-center',
                                    ],
                                    'viewParams'       => [
                                        'notificationSettings' => $notificationSettings,
                                        'listCategories'       => $listCategories,
                                    ],
                                    'options'          => [
                                        'tag'   => 'ul',
                                        'class' => 'media-list media-list-linked media-list-bordered',
                                    ],
                                    'itemOptions'      => [
                                        'tag' => false,
                                    ],
                                ]); ?>

                            </div>

                            <div class="panel-footer">
                                <div class="heading-elements">

                                    <?php if(($notificationCount = $dataProvider->getTotalCount())) : ?>
                                        <span class="heading-text text-semibold"><?= AcpMsg::t('{notificationCount, plural, one{# уведомление} few{# уведомления} many{# уведомлений} other{# уведомлений} }', ['notificationCount' => $notificationCount]); ?></span>
                                    <?php endif; ?>

                                    <div class="pull-right">
                                        <?php echo \yii\widgets\LinkPager::widget([
                                                'pagination' => $dataProvider->getPagination(),
                                            ] + $pagerSettings); ?>
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

<?php
AcpAsset::getTheme($this);

$sources = SourcesAsset::getSources($this);

$sources->registerSweetAlert2();

$this->registerJs('Notifications.NotificationPage().init()');
?>