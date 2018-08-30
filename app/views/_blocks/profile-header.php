<?php ?>

<div class="cabinet-menu nopadding">
    <div class="wrapper">
        <div class="menu-link active">
            <div class="icon"></div>
            <div class="text">Завдання</div>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <div class="text">Новини</div>
        </div>
        <div class="menu-link">
            <div class="icon">
                <div class="new-count">1</div>
            </div>
            <div class="text">Повідомлення</div>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <div class="text">Команда</div>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <div class="text">Профіль</div>
        </div>
        <div class="menu-link">
            <div class="icon"></div>
            <a href="<?= \yii\helpers\Url::to(['/logout']) ?>">
                <div class="text">Вихід</div>
            </a>
        </div>
    </div>
</div>
