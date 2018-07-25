<?php

use \yii\easyii\modules\text\api\Text;

$asset = \app\assets\AppAsset::register($this);
?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12 footer-main">
                <div class="col-md-4 nopadding socials">
                    <div class="socials-block">
                        <div class="social-icon-pack">
                            <a class="social-icon" href="<?= Text::get('facebook-link') ?>">
                                <img src="<?= $asset->baseUrl ?>/img/fb-icon.svg">
                            </a>
                            <a class="social-icon" href="<?= Text::get('instagram-link') ?>">
                                <img src="<?= $asset->baseUrl ?>/img/insta-icon.svg">
                            </a>
                        </div>
                        <a class="phone-number"
                           href="tel:<?= trim(\yii\easyii\modules\text\api\Text::get('phone-number')) ?>">
                            <?= \yii\easyii\modules\text\api\Text::get('phone-number') ?>
                        </a>
                    </div>

                </div>
                <div class="col-md-4 footer-logo">
                    <img class="svg" src="<?= $asset->baseUrl ?>/img/logo-white.png">
                </div>
                <div class="col-md-4 rights nopadding">
                    <?= Text::get('rights-reserved') ?>
                </div>
            </div>
        </div>
    </div>
</footer>