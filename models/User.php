<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $created
 * @property string $name
 *
 * @property UserHome[] $userHomes
 */
class User extends \yii\db\ActiveRecord  implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['username'], 'required'],
            [['username'], 'string', 'max' => 50],
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
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    
    /**
     * Gets query for [[UserHomes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserHomes()
    {
        return $this->hasMany(UserHome::className(), ['user_id' => 'id']);
    }
        
    public function validatePassword($password)
    {
        return $password === $this->password;
    }
    
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }
    
    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::findOne(['accessToken' => $token]);        
    }

}
