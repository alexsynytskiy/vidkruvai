<?php

namespace app\models\forms;

use app\models\Team;
use app\models\TeamSiteUser;
use yii\base\Model;
use yii\helpers\ArrayHelper;

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
     * @var TeamSiteUser[]
     */
    private $_teamMembers;
    /**
     * @var bool
     */
    public $isNewRecord;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emails', 'avatar', 'captchaTeam', 'name'], 'required'],
            ['emails', 'each', 'rule' => ['email']],
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
     * @return TeamSiteUser[]
     */
    public function getMembers()
    {
        return $this->_teamMembers;
    }

    /**
     * @return Team
     */
    public function getTeamUsers()
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
        $team->name = $this->name;
        $team->avatar = $this->avatar;
        $team->status = Team::STATUS_UNCONFIRMED;
        $team->level_id = 1;

        $this->_team = $team;

        if ($this->validate() && $team->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if($team->save(false)) {
                    foreach ($this->emails as $email) {
                        $teamMember = new TeamSiteUser;
                        $teamMember->team_id = $team->id;
                        $teamMember->email = $email;
                        $teamMember->status = TeamSiteUser::STATUS_UNCONFIRMED;

                        if(!$team->save()) {
                            $this->addErrors($teamMember->getErrors());
                        }

                        $this->_teamMembers = ArrayHelper::merge($this->_teamMembers, [$teamMember]);
                    }
                }

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
