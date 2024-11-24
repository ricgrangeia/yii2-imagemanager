<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ImgCategory */
?>
<div class="img-category-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'descricao',
        ],
    ]) ?>

</div>
