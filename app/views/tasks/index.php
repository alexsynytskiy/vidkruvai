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

$totalTasks = \app\models\Task::getUserTasksCounters();
$totalTasks = $totalTasks > 0 ? $totalTasks : null;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet tasks">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <?php if (count($tasks)): ?>
                    <?php if($totalTasks): ?>
                        <div class="helpers-header clearfix">
                            <?= \yii\helpers\Html::a('<i class="fa fa-check"></i>' . \app\components\AppMsg::t('Прочитати все'),
                                null,
                                ['id' => 'mark-all-tasks-as-read', 'class' => count($tasks) ? 'no-spinner' : 'disabled']) ?>
                        </div>
                    <?php endif; ?>

                    <div id="tasks-list">
                        <?php foreach ($tasks as $task): ?>
                            <?= $this->render('task-item', ['item' => $task]) ?>
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
                            <span class="heading-text text-semibold">
                                <?= \app\components\AppMsg::t('Завдань поки немає') ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    </div>
</div>

<?php
$this->registerJs('Tasks.TasksPage().init()');
?>