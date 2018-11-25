<?php

/* @var $this yii\web\View */
/* @var $questions \yii\easyii\modules\page\api\PageObject */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block questions clearfix">
    <div class="cabinet questions">
        <article>
            <div class="content-left-fixed container">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                            <?= $questions->text ?>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>