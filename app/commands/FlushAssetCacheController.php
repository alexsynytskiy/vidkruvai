<?php

namespace app\commands;

use app\models\City;
use yii\console\Controller;

/**
 * Class FlushAssetCacheController
 * @package app\commands
 */
class FlushAssetCacheController extends Controller
{
    protected $paths = [
        'media/',
        'vendor/noumo/easyii/media/',
    ];

    /**
     * Change time of the modification of the directories
     * It forces AssetManager to republish directories
     */
    public function actionIndex()
    {
        $date = date('YmdHi.s');

        foreach ($this->paths as $path) {
            exec("touch -t {$date} {$path}");
        }
    }

    /**
     * @return void
     */
    public function actionTranslations()
    {
        $acpJsTranslations = require(__DIR__ . '/../modules/messages/JsTranslations.php');

        $acpJsFile = __DIR__ . '/../media/js/js-translations.js';

        $acpJsVar = 'var Translations = ';

        file_put_contents($acpJsFile, $acpJsVar . json_encode([
                'uk' => $acpJsTranslations,
                'en' => $acpJsTranslations,
            ]) . ';');
    }

    public function actionGetCoords()
    {
        /** @var City[] $cities */
        $cities = City::find()->where(['id' => [
            3,
            5,
            6,
            7,
            13,
            16,
            19,
            22,
            24,
            25,
            26,
            28,
            30,
            37,
            40,
            42,
            44,
            45,
            48,
            49,
            50,
            54,
            55,
            56,
            58,
            59,
            62,
            66,
            68,
            70,
            72,
            74,
            78,
            80,
            83,
            84,
            86,
            87,
            88,
            90,
            91,
            93,
            96,
            97,
            102,
            103,
            105,
            106,
            108,
            112,
            115,
            116,
            118,
            125,
            126,
            129,
            130,
            132,
            135,
            136,
            137,
            139,
            140,
            141,
            144,
            151,
            153,
            154,
            157,
            160,
            165,
            169,
            179,
            182,
            187,
            190,
            197,
            204,
            205,
            207,
            210,
            212,
            215,
            217,
            222,
            225,
            226,
            227,
            229,
            230,
            234,
            235,
            237,
            244,
            255,
            256,
            265,
            267,
            268,
            272,
            273,
            280,
            281,
            283,
            284,
            285,
            286,
            292,
            293,
            300,
            301,
            303,
            304,
            306,
            310,
            311,
            313,
            315,
            316,
            317,
            318,
            320,
            321,
            322,
            326,
            328,
            329,
            333,
            337,
            340,
            341,
            345,
            356,
            358,
            370,
            392,
            397,
            402,
            409,
            422,
            423,
            432,
            441,
            453,
            457,
            467,
            470,
            472,
            473,
            474,
            475,
            484,
            491,
            492,
            495,
            498,
            501,
            504,
            513,
            514,
            520,
            524,
            526,
            534,
            535,
            536,
            548,
            555,
            558,
            562,
            565,
            583,
            589,
            600,
            608,
            612,
            616,
            621,
            623,
            625,
            626,
            630,
            632,
            634,
            643,
            644,
            645,
            646,
            648,
            656,
            657,
            673,
            689,
            709,
            711,
            718,
            721,
            724,
            728,
            729,
            731,
            732,
            750,
            761,
            762,
            763,
            764,
            768,
            770,
            771,
            773,
            775,
            777,
            780,
            782,
            787,
            789,
            795,
            796,
            798,
            817,
            818,
            824,
            829,
            835
        ]])->all();

        foreach ($cities as $city) {
            if (!$city->latitude) {
                $address = $city->city . '+' . $city->state->name . '+область+Україна';

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDpVcxjCQWKJb952npbOD5hGSo8qyJ5UTE&address=$address&sensor=false",
                    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
                ]);
                $data = curl_exec($curl);
                curl_close($curl);

                $data = json_decode($data, true);

                $city->latitude = $data["results"][0]["geometry"]["location"]["lat"];
                $city->longitude = $data["results"][0]["geometry"]["location"]["lng"];
                $city->update(false);
            }
        }

        return 1;
    }
}