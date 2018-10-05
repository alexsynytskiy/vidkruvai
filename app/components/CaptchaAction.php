<?php

namespace app\components;

/**
 * Class CaptchaAction
 * @package app\components
 */
class CaptchaAction extends \yii\captcha\CaptchaAction
{
    /**
     * Overriding method, because it returned Unique ID with Module ID
     * and that breaks JS validation
     *
     * @return string
     */
    public function getUniqueId() {
        return $this->controller->id . '/' . $this->id;
    }
}
