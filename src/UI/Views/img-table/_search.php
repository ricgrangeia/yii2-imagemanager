<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ImageTableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="img-table-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'timestamp') ?>

    <?= $form->field($model, 'path') ?>

    <?= $form->field($model, 'img_name') ?>

    <?= $form->field($model, 'file_type') ?>

    <?php // echo $form->field($model, 'img_name_thumb') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'model_id') ?>

    <?php // echo $form->field($model, 'name_model') ?>

    <?php // echo $form->field($model, 'category_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
