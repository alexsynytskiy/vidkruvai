<?php

use yii\helpers\Url;

$asset = \app\assets\AppAsset::register($this);

$currentPage = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;

$contact = '';
$portfolio = '';
$clients = '';
$blog = '';

switch ($controller) {
    case 'blog':
        $blog = "active";
        break;
    case 'contact':
        $contact = "active";
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
                    <a href="<?= Url::to(['/blog']) ?>">
                        Блог
                    </a>
                </li>
                <li class="last <?= $contact ?>">
                    <a href="<?= Url::to(['/contact']) ?>">
                        Контакт
                    </a>
                </li>
            </ul>

        </div>

        <!--/.nav-collapse -->
    </div>
</nav>
