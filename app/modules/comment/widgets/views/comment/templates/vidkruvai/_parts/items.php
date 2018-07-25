<?php
use landings\common\components\LMsg;
use landings\way2case\components\CommentsVisualisationHelper;
use yii\helpers\Html;

/** @var landings\comment\models\landing\Comment[] $comments */
/** @var landings\comment\components\CommentService $service */

\landings\common\components\assets\Way2CaseAsset::getInstance()->setView($this)->registerAsset();

$service         = $this->context->commentService;
$defaultUsername = landings\comment\components\CommentService::UNKNOWN_USERNAME;
$isGuest         = $service->isGuest;


foreach($comments as $comment): ?>

    <?php
    $user              = $comment->landingUser;
    $username          = $user && $user->name ? $user->name : $defaultUsername;
    $userId            = $user && $user->id ? $user->id : '#';
    $userCommentRating = null;
    $isAllowVote       = true;
    $casesOpened       = \acp\models\landing\Sale::getUserCases($user->id ?? null);

    if(!$isGuest) {
        $userVote = $comment->userVote;
        if($userVote) {
            $userCommentRating = $userVote->rating;
        }
    }

    $levelGroupSlug = $user->level->levelgroup->slug ?? '';
    ?>

    <ul class="comment-wrapper">
        <li class="<?= CommentsVisualisationHelper::leftPaddingClassName($comment->depth); ?> <?= $comment->isFirstReply ? "first-reply" : "" ?> comment" style="padding-left: <?= (40 * Html::encode($comment->depth)) ?>px;">
            <div class="testimonials-item" data-id="<?= Html::encode($comment->id) ?>" data-tree-id="<?= Html::encode($comment->tree) ?>" data-depth="<?= Html::encode($comment->depth) ?>">
                <div class="left-side">
                    <div class="avatar">
                        <?php if($user && $user->avatar) { ?>
                            <img class="<?= Html::encode($levelGroupSlug) ?>-border" src="<?= Html::encode($user->avatar); ?>">
                        <?php } else { ?>
                            <img class="<?= Html::encode($levelGroupSlug) ?>-border" src="<?= \Yii::$app->view->params['pathToImages'] . '/account.png' ?>" alt="avatar">
                        <?php } ?>
                    </div>
                    <?php if(isset($user->level->num)): ?>
                        <div class="rank">
                            <a href="<?= \yii\helpers\Url::to(($userId == Yii::$app->user->id) ? '/profile/levels' : "/profile/{$userId}/levels") ?>" data-toggle="tooltip" data-placement="top" title="<?= LMsg::t('Уровень: {num}', ['num' => Html::encode($user->level->num)]); ?>" class="level-group <?= Html::encode($levelGroupSlug) ?>"><?= $user->level->levelgroup->name ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="right-side">
                    <div class="side-inner">
                        <div class="user-login">
                            <?= \yii\helpers\Html::a(Html::encode($username),
                                ["/profile/" . $userId]) ?>
                        </div>
                        <div class="comment">
                            <p>
                                <?= nl2br(Html::encode($comment->message)) ?>
                            </p>
                        </div>
                        <?php if(!Yii::$app->user->isGuest): ?>
                            <div class="bottom-panel clearfix">
                                <div class="edit-buttons">
                                    <div class="date">
                                        <?= date('d.m.Y H:i', strtotime($comment->created_at)) ?>
                                    </div>
                                </div>

                                <div class="edit-buttons">
                                    <a href="#" class="reply-to"><?= LMsg::t('Ответить'); ?></a>
                                </div>

                                <div class="rating">
                                <span class="plus">
                                    <?php if(!$isGuest && $isAllowVote): ?>
                                        <a href="#" class="rating-btn <?= $userCommentRating === 1 ? ' disabled' : ''; ?>" data-rating="1">+</a>
                                    <?php else: ?>
                                        +
                                    <?php endif; ?>
                                </span>

                                    <span class="total"><?= Html::encode($comment->rating) ?></span>

                                    <span class="minus">
                                    <?php if(!$isGuest && $isAllowVote): ?>
                                        <a href="#" class="rating-btn <?= $userCommentRating === -1 ? ' disabled' : ''; ?>" data-rating="-1">-</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="info-block clearfix">
                        <?php if(isset($user->lastSaleItem)) : ?>
                            <div class="info-item">
                                <div class="info"><?= LMsg::t('Последний выигрыш'); ?></div>
                                <img data-toggle="tooltip" data-placement="top" title="<?= Html::encode($user->lastSaleItem->objectName) ?>" src="<?= Html::encode($user->lastSaleItem->objectImage) ?>" alt="alt">
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <p><?= LMsg::t('Открыто <span>кейсов</span>'); ?></p>
                            <div class="counter"><?= Html::encode($casesOpened) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="<?= CommentsVisualisationHelper::leftPaddingClassName($comment->depth + 1) ?> first-reply reply-form" style="padding-left: <?= (40 * (Html::encode($comment->depth + 1))) ?>px; display: none;">

        </li>
    </ul>

<?php endforeach; ?>