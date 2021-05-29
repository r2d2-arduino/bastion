<?php
/**
 * 
 * 
 */
use app\models\SensorValue;
use yii\web\Request;

$period = Yii::$app->request->get('period', 'minute');

$this->registerJsFile('@web/js/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$sensorValue = SensorValue::find()->select(['created'])->where(['sensor_id' => $model->id])->orderBy('id desc')->limit(1)->one();

$cutDate = new DateTime($sensorValue->created);

if ($period === 'minute')
{
    $cutDate->modify('-1 hour');

   /* $items = SensorValue::find()
            ->select(['AVG(value) as value', "DATE_FORMAT(created, '%H:%i') as created"])
            ->where(['sensor_id' => $model->id])
            ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
            ->groupBy(['MINUTE(created)'])
            ->orderBy('id')
            ->all();*/
 $start = SensorValue::find()
            ->select('id')->where(['sensor_id' => $model->id])
            ->andWhere(['<=', 'created' , $cutDate->format('Y-m-d H:i:s')])
            ->orderBy('id desc')
            ->limit(1)->one();
    
    $limit = SensorValue::find()
            ->where(['sensor_id' => $model->id])
            ->andWhere(['>=', 'id', $start->id ])
            ->orderBy('id desc')
            ->count('id');
    
    $sql = "select AVG(value) as value, DATE_FORMAT(created, '%H:%i') as created
from (select created, value
from sensor_value
where sensor_id = ".$model->id." 
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
            ->where(['sensor_id' => $model->id])
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
            ->where(['sensor_id' => $model->id])
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
            ->where(['sensor_id' => $model->id])
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
            ->where(['sensor_id' => $model->id])
            ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
            ->groupBy(['MONTH(created)'])
            ->orderBy('id')
            ->all();
}

$dataX = [];
$dataY = [];

$minY = 9999;
$maxY = -9999;

$dataPoints = [];
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
?>
<select class="form-control" aria-label="Choose period..." onchange="changeChart($(this).val())" >
    <option value="minute" <?=$period==='minute' ? 'selected' : ''?> >Minutly</option>
    <option value="hour" <?=$period==='hour' ? 'selected' : ''?> >Hourly</option>
    <option value="day" <?=$period==='day' ? 'selected' : ''?> >Dayly</option>
    <option value="week" <?=$period==='week' ? 'selected' : ''?> >Weekly</option>
    <option value="month" <?=$period==='month' ? 'selected' : ''?> >Monthly</option>
</select>
<br>

<canvas id="myChart"></canvas>
<script>
const data = {

  labels: <?php echo json_encode($dataX, JSON_NUMERIC_CHECK); ?>,
  datasets: [{
    label: '<?=$model->name?> (<?=$model->unit?>)',
    backgroundColor: 'rgb(0, 55, 255)',
    borderColor: 'rgb(0, 55, 255)',
    data: <?php echo json_encode($dataY, JSON_NUMERIC_CHECK); ?>,
    //fill: true,
    cubicInterpolationMode: 'monotone',
    tension: 0.1,
  }]
};

const config = {
    type: 'line',
    data,
    options: {   
        plugins: {
            legend: {
                display: false,
            }
        },
        responsive: true,
        interaction: {
            intersect: false,
        },
        scales: {
          x: {
              display: true,
              title: {
                  display: false
              }
          },
          y: {
                display: true,
                title: {
                    display: false,
                    text: '<?=$model->unit?>'
                },
                suggestedMin: <?=$lowY ?>,
                suggestedMax: <?=$hiY ?>
            }
        }
    },
};   

var myChart = null;
window.onload = function () 
{
    myChart = new Chart( document.getElementById('myChart'), config );
}

function changeChart(name)
{
    window.location.href = window.location.href + '&period='+name
}
</script>