<?php
/** @var \app\models\forms\RegisterForm $model */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$asset = \app\assets\AppAsset::register($this);
?>

    <div class="steps-block login clearfix">
        <div class="block-left">
            <div class="step-title"><?= 'Реєстрація на проект' ?></div>
            <div class="step-subtitle"><?= 'Заповніть форму реєстрації та створіть захищений пароль. Усі поля обов’язкові для заповнення.' ?></div>
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
                    <div class="custom-option clearfix">
                        <?php foreach (\app\models\SiteUser::getRoles() as $value => $role): ?>
                            <div class="item" data-value="<?= $value ?>"><?= $role ?></div>
                        <?php endforeach; ?>
                    </div>

                    <?= $form->field($model, 'role')->hiddenInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => "Ім'я"]) ?>

                    <?= $form->field($model, 'surname')->textInput(['maxlength' => true, 'placeholder' => 'Прізвище']) ?>

                    <?= $form->field($model, 'age')->textInput(['maxlength' => true, 'placeholder' => 'Вік']) ?>

                    <?= $form->field($model, 'class')->textInput(['maxlength' => true, 'placeholder' => 'Клас']) ?>

                    <?= $form->field($model, 'school')->textInput(['maxlength' => true, 'placeholder' => 'Школа']) ?>

                    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Електронна пошта']) ?>

                    <?= $form->field($model, 'userPassword')->passwordInput(['maxlength' => true, 'placeholder' => 'Пароль']) ?>

                    <?= $form->field($model, 'passwordRepeat')->passwordInput(['maxlength' => true, 'placeholder' => 'Повторіть пароль']) ?>

                    <?= $form->field($model, 'captchaUser')->widget(\yii\captcha\Captcha::className(), [
                        'captchaAction' => 'profile/captcha',
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
                    <a href='<?= \yii\helpers\Url::to(['/site/help']) ?>' class="link-additional">
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

]);

$this->registerJs('RegisterPage(' . $pageOptions . ')');
?>