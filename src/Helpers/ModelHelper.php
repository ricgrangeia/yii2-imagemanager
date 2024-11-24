<?php


namespace Helpers;

use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

class ModelHelper {
	public static function getModelName( ActiveRecord $model ): string {

		return StringHelper::basename( get_class( $model ) );
	}
}