<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div id="vhalf" style="margin-top: 100px">
    <div id="vcenter">
        <div id="splash-container">
            <div class="splash-content">
                <div class="splash-sphere"></div>
                <div class="splash-logo"></div>
                <div class="splash-soon">


                <h1 style="text-align: center">
                    <?php

                    echo $exception->statusCode. ' '. $exception->getMessage();

                    /*switch ($exception->statusCode) {
                        case "404":
                            echo " 404  страница не найдена";
                            break;
                        case "403":
                            echo "403 Доступ запрещен";
                            break;
                        default:
                            echo strip_tags($message);
                    }*/
                    ?>
                </h1>
                </div>
                <div class="splash-line"></div>
            </div>
        </div>
    </div>
</div>

<div id="main-bg"></div>