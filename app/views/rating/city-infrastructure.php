<?php

/* @var \app\models\Category[] $categories */
/* @var integer $cityId */
/* @var integer $teamId */

$city = \app\models\City::findOne($cityId);
$teamId = isset($teamId) ? $teamId : \Yii::$app->siteUser->identity->team->id;
$team = \app\models\Team::findOne($teamId);
?>

<div uk-filter="target: .js-filter">
    <ul class="uk-subnav uk-subnav-pill">
        <li class="uk-active" uk-filter-control>
            <a href="#">Всі категорії
                <div class="close"><i class="fa fa-times"></i></div>
            </a></li>
        <?php foreach ($categories as $category): ?>
            <?php if ($category->childrenSubItemsCount() > 0): ?>
                <li uk-filter-control="[data-color='<?= $category->slug ?>']"><a
                            href="#"><?= $category->name ?>
                        <div class="close"><i class="fa fa-times"></i></div>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <ul class="js-filter uk-child-width-1-2 uk-child-width-1-3@m uk-text-center" uk-grid>
        <?php foreach ($categories as $category): ?>
            <?php $categoryBoughtCount = 0;
            $categoryItemsCount = $category->childrenSubItemsCount(\app\models\definitions\DefStoreItem::TYPE_CITY); ?>

            <?php if ($categoryItemsCount > 0): ?>
                <li data-color="<?= $category->slug ?>">
                    <div class="items <?= $category->slug ?> clearfix">
                        <div class="head clearfix">
                            <div class="icon tooltip-new">
                                <i class="fa fa-info"></i>
                                <span class="tooltiptext"><?= $category->description ?></span>
                            </div>
                            <div class="title">Команда <?= $team->name ?> (<?= $city->city ?>, <?= $city->state->name ?> область) - <?= $category->name ?></div>
                            <div class="items-count"><?= $categoryItemsCount ?>
                                Елементів
                            </div>
                        </div>
                        <?php
                        $levels = $category->children()->orderBy('id ASC')->all();
                        /** @var \app\models\Category[] $levels */
                        foreach ($levels as $key => $level): ?>
                            <?php $storeItems = $level->storeItemsCity;
                            if ($storeItems):
                                $prevLevelPassed = $level->prevLevelPassed(); ?>

                                <div class="level clearfix">
                                    <div class="title"><?= $level->name ?></div>
                                    <?php foreach ($storeItems as $storeItem): ?>
                                        <?php $itemBought = $storeItem->isBoughtCity($city->id, $teamId);
                                        $categoryBoughtCount = $itemBought ? ++$categoryBoughtCount : $categoryBoughtCount; ?>

                                        <?= $this->render('rating-item', [
                                            'itemBought' => $itemBought,
                                            'storeItem' => $storeItem,
                                            'level' => $level,
                                        ]) ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; endforeach; ?>
                    </div>
                    <div class="progress">
                        <div class="value"
                             id="<?= $category->slug ?>-chart"
                             style="width: <?= ($categoryBoughtCount * 100) / $categoryItemsCount ?>%"></div>
                    </div>
                    <div class="progress-description">
                        Відкрито
                        <div style="display: inline-block"
                             id="<?= $category->slug ?>-text"><?= $categoryBoughtCount ?>
                            /<?= $categoryItemsCount ?>
                        </div>
                    </div>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
