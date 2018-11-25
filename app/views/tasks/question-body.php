<?php
/* @var $this yii\web\View */
/* @var $question \app\models\Question */

$test = $question->group;
$testQuestionsCount = count($test->questions);

$countAnswered = $testQuestionsCount - $question->emptyQuestionsCount;
?>

<div class="question clearfix" data-id="<?= $question->id ?>" data-group-id="<?= $question->group_id ?>">
    <?php if ($question->image): ?>
        <div class="question-text">
            <?= $question->text ?>
        </div>
        <div class="image">
            <img src="<?= $question->image ?>">
        </div>
    <?php else: ?>
        <?= $question->text ?>
    <?php endif; ?>
</div>

<div class="timer">
    <div class="timer-wrapper clearfix">
        <div class="timer-icon"></div>
        <div id="time-value"></div>
    </div>
</div>

<div class="answers clearfix">
    <?php foreach ($question->answers as $answer): ?>
        <div class="answer" data-id="<?= $answer->id ?>">
            <div class="text">
                <?= $answer->text ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= \yii\helpers\Html::submitButton('Підтвердити', [
    'class' => 'link-button',
    'id' => 'submit-answer',
]) ?>

<div class="pointer-question">
    <div class="pointer-question-wrapper clearfix">
        <?php for ($i = 0; $i < $countAnswered; $i++): ?>
            <div class="item answered-point"></div>
        <?php endfor; ?>

        <?php for ($i = 0; $i < $testQuestionsCount - $countAnswered; $i++): ?>
            <div class="item unanswered-point"></div>
        <?php endfor; ?>
    </div>
</div>
