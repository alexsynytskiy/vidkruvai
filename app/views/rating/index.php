<?php

use app\components\AppMsg;

/* @var $this yii\web\View */
/* @var $data array */
/* @var $saleDataCity array */
/* @var $marksJS array */
/* @var $stateData string */
/* @var \app\models\Category[] $categories */
/* @var $statesRating array */

\app\assets\StoreAsset::register($this);
$asset = \app\assets\AppAsset::register($this);
\app\assets\MapAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;

foreach ($marksJS as $key => $mark) {
    $marksJS[$key]['src'] = str_replace('{assetUrl}', $baseUrl, $mark['src']);
}
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile team-rating">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="block-title">
                    <div class="icon">
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="text">Рейтинг</div>
                </div>

                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div style="height: 1px;"></div>

                        <div class="progress-block pl-24">
                            <div class="progress-top">
                                <div class="progress-title level-title">
                                    <h3 class="levels-table-title">
                                        <?= \app\components\AppMsg::t('Змінюючи своє місто - змінюєш країну'); ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div id="mapsvg"></div>

                        <div style="height: 20px;"></div>

                        <div class="store-main cities-main clearfix"></div>
                        <div class="states-main clearfix">
                            <?= $this->render('rating-table', [
                                'categories' => $categories,
                                'statesRating' => $statesRating,
                            ]) ?>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'marksJS' => \yii\helpers\Json::encode($marksJS),
    'mapUrl' => $baseUrl . '/img/ukraine.svg',
    'stateData' => $stateData,
    'cityProgressUrl' => '/rating/render-city-progress/',
    'stateProgressUrl' => '/rating/render-state-progress/',
]);

$this->registerJs('RatingPage(' . $pageOptions . ')');
?>
