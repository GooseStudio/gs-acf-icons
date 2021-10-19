<?php
/**
 * ACF Icon provides you with icon.
 *
 * @package gs-acf-icons
 */

namespace GooseStudio\AcfIcons;

spl_autoload_register( __NAMESPACE__ . '\\autoload' );

/**
 * Loads ACF Icon classes.
 *
 * @param string $class_name The fully-qualified name of the class to load.
 */
function autoload( $class_name ) {
	if ( 0 !== strpos( $class_name, 'GooseStudio\\AcfIcons\\' ) ) {
		return;
	}
	$local_class          = substr( $class_name, strlen( 'GooseStudio\\AcfIcons\\' ) );
	$local_class          = implode( DIRECTORY_SEPARATOR, explode( '\\', $local_class ) );
	$localized_class_path = __DIR__ . '/src/' . $local_class . '.php';
	if ( file_exists( $localized_class_path ) ) {
		include $localized_class_path;
	}
}
