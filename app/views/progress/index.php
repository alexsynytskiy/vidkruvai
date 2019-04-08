<?php

/* @var $this yii\web\View */
/* @var $data array */

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
