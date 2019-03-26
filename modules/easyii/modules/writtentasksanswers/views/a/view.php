<?php

/* @var $this yii\web\View */
/* @var $model \app\models\WrittenTaskAnswer */

$this->title = Yii::t('easyii', 'Перегляд відповіді');
?>

<?= $this->render('_menu') ?>

<div class="col-md-12 form-z-index clearfix">
    <br>
    <b>Команда:</b>
    <br>
    <?= $model->team->name . ' (' . $model->team->school->getFullName() . ')' ?>
    <br><br>

    <b><?= $model->task->name ?><b>
    <br><br>

    <b>Відповідь:</b>
    <br>
    <?= $model->text ?>
</div>
