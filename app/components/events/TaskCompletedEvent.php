<?php

namespace app\components\events;

use yii\base\Event;

/**
 * Class TaskCompletedEvent
 * @package app\components\events
 */
class TaskCompletedEvent extends Event
{
    /**
     * @var null|int
     */
    public $teamId = null;

    /**
     * @var null|int
     */
    public $taskId = null;
}
