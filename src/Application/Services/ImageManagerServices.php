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

namespace ricgrangeia\yii2ImageManager\Application\Services;


use Yii;
use ricgrangeia\yii2ImageManager\Helpers\ModelHelper;
use ricgrangeia\yii2ImageManager\Domain\Entity\Image;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;


class ImageManagerServices {

	public static function addFiles( ActiveRecord $model, string $filesUploadString ): void {

		$uploadedFiles = UploadedFile::getInstances( $model, $filesUploadString );


		// Save the files
		foreach ( $uploadedFiles as $file ) {
			$image = new Image();

			$image->filename = time() . $file->name;
			$image->img_name = $image->filename;

			$image->id_model = $model->id;
			$image->name_model = ModelHelper::getModelName( $model );

			$image->path = $image->name_model . '/' . $image->id_model . '/';

			if ( !is_dir( $imagesMainPath . '/' . $image->path ) ) {
				// Create the directory and set permissions to 0755
				mkdir( $concurrentDirectory = $imagesMainPath . '/' . $image->path, 0755, true ) || !is_dir( $concurrentDirectory );

			}

			$savePath = $imagesMainPath . '/' . $image->path . $image->filename;
			$file->saveAs( $savePath );

			$image->file_type = 'jpg';

			$image->save();

		}

	}


	public static function addFile( int $idModel, string $nameModel, UploadedFile $file ): void {

		// todo: this $imageMainPath shouldn't be so hardcoded, should be given type confs module, not get it.
		$imagesMainPath = Yii::$app->params['IMAGES_FILES_PATH'];

		$image = new Image();

		$image->filename = time() . $file->name;
		$image->img_name = $image->filename;

		$image->id_model = $idModel;
		$image->name_model = $nameModel;

		$image->path = $image->name_model . '/' . $image->id_model . '/';

		if ( !is_dir( $imagesMainPath . '/' . $image->path ) ) {
			// Create the directory and set permissions to 0755
			mkdir( $concurrentDirectory = $imagesMainPath . '/' . $image->path, 0755, true ) || !is_dir( $concurrentDirectory );

		}

		$savePath = $imagesMainPath . '/' . $image->path . $image->filename;
		$file->saveAs( $savePath );

		// todo: is hardcoded please fix
		$image->file_type = 'jpg';

		$image->save();


	}

	public static function getFile( ActiveRecord $model ): string|null {

		$image = Image::find()->where( [ 'id_model' => $model->primaryKey, 'name_model' => ModelHelper::getModelName( $model )  ] )->one();

		return $image?->imageToBase64();

	}


	public static function removeAllFilesOfModel( int $idModel, string $nameModel ): void {

		ImageServices::removeAllOfModel($idModel, $nameModel );

	}

}

