<?php

use app\components\AppMsg;
use \app\models\definitions\DefTeam;
use \app\components\helpers\StockHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $showTeamInfo bool */
/* @var $achievements array */
/* @var $levelInfo array */
/* @var $previousLevels array */
/* @var $nextLevels array */
/* @var $preview boolean */
/* @var array $entityCredentials */

\app\assets\StoreAsset::register($this);
$asset = \app\assets\AppAsset::register($this);
\app\assets\ModalAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
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
                                        <a href="#">Всі категорії<div class="close"><i class="fa fa-times"></i></div></a></li>
                                    <li uk-filter-control="[data-color='infrastructure']"><a href="#">Інфраструктура
                                            <div class="close"><i class="fa fa-times"></i></div>
                                        </a>
                                    </li>
                                    <li uk-filter-control="[data-color='sport']"><a href="#">Спорт і здоровя<div class="close"><i class="fa fa-times"></i></div></a></li>
                                    <li uk-filter-control="[data-color='speech']"><a href="#">Спілкування та атмосфера<div class="close"><i class="fa fa-times"></i></div></a></li>
                                    <li uk-filter-control="[data-color='science']"><a href="#">Наука та розвиток<div class="close"><i class="fa fa-times"></i></div></a></li>
                                    <li uk-filter-control="[data-color='ecology']"><a href="#">Екологія ста сталість<div class="close"><i class="fa fa-times"></i></div></a></li>
                                    <li uk-filter-control="[data-color='art']"><a href="#">Арт<div class="close"><i class="fa fa-times"></i></div></a></li>
                                </ul>

                                <ul class="js-filter uk-child-width-1-2 uk-child-width-1-3@m uk-text-center" uk-grid>
                                    <li data-color="infrastructure">
                                        <div class="items clearfix">
                                            <div class="head clearfix">
                                                <div class="title">Інфраструктура</div>
                                                <div class="icon tooltip-new">
                                                    <i class="fa fa-lock"></i>
                                                    <span class="tooltiptext">Tooltip text</span>
                                                </div>
                                                <div class="items-count">10 Елементів</div>
                                            </div>
                                            <div class="level clearfix">
                                                <div class="title">Базовий рівень</div>
                                                <div class="item bought">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/floor1.svg')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">800</div>
                                                            <div class="icon tooltip-new">
                                                                <i class="fa fa-lock"></i>
                                                                <span class="tooltiptext">Tooltip text</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="level clearfix">
                                                <div class="title">Перший рівень</div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/entrance.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">400</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <a href="#" data-id="1" class="buy-item">
                                                                <div class="cart">
                                                                    <i class="fa fa-shopping-cart"></i>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/cycle.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">500</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <div class="cart">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/cycle.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">500</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <div class="cart">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="level clearfix">
                                                <div class="title">Другий рівень</div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/entrance.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">400</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <div class="cart">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/cycle.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">500</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <div class="cart">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="level clearfix">
                                                <div class="title">Третій рівень</div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/entrance.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">400</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <div class="cart">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/cycle.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">500</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <div class="cart">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="level clearfix">
                                                <div class="title">Четвертий рівень</div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/entrance.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">400</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <div class="cart">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item">
                                                    <div class="body" style="background-image: url('<?= $baseUrl ?>/img/level<?= 1 ?>.png'), url('<?= $baseUrl ?>/img/cycle.png')">
                                                        <div class="body-wrapper">
                                                            <div class="cost">500</div>
                                                            <div class="icon">
                                                                <i class="fa fa-lock"></i>
                                                            </div>
                                                            <div class="cart">
                                                                <i class="fa fa-shopping-cart"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress">
                                            <div class="value" style="width: 10%"></div>
                                        </div>
                                        <div class="progress-description">
                                            Відкрито 1/10
                                        </div>
                                    </li>
                                    <li data-color="sport">
                                        <div class="items">

                                        </div>
                                        <div class="progress">
                                            <div class="value" style="width: 10%"></div>
                                        </div>
                                        <div class="progress-description">
                                            Відкрито 1/10
                                        </div>
                                    </li>
                                    <li data-color="speech">
                                        <div class="items">

                                        </div>
                                        <div class="progress">
                                            <div class="value" style="width: 10%"></div>
                                        </div>
                                        <div class="progress-description">
                                            Відкрито 1/10
                                        </div>
                                    </li>
                                    <li data-color="science">
                                        <div class="items">

                                        </div>
                                        <div class="progress">
                                            <div class="value" style="width: 10%"></div>
                                        </div>
                                        <div class="progress-description">
                                            Відкрито 1/10
                                        </div>
                                    </li>
                                    <li data-color="ecology">
                                        <div class="items">

                                        </div>
                                        <div class="progress">
                                            <div class="value" style="width: 10%"></div>
                                        </div>
                                        <div class="progress-description">
                                            Відкрито 0/10
                                        </div>
                                    </li>
                                    <li data-color="art">
                                        <div class="items">

                                        </div>
                                        <div class="progress">
                                            <div class="value" style="width: 20%"></div>
                                        </div>
                                        <div class="progress-description">
                                            Відкрито 2/10
                                        </div>
                                    </li>
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
    'elementsUrl' => \yii\helpers\Url::to('/progress/')
]);

$this->registerJs('StorePage(' . $pageOptions . ')');
?>
