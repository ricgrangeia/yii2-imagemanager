<?php
/**
 * Created by PhpStorm.
 * Author: Ricardo Grangeia Dias
 * Date: 09/02/22, 14:01.
 **/

namespace ricgrangeia\yii2ImageManager\tests\unit;


use Codeception\Stub;
use Domain\Entity\Image;
use Codeception\Test\Unit;

final class ImageTest extends Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	public function testGetFullPathFilename()
	{
		// Create a stub of the Image class
		$image = Stub::make(Image::class, [
			'getModelName' => 'Funcionario',
			'getModelId' => 217,
			'getImgName' => '202005272157153244415Funcionarios_217.jpg',
		]);

		// Call the method being tested
		$fullPathFilename = $image->getFullPathFilename();

		// Assert that the method returns the correct full path filename
		$expectedPath = '/var/www/imagens/Funcionario/217/202005272157153244415Funcionarios_217.jpg'; // Adjust this path according to your application
		$this->assertSame($expectedPath, $fullPathFilename);
	}

	public function testImageToBase64()
	{
		// Create a stub of the Image class
		$image = Stub::make(Image::class, [
			'getModelName' => 'Funcionario',
			'getModelId' => 217,
			'getImgName' => '202005272157153244415Funcionarios_217.jpg',
		]);

		// Call the method being tested
		$base64Image = $image->imageToBase64();

		// Assert that the method returns a non-empty base64-encoded image
		$this->assertNotEmpty($base64Image);
		$this->assertStringStartsWith('data:image/', $base64Image);
	}

	public function testHtmlImgTagWithBase64()
	{
		// Create a stub of the Image class
		$image = Stub::make(Image::class, [
			'getModelName' => 'Funcionario',
			'getModelId' => 217,
			'getImgName' => '202005272157153244415Funcionarios_217.jpg',
		]);

		// Call the method being tested
		$htmlImgTag = $image->htmlImgTagWithBase64();

		// Assert that the method returns a non-empty HTML img tag
		$this->assertNotEmpty($htmlImgTag);
		$this->assertStringStartsWith('<img', $htmlImgTag);
		$this->assertStringContainsString('src="data:image/', $htmlImgTag);
	}

}
