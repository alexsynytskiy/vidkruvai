<?php

/* @var $this yii\web\View */
/* @var bool $itemBought */
/* @var bool $itemLocked */
/* @var \app\models\StoreItem $storeItem */
/* @var \app\models\Category $level */
/* @var integer $teamId */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$description = htmlentities($storeItem->description, null, 'utf-8');
$description = str_replace("&nbsp;", " ", $description);
?>

<div class="item <?= $itemBought ? 'bought' : '' ?>">
    <div class="body <?= $itemLocked ? 'disabled' : '' ?>"
         style="background-image: url('<?= $baseUrl ?>/img/level<?= $level->slug ?>.svg'), url('<?= $storeItem->icon ?>')">
        <div class="body-wrapper">
            <div class="icon tooltip-new">
                <i class="fa <?= $itemLocked ? 'fa-lock' : 'fa-info' ?>"></i>
                <span class="tooltiptext">
                    <div class="bold"><?= $storeItem->name ?></div>
                    <br>
                    <?= $description ?>
                </span>
            </div>
            <?php if ($itemLocked): ?>
                <div class="lock"></div>
            <?php endif; ?>
            <div class="cost"><?= $storeItem->teamAdoptedCost($teamId) ?></div>
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
