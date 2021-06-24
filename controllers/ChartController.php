<?php


namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SensorStat;
use app\models\Sensor;
use app\models\SensorValue;


/**
 * Description of ChartController
 *
 * @author r-2
 */
class ChartController extends Controller
{
    /**
     * Main chart page
     * @return mixed
     */
    public function actionIndex()
    {
        $period = Yii::$app->request->get('period', 'minute');
        $device_id = Yii::$app->request->get('device_id', null);
        $sensor_id = Yii::$app->request->get('sensor_id', null);
        $sensor = Sensor::find()->where(['id' => $sensor_id])->one();
        
        $stat = SensorStat::find()->where(['sensor_id' => $sensor_id, 'device_id' => $device_id])->one();

        $items = [];
        if ($stat)
        {
            $items = $stat->getData($period);
        }

        return $this->render('index', [
            'items' => $items,
            'period' => $period,
            'sensor' => $sensor,
            'device_id' => $device_id,
        ]);
    }
    
    /**
     * Alternative chart page
     * @return type
     */
    public function actionAlt()
    {
        $period = Yii::$app->request->get('period', 'minute');
        $device_id = Yii::$app->request->get('device_id', null);
        $sensor_id = Yii::$app->request->get('sensor_id', null);
        $sensor = Sensor::find()->where(['id' => $sensor_id])->one();

        $sensorValue = SensorValue::find()->select(['created'])->where(['sensor_id' => $sensor_id, 'device_id' => $device_id])->orderBy('id desc')->limit(1)->one();

        $cutDate = new \DateTime($sensorValue->created);

        if ($period === 'minute')
        {
            $cutDate->modify('-1 hour');

           /* $items = SensorValue::find()
                    ->select(['AVG(value) as value', "DATE_FORMAT(created, '%H:%i') as created"])
                    ->where(['sensor_id' => $sensor->id])
                    ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
                    ->groupBy(['MINUTE(created)'])
                    ->orderBy('id')
                    ->all();*/
            $start = SensorValue::find()
                    ->select('id')
                    ->where(['sensor_id' => $sensor_id, 'device_id' => $device_id])
                    ->andWhere(['<=', 'created' , $cutDate->format('Y-m-d H:i:s')])
                    ->orderBy('id desc')
                    ->limit(1)->one();

            $limit = SensorValue::find()
                    ->where(['sensor_id' => $sensor_id, 'device_id' => $device_id])
                    ->andWhere(['>=', 'id', $start->id ])
                    ->orderBy('id desc')
                    ->count('id');

            $sql = "select AVG(value) as value, DATE_FORMAT(created, '%H:%i') as created
        from (select created, value
        from sensor_value
        where sensor_id = ".$sensor->id." 
        order by id desc
        limit ".$limit.") as t1
        group by MINUTE(created)
        order by created";

            $items = SensorValue::findBySql($sql)->all();    
        }
        if ($period === 'hour')
        {
            $cutDate->modify('-1 day');

            $items = SensorValue::find()
                    ->select(['AVG(value) as value', "DATE_FORMAT(created, '%H') as created"])
                    ->where(['sensor_id' => $sensor_id, 'device_id' => $device_id])
                    ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
                    ->groupBy(['HOUR(created)'])
                    ->orderBy('id')
                    ->all();
        }
        if ($period === 'day')
        {
            $cutDate->modify('-1 month');

            $items = SensorValue::find()
                    ->select(['AVG(value) as value', "DATE_FORMAT(created, '%d.%m') as created"])
                    ->where(['sensor_id' => $sensor_id, 'device_id' => $device_id])
                    ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
                    ->groupBy(['DAY(created)'])
                    ->orderBy('id')
                    ->all();
        }
        if ($period === 'week')
        {
            $cutDate->modify('-1 year');

            $items = SensorValue::find()
                    ->select(['AVG(value) as value', "DATE_FORMAT(created, '%d.%m') as created"])
                    ->where(['sensor_id' => $sensor_id, 'device_id' => $device_id])
                    ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
                    ->groupBy(['WEEK(created)'])
                    ->orderBy('id')
                    ->all();
        }
        if ($period === 'month')
        {
            $cutDate->modify('-1 year');

            $items = SensorValue::find()
                    ->select(['AVG(value) as value', "DATE_FORMAT(created, '%m.%y') as created"])
                    ->where(['sensor_id' => $sensor_id, 'device_id' => $device_id])
                    ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
                    ->groupBy(['MONTH(created)'])
                    ->orderBy('id')
                    ->all();
        }

        $dataX = [];
        $dataY = [];

        $minY = 9999;
        $maxY = -9999;

        if ($items)
        {
            foreach ($items as $item)
            {
                $dataX[] = $item->created;
                $dataY[] = $item->value;        

                if ($item->value < $minY)
                {
                    $minY = $item->value;
                }
                if ($item->value > $maxY)
                {
                    $maxY = $item->value;
                }
            }
        }

        $lowY = $minY + $maxY > 200 ? floor($minY/10) * 10 : floor($minY);
        $hiY =  $minY + $maxY > 200 ? ceil($maxY/10) * 10  : ceil($maxY);
        
        return $this->render('alt', [
            'items' => $items,
            'period' => $period,
            'sensor' => $sensor,
            'device_id' => $device_id,
            'dataX' => $dataX,
            'dataY' => $dataY,
            'lowY' => $lowY,
            'hiY' => $hiY,
        ]);
    }
}
