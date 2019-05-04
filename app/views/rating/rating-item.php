<?php

/* @var $this yii\web\View */
/* @var bool $itemBought */
/* @var \app\models\StoreItem $storeItem */
/* @var \app\models\Category $level */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$description = htmlentities($storeItem->description, null, 'utf-8');
$description = str_replace("&nbsp;", " ", $description);
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
                    <?= $description ?>

                    <?php if($itemBought): ?>
                        <br>
                        <br>
                        <div class="bold">Відкрито після придбань:</div>
                        <?php foreach (explode(',', $storeItem->open_rule) as $ruleID): ?>
                            <?php $causedItem = \app\models\StoreItem::findOne($ruleID); ?>
                            <?php if($causedItem): ?>
                                <?= $causedItem->name ?>
                                <br>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </span>
            </div>
            <?php if (!$itemBought): ?>
                <div class="lock"></div>
            <?php endif; ?>
        </div>
    </div>
</div>
