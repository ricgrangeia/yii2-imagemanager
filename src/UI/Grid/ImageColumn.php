<?php

namespace ricgrangeia\yii2ImageManager\UI\Grid;

use yii\helpers\Html;
use yii\grid\DataColumn;
use Application\Services\ImageManagerServices;
use common\modules\ImageManager\ModuleImageManager;

class ImageColumn extends DataColumn {

	/**
	 * @var string the attribute to use for fetching the image (e.g., base64 or URL)
	 */
	public string $imageAttribute = 'image';

	/**
	 * @var array HTML options for the image tag (e.g., size, class)
	 */
	public array $imageOptions = [ 'width' => '50px', 'height' => '50px' ];

	/**
	 * @var string fallback text if no image is available
	 */
	public string $noImageText = 'No Image';

	/**
	 * Renders the content of a data cell.
	 * This method will render an image if available, or fallback text if no image is found.
	 *
	 * @param mixed $model the data model
	 * @param mixed $key the key associated with the data model
	 * @param int $index the zero-based index of the data item among the items array returned by [[dataProvider]].
	 * @return string the rendering result
	 */
	public function renderDataCellContent( $model, $key, $index ): string {

		// Use ImageManagerServices to get the image data (base64 or URL)
		$image = ImageManagerServices::getFile( $model );

		if ( !empty( $image ) ) {
			// Render the image using Html::img()
			return Html::img( $image, $this->imageOptions );
		}

		// Fallback if no image is available
		return ModuleImageManager::t( 'module-imagemanager', $this->noImageText );
	}
}
