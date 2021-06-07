<?php
$this->title = 'My Bastion';

use app\models\Sensor;
use app\models\SensorStat;
use app\models\Home;
use app\models\Position;
use app\models\Device;


$this->registerJsFile('@web/js/speedometer.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::class]]);

/* @var $this yii\web\View */
$homes = Home::find()->select(['id', 'name'])->where(['user_id' => Yii::$app->user->id])->all();
$positions = Position::find()->select(['id', 'name'])->where(['user_id' => Yii::$app->user->id])->all();
$devices = Device::find()->select(['id', 'name'])->where(['user_id' => Yii::$app->user->id])->all();
$sensors = Sensor::find()->where(['user_id' => Yii::$app->user->id])->all();
$types = ['Widgets', 'Sensors', 'Controllers'];
?>
<div class="site-index">
    
    <?php if (Yii::$app->user->isGuest): ?>
    <div class="jumbotron">
        <h1>Welcome to Bastion!</h1>
        <p class="lead"></p>
    </div>
    <?php else: ?>
    
    <div class="nav-header">
        <h1>Admin Panel</h1>
        <p class="lead"></p>
    </div>
    
    <ul class="nav nav-pills main-nav">
        <li class="nav-item nav-label">
            <span>Home:</span>
        </li>
        <li class="nav-item all active">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="home" data-id="0" onclick="return checkNav(this);">All homes</a>
        </li>        
        <?php foreach ($homes as $home): 
            $pCnt = Position::find()->where(['home_id' => $home->id])->count();
            if ($pCnt > 0): ?>
        <li class="nav-item">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="home" data-id="<?=$home->id; ?>" onclick="return checkNav(this);"><?=$home->name.' ('.$pCnt.')';?></a>
        </li>
        <?php 
        endif;
        endforeach; ?>
    </ul>
    
    <ul class="nav nav-pills main-nav">
        <li class="nav-item nav-label">
            <span>Position:</span>
        </li>
        <li class="nav-item all active">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="pos" data-id="0" onclick="return checkNav(this);">All positions</a>
        </li>
        <?php foreach ($positions as $position): 
            $dCnt = Device::find()->where(['position_id' => $position->id])->count();
            if ($dCnt > 0): ?>
        <li class="nav-item">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="pos" data-id="<?=$position->id; ?>" onclick="return checkNav(this);"><?=$position->name.' ('.$dCnt.')';?></a>
        </li>
        <?php 
        endif;
        endforeach; ?>
    </ul>
    
    <ul class="nav nav-pills main-nav">
        <li class="nav-item nav-label">
            <span>Device:</span>
        </li>
        <li class="nav-item all active">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="dev" data-id="0" onclick="return checkNav(this);">All devices</a>
        </li>        
        <?php foreach ($devices as $device): ?>
        <li class="nav-item">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="dev" data-id="<?=$device->id; ?>" onclick="return checkNav(this);"><?=$device->name;?></a>
        </li>
        <?php endforeach; ?>
    </ul>
    
    <ul class="nav nav-pills main-nav">
        <li class="nav-item nav-label">
            <span>Types:</span>
        </li>
        <li class="nav-item all active">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="type" data-id="0" onclick="return checkNav(this);">All types</a>
        </li>        
        <?php foreach ($types as $type): ?>
        <li class="nav-item">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="type" data-id="<?=$type; ?>" onclick="return checkNav(this);"><?=$type;?></a>
        </li>
        <?php endforeach; ?>
    </ul>
    
    <?php endif; ?>
    
    <label for="checkUpdate" class="checkUpdate"><input type="checkbox" id="checkUpdate" value="1" />Update sensors</label>
    <div class="body-content">
        <div class="row">
            <?php foreach ($sensors as $sensor): 
                $sensorStat = SensorStat::find()->where(['sensor_id' => $sensor->id])->one(); ?>
                <?php if ($sensorStat)
                {
                    echo $this->render('//layouts/_speedometer', ['value' => $sensorStat->getLastValue(), 'sensor' => $sensor]);
                } ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
setInterval(function(){ 
    getLastSensorsValue();
}, 5000);
</script>