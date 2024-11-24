<?php
use yii\helpers\Url;
use kartik\grid\DataColumn;
use kartik\grid\SerialColumn;
use kartik\grid\CheckboxColumn;

return [
    [
        'class' => CheckboxColumn::class,
        'width' => '20px',
    ],
    [
        'class' => SerialColumn::class,
        'width' => '30px',
    ],
    [
         'class'=>DataColumn::class,
         'attribute'=>'id',
         'visible' => false,
    ],
    [
        'class'=>DataColumn::class,
        'attribute'=>'path',
    ],
    [
        'class'=>DataColumn::class,
        'attribute'=>'img_name',
    ],
    [
        'class'=>DataColumn::class,
        'attribute'=>'file_type',
    ],
    [
        'class'=>DataColumn::class,
        'attribute'=>'filename',
    ],
    [
        'class'=>DataColumn::class,
        'attribute'=>'id_model',
    ],
[
        'class'=>DataColumn::class,
        'attribute'=>'name_model',
        'visible' => false,
   ],
[
        'class'=>DataColumn::class,
        'attribute'=>'img_name_thumb',
        'visible' => false,
   ],
[
        'class'=>DataColumn::class,
        'attribute'=>'category',
        'visible' => false,
   ],
[
         'class'=>DataColumn::class,
         'attribute'=>'created_at',
         'visible' => false,
    ],
[
         'class'=>DataColumn::class,
         'attribute'=>'updated_at',
         'visible' => false,
    ],
[
        'class'=>DataColumn::class,
        'attribute'=>'created_by',
        'visible' => false,
   ],
[
        'class'=>DataColumn::class,
        'attribute'=>'updated_by',
        'visible' => false,
   ],
    \common\Helpers\UIHelper::UICrudGridActions(),

];   