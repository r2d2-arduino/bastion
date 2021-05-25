<?php
use app\models\Sensor;

$sensor = isset($sensor) ? $sensor : Sensor::findOne(['user_id' => Yii::$app->user->id, 'id' => $model->sensor_id]);

$value = $model->value > 100 ? round($model->value) : $model->value;

$pos = ($value - $sensor->min_rate) / ($sensor->max_rate - $sensor->min_rate);
$grad = round(180 * $pos);
?>
<h3><?=$sensor->name; ?> (<?=$sensor->unit; ?>)</h3>
<div class="gauge-wrapper">
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
            <div class="number"><?=$value;?></div>
        </div>    
    </div>
</div>

<!-- script>
    window.onload = function () 
    {
       $('.needle').css('transform', 'rotate(<?=$grad;?>deg)'); 
    };
</script -->

<style>

.gauge-wrapper {
  display: inline-block;
  width: auto;
  margin: 0 auto;
  padding: 20px 15px 15px;
  text-align: center;
}

.gauge {
  background: #e7e7e7;
  box-shadow: 0 -3px 6px 2px rgba(0, 0, 0, 0.50);
  width: 200px;
  height: 100px;
  border-radius: 100px 100px 0 0!important;
  position: relative;
  overflow: hidden;
}
.gauge.min-scaled {
  transform: scale(0.5);
}

.gauge-center {
  content: '';
  color: #fff;
  width: 76%;
  height: 76%;
  background: white;
  border-radius: 100px 100px 0 0!important;
  position: absolute;
  box-shadow: 0 -13px 15px -10px rgba(0, 0, 0, 0.28);
  right: 12.5%;
  bottom: 0;
  color: black;
  z-index:10;
  _display: none;
}

.gauge-center .label, .gauge-center .number {
    display:block; 
    width: 100%; 
    text-align: center; 
    border:0!important;
}
.gauge-center .label {
    font-size:0.75em; 
    opacity:0.6; 
    margin:1.1em 0 0.1em 0;
    color: black;
}
.gauge-center .number {
    font-size:3.5em;
}

.needle {
  width: 90px;
  height: 7px;
  background: #15222E;
  border-bottom-left-radius: 100%!important;
  border-bottom-right-radius: 5px!important;
  border-top-left-radius: 100%!important;
  border-top-right-radius: 5px!important;
  position: absolute;
  bottom: -2px;
  left: 10px;
  transform-origin: 100% 4px;
  transform: rotate(0deg);
  box-shadow: 0 2px 2px 1px rgba(0, 0, 0, 0.38);
  display:block;
  z-index:9;
}

.slice-colors {height:100%;}

.slice-colors .st {
  position: absolute;
  bottom: 0;
  width: 0;
  height: 0;
  border: 50px solid transparent;  
}


.four .slice-colors .st.slice-item:nth-child(2) {
  border-top: 50px #f1c40f solid;
  border-right: 50px #f1c40f solid;
  background-color:#1eaa59;
}

.four .slice-colors .st.slice-item:nth-child(4) {
  left:50%;
  border-bottom: 50px #E84C3D solid;
  border-right: 50px #E84C3D solid;
  background-color:#e67e22;
}

</style>