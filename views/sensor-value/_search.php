<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SensorValueSearch */
/* @var $form yii\widgets\ActiveForm */
use app\models\Sensor;
use app\models\Device;

$sensors = Sensor::findAll(['user_id' => Yii::$app->user->id]);
//$sensors = Sensor::find()->all();

$sensorList = [];
foreach ($sensors as $sensor)
{
    $sensorList[$sensor->id] = $sensor->name;
}

$devices = Device::findAll(['user_id' => Yii::$app->user->id]);
//$devices = Device::find()->all();

$deviceList = [];
foreach ($devices as $device)
{
    $deviceList[$device->id] = $device->name;
}



?>

<div class="sensor-value-search search-block">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php //= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created') ?>

    <?= $form->field($model, 'sensor_id')->dropDownList($sensorList, ['prompt' => 'Выберите сенсор...']); ?>
    <?= $form->field($model, 'device_id')->dropDownList($deviceList, ['prompt' => 'Выберите устройство...']); ?>
    
    

    
    <!-- div class="form-group field-sensorvaluesearch-value">
        <label class="control-label" for="sensorvaluesearch-value" style="display: block">Value</label>
        <select name="SensorValueSearch[bigless]" class="form-control" style="width: 60px; float: left; margin-right: 8px" >
            <option value="=" selected>=</option>
            <option value=">">></option>
            <option value="<"><</option>
            <option value="!=">!=</option>
        </select>
        <input  style="width: 200px; float: left" type="text" id="sensorvaluesearch-value" class="form-control" name="SensorValueSearch[value]">

        <div class="help-block"></div>
    </div -->
    
    <?= $form->field($model, 'value') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
