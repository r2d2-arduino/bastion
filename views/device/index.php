<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\models\Position;
use app\models\Connection;
use app\models\DeviceSensor;

$this->title = Yii::t('app', 'Devices');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div  class="btn-add-block" >
<?= Html::a(Yii::t('app', 'Create Device'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'label' => 'Position',
                'value' => function ($data)
                {
                    $model = Position::findOne(['id' => $data->position_id]);
                    return $model ? $model->name : '--deleted--';
                },
            ],
            'channel',
            [
                'label' => 'Connection',
                'value' => function ($data)
                {
                    $model = Connection::findOne(['id' => $data->connection_id]);
                    return $model ? $model->name : '--deleted--';
                },
            ],
            [
                'label' => 'Sensors',
                'value' => function ($data)
                {
                    $cnt = DeviceSensor::find()->where(['device_id' => $data->id])->count();
                    return $cnt;
                },
            ],
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
