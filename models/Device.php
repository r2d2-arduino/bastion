<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "device".
 *
 * @property int $id
 * @property string $name
 * @property int $position_id
 * @property string $channel
 * @property int $connection_id
 *
 * @property Connection $connection
 * @property Position $position
 * @property DeviceController[] $deviceControllers
 * @property DeviceSensor[] $deviceSensors
 * @property SensorValue[] $sensorValues
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],            
            [['name', 'position_id'], 'required'],
            [['position_id', 'connection_id'], 'integer'],
            [['channel'], 'string'],
            [['name'], 'string', 'max' => 32],
            [['connection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Connection::className(), 'targetAttribute' => ['connection_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'name' => Yii::t('app', 'Name'),
            'position_id' => Yii::t('app', 'Position'),
            'channel' => Yii::t('app', 'Channel'),
            'connection_id' => Yii::t('app', 'Connection'),
        ];
    }

    /**
     * Gets query for [[Connection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConnection()
    {
        return $this->hasOne(Connection::className(), ['id' => 'connection_id']);
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * Gets query for [[DeviceControllers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceControllers()
    {
        return $this->hasMany(DeviceController::className(), ['device_id' => 'id']);
    }

    /**
     * Gets query for [[DeviceSensors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceSensors()
    {
        return $this->hasMany(DeviceSensor::className(), ['device_id' => 'id']);
    }

    /**
     * Gets query for [[SensorValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSensorValues()
    {
        return $this->hasMany(SensorValue::className(), ['device_id' => 'id']);
    }
}
