<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ImgTable */
?>
<div class="img-table-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'timestamp',
            'path',
            'img_name',
            'file_type',
            'img_name_thumb',
            'name',
            'model_id',
            'name_model',
            'category_id',
        ],
    ]) ?>

</div>
