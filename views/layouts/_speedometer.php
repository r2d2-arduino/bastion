<?php
use app\models\Sensor;
/**
 * $value -
 * $sensor_id - 
 * $device_id - 
 * $sensor -
 */
$sensor = isset($sensor) ? $sensor : Sensor::findOne(['user_id' => Yii::$app->user->id, 'id' => $sensor_id]);

$grad = 90;

if ($sensor->max_rate - $sensor->min_rate > 0)
{
    $grad = round(180 * ($value - $sensor->min_rate) / ($sensor->max_rate - $sensor->min_rate) );
}
if ($grad < 0)
{
    $grad = 0;
}
?>
<div class="col-md-3 text-center speedometer" >
    <h3><?=$sensor->name; ?><?=$sensor->unit ? ' ('.$sensor->unit.')' : ''; ?><div class="led "></div></h3>
    
    <div id="sensor_<?=$device_id.'_'.$sensor->id?>" class="gauge-wrapper " 
         onclick="window.location.href = window.location.origin + '/sensor/view?id=<?=$sensor->id?>&device_id=<?=$device_id?>';" 
         data-min="<?=$sensor->min_rate?>" data-max="<?=$sensor->max_rate?>" data-grad="<?=$grad;?>">
        
        <div class="gauge four">
            
            <div class="slice-colors">
                <div class="st slice-item"></div>
                <div class="st slice-item"></div>
                <div class="st slice-item"></div>
                <div class="st slice-item"></div>
            </div>
            <div class="needle" style="transform: rotate(<?=$grad;?>deg);"></div>
            <div class="gauge-center">
                <div class="label"></div>
                <div class="number"><?=$value > 100 ? round($value) : (float) $value;?></div>
            </div>    
        </div>
    </div>
</div>
