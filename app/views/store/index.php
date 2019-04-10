<?php

/* @var $this yii\web\View */
/* @var \app\models\Category[] $categories */

\app\assets\StoreAsset::register($this);
$asset = \app\assets\AppAsset::register($this);
\app\assets\ModalAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
$team = \Yii::$app->siteUser->identity->team;

$categoryBoughtCount = 0;
$totalExperience = $team && $team->status === \app\models\definitions\DefTeam::STATUS_ACTIVE ? $team->total_experience : -1;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile store">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="block-title">
                    <div class="icon">
                        <i class="fa fa-shopping-basket"></i>
                    </div>
                    <div class="text">Магазин</div>
                </div>

                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <div class="store-main clearfix">
                            <div uk-filter="target: .js-filter">
                                <ul class="uk-subnav uk-subnav-pill">
                                    <li class="uk-active" uk-filter-control>
                                        <a href="#">Всі категорії
                                            <div class="close"><i class="fa fa-times"></i></div>
                                        </a></li>
                                    <?php foreach ($categories as $category): ?>
                                        <li uk-filter-control="[data-color='<?= $category->slug ?>']"><a
                                                    href="#"><?= $category->name ?>
                                                <div class="close"><i class="fa fa-times"></i></div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <ul class="js-filter uk-child-width-1-2 uk-child-width-1-3@m uk-text-center" uk-grid>
                                    <?php foreach ($categories as $category): ?>
                                        <?php $categoryBoughtCount = 0;
                                        $categoryItemsCount = $category->childrenSubItemsCount(); ?>

                                        <li data-color="<?= $category->slug ?>">
                                            <div class="items <?= $category->slug ?> clearfix">
                                                <div class="head clearfix">
                                                    <div class="title"><?= $category->name ?></div>
                                                    <div class="icon tooltip-new">
                                                        <i class="fa fa-lock"></i>
                                                        <span class="tooltiptext"><?= $category->description ?></span>
                                                    </div>
                                                    <div class="items-count"><?= $categoryItemsCount ?>
                                                        Елементів
                                                    </div>
                                                </div>
                                                <?php
                                                $levels = $category->children()->orderBy('id ASC')->all();
                                                /** @var \app\models\Category[] $levels */
                                                foreach ($levels as $key => $level): ?>
                                                    <?php $storeItems = $level->storeItems;
                                                    $prevLevelPassed = $level->prevLevelPassed(); ?>

                                                    <div class="level clearfix">
                                                        <div class="title"><?= $level->name ?></div>
                                                        <?php foreach ($storeItems as $storeItem): ?>
                                                            <?php $itemBought = $storeItem->isBought();
                                                            $categoryBoughtCount = $itemBought ? ++$categoryBoughtCount : $categoryBoughtCount;
                                                            $itemLocked = !$prevLevelPassed || time() < strtotime($level->enabled_after) || $totalExperience < $storeItem->cost; ?>

                                                            <div class="item <?= $itemBought ? 'bought' : '' ?>">
                                                                <div class="body <?= $itemLocked ? 'disabled' : '' ?>"
                                                                     style="background-image: url('<?= $baseUrl ?>/img/level<?= $level->slug ?>.svg'), url('<?= $storeItem->icon ?>')">
                                                                    <div class="body-wrapper">
                                                                        <?php if ($itemLocked): ?>
                                                                            <div class="lock"></div>
                                                                        <?php endif; ?>
                                                                        <div class="cost"><?= $storeItem->cost ?></div>
                                                                        <div class="icon tooltip-new">
                                                                            <i class="fa fa-lock"></i>
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
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endforeach; ?>
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
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'elementsUrl' => \yii\helpers\Url::to('/progress'),
    'modalPrepareUrl' => '/store/modal-prepare/',
    'buyUrl' => '/store/buy/',
]);

$this->registerJs('StorePage(' . $pageOptions . ')');
?>
