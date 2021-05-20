<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\models\Position;
use app\models\Connection;


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
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            //'position_id',
            [
                'label' => 'Position',
                'value' => function ($data)
                {
                    $model = Position::findOne(['id' => $data->position_id]);
                    return $model->name;
                },
            ],
            'channel',
            //'connection_id',
            [
                'label' => 'Connection',
                'value' => function ($data)
                {
                    $model = Connection::findOne(['id' => $data->connection_id]);
                    return $model->name;
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

<?php Pjax::end(); ?>

</div>
