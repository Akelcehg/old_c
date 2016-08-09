<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->registerJs('setTimeout(function() { window.location=\'/admin/users/index\';}, 5000);');

?>


<div id="content">

    Такого пользователя не существует ...
    <i> перенаправление на список пользователей через 5 ть секунд </i>

</div>
