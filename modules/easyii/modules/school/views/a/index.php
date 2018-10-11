<?php

/* @var $this yii\web\View */

/* @var $data yii\data\ActiveDataProvider */

use yii\helpers\Url;

$this->title = 'Schools';

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <?php if (IS_ROOT) : ?>
                <th width="50">#</th>
            <?php endif; ?>
            <th><?= Yii::t('easyii', 'Область') ?></th>
            <th><?= Yii::t('easyii', 'Город') ?></th>
            <th><?= Yii::t('easyii', 'Тип') ?></th>
            <th><?= Yii::t('easyii', '№') ?></th>
            <th><?= Yii::t('easyii', 'Название') ?></th>
            <th width="150">#</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->models as $item): ?>
            <?php /** School $item */ ?>

            <tr data-id="<?= $item->primaryKey ?>">

                <?php if (IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td><?= $item->city->state->name ?></td>
                <td><?= $item->city->city ?></td>
                <td><?= $item->type->name ?></td>
                <td><?= $item->number ?></td>
                <td><?= $item->name ?></td>
                <td><a href="<?= Url::to(['/admin/' . $module . '/a/edit/', 'id' => $item->primaryKey]) ?>">Редактировать</a>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>
