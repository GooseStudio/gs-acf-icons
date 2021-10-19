<?php
/**
 * ACF Icon provides you with icon.
 *
 * @package gs-acf-icons
 */

namespace GooseStudio\AcfIcons\Fields;

/**
 * Registers ACF fields.
 */
class AcfFields {

	/**
	 * Adds hooks.
	 */
	public function init() {
		add_action( 'acf/include_field_types', array( $this, 'include_fields' ) );
	}

	/**
	 * Initiates ACF Fields.
	 */
	public function include_fields() {
		$settings = array(
			'version' => GS_ACF_ICONS_VERSION,
			'url'     => plugin_dir_url( GS_ACF_ICONS_PLUGIN_FILE__FILE ),
			'path'    => plugin_dir_path( GS_ACF_ICONS_PLUGIN_FILE__FILE ),
		);
		new AcfIconField( $settings );
	}
}
