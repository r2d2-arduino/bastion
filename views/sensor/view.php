<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sensor */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sensors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

//$this->registerJsFile('@web/js/jquery.canvasjs.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);

use app\models\SensorValue;

$today = new DateTime();
$today->modify('-12 hours');
$stoday = $today->format('Y-m-d H:i:s');

$values = SensorValue::find()->where(['sensor_id' => $model->id])->andWhere(['>', 'created' , $stoday])->all();

$dataX = [];
$dataY = [];
$minY = 9999;
$maxY = -9999;

$dataPoints = [];
foreach ($values as $value)
{
    //array_push($dataPoints, ['x' => $value->id, 'y' => $value->value]);
    $dataX[] = $value->created;
    $dataY[] = $value->value;        
    
    if ($value->value < $minY)
    {
        $minY = $value->value;
    }
    if ($value->value > $maxY)
    {
        $maxY = $value->value;
    }
}
$lowY = floor($minY/10) * 10;
$hiY = ceil($maxY/10) * 10;

?>
<div class="sensor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created',
            'name',
            'shortname',
            'unit',
            'min_rate',
            'max_rate',
        ],
    ])
    ?>

</div>
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
    //cubicInterpolationMode: 'monotone',
    //tension: 0.1,
  }]
};

const config = {
    type: 'line',
    data,
    options: {
        responsive: true,
        plugins: {
            title: {
                display: false,
                text: 'Sensor data'
            },
        },
        interaction: {
            intersect: false,
        },
      scales: {
        x: {
            display: true,
            title: {
                display: true
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
</script>
