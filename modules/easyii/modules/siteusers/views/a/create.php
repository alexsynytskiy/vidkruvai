<?php
$this->title = Yii::t('easyii', 'Создать пользователя');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>