<?php

namespace app\controllers;

use app\models\GenerateForm;
use app\models\search\CardUseSearch;
use app\models\User;
use Yii;
use app\models\Cards;
use app\models\search\CardsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * CardsController implements the CRUD actions for Cards model.
 */
class CardsController extends Controller
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
     * Lists all Cards models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax){
            $genModel = new GenerateForm();
            if ($genModel->load(Yii::$app->request->post()) && $genModel->generateCards()){
                return Json::encode([
                        'success' => true,
                        'error' => false,
                        'message' => 'Генерация карт прошла успешно'
                    ]);
            } else {
                return Json::encode([
                        'success' => false,
                        'error' => true,
                        'message' => 'Ошибка генерации карт.'
                    ]);
            }
        }

        $searchModel = new CardsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $generateFormModel = new GenerateForm();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'generateFormModel' => $generateFormModel,
        ]);
    }

    /**
     * Displays a single Cards model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new CardUseSearch();
        $params = Yii::$app->request->getQueryParams();
        $params['CardUseSearch']['card_id'] = $id;

        $dataProvider = $searchModel->search($params);

        return $this->render('view', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'model' => $this->findModel($id),
            ]);
    }

    /**
     * Creates a new Cards model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cards;

        if ($model->load(Yii::$app->request->post())) {

            $model->creator_id = Yii::$app->user->getId();

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing Cards model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cards model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cards model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Cards the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cards::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
