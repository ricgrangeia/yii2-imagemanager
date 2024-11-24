<?php

namespace ricgrangeia\yii2ImageManager\UI\Controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use Domain\Entity\ImageSearch;
use Application\Services\ImageServices;
use Application\Services\ImageManagerServices;
use common\modules\ImageManager\UI\Controllers\Response;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ApiController extends Controller {


	/**
	 * @inheritdoc
	 */
	public function behaviors(): array {

		return [
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'delete' => [ 'post' ],
				],
			],
		];
	}


	public $enableCsrfValidation = false; // Disable CSRF for API calls (ensure security tokens are used)

	// Action for handling file uploads
	public function actionProcess()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$uploadedFile = UploadedFile::getInstanceByName('file'); // File input name should match 'file'
		if ($uploadedFile) {
			$uploadDir = Yii::getAlias('@webroot/uploads'); // Adjust the directory as needed
			$fileName = uniqid() . '.' . $uploadedFile->extension;

			// Ensure the uploads directory exists
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0777, true);
			}

			// Save the uploaded file
			if ($uploadedFile->saveAs($uploadDir . '/' . $fileName)) {
				return [
					'success' => true,
					'fileUrl' => Yii::getAlias('@web/uploads/' . $fileName), // URL to access the file
				];
			}
		}

		return ['success' => false, 'error' => 'File upload failed.'];
	}

	// Action for reverting/deleting uploaded files
	public function actionRevert()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$fileName = Yii::$app->request->getRawBody(); // Get the file name from the request body
		$uploadDir = Yii::getAlias('@webroot/uploads'); // Adjust the directory as needed

		if ($fileName && file_exists($uploadDir . '/' . $fileName)) {
			unlink($uploadDir . '/' . $fileName); // Delete the file
			return ['success' => true];
		}

		return ['success' => false, 'error' => 'File not found or deletion failed.'];
	}




	/**
	 * Lists all ImgCategory models.
	 * @return string
	 */
	public function actionIndex(): string {

		$searchModel = new ImageSearch();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		] );
	}

	public function actionUpload(): array {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		// Get the uploaded files
		$uploadedFiles = UploadedFile::getInstancesByName('filesUpload');
		$uploadedPaths = [];

		if ($uploadedFiles) {
			foreach ($uploadedFiles as $file) {
				// Ensure a safe file name
				$safeFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '', $file->name);
				$filePath = \Yii::getAlias('@webroot/upload/') . $safeFileName;

				// Save the file
				if ($file->saveAs($filePath)) {
					// Store the uploaded file paths for the response
					$uploadedPaths[] = \Yii::getAlias('@web/upload/') . $safeFileName;
				} else {
					return [
						'success' => false,
						'message' => 'Failed to save one or more files.',
					];
				}
			}

			return [
				'success' => true,
				'uploadedFiles' => $uploadedPaths,
			];
		}

		return [
			'success' => false,
			'message' => 'No files were uploaded.',
		];
	}

	public function actionUploadfiles(): bool|string {

		// This action handles multiple file uploads asynchronously
		$uploadedFiles = UploadedFile::getInstances( $model, $this->nameFileInput );

		$uploadedFilePaths = []; // To store paths of uploaded files

		if ( $uploadedFiles ) {
			foreach ( $uploadedFiles as $uploadedFile ) {



				// Fetch uploaded files
				$uploadedFiles = UploadedFile::getInstances( $model, $this->nameFileInput );

				// Check if there are any files to process
				if ( !empty( $uploadedFiles ) ) {
					foreach ( $uploadedFiles as $file ) {
						ImageManagerServices::addFile( $idModel, $nameModel, $file );
					}
				}





				// Define the path where the file will be saved
				$filePath = 'uploads/temp/' . $uploadedFile->baseName . '.' . $uploadedFile->extension;

				// Save the file
				if ( $uploadedFile->saveAs( $filePath ) ) {
					$uploadedFilePaths[] = $filePath; // Store the file path
				} else {
					// If saving any file fails, return a failure response
					return json_encode( [ 'success' => false, 'error' => 'File upload failed for ' . $uploadedFile->name ], JSON_THROW_ON_ERROR );
				}
			}

			// Return success with the uploaded file paths
			return json_encode( [ 'success' => true, 'filePaths' => $uploadedFilePaths ], JSON_THROW_ON_ERROR );
		}

		// If no files are uploaded, return a failure response
		return json_encode( [ 'success' => false, 'error' => 'No files uploaded' ], JSON_THROW_ON_ERROR );
	}

	public function actionDelete(): bool|string {

		$request = Yii::$app->request;
		if ( $request->isAjax ) {

			$imageId = $request->post( 'key' );

			if ( ImageServices::deleteImage( $imageId ) ) {
				return json_encode( [ 'success' => 'File deleted!' ], JSON_THROW_ON_ERROR );
			}


		}

		return json_encode( [ 'error' => 'Error - Fail to delete file!', 'success' => false ], JSON_THROW_ON_ERROR );
	}

}
