<?php

use app\components\AppMsg;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/** @var \app\modules\comment\models\Comment $model */
/** @var View $this */

$asset = \app\assets\AppAsset::register($this);
$baseUrl = $asset->baseUrl;

\app\modules\comment\assets\CommentAsset::getInstance()->setView($this)->registerAsset();

$this->context->activeFormOptions['fieldConfig'] = [
    'template' => "{input}\n{error}",
    'inputOptions' => [
        'class' => '',
    ],
];

$user = Yii::$app->siteUser;
?>

<?php if (!$user->isGuest): ?>
    <div class="add-testimonials main-comment-form">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-xs-3">
                <div class="test-avatar">
                    <div class="image-cropper">
                        <img src="<?= $user->identity->avatar ?: $baseUrl . '/img/default-avatar-user.jpg' ?>" alt="avatar">
                    </div>
                    <div class="test-login"><?= $user->identity->getFullName() ?></div>
                </div>
            </div>
            <div class="col-lg-10 col-md-9 col-xs-8 input-comment-text">
                <?php $form = ActiveForm::begin($this->context->activeFormOptions); ?>
                <?= Html::hiddenInput('t', $this->context->commentService->template); ?>
                <div class="form-group clearfix">
                    <?= $form->field($model, 'message')->textarea(
                        ['placeholder' => AppMsg::t('Повідомлення'), 'class' => 'textarea']
                    ); ?>
                </div>
                <div class="form-group clearfix">
                    <?= Html::submitButton(AppMsg::t('Відправити'), ['class' => 'button']); ?>
                    <?= Html::button(AppMsg::t('Відміна'), ['class' => 'button cancel-reply hidden']); ?>
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

if (!Yii::$app->siteUser->isGuest) {
    $pageOptions = array_merge($pageOptions, [
        'voteUrl' => "/comment/{$this->context->channelId}/vote",
        'validationUrl' => $this->context->activeFormOptions['validationUrl'],
        'addReloadUrl' => "/comment/{$this->context->channelId}/reload-tree",
    ]);
}

$pageOptions = yii\helpers\Json::encode($pageOptions);

$this->registerJs("Comment({$pageOptions});");