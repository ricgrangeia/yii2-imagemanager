<?php

namespace ricgrangeia\yii2ImageManager\tests\unit;

use Codeception\Stub;
use Domain\Entity\Image;
use Codeception\Test\Unit;
use Application\Services\ImageServices;
use common\modules\ImageManager\Domain\Repository\ImageRepositoryInterface;

class ImageServiceTest extends Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	public function testGetImageById()
	{
		// Create a stub for ImageRepositoryInterface
		$imageRepository = Stub::makeEmpty(ImageRepositoryInterface::class, [
			'findById' => function ($id) {
				// Simulate returning an Image object
				$image = new Image();
				$image->setId($id);
				return $image;
			}
		]);

		// Create an instance of the ImageService with the stubbed repository
		$imageService = new ImageServices($imageRepository);

		// Call the method being tested
		$image = $imageService->getImageById(26072);

		// Assert that the method returns a non-null Image object
		$this->assertNotNull($image);
		$this->assertInstanceOf(Image::class, $image);
		$this->assertEquals(26072, $image->getId());
	}

	public function testSaveImage()
	{
		// Create a stub for ImageRepositoryInterface
		$imageRepository = Stub::makeEmpty(ImageRepositoryInterface::class, [
			'save' => function ($image) {
				// Simulate successful saving
				return true;
			}
		]);

		// Create an instance of the ImageService with the stubbed repository
		$imageService = new ImageServices($imageRepository);

		// Create a mock Image object
		$image = Stub::makeEmpty(Image::class);

		// Call the method being tested
		$result = $imageService->saveImage($image);

		// Assert that the method returns true (successful save)
		$this->assertTrue($result);
	}

	public function testGetImageIdByModelIdAndClassName()
	{
		// Create a stub for ImageRepositoryInterface
		$imageRepository = Stub::makeEmpty(ImageRepositoryInterface::class, [
			'findByModelIdAndClassName' => function ($modelId, $className) {
				// Simulate returning an Image object
				$image = new Image();
				$image->setId(1);
				return $image;
			}
		]);

		// Create an instance of the ImageService with the stubbed repository
		$imageService = new ImageServices($imageRepository);

		// Call the method being tested
		$imageId = $imageService->getImageIdByModelIdAndClassName(1, 'ExampleClass');

		// Assert that the method returns the correct Image ID
		$this->assertEquals(1, $imageId);
	}

	public function testGetImageByModelIdAndClassName()
	{
		// Create a stub for ImageRepositoryInterface
		$imageRepository = Stub::makeEmpty(ImageRepositoryInterface::class, [
			'findByModelIdAndClassName' => function ($modelId, $className) {
				// Simulate returning an Image object
				$image = new Image();
				$image->setId(1);
				return $image;
			}
		]);

		// Create an instance of the ImageService with the stubbed repository
		$imageService = new ImageServices($imageRepository);

		// Call the method being tested
		$image = $imageService->getImageByModelIdAndClassName(1, 'ExampleClass');

		// Assert that the method returns a non-null Image object
		$this->assertNotNull($image);
		$this->assertInstanceOf(Image::class, $image);
		$this->assertEquals(1, $image->getId());
	}

	public function testGetHtmlImgTag()
	{
		// Create a stub for ImageRepositoryInterface
		$imageRepository = Stub::makeEmpty(ImageRepositoryInterface::class, [
			'findByModelIdAndClassName' => function ($modelId, $className) {
				// Simulate returning an Image object
				$image = new Image();
				$image->setId(1);
				return $image;
			}
		]);

		// Create an instance of the ImageService with the stubbed repository
		$imageService = new ImageServices($imageRepository);

		// Call the method being tested
		$htmlImgTag = $imageService->getHtmlImgTag(1, 'ExampleClass', 'img-class', 'img-style');

		// Assert that the method returns a non-empty HTML img tag
		$this->assertNotEmpty($htmlImgTag);
		$this->assertStringStartsWith('<img', $htmlImgTag);
		$this->assertStringContainsString('src="data:image/', $htmlImgTag);
		$this->assertStringContainsString('class="img-class"', $htmlImgTag);
		$this->assertStringContainsString('style="img-style"', $htmlImgTag);
	}
}