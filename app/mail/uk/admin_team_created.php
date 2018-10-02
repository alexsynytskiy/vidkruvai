<?php

use yii\helpers\Html;

/** @var string $subject */
/** @var string $captainName */
/** @var \yii\helpers\Url $link */

$this->title = $subject;
?>

<p>Користувач <b><?= $captainName ?></b> створив нову команду!</p>
<p>Переглянути її ви можете за посиланням <?= Html::a('тут', $link) ?>.</p>
<hr>
<p>Це автоматичне повідомлення і на нього не треба відповідати.</p>