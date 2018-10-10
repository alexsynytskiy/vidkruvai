<?php

namespace app\models\forms;

use app\models\City;
use app\models\School;
use app\models\State;
use Yii;
use yii\base\Model;

/**
 * Class AddSchoolForm
 * @package app\models\forms
 */
class AddSchoolForm extends Model
{
    /**
     * @var int
     */
    public $state_id;
    /**
     * @var int
     */
    public $city_id;
    /**
     * @var string
     */
    public $city_name;
    /**
     * @var int
     */
    public $type_id;
    /**
     * @var string
     */
    public $school_number;
    /**
     * @var string
     */
    public $school_name;
    /**
     * @var string
     */
    public $captchaUser;
    /**
     * @var School
     */
    private $_school;
    /**
     * @var City
     */
    private $_city;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id', 'city_id', 'type_id'], 'required'],
            ['captchaUser', 'captcha', 'captchaAction' => '/validation/captcha'],
            [['school_number', 'school_name', 'city_name'], 'string'],
            [['school_number', 'school_name'], 'notEmptySchoolData'],
            [['state_id', 'city_id', 'type_id', 'school_number', 'school_name'], 'uniqueSchool'],
        ];
    }

    public function notEmptySchoolData($attribute, $params, $validator)
    {
        if (!$this->school_number && !$this->school_name) {
            $this->addError($attribute, 'Школа має мати як мінімум номер або назву');
        }
    }

    public function uniqueSchool($attribute, $params, $validator)
    {
        $schoolExists = School::find()
            ->alias('s')
            ->innerJoin(City::tableName() . ' c', 'c.id = s.city_id')
            ->innerJoin(State::tableName() . ' st', 'st.id = c.state_id')
            ->where([
                'city_id' => $this->city_id,
                'type_id' => $this->type_id,
                'number' => $this->school_number,
                'name' => $this->school_name,
            ])
            ->exists();

        if ($schoolExists) {
            $this->addError($attribute, "Така школа вже існує ({$this->school_number} {$this->school_name})");
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'state_id' => 'Область',
            'city_id' => 'Місто',
            'type_id' => 'Тип учбового закладу',
            'school_number' => '№ Школи',
            'school_name' => 'Назва школи',
            'city_name' => 'Назва міста',
            'captchaUser' => 'Капча',
        ];
    }

    /**
     * @return School
     */
    public function getSchool()
    {
        return $this->_school;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function add()
    {
        $school = new School();

        $this->_school = $school;

        if ($this->validate() && $school->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $school->save(false);

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }

            return true;
        }

        $this->addErrors($this->_school->getErrors());

        return false;
    }
}