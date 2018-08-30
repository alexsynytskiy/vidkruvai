<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $email string */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="separator-space" style="height: 30px;"></div>

    <div class="cabinet">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-header') ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">

                </div>

            </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
]);

$this->registerJs('ProfilePage(' . $pageOptions . ')');
?>

