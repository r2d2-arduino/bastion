<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Home;

/* @var $this yii\web\View */
/* @var $model app\models\Position */
/* @var $form yii\widgets\ActiveForm */
$homes = Home::findAll(['user_id' => Yii::$app->user->id]);

$homeList = [];
foreach ($homes as $home)
{
    $homeList[$home->id] = $home->name;
}
?>

<div class="position-form form-block">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'home_id')->dropDownList($homeList, ['prompt' => 'Выберите дом...'] ); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
