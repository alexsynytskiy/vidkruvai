<?php
/** @var \app\models\forms\RegisterForm $model */
/** @var bool $invitation */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$asset = \app\assets\AppAsset::register($this);
?>

    <div class="steps-block login clearfix">
        <div class="block-left">
            <div class="step-title"><?= \app\components\AppMsg::t('Реєстрація на проект') ?></div>
            <div class="step-subtitle">
                <?= $invitation ?
                    'Зареєструйся та разом з командою розпочни свій шлях у проекті!' :
                    'Заповніть форму реєстрації та створіть захищений пароль. Усі поля обов’язкові для заповнення.' ?>
            <br><br>
                <?= $invitation ?
                    'Не знайшов свою школу? Перепитай у капітана, він зареєструвався - а отже твоя школа є у нашому списку!' :
                    'Не знайшов свою школу? ' .
                    Html::a('Додати школу', \yii\helpers\Url::to(['/add-school']), ['class' => 'link-button']) ?>
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
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Електронна пошта']) ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => "Ім'я"]) ?>

                    <?= $form->field($model, 'surname')->textInput(['maxlength' => true, 'placeholder' => 'Прізвище']) ?>

                    <div class="custom-option clearfix">
                        <?php foreach (\app\models\SiteUser::getRoles() as $value => $role): ?>
                            <div class="item" data-value="<?= $value ?>"><?= $role ?></div>
                        <?php endforeach; ?>
                    </div>

                    <?= $form->field($model, 'role')->hiddenInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'school_id')->widget(\kartik\select2\Select2::className(), [
                        'data' => \app\models\School::getList(),
                        'language' => Yii::$app->language,
                        'options' => ['placeholder' => \app\components\AppMsg::t('Школа')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>

                    <?= $form->field($model, 'class')->textInput(['maxlength' => true, 'placeholder' => 'Клас']) ?>

                    <?= $form->field($model, 'age')->textInput(['maxlength' => true, 'placeholder' => 'Вік']) ?>

                    <?= $form->field($model, 'userPassword')->passwordInput(['maxlength' => true, 'placeholder' => 'Пароль']) ?>

                    <?= $form->field($model, 'passwordRepeat')->passwordInput(['maxlength' => true, 'placeholder' => 'Повторіть пароль']) ?>

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

                    <?= Html::submitButton('Далі', ['class' => 'link-button']) ?>
                    <div class="already">
                        <?= 'Маєте профіль? ' . Html::a('Вхід', '/login', ['class' => 'link-button']) ?>
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
    'mentorValue' => \app\models\SiteUser::ROLE_MENTOR,
]);

$this->registerJs('RegisterPage(' . $pageOptions . ')');
?>