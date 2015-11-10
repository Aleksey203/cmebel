<?php

namespace app\controllers;

use app\models\ShopCategories;
use Yii;
use app\models\ShopProducts;
use app\models\ShopProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ShopProductsController implements the CRUD actions for ShopProducts model.
 */
class ShopProductsController extends Controller
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
			            'roles' => ['@'],
			            'matchCallback' => function () {
				            return Yii::$app->user->identity->getIsAdmin();
			            },
		            ],
	            ],
            ],
        ];
    }

    /**
     * Lists all ShopProducts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopProducts model.
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
     * Creates a new ShopProducts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopProducts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ShopProducts model.
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
     * Deletes an existing ShopProducts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

	public function actionTree()
	{
		if (Yii::$app->request->isAjax) {
			$data['success'] = false;

			$post = \Yii::$app->request->post();
			$products = ShopProducts::find()->where('category_id='.$post['category_id'])->all();

			$data['html'] = $this->renderPartial('_products', [
				'products' => $products,
			]);

			$data['success'] = true;
			return json_encode($data);
		}
		else {
			$products = ShopProducts::find()->all();

			$categoriesTree = ShopCategories::getTree();

			return $this->render('tree', [
				'products' => $products,
				'categoriesTree' => $categoriesTree,
			]);
		}
	}

    /**
     * Finds the ShopProducts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ShopProducts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopProducts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
