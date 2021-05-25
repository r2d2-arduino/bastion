<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "device_sensor".
 *
 * @property int $device_id
 * @property int $sensor_id
 *
 * @property Device $device
 * @property Sensor $sensor
 */
class DeviceSensor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device_sensor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'sensor_id'], 'required'],
            [['device_id', 'sensor_id'], 'integer'],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'id']],
            [['sensor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sensor::className(), 'targetAttribute' => ['sensor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'device_id' => Yii::t('app', 'Device ID'),
            'sensor_id' => Yii::t('app', 'Sensor ID'),
        ];
    }

    public static function removeByDeviceId($device_id)
    {
        DeviceSensor::deleteAll(['device_id' => $device_id]);
    }
    
    public static function add($device_id, $sensor_id)
    {
        $model = new DeviceSensor();
        $model->device_id = $device_id;
        $model->sensor_id = $sensor_id;
        $model->save();
    }

        /**
     * Gets query for [[Device]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    /**
     * Gets query for [[Sensor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSensor()
    {
        return $this->hasOne(Sensor::className(), ['id' => 'sensor_id']);
    }
}
