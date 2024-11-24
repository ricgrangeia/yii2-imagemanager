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

use yii\bootstrap4\Html;
use yii\bootstrap4\Widget;

class Thumbnail extends Widget {

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
	public $style = 'width:100%';


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
	public $template = <<<HTML
    <div class="img-thumbnail" style="{style}">
    {img}
    </div>
    <div class="caption">
            <h3>{title}</h3>
            <p>{description}</p>
        </div>
    HTML;


	public function init() {

		parent::init();

		if(!empty($this->model[$this->attribute]))
			$this->imgSource = $this->model[$this->attribute];

		$this->widget = strtr(
			$this->template,
			[
				'{imgSource}' => $this->imgSource,
				'{img}' => Html::img($this->imgSource, ['class'=>'img-thumbnail']),
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
