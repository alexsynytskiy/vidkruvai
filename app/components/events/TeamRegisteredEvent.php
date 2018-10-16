<?php

namespace app\components\events;

use yii\base\Event;

/**
 * Class TeamRegisteredEvent
 * @package app\components\events
 */
class TeamRegisteredEvent extends Event
{
    /**
     * @var null|int
     */
    public $teamId = null;
}
