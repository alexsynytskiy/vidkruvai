<?php

use \app\models\definitions\DefTask;

/* @var $this yii\web\View */
/* @var $item \app\models\Task */

$asset = \app\assets\AppAsset::register($this);
$baseUrl = $asset->baseUrl;
?>

<div class="tasks-item task clearfix">
    <div class="image" style="background: url(<?= $item->image ?: $baseUrl . '/img/task-default.png' ?>); background-size: cover; background-repeat: no-repeat;"></div>
    <div class="information">
        <div class="icon-states">
            <?php if ($item->read): ?>
                <i class="fa fa-circle new-task" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                   title="<?= \app\components\AppMsg::t('Ви отримали нове завдання!') ?>"></i>
            <?php endif; ?>
            <?php if ($item->required): ?>
                <i class="fa fa-asterisk" aria-hidden="true" data-toggle="tooltip" data-placement="top"
                   title="<?= \app\components\AppMsg::t('Це завдання - обов\'язкове') ?>"></i>
            <?php endif; ?>
        </div>

        <div class="title clearfix">
            <?php if($item->stateForTeam === DefTask::ACTIVE): ?>
                <a href="<?= \yii\helpers\Url::to(["/tasks/{$item->item_type}/" . $item->hash]) ?>"><?= $item->object->name ?></a>
            <?php else: ?>
                <?= $item->object->name ?>
            <?php endif; ?>
            <div class="comments-count-task"><?= $item->commentsCount ?></div>
        </div>

        <div class="short">
            <?= \yii\helpers\StringHelper::truncate($item->object->short, 250, '..') ?>
        </div>

        <div class="short item-date">
            <div class="dark">Старт</div>
            <?= date('d.m.Y H:i:s ', strtotime($item->starting_at)) .
            '<div class="dark">- Дедлайн</div>' .
            date(' d.m.Y H:i:s', strtotime($item->ending_at)) ?>
        </div>

        <div class="categories clearfix">
            <div class="category">
                <div class="icon <?= $item->item_type ?>"></div>
                <div class="text-category"><?= DefTask::getTypeText($item->item_type) ?></div>
            </div>
        </div>

        <div class="team-state <?= $item->stateForTeam ?>">
            <?= \app\models\definitions\DefTask::getStateText($item->stateForTeam) ?>
        </div>

        <?php if (!in_array($item->stateForTeam, [DefTask::DISABLED, DefTask::MISSED], false)): ?>
            <a class="short read-more" href="<?= \yii\helpers\Url::to(["/tasks/{$item->item_type}/" . $item->hash]) ?>">
                <?= $item->stateForTeam === DefTask::ACTIVE ? 'Детальніше' : 'Переглянути' ?>
            </a>
        <?php endif; ?>

        <div class="heading-elements" data-task-id="<?= $item->id; ?>">
            <?php if ($item->read): ?>
                <a id="read<?= $item->id ?>"
                   href="<?= \yii\helpers\Url::to(['/tasks/read', 'id' => $item->id]) ?>"
                   class="label label-success heading-text read-tasks" data-ajax="1">
                    <i class="fa fa-check"></i>Ознайомився
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>