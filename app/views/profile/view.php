<?php
/* @var $news yii\easyii\modules\news\api\News */

$asset = \app\assets\AppAsset::register($this);
?>

<section style="padding-top: 120px;"></section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 blog-post">
                <article>
                    <h2><?= $news->title ?></h2>
                    <time class="comment-date" datetime="1914-12-20 08:00"><?= date('d.m.Y', $news->time); ?></time>
                    <?php if (isset($news->image)): ?>
                        <img class="img-responsive img-post" style="margin: 0 auto 40px;" src="<?= $news->image ?>">
                    <?php endif; ?>
                    <?= $news->text ?>
                </article>
                <?php if (count($news->tags) > 0): ?>
                    <div class="widget">
                        <div class="sidebar-title">
                            <h4>Теги</h4>
                        </div>
                        <ul class="tags">
                            <?php foreach ($news->tags as $tag): ?>
                                <li>
                                    <a href="<?= \yii\helpers\Url::to(['/news', 'tag' => $tag->name]) ?>">
                                        <?= $tag->name ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>