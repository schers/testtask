<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\CardUse;
use app\models\search\CardUseSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CardUseController implements the CRUD actions for CardUse model.
 */
class CardUseController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [User::ROLE_SUPERADMIN, User::ROLE_ADMIN],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CardUse models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CardUseSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CardUse model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CardUse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param $cid
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate($cid)
    {
        $model = new CardUse;

        $model->card_id = $cid;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/cards/view', 'id' => $model->card->id]);
        } else {
            $model->date_use = date('Y-m-d H:i');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CardUse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/cards/view', 'id' => $model->card->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CardUse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/cards/index']);
    }

    /**
     * Finds the CardUse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CardUse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CardUse::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
