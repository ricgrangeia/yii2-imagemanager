<?php

namespace Domain\Entity;

use Yii;
use common\Helpers\FileHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\modules\ImageManager\ModuleImageManager;

/**
 * This is the model class for table "imagemanager_image".
 *
 * @property int $id
 * @property string $path
 * @property string $img_name
 * @property string $file_type
 * @property string $filename
 * @property int $id_model
 * @property string $name_model
 * @property string|null $img_name_thumb
 * @property string|null $category
 * @property int|null $created_at Created At
 * @property int|null $updated_at Updated At
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 */
class Image extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {

		return 'imagemanager_image';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array {

		return [
			[
				'class' => TimestampBehavior::class,
			],
			[
				'class' => BlameableBehavior::class,
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {

		return [
			[ [ 'path', 'img_name', 'file_type', 'filename', 'name_model' ], 'required' ],
			[ [ 'created_at', 'updated_at', 'created_by', 'updated_by', 'id_model' ], 'integer' ],
			[ [ 'path', 'img_name', 'filename', 'name_model', 'img_name_thumb', 'category' ], 'string', 'max' => 250 ],
			[ [ 'file_type' ], 'string', 'max' => 10 ],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {

		return [
			'id' => ModuleImageManager::t( 'module-imagemanager', 'ID' ),
			'path' => ModuleImageManager::t( 'module-imagemanager', 'Path' ),
			'img_name' => ModuleImageManager::t( 'module-imagemanager', 'Img Name' ),
			'file_type' => ModuleImageManager::t( 'module-imagemanager', 'File Type' ),
			'filename' => ModuleImageManager::t( 'module-imagemanager', 'Filename' ),
			'id_model' => ModuleImageManager::t( 'module-imagemanager', 'Id Model' ),
			'name_model' => ModuleImageManager::t( 'module-imagemanager', 'Name Model' ),
			'img_name_thumb' => ModuleImageManager::t( 'module-imagemanager', 'Img Name Thumb' ),
			'category' => ModuleImageManager::t( 'module-imagemanager', 'Category' ),
			'created_at' => ModuleImageManager::t( 'module-imagemanager', 'Created At' ),
			'updated_at' => ModuleImageManager::t( 'module-imagemanager', 'Updated At' ),
			'created_by' => ModuleImageManager::t( 'module-imagemanager', 'Created By' ),
			'updated_by' => ModuleImageManager::t( 'module-imagemanager', 'Updated By' ),
		];
	}

	public function getFullPathFilename(): string {

		$imagesMainPath = Yii::$app->params['IMAGES_FILES_PATH'];

		return $imagesMainPath . '/' . $this->name_model . '/' . $this->id_model . '/' . $this->img_name;
	}

	public function imageToBase64(): string {

		$imagesMainPath = Yii::$app->params['IMAGES_FILES_PATH'];

		$src = $imagesMainPath . '/' . $this->name_model . '/' . $this->id_model . '/' . $this->img_name;

		$type = pathinfo( $src, PATHINFO_EXTENSION );

		if ( !is_file( $src ) ) {
			return '';
		}

		$data = file_get_contents( $src );

		return 'data:image/' . $type . ';base64,' . base64_encode( $data );
	}


	public function htmlImgTagWithBase64( string $class = '', string $style = '' ): string {

		$base64Image = $this->imageToBase64();

		return '<img src="' . $base64Image . '" class="' . $class . '" style="' . $style . '">';
	}

	public function delete(): bool|int {

		$imagesMainPath = Yii::$app->params['IMAGES_FILES_PATH'];

		$filename = $this->filename;
		$path = $this->path;

		// delete record
		$deleted = parent::delete();

		if ( $deleted ) {

			$fullPath = $imagesMainPath . '/' . $path;
			$fullPathFilename = $fullPath . $filename;

			$deleted = FileHelper::removeFile( $fullPathFilename );
			FileHelper::removeDirectoryIfEmpty( $fullPath );

		}

		return $deleted;
	}

	function deleteFolderIfEmpty( string $folderPath ): bool {

		// Check if folder exists
		if ( is_dir( $folderPath ) ) {
			// Scan the folder for files (ignoring '.' and '..')
			$files = array_diff( scandir( $folderPath ), [ '.', '..' ] );

			// If no files or subfolders are found, delete the folder
			if ( empty( $files ) ) {
				FileHelper::removeDirectory( $folderPath );

				return true; // Folder deleted
			}
		}

		return false; // Folder not empty or doesn't exist
	}

}
