<?php

namespace app\models\definitions;

use app\components\BaseDefinition;

/**
 * Class DefEntityAchievement
 * @package app\models\definitions
 */
class DefEntityAchievement extends BaseDefinition
{
    const IS_DONE = 1;
    const IS_IN_PROGRESS = 0;

    const ENTITY_USER = 'user';
    const ENTITY_TEAM = 'team';
}
