<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $email string */
/* @var $news yii\easyii\modules\news\models\News[] */
/* @var $tag string */
/* @var bool $hasToLoadMore */
/* @var int $lastItemId */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

    <div class="steps-block profile clearfix">
        <div class="cabinet profile news">
            <article>
                <div class="sidebar-right-fixed">
                    <?= $this->render('/_blocks/profile-sidebar') ?>
                </div>
                <div class="content-left-fixed">
                    <div class="helpers-header clearfix">
                        <?= \yii\helpers\Html::a('<i class="fa fa-check"></i>' . \app\components\AppMsg::t('Прочитати все'),
                            null,
                            ['id' => 'mark-all-news-as-read', 'class' => count($news) ? 'no-spinner' : 'disabled']) ?>
                    </div>

                    <?php if(count($news)): ?>
                        <div id="news-list">
                            <?php $large = array_shift($news); ?>
                            <?php if($large): ?>
                                <?= $this->render('news-item-first', ['item' => $large]) ?>
                            <?php endif; ?>

                            <?php foreach ($news as $item): ?>
                                <?= $this->render('news-item', ['item' => $item]) ?>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($hasToLoadMore): ?>
                            <div class="row">
                                <div class="col-lg-12">

                                    <a href="#"
                                       id="load-more-news"
                                       class="button"
                                       data-last-id="<?= $lastItemId ?>">
                                        <?= \app\components\AppMsg::t('Більше новин') ?>
                                    </a>

                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="panel-footer">
                            <div class="heading-elements">
                                <span class="heading-text text-semibold"><?= \app\components\AppMsg::t('Новин поки немає') ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </div>

<?php
$this->registerJs('News.NewsPage().init()');
?>