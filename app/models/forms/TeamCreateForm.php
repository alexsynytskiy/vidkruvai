<?php

namespace app\models\forms;

use app\components\AppMsg;
use app\models\definitions\DefTeam;
use app\models\definitions\DefTeamSiteUser;
use app\models\Team;
use app\models\TeamSiteUser;
use yii\base\Model;

/**
 * Class TeamCreateForm
 * @package app\models\forms
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
            [['avatar', 'captchaTeam', 'name'], 'required'],
            ['emails', 'each', 'rule' => ['email']],
            ['emails', 'checkcount'],
            ['captchaTeam', 'captcha', 'captchaAction' => '/profile/captcha'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkcount($attribute, $params)
    {
        if (count($this->emails) < 2) {
            $this->addError('emails', AppMsg::t('Кількість учасників має бути більша за 2'));
        }

        if (count($this->emails) > 10) {
            $this->addError('emails', AppMsg::t('Кількість учасників має бути не більша за 10'));
        }
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
        $team->status = DefTeam::STATUS_UNCONFIRMED;
        $team->level_id = 1;

        $this->_team = $team;

        if ($this->validate() && $team->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if ($team->save()) {
                    foreach ($this->emails as $email) {
                        if (!empty($email)) {
                            $teamMember = new TeamSiteUser;
                            $teamMember->team_id = $team->id;
                            $teamMember->email = $email;
                            $teamMember->role = DefTeamSiteUser::ROLE_MEMBER;
                            $teamMember->status = DefTeamSiteUser::STATUS_UNCONFIRMED;

                            if ($teamMember->email === \Yii::$app->siteUser->identity->email) {
                                $teamMember->site_user_id = \Yii::$app->siteUser->identity->id;
                                $teamMember->role = DefTeamSiteUser::ROLE_CAPTAIN;
                                $teamMember->status = DefTeamSiteUser::STATUS_CONFIRMED;
                            }

                            if (!$teamMember->save()) {
                                $this->addErrors($teamMember->getErrors());
                            }

                            $this->_teamMembers[] = $teamMember;
                        }
                    }

                    //$team->mailAdmin();
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
