<?php
/**
 * Created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 31/08/23, 15:11
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $imageData string */
?>

<div class="image-get">

	<?= Html::img('data:image/jpeg;base64,'. $imageData)?>

</div>