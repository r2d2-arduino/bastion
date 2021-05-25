<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Home;
use app\models\Device;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Positions');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div  class="btn-add-block" >
        <?= Html::a(Yii::t('app', 'Create Position'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php Pjax::begin(); ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'label' => 'Home',
                'value' => function ($data)
                {
                    $model = Home::findOne(['id' => $data->home_id]);
                    return $model->name;
                },
            ],
            [
                'label' => 'Devices',
                'value' => function ($data)
                {
                    $cnt = Device::find()->where(['position_id' => $data->id])->count();
                    return $cnt ? "<a aria-label='view' data-pjax='0' href='" . Url::base() .
                            "/index.php?r=device%2Findex&DeviceSearch%5Bname%5D=&DeviceSearch%5Bposition_id%5D=" . $data->id . "'>" . $cnt . "</a>" : "-";
                },
                'format' => 'raw'
            ],                        
            [
                'class' => 'yii\grid\ActionColumn',
                'buttonOptions' => ['class' => 'btn btn-default'],
                'contentOptions' => ['style' => 'width: 150px'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
