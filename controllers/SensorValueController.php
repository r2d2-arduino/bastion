<?php

namespace app\controllers;

use Yii;
use app\models\SensorValue;
use app\models\SensorValueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

//Model::find()->where(['in', 'id', [1,2]])->all();
//Table::find()->where('id > :id', [':id' => '2']])->all();
//Table::find()->where([’>’, ‘id’, ‘2’])->all();

/**
 * SensorValueController implements the CRUD actions for SensorValue model.
 */
class SensorValueController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SensorValue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SensorValueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SensorValue model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SensorValue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SensorValue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SensorValue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SensorValue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionActual()
    {
        $sensor_id = Yii::$app->request->post('sensor_id', 0);
        $device_id = Yii::$app->request->post('device_id', 0);
        
        $sensorValues = [];
        $sensors = \app\models\Sensor::find()->select(['id'])->where(['user_id' => Yii::$app->user->id])->all();
        
        $now = (new \yii\db\Query)->select( new yii\db\Expression('NOW()') )->scalar();
        
        foreach ($sensors as $sensor)
        {
            $senVal = SensorValue::find()->select(['sensor_id', 'created', 'value'])->where(['sensor_id' => $sensor->id])->orderBy('id desc')->limit(1)->one();
            if (self::getDiffInSeconds($now, $senVal->created) < 60)
            {
                $sensorValues[] = SensorValue::find()->select(['sensor_id', 'created', 'value'])->where(['sensor_id' => $sensor->id])->orderBy('id desc')->limit(1)->one();
            }
        }
                
        echo \yii\helpers\Json::encode($sensorValues);
    }
    
     /**
     * Finds the SensorValue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SensorValue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SensorValue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    public static function getDiffInSeconds( $dateFirst, $dateSecond = null )
    {
        //$datetime1 = new DateTime($dateFirst);
        //$datetime2 = new DateTime($dateSecond);
        //$interval = $datetime1->diff($datetime2);

        $timeFirst = strtotime($dateFirst);
        if ($dateSecond)
        {
            $timeSecond = strtotime($dateSecond);
        }
        else
        {
            $timeSecond = time();
        }

        return ($timeFirst - $timeSecond);
    }
    
    
}
