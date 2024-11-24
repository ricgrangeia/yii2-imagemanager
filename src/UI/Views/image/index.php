<?php

use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\dynagrid\DynaGrid;
use yii2ajaxcrud\ajaxcrud\CrudAsset;
use yii2ajaxcrud\ajaxcrud\BulkButtonWidget;
use common\modules\ImageManager\ModuleImageManager;

/* @var $this yii\web\View */
/* @var $searchModel \Domain\Entity\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ModuleImageManager::t('module-imagemanager', 'Images');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="image-index">
    <div id="ajaxCrudDatatable">
        <?=DynaGrid::widget([
            'options' => [ 'id' => 'crud-datatable' ], // a unique identifier is important
            'columns' => require(__DIR__.'/_columns.php'),
            'theme' => 'panel-info',
            'showPersonalize' => true,
            'storage' => 'session',
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'floatPageSummary' => true, // table page summary floats when you scroll
                'floatFooter' => false, // disable floating of table footer
                'showPageSummary' => true,
                'responsiveWrap' => false,
                'pjax'=>true,
                'toolbar'=> [
                    ['content'=>
                        Html::a(Yii::t('yii2-ajaxcrud', 'Create New'), ['create'],
                        ['role'=>'modal-remote','title'=> Yii::t('yii2-ajaxcrud', 'Create New').' Images','class'=>'btn btn-outline-primary']).
                        Html::a('<i class="fa fa-redo"></i>', [''],
                        ['data-pjax'=>1, 'class'=>'btn btn-outline-success', 'title' => Yii::t('yii2-ajaxcrud', 'Reset Grid')]).
                        '{toggleData}'

                    ],
                    ['content'=>'{dynagrid}'],
                    '{export}'
                ],
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'default',
                    'heading' => '<i class="fa fa-list"></i> <b>'.$this->title.'</b>',
                    'before'=>'<em>* '.Yii::t('yii2-ajaxcrud', 'Resize Column').'</em>',
                    'after'=>BulkButtonWidget::widget([
                                'buttons'=>Html::a('<i class="fa fa-trash"></i>&nbsp; '.Yii::t('yii2-ajaxcrud', 'Delete All'),
                                    ["bulkdelete"] ,
                                    [
                                        "class"=>"btn btn-danger btn-xs",
                                        'role'=>'modal-remote-bulk',
                                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                        'data-request-method'=>'post',
                                        'data-confirm-title' => Yii::t('yii2-ajaxcrud', 'Delete'),
                                        'data-confirm-message' => Yii::t('yii2-ajaxcrud', 'Delete Confirm')
                                    ]),
                            ]).
                            '<div class="clearfix"></div>',
                ],
            ],
        ])?>
    </div>
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
