<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Position;
use app\models\Connection;

/* @var $this yii\web\View */
/* @var $model app\models\Device */
/* @var $form yii\widgets\ActiveForm */

$positions = Position::findAll(['user_id' => Yii::$app->user->id]);

$posList = [];
foreach ( $positions as $pos )
{
    $posList[$pos->id] = $pos->name;
}

$connections = Connection::find()->all();

$connList = [];
foreach ( $connections as $conn )
{
    $connList[$conn->id] = $conn->name;
}

$channelList = ['RECEIVER' => 'RECEIVER', 'TRANSMITTER' => 'TRANSMITTER', 'DUAL' => 'DUAL',];
?>

<div class="device-form form-block">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position_id')->dropDownList($posList, ['prompt' => 'Выберите позицию...']); ?>

    <?= $form->field($model, 'channel')->dropDownList($channelList, ['prompt' => 'Выберите тип канала...']) ?>

    <?= $form->field($model, 'connection_id')->dropDownList($connList, ['prompt' => 'Выберите соединение...']); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
