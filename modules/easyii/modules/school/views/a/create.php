<?php
$this->title = Yii::t('easyii', 'Створити школу');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model, 'cities' => []]) ?>
