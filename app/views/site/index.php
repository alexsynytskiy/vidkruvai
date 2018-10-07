<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block index clearfix">
    <div class="brand-tags">бренд теги</div>
    <div class="slogan">Слоган проекту в декілька слів та ключових тегів</div>
    <div class="block-right"></div>
    <div class="block-left">
        <div class="step-subtitle">
            <?= 'Короткий опис проекту. Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.' ?>
            <br><br>
            <?= 'Короткий опис проекту. Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
            sed do eiusmod tempor incididunt ut .' ?></div>

        <?= Html::a('Реєстрація', \yii\helpers\Url::to(['/register']), ['class' => 'link-button register']) ?>
        <div class="already">
            <?= 'Маєте профіль? ' . Html::a('Вхід', \yii\helpers\Url::to(['/login']),
                ['class' => 'link-button']) ?>
        </div>
    </div>
</div>
