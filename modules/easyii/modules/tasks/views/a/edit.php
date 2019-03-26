<?php
/** @var  \yii\easyii\modules\tasks\models\AddTaskForm $model */

$this->title = 'Редагувати завдання: ' . $model->task_name;
?>
<?= $this->render('_menu') ?>

<?= $this->render('_form', ['model' => $model]) ?>