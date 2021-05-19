<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "home_position".
 *
 * @property int $home_id
 * @property int $position_id
 *
 * @property Home $home
 * @property Position $position
 */
class HomePosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'home_position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['home_id', 'position_id'], 'required'],
            [['home_id', 'position_id'], 'integer'],
            [['home_id'], 'exist', 'skipOnError' => true, 'targetClass' => Home::className(), 'targetAttribute' => ['home_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'home_id' => Yii::t('app', 'Home ID'),
            'position_id' => Yii::t('app', 'Position ID'),
        ];
    }

    /**
     * Gets query for [[Home]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHome()
    {
        return $this->hasOne(Home::className(), ['id' => 'home_id']);
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
}
