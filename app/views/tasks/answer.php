<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */
/* @var $blockQuestion \app\models\Question */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$userAnswer = \app\models\UserAnswer::findOne([
    'question_id' => $blockQuestion->id,
    'user_id' => \Yii::$app->siteUser->identity->id,
    'answer_id' => null,
]);

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
                                    <div id="question-wrapper">
                                        <?= $this->render('/_blocks/question-body',
                                            ['blockQuestion' => $blockQuestion]) ?>
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
    'questionId' => $blockQuestion->id,
    'checkAnswerUrl' => '/tasks-test/answer-check/',
    'expiringAt' => strtotime($userAnswer->started_at) + \app\models\Question::TIME_FOR_ANSWER,
]);

$this->registerJs('AnswerPage(' . $pageOptions . ')');
?>