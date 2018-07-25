<?php

namespace app\components;

/**
 * Class AppMsg
 * @package app\components
 */
class AppMsg
{
    /**
     * @param       $message
     * @param array $params
     * @param null $language
     *
     * @return string
     */
    public static function t($message, $params = [], $language = null)
    {
        return \Yii::t('app', $message, $params, $language);
    }
}
