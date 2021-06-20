<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Home;

/* @var $this yii\web\View */
/* @var $model app\models\PositionSearch */
/* @var $form yii\widgets\ActiveForm */
$homes = Home::findAll(['user_id' => Yii::$app->user->id]);

$homeList = [];
foreach ($homes as $home)
{
    $homeList[$home->id] = $home->name;
}
?>

<div class="position-search search-block">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php //= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>
    
    <?= $form->field($model, 'home_id')->dropDownList($homeList, ['prompt' => 'Выберите дом...']); ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
