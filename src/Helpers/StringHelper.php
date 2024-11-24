<?php
/**
 * Created by PhpStorm
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 * @created: 25/09/23, 16:02
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */


namespace Helpers;

use yii\base\Exception;
use yii\helpers\HtmlPurifier;

class StringHelper extends \yii\helpers\StringHelper {

	public static function getClassShortName( mixed $class ): string {

		if ( is_object( $class ) )
			$className = get_class( $class );
		else
			$className = $class;
		$className = explode( '\\', $className );
		$className = end( $className );

		return $className;
	}

	public static function getClassNameCamelCaseWithSpaces( mixed $class ): string {

		if ( is_object( $class ) )
			$className = self::getClassShortName( $class );
		else
			$className = $class;
		$className = preg_replace( '/(?<!^)[A-Z]/', ' $0', $className );

		return ucwords( $className );
	}

	/**
	 * @param string $className
	 * @return string
	 * @tutorial example: StringHelper::getModuleClassName( __CLASS__ );
	 */
	public static function extractModuleClassName( string $className ): string {

		$className = self::getClassShortName( $className );
		// Check if the className starts with "Module"
		if ( str_starts_with( $className, 'Module' ) ) {
			// Remove "Module" from the beginning
			return substr( $className, strlen( 'Module' ) );
		}

		// If it doesn't start with "Module," return it as is
		return $className;
	}

	public static function aliasToNamespace( string $alias ): string {

		// Remove the "@" symbol
		$namespacePath = ltrim( $alias, '@' );

		// Replace forward slashes with backslashes
		return str_replace( '/', '\\', $namespacePath );
	}

	public static function isStringEmpty( string $string ): string {

		# Remove any HTML tags from the string
		$string = HtmlPurifier::process( $string );
		if ( empty( $string ) ) {
			throw new InvalidArgumentException( 'String is empty' );
		}

		return $string;
	}

	public static function isStringInteger( string $value, string $error_message = 'Value is not an integer' ): int {

		$value = self::isStringEmpty( $value );
		if ( ctype_digit( $value ) ) {
			return (int)$value;
		}
		throw new Exception( $error_message );
	}

	public static function getMatchesFromRegex( string $string, string $pattern = '/-?\d+\.?\d*/', string $valueType = 'floatval' ): array {

		// Find all matches of the pattern
		preg_match_all( $pattern, $string, $matches );

		// Convert matches to float
		return array_map( $valueType, $matches[0] );

	}

	public static function getTwoInitialsOfName( string $name ): string {


		$words = explode( ' ', $name );
		$nameInitials = '';

		foreach ( $words as $word ) {
			if ( !empty( $word ) ) {
				$nameInitials .= strtoupper( $word[0] );
			}
			if ( strlen( $nameInitials ) === 2 ) {
				break;
			}
		}

		return $nameInitials;

	}

}