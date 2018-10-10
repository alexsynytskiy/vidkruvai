<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block contacts clearfix">
    <div class="cabinet questions">
        <article>
            <div class="content-left-fixed container">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <div class="questions-text-bold">Менеджер з комунікації</div>
                        <div class="questions-text">
                            <img class="svg" src="<?= $asset->baseUrl ?>/img/kravchuk.jpg">
                            <div class="text-bold">Кравчук Володимир</div>
                            <a href="tel:+38 050 339 71 73">+38 050 339 71 73</a>

                            <br>Питання по проекту (апеляції): <a href="mailto:vu.proekt@gmail.com">vu.proekt@gmail.com</a>
                            <br>Технічні питання по сайту: <a href="mailto:vu.platform@gmail.com">vu.platform@gmail.com</a>
                            <br><br>Апеляції і питання по проекту подаються на скриньку vu.proekt@gmail.com і розглядаються організаційним комітетом. У темі листа обов'язково має бути слово “апеляція”, а також вкажіть назву міста, школи і команди.
                            <br>У випадку виникнення технічних питань на сайті, потрібно писати на ел.скриньку vu.platform@gmail.com. Для таких звернень необхідно у темі листа зазначити назву міста, школи і команди, а в тілі листа надати опис проблеми і прикріпити скріншот (знімок екрану), який демонструє проблему.
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>