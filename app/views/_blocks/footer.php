<?php

use \yii\easyii\modules\text\api\Text;

$asset = \app\assets\AppAsset::register($this);
?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12 footer-main">
                <div class="col-md-6 nopadding socials">
                    <div class="links-block">
                        <a class="link" href="#">
                            Поширені питання
                        </a>
                        <a class="link" href="#">
                            Контакт
                        </a>
                        <a class="link" href="#">
                            Партнери
                        </a>
                        <a class="link" href="#">
                            Про проект
                        </a>
                    </div>
                    <div class="footer-logo">
                        <img class="svg" src="<?= $asset->baseUrl ?>/img/logo-grey.png">
                    </div>
                </div>
                <div class="col-md-6 rights nopadding">
                    <div class="social-block">
                        <div class="social-icon-pack">

                            <a class="social-icon" href="#">
                                <img class="svg" src="<?= $asset->baseUrl ?>/img/mail.png">
                            </a>
                            <a class="social-icon" href="#">
                                <img class="svg" src="<?= $asset->baseUrl ?>/img/instagram.png">
                            </a>
                            <a class="social-icon" href="#">
                                <img class="svg" src="<?= $asset->baseUrl ?>/img/youtube.png">
                            </a>
                            <a class="social-icon" href="#">
                                <img class="svg" src="<?= $asset->baseUrl ?>/img/facebook.png">
                            </a>
                        </div>
                    </div>
                    <div class="footer-rights">
                        <?= Text::get('rights-reserved') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>