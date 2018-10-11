<?php

/* @var $this yii\web\View */
/* @var $data yii\data\ActiveDataProvider */

use yii\easyii\modules\news\models\News;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Users';

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
            <th><?= Yii::t('easyii', 'Имя') ?></th>
            <th><?= Yii::t('easyii', 'Фамилия') ?></th>
            <th><?= Yii::t('easyii', 'E-Mail') ?></th>
            <th><?= Yii::t('easyii', 'Роль') ?></th>
            <th><?= Yii::t('easyii', 'Школа') ?></th>
            <th width="100"><?= Yii::t('easyii', 'Status') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->models as $item) : ?>
            <tr data-id="<?= $item->primaryKey ?>">
                <?php if (IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td><?= $item->name ?></td>
                <td><?= $item->surname ?></td>
                <td><?= $item->email ?></td>
                <td><?= \app\models\definitions\DefSiteUser::getUserRoleText($item->role) ?></td>
                <td><?= $item->school ? $item->school->getFullName() : '' ?></td>
                <td class="status">
                    <?= Html::checkbox('', $item->status === \app\models\SiteUser::STATUS_ACTIVE, [
                        'class' => 'switch',
                        'data-id' => $item->primaryKey,
                        'data-link' => Url::to(['/admin/' . $module . '/a']),
                    ]) ?>
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