<?php
/** @var \app\models\forms\AddSchoolForm $model */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$asset = \app\assets\AppAsset::register($this);
?>

    <div class="steps-block add-school login clearfix">
        <div class="block-left">
            <div class="step-title"><?= \app\components\AppMsg::t('Додати школу') ?></div>
            <div class="step-subtitle">
                <?= 'Якщо ти не знайшов свою школу у списку при реєстрації - не біда! 
                Додай, та спробуй зареєструватись ще раз.' ?>
            </div>
            <div class="social-items clearfix">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'user-register',
                    'enableClientValidation' => true,
                    'options' => [
                        'class' => 'link-form',
                        'enctype' => 'multipart/form-data',
                    ],
                    'fieldConfig' => [
                        'template' => "{input}\n{error}",
                    ],
                ]);
                ?>

                <div class="col-md-12 form-z-index clearfix">
                    <?= $form->field($model, 'state_id')->widget(\kartik\select2\Select2::className(), [
                        'data' => \app\models\State::getList(),
                        'language' => Yii::$app->language,
                        'options' => ['placeholder' => \app\components\AppMsg::t('Область')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>

                    <?= $form->field($model, 'city_id')->widget(\kartik\select2\Select2::className(), [
                        'data' => [],
                        'language' => Yii::$app->language,
                        'options' => ['class' => 'hidden', 'placeholder' => \app\components\AppMsg::t('Місто')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>

                    <?= Html::a('Не знайшов своє місто? Натисни і додай!', '#',
                        ['id' => 'add-new-city', 'class' => 'hidden-link']) ?>

                    <div class="create-new-city-form">
                        <div class="info">Назва міста/села(без уточнюючих аббревіатур м, с, село, і тд.), назва району також не потрібна</div>
                        <?= $form->field($model, 'city_name')->textInput(['maxlength' => true, 'placeholder' => 'Назва міста/села']) ?>
                    </div>

                    <?= $form->field($model, 'type_id')->widget(\kartik\select2\Select2::className(), [
                        'data' => \app\models\SchoolType::getList(),
                        'language' => Yii::$app->language,
                        'options' => ['placeholder' => \app\components\AppMsg::t('Тип учбового закладу')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>

                    <?= $form->field($model, 'school_number')->textInput(['maxlength' => true, 'placeholder' => '№ Учбового закладу']) ?>

                    <?= $form->field($model, 'school_name')->textInput(['maxlength' => true, 'placeholder' => 'Назва учбового закладу']) ?>

                    <?= $form->field($model, 'captchaUser')->widget(\yii\captcha\Captcha::className(), [
                        'captchaAction' => 'validation/captcha/',
                        'options' => [
                            'placeholder' => 'Код перевірки',
                            'autocomplete' => 'off',
                        ],
                        'imageOptions' => [
                            'data-toggle' => "tooltip",
                            'data-placement' => "top",
                            'title' => 'Оновити картинку',
                        ],
                        'template' => '<div class="media-body"><div class="pl-0" style="padding-right: 10px;">{input}</div></div><div class="media-right">{image}</div>',
                    ]) ?>

                    <?= Html::submitButton('Додати', ['class' => 'link-button']) ?>
                    <div class="already">
                        <?= 'Школа вже існує? ' . Html::a('Реєстрація', '/register', ['class' => 'link-button']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="profile-header clearfix">
                <div class="profile-navigation">
                    <a href='<?= \yii\helpers\Url::to(['/contacts']) ?>' class="link-additional">
                        <div class="link-icon">
                            <div class="help"></div>
                        </div>
                        Техпідтримка
                    </a>
                </div>
            </div>
        </div>
        <div class="block-right"></div>
    </div>

<?php
$pageOptions = \yii\helpers\Json::encode([
    'getStateCitiesUrl' => '/profile/get-state-cities/',
]);

$this->registerJs('AddSchoolPage(' . $pageOptions . ')');
?>