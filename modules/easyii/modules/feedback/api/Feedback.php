<?php
namespace yii\easyii\modules\feedback\api;

use Yii;
use yii\base\Exception;
use yii\easyii\helpers\TypeHelper;
use yii\easyii\modules\feedback\models\Feedback as FeedbackModel;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\ReCaptcha;


/**
 * Feedback module API
 * @package yii\easyii\modules\feedback\api
 *
 * @method static string form(array $options = []) Returns fully worked standalone html form.
 * @method static array save(array $attributes) If you using your own form, this function will be useful for manual saving feedback's.
 */

class Feedback extends \yii\easyii\components\API
{
    const SENT_VAR = 'feedback_sent';

    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_form($options = [])
    {
        try {

            $model = new FeedbackModel;
            $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;
            $options = array_merge($this->_defaultFormOptions, $options);

            ob_start();
            $form = ActiveForm::begin([
                'action' => Url::to(['/admin/feedback/send']),
                'options' => [
                    'class' => 'col-md-6 contact-form',
                    'enctype' => 'multipart/form-data'
                ],
                'fieldConfig' => [
                    'template' => "<div class=\"form-group\">{input}</div>",
                    'options' => [
                        'tag' => false,
                    ],
                ],
            ]);

            echo '<div class="form-title">Заповніть контактну форму</div>';
            echo '<div class="form-sub-title">Ми зв’яжемось з вами найближчим часом</div>';

            echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current([self::SENT_VAR => 0]));
            echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current([self::SENT_VAR => 1]));

            echo $form->field($model, 'name')->textInput(['class' => 'form-control', 'id' => 'first-name', 'placeholder' => 'Ім\'я']);

            echo $form->field($model, 'phone')->textInput(['class' => 'form-control', 'id' => 'last-name', 'placeholder' => 'Телефон']);

            echo $form->field($model, 'email')->input('email', ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'E-mail']);

            echo $form->field($model, 'client_type')->dropDownList(TypeHelper::getListRules(),
                ['class' => 'form-control', 'id' => 'client_type', 'placeholder' => 'Тип клієнта']);

            echo $form->field($model, 'text')->textarea(['class' => 'form-control', 'rows' => '7', 'placeholder' => 'Повідомлення']);

            echo $form->field($model, 'captchaUser')->widget(\yii\captcha\Captcha::className(), [
                'captchaAction' => '/admin/feedback/captcha',
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
            ]);

            echo '<div class="button-order-container col-md-12 col-xs-12">';
            echo Html::submitButton('Замовити', ['class' => 'button-order']);
            echo '</div><div class="clearfix"></div>';

        } catch (Exception $e) {
            print_r($e->getMessage());
        }
        
        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_save($data)
    {
        $model = new FeedbackModel($data);
        if($model->save()){
            return ['result' => 'success'];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}