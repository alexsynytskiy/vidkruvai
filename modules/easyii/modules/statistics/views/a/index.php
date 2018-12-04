<?php
/* @var $stateStatistics array */
/* @var $schoolStatistics array */
/* @var $cityStatistics array */

\yii\bootstrap\BootstrapPluginAsset::register($this);
?>

<ul class="nav nav-tabs">
    <li class="active">
        <a data-toggle="tab" href="#state"><?= \app\components\AppMsg::t('Групування за областю') ?></a>
    </li>
    <li>
        <a data-toggle="tab" href="#school"><?= \app\components\AppMsg::t('Групування за школою') ?></a>
    </li>
    <li>
        <a data-toggle="tab" href="#city"><?= \app\components\AppMsg::t('Групування за Містом/Селом/Селищем') ?></a>
    </li>
</ul>
<div class="tab-content">
    <div id="state" class="tab-pane fade in active">
        <br>
        <?= $this->render('_statistics-block', ['statistics' => $stateStatistics]) ?>
    </div>
    <div id="school" class="tab-pane fade">
        <br>
        <?= $this->render('_statistics-block', ['statistics' => $schoolStatistics]) ?>
    </div>
    <div id="city" class="tab-pane fade">
        <br>
        <?= $this->render('_statistics-block', ['statistics' => $cityStatistics]) ?>
    </div>
</div>