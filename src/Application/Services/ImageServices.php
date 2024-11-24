<?php
/**
 * Created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 31/08/23, 16:54
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */

namespace Application\Services;

use Yii;
use Domain\Entity\Image;
use kartik\form\ActiveForm;
use common\Helpers\FileHelper;
use yii\db\StaleObjectException;
use common\modules\ImageManager\Domain\Repository\ImageRepositoryInterface;
use common\modules\ImageManager\Infrastructure\Repository\Yii2ImageRepository;

class ImageServices {

	private ImageRepositoryInterface $imageRepository;

	public function __construct() {

		$this->imageRepository = new Yii2ImageRepository();
	}

	public function getImageById( int $id ): ?Image {

		return $this->imageRepository->findById( $id );
	}


	public function saveImage( Image $image ): bool {

		return $this->imageRepository->save( $image );
	}

	public function getImageIdByModelIdAndClassName( int $modelId, string $className ): ?int {

		return $this->imageRepository->findByModelIdAndClassName( $modelId, $className )?->getId();
	}

	public function getImageByModelIdAndClassName( int $modelId, string $className ): ?Image {

		return $this->imageRepository->findByModelIdAndClassName( $modelId, $className );
	}

	public function getHtmlImgTag( int $modelId, string $className, string $class = '', string $style = '' ): string {


		$image = $this->getImageByModelIdAndClassName( $modelId, $className );
		if ( $image ) {
			return $image->htmlImgTagWithBase64( $class, $style );
		}

		return '';
	}

	public static function getImageBase64( int $imageID ): ?string {

		return Image::findOne( $imageID )?->imageToBase64();

	}

	/**
	 * @throws StaleObjectException
	 * @throws \Throwable
	 */
	public static function deleteImage( int $imageId ): bool|int {

		$image = Image::findOne( $imageId );

		if ( $image ) {
			return $image->delete();
		}

		return false;

	}


	public static function removeAllOfModel( int $idModel, string $nameModel ): bool {

		$imagesMainPath = Yii::$app->params['IMAGES_FILES_PATH'];

		$images = Image::find()->where( [ 'id_model' => $idModel, 'name_model' => $nameModel ] )->all();
		$fullPath = '';

		if ( $images ) {
			foreach ( $images as $image ) {
				assert( $image instanceof Image );
				$fullPath = $imagesMainPath . '/' . $image->path;
				$image->delete();
			}

			FileHelper::removeDirectory( $fullPath );

			return true;
		}

		return false;

	}


}