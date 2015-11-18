<?php

namespace app\controllers;

use app\models\ShopProducts;
use Yii;
use app\models\Orders;
use app\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\base\InvalidCallException;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
	    $modelTemp = $this->findModel($id);
	    $post=Yii::$app->request->post();
	    if (isset($post['new_order']) AND $post['new_order']==1) {
		    $model = new Orders();
		    $versionLast = Orders::find()->where('order_opencart_id=:order_opencart_id',[':order_opencart_id'=>$modelTemp->order_opencart_id])
			    ->orderBy('version DESC')->limit(1)->one();
		    $model->version = $versionLast->version+1;
		    $model->order_opencart_id = $modelTemp->order_opencart_id;
		    $model->client_id = $modelTemp->client_id;
		    $model->status_id = $modelTemp->status_id;
		    $model->save(false);
	    } else {
		    $model = $modelTemp;
	    }


	    $orderProducts = $model->orderProducts;
	    $orderFiles = $model->files;

        if ($model->load($post) && $model->save()) {
            return $this->redirect(['index']);
        } else {
	        $modelFile = new UploadForm();

            return $this->render('update', [
                'model' => $model,
                'modelFile' => $modelFile,
                'orderProducts' => $orderProducts,
                'orderFiles' => $orderFiles,
            ]);
        }
    }

	public function actionAddproduct()
	{
		if (Yii::$app->request->isAjax) {
			$data['success'] = false;

			$get = \Yii::$app->request->get();
			$product = ShopProducts::findOne($get['product_id']);

			$data['html'] = $this->renderPartial('_add_product', [
				'get' => $get,
				'product' => $product,
			]);

			$data['success'] = true;
			return json_encode($data);
		}
		else {
			throw new InvalidCallException("Неверный запрос к OrdersController->actionAddproduct()");
		}
	}


	public function actionAddfile()
	{
		if (Yii::$app->request->isAjax) {
			$data['success'] = false;

			$get = \Yii::$app->request->get();

			$get['file'] = Orders::trunslit($get['filename']);

			$data['html'] = $this->renderPartial('_add_file', [
				'get' => $get,
			]);

			$data['success'] = true;
			return json_encode($data);
		}
		else {
			throw new InvalidCallException("Неверный запрос к OrdersController->actionAddproduct()");
		}
	}

	public function actionUploadfile()
	{
		if (Yii::$app->request->isAjax) {
			$id = intval($_GET['id']);

			$model = new UploadForm();
			$model->file = UploadedFile::getInstance($model, 'file');

			if ($model->upload($id)) {
				// file is uploaded successfully
				return;
			}

			return 'No loading';
		}
		else {
			throw new InvalidCallException("Неверный запрос к OrdersController->actionAddproduct()");
		}
	}

    /**
     * Deletes an existing Orders model.
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
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
