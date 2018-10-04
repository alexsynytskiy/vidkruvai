<?php

use yii\helpers\Html;
use app\models\definitions\DefTeamSiteUser;

/** @var \app\models\TeamSiteUser $member */

$asset = \app\assets\AppAsset::register($this);
$baseUrl = $asset->baseUrl;
$memberInstance = $member->user;

$confirmed = $member->status === DefTeamSiteUser::STATUS_CONFIRMED;
?>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="member-item">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                <div class="member-avatar">
                    <img src="<?= $confirmed ? ($memberInstance->avatar ?: $baseUrl . '/img/user-default.png') :
                        $baseUrl . '/img/user-default.png' ?>" class="avatar">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="progress-descr">
                    <h5><?= $confirmed ? Html::encode($memberInstance->getFullName()) : $member->email ?></h5>
                    <p>
                        <?= $confirmed ? DefTeamSiteUser::getRoleText($member->role, $memberInstance->role) :
                            DefTeamSiteUser::getStatusText($member->status) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
