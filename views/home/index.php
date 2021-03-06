<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\HomeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\models\Position;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Homes');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-add-block">
        <?= Html::a(Yii::t('app', 'Create Home'), ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'label' => 'Positions',
                'value' => function ($data)
                {
                    $cnt = Position::find()->where(['home_id' => $data->id])->count();
                    return $cnt ? "<a aria-label='view' data-pjax='0' href='" . Url::base() .
                            "/position/index?PositionSearch%5Bhome_id%5D=" . $data->id . "'>" . $cnt . "</a>":"-";
                },
                'format' => 'raw'
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
