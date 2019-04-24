<?php

use app\components\AppMsg;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $data array */
/* @var $saleDataSchool array */

$asset = \app\assets\AppAsset::register($this);
\app\assets\ChartAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile team-progress">
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
                        <div class="progress-block pl-24">
                            <div class="progress-top">
                                <div class="progress-title level-title">
                                    <h3 class="levels-table-title">
                                        <?= AppMsg::t('Історія розвитку школи'); ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <?php if ($saleDataSchool): ?>
                            <div class="levels-table-block clearfix">
                                <table class="table table-striped col-lg-12 col-md-12">
                                    <thead>
                                    <tr>
                                        <td class="col-lg-2 col-md-2"><?= AppMsg::t('Елемент'); ?></td>
                                        <td class="col-lg-2 col-md-2"><?= AppMsg::t('Категорія'); ?></td>
                                        <td class="col-lg-2 col-md-2"><?= AppMsg::t('Капітан'); ?></td>
                                        <td class="col-lg-2 col-md-1"><?= AppMsg::t('Вартість'); ?></td>
                                        <td class="col-lg-3 col-md-3"><?= AppMsg::t('Баланс до'); ?></td>
                                        <td class="col-lg-3 col-md-3"><?= AppMsg::t('Баланс після'); ?></td>
                                        <td class="col-lg-3 col-md-3"><?= AppMsg::t('Дата'); ?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($saleDataSchool as $sale):
                                        /* @var $sale \app\models\Sale */ ?>
                                        <tr class="">
                                            <td class="col-lg-3 col-md-2"
                                                data-label="Елемент"><?= \yii\helpers\Html::encode($sale->storeItem->name) ?></td>
                                            <td class="col-lg-3 col-md-2" data-label="Категорія">
                                                <?= Html::encode($sale->storeItem->category->parents()->one()->name) ?>
                                            </td>
                                            <td class="col-lg-3 col-md-2"
                                                data-label="Капітан"><?= Html::encode($sale->captain->getFullName()) ?></td>
                                            <td class="col-lg-1 col-md-1"
                                                data-label="Вартість"><?= Html::encode($sale->storeItem->cost) ?></td>
                                            <td class="col-lg-1 col-md-3"
                                                data-label="Баланс до"><?= Html::encode($sale->team_balance + $sale->storeItem->teamAdoptedCost($user->team->id)) ?></td>
                                            <td class="col-lg-1 col-md-3"
                                                data-label="Баланс після"><?= Html::encode($sale->team_balance) ?></td>
                                            <td class="col-lg-1 col-md-2"
                                                data-label="Дата"><?= Html::encode($sale->created_at) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                        <div style="height: 20px;"></div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'boughtItemsPerCategory' => $data,
]);

$this->registerJs('ProgressPage(' . $pageOptions . ')');
?>
