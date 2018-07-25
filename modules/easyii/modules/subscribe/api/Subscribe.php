<?php
namespace yii\easyii\modules\subscribe\api;

use Yii;
use yii\easyii\modules\subscribe\models\Subscriber;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/**
 * Subscribe module API
 * @package yii\easyii\modules\subscribe\api
 *
 * @method static string form(array $options = []) Returns fully working standalone html form.
 * @method static array save(array $attributes) If you are using your own form, this function will be useful for manual saving of subscribers.
 */

class Subscribe extends \yii\easyii\components\API
{
    const SENT_VAR = 'subscribe_sent';

    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_form($options = [])
    {
        $model = new Subscriber;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();

        $form = ActiveForm::begin([
            'action' => Url::to(['/admin/subscribe/send/']),
            'id' => '',
            'fieldConfig' => [
                'template' => "<div class=\"form-group\">{input}</div>",
                'options' => [
                    'tag' => false,
                ],
            ],
        ]);
        
        echo Html::hiddenInput('errorUrl', $options['errorUrl'] ? $options['errorUrl'] : Url::current([self::SENT_VAR => 0]));
        echo Html::hiddenInput('successUrl', $options['successUrl'] ? $options['successUrl'] : Url::current([self::SENT_VAR => 1]));

        echo '<label class="sr-only" for="exampleInputEmail1">Email address</label>';
        echo $form->field($model, 'email')->input('email', ['class' => 'form-control', 'id' => 'exampleInputEmail1', 'placeholder' => 'Залиште ваш е-mail']);
        
        echo Html::submitButton(Yii::t('easyii/subscribe/api', 'Підписатись'), ['class' => 'btn btn-default', 'id' => 'subscriber-send']);

        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_save($email)
    {
        $model = new Subscriber(['email' => $email]);
        if($model->save()){
            return ['result' => 'success', 'error' => false];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}