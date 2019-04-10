<?php

/* @var $this yii\web\View */
/* @var bool $itemBought */
/* @var bool $itemLocked */
/* @var \app\models\StoreItem $storeItem */
/* @var \app\models\Category $level */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="item <?= $itemBought ? 'bought' : '' ?>">
    <div class="body <?= $itemLocked ? 'disabled' : '' ?>"
         style="background-image: url('<?= $baseUrl ?>/img/level<?= $level->slug ?>.svg'), url('<?= $storeItem->icon ?>')">
        <div class="body-wrapper">
            <?php if ($itemLocked): ?>
                <div class="lock"></div>
            <?php endif; ?>
            <div class="cost"><?= $storeItem->cost ?></div>
            <div class="icon tooltip-new">
                <i class="fa <?= $itemLocked ? 'fa-lock' : 'fa-info' ?>"></i>
                <span class="tooltiptext"><?= $storeItem->description ?></span>
            </div>
            <?php if (!$itemBought && !$itemLocked): ?>
                <a href="#" data-id="<?= $storeItem->id ?>"
                   class="buy-item">
                    <div class="cart">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
