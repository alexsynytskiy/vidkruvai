<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block index clearfix">
    <div class="brand-tags">Стань супергероєм - зміни своє місто!</div>
    <div class="slogan">«Відкривай Україну» - освітній проект для учнів 7-11 класів з усієї України</div>
    <div class="mobile-actions">
        <?= Html::a('Реєстрація', \yii\helpers\Url::to(['/register']), ['class' => 'link-button register']) ?>
        <div class="already">
            <?= 'Маєте профіль? ' . Html::a('Вхід', \yii\helpers\Url::to(['/login']),
                ['class' => 'link-button']) ?>
        </div>
    </div>
    <div class="block-right"></div>
    <div class="block-left">
        <div class="step-subtitle">
            <?= 'Мета проекту - створити середовище для розвитку підлітків міст України, у якому вони набудуть 
            необхідних вмінь та отримають можливість реалізувати командою власну ідею у школі, а згодом - у місті.' ?>
            <br><br>
            <?= 'Навчальний процес буде проходити на геймифікованій онлайн-платформі, яка містить інтерактивні 
            інструкції, навчальні матеріали, надихаючі історії успіху та багато іншого!' ?></div>
        <?php if (\Yii::$app->siteUser->isGuest): ?>
        <div class="desktop-actions">
            <?= Html::a('Реєстрація', \yii\helpers\Url::to(['/register']), ['class' => 'link-button register']) ?>
            <div class="already">
                <?= 'Маєте профіль? ' . Html::a('Вхід', \yii\helpers\Url::to(['/login']),
                    ['class' => 'link-button']) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
