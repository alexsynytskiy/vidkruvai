<?php

namespace app\components;

/**
 * Class MassAchievement
 * @package app\components
 */
class MassAchievement
{
    /**
     * @var int
     */
    protected $entityId;
    /**
     * @var string
     */
    protected $entityType;
    /**
     * @var array
     */
    protected $rules = [];
    /**
     * @var int
     */
    protected $transactionId;

    /**
     * MassAchievement constructor.
     *
     * @param int $entityId
     * @param string $entityType
     */
    public function __construct($entityId, $entityType)
    {
        $this->entityId = $entityId;
        $this->entityType = $entityType;
    }

    /**
     * @param       $ruleName
     * @param array $params
     *
     * @return $this
     */
    public function add($ruleName, $params = [])
    {
        $this->rules[$ruleName] = $params;

        return $this;
    }

    /**
     * @param int $transactionId
     *
     * @return $this
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return void
     */
    public function checkAchievements()
    {
        foreach ($this->rules as $ruleName => $params) {
            try {
                if (AchievementComponent::isGoalAchieved($ruleName, $this->entityId, $this->entityType, $params)) {
                    AchievementComponent::achieveByUser($ruleName, $this->entityId, $this->entityType);
                }
            } catch (\Exception $e) {

            }
        }
    }
}
