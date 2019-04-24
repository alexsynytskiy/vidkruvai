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

                            <div class="pl-24">
                                <h3><?= $newsItem->title ?></h3>
                            </div>

                            <div class="item-date pl-24">
                                <?= date('d.m.Y', $newsItem->time); ?>
                            </div>

                            <div class="text pl-24">
                                <?= $newsItem->short ?>

                                <?= $newsItem->text ?>
                            </div>

                            <?php if(count($newsItem->tags)): ?>
                                <h4 class="pl-24">Теги</h4>
                                <ul class="tags pl-24 clearfix">
                                    <?php foreach ($newsItem->tags as $tag): ?>
                                        <li>
                                            <a href="<?= \yii\helpers\Url::to(['/profile/news/' . $tag->name]) ?>">
                                                <?= $tag->name ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <section id="comments-section" class="clearfix">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="section-title">
                                                <p class="title">Коментарі</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12" class="clearfix">
                                            <?= \app\modules\comment\widgets\CommentWidget::widget([
                                                'channelName' => $newsItem->slug,
                                                'template'    => 'vidkruvai',
                                            ]); ?>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
