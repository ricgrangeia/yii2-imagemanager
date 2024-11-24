<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \Domain\Entity\Image */
?>
<div class="image-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'path',
            'img_name',
            'file_type',
            'filename',
            'id_model',
            'name_model',
            'img_name_thumb',
            'category',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
