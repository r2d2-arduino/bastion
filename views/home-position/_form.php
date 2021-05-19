<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HomePosition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="home-position-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'home_id')->dropDownList([
        '0' => 'Активный',
        '1' => 'Отключен',
        '2'=>'Удален'
    ], [
        'prompt' => 'Выберите дом...'
    ] ); ?>

    <?= $form->field($model, 'position_id')->dropDownList([
        '0' => 'Активный',
        '1' => 'Отключен',
        '2'=>'Удален'
    ], [
        'prompt' => 'Выберите позицию...'
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
