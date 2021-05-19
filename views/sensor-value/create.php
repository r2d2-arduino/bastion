<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SensorValue */

$this->title = Yii::t('app', 'Create Sensor Value');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sensor Values'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sensor-value-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
