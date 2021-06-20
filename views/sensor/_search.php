<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SensorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sensor-search search-block">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?php //= $form->field($model, 'created') ?>

    <?= $form->field($model, 'name') ?>

    <?php //= $form->field($model, 'shortname') ?>

    <?php //= $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'min_rate') ?>

    <?php // echo $form->field($model, 'max_rate') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
