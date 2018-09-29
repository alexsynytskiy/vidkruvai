<?php
/** @var $this yii\web\View */
/** @var $searchModel \app\models\search\AchievementSearch */
/** @var array $userCredentials */
/** @var boolean $preview */
/** @var array $groups */
/* @var string $status */

use app\components\AppMsg;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\definitions\DefAchievements;

$asset = \app\assets\AppAsset::register($this);

$dropDownFilter = [
    [
        'isActive' => empty($status) || $status === DefAchievements::STATUS_ALL,
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
foreach ($dropDownFilter as $key => $item) {
    if ($item['isActive']) {
        $subTitle .= '<span id="subtitle-filter-label"> - ' . \yii\helpers\Html::encode($item['label']) . '</span>';

        $dropDownFilterLabel = $item['label'];
        break;
    }
}
?>

<?= Html::beginForm('', 'get', ['id' => 'selected-achievements-filters']) ?>
<?= Html::endForm() ?>

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
                                                'template' => "{input}",
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
                                    <?php foreach ($groups as $group => $achievements) {
                                        $data = explode('__', $group);
                                        echo '<div class="achievement-group col-lg-12 col-md-12 col-sm-12 col-xs-12"><p>' .
                                            \yii\helpers\Html::encode($data[1]) . '</p></div>';

                                        echo '<div id="' . $data[0] . '-preview">';
                                        foreach ($achievements['preview'] as $achievement) {
                                            echo $this->render(
                                                '_achievement-item',
                                                [
                                                    'model' => $achievement,
                                                    'userId' => $preview ? $userCredentials['id'] : null,
                                                ]
                                            );
                                        }
                                        echo '</div>';

                                        if (count($achievements['full']) > 0) {
                                            echo '<div id="' . $data[0] . '-full" class="hidden">';
                                            foreach ($achievements['full'] as $achievement) {
                                                echo $this->render(
                                                    '_achievement-item',
                                                    [
                                                        'model' => $achievement,
                                                        'userId' => $preview ? $userCredentials['id'] : null,
                                                    ]
                                                );
                                            }
                                            echo '</div><div class="col-lg-12"><div class="progress-block pt-0 mt-0">';

                                            echo \yii\helpers\Html::a(AppMsg::t('Показать все'),
                                                ["#"],
                                                [
                                                    'class' => 'button mt-0 show-full-group',
                                                    'id' => $data[0],
                                                ]);

                                            echo '</div></div>';
                                        }
                                    } ?>
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