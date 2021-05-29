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
        
        $currMinute = (int) $datetime->format('i');
        $currHour   = (int) $datetime->format('H');
        $currDay    = (int) $datetime->format('d');
        $currWeek   = (int) $datetime->format('W');
        $currMonth  = (int) $datetime->format('m');
        $totalDays  = (int) $datetime->format('t');
        
        if ( (int) $this->month !== $currMonth )
        {
            $this->setAvgMonth($totalDays);
            $this->month = $currMonth;
        }
        if ( (int) $this->week !== $currWeek )
        {
            $this->setAvgWeek($datetime);
            $this->week = $currWeek;
        }
        if ( (int) $this->day !== $currDay )
        {
            $this->setAvgDay();
            $this->day = $currDay;
        }        
        if ( (int) $this->hour !== $currHour )
        {
            $this->setAvgHour();
            $this->hour = $currHour;
        }
        
        $minuteProp = 'minute'.$currMinute;
        $this->$minuteProp = $value;

        $this->save();
    }
    
    private function setAvgHour()
    {
        $sum = 0;
        $amount = 60;
        for ($i = 0; $i < 60; $i++)
        {
            $prop = 'minute'.$i;
            if ($this->$prop === null)
            {
                $amount--;
            }
            else
            {
                $sum = $sum + (float) $this->$prop; 
            }
        }
        $avgProp = 'hour'.$this->hour;
        $this->$avgProp = $amount > 0 ? $sum / $amount : null;
    }
    
    private function setAvgDay()
    {
        $sum = 0;
        $amount = 24;
        for ($i = 0; $i < 24; $i++)
        {
            $prop = 'hour'.$i;
            if ($this->$prop === null)
            {
                $amount--;
            }
            else
            {
                $sum = $sum + (float) $this->$prop; 
            }
        }
        $avgProp = 'day'.$this->day;
        $this->$avgProp = $amount > 0 ? $sum / $amount : null;
    }

    private function setAvgWeek($datetime)
    {
        $sum = 0;
        $amount = 7;
        for ($i = 0; $i < 7; $i++)
        {
            $day = (int) $datetime->format('d');
            $datetime->modify('-1 day');
        
            $prop = 'day'.$day;
            if ($this->$prop === null)
            {
                $amount--;
            }
            else
            {
                $sum = $sum + (float) $this->$prop; 
            }
        }
        $avgProp = 'week'.$this->week;
        $this->$avgProp = $amount > 0 ? $sum / $amount : null;
    }
    
    private function setAvgMonth($totalDays)
    {
        $sum = 0;
        $amount = $totalDays;
        for ($i = 1; $i <= $totalDays; $i++)
        {
            $prop = 'day'.$i;
            if ($this->$prop === null)
            {
                $amount--;
            }
            else
            {
                $sum = $sum + (float) $this->$prop; 
            }
        }
        
        $avgProp = 'month'.$this->month;
        $this->$avgProp = $amount > 0 ? $sum / $amount : null;
    }
    
    public function getData($name, $created = null)
    {
        $minY = 9999;
        $maxY = -9999;

        $cutDate = new \DateTime($created);
        
        $monthName = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];
        
        $upper = [
            'minute' => 'hour', 'hour' => 'day', 'day' => 'month', 'week' => 'year', 'month' => 'year'
        ];
        
        $end = [
            'minute' => 59, 'hour' => 23, 'day' => 31, 'week' => 53, 'month' => 12,
        ];
        
        $begin = [
            'minute' => 0, 'hour' => 0, 'day' => 1, 'week' => 1, 'month' => 1,
        ];
        
        $current = [
            'minute' => (int) $cutDate->format('i'),
            'hour' => (int) $cutDate->format('H'),
            'day' => (int) $cutDate->format('d'),
            'week' => (int) $cutDate->format('W'),
            'month' => (int) $cutDate->format('m'),
        ];

        $cutDate->modify('-1 '.$upper[$name]);

        $previos = [
            'minute' => (int) $cutDate->format('i'),
            'hour' => (int) $cutDate->format('H'),
            'day' => (int) $cutDate->format('d'),
            'week' => (int) $cutDate->format('W'),
            'month' => (int) $cutDate->format('m'),
        ];
        
        if ($name == 'day')
        {
            $end['day'] = $cutDate->format('t');
        }
        
       // print_r($previos);
        
        $itemsRaw = [];
        $labelsRaw = [];

        for ($i = $begin[$name]; $i <= $end[$name]; $i++)
        {
            $attr = $name.$i;
            if ($this->$attr)
            {
                $itemsRaw[] = $this->$attr;
                
                $label = $i;
                                
                if ($name === 'minute')
                {
                    $label = $i < 10 ? '0'.$i : ''.$i;
                    $label = $i > $current[$name] ? $previos[$upper[$name]].':'.$label : $current[$upper[$name]].':'.$label;
                }    
                if ($name === 'hour')
                {
                    $label = $i > $current[$name] ? $label.':00' : $label.':00';
                }
                if ($name === 'day')
                {
                    $month = $i > $current[$name] ? $monthName[$previos[$upper[$name]]] : $monthName[$current[$upper[$name]]];
                    $label = $month.' '.$label;
                }
                if ($name === 'month')
                {
                    $label = $monthName[$label];
                }
                
                $labelsRaw[] = $label;
                
                $minY = (float) $this->$attr < $minY ? (float) $this->$attr : $minY;
                $maxY = (float) $this->$attr > $maxY ? (float) $this->$attr : $maxY;
            }
        }
        $items1 = $items2 = $itemsRaw;
        array_splice($items1, $current[$name] + 1);        
        array_splice($items2, 0, $current[$name] + 1);

        $items = array_merge($items2, $items1);
        
        $labels1 = $labels2 = $labelsRaw;
        array_splice($labels1, $current[$name] +1);        
        array_splice($labels2, 0, $current[$name] + 1);
        
        $labels = array_merge($labels2, $labels1);
        
        $lowY = $minY + $maxY > 200 ? floor($minY/10) * 10 : floor($minY);
        $hiY =  $minY + $maxY > 200 ? ceil($maxY/10) * 10  : ceil($maxY);
        
        return [
            'x' => $labels, 'y' => $items, 'lowY' => $lowY, 'hiY' => $hiY
        ];
    }
    
}
