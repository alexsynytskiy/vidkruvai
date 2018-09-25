<?php

namespace app\components\events;

use yii\base\Event;

/**
 * Class AwardEvent
 * @package app\components\events
 */
class AwardEvent extends Event
{
    /**
     * Achievement ID or Level ID
     *
     * @var int
     */
    public $objectId = null;
    /**
     * Name of the class the event triggered from
     *
     * @var string
     */
    public $senderClassName = null;
    /**
     * @var int
     */
    public $userId = null;
}
