<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

    <div class="steps-block profile clearfix">
        <div class="cabinet rules">
            <article>
                <div class="sidebar-right-fixed">
                    <?= $this->render('/_blocks/profile-sidebar') ?>
                </div>
                <div class="content-left-fixed">
                    <div class="project-info-page-description">
                        <div class="profile-user-page">
                            <div class="image"></div>

                            <div class="progress-block">
                                <div class="progress-top">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="progress-title">
                                                <h3>
                                                    <?= \app\components\AppMsg::t('Правила'); ?>
                                                </h3>
                                            </div>

                                            Правила текст
                                        </div>

                                        <?= !\Yii::$app->siteUser->identity->agreement_read ?
                                            \yii\helpers\Html::submitButton('Далі',
                                                ['class' => 'link-button', 'id' => 'rules-read-agreement']) :
                                            '' ?>
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
    'acceptAgreementUrl' => '/profile/agreement/',
]);

$this->registerJs('RulesPage(' . $pageOptions . ')');
?>