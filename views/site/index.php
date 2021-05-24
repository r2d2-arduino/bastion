<?php
$this->title = 'My Bastion';

use app\models\Sensor;
use app\models\SensorValue;

/* @var $this yii\web\View */

$sensors = Sensor::find()->where(['user_id' => Yii::$app->user->id])->all();
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome!</h1>

        <p class="lead">The current sensor values are displayed here.</p>
    </div>

    <div class="body-content">
        <div class="row">
            <?php foreach ($sensors as $sensor): ?>
            <div class="col-lg-3" style="">
                <?php
                $sensorValId = SensorValue::find()->where(['sensor_id' => $sensor->id])->max('id');
                $sensorValue  = SensorValue::find()->where(['id' => $sensorValId])->one();
                ?>
                <?= $this->render('//layouts/_speedometer', ['model' => $sensorValue]) ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
setTimeout(function(){ 
    window.location.reload(); 
}, 5000);
</script>