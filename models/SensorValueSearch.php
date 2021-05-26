<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SensorValue;

/**
 * SensorValueSearch represents the model behind the search form of `app\models\SensorValue`.
 */
class SensorValueSearch extends SensorValue
{
    public $bigless = '=';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sensor_id', 'device_id'], 'integer'],
            [['created', 'bigless'], 'safe'],
            [['value'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SensorValue::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //print_r($this);exit;
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created' => $this->created,
            'sensor_id' => $this->sensor_id,
            'device_id' => $this->device_id,
            'value' => $this->value,
        ]);
        
        //$query->andFilterWhere([$this->bigless, 'value', $this->value]);

        return $dataProvider;
    }
}
