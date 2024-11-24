<?php
/**
 * Created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 31/08/23, 14:39
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */

namespace ricgrangeia\yii2ImageManager\UI\Controllers;

use yii\helpers\Html;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use common\modules\AppController\AppController;


/**
 * ImgCategoryController implements the CRUD actions for ImgCategory model.
 */
final class ImageCategoryController extends AppController
{


    /**
     * Lists all ImgCategory models.
     * @return string
	 */
    public function actionIndex(): string {
        $searchModel = new ImageCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single ImgCategory model.
     * @param integer $id
     * @return string|array
	 */
    public function actionView( int $id): string|array {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "ImgCategory #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                            Html::a(Yii::t('yii2-ajaxcrud', 'Update'), ['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }

		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

    /**
     * Creates a new ImgCategory model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new \common\modules\ImageManager\Application\ImgCategory();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> 'Create New'." ImgCategory",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close', ['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Create', ['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }

			if($model->load($request->post()) && $model->save()){
				return [
					'forceReload'=>'#crud-datatable-pjax',
					'title'=> Yii::t('yii2-ajaxcrud', 'Create New')." ImgCategory",
					'content'=>'<span class="text-success">'. 'Create'.' ImgCategory '.'Success'.'</span>',
					'footer'=> Html::button( 'Close', ['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
							Html::a('Create More', ['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

				];
			}

			return [
				'title'=> Yii::t('yii2-ajaxcrud', 'Create New')." ImgCategory",
				'content'=>$this->renderAjax('create', [
					'model' => $model,
				]),
				'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
							Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class'=>'btn btn-primary','type'=>"submit"])

			];
		}

		/*
				*   Process for non-ajax request
				*/
		if ($model->load($request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);

	}

    /**
     * Updates an existing ImgCategory model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        /** @var \common\modules\ImageManager\Application\ImgCategory $model */
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> Yii::t('yii2-ajaxcrud', 'Update')." ". Yii::t('module-img', 'Category')." ".$model->descricao,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                                Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }

			if($model->load($request->post()) && $model->save()){
				return [
					'forceReload'=>'#crud-datatable-pjax',
					'title'=> "ImgCategory #".$id,
					'content'=>$this->renderAjax('view', [
						'model' => $model,
					]),
					'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
							Html::a(Yii::t('yii2-ajaxcrud', 'Update'), ['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
				];
			}

			return [
			   'title'=> Yii::t('yii2-ajaxcrud', 'Update')." ImgCategory #".$id,
			   'content'=>$this->renderAjax('update', [
				   'model' => $model,
			   ]),
			   'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
						   Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class'=>'btn btn-primary','type'=>"submit"])
		   ];
		}

		/*
				*   Process for non-ajax request
				*/
		if ($model->load($request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

    /**
     * Delete an existing ImgCategory model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return array|\Response
	 */
    public function actionDelete( int $id): array|\Response {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }

		/*
				*   Process for non-ajax request
				*/

		return $this->redirect(['index']);


	}

     /**
     * Delete multiple existing ImgCategory model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkdelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the ImgCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return \common\modules\ImageManager\Application\ImgCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = \common\modules\ImageManager\Application\ImgCategory::findOne($id)) !== null) {
            return $model;
        }

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
