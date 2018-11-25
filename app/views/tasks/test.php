<?php

use app\models\Test;

/* @var $this yii\web\View */
/* @var $test \app\models\Test */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile task">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <div class="profile-info-main clearfix">
                            <?php if ($user->team): ?>
                                <div class="questions">
                                    <div class="block <?= $test->active ?> clearfix">
                                        <div class="left-part">
                                            <?php if (in_array($test->active, [Test::MISSED, Test::ANSWERED], false)
                                                && $test->completed_data): ?>
                                                <div class="sub-title info"><?= $test->completed_data ?></div>
                                            <?php elseif (in_array($test->active, [Test::ACTIVE, Test::DISABLED], false)
                                                || !$test->completed_data): ?>
                                                <div class="title"><?= $test->name ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="right-part">
                                            <?php if ($test->active === Test::ACTIVE && Yii::$app->siteUser->identity->isCaptain()): ?>
                                                <div data-hash="<?= $test->task->hash ?>" id="block-start"
                                                     class="start enabled">
                                                    <div class="text title">Старт</div>
                                                    <div class="text sub-title"><?= \app\models\Question::TIME_FOR_ANSWER / 60 ?>
                                                        хв
                                                    </div>
                                                </div>
                                            <?php elseif ($test->active === Test::ANSWERED): ?>
                                                <div class="start <?= $test->active ?>">
                                                    <div class="icon"></div>
                                                </div>
                                            <?php else: ?>
                                                <div class="start <?= $test->active ?>">
                                                    <div class="icon"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="numbers <?= $test->active ?>">
                                            <?php $i = 0; ?>
                                            <?php foreach ($test->teamAnswers as $teamAnswer): ?>
                                                <?php if (in_array($test->active, [Test::ACTIVE, Test::DISABLED],
                                                    false)): ?>
                                                    <div class="number"><?= ++$i ?></div>
                                                <?php elseif ($test->active === Test::MISSED): ?>
                                                    <div class="number">
                                                        <div class="state wrong">
                                                            <?= ++$i ?>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="number">
                                                        <div class="state <?= $teamAnswer->answer &&
                                                        $teamAnswer->answer->is_correct ?
                                                            'correct' : 'wrong' ?>">
                                                            <?= ++$i ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="text test-description">
                                <?= $test->description ?>
                            </div>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'startBlockUrl' => '/tasks/start-test/',
]);

$this->registerJs('TestsPage(' . $pageOptions . ')');
?>
