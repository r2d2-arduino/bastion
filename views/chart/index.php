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

if ($items):
?>
<div class="chart-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <select class="form-control" aria-label="Choose period..." onchange="changeParam('period', $(this).val())" >
        <option value="minute" <?=$period==='minute' ? 'selected' : ''?> >Minutly</option>
        <option value="hour" <?=$period==='hour' ? 'selected' : ''?> >Hourly</option>
        <option value="day" <?=$period==='day' ? 'selected' : ''?> >Dayly</option>
        <option value="week" <?=$period==='week' ? 'selected' : ''?> >Weekly</option>
        <option value="month" <?=$period==='month' ? 'selected' : ''?> >Monthly</option>
    </select>
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