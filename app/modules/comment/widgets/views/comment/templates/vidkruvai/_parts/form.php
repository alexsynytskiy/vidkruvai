<?php
use landings\common\components\assets\LandingsAsset;
use landings\common\components\LMsg;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/** @var landings\comment\models\landing\Comment $model */
/** @var View $this */

LandingsAsset::getInstance()->setView($this)->registerAsset();

$this->context->activeFormOptions['fieldConfig'] = [
    'template'     => "{input}\n{error}",
    'inputOptions' => [
        'class' => '',
    ],
];

$user    = Yii::$app->user;
$profile = $user->getSocialProfile();
?>

<?php if(!Yii::$app->user->isGuest): ?>
    <div class="add-testimonials main-comment-form">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-3">
                <div class="test-avatar">
                    <?php if($avatar = $profile->avatar) { ?>
                        <img src="<?= Html::encode($avatar) ?>" alt="avatar">
                    <?php } elseif($user->identity->avatar) { ?>
                        <img src="<?= Html::encode($user->identity->avatar) ?>" alt="avatar">
                    <?php } else { ?>
                        <img src="<?= \Yii::$app->view->params['pathToImages'] . '/account.png' ?>" alt="avatar">
                    <?php } ?>

                    <?php if($name = $profile->name) { ?>
                        <div class="test-login"><?= Html::encode($name) ?></div>
                    <?php } else { ?>
                        <div class="test-login"><?= Html::encode($user->identity->name) ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-9">
                <?php $form = ActiveForm::begin($this->context->activeFormOptions); ?>
                <?= Html::hiddenInput('t', $this->context->commentService->template); ?>
                <div class="form-group clearfix">
                    <?= $form->field($model, 'message')->textarea(['placeholder' => LMsg::t('Оставьте свой отзыв'), 'class' => 'textarea']); ?>
                </div>
                <div class="form-group clearfix">
                    <?= Html::submitButton(LMsg::t('Отправить'), ['class' => 'button']); ?>
                    <?= Html::button(LMsg::t('Отмена'), ['class' => 'button cancel-reply hidden']); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$pageOptions = [
    'cid'         => $this->context->channelId,
    'loadMoreUrl' => "/comment/{$this->context->channelId}/load-more",
    'treesLimit'  => $this->context->commentService->getTreesLimit(),
];

if(!Yii::$app->user->isGuest) {
    $pageOptions = array_merge($pageOptions, [
        'voteUrl'       => "/comment/{$this->context->channelId}/vote",
        'validationUrl' => $this->context->activeFormOptions['validationUrl'],
        'addReloadUrl'  => "/comment/{$this->context->channelId}/reload-tree",
    ]);
}

$pageOptions = yii\helpers\Json::encode($pageOptions);

$this->registerJs("Comment({$pageOptions});");