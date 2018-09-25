<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $email string */
/* @var $showUserInfo bool */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;

$user = \Yii::$app->siteUser->identity;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar', ['showUserInfo' => $showUserInfo]) ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>
                        <div class="profile-info-main clearfix">
                            <div class="left-side">
                                <img src="<?= $user->avatar ?>" class="avatar">
                                <div class="status"></div>
                            </div>
                            <div class="right-side">
                                <div class="name"><?= $user->name . ' ' . $user->surname ?></div>
                                <div class="school"><?= $user->school ?></div>
                                <div class="rating">Рейтинг: <?= $user->total_experience ?></div>

                                <div class="team-title">
                                    Команда:
                                </div>
                                <div class="personal-achievements">
                                    Досягнення:
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>

<?php
$pageOptions = \yii\helpers\Json::encode([
]);

$this->registerJs('ProfilePage(' . $pageOptions . ')');
?>
