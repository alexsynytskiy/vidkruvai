<?php

use yii\helpers\Html;
use app\models\definitions\DefTeamSiteUser;

/** @var \app\models\TeamSiteUser $member */

$asset = \yii\easyii\modules\teams\assets\TeamAsset::register($this);
$baseUrl = $asset->baseUrl;
$memberInstance = $member->user;

$confirmed = $member->status === DefTeamSiteUser::STATUS_CONFIRMED;
?>

<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <div class="member-item">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                <div class="member-avatar">
                    <div class="image-cropper">
                        <img src="<?= $confirmed ? ($memberInstance->avatar ?: $baseUrl . '/img/default-avatar-user.jpg') :
                            $baseUrl . '/img/default-avatar-user.jpg' ?>" class="avatar">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="progress-descr">
                    <h5><?= $confirmed ? Html::encode($memberInstance->getFullName()) : $member->email ?></h5>
                    <p>
                        <?php if($member->role === DefTeamSiteUser::ROLE_CAPTAIN &&
                            $memberInstance->role === \app\models\definitions\DefSiteUser::ROLE_MENTOR) : ?>
                        <div class="bold" style="color: red;">Команду створив ментор - необхідно зв'язатись з ментором,
                        та перевизначити капітана у системі. До того часу команда виконувати завдання не може, АКТИВУВАТИ КОМАНДУ ЗАБОРОНЕНО!</div>
                        <?php endif; ?>
                        <?= $confirmed ? DefTeamSiteUser::getRoleText($member->role, $memberInstance->role) : '' ?>
                        <br>
                        <?= DefTeamSiteUser::getStatusText($member->status) ?>
                        <?php if($memberInstance->role === \app\models\definitions\DefSiteUser::ROLE_PARTICIPANT): ?>
                            <br>
                            Клас:
                            <?= $memberInstance->class ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>