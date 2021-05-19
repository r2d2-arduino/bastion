<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\HomePositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use \app\models\Home;
use \app\models\Position;

$this->title = Yii::t('app', 'Home Positions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-position-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Home Position'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'home_id',
            'position_id',
            [
                'label' => 'Home',
                'value' => function ($data) {
                    $model = Home::find()->where( [ 'id' => $data->home_id ] )->one();
                    return $model->name; 
                },
            ],
            [
                'label' => 'Position',
                'value' => function ($data) {
                    $model = Position::find()->where( [ 'id' => $data->position_id ] )->one();
                    return $model->name; 
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
