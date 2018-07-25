<?php

/** @var bool $hasTreesToLoadMore */
/** @var landings\comment\components\CommentService $service */
$service = $this->context->commentService;

?>

<?php if(Yii::$app->user->isGuest): ?>
    <?= $this->render('@landings/way2case/views/_blocks/auth-block',
        ['needAuthMsg' => 'Чтобы оставить отзыв, вам необходимо авторизоваться']
    ); ?>
<?php endif; ?>
<div class="comments-admin-online-message">
    <?= 'Уважаемые покупатели, при любых проблемах обращайтесь , мы с радостью Вам поможем!', [
        'supportUrl' => \yii\helpers\Url::to(['/contacts']),
    ]; ?>
</div>
<!-- Testimonials section -->
<section id="testimonials-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="testimonials-list">
                    {form}
                    <li id="comment-list">
                        {items}
                    </li>
                </ul>
            </div>
        </div>
        <?php if($hasTreesToLoadMore): ?>
            <div class="row">
                <div class="col-lg-12">

                    <a href="#"
                       id="load-more-comments"
                       class="button more-testimonials"
                       data-t="<?= $service->template; ?>"
                       data-tree-id="<?= $service->maxTreeId; ?>">
                        <?= 'Показать больше отзывов'; ?>
                    </a>

                </div>
            </div>
        <?php endif; ?>
    </div>
</section>