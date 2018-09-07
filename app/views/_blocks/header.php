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
?>

<nav id="main-menu" class="navbar navbar-inverse navbar-fixed-top" data-spy="affix" data-offset-top="100">
    <div class="container">
        <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" class="navbar-toggle collapsed"
                    data-target="#navbar" data-toggle="collapse" type="button">
                <div class="navbar-mobile-rows">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </div>
                Меню
            </button>
            <a class="navbar-brand" href="/">
                <img src="<?= $asset->baseUrl ?>/img/logo.png">
            </a>
        </div>
        <div class="navbar-collapse collapse navbar-right" id="navbar">
            <ul class="nav navbar-nav">
                <li class="<?= $blog ?>">
                    <a href="<?= Url::to(['/lections']) ?>">
                        Освітні Лекції
                    </a>
                </li>
                <li class="<?= $blog ?>">
                    <a href="<?= Url::to(['/news']) ?>">
                        Новини
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
                    <li class="last dropdown dropdown-velocity" id="toolbar-notifications">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-content">
                            <div class="dropdown-content-heading">
                                Уведомления
                            </div>
                            <ul class="media-list media-list-linked width-350" id="toolbar-list-notifications">
                                <li class="media">
                                    <span class="media-link">
                                        <div class="media-body">
                                            Непрочитанные уведомления отсуствуют.
                                        </div>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>

        </div>

        <!--/.nav-collapse -->
    </div>
</nav>
