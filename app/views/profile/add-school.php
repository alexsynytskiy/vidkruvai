<?php
/** @var \app\models\forms\AddSchoolForm $model */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$asset = \app\assets\AppAsset::register($this);
?>

    <div class="steps-block login clearfix">
        <div class="block-left">
            <div class="step-title"><?= \app\components\AppMsg::t('Додавання школи') ?></div>
            <div class="step-subtitle">
                <?= 'Якщо ти не знайшов свою школу у списку при реєстрації - не біда! 
                Додавай скоріше, та спробуй зареєструватись ще раз.' ?>
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

                    <?= $form->field($model, 'school_number')->textInput(['maxlength' => true, 'placeholder' => '№ Школи']) ?>

                    <?= $form->field($model, 'school_name')->textInput(['maxlength' => true, 'placeholder' => 'Назва школи']) ?>

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

]);

$this->registerJs('AddSchoolPage(' . $pageOptions . ')');
?>