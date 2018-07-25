<?php

namespace app\components;

use GuzzleHttp\Client;
use yii\base\Object;
use yii\helpers\Json;

/**
 * Class Formatter
 * @package app\components
 */
class GuzzleFacade extends Object
{
    /**
     * @param string $method
     * @param string $requestUrl
     * @param array  $requestOptions
     * @param array  $guzzleConfig
     *
     * @return array
     */
    public function request($method, $requestUrl, $requestOptions = [], $guzzleConfig = null) {
        $finalRequestOptions = array_merge($requestOptions, [
        ]);

        $client = ($guzzleConfig) ? new Client($guzzleConfig) : new Client();

        $response = [];

        try {
            $response = $client->request($method, $requestUrl, $finalRequestOptions);
            $response = Json::decode($response->getBody()->getContents());
        }
        catch(\Throwable $e) {
            $msg = $e->getMessage();

            if(!$response) {
                $response[] = $msg;
            } else {
                $response = array_merge($response, [$msg]);
            }

            \Yii::error("Ошибка при запросе GuzzleFacade::request().\nError: " . $e->getMessage());
        }

        return [
            'response'       => $response,
            'requestOptions' => $finalRequestOptions,
        ];
    }
}