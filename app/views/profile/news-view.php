<?php

/* @var $this yii\web\View */
/* @var $newsItem yii\easyii\modules\news\models\News */
/* @var $tags string */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

    <div class="steps-block profile clearfix">
        <div class="cabinet news-item">
            <article>
                <div class="sidebar-right-fixed">
                    <?= $this->render('/_blocks/profile-sidebar') ?>
                </div>
                <div class="content-left-fixed">
                    <div class="simple-news-page-description">
                        <div class="simple-news-page">
                            <div class="image" style="background: url(<?= $newsItem->image ?>); background-size: cover;"></div>

                            <div class="helpers-header clearfix">
                                <div class="heading-elements" data-news-id="<?= $newsItem->id; ?>">
                                    <?php if ($newsItem->read): ?>
                                        <a id="read<?= $newsItem->id ?>"
                                           href="<?= \yii\helpers\Url::to(['/news/read', 'id' => $newsItem->id]) ?>"
                                           class="label label-success heading-text read-news" data-ajax="1">
                                            <i class="fa fa-check"></i>Ознайомився
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="pl-32">
                                <h3><?= $newsItem->title ?></h3>
                            </div>

                            <div class="item-date pl-32">
                                <?= date('d.m.Y', $newsItem->time); ?>
                            </div>

                            <div class="text pl-32">
                                <?= $newsItem->short ?>

                                <?= $newsItem->text ?>
                            </div>

                            <?php if(count($newsItem->tags)): ?>
                                <h4 class="pl-32">Теги</h4>
                                <ul class="tags pl-32 clearfix">
                                    <?php foreach ($newsItem->tags as $tag): ?>
                                        <li>
                                            <a href="<?= \yii\helpers\Url::to(['/profile/news/' . $tag->name]) ?>">
                                                <?= $tag->name ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
