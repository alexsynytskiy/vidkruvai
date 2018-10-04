<?php
/** @var \app\models\forms\TeamCreateForm $model */

use app\components\AppMsg;
use yii\widgets\ActiveForm;

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="cabinet create-team">
        <article>
            <div class="sidebar-right-fixed">
                <?= $this->render('/_blocks/profile-sidebar') ?>
            </div>
            <div class="content-left-fixed">
                <div class="project-info-page-description">
                    <div class="profile-user-page">
                        <div class="image"></div>

                        <div id="profile-info" class="clearfix">
                            <div class="user-form-edit">
                                <div class="step-title"><?= 'Редагувати команду' ?></div>

                                <?= $this->render('_form', ['model' => $model,
                                    'button' => AppMsg::t('Оновити команду')]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>
