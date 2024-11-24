<?php

namespace Application\Behaviors;

use yii\base\Event;
use yii\base\Behavior;
use Helpers\ModelHelper;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Application\Services\ImageManagerServices;

class ImageManagerBehavior extends Behavior {

	public string $nameFileInput = 'filesUpload';

	public function events(): array {

		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'handleFileUpload',
			ActiveRecord::EVENT_AFTER_UPDATE => 'handleFileUpload',
			ActiveRecord::EVENT_AFTER_DELETE => 'handleModelDelete',
		];
	}

	/**
	 * @param Event $event
	 * @return void
	 */
	public function handleFileUpload( Event $event ): void {

		if ( $this->owner instanceof ActiveRecord ) {
			$model = $this->owner; // Access the model
			$idModel = $model->primaryKey;
			$nameModel = ModelHelper::getModelName( $model );

			// Fetch uploaded files
			$uploadedFiles = UploadedFile::getInstances( $model, $this->nameFileInput );

			// Check if there are any files to process
			if ( !empty( $uploadedFiles ) ) {
				foreach ( $uploadedFiles as $file ) {
					ImageManagerServices::addFile( $idModel, $nameModel, $file );
				}
			}
		}
	}

	/**
	 * @param Event $event
	 * @return void
	 */
	public function handleModelDelete( Event $event ): void {

		if ( $this->owner instanceof ActiveRecord ) {
			$model = $this->owner; // Access the model
			$idModel = $model->primaryKey;
			$nameModel = ModelHelper::getModelName( $model );

			ImageManagerServices::removeAllFilesOfModel( $idModel, $nameModel );

		}

	}

}
