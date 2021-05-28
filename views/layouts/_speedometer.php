<?php
use app\models\Sensor;
/**
 * $value -
 * $sensor_id - 
 * $sensor -
 */
$sensor = isset($sensor) ? $sensor : Sensor::findOne(['user_id' => Yii::$app->user->id, 'id' => $sensor_id]);

$grad = round(180 * ($value - $sensor->min_rate) / ($sensor->max_rate - $sensor->min_rate) );
?>
<div class="col-md-3 text-center speedometer" >
    <h3><?=$sensor->name; ?> (<?=$sensor->unit; ?>)</h3>
    <div id="sensor_<?=$sensor->id?>" class="gauge-wrapper " 
         onclick="window.location.href = window.location.pathname + '?r=sensor%2Fview&id=<?=$sensor->id?>';" 
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
