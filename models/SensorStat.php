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
            [['minute0', 'minute1', 'minute2', 'minute3', 'minute4', 'minute5', 'minute6', 'minute7', 'minute8', 'minute9', 'minute10', 'minute11', 'minute12', 'minute13', 'minute14', 'minute15', 'minute16', 'minute17', 'minute18', 'minute19', 'minute20', 'minute21', 'minute22', 'minute23', 'minute24', 'minute25', 'minute26', 'minute27', 'minute28', 'minute29', 'minute30', 'minute31', 'minute32', 'minute33', 'minute34', 'minute35', 'minute36', 'minute37', 'minute38', 'minute39', 'minute40', 'minute41', 'minute42', 'minute43', 'minute44', 'minute45', 'minute46', 'minute47', 'minute48', 'minute49', 'minute50', 'minute51', 'minute52', 'minute53', 'minute54', 'minute55', 'minute56', 'minute57', 'minute58', 'minute59', 
              'hour0', 'hour1', 'hour2', 'hour3', 'hour4', 'hour5', 'hour6', 'hour7', 'hour8', 'hour9', 'hour10', 'hour11', 'hour12', 'hour13', 'hour14', 'hour15', 'hour16', 'hour17', 'hour18', 'hour19', 'hour20', 'hour21', 'hour22', 'hour23', 
              'week1', 'week2', 'week3', 'week4', 'week5', 'week6', 'week7', 'week8', 'week9', 'week10', 'week11', 'week12', 'week13', 'week14', 'week15', 'week16', 'week17', 'week18', 'week19', 'week20', 'week21', 'week22', 'week23', 'week24', 'week25', 'week26', 'week27', 'week28', 'week29', 'week30', 'week31', 'week32', 'week33', 'week34', 'week35', 'week36', 'week37', 'week38', 'week39', 'week40', 'week41', 'week42', 'week43', 'week44', 'week45', 'week46', 'week47', 'week48', 'week49', 'week50', 'week51', 'week52', 'week53',
              'day1', 'day2', 'day3', 'day4', 'day5', 'day6', 'day7', 'day8', 'day9', 'day10', 'day11', 'day12', 'day13', 'day14', 'day15', 'day16', 'day17', 'day18', 'day19', 'day20', 'day21', 'day22', 'day23', 'day24', 'day25', 'day26', 'day27', 'day28', 'day29', 'day30', 'day31', 
              'month1', 'month2', 'month3', 'month4', 'month5', 'month6', 'month7', 'month8', 'month9', 'month10', 'month11', 'month12'], 'number'],
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
    /**
     * 
     * @param type $value
     * @param type $created
     */
    public function updateByDate($value)
    {
        $created = (new \yii\db\Query)->select( new yii\db\Expression('NOW()') )->scalar();
        $datetime = new \DateTime($created);
        
        $minute = 'minute'.(int) $datetime->format('i');
        $hour = 'hour'.(int) $datetime->format('H');
        $day = 'day'.(int) $datetime->format('d');
        $week = 'week'.(int) $datetime->format('W');
        $month = 'month'.(int) $datetime->format('m');
        
        $this->$minute = $this->$minute ? ($this->$minute + $value)/2 : $value;
        $this->$hour = $this->$hour ? ($this->$hour + $value)/2 : $value;
        $this->$day = $this->$day ? ($this->$day + $value)/2 : $value;
        $this->$week = $this->$week ? ($this->$week + $value)/2 : $value;
        $this->$month = $this->$month ? ($this->$month + $value)/2 : $value;
        
        $this->save();
    }
    
    public function getData($name, $created = null)
    {
        $minY = 9999;
        $maxY = -9999;

        $cutDate = new \DateTime($created);
        
        $amount = [
            'minute' => 59,
            'hour' => 23,
            'day' => 31,
            'week' => 53,
            'month' => 12,
        ];
        
        $startFrom = [
            'minute' => 0,
            'hour' => 0,
            'day' => 1,
            'week' => 1,
            'month' => 1,
        ];
        
        $start = [
            'minute' => (int) $cutDate->format('i'),
            'hour' => (int) $cutDate->format('H'),
            'day' => (int) $cutDate->format('d'),
            'week' => (int) $cutDate->format('W'),
            'month' => (int) $cutDate->format('m'),
        ];
        
        if ($name == 'day')
        {
            $amount['day'] = $cutDate->format('t');
        }

        $items = [];
        $labels = [];
       /* print_r($cutDate);
        print_r('<br>');
        print_r($start[$name]);
        print_r('<br>');
        print_r($amount[$name]);*/
        
        for ($i = $start[$name]; $i < $amount[$name]; $i++)
        {
            $attr = $name.$i;
            if ($this->$attr)
            {
                $items[] = $this->$attr;
                $labels[] = $i < 10 ? "0".$i : $i;
                
                if ( (float) $this->$attr < $minY)
                {
                    $minY = (float) $this->$attr;
                }
                if ( (float) $this->$attr > $maxY)
                {
                    $maxY = (float) $this->$attr;
                }
            }
        }
     
        /*for ($j = $startFrom[$name]; $j < $start[$name]; $j++)
        {
            $attr = $name.$j;
            if ($this->$attr)
            {
                $items[] = $this->$attr;
                $labels[] = $j < 10 ? "0".$j : $j;
                
                if ( (float) $this->$attr < $minY)
                {
                    $minY = (float) $this->$attr;
                }
                if ( (float) $this->$attr > $maxY)
                {
                    $maxY = (float) $this->$attr;
                }
            }
        }*/
        
        $lowY = $minY + $maxY > 200 ? floor($minY/10) * 10 : floor($minY);
        $hiY =  $minY + $maxY > 200 ? ceil($maxY/10) * 10  : ceil($maxY);
        
        return ['x' => $labels, 'y' => $items, 'lowY' => $lowY, 'hiY' => $hiY];
    }
    
}
