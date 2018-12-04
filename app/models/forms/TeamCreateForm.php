<?php

namespace app\models\forms;

use app\components\AppMsg;
use app\models\CreateTeamLogs;
use app\models\definitions\DefTeam;
use app\models\definitions\DefTeamSiteUser;
use app\models\Team;
use app\models\TeamSiteUser;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

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
     * @param array $errors
     *
     * @return bool|array
     * @throws \yii\db\Exception
     */
    public function createTeam(&$errors)
    {
        $team = new Team;
        $team->name = $this->name;
        $team->avatar = $this->avatar;
        $team->status = DefTeam::STATUS_UNCONFIRMED;
        $team->level_id = 1;

        $this->_team = $team;

        CreateTeamLogs::saveLog('create', 'Team params: ' . VarDumper::export($this->attributes));

        if ($this->validate() && $this->_team->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if ($this->_team->save()) {
                    foreach ($this->emails as $email) {
                        if (!empty($email)) {
                            /** @var TeamSiteUser $teamMember */
                            $teamMember = TeamSiteUser::find()
                                ->where(['email' => $email, 'team_id' => $this->_team->id])
                                ->one();

                            if (!$teamMember) {
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

                                if (!$teamMember->validate() || !$teamMember->save()) {
                                    $errors[] = $teamMember->getErrors();

                                    $this->_team->addErrors($teamMember->getErrors());
                                }

                                $this->_teamMembers[] = $teamMember;
                            } else {
                                $teamMemberOtherTeam = TeamSiteUser::find()
                                    ->where(['email' => $email, 'status' => DefTeamSiteUser::STATUS_CONFIRMED])
                                    ->andWhere(['!=', 'team_id', $this->_team->id])
                                    ->one();

                                if ($teamMemberOtherTeam) {
                                    $errors[] = AppMsg::t("Користувач {$email} вже в іншій команді!");
                                }
                            }
                        }
                    }

                    $this->_team->mailAdmin('created');
                }
                else {
                    $this->addErrors($this->_team->getErrors());
                    $errors[] = $this->_team->getErrors();
                }
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();

                $transaction->rollBack();
            }

            if(empty($errors)) {
                $transaction->commit();

                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Updates team
     *
     * @param array $errors
     *
     * @return array|bool
     * @throws \yii\db\Exception
     */
    public function updateTeam(&$errors)
    {
        $team = Team::findOne(['id' => $this->id]);

        if ($team) {
            CreateTeamLogs::saveLog('update',
                'Team new params: ' . VarDumper::export($this->attributes),
                'Team old params: ' . VarDumper::export($team->attributes) .
                    'Team old members: ' . VarDumper::export($team->getTeamUsersEmails()));

            $team->name = $this->name;
            $team->avatar = $this->avatar;

            $this->_team = $team;

            if ($this->validate() && $this->_team->validate()) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    $this->_team->update();

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
                                ->one();

                            if (!$teamMember) {
                                $teamMember = new TeamSiteUser;
                                $teamMember->team_id = $this->_team->id;
                                $teamMember->email = $email;
                                $teamMember->role = DefTeamSiteUser::ROLE_MEMBER;
                                $teamMember->status = DefTeamSiteUser::STATUS_UNCONFIRMED;

                                if (!$teamMember->validate() || !$teamMember->save()) {
                                    $errors[] = $teamMember->getErrors();

                                    $this->_team->addErrors($teamMember->getErrors());
                                }

                                $this->_teamMembers[] = $teamMember;
                            }
                            else {
                                $teamMemberOtherTeam = TeamSiteUser::find()
                                    ->where(['email' => $email, 'status' => DefTeamSiteUser::STATUS_CONFIRMED])
                                    ->andWhere(['!=', 'team_id',$this->_team->id])
                                    ->one();

                                if($teamMemberOtherTeam) {
                                    $errors[] = AppMsg::t("Користувач {$email} вже в іншій команді!");
                                }
                            }
                        }
                    }

                    if (count($this->_team->teamUsers) < 7) {
                        $this->_team->status = DefTeam::STATUS_UNCONFIRMED;
                    }

                    $this->_team->mailAdmin('updated');
                } catch (\Throwable $e) {
                    $errors[] = $e->getMessage();

                    $transaction->rollBack();
                }

                if(empty($errors)) {
                    $transaction->commit();

                    return true;
                }

                return false;
            }

            return false;
        }

        $errors[] = AppMsg::t('Команду не знайдено!');

        return false;
    }

    /**
     * @param array $errors
     * @return array
     */
    public function getErrorsSimple($errors) {
        $simpleArrayErrors = [];

        foreach ($errors as $error) {
            if(is_array($error)) {
                $simpleArrayErrors = ArrayHelper::merge($simpleArrayErrors, $this->getErrorsSimple($error));
            }
            else {
                $simpleArrayErrors[] = $error;
            }
        }

        return array_unique($simpleArrayErrors);
    }
}
