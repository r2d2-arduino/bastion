<?php
/**
 * 
 * 
 */
use app\models\SensorStat;
use yii\web\Request;

$period = Yii::$app->request->get('period', 'minute');
$device_id = Yii::$app->request->get('device_id', null);

$this->registerJsFile('@web/js/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$stat = SensorStat::find()->where(['sensor_id' => $model->id, 'device_id' => $device_id])->one();

if ($stat):
    $items = $stat->getData($period);
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

  labels: <?php echo json_encode($items['x'], JSON_NUMERIC_CHECK); ?>,
  datasets: [{
    label: '<?=$model->name?><?=$model->unit ? ' ('.$model->unit.')' : ''?>',
    backgroundColor: 'rgb(0, 55, 255)',
    borderColor: 'rgb(0, 55, 255)',
    data: <?php echo json_encode($items['y'], JSON_NUMERIC_CHECK); ?>,
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
                suggestedMin: <?=$items['lowY'] ?>,
                suggestedMax: <?=$items['hiY'] ?>
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
    if (!getParam('period'))
    {
        window.location.href = window.location.href + '&period='+name
    }
    else
    {
        insertParam('period', name);
    }
}
</script>
<?php endif; ?>