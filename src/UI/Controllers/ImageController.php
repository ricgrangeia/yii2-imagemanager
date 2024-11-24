<?php

namespace ricgrangeia\yii2ImageManager\UI\Controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use ricgrangeia\yii2ImageManager\Helpers\UIHelper;
use ricgrangeia\yii2ImageManager\Domain\Entity\Image;
use ricgrangeia\yii2ImageManager\Domain\Entity\ImageSearch;
use ricgrangeia\yii2ImageManager\Application\Services\ImageServices;


/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Image model.
     * @param int $id ID
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Image #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> UIHelper::UICrudFooterUpdate( $id ),
                ];    
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);

    }

    /**
     * Creates a new Image model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Image();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> Yii::t('yii2-ajaxcrud', 'Create New')." Image",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> UIHelper::UICrudFooterCreate(),

                ];         
            }

            if($model->load($request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', Yii::t('yii2-ajaxcrud', 'Successfully Saved!') );
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> Yii::t('yii2-ajaxcrud', 'Create New')." Image",
                    'content'=>'<span class="text-success">'.Yii::t('yii2-ajaxcrud', 'Create').' Image '.Yii::t('yii2-ajaxcrud', 'Success').'</span>',
                    'footer'=> UIHelper::UICrudFooterCreateMore(),
        
                ];         
            }
                return [
                    'title'=> Yii::t('yii2-ajaxcrud', 'Create New')." Image",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> UIHelper::UICrudFooterSave(),
        
                ];         

        }
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('yii2-ajaxcrud', 'Successfully Saved!'));
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);


       
    }

	public function actionGet(): Response {

		$request = Yii::$app->request;

		if(!$request->getIsPost()){
			return $this->asJson(['success' => false, 'message' => 'Request is not POST']);
		}

		if($request->post('modelId') === null || $request->post('className') === null){
			return $this->asJson(['success' => false, 'message' => 'ModelId or ClassName not found']);
		}

		$modelId = $request->post('modelId');
		$className = $request->post('className');

		$imageService = new ImageServices();
		$image = $imageService->getImageByModelIdAndClassName( $modelId, $className );


		// Ensure the image was found
		if ($image === null) {
			return $this->asJson(['success' => false, 'message' => 'Image not found']);
		}

		$imageFile = $image->getFullPathFilename();
		$imageData = base64_encode(file_get_contents($imageFile));

		return $this->asJson(['success' => true, 'imageData' => $imageData]);
	}

    /**
     * Updates an existing Image model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> Yii::t('yii2-ajaxcrud', 'Update')." Image #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> UIHelper::UICrudFooterSave(),
                ];         
            }

            if($model->load($request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', Yii::t('yii2-ajaxcrud', 'Successfully Saved!'));
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Image #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> UIHelper::UICrudFooterUpdate( $id ),
                ];    
            }
                 return [
                    'title'=> Yii::t('yii2-ajaxcrud', 'Update')." Image #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> UIHelper::UICrudFooterSave(),
                ];        

        }
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('yii2-ajaxcrud', 'Successfully Saved!'));
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);


    }

    /**
     * Delete an existing Image model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', Yii::t('yii2-ajaxcrud', 'Successfully Deleted!'));
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
     * Delete multiple existing Image model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
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
        Yii::$app->session->setFlash('success', Yii::t('yii2-ajaxcrud', 'Successfully Deleted!'));
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
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');

    }
}
