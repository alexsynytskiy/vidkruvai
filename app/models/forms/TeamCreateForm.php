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
     * @var integer
     */
    public $id;
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
            [['captchaTeam', 'name'], 'required'],
            ['emails', 'each', 'rule' => ['email']],
            ['emails', 'checkcount'],
            ['captchaTeam', 'captcha', 'captchaAction' => '/team/captcha'],
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
    public function getTeam()
    {
        return $this->_team;
    }

    /**
     * @param Team $team
     */
    public function setTeam($team)
    {
        $this->_team = $team;
        $this->_teamMembers = $team->teamUsers;

        $this->id = $team->id;
        $this->avatar = $team->avatar;
        $this->name = $team->name;

        foreach ($this->_teamMembers as $member) {
            if ($member->role !== DefTeamSiteUser::ROLE_CAPTAIN) {
                $this->emails[] = $member->email;
            }

        }
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

    /**
     * Updates team
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function updateTeam()
    {
        $team = Team::findOne(['id' => $this->id]);

        if ($team) {
            $team->name = $this->name;
            $team->avatar = $this->avatar;
            $team->status = DefTeam::STATUS_UNCONFIRMED;

            $this->_team = $team;

            if ($this->validate() && $team->validate()) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($team->update()) {
                        $oldTeamMembers = TeamSiteUser::find()
                            ->where(['team_id' => $team->id, 'role' => DefTeamSiteUser::ROLE_MEMBER])
                            ->all();

                        /** @var TeamSiteUser $oldTeamMember */
                        foreach ($oldTeamMembers as $oldTeamMember) {
                            if (!in_array($oldTeamMember->email, $this->emails, false)) {
                                $oldTeamMember->status = DefTeamSiteUser::STATUS_REMOVED;
                                $oldTeamMember->update();
                            }
                        }

                        foreach ($this->emails as $email) {
                            if (!empty($email)) {
                                $teamMember = TeamSiteUser::find()
                                    ->where(['email' => $email, 'team_id' => $team->id])
                                    ->andWhere(['in', 'status',
                                        [DefTeamSiteUser::STATUS_CONFIRMED, DefTeamSiteUser::STATUS_UNCONFIRMED]])
                                    ->exists();

                                if (!$teamMember) {
                                    $teamMember = new TeamSiteUser;
                                    $teamMember->team_id = $team->id;
                                    $teamMember->email = $email;
                                    $teamMember->role = DefTeamSiteUser::ROLE_MEMBER;
                                    $teamMember->status = DefTeamSiteUser::STATUS_UNCONFIRMED;

                                    if (!$teamMember->save()) {
                                        $this->addErrors($teamMember->getErrors());
                                    }

                                    $this->_teamMembers[] = $teamMember;
                                }
                            }
                        }
                    }

                    $transaction->commit();
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                }

                return true;
            }

            $this->addErrors($this->_team->getErrors());
        }

        return false;
    }
}
