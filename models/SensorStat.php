<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sensor_stat".
 * 
 */
class SensorStat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sensor_stat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'sensor_id'], 'required'],
            [['device_id', 'sensor_id'], 'integer'],
            [['minute0', 'minute1', 'minute2', 'minute3', 'minute4', 'minute5', 'minute6', 'minute7', 'minute8', 'minute9', 'minute10', 'minute11', 'minute12', 'minute13', 'minute14', 'minute15', 'minute16', 'minute17', 'minute18', 'minute19', 'minute20', 'minute21', 'minute22', 'minute23', 'minute24', 'minute25', 'minute26', 'minute27', 'minute28', 'minute29', 'minute30', 'minute31', 'minute32', 'minute33', 'minute34', 'minute35', 'minute36', 'minute37', 'minute38', 'minute39', 'minute40', 'minute41', 'minute42', 'minute43', 'minute44', 'minute45', 'minute46', 'minute47', 'minute48', 'minute49', 'minute50', 'minute51', 'minute52', 'minute53', 'minute54', 'minute55', 'minute56', 'minute57', 'minute58', 'minute59', 'hour0', 'hour1', 'hour2', 'hour3', 'hour4', 'hour5', 'hour6', 'hour7', 'hour8', 'hour9', 'hour10', 'hour11', 'hour12', 'hour13', 'hour14', 'hour15', 'hour16', 'hour17', 'hour18', 'hour19', 'hour20', 'hour21', 'hour22', 'hour23', 'day1', 'day2', 'day3', 'day4', 'day5', 'day6', 'day7', 'day8', 'day9', 'day10', 'day11', 'day12', 'day13', 'day14', 'day15', 'day16', 'day17', 'day18', 'day19', 'day20', 'day21', 'day22', 'day23', 'day24', 'day25', 'day26', 'day27', 'day28', 'day29', 'day30', 'day31', 'month1', 'month2', 'month3', 'month4', 'month5', 'month6', 'month7', 'month8', 'month9', 'month10', 'month11', 'month12'], 'number'],
        ];
    }
    
    public static function getOne($device_id, $sensor_id)
    {
        $item = SensorStat::find()->where(['device_id' => $device_id, 'sensor_id' => $sensor_id])->one();
        if (!$item)
        {
            $item = new SensorStat();
            $item->device_id = $device_id;
            $item->sensor_id = $sensor_id;
            
            $item->save();
        }
        return $item;
    }
    
    public function updateByDate($value, $created = null)
    {
        $datetime = new \DateTime($created);
        
        $minute = 'minute'.(int) $datetime->format('i');
        $hour = 'hour'.(int) $datetime->format('H');
        $day = 'day'.(int) $datetime->format('d');
        $month = 'month'.(int) $datetime->format('m');
        
        $this->$minute = $this->$minute ? ($this->$minute + $value)/2 : $value;
        $this->$hour = $this->$hour ? ($this->$hour + $value)/2 : $value;
        $this->$day = $this->$day ? ($this->$day + $value)/2 : $value;
        $this->$month = $this->$month ? ($this->$month + $value)/2 : $value;
        
        $this->save();
    }
}
