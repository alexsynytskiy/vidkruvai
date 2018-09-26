<?php

namespace app\modules\events;

use app\components\UserRegisteredEventHandler;
use app\controllers\ProfileController;

/**
 * Class Module
 * @package app\modules\events
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        \Yii::$app->on(ProfileController::EVENT_USER_REGISTERED, [new UserRegisteredEventHandler(), 'handle']);
    }
}
