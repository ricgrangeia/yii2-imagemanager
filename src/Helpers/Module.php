<?php

/**
 * @created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 03/10/23, 17:17
 *
 * @company Sabores do Futuro
 * @copyright All rights reserved.
 */


namespace Helpers;

use yii;
use yii\console\Application;
use yii\i18n\PhpMessageSource;
use yii\base\BootstrapInterface;


class Module extends yii\base\Module implements BootstrapInterface {

	private ?string $className = null;
	private ?string $parentAlias = null;
	private ?string $migrationType = 'migrate';


	/**
	 * This is to set the module class name.
	 * Use mostly by the child classes.
	 * @param string $className
	 */
	protected function setClassName( string $className ): void {

		$this->className = $className;
	}

	/**
	 * This is when module has Parent Module
	 * @param string $parentAlias
	 * @return void
	 */
	protected function setParentAlias( string $parentAlias ): void {

		$this->parentAlias = $parentAlias;
	}

	/**
	 * This is when module has Parent Module
	 * @param string $migrationType
	 * @return void
	 */
	protected function setMigrationType( string $migrationType ): void {

		$this->migrationType = $migrationType;
	}

	/**
	 * This is the generic init method for all modules, that extends this class.
	 * The module class name e passed when the module is called.
	 * So it can be used for multiple modules.
	 * It sets the controller namespace, the view path, the translations and the console commands.
	 * Alsothe migrations path is set to the migrations folder of the module.
	 * @return void
	 */
	public function init(): void {

		$this->controllerNamespace = "common\modules\\$this->className\UI\Controllers";

		if ( $this->parentAlias ) {
			// if is a SubModule with a parent module
			$this->controllerNamespace = StringHelper::aliasToNamespace( $this->parentAlias ) . "\SubModule\\$this->className\UI\Controllers";
		}

		$this->setViewPath( "@common/modules/$this->className/UI/Views" );

		if ( $this->parentAlias ) {
			// is a SubModule with a parent module
			$this->setViewPath( $this->parentAlias . "/SubModule/$this->className/UI/Views" );
		}

		$this->registerTranslations();

		$this->getControllerMap();

		parent::init();

	}


	/**
	 * This is to register translations for the module.
	 * The module class name is passed when the module is called.
	 * @return void
	 */
	public function registerTranslations(): void {


		$basePath = "@common/modules/{$this->className}/UI/messages";
		if ( $this->parentAlias ) {
			$basePath = "{$this->parentAlias}/SubModule/{$this->className}/UI/messages";
		}

		$baseFilePath = Yii::getAlias( $basePath );

		// Initialize the fileMap array
		$fileMap = [];

		// Use GLOB_BRACE to scan for all PHP files recursively
		$files = glob( "$baseFilePath/**/*.php", GLOB_BRACE );

		// Initialize an array to keep track of distinct filenames
		$processedFiles = [];

		// Loop through all files found
		foreach ( $files as $file ) {
			$fileName = basename( $file, '.php' );

			// Check if the file name has already been processed
			if ( !in_array( $fileName, $processedFiles, true ) ) {
				// Add the file to the fileMap
				$fileMap["modules/{$this->className}/{$fileName}"] = "{$fileName}.php";
				// Mark this filename as processed
				$processedFiles[] = $fileName;
			}
		}

		Yii::$app->i18n->translations["modules/{$this->className}/*"] = [
			'class' => PhpMessageSource::class,
			'sourceLanguage' => 'en',
			'basePath' => $basePath,
			'fileMap' => $fileMap,
		];
	}

	/**
	 * This is a method that is called during module bootstrap.
	 * To use console commmands of the module, the module class name e passed when the module is called.
	 * @param $app
	 * @return void
	 */
	public function bootstrap( $app ): void {

		if ( $app instanceof Application ) {
			$this->controllerNamespace = "common\modules\\$this->className\Commands";


			$this->getControllerMap();


		}
	}

	/**
	 * @return void
	 */
	private function getControllerMap(): void {

		if ( $this->migrationType === 'migrate' ) {
			$this->controllerMap = [
				'migrate' => [
					'class' => \yii\console\controllers\MigrateController::class,
					'migrationNamespaces' => [ "common\modules\\$this->className\Migrations" ],
					'migrationTable' => 'migration_module',
					'migrationPath' => null,
				],
			];
		} else {
			$this->controllerMap = [
				'mongodb-migrate' => [
					'class' => \yii\mongodb\console\controllers\MigrateController::class,
					'migrationNamespaces' => [ "common\modules\\$this->className\Migrations\mongodb" ],
					'migrationTable' => 'migration_module',
					'migrationPath' => null,
				],
			];
		}
	}


}