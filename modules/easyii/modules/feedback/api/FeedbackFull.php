<?php
namespace yii\easyii\modules\feedback\api;

use Yii;
use yii\easyii\helpers\TypeHelper;
use yii\easyii\modules\feedback\models\Feedback as FeedbackModel;

use yii\easyii\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\ReCaptcha;

/**
 * FeedbackFull module API
 * @package yii\easyii\modules\feedback\api
 *
 * @method static string form(array $options = []) Returns fully worked standalone html form.
 * @method static array save(array $attributes) If you using your own form, this function will be useful for manual saving feedback's.
 */

class FeedbackFull extends \yii\easyii\components\API
{
    const SENT_VAR = 'feedback_sent';

    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_form($options = [])
    {
        $model    = new FeedbackModel;
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;
        $options  = array_merge($this->_defaultFormOptions, $options);

        ob_start();
        $form = ActiveForm::begin([
            'action' => Url::to(['/admin/feedback/send']),
            'options' => [
                'class'   => 'col-md-12 contact-form contact-form-full',
                'enctype' => 'multipart/form-data'
            ],
            'fieldConfig' => [
                'template' => "<div class=\"form-group\">{input}</div>",
                'options' => [
                    'tag' => false,
                ],
            ],
        ]);

            echo '<div class="form-title">Заповніть форму для замовлення</div>';
            echo '<div class="form-sub-title">Ми зв’яжемось з вами найближчим часом</div>';

            echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current([self::SENT_VAR => 0]));
            echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current([self::SENT_VAR => 1]));

            echo '<div class="col-md-6 col-xs-12">';

                echo $form->field($model, 'name')->textInput(['class' => 'form-control', 'id' => 'first-name', 'placeholder' => 'Ім\'я']);

                echo $form->field($model, 'phone')->textInput(['class' => 'form-control', 'id' => 'last-name', 'placeholder' => 'Телефон']);

                echo $form->field($model, 'email')->input('email', ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'E-mail']);

                echo $form->field($model, 'client_type')->dropDownList(TypeHelper::getListRules(),
                    ['class' => 'form-control', 'id' => 'client_type', 'placeholder' => 'Тип клієнта']);

                echo $form->field($model, 'order_type')->dropDownList(TypeHelper::getListOrders(),
                    ['class' => 'form-control', 'id' => 'order_type', 'placeholder' => 'Тип замовлення']);

                echo $form->field($model, 'date')->textInput(['class' => 'form-control', 'id' => 'date', 'placeholder' => 'Дата (завершення події/проекту)']);

                echo $form->field($model, 'place')->textInput(['class' => 'form-control', 'id' => 'place', 'placeholder' => 'Місце події/проекту']);


            echo '</div><div class="col-md-6 col-xs-12">';

                echo $form->field($model, 'text')->textarea(['class' => 'form-control', 'rows' => '7', 'placeholder' => 'Повідомлення']);

                if($settings['enableCaptcha']) echo $form->field($model, 'reCaptcha')->widget(ReCaptcha::className());

            echo '</div><div class="clearfix"></div>';

            echo '<div class="button-order-container col-md-12 col-xs-12">';
                echo Html::submitButton('Надіслати', ['class' => 'button-order']);
            echo '</div><div class="clearfix"></div>';
        
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