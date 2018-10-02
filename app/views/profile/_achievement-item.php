<?php
/** @var \app\models\Achievement $model */

/** @var integer $userId */

use app\components\AppMsg;
use app\models\definitions\DefUserAchievement;
use yii\helpers\Html;

$awardEmpty = null;
if (!isset($model->awards) || count($model->awards) == 0) {
    $awardEmpty = 'empty';
}

$stepsComplete = 0;

/** @var \app\models\UserAchievement $status */
$status = $model->getUserAchievementStatus($userId ?: Yii::$app->siteUser->id)->one();

if ($status) {
    if ($status->done === DefUserAchievement::IS_IN_PROGRESS) {
        $stepsComplete = $status->performed_steps;
    } elseif ($status->done === DefUserAchievement::IS_DONE) {
        $stepsComplete = $model->required_steps;
    }
}

?>

<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
    <div class="progress-item">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                <div class="knob-block">
                    <input type="text" value="<?= Html::encode($stepsComplete) ?>" data-min="0"
                           data-max="<?= Html::encode($model->required_steps) ?>" class="dial">
                    <div class="counter"><?= ($stepsComplete === $model->required_steps) ? '<span>' .
                            AppMsg::t('Виконано') . '</span>' : Html::encode($stepsComplete) .
                            '<span> / ' . Html::encode($model->required_steps) . '</span>' ?></div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="progress-descr <?= $awardEmpty ?>">
                    <h5><?= Html::encode($model->name) ?></h5>
                    <?php if (null === $awardEmpty): ?>
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