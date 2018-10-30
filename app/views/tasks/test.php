<?php

use app\models\Test;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */
/* @var $test \app\models\Test */
/* @var $blocksQuestions array */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet profile team">
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
                                                <div class="sub-title"><?= $test->description ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="right-part">
                                            <div class="numbers <?= $test->active ?>">
                                                <?php $i = 0; ?>
                                                <?php foreach ($test->userAnswers as $userAnswer): ?>
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
                                                            <div class="state <?= $userAnswer->answer &&
                                                            $userAnswer->answer->is_correct ?
                                                                'correct' : 'wrong' ?>">
                                                                <?= ++$i ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php if ($test->active === Test::ACTIVE): ?>
                                                <div data-hash="<?= $test->hash ?>" id="block-start"
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
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'startBlockUrl' => '/tasks-test/start-block/',
]);

$this->registerJs('TestsPage(' . $pageOptions . ')');
?>
