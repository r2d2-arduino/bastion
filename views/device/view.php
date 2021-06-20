<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\DeviceSensor;
use app\models\Sensor;
use app\models\SensorStat;

$this->registerJsFile('@web/js/speedometer.js', ['depends' => [\yii\web\JqueryAsset::class]]);
/* @var $this yii\web\View */
/* @var $model app\models\Device */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$devSensors = DeviceSensor::find()->select('sensor_id')->where(['device_id' => $model->id])->column();
$sensors = Sensor::find()->where(['in', 'id', $devSensors])->all();
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
<label for="checkUpdate"><input type="checkbox" id="checkUpdate" value="1" />Update sensors</label>
<div class="body-content">
    <div class="row">
        <?php foreach ($sensors as $sensor): 
            $sensorStat = SensorStat::find()->where(['sensor_id' => $sensor->id])->one(); ?>
            <?php if ($sensorStat)
            {
                echo $this->render('//layouts/_speedometer', ['value' => $sensorStat->getLastValue(), 'sensor' => $sensor, 'device_id' => $sensorStat->device_id]);
            } ?>
        <?php endforeach; ?>
    </div>
</div>
<script>
setInterval(function(){ 
    getLastSensorsValue(<?=$model->id; ?>);
}, 5000);
</script>