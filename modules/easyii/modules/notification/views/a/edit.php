<?php
$this->title = Yii::t('easyii', 'Редактировать команду');

?>
<?= $this->render('_menu') ?>

<b>Верифицировать:</b>
<?= \yii\helpers\Html::checkbox('', $model->status === \app\models\definitions\DefTeam::STATUS_ACTIVE, [
    'class' => 'switch',
    'data-id' => $model->primaryKey,
    'data-link' => \yii\helpers\Url::to(['/admin/' . $this->context->module->id . '/a']),
]); ?>

<div class="edit-form">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>