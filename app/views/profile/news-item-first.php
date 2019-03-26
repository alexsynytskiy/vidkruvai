<?php
/* @var $this yii\web\View */
/* @var $item yii\easyii\modules\news\api\NewsObject */

?>

<div class="news-item first clearfix">
    <div class="image lazy" data-bg="url(<?= $item->image ?>)" style="background-size: cover; background-position: left;"></div>
    <div class="information">
        <div class="comments-count-news"><?= $item->commentsCount ?></div>
        <div class="title">
            <a href="<?= \yii\helpers\Url::to(['/profile/news-item/' . $item->slug]) ?>"><?= $item->title ?></a>
        </div>
        <div class="short">
            <?= \yii\helpers\StringHelper::truncate($item->short, 110, '..') ?>
        </div>
        <div class="short item-date">
            <?= date('d.m.Y', $item->time); ?>
        </div>
        <a class="short read-more" href="<?= \yii\helpers\Url::to(['/profile/news-item/' . $item->slug]) ?>">Читати далі</a>

        <div class="heading-elements" data-news-id="<?= $item->id; ?>">
            <?php if ($item->read): ?>
                <a id="read<?= $item->id ?>"
                   href="<?= \yii\helpers\Url::to(['/news/read', 'id' => $item->id]) ?>"
                   class="label label-success heading-text read-news" data-ajax="1">
                    <i class="fa fa-check"></i>Ознайомився
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
