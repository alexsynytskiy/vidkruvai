<?php
/** @var \app\models\Level $model */

use yii\helpers\Html;
use \app\components\AppMsg;

$awardEmpty = null;
if(!isset($model->awards) || count($model->awards) === 0) {
    $awardEmpty = 'empty';
}
?>

<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
    <div class="progress-item">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                <div class="knob-block">
                    <input type="text" value="0" data-min="0" data-max="<?= Html::encode($model->required_experience) ?>" class="dial">
                    <?php if($model->getNextLevel(\app\models\definitions\DefEntityAchievement::ENTITY_USER)): ?>
                        <div class="counter">
                            <?= AppMsg::t('<span>{amount}</span><span class="descr">досвіду</span>', [
                                'amount' => Html::encode($model->getNextLevel(\app\models\definitions\DefEntityAchievement::ENTITY_USER)
                                        ->required_experience - $model->required_experience),
                            ]); ?>
                        </div>
                    <?php else: ?>
                        <div class="counter">
                            <span><?= AppMsg::t('останній рівень'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="progress-descr <?= $awardEmpty ?>">
                    <h5><?= AppMsg::t('{num}-й рівень', [
                            'num' => Html::encode($model->num),
                        ]); ?></h5>
                    <?php if(null === $awardEmpty): ?>
                        <p>
                            <?= AppMsg::t('Нагорода: <span>{award}</span>', [
                                'award' => Html::encode(implode(', ',
                                    \yii\helpers\ArrayHelper::getColumn((array)$model->awards, 'name'))),
                            ]); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>