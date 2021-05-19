<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\HomeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\models\User;
use app\models\HomePosition;

$this->title = Yii::t('app', 'Homes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Home'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'name',
            [
                'label' => 'User',
                'value' => function ($data)
                {
                    $model = User::find(['id' => $data->user_id])->one();
                    return $model->username;
                },
            ],
            [
                'label' => 'Positions',
                'value' => function ($data)
                {
                    $cnt = HomePosition::find()->where(['home_id' => $data->id])->count();
                    return "<a href='/bastion/web/index.php?r=home-position&HomePositionSearch%5Bhome_id%5D=".$data->id."'>".$cnt."</a>";
                },
                'format' => 'raw'
            ],            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
