<?php

/* @var array $item */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
$user = \Yii::$app->siteUser->identity;

$additionalMessage = \app\components\AppMsg::t('Сума балів буде списана з командного рахунку');
$additionalMessageFail = false;

if(!$user->isCaptain()) {
    $additionalMessage = \app\components\AppMsg::t('Купувати елементи може лише капітан');
    $additionalMessageFail = true;
}

if($user->team->total_experience < $item['cost']) {
    $additionalMessage = \app\components\AppMsg::t('Недостатньо коштів на рахунку');
    $additionalMessageFail = true;
}

if($item['isBought']) {
    $additionalMessage = \app\components\AppMsg::t('Цей елемент вже придбано');
    $additionalMessageFail = true;
}
?>

<div class="modal sell-item">
    <div class="sell-item-wrapper">
        <div class="header">Ви обрали:</div>
        <div class="selected-item">
            <div class="body"
                 style="background-image: url('<?= $baseUrl ?>/img/level<?= $item['level'] ?>.svg'), url('<?= $item['icon'] ?>')"></div>
        </div>
        <div class="name"><?= $item['itemName'] ?></div>
        <div class="sub-name info">
            З рахунку буде списано
            <div class="bold"><?= $item['cost'] ?> балів</div>
            <br>На рахунку залишиться
            <div class="bold"><?= $user->team->total_experience - $item['cost'] ?> балів</div>
        </div>
        <div class="sub-name finish">
            Придбано
            <div class="bold"><?= $item['itemName'] ?></div>
            за
            <div class="bold"><?= $item['cost'] ?> балів</div>
        </div>
        <div class="description short"><?= $item['itemShort'] ?></div>
        <div class="category">
            <div class="category-wrapper clearfix">
                <i class="fa fa-star icon"></i>
                <div class="text"><?= $item['categoryName'] ?></div>
            </div>
        </div>
        <div class="level clearfix">
            <div class="level-wrapper clearfix">
                <?php for ($i = 0; $i < $item['levelsCount']; $i++): ?>
                    <div class="item <?= $i <= $item['level'] ? 'passed' : '' ?>">
                        <div class="center"></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php if ($user->isCaptain() && $user->team->total_experience >= $item['cost'] && !$item['isBought']): ?>
            <a href="#">
                <div id="buy-question" class="buy active" data-id="<?= $item['itemId'] ?>" data-name="<?= $item['itemName'] ?>"
                     data-cost="<?= $item['cost'] ?>">Купити за <?= $item['cost'] ?> балів<i class="fa fa-angle-right"
                                                                                             aria-hidden="true"></i>
                </div>
            </a>
        <?php else: ?>
            <div class="buy disabled">Купити за <?= $item['cost'] ?> балів<i class="fa fa-angle-right"
                                                                             aria-hidden="true"></i></div>
        <?php endif; ?>
        <div class="description long">
            <?php if ($additionalMessageFail): ?>
                <div class="bold"><?= $additionalMessage ?></div>
            <?php else: ?>
                <?= $additionalMessage ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="arrow-down">
        <div class="center">
            <i class="fa fa-angle-down" aria-hidden="true"></i>
        </div>
    </div>
</div>