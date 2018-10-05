<?php

namespace app\components;

use yii\base\Object;
use yii\rest\Serializer;

/**
 * Class TrailingSlashHelper
 * @package app\components
 */
class TrailingSlashHelper extends Object
{
    /**
     * @throws \yii\base\ExitException
     */
    public function redirectSlash()
    {
        $app = \Yii::$app;
        $pathInfo = $app->request->pathInfo;

        $getParam = $app->request->get('parent');
        preg_match('/[^\/]+$/', $pathInfo, $matches);

        $startRedirect = ['admin', 'site', 'profile', 'team'];
        $stopRedirect = ['items', 'edit', 'photos', 'settings', 'index',
            'list', 'redactor', 'all', 'account', 'news-item'];

        $redirect = false;

        if (empty($getParam) && (isset($matches[0]) && !is_numeric($matches[0]))) {
            foreach ($startRedirect as $startItem) {
                if (strpos($pathInfo, $startItem) !== false) {
                    $redirect = true;
                    break;
                }
            }

            foreach ($stopRedirect as $stopItem) {
                if (strpos($pathInfo, $stopItem) !== false) {
                    $redirect = false;
                    break;
                }
            }
        }

        if (!$app->request->post() && $redirect && !empty($pathInfo) && substr($pathInfo, -1) !== '/') {
            $app->response->redirect('/' . rtrim($pathInfo) . '/', 301);
            $app->end();
        }
    }
}
