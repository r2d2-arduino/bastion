<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SensorValueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\models\Sensor;
use app\models\Device;

$this->title = Yii::t('app', 'Sensor Values');
//$this->params['breadcrumbs'][] = $this->title;


?>
<div class="sensor-value-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- div  class="btn-add-block" >
    <?= Html::a(Yii::t('app', 'Create Sensor Value'), ['create'], ['class' => 'btn btn-success']) ?>
    </div -->

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'created',
            [
                'label' => 'Sensor',
                'value' => function ($data)
                {
                    $model = Sensor::find()->select(['name'])->where(['id' => $data->sensor_id])->one();
                    return $model ? $model->name : '-- deleted --';
                },
            ],
            [
                'label' => 'Device',
                'value' => function ($data)
                {
                    $model = Device::find()->select(['name'])->where(['id' => $data->device_id])->one();
                    return $model ? $model->name : '-- deleted --';
                },
            ],
            'value',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttonOptions' => ['class' => 'btn btn-default'],
                'contentOptions' => ['style' => 'width: 150px'],
            ],
        ],
    ]);
    ?>

<?php Pjax::end(); ?>

</div>
