<?php
$this->title = Yii::t('easyii/lineup', 'Create lineup');
?>
<?= $this->render('_menu', ['category' => $category]) ?>
<?= $this->render('_form', ['model' => $model]) ?>