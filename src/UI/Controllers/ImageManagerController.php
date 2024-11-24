<?php

/**
 * Created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 31/08/23, 15:10
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */

namespace ricgrangeia\yii2ImageManager\UI\Controllers;


use Yii;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use Domain\Entity\ImageSearch;
use Application\Services\ImageServices;
use common\modules\AppController\AppController;
use common\modules\ImageManager\Domain\Entity\ImageCategory\ImageCategorySearch;


class ImageManagerController extends AppController {


	/**
	 * @inheritdoc
	 */
	public function behaviors(): array {
		return [
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all ImgCategory models.
	 * @return string
	 */
	public function actionIndex(): string {
		$searchModel = new ImageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionUpload()
	{
		// This action handles each file upload asynchronously
		$uploadedFiles = UploadedFile::getInstancesByName('filesUpload');

		if ($uploadedFiles) {



			foreach ( $uploadedFiles as $file ) {

				$filePath = '/var/www/html/backend/web/upload/' . time() . $file->name;

				if ( $file->saveAs( $filePath ) ) {
					// You can store the file path in the session or database for later
					Yii::$app->session->addFlash( 'uploadedFiles', $filePath );

					return json_encode( [ 'success' => true, 'filePath' => $filePath ] );
				}
			}
		}
		return json_encode(['success' => false]);
	}

	public function actionUploadfiles()
	{
		// This action handles multiple file uploads asynchronously
		$uploadedFiles = UploadedFile::getInstancesByName('filesUpload'); // Get all instances of uploaded files

		$uploadedFilePaths = []; // To store paths of uploaded files

		if ($uploadedFiles) {
			foreach ($uploadedFiles as $uploadedFile) {
				// Define the path where the file will be saved
				$filePath = 'uploads/temp/' . $uploadedFile->baseName . '.' . $uploadedFile->extension;

				// Save the file
				if ($uploadedFile->saveAs($filePath)) {
					$uploadedFilePaths[] = $filePath; // Store the file path
				} else {
					// If saving any file fails, return a failure response
					return json_encode(['success' => false, 'error' => 'File upload failed for ' . $uploadedFile->name]);
				}
			}

			// Return success with the uploaded file paths
			return json_encode(['success' => true, 'filePaths' => $uploadedFilePaths]);
		}

		// If no files are uploaded, return a failure response
		return json_encode(['success' => false, 'error' => 'No files uploaded']);
	}

	public function actionDelete()
	{
		$request = Yii::$app->request;
		if($request->isAjax) {

			$imageId = $request->post('key');

			if (ImageServices::deleteImage($imageId)){
				return json_encode(['success'=> 'File deleted!']);
			}


		}
		return json_encode(['error'=> 'Error - Fail to delete file!', 'success' => false]);
	}

}