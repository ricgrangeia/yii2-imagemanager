<?php

use Domain\Entity\Image;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use yii2ajaxcrud\ajaxcrud\CrudAsset;
use yii2ajaxcrud\ajaxcrud\BulkButtonWidget;
use common\modules\ImageManager\ModuleImageManager;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImageCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ModuleImageManager::t('module-imagemanager', 'Img Categories');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="img-category-index">
    <?php
        foreach ($dataProvider->getModels() as  $model){
			assert( $model instanceof Image );
           \yii\helpers\VarDumper::dump( $model );
           echo $model->htmlImgTagWithBase64();

    }
    ?>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "", // always need it for jquery plugin
    "clientOptions" => [
        "tabindex" => false,
        "backdrop" => "static",
        "keyboard" => false,
    ],
    "options" => [
        "tabindex" => false
    ]
])?>
<?php Modal::end(); ?>
