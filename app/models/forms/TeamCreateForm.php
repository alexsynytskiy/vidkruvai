<?php

namespace app\models\forms;

use app\components\AppMsg;
use app\models\definitions\DefTeam;
use app\models\definitions\DefTeamSiteUser;
use app\models\Team;
use app\models\TeamSiteUser;
use yii\base\Model;
use yii\helpers\ArrayHelper;

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
            ['captchaTeam', 'captcha', 'captchaAction' => '/validation/captcha'],
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

        if (count($this->emails) > 11) {
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
                $this->emails[$member->status][] = $member->email;
            }

        }

        $this->emails = $this->emails ?: [];
    }

    /**
     * Creates team with users for accepting invitation
     *
     * @return bool|array
     * @throws \yii\db\Exception
     */
    public function createTeam()
    {
        $team = new Team;
        $team->name = $this->name;
        $team->avatar = $this->avatar;
        $team->status = DefTeam::STATUS_UNCONFIRMED;
        $team->level_id = 1;

        $membersCreateErrors = $globalErrors = [];

        $this->_team = $team;

        if ($this->validate() && $this->_team->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if ($this->_team->save()) {
                    foreach ($this->emails as $email) {
                        if (!empty($email)) {
                            $teamMember = new TeamSiteUser;
                            $teamMember->team_id = $this->_team->id;
                            $teamMember->email = $email;
                            $teamMember->role = DefTeamSiteUser::ROLE_MEMBER;
                            $teamMember->status = DefTeamSiteUser::STATUS_UNCONFIRMED;

                            if ($teamMember->email === \Yii::$app->siteUser->identity->email) {
                                $teamMember->site_user_id = \Yii::$app->siteUser->identity->id;
                                $teamMember->role = DefTeamSiteUser::ROLE_CAPTAIN;
                                $teamMember->status = DefTeamSiteUser::STATUS_CONFIRMED;
                            }

                            if (!$teamMember->save()) {
                                $membersCreateErrors[] = $teamMember->getErrors();

                                $this->addErrors($teamMember->getErrors());
                            }

                            $this->_teamMembers[] = $teamMember;
                        }
                    }

                    $this->_team->mailAdmin('created');
                }
            } catch (\Exception $e) {
                $globalErrors[] = $e->getMessage();

                $transaction->rollBack();
            }

            if(empty($membersCreateErrors) && empty($globalErrors)) {
                $transaction->commit();

                return true;
            }

            return ArrayHelper::merge($membersCreateErrors, $globalErrors);
        }

        $this->addErrors($this->_team->getErrors());

        return [$this->_team->getErrors()];
    }

    /**
     * Updates team
     *
     * @return bool|array
     * @throws \yii\db\Exception
     */
    public function updateTeam()
    {
        $team = Team::findOne(['id' => $this->id]);

        if ($team) {
            $team->name = $this->name;
            $team->avatar = $this->avatar;

            $this->_team = $team;
            $membersCreateErrors = $globalErrors = [];

            if ($this->validate() && $this->_team->validate()) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($this->_team->update()) {
                        $oldTeamMembers = TeamSiteUser::find()
                            ->where(['team_id' => $this->_team->id, 'role' => DefTeamSiteUser::ROLE_MEMBER])
                            ->all();

                        /** @var TeamSiteUser $oldTeamMember */
                        foreach ($oldTeamMembers as $oldTeamMember) {
                            if (!in_array($oldTeamMember->email, $this->emails, false)) {
                                $oldTeamMember->delete();
                            }
                        }

                        foreach ($this->emails as $email) {
                            if (!empty($email)) {
                                $teamMember = TeamSiteUser::find()
                                    ->where(['email' => $email, 'team_id' => $this->_team->id])
                                    ->exists();

                                if (!$teamMember) {
                                    $teamMember = new TeamSiteUser;
                                    $teamMember->team_id = $this->_team->id;
                                    $teamMember->email = $email;
                                    $teamMember->role = DefTeamSiteUser::ROLE_MEMBER;
                                    $teamMember->status = DefTeamSiteUser::STATUS_UNCONFIRMED;

                                    if (!$teamMember->save()) {
                                        $membersCreateErrors[] = $teamMember->getErrors();

                                        $this->addErrors($teamMember->getErrors());
                                    }

                                    $this->_teamMembers[] = $teamMember;
                                }
                            }
                        }

                        if (count($this->_team->teamUsers) < 7) {
                            $this->_team->status = DefTeam::STATUS_UNCONFIRMED;
                        }

                        $this->_team->mailAdmin('updated');
                    }
                } catch (\Throwable $e) {
                    $globalErrors[] = $e->getMessage();

                    $transaction->rollBack();
                }

                if(empty($membersCreateErrors) && empty($globalErrors)) {
                    $transaction->commit();

                    return true;
                }

                return ArrayHelper::merge($membersCreateErrors, $globalErrors);
            }

            $this->addErrors($this->_team->getErrors());

            return [$this->_team->getErrors()];
        }

        return [AppMsg::t('Команду не знайдено!')];
    }
}
