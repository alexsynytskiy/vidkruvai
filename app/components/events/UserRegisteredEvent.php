<?php

namespace app\components\events;

use yii\base\Event;

/**
 * Class UserRegisteredEvent
 * @package app\components\events
 */
class UserRegisteredEvent extends Event
{
    /**
     * @var null|int
     */
    public $userId = null;
}
