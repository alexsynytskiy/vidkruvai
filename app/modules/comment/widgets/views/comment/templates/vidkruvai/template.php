<?php

/** @var bool $hasTreesToLoadMore */
/** @var \app\modules\comment\components\CommentService $service */
$service = $this->context->commentService;

?>

<!-- Testimonials section -->
<section class="col-md-12" id="testimonials-section">
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
</section>