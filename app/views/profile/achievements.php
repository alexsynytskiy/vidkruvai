<?php
/* @var $this yii\web\View */
/* @var $searchModel \app\models\search\AchievementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $topInfo array */
/* @var array $userCredentials */
/* @var boolean $preview */
/* @var string $status */

use app\components\AppMsg;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\definitions\DefAchievements;

$asset = \app\assets\AppAsset::register($this);
$baseUrl = $asset->baseUrl;

// Build type dropdown
$dropDownFilter = [
    [
        'isActive' => empty($status),
        'isAll' => true,
        'label' => DefAchievements::getStatus(DefAchievements::STATUS_ALL),
        'url' => '#',
        'data-status' => DefAchievements::STATUS_ALL,
        'status' => null,
    ],
    [
        'isActive' => $status === DefAchievements::STATUS_AVAILABLE,
        'label' => DefAchievements::getStatus(DefAchievements::STATUS_AVAILABLE),
        'url' => '#',
        'data-status' => DefAchievements::STATUS_AVAILABLE,
        'status' => DefAchievements::STATUS_AVAILABLE,

    ],
    [
        'isActive' => $status === DefAchievements::STATUS_IN_PROGRESS,
        'label' => DefAchievements::getStatus(DefAchievements::STATUS_IN_PROGRESS),
        'url' => '#',
        'data-status' => DefAchievements::STATUS_IN_PROGRESS,
        'status' => DefAchievements::STATUS_IN_PROGRESS,

    ],
    [
        'isActive' => $status === DefAchievements::STATUS_ACHIEVED,
        'label' => DefAchievements::getStatus(DefAchievements::STATUS_ACHIEVED),
        'url' => '#',
        'data-status' => DefAchievements::STATUS_ACHIEVED,
        'status' => DefAchievements::STATUS_ACHIEVED,
    ],
];

$subTitle = '';
$dropDownFilterLabel = '';
$hasFilterSubtitle = false;
foreach ($dropDownFilter as $key => $item) {
    if ($item['isActive']) {
        if ($key !== 0) {
            $subTitle .= '<span id="subtitle-filter-label"> - ' . \yii\helpers\Html::encode($item['label']) . '</span>';
            $hasFilterSubtitle = true;
        }

        $dropDownFilterLabel = $item['label'];
        break;
    }
}
?>

<div class="steps-block profile clearfix">
    <div class="cabinet achievements">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>

                        <div class="progress-block">
                            <div class="progress-top">
                                <div class="row">
                                    <div class="col-lg-3 col-md-7 col-sm-7">
                                        <div class="progress-title">
                                            <h3>
                                                <?= AppMsg::t('Досягнення'); ?>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-5 col-sm-5 clearfix h-28">
                                        <?php Pjax::begin(['timeout' => 30000, 'id' => 'achievement-filters']); ?>
                                        <?php $form = ActiveForm::begin([
                                            'options' => [
                                                'data-pjax' => true,
                                                'class' => 'select-form',
                                                'id' => 'achievement-filter-form',
                                            ],
                                            'fieldConfig' => [
                                                'template' => '{input}',
                                                'options' => [
                                                    'tag' => false,
                                                ],
                                                'inputOptions' => [
                                                    'class' => '',
                                                ],
                                            ],
                                            'method' => 'get',
                                            'action' => 'achievements',
                                        ]); ?>

                                        <div class="form-group">
                                            <?= $form->field($searchModel, 'filterAchievementType')->hiddenInput() ?>

                                            <ul class="nav navbar-nav">
                                                <li class="dropdown dropdown-velocity" id="notification-filter-status">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icon-eye position-left"></i>
                                                        <?= AppMsg::t('Показати:') ?>
                                                        <span class="label label-success label-inline label-rounded position-right"
                                                              id="filter-btn-title"><?= $dropDownFilterLabel; ?></span>
                                                        <span class="caret"></span>
                                                    </a>

                                                    <ul class="dropdown-menu dropdown-menu-left">
                                                        <?php foreach ($dropDownFilter as $item): ?>
                                                            <li<?= $item['isActive'] ? ' class="active"' : ''; ?>>
                                                            <li class="<?= $item['isActive'] ? 'active' : ''; ?>">
                                                                <a href="<?= $item['url'] ?>"
                                                                   class="<?= array_key_exists('isAll', $item) ? ' all-list' : ''; ?>"
                                                                   id="<?= null !== $item['status'] ? $item['status'] : ''; ?>"
                                                                   data-status="<?= $item['data-status'] ?>">
                                                                    <?= $item['label']; ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach ?>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>

                                        <?php ActiveForm::end(); ?>
                                        <?php Pjax::end(); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-items">
                                <div class="row">
                                    <?php Pjax::begin(['timeout' => 30000, 'id' => 'achievement-list']); ?>
                                    <?= ListView::widget([
                                        'dataProvider' => $dataProvider,
                                        'emptyText' => $this->render('_empty-item', ['msg' => AppMsg::t('Досягнень немає')]),
                                        'summary' => '',
                                        'itemView' => function ($model, $key, $index, $widget) use ($preview, $userCredentials) {
                                            return $this->render('_achievement-item',
                                                [
                                                    'model' => $model,
                                                    'userId' => $preview ? $userCredentials['id'] : null,
                                                ]);
                                        },
                                        'itemOptions' => [
                                            'tag' => false,
                                        ],
                                        'beforeItem' => function ($model) {
                                            /** @var \app\models\Achievement $category */
                                            static $category = '';
                                            $html = null;

                                            if (($category && $category !== $model->group_id) || empty($category)) {
                                                $html = '<div class="achievement-group col-lg-12 col-md-12 col-sm-12 col-xs-12"><p>' .
                                                    \yii\helpers\Html::encode($model->group->name ?: '') . '</p></div>';
                                            }

                                            $category = $model->group_id;

                                            return $html;
                                        },
                                    ]);
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>

<?php
$achievementsPageOptions = \yii\helpers\Json::encode([]);
$this->registerJs('Achievements(' . $achievementsPageOptions . ')');