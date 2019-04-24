<?php

use app\components\AppMsg;

/* @var $this yii\web\View */
/* @var \app\models\Category[] $categories */
/* @var $statesRating array */
/* @var $stateId int */

if (!isset($stateId)) {
    $stateId = \Yii::$app->siteUser->identity->school->city->state_id;
}

$asset = \app\assets\AppAsset::register($this);
?>

<div class="levels-table-block clearfix">
    <table class="rating-table table table-bordered col-lg-12 col-md-12">
        <thead>
        <tr>
            <td class="col-lg-1 col-md-1"></td>
            <td class="col-lg-2 col-md-2"><?= AppMsg::t('Область'); ?></td>
            <?php foreach ($categories as $category): ?>
                <td class="col-lg-2 col-md-2"><?= $category->name ?></td>
            <?php endforeach; ?>
            <td class="col-lg-3 col-md-3"><?= AppMsg::t('Загальний рейтинг'); ?></td>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1;
        foreach ($statesRating as $stateName => $categoryValues): ?>
            <tr class="<?= $i === 1 ? 'first' : ($i === 2 ? 'second' : ($i === 3 ? 'third' : '')) ?> <?= $stateId === $categoryValues['stateId'] ? 'active' : '' ?>">
                <td class="col-lg-1 col-md-1"><?= $i ?></td>
                <td class="col-lg-2 col-md-2 state-name"><?= $stateName . ' область' ?></td>
                <?php $rating = 0;
                foreach ($categories as $category): ?>
                    <td class="col-lg-2 col-md-2"><?= $categoryValues[$category->name] * 100 ?>%</td>
                    <?php $rating += $categoryValues[$category->name]; endforeach; ?>
                <td class="col-lg-3 col-md-3"><?= round($rating / count($categories), 2) * 100 ?>%</td>
            </tr>
            <?php $i++; endforeach; ?>
        </tbody>
    </table>
</div>