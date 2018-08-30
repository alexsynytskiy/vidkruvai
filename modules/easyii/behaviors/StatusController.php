<?php

namespace yii\easyii\behaviors;

use Yii;
use yii\helpers\VarDumper;

/**
 * Status behavior. Adds statuses to models
 * @package yii\easyii\behaviors
 */
class StatusController extends \yii\base\Behavior
{
    public $model;

    /**
     * @param int $id
     * @param string $status
     *
     * @return mixed
     */
    public function changeStatus($id, $status)
    {
        $modelClass = $this->model;

        if (($model = $modelClass::findOne($id))) {
            /** SiteUser $model */
            $model->status = $status;

            if(!$model->update()) {
                $this->owner->formatResponse(Yii::t('easyii', 'Not updated with error: ' .
                    VarDumper::export($model->getErrors())));
            }
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->owner->formatResponse(Yii::t('easyii', 'Status successfully changed'));
    }
}
