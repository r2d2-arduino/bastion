<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HomePosition */

$this->title = Yii::t('app', 'Create Home Position');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Home Positions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-position-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
