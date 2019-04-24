<?php
/** @var $cities array */

$this->title = Yii::t('easyii', 'Редагувати категорію');
?>
<?= $this->render('_menu') ?>
<div class="edit-form">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>