<?php

use \app\components\AppMsg;
use \yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $data array */
/* @var $userCredentials array */
/* @var $userLevelExperience integer */
/* @var $userCurrentLevel integer */
/* @var $preview boolean */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

    <div class="steps-block profile clearfix">
        <div class="cabinet">
            <article>
                <div class="sidebar-right-fixed">
                    <?= $this->render('/_blocks/profile-sidebar') ?>
                </div>
                <div class="content-left-fixed">
                    <div class="project-info-page-description">
                        <div class="profile-user-page">
                            <div class="image"></div>
                            <div class="progress-block pl-32">
                                <div class="progress-top">
                                    <div class="progress-title level-title">
                                        <h3 class="levels-table-title">
                                            <?= AppMsg::t('Список рівнів'); ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <?php if($data): ?>
                                <div class="levels-table-block clearfix">
                                    <table class="table col-lg-12 col-md-12">
                                        <thead>
                                        <tr>
                                            <td class="col-lg-2 col-md-2"><?= AppMsg::t('Рівень'); ?></td>
                                            <td class="col-lg-2 col-md-2"><?= AppMsg::t('Ранг'); ?></td>
                                            <td class="col-lg-2 col-md-2"><?= AppMsg::t('Досвіду потрібно'); ?></td>
                                            <td class="col-lg-3 col-md-3"><?= AppMsg::t('Завершено на (%)'); ?></td>
                                            <td class="col-lg-3 col-md-3"><?= AppMsg::t('Приз'); ?></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($data as $level):
                                            /* @var $level \app\models\Level */ ?>
                                            <tr class="<?= ($level->num === $userCurrentLevel) ? 'current-level' : '' ?>">
                                                <td class="col-lg-1 col-md-1"><?= Html::encode($level->num) ?></td>
                                                <td class="col-lg-2 col-md-2 <?= Html::encode($level->levelgroup->slug) ?>">
                                                    <?= Html::encode($level->levelgroup->name) ?>
                                                </td>
                                                <td class="col-lg-2 col-md-2"><?= Html::encode($level->required_experience) ?></td>

                                                <?php
                                                    if($level->num < $userCurrentLevel) {
                                                        $done = '<i class=\"fa fa-check\" style=\"font-size: 16px;\"></i>';
                                                    } elseif($level->num === $userCurrentLevel) {
                                                        $percent = 100;

                                                        if($level->nextLevel) {
                                                            $percent = ($level->nextLevel->required_experience === 0) ? 0 :
                                                                ($userLevelExperience / ($level->nextLevel->required_experience - $level->required_experience)) * 100;
                                                        }

                                                        $done = Html::encode(round($percent, 2) . '%');
                                                    } else {
                                                        $done = '';
                                                    }
                                                ?>
                                                <td class="col-lg-3 col-md-3"><?= $done ?></td>
                                                <td class="col-lg-3 col-md-3"><?= $level->landingAwardsString ?></td>
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
