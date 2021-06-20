<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Bastion - это проект умного дома, внешнего и внутреннего сервера, написанного на Yii2. 
        Позволяет подключать и мониторить датчики, а также управлять дистанционно устройствами.
    </p>
</div>
