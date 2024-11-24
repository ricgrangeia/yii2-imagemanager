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

use Yii;
use yii\helpers\Html;
use yii\web\Response;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\VarDumper;
use yii\filters\VerbFilter;
use common\Helpers\MyFunctions;
use yii\web\NotFoundHttpException;
use common\modules\ImageManager\UI\Controllers\ImgTable;
use common\modules\ImageManager\Domain\Entity\ImageTable\ImageTableSearch;

/**
 * ImageTableController implements the CRUD actions for ImageTable model.
 */
class ImageTableController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ImageTable models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ImageTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single ImageTable model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "ImageTable #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                            Html::a(Yii::t('yii2-ajaxcrud', 'Update'), ['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new ImageTable model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new ImgTable();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> Yii::t('yii2-ajaxcrud', 'Create New')." ImageTable",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                                Html::button(Yii::t('yii2-ajaxcrud', 'Create'), ['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> Yii::t('yii2-ajaxcrud', 'Create New')." ImageTable",
                    'content'=>'<span class="text-success">'.Yii::t('yii2-ajaxcrud', 'Create').' ImageTable '.Yii::t('yii2-ajaxcrud', 'Success').'</span>',
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                            Html::a(Yii::t('yii2-ajaxcrud', 'Create More'), ['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> Yii::t('yii2-ajaxcrud', 'Create New')." ImageTable",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                                Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing ImageTable model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
                    'title'=> Yii::t('yii2-ajaxcrud', 'Update')." ImageTable #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                                Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "ImageTable #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                            Html::a(Yii::t('yii2-ajaxcrud', 'Update'), ['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> Yii::t('yii2-ajaxcrud', 'Update')." ImageTable #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(Yii::t('yii2-ajaxcrud', 'Close'), ['class'=>'btn btn-default pull-left', 'data-dismiss'=>"modal", 'data-bs-dismiss'=>"modal"]).
                                Html::button(Yii::t('yii2-ajaxcrud', 'Save'), ['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing ImageTable model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

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
     * Delete multiple existing ImageTable model.
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
     * Finds the ImageTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ImgTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ImgTable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    //public static function actionUpload($model, $imgFicheiros, $foto = false, $categoryUpload = null)
    public static function actionUpload()
    {
        VarDumper::dump($_POST);
        VarDumper::dump($_GET);
        VarDumper::dump($_FILES['imgFicheiros']);

        if (isset($_FILES['imgFicheiros']) and isset($_GET['class'])) {
            /**
             * Se recebeu ficheiros, vai se ver se é foto ou grupo de ficheiros.
             */
            $class = str_replace(__NAMESPACE__ . '\\', '', $_GET['class']);;
            $ficheirosUpload = UploadedFile::getInstancesByName('imgFicheiros');



            VarDumper::dump($ficheirosUpload);

            /**
             * Tipos de variáveis a usar
             * @var ImgTable $ficheiro
             * @var ImgTable $modelImg
             */

            foreach ($ficheirosUpload as $key => $ficheiro) {
                /**
                 * Agora vai se tratar de cada ficheiro caso haja mais que um.
                 * Se for do tipo foto trata-se de uma forma, for imagem documental ou pdf trata-se de outra forma.
                 */

                if (empty($modelImg)){
                    /**
                     * Não existe, tratamento de nova imagem.
                     */
                    $modelImg = new ImgTable();
                } else {
                    /**
                     * Já existe, guarda-se temporariamente a antiga.
                     */
                    $oldImg = clone $modelImg;
                }

                /**
                 * Dados necessários para imagem
                 * que necessitam de ser gravados
                 * - para cada imagem documental ou pdf é criado um nome de ficheiro único,
                 * com uso da data e número aleatório, classe do modela e id do modelo.
                 */
                $modelImg->timestamp = date("Y-m-d H:i:s");
                $modelImg->path = Yii::getRootAlias('application') . "/images/$class/" . $model->id . '/';
                $randomName = date('YmdHis') . rand(1000000, 9999999) . get_class($model)."_".$model->id;
                $modelImg->imgName = $randomName . '.' . strtolower($ficheiro->extensionName);
                $modelImg->fileType = strtolower($ficheiro->extensionName);

                if(get_class($model) === 'Funcionarios') {
                    /**
                     * Quando a class é Funcionarios,
                     * adiciona-se informações à descrição do ficheiro e o ano.mes.
                     */
//                    $empresaNome = Funcionarios::model()->findByPk($model->id)->idLoja0->idEmpresa0->shortName;
//                    $modelImg->name = date("Y.m - ")."$empresaNome - ".$ficheiro->name;
                } else {
                    /**
                     * Quando a class é outra qualquer class,
                     * adiciona-se à descrição do ficheiro e o ano.mes.
                     */
                    $modelImg->name = date("Y.m - ") . $ficheiro->name;
                }

                $modelImg->idModel = $model->id;


                if(!empty($categoryUpload))
                    $modelImg->category = $categoryUpload;

                if (in_array($modelImg->fileType, array('jpg', 'jpeg', 'png'))) {
                    /**
                     * Identificar o tipo de ficheiro.
                     */
                    $modelImg->imgNameThumb = $randomName . '_thumb.' . $modelImg->fileType;
                    if ($foto) {
                        /**
                         * Quando foto auto atribui a categoria
                         */
                        $modelImg->category = 8;
                    }
                }

                $modelImg->nameModel = get_class($model);

                if ($modelImg->save()) {

                    if (!is_dir($modelImg->path)) {
                        /**
                         * Se não existir pasta de ficheiros, é criada
                         */
                        mkdir($modelImg->path, 0755, true);
                    }

                    /**
                     * Grava-se o ficheiro
                     */

                    $ficheiro->saveAs($modelImg->path . $modelImg->imgName, false);

                    if (in_array($modelImg->fileType, array('jpg', 'jpeg', 'png'))) {
                        /**
                         * Caso seja tipo imagem
                         * Corrige-se a orientação da imagem e redimensiona-se para ficar menos pesada em disco.
                         */
                        MyFunctions::image_fix_orientation($modelImg->path . $modelImg->imgName);
                        self::resize(1400, $modelImg->path . $modelImg->imgName, $modelImg->path . $modelImg->imgName);

                    }

                    if (in_array($modelImg->fileType, array('jpg', 'jpeg', 'png'))) {
                        /**
                         * Caso seja tipo imagem
                         * Cria-se um thumb da imagem
                         */
                        $ficheiro->saveAs($modelImg->path . $modelImg->imgNameThumb);
                        MyFunctions::image_fix_orientation($modelImg->path . $modelImg->imgNameThumb);
                        self::resize(80, $modelImg->path . $modelImg->imgNameThumb, $modelImg->path . $modelImg->imgNameThumb);
                    }


                    if ($foto and !empty($oldImg)) {
                        /**
                         * Se é foto e já existia outra foto, elimina a antiga.
                         */
                        self::deleteImgFile($oldImg);
                    }
                    unset($modelImg);

                }

            }
        }
    }
}
