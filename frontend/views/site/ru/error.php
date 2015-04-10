<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Данная ошибка возникла вследствии того, что сервер не смог обработать Ваш запрос.
    </p>
    <p>
        Если Вы считаете это ошибкой. Пожалуйста, сообщите нам.
    </p>

</div>
