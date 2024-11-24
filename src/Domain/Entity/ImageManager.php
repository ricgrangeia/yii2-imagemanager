<?php
/**
 * Created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 31/08/23, 14:58
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */

namespace Domain\Entity;

use common\modules\ImageManager\Domain\Entity\Exception;
use common\modules\ImageManager\Domain\Entity\ImageTable\ImageTable;

class ImageManager {

	public static function getFoto($idModel, $nameModel): array {
		return self::findAll(['condition' => "idModel = '$idModel' AND nameModel = '$nameModel' AND category = 8",]);


	}


	public static function resize( $newWidth, $targetFile, $originalFile): void {

		$info = getimagesize($originalFile);
		$mime = $info['mime'];

		switch ($mime) {
			case 'image/jpeg':
				$image_create_func = 'imagecreatefromjpeg';
				$image_save_func = 'imagejpeg';
				$new_image_ext = 'jpg';
				break;

			case 'image/png':
				$image_create_func = 'imagecreatefrompng';
				$image_save_func = 'imagepng';
				$new_image_ext = 'png';
				break;

			case 'image/gif':
				$image_create_func = 'imagecreatefromgif';
				$image_save_func = 'imagegif';
				$new_image_ext = 'gif';
				break;

			default:
				throw new Exception('Unknown image type.');
		}

		$img = $image_create_func($originalFile);
		[$width, $height] = getimagesize($originalFile);

		$newHeight = ($height / $width) * $newWidth;
		$tmp = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		if (file_exists($targetFile)) {
			unlink($targetFile);
		}
		$image_save_func($tmp, "$targetFile");
	}


}
