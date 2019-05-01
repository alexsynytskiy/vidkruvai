<?php

/* @var $this yii\web\View */
/* @var bool $itemBought */
/* @var \app\models\StoreItem $storeItem */
/* @var \app\models\Category $level */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="item <?= $itemBought ? 'bought' : '' ?>">
    <div class="body <?= !$itemBought ? 'disabled' : '' ?>"
         style="background-image: url('<?= $baseUrl ?>/img/level<?= $level->slug ?>.svg'), url('<?= $storeItem->icon ?>')">
        <div class="body-wrapper">
            <div class="icon tooltip-new">
                <i class="fa <?= !$itemBought ? 'fa-lock' : 'fa-info' ?>"></i>
                <span class="tooltiptext">
                    <div class="bold"><?= $storeItem->name ?></div>
                    <br>
                    <?= html_entity_decode($storeItem->description) ?>
                </span>
            </div>
            <?php if (!$itemBought): ?>
                <div class="lock"></div>
            <?php endif; ?>
        </div>
    </div>
</div>
