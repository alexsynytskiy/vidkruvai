<?php
/** @var $cities array */

$this->title = Yii::t('easyii', 'Редагувати школу');
?>
<?= $this->render('_menu') ?>
<div class="edit-form">
    <?= $this->render('_form', ['model' => $model, 'cities' => $cities]) ?>
</div>