<?php
/* @var $this yii\web\View */
/* @var $item \app\models\Task */

?>

<div class="task-item clearfix">
    <div class="image" style="background: url(<?= $item->image ?>);background-size: cover;"></div>
    <div class="information">
        <div class="title">
            <a href="<?= \yii\helpers\Url::to(["/tasks/{$item->type}/" . $item->hash]) ?>"><?= $item->title ?></a>
        </div>
        <div class="short">
            <?= \yii\helpers\StringHelper::truncate($item->short, 250, '..') ?>
        </div>
        <div class="short item-date">
            <?= date('d.m.Y', $item->time); ?>
        </div>
        <a class="short read-more" href="<?= \yii\helpers\Url::to(["/tasks/{$item->type}/" . $item->hash]) ?>">Детальніше</a>
    </div>
</div>
