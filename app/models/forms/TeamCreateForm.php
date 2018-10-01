<?php

namespace app\models\forms;

use app\models\Team;
use yii\base\Model;

/**
 * Login form
 */
class TeamCreateForm extends Model
{
    /**
     * @var array
     */
    public $emails;
    /**
     * @var string
     */
    public $avatar;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $captchaTeam;
    /**
     * @var Team
     */
    private $_team;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emails', 'avatar', 'captchaTeam', 'name'], 'required'],
            ['captchaTeam', 'captcha', 'captchaAction' => '/profile/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'emails' => 'Учасники',
            'avatar' => 'Зображення',
            'name' => 'Назва',
            'captchaTeam' => 'Капча',
        ];
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->_team;
    }

    /**
     * Creates team with users for accepting invitation
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function createTeam()
    {
        $team = new Team;


        $this->_team = $team;

        if ($this->validate() && $team->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $team->save(false);

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }

            return true;
        }

        $this->addErrors($this->_team->getErrors());

        return false;
    }
}