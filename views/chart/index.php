<?php
/**
 * 
 * 
 */
use yii\web\Request;
use yii\helpers\Html;

$this->registerJsFile('@web/js/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$this->title = $sensor->name;
$this->params['breadcrumbs'][] = $this->title;

$periods = ['minute' => 'Minutly', 'hour' => 'Hourly', 'day' => 'Dayly', 'week' => 'Weekly', 'month' => 'Monthly'];

if ($items):
?>
<div class="chart-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <ul class="nav nav-pills main-nav">      
        <?php foreach ($periods as $value => $name): ?>
            <li class="nav-item <?=$period === $value ? 'active' : ''?>">
                <a class="nav-link " aria-current="page" href="#" 
                   onclick="return changeParam('period', '<?=$value?>');"><?=$name?></a>
            </li>
        <?php endforeach; ?>
    </ul>

    <br>

    <canvas id="myChart"></canvas>
</div>
<script>
const data = {

  labels: <?php echo json_encode($items['x'], JSON_NUMERIC_CHECK); ?>,
  datasets: [{
    label: '<?=$sensor->name?><?=$sensor->unit ? ' ('.$sensor->unit.')' : ''?>',
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
                    text: '<?=$sensor->unit?>'
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
</script>
<?php endif; ?>
<br>
<a href="/chart/alt?sensor_id=<?=$sensor->id?>&device_id=<?=$device_id?>">Original data</a>