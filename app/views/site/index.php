<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>
<section  style="padding-top: 100px;"></section>

<section class="page-block steps-block padding clearfix">
    <?= \app\modules\comment\widgets\CommentWidget::widget([
        'channelName' => 'main-page',
        'template'    => 'vidkruvai',
    ]); ?>
</section>