<?php
/**
 * ACF Icon provides you with icon.
 *
 * @package gs-acf-icons
 */

namespace GooseStudio\AcfIcons;

use GooseStudio\AcfIcons\Fields\AcfFields;

/**
 * Class AcfIcons
 *
 * @package GooseStudio\AcfIcons
 */
class AcfIcons {

	/**
	 * Set up plugin
	 */
	public function init() {
		( new AcfFields() )->init();
		$this->add_hooks();
		add_action( 'wp_loaded', array( $this, 'upgrade_check' ) );
	}

	/**
	 * Check if upgrade required
	 */
	public function upgrade_check() {
		include_once ABSPATH . '/wp-includes/pluggable.php';

		if ( is_admin() && current_user_can( 'manage_options' ) && get_option( 'gs_acf_icons_version' ) !== GS_ACF_ICONS_VERSION ) {
			$this->upgrade();
		}
	}

	/**
	 * Run upgrade code
	 */
	private function upgrade() {
		update_option( 'gs_acf_icons_version', GS_ACF_ICONS_VERSION );
	}

	/**
	 * Add hooks
	 */
	private function add_hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_assets' ) );
	}

	/**
	 * Admin
	 */
	public function admin_init() {

	}

	/**
	 * Adds frontend CSS and JS assets.
	 */
	public function frontend_assets() {
		$url = plugin_dir_url( GS_ACF_ICONS_PLUGIN_FILE__FILE );
		wp_register_style( 'ionicons', "{$url}assets/dependencies/ionicons/css/ionicons.min.css", array(), '4.5.10-1' );
		wp_register_style( 'font-awesome-5-all', "{$url}assets/dependencies/font-awesome/css/all.min.css", array(), '5.11.2' );
	}

	/**
	 * Adds admin CSS and JS
	 */
	public function admin_assets() {
		$url = plugin_dir_url( GS_ACF_ICONS_PLUGIN_FILE__FILE );
		wp_register_style( 'ionicons', "{$url}assets/dependencies/ionicons/css/ionicons.min.css", array(), '4.5.10-1' );
		wp_register_style( 'font-awesome-5-all', "{$url}assets/dependencies/font-awesome/css/all.min.css", array(), '5.11.2' );
	}
}
