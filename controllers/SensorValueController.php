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
        
        if ($sensor_id)
        {
            $maxIds[] = SensorValue::find()->where(['sensor_id' => $sensor_id])->max();
        }
        {
            $maxIds = SensorValue::find()->select(['MAX(id) as id'])->groupBy(['sensor_id'])->column();
        }
        
        $sensors = [];
        
        if ($maxIds)
        {
            foreach ($maxIds as $sid)
            {
                $sensors[] = SensorValue::find()->select(['sensor_id', 'value'])->where(['id' => (int) $sid])->one();
            }
        }
        
        echo \yii\helpers\Json::encode($sensors);
    }

    
    public function actionTest()
    {
        echo 'test1';
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
    
    
}
