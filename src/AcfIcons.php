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
		add_action(
			'admin_menu',
			function() {
				add_submenu_page( 'edit.php?post_type=acf-field-group', __( 'ACF Icons', 'gs-acf-icons' ), __( 'ACF Icons', 'gs-acf-icons' ), acf_get_setting( 'capability' ), 'gs-acf-icons', array( $this, 'settings_page' ) );
			}
		);
		add_action( 'current_screen', array( $this, 'current_screen' ) );

	}

	/**
	 * Add headers if current screen belongs to ACF.
	 *
	 * @param \WP_Screen $screen The current screen.
	 *
	 * @return void
	 */
	public function current_screen( $screen ) {
		if ( isset( $screen->post_type ) && 'acf-field-group' === $screen->post_type ) {
			add_action( 'in_admin_header', array( $this, 'in_admin_header' ) );
		}
	}

	/**
	 * Admin
	 */
	public function admin_init() {
		$this->register_settings();
	}

	/**
	 * Register settings used by ACF Icons.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'gs-acf-icons', 'font-awesome-pro-kit-id', 'sanitize_key' );
		add_settings_section( 'icon-libraries', 'Icon Libraries', '', 'gs-acf-icons' );
		add_settings_field(
			'font-awesome-pro-kit-id',
			'Font Awesome Pro Kit ID',
			function() {
				$setting = get_option( 'font-awesome-pro-kit-id' ); ?>
			<input type="text" name="font-awesome-pro-kit-id" value="<?php echo esc_attr( isset( $setting ) ? $setting : '' ); ?>">
																				<?php
			},
			'gs-acf-icons',
			'icon-libraries'
		);
	}

	/**
	 * If current screen is ACF icons settings page render header.
	 *
	 * @return void
	 */
	public function in_admin_header() {
		$screen = get_current_screen();
		if ( isset( $screen->base ) && 'custom-fields_page_gs-acf-icons' === $screen->base ) {
			echo '<div class="acf-headerbar acf-headerbar-field-editor">
	<div class="acf-headerbar-inner">
		<div class="acf-headerbar-content">
			<h1 class="acf-page-title">ACF Icons</h1>
		</div>
	</div>
</div>';
		}
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public function settings_page() {
		echo '<div id="wpbody" role="main"><div id="wpbody-content"><div class="wrap">';
		echo '<form method="post" action="options.php">';
		settings_fields( 'gs-acf-icons' );
		echo '<h2 class="wp-heading-inline">Icon Libraries</h2>';
		echo '<style>.form-table th { padding:20px }</style>';
		echo '<table class="form-table" role="presentation" style="background-color: #FFF;border-radius: 8px;box-shadow: 0 1px 2px rgb(16 24 40 / 10%);max-width: 1440px;">';
		do_settings_fields( 'gs-acf-icons', 'icon-libraries' );
		echo '</table>';
		echo '<div style="padding:20px">';
		echo '<button class="acf-btn acf-publish" type="submit">Save Changes</button>';
		echo '</div>';
		echo '</form>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	/**
	 * Adds frontend CSS and JS assets.
	 */
	public function frontend_assets() {
		$url = plugin_dir_url( GS_ACF_ICONS_PLUGIN_FILE__FILE );
		wp_register_style( 'ionicons', "{$url}assets/dependencies/ionicons/css/ionicons.min.css", array(), '4.5.10-1' );
		wp_register_style( 'font-awesome-5-all', "{$url}assets/dependencies/font-awesome/css/all.min.css", array(), '5.11.2' );
		$pro_id = get_option( 'font-awesome-pro-kit-id' );
		if ( $pro_id ) {
			wp_register_script(
				'font-awesome-6-pro',
				'https://kit.fontawesome.com/' . esc_attr( $pro_id ) . '.js',
				array(),
				'6.12',
				true
			);
		}
	}

	/**
	 * Adds admin CSS and JS
	 */
	public function admin_assets() {
		$url = plugin_dir_url( GS_ACF_ICONS_PLUGIN_FILE__FILE );
		wp_register_style( 'ionicons', "{$url}assets/dependencies/ionicons/css/ionicons.min.css", array(), '4.5.10-1' );
		wp_register_style( 'font-awesome-5-all', "{$url}assets/dependencies/font-awesome/css/all.min.css", array(), '5.11.2' );
		$pro_id = get_option( 'font-awesome-pro-kit-id' );
		if ( $pro_id ) {
			wp_register_script(
				'font-awesome-6-pro',
				'https://kit.fontawesome.com/' . esc_attr( $pro_id ) . '.js',
				array(),
				'6.12',
				true
			);
		}
	}
}
