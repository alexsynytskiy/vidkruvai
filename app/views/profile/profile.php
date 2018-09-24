<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $email string */

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
                    </div>
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
