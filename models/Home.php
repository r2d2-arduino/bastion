<?php

namespace app\models;

use Yii;
use app\models\Position;
/**
 * This is the model class for table "home".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 *
 * @property User $user
 * @property HomePosition[] $homePositions
 */
class Home extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'home';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
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
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
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
        return $this->hasMany(HomePosition::className(), ['home_id' => 'id']);
    }
    
    /**
     * Position count of current home
     * @return type
     */
    public function getPositionCount()
    {
        return Position::find()->where(['home_id' => $this->id])->count();
    }
}
