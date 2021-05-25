<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\DeviceSensor;
use app\models\Sensor;
use app\models\SensorValue;

/* @var $this yii\web\View */
/* @var $model app\models\Device */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$devSensors = DeviceSensor::find()->where(['device_id' => $model->id])->all();
$sensors = [];
foreach ($devSensors as $devsen)
{
    $sensors[] = Sensor::find()->where(['id' => $devsen->sensor_id])->one();
}
?>
<div class="device-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'position_id',
            'channel',
            'connection_id',
        ],
    ]) ?>

</div>
<div class="body-content">
        <div class="row">
            <?php foreach ($sensors as $sensor): ?>
            <div class="col-md-3 text-center" >
                <?php
                $sensorValId = SensorValue::find()->where(['sensor_id' => $sensor->id])->max('id');
                $sensorValue  = SensorValue::find()->where(['id' => $sensorValId])->one();
                ?>
                <?= $this->render('//layouts/_speedometer', ['model' => $sensorValue, 'sensor' => $sensor]) ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>