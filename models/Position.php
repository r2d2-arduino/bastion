<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "position".
 *
 * @property int $id
 * @property string $name
 *
 * @property Device[] $devices
 * @property HomePosition[] $homePositions
 */
class Position extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['home_id'], 'required'],
            [['home_id'], 'integer'], 
            [['user_id'], 'required'],
            [['user_id'], 'integer'],            
            [['name'], 'required'],
            [['name'], 'string', 'max' => 8],
            [['home_id'], 'exist', 'skipOnError' => true, 'targetClass' => Home::className(), 'targetAttribute' => ['home_id' => 'id']],
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
            'home_id' => Yii::t('app', 'Home'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * Gets query for [[Devices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Device::className(), ['position_id' => 'id']);
    }

    public function getHome()
    {
        return $this->hasOne(User::className(), ['id' => 'home_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * Gets query for [[HomePositions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHomePositions()
    {
        return $this->hasMany(HomePosition::className(), ['position_id' => 'id']);
    }
}
