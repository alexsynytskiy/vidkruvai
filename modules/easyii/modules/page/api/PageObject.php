<?php

namespace yii\easyii\modules\page\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Html;
use yii\helpers\Url;
use \yii\easyii\components\ApiObject;

/**
 * Class PageObject
 * @package yii\easyii\modules\page\api
 *
 * @property string $slug
 * @property \yii\easyii\modules\page\models\Page $model
 *
 * @property string $title
 * @property string $text
 * @property string $editLink
 * @property string $createLink
 */
class PageObject extends ApiObject
{
    /**
     * @var string
     */
    public $slug;

    /**
     * @return string
     */
    public function getTitle(){
        if($this->model->isNewRecord){
            return $this->createLink;
        } else {
            return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
        }
    }

    /**
     * @return string
     */
    public function getText(){
        if($this->model->isNewRecord){
            return $this->createLink;
        } else {
            return $this->model->text;
        }
    }

    /**
     * @return string
     */
    public function getEditLink(){
        return Url::to(['/admin/page/a/edit/', 'id' => $this->id]);
    }

    /**
     * @return string
     */
    public function getCreateLink(){
        return Html::a(Yii::t('easyii/page/api', 'Create page'), ['/admin/page/a/create', 'slug' => $this->slug], ['target' => '_blank']);
    }
}