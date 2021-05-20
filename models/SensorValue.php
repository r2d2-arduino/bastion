<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sensor_value".
 *
 * @property int $id
 * @property string $created
 * @property int $sensor_id
 * @property int $device_id
 * @property float $value
 *
 * @property Device $device
 * @property Sensor $sensor
 */
class SensorValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sensor_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['sensor_id', 'device_id'], 'required'],
            [['sensor_id', 'device_id'], 'integer'],
            [['value'], 'number'],
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
            'id' => Yii::t('app', 'ID'),
            'created' => Yii::t('app', 'Created'),
            'sensor_id' => Yii::t('app', 'Sensor'),
            'device_id' => Yii::t('app', 'Device'),
            'value' => Yii::t('app', 'Value'),
        ];
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
