<?php
$this->title = 'My Bastion';

use app\models\Sensor;
use app\models\SensorValue;
use app\models\Home;
use app\models\Position;
use app\models\Device;


$this->registerJsFile('@web/js/speedometer.js', ['depends' => [\yii\web\JqueryAsset::class]]);

/* @var $this yii\web\View */
$homes = Home::find()->select(['id', 'name'])->where(['user_id' => Yii::$app->user->id])->all();
$positions = Position::find()->select(['id', 'name'])->where(['user_id' => Yii::$app->user->id])->all();
$devices = Device::find()->select(['id', 'name'])->where(['user_id' => Yii::$app->user->id])->all();
$sensors = Sensor::find()->where(['user_id' => Yii::$app->user->id])->all();

?>
<div class="site-index">
    
    <!-- ul class="nav nav-pills">
        <li class="nav-item">
            <div style="padding: 10px 15px">Home:</div>
        </li>
        <?php foreach ($homes as $home): 
            $pCnt = Position::find()->where(['home_id' => $home->id])->count();
            if ($pCnt > 0): ?>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#"><?=$home->name.' ('.$pCnt.')';?></a>
        </li>
        <?php 
        endif;
        endforeach; ?>
    </ul>
    
    <ul class="nav nav-pills">
        <li class="nav-item">
            <div style="padding: 10px 15px">Position:</div>
        </li>
        <?php foreach ($positions as $position): 
            $dCnt = Device::find()->where(['position_id' => $position->id])->count();
            if ($dCnt > 0): ?>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#"><?=$position->name.' ('.$dCnt.')';?></a>
        </li>
        <?php 
        endif;
        endforeach; ?>
    </ul>
    
    <ul class="nav nav-pills">
        <li class="nav-item">
            <div style="padding: 10px 15px">Device:</div>
        </li>
        <?php foreach ($devices as $device): ?>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#"><?=$device->name;?></a>
        </li>
        <?php endforeach; ?>
    </ul -->
    
    <div class="jumbotron">
        <h1>Welcome to Bastion!</h1>
        <p class="lead"></p>
    </div>

    <div class="body-content">
        <div class="row">        
            <?php foreach ($sensors as $sensor): ?>
                <?= $this->render('//layouts/_speedometer', ['value' => $sensor->min_rate, 'sensor' => $sensor]); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
window.onload = function () 
{
    setTimeout(function(){
        getLastSensorsValue();
    }, 500);
}
setInterval(function(){ 
    getLastSensorsValue();
}, 5000);
</script>