<?php

use \app\components\AppMsg;
use \yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $data array */
/* @var $saleData array */

$asset = \app\assets\AppAsset::register($this);
\app\assets\ChartAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile progress">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="block-title">
                    <div class="icon">
                        <i class="fa fa-tasks"></i>
                    </div>
                    <div class="text">Прогрес</div>
                </div>

                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <canvas id="chart-0"></canvas>
                        <div class="progress-block pl-32">
                            <div class="progress-top">
                                <div class="progress-title level-title">
                                    <h3 class="levels-table-title">
                                        <?= AppMsg::t('Список рівнів профілю'); ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <?php if($saleData): ?>
                            <div class="levels-table-block clearfix">
                                <table class="table col-lg-12 col-md-12">
                                    <thead>
                                    <tr>
                                        <td class="col-lg-2 col-md-2"><?= AppMsg::t('Рівень'); ?></td>
                                        <td class="col-lg-2 col-md-2"><?= AppMsg::t('Група'); ?></td>
                                        <td class="col-lg-2 col-md-2"><?= AppMsg::t('Досвіду потрібно'); ?></td>
                                        <td class="col-lg-3 col-md-3"><?= AppMsg::t('Завершено на (%)'); ?></td>
                                        <td class="col-lg-3 col-md-3"><?= AppMsg::t('Нагорода'); ?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($saleData as $sale):
                                        /* @var $sell \app\models\Sale */ ?>
                                        <tr class="">
                                            <td class="col-lg-1 col-md-1" data-label="Рівень"><?= \yii\helpers\Html::encode(879789) ?></td>
                                            <td class="col-lg-2 col-md-2" data-label="Група">
                                                <?= Html::encode(78979) ?>
                                            </td>
                                            <td class="col-lg-2 col-md-2" data-label="Досвіду потрібно"><?= Html::encode(567) ?></td>

                                            <td class="col-lg-3 col-md-3 clearfix" data-label="Завершено на (%)"><?= 9 ?></td>
                                            <td class="col-lg-3 col-md-3" data-label="Нагорода"><?= 6757 ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'boughtItemsPerCategory' => $data
]);

$this->registerJs('ProgressPage(' . $pageOptions . ')');
?>
