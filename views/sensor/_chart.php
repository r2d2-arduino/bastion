<?php
/**
 * 
 * 
 */
use app\models\SensorValue;
use yii\web\Request;

$period = Yii::$app->request->get('period', 'day');

$this->registerJsFile('@web/js/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$maxSensorDateId = SensorValue::find()->where(['sensor_id' => $model->id])->max('id');
$maxSensorDate = SensorValue::find()->select(['created'])->where(['id' => (int)$maxSensorDateId])->one();

$cutDate = new DateTime($maxSensorDate->created);

if ($period === 'minute')
{
    $cutDate->modify('-1 hour');

    $items = SensorValue::find()
            ->select(['AVG(value) as value', "DATE_FORMAT(created, '%H:%i') as created"])
            ->where(['sensor_id' => $model->id])
            ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
            ->groupBy(['MINUTE(created)'])
            ->all();
}
if ($period === 'hour')
{
    $cutDate->modify('-1 day');

    $items = SensorValue::find()
            ->select(['AVG(value) as value', "DATE_FORMAT(created, '%H') as created"])
            ->where(['sensor_id' => $model->id])
            ->andWhere(['>', 'created' , $cutDate->format('Y-m-d H:i:s')])
            ->groupBy(['HOUR(created)'])
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
$lowY = floor($minY/10) * 10;
$hiY = ceil($maxY/10) * 10;

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