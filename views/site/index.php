<?php

/* @var $this yii\web\View */

use app\models\SensorStat;

$this->title = 'My Bastion';

$this->registerJsFile('@web/js/speedometer.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$home_id = (int) Yii::$app->request->get('home_id', 0);
$position_id = (int) Yii::$app->request->get('position_id', 0);
$device_id = (int) Yii::$app->request->get('device_id', 0);
$sensor_id = (int) Yii::$app->request->get('sensor_id', 0);
$type_id = Yii::$app->request->get('type_id', '0');
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
    <!-- HOME -->
    <?php if (count($homes) > 1): ?>
    <ul class="nav nav-pills main-nav">
        <li class="nav-item all <?=$home_id === 0 ? 'active' : ''?> ">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="home_id" data-id="0" onclick="return checkNav(this);">All homes</a>
        </li>        
        <?php foreach ($homes as $home): 
            $pCnt = $home->positionCount;
            if ($pCnt > 0): ?>
            <li class="nav-item <?=$home_id === (int) $home->id ? 'active' : ''?> ">
                <a class="nav-link " aria-current="page" href="#" 
                   data-name="home_id" data-id="<?=$home->id; ?>" onclick="return checkNav(this);"><?=$home->name.' ('.$pCnt.')';?></a>
            </li>
        <?php 
            endif;
        endforeach; ?>
    </ul>
    <?php endif; ?>
    <!-- POSITION -->
    <ul class="nav nav-pills main-nav">
        <li class="nav-item all <?=$position_id === 0 ? 'active' : ''?>">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="position_id" data-id="0" onclick="return checkNav(this);">All positions</a>
        </li>
        <?php foreach ($positions as $position): 
            $dCnt = $position->deviceCount;
            if ($dCnt > 0): ?>
            <li class="nav-item <?=$position_id === (int) $position->id ? 'active' : ''?> ">
                <a class="nav-link " aria-current="page" href="#" onclick="return checkNav(this);"
                   data-name="position_id" data-id="<?=$position->id; ?>" onclick="return checkNav(this);"><?=$position->name.' ('.$dCnt.')';?></a>
            </li>
        <?php 
            endif;
        endforeach; ?>
    </ul>
    <!-- DEVICE -->
    <ul class="nav nav-pills main-nav">
        <li class="nav-item all <?=$device_id === 0 ? 'active' : ''?>">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="device_id" data-id="0" onclick="return checkNav(this);">All devices</a>
        </li>        
        <?php foreach ($devices as $device): 
            $sCnt = $device->subitemCount; 
            if ($sCnt > 0): ?>
            <li class="nav-item <?=$device_id === (int) $device->id ? 'active' : ''?>">
                <a class="nav-link " aria-current="page" href="#" onclick="return checkNav(this);"
                   data-name="device_id" data-id="<?=$device->id; ?>" onclick="return checkNav(this);"><?=$device->name.' ('.$sCnt.')';?></a>
            </li>
        <?php 
            endif;
        endforeach; ?>
    </ul>
    <!-- TYPE -->
    <ul class="nav nav-pills main-nav">
        <li class="nav-item all <?=$type_id === '0' ? 'active' : ''?>">
            <a class="nav-link " aria-current="page" href="#" 
               data-name="type_id" data-id="0" onclick="return checkNav(this);">All types</a>
        </li>        
        <?php foreach ($types as $type): ?>
        <li class="nav-item <?=$type_id === $type ? 'active' : ''?>">
            <a class="nav-link " aria-current="page" href="#" onclick="return checkNav(this);" 
               data-name="type_id" data-id="<?=$type; ?>" onclick="return checkNav(this);"><?=$type;?></a>
        </li>
        <?php endforeach; ?>
    </ul>
    
    
        
    <?php endif; ?>
    
    <!-- SENSORS -->
    <div class="body-content container">
        <div class="col-md-12 col-sm-12 text-right" >
            <label for="checkUpdate" class="checkUpdate"><input type="checkbox" id="checkUpdate" value="1" />Update sensors</label>
        </div>
        <?php foreach ($choosedDevices as $device):
            
            $sensorIds = \app\models\DeviceSensor::find()
                    ->select('sensor_id')
                    ->where(['device_id' => $device->id])
                    ->column();

            $sensorStats = SensorStat::find()->where(['in', 'sensor_id', $sensorIds])->andWhere(['device_id' => $device->id])->orderBy('sensor_id asc')->all(); 
            
            if (count($sensorStats)): ?>
            <div class="col-md-12 col-sm-12 text-center" >
                <h2><?php echo $device->name ?></h2>
            </div>
            <div class="row">
            
            <?php foreach ($sensorStats as $sensorStat): ?>
                <?php echo $this->render('//layouts/_speedometer', ['value' => $sensorStat->getLastValue(), 'sensor_id' => $sensorStat->sensor_id, 'device_id' => $sensorStat->device_id]); ?>
            <?php endforeach; ?> 
            </div>
        <?php else: ?>
        <?php endif;
        endforeach; ?>
    </div>
</div>
<script>
<?php $device_id = count($choosedDevices) === 1 ? $choosedDevices[0]->id : '' ?>
setInterval(function(){ 
    getLastSensorsValue(<?=$device_id?>);
}, 5000);
</script>