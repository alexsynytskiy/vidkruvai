<?php

use app\models\definitions\DefTeamSiteUser;
use yii\helpers\Html;

/** @var \app\models\TeamSiteUser[] $members */

$asset = \yii\easyii\modules\teams\assets\TeamAsset::register($this);
$baseUrl = $asset->baseUrl;
?>

<table class="table table-hover" style="margin-bottom: 20px">
    <thead>
    <tr>
        <th><?= Yii::t('easyii', 'Ім\'я/E-mail') ?></th>
        <th><?= Yii::t('easyii', 'Помилки') ?></th>
        <th><?= Yii::t('easyii', 'Статус') ?></th>
        <th><?= Yii::t('easyii', 'Роль') ?></th>
        <th><?= Yii::t('easyii', 'Клас') ?></th>
        <th><?= Yii::t('easyii', 'Запрошення надіслано') ?></th>
        <th><?= Yii::t('easyii', 'Отримано відповідь на запрошення') ?></th>
        <th><?= Yii::t('easyii', 'Надіслати запрошення ще раз') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($members as $member) : ?>
        <?php
        $memberInstance = $member->user;
        $confirmed = $member->status === DefTeamSiteUser::STATUS_CONFIRMED;
        ?>
        <tr>
            <td><?= $confirmed ? Html::encode($memberInstance->getFullName()) : $member->email ?></td>
            <td><?php if ($member->role === DefTeamSiteUser::ROLE_CAPTAIN &&
                    $memberInstance->role === \app\models\definitions\DefSiteUser::ROLE_MENTOR) : ?>
                    <div class="bold" style="color: red;">Команду створив ментор - необхідно зв'язатись з ментором,
                        та перевизначити капітана у системі. До того часу команда виконувати завдання не може,
                        АКТИВУВАТИ КОМАНДУ ЗАБОРОНЕНО!
                    </div>
                <?php endif; ?>
            </td>
            <td><?= DefTeamSiteUser::getStatusText($member->status) ?></td>
            <td><?= $confirmed ? DefTeamSiteUser::getRoleText($member->role, $memberInstance->role) : '' ?></td>
            <td>
                <?php if ($memberInstance->role === \app\models\definitions\DefSiteUser::ROLE_PARTICIPANT): ?>
                    <?= $memberInstance->class ?>
                <?php endif; ?>
            </td>
            <td><?= date('d.m.Y H:i:s', strtotime($member->created_at)) ?></td>
            <td><?= $member->updated_at ? date('d.m.Y H:i:s', strtotime($member->updated_at)) : '' ?></td>
            <td>
                <?php if ($member->status !== DefTeamSiteUser::STATUS_CONFIRMED): ?>
                    <?= Html::a('Надіслати', '#',
                        ['data-pjax' => 0, 'data-hash' => $member->hash, 'class' => 'send-invitation-again']) ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>