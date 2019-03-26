<?php

use yii\easyii\modules\text\api\Text;

$asset = \app\assets\AppAsset::register($this);
?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12 footer-main">
                <div class="col-md-6 nopadding socials">
                    <div class="links-block">
                        <a class="link" href="<?= \yii\helpers\Url::to(['/questions']) ?>">
                            Поширені питання
                        </a>
                        <a class="link" href="<?= \yii\helpers\Url::to(['/contacts']) ?>">
                            Контакти
                        </a>
                        <!--<a class="link" href="#">
                            Партнери
                        </a>
                        <a class="link" href="#">
                            Про проект
                        </a> -->
                    </div>
                    <div class="footer-logo">
                        <img class="svg" src="<?= $asset->baseUrl ?>/img/logo-grey.png">
                    </div>
                </div>
                <div class="col-md-6 rights nopadding">
                    <div class="social-block">
                        <div class="social-icon-pack">
                            <a class="social-icon" href="mailto:vidkryvai.ukrainu@gmail.com">
                                <i class="fa fa-envelope" style="color: #afb7bd;font-size: 24px;"></i>
                            </a>
                            <a class="social-icon" target="_blank" href="https://www.instagram.com/vidkruvai.ukrainu/">
                                <i class="fa fa-instagram" style="color: #afb7bd;font-size: 28px;"></i>
                            </a>
                            <a class="social-icon" target="_blank" href="http://bit.ly/2DQebVI">
                                <i class="fa fa-youtube" style="color: #afb7bd;font-size: 24px;"></i>
                            </a>
                            <a class="social-icon" target="_blank" href="https://www.facebook.com/vidkruvai.ukrainu/">
                                <i class="fa fa-facebook-official" style="color: #afb7bd;font-size: 27px;"></i>
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