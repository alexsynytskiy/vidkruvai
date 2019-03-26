<?php

use yii\helpers\Url;

$asset = \app\assets\AppAsset::register($this);

$currentPage = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;

$contact = '';
$portfolio = '';
$clients = '';
$blog = '';
$login = '';

switch ($controller) {
    case 'blog':
        $blog = "active";
        break;
    case 'contact':
        $contact = "active";
        break;
    case 'login':
        $login = "active";
        break;
}

$user = \Yii::$app->siteUser->identity;
?>

<nav id="main-menu" class="navbar navbar-inverse navbar-fixed-top" data-spy="affix" data-offset-top="100">
    <div class="container">
        <div class="navbar-header">
            <?php if (!\Yii::$app->siteUser->isGuest): ?>
                <div class="image-cropper">
                    <img src="<?= $user->avatar ?: $baseUrl . '/img/default-avatar-user.jpg' ?>" class="avatar-menu">
                </div>
            <?php endif; ?>
            
            <button aria-controls="navbar" aria-expanded="false" class="navbar-toggle collapsed"
                    data-target="#navbar" data-toggle="collapse" type="button">
                <div class="navbar-mobile-rows">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </div>
            </button>
            <a class="navbar-brand" href="/">
                <img src="<?= $asset->baseUrl ?>/img/logo.png">
            </a>
        </div>
        <div class="navbar-collapse collapse navbar-right" id="navbar">
            <ul class="nav navbar-nav">
                <?php if (!\Yii::$app->siteUser->isGuest): ?>
                    <?= $this->render('/_blocks/profile-sidebar') ?>
                <?php endif; ?>
                <li>
                    <a class="donate" target="_blank" href="https://coukraine.org/donate">
                        Підтримайте нас
                    </a>
                </li>
                <li class="<?= $contact ?>">
                    <a href="<?= Url::to(['/about']) ?>">
                        Про проект
                    </a>
                </li>
                <li class="<?= $contact ?>">
                    <a href="<?= Url::to(['/contacts']) ?>">
                        Контакти
                    </a>
                </li>
                <?php if (\Yii::$app->siteUser->isGuest): ?>
                    <li class="<?= $contact ?>">
                        <a href="<?= Url::to(['/register']) ?>">
                            Реєстрація
                        </a>
                    </li>
                    <li class="last <?= $login ?> login">
                        <a href="<?= Url::to(['/login']) ?>">
                            Вхід
                        </a>
                    </li>
                <?php else: ?>
                    <?= $this->render('/notifications/_top-menu-notifications') ?>
                <?php endif; ?>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>
