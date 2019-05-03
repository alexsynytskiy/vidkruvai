<?php

/* @var $this yii\web\View */
/* @var \app\models\Category[] $categories */

\app\assets\StoreAsset::register($this);
$asset = \app\assets\AppAsset::register($this);
\app\assets\ModalAsset::register($this);

$team = \Yii::$app->siteUser->identity->team;
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

                <?php if(!$team->store_ready): ?>
                    <div class="block-title notification" id="rules-store-notification">
                        <div class="title">
                            Вітаємо у Магазині!
                        </div>
                        <div class="sub-title">
                            Перш ніж робити покупки необхідно ознайомитись з
                            <a href="<?= \yii\helpers\Url::to('/profile/news-item/opis-gejmifikacii-na-sajti') ?>">інструкцією</a>.
                        </div>
                        <div id="rules-store-ready">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            Так, зрозуміло
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div id="school-store"></div>
                <?php else: ?>
                    <div id="school-store">
                        <?= $this->render('categories', ['categories' => $categories]) ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'elementsUrl' => \yii\helpers\Url::to('/progress'),
    'modalPrepareUrl' => '/store/modal-prepare/',
    'buyUrl' => '/store/buy/',
    'rulesReadUrl' => '/store/rules-read/',
]);

$this->registerJs('StorePage(' . $pageOptions . ')');
?>
