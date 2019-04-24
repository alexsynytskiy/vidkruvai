<?php
/* @var \app\models\State $state */
/* @var array $stateProgress */
/* @var \app\models\Category[] $categories */

?>

<div class='graph'>
    <?php if ($stateProgress['rating'] > 0): ?>
        <div class='rating clearfix'>
            <?php foreach ($categories as $key => $category): ?>
                <div class='value'
                     style='height: <?= $stateProgress[$category->name] * 100 ?>%; left: <?= $key * count($categories) ?>px;'></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <a href="#" id="state-rating" data-id="<?= $state->id ?>">
        <div class='title clearfix'><?= $state->name ?>
            область</div>
    </a>
</div>
