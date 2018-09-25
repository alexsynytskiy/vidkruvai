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
    protected $userId;
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
     * @param $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
                if (AchievementComponent::isGoalAchieved($ruleName, $this->userId, $params)) {
                    AchievementComponent::achieveByUser($ruleName, $this->userId);
                }
            } catch (\Exception $e) {

            }
        }
    }
}
