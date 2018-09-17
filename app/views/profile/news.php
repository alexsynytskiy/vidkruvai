<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $email string */
/* @var $news yii\easyii\modules\news\models\News[] */
/* @var $tag string */
/* @var bool $hasToLoadMore */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-header') ?>
            </div>
            <div class="content-left-fixed">
                <div class="helpers-header clearfix">
                    <?= \yii\helpers\Html::a('<i class="fa fa-check"></i> Прочитати все',
                        null,
                        ['id' => 'mark-all-news-as-read', 'class' => 'no-spinner']) ?>
                </div>

                <div id="news-list">
                    <?php $large = array_shift($news); ?>
                    <?= $this->render('news-item-first', ['item' => $large]) ?>
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
                                <?= 'Показать больше новостей'; ?>
                            </a>

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