<?php

use app\components\AppMsg;
use app\modules\comment\components\CommentsVisualisationHelper;
use yii\helpers\Html;

/** @var \app\modules\comment\models\Comment[] $comments */
/** @var \app\modules\comment\components\CommentService $service */

$asset = \app\assets\AppAsset::register($this);
$baseUrl = $asset->baseUrl;
\app\modules\comment\assets\CommentAsset::getInstance()->setView($this)->registerAsset();

$service = $this->context->commentService;
$defaultUsername = \app\modules\comment\components\CommentService::UNKNOWN_USERNAME;
$isGuest = $service->isGuest;

foreach ($comments as $comment):
?>

<?php
    $user = $comment->user;
    $username = $user && $user->getFullName() ? $user->getFullName() : $defaultUsername;
    $userId = $user && $user->id ? $user->id : '#';
    $userCommentRating = null;
    $isAllowVote = true;

    if (!$isGuest) {
        $userVote = $comment->userVote;
        if ($userVote) {
            $userCommentRating = $userVote->rating;
        }
    }
?>

    <ul class="comment-wrapper">
        <li class="<?= CommentsVisualisationHelper::leftPaddingClassName($comment->depth); ?>
        <?= $comment->isFirstReply ? 'first-reply' : '' ?> comment"
            style="padding-left: <?= (60 * Html::encode($comment->depth)) ?>px;">
            <div class="testimonials-item" data-id="<?= Html::encode($comment->id) ?>"
                 data-tree-id="<?= Html::encode($comment->tree) ?>"
                 data-depth="<?= Html::encode($comment->depth) ?>">
                <div class="left-side">
                    <div class="image-cropper">
                        <img class="avatar" src="<?= $user->avatar ?: $baseUrl . '/img/default-avatar-user.jpg' ?>">
                    </div>
                </div>
                <div class="right-side">
                    <div class="side-inner">
                        <div class="user-login">
                            <?= $user->getFullName() ?>
                        </div>
                        <div class="comment">
                            <p>
                                <?= nl2br(Html::encode($comment->message)) ?>
                            </p>
                        </div>
                        <?php if (!Yii::$app->siteUser->isGuest): ?>
                            <div class="bottom-panel clearfix">
                                <div class="edit-buttons">
                                    <div class="date">
                                        <?= date('d.m.Y H:i', strtotime($comment->created_at)) ?>
                                    </div>
                                </div>

                                <div class="edit-buttons">
                                    <a href="#" class="reply-to"><?= \app\components\AppMsg::t('Відповісти'); ?></a>
                                </div>

                                <div class="rating">
                                <span class="plus">
                                    <?php if (!$isGuest && $isAllowVote): ?>
                                        <a href="#"
                                           class="rating-btn <?= $userCommentRating === 1 ? ' disabled' : ''; ?>"
                                           data-rating="1">+</a>
                                    <?php else: ?>
                                        +
                                    <?php endif; ?>
                                </span>

                                    <span class="total"><?= Html::encode($comment->rating) ?></span>

                                    <span class="minus">
                                    <?php if (!$isGuest && $isAllowVote): ?>
                                        <a href="#"
                                           class="rating-btn <?= $userCommentRating === -1 ? ' disabled' : ''; ?>"
                                           data-rating="-1">-</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="info-block clearfix">
                        <?php if($user->team): ?>
                            <div class="info-item">
                                <div class="info"><?= AppMsg::t('Команда'); ?></div>
                                <img data-toggle="tooltip" data-placement="top" title="#"
                                     src="<?= $user->team->avatar ?: $baseUrl . '/img/default-avatar.png' ?>" alt="alt">
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <p><?= AppMsg::t('Досвід'); ?></p>
                            <div class="counter"><?= $comment->user->total_experience ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="<?= CommentsVisualisationHelper::leftPaddingClassName($comment->depth + 1) ?> first-reply reply-form"
            style="padding-left: <?= (60 * Html::encode($comment->depth + 1)) ?>px; display: none;">

        </li>
    </ul>

<?php endforeach; ?>