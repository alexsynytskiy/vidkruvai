<?php

use app\components\AppMsg;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/** @var \app\modules\comment\models\Comment $model */
/** @var View $this */

\app\modules\comment\assets\CommentAsset::getInstance()->setView($this)->registerAsset();

$this->context->activeFormOptions['fieldConfig'] = [
    'template' => "{input}\n{error}",
    'inputOptions' => [
        'class' => '',
    ],
];

$user = Yii::$app->user;
?>

<?php if (!Yii::$app->user->isGuest): ?>
    <div class="add-testimonials main-comment-form">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-3">
                <div class="test-avatar">
                    <img src="<?= \Yii::$app->view->params['pathToImages'] . '/user-icon.png' ?>" alt="avatar">
                    <div class="test-login"><?= 'Name' ?></div>
                </div>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-9">
                <?php $form = ActiveForm::begin($this->context->activeFormOptions); ?>
                <?= Html::hiddenInput('t', $this->context->commentService->template); ?>
                <div class="form-group clearfix">
                    <?= $form->field($model, 'message')->textarea(
                        ['placeholder' => AppMsg::t('Сообщение'), 'class' => 'textarea']
                    ); ?>
                </div>
                <div class="form-group clearfix">
                    <?= Html::submitButton(AppMsg::t('Отправить'), ['class' => 'button']); ?>
                    <?= Html::button(AppMsg::t('Отмена'), ['class' => 'button cancel-reply hidden']); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$pageOptions = [
    'cid' => $this->context->channelId,
    'loadMoreUrl' => "/comment/{$this->context->channelId}/load-more",
    'treesLimit' => $this->context->commentService->getTreesLimit(),
];

if (!Yii::$app->user->isGuest) {
    $pageOptions = array_merge($pageOptions, [
        'voteUrl' => "/comment/{$this->context->channelId}/vote",
        'validationUrl' => $this->context->activeFormOptions['validationUrl'],
        'addReloadUrl' => "/comment/{$this->context->channelId}/reload-tree",
    ]);
}

$pageOptions = yii\helpers\Json::encode($pageOptions);

$this->registerJs("Comment({$pageOptions});");