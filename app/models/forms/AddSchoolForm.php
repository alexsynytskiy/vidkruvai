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
            [['state_id', 'type_id', 'captchaUser'], 'required'],
            ['captchaUser', 'captcha', 'captchaAction' => '/validation/captcha'],
            [['school_number', 'school_name', 'city_name'], 'string'],
            [['school_number', 'school_name'], 'notEmptySchoolData'],
            [['city_id', 'city_name'], 'notEmptyCityData'],
            [['state_id', 'city_id', 'type_id', 'school_number', 'school_name'], 'uniqueSchool'],
        ];
    }

    public function notEmptySchoolData($attribute, $params, $validator)
    {
        if (!$this->school_number && !$this->school_name) {
            $this->addError($attribute, 'Школа має мати як мінімум номер або назву');
        }
    }

    public function notEmptyCityData($attribute, $params, $validator)
    {
        if (!$this->city_id && !$this->city_name) {
            $this->addError($attribute, 'Необхідно або вибрати місто зі списку, або додати нове');
        }
    }

    public function uniqueSchool($attribute, $params, $validator)
    {
        $schoolExistsQuery = School::find()
            ->alias('s')
            ->where([
                's.type_id' => $this->type_id,
                's.number' => $this->school_number,
                's.name' => $this->school_name,
            ]);

        if($this->city_id) {
            $schoolExistsQuery
                ->innerJoin(City::tableName() . ' c', 'c.id = s.city_id')
                ->innerJoin(State::tableName() . ' st', 'st.id = c.state_id')
                ->andWhere([
                    'c.state_id' => $this->state_id,
                ]);
        }

        if ($schoolExistsQuery ->exists()) {
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
        if(!$this->city_id && $this->city_name) {
            $city = new City();
            $city->city = $this->city_name;
            $city->state_id = $this->state_id;

            if(!$city->save()) {
                $this->addErrors($this->_city->getErrors());
                return false;
            }

            $this->city_id = $city->id;
        }

        $school = new School();
        $school->city_id = $this->city_id;
        $school->type_id = $this->type_id;
        $school->name = $this->school_name;
        $school->number = $this->school_number;

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