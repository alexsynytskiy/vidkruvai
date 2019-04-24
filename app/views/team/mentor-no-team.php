<?php

use app\components\AppMsg;
use \app\models\definitions\DefTeam;
use \app\components\helpers\StockHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $showTeamInfo bool */
/* @var $achievements array */
/* @var $levelInfo array */
/* @var $previousLevels array */
/* @var $nextLevels array */
/* @var $preview boolean */
/* @var array $entityCredentials */

$asset = \app\assets\AppAsset::register($this);
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile team">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <div class="profile-info-main clearfix">
                            <div class="profile-personal-preview clearfix">
                                <div class="right-side">
                                    <div class="black-panel mb-50 pb-20 pt-20-i personal-team">
                                        <div class="progress-block pt-0 mt-0">
                                            <div class="progress-top mb-0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="progress-title mb-10 pl-24">
                                                            <h3>
                                                                <?= AppMsg::t('Команда'); ?>
                                                            </h3>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="team-items mb-0 pl-24">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        Вас ще не запросили в команду! Чекайте на запрошення приєднатися від капітана
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>