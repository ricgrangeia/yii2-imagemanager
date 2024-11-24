<?php
/**
 * Created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 31/08/23, 14:39
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */

namespace ricgrangeia\yii2ImageManager\UI\Widgets;

use yii\helpers\Url;
use yii\bootstrap4\Html;
use Domain\Entity\Image;
use yii\bootstrap4\Widget;
use kartik\widgets\FileInput;
use common\modules\ImageManager\ModuleImageManager;

class SimpleManager extends Widget {

	/**
	 * Model
	 * @var string $name
	 */
	public $name = '';
	public $model = '';
	public $attribute = '';

	/**
	 * @var string $widget
	 */
	public $widget = '';

	/**
	 * @var string $title
	 */
	public $title = '';

	/**
	 * @var string $title
	 */
	public $style =
		<<<CSS
	
		/*----------multiple-file-upload-----------*/
		.input-group.file-caption-main {
			display: none;
		}
	
		.close.fileinput-remove {
			display: none;
		}
	
		.file-drop-zone {
			margin: 0px;
			border: 1px solid #fff;
			background-color: #fff;
			padding: 0px;
			display: contents;
		}
	
		.file-drop-zone.clickable:hover {
			border-color: #fff;
		}
	
		.file-drop-zone .file-preview-thumbnails {
			display: inline;
		}
	
		.file-drop-zone-title {
			padding: 15px;
			height: 120px;
			width: 120px;
			font-size: 12px;
		}
	
		.file-input-ajax-new {
			display: inline-block;
		}
	
		.file-input.theme-fas {
			display: inline-block;
			width: 100%;
		}
	
		.file-preview {
			padding: 0px;
			border: none;
			display: inline;
	
		}
	
		.file-drop-zone-title {
			display: none;
		}
	
		.file-footer-caption {
			display: none !important;
		}
	
		.kv-file-upload {
			display: none;
		}
	
		.file-upload-indicator {
			display: none;
		}
	
		.file-drag-handle.drag-handle-init.text-info {
			display: none;
		}
	
		.krajee-default.file-preview-frame .kv-file-content {
			width: 120px;
			height: 120px;
			display: flex;
			text-align: center;
			align-items: center;
		}
	
		.krajee-default.file-preview-frame {
			background-color: #fff;
			margin: 3px;
			border-radius: 15px;
			overflow: hidden;
		}
	
		.krajee-default.file-preview-frame:not(.file-preview-error):hover {
			box-shadow: none;
			border-color: #ed3237;
		}
	
		.krajee-default.file-preview-frame:not(.file-preview-error):hover .file-preview-image {
			transform: scale(1.1);
		}
	
		.krajee-default.file-preview-frame {
			box-shadow: none;
			border-color: #fff;
			max-width: 150px;
			margin: 5px;
			padding: 0px;
			transition: 0.5s;
		}
	
		.file-thumbnail-footer,
		.file-actions {
			width: 20px;
			height: 20px !important;
			position: absolute !important;
			top: 3px;
			right: 3px;
		}
	
		.kv-file-remove:focus,
		.kv-file-remove:active {
			outline: none !important;
			box-shadow: none !important;
		}
	
	
	
		.kv-preview-data.file-preview-video {
			width: 100% !important;
			height: 100% !important;
		}
	
		.btn-outline-secondary.focus, .btn-outline-secondary:focus {
			box-shadow: none;
		}
	
		.btn-toggleheader,
		.btn-fullscreen,
		.btn-borderless {
			display: none;
		}
	
		.btn-kv.btn-close {
			color: #fff;
			border: none;
			background-color: #ed3237;
			font-size: 11px;
			width: 18px;
			height: 18px;
			text-align: center;
			padding: 0px;
		}
	
		.btn-outline-secondary:not(:disabled):not(.disabled).active:focus,
		.btn-outline-secondary:not(:disabled):not(.disabled):active:focus,
		.show > .btn-outline-secondary.dropdown-toggle:focus {
			background-color: rgba(255, 255, 255, 0.8);
			color: #000;
			box-shadow: none;
			color: #ed3237;
		}
	
		.kv-file-content .file-preview-image {
			width: 120px !important;
			height: 120px !important;
			max-width: 120px !important;
			max-height: 120px !important;
			transition: 0.5s;
		}
	
		.btn-danger.btn-file {
			padding: 0px;
			height: 95px;
			width: 95px;
			display: inline-block;
			margin: 5px;
			border-color: #fdeff0;
			background-color: #fdeff0;
			color: #ed1924;
			border-radius: 15px;
			padding-top: 30px;
			transition: 0.5s;
		}
	
		.btn-danger.btn-file:active,
		.btn-danger.btn-file:hover {
			background-color: #fde3e5;
			color: #ed1924;
			border-color: #fdeff0;
			box-shadow: none;
		}
	
		.btn-danger.btn-file i {
			font-size: 30px;
		}
	
	
		@media (max-width: 350px) {
			.krajee-default.file-preview-frame:not([data-template=audio]) .kv-file-content {
				width: 90px;
			}
		}
	CSS;


	/**
	 * @var string $description
	 */
	public $description = '';

	/**
	 * @var array $imgOptions
	 */
	public $imgSource = '/sem-imagem.gif';

	/**
	 * @var string $template
	 */


	public $template = '';


	public function init() {

		parent::init();

		$initialPreview = [];
		$initialPreviewConfig = [];

		// Ensure the model is properly set
		if ( $this->model !== null ) {

			$images = Image::find()->where( [
				'id_model' => $this->model->id,
				'name_model' => $this->model->getModelName(),
			] )->all();

			if ( $images ) {
				foreach ( $images as $image ) {
					assert( $image instanceof Image );
					$initialPreview[] = $image->imageToBase64();
					$initialPreviewConfig[] = ['key' => $image->id];

				}
			}
		}

		$this->template = FileInput::widget( [

			'name' => 'Imobilizado[filesUpload][]',

			'options' => [
				'multiple' => true,
				'accept' => 'image/*',
				'id' => 'input-b5', // Important: Ensure the ID matches the JavaScript initialization
			],

			'pluginOptions' => [

				'uploadUrl' => Url::to( [ '/ImageManager/image-manager/upload' ] ),
				'deleteUrl' => Url::to( [ '/ImageManager/image-manager/delete' ] ),
				'allowedFileExtensions' => [ 'jpg', 'jpeg', 'png', 'gif', 'pdf' ],
				'maxFileCount' => 1,
				'language' => 'pt',
				'initialPreview' => $initialPreview,
				'initialPreviewConfig' => $initialPreviewConfig,
				'initialPreviewAsData' => true,
				'overwriteInitial' => false,  // This ensures the initial preview is not replaced
				'showRemove' => false,
				'showUpload' => false,
				'showCaption' => false,
				'showZoom' => false,
				'browseLabel' => '<b>' . ModuleImageManager::t( 'module-imagemanager', 'Photos' ) . '</b>',
				'browseIcon' => '<i class=\'fa fa-plus\'></i>',
				'removeIcon' => false,
				'showCancel' => false,

			],
		] );

		if ( !empty( $this->model[$this->attribute] ) ) {
			$this->imgSource = $this->model[$this->attribute];
		}

		$this->widget = strtr(
			$this->template .
			'<style>' . $this->style . '</style>',

			[
				'{imgSource}' => $this->imgSource,
				'{img}' => Html::img( $this->imgSource, [ 'class' => 'img-thumbnail' ] ),
				'{title}' => $this->title,
				'{description}' => $this->description,
				'{style}' => $this->style,
			]
		);
	}

	public function run(): string {

		return $this->widget;
	}
}
