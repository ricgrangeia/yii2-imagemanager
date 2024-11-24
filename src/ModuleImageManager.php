<?php
/**
 * Created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 22/09/23, 11:08
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */

namespace ricgrangeia\yii2ImageManager;

use Yii;
use common\Helpers\StringHelper;

class ModuleImageManager extends Module {

	/****
	 ** Note: If exist ConsoleController inside Folder Commands
	 ** to be called, need to add Class Name in
	 ** /console/config/main.php = on array 'bootstrap' add 'ImageManager'
	 ****/

	/**
	 * Initializes the module.
	 *
	 * This method is called at the end of the module's constructor and is used to perform
	 * any additional setup or initialization tasks.
	 */
	public function init(): void {
		// Get the module class name using StringHelper
		$class_name = StringHelper::extractModuleClassName( __CLASS__ );

		// Set the class name for the module
		$this->setClassName( $class_name );

		// Call the init method of the parent class
		parent::init(); // TODO: Change the autogenerated stub
	}

	/**
	 * Translates a message in the specified category within a Yii module.
	 *
	 * This method is a wrapper around Yii's translation function (Yii::t()), designed
	 * for translating messages within a Yii module. It ensures that the module translations
	 * are registered before performing the translation.
	 *
	 * @param string $category The message category.
	 * @param string $message The message to be translated.
	 * @param array $params The parameters to be inserted into the message.
	 * @param string|null $language The target language. If null, the application language will be used.
	 *
	 * @return string The translated message.
	 */
	public static function t( string $category, string $message, array $params = [], string|null $language = null ): string {
		// Get the module class name using StringHelper
		$class_name = StringHelper::extractModuleClassName( StringHelper::getClassShortName( self::class ) );

		// Check if the translation category is not already registered
		if ( !isset( Yii::$app->i18n->translations["modules/$class_name/*"] ) ) {
			// If not registered, create an instance of the class and register translations
			(new self(Yii::$app->controller->module->id))->registerTranslations();
		}

		// Use Yii::t() to translate the message in the specified category
		return Yii::t( "modules/$class_name/" . $category, $message, $params, $language );
	}

}