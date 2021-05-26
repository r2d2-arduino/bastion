<?php
$this->title = 'My Bastion';

use app\models\Sensor;
use app\models\SensorValue;
use app\models\Home;
use app\models\Position;
use app\models\Device;
use app\models\Connection;

$this->registerJsFile('@web/js/speedometer.js', ['depends' => [\yii\web\JqueryAsset::class]]);

/* @var $this yii\web\View */
$hCnt = Home::find()->where(['user_id' => Yii::$app->user->id])->count();
$pCnt = Position::find()->where(['user_id' => Yii::$app->user->id])->count();
$dCnt = Device::find()->where(['user_id' => Yii::$app->user->id])->count();
$sCnt = Sensor::find()->where(['user_id' => Yii::$app->user->id])->count();
$cCnt = Connection::find()->count();

//$maxIds = SensorValue::find()->select(['MAX(id) as id'])->groupBy(['sensor_id'])->column();
$maxIds = [1,2,3,4];
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to Bastion!</h1>

        <p class="lead">Homes: <?=$hCnt;?> &nbsp; Positions: <?=$pCnt;?> &nbsp; Devices: <?=$dCnt;?> &nbsp; Sensors: <?=$sCnt;?></p>
    </div>

    <div class="body-content">
        <div class="row">        
            <?php foreach ($maxIds as $svid): 
                $sensorValue = SensorValue::find()->where(['id' => (int) $svid])->one(); ?>
                <?= $this->render('//layouts/_speedometer', ['value' => $sensorValue->value, 'sensor_id' => $sensorValue->sensor_id]); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
setTimeout(function(){ 
    getLastSensorsValue();
}, 3000);
</script>