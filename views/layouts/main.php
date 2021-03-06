<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

/*use app\models\Sensor;
use app\models\Home;
use app\models\Position;
use app\models\Device;*/

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            
            $items = [
               // ['label' => 'Main', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
                ['label' => 'Login', 'url' => ['/site/login']],
            ];
            if (!Yii::$app->user->isGuest)
            {
                /*$hCnt = Home::find()->where(['user_id' => Yii::$app->user->id])->count();
                $pCnt = Position::find()->where(['user_id' => Yii::$app->user->id])->count();
                $dCnt = Device::find()->where(['user_id' => Yii::$app->user->id])->count();
                $sCnt = Sensor::find()->where(['user_id' => Yii::$app->user->id])->count();*/
                
                $items = [
                   // ['label' => 'Main', 'url' => ['/site/index']],
                    //['label' => 'User', 'url' => ['/user/index']],
                    ['label' => 'Homes', 'url' => ['/home/index']],
                    ['label' => 'Positions', 'url' => ['/position/index']],
                    ['label' => 'Devices', 'url' => ['/device/index']],
                    ['label' => 'Sensors', 'url' => ['/sensor/index']],
                    ['label' => 'Connections', 'url' => ['/connection/index']],
                    ['label' => 'Values', 'url' => ['/sensor-value/index']],
                    '<li>'. Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton('Logout (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn btn-link logout'])
                    . Html::endForm(). '</li>'
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $items,
            ]);
            NavBar::end();
            ?>

            <div class="container">
            <?=
            Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'homeLink' => [
                    'label' => Yii::$app->name,
                    'url' => '/',
                ],
            ])
            ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; Bastion <?= date('Y') ?></p>
            </div>
        </footer>

<?php $this->endBody() ?>
    </body>
</html>
        <?php $this->endPage() ?>
