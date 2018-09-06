<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $email string */
/* @var $showLoadMore bool */
/* @var $news yii\easyii\modules\news\models\News[] */
/* @var $tag string */

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

                <?php $large = array_shift($news); ?>
                <?= $this->render('news-item-first', ['item' => $large]) ?>
                <?php foreach ($news as $item): ?>
                    <?= $this->render('news-item', ['item' => $item]) ?>
                <?php endforeach; ?>
            </div>
        </article>
    </div>
</div>

<?php
$this->registerJs('News.NewsPage().init()');
?>