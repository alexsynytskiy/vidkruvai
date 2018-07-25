<?php

namespace yii\easyii\modules\page\api;

use Yii;
use yii\easyii\modules\page\models\Page as PageModel;
use yii\helpers\Html;

/**
 * Page module API
 * @package yii\easyii\modules\page\api
 *
 * @method static PageObject get(mixed $id_slug) Get page object by id or slug
 */


class Page extends \yii\easyii\components\API
{
    private $_pages = [];

    public function api_get($params)
    {
        if(!isset($this->_pages[$params[0]])) {
            $this->_pages[$params[0]] = $this->findPage($params);
        }
        return $this->_pages[$params[0]];
    }

    private function findPage($params)
    {
        $page = PageModel::find()->where(['or', 'page_id=:id_slug', 'slug=:id_slug'], [':id_slug' => $params[0]]);

        if(isset($params[1])) {
            $page->localized($params[1]);
        }

        $page = $page->one();

        return $page ? new PageObject($page) : $this->notFound($params[0]);
    }

    private function notFound($id_slug)
    {
        $page = new PageModel([
            'slug' => $id_slug
        ]);
        return new PageObject($page);
    }
}