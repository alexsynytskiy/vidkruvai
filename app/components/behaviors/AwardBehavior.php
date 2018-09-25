<?php

namespace app\components\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class AwardBehavior
 * @package app\components\behaviors
 */
class AwardBehavior extends Behavior
{
    /**
     * The list of award IDs that should be attached to some entity
     *
     * @var array|null
     */
    public $awardIDs = null;
    /**
     * Table name where entities and theirs awards are stored
     *
     * @var null
     */
    public $junctionTable = null;
    /**
     * Attribute name that identifies field where entity ID is stored
     *
     * @var null
     */
    public $entityAttribute = null;
    /**
     * Attribute isCase that identifies is object case
     *
     * @var null
     */
    public $isCase = false;
    /**
     * Attribute hasSavedAward that identifies are all object awards selected correct
     *
     * @var null
     */
    public $hasSavedAward = false;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'saveAwards',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveAwards',
        ];
    }

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        if (!$this->junctionTable) {
            throw new \Exception('The junction table must be specified.');
        }

        if (!$this->entityAttribute) {
            throw new \Exception('The entity attribute name must be specified.');
        }
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function saveAwards()
    {
        $entityId = $this->owner->id;
        $batchInsert = [];

        if ($this->awardIDs === null) {
            return false;
        }

        foreach ((array)$this->awardIDs as $awardID) {
            if ($awardID) {
                $batchInsert[] = [$entityId, $awardID];
            }
        }

        \Yii::$app->db->createCommand()
            ->delete($this->junctionTable, [$this->entityAttribute => $entityId])
            ->execute();

        if ($batchInsert) {
            \Yii::$app->db->createCommand()
                ->batchInsert($this->junctionTable, [$this->entityAttribute, 'award_id'], $batchInsert)
                ->execute();
        }

        $this->hasSavedAward = true;
        return true;
    }
}
