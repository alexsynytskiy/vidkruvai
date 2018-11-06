<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $email string */
/* @var $tasks \app\models\Task[] */
/* @var $tag string */
/* @var bool $hasToLoadMore */
/* @var int $lastItemId */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

    <div class="steps-block profile clearfix">
        <div class="cabinet tasks">
            <article>
                <div class="sidebar-right-fixed">
                    <?= $this->render('/_blocks/profile-sidebar') ?>
                </div>
                <div class="content-left-fixed">

                    <div id="tasks-list">
                        <div class="tasks-item task clearfix">
                            <div class="image" style="background: url(<?= '' ?>);background-size: cover;"></div>
                            <div class="information">
                                <div class="title">
                                    <a href="<?= \yii\helpers\Url::to(["#"]) ?>">Test test test</a>
                                </div>
                                <div class="short">
                                    fdfg fg df gdf gdfgdfgergdfg dfgdf gdfg egdgd fgdf
                                </div>
                                <div class="categories clearfix">
                                    <div class="category">
                                        <div class="icon"></div>
                                        <div class="text-category">Суспільство</div>
                                    </div>
                                    <div class="category">
                                        <div class="icon"></div>
                                        <div class="text-category">Місто</div>
                                    </div>
                                </div>
                                <a class="short read-more" href="<?= \yii\helpers\Url::to(["#"]) ?>">Детальніше</a>
                            </div>
                        </div>
                    </div>

                    <?php if(count($tasks)): ?>
                        <div id="tasks-list">
                            <?php foreach ($tasks as $task): ?>
                                <?= $this->render('task-item', ['task' => $task]) ?>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($hasToLoadMore): ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="#"
                                       id="load-more-tasks"
                                       class="button"
                                       data-last-id="<?= $lastItemId ?>">
                                        <?= \app\components\AppMsg::t('Більше завдань') ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="panel-footer">
                            <div class="heading-elements">
                                <span class="heading-text text-semibold"><?= \app\components\AppMsg::t('Завдань поки немає') ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </div>