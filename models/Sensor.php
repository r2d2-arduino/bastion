<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sensor".
 *
 * @property int $id
 * @property string $created
 * @property string $name
 * @property string $shortname
 * @property string|null $unit
 * @property float|null $min_rate
 * @property float|null $max_rate
 *
 * @property DeviceSensor[] $deviceSensors
 * @property SensorValue[] $sensorValues
 */
class Sensor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sensor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['name', 'shortname'], 'required'],
            [['min_rate', 'max_rate'], 'number'],
            [['name'], 'string', 'max' => 16],
            [['shortname', 'unit'], 'string', 'max' => 8],
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
            'name' => Yii::t('app', 'Name'),
            'shortname' => Yii::t('app', 'Shortname'),
            'unit' => Yii::t('app', 'Unit'),
            'min_rate' => Yii::t('app', 'Min Rate'),
            'max_rate' => Yii::t('app', 'Max Rate'),
        ];
    }

    /**
     * Gets query for [[DeviceSensors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceSensors()
    {
        return $this->hasMany(DeviceSensor::className(), ['sensor_id' => 'id']);
    }

    /**
     * Gets query for [[SensorValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSensorValues()
    {
        return $this->hasMany(SensorValue::className(), ['sensor_id' => 'id']);
    }
}
