<?php
/**
 * ACF Icon provides you with icon.
 *
 * @package gs-acf-icons
 */

namespace GooseStudio\AcfIcons\Fields;

use acf_field;

/**
 *
 * ACF field that enables the use of icons.
 */
class AcfIconField extends acf_field {
	/**
	 * The Field settings.
	 *
	 * @var array Field settings.
	 */
	private $settings;

	/**
	 * Initiate a field.
	 *
	 * @param array $settings Field settings.
	 */
	public function __construct( $settings ) {
		$this->name     = 'gs-acf-icon';
		$this->label    = __( 'Icon', 'gs-acf' );
		$this->category = 'content';
		$this->defaults = array(
			'preview_font_size' => 100,
			'return_format'     => 'class',
		);
		$this->l10n     = array();
		$this->settings = $settings;

		parent::__construct();
	}

	/**
	 * Returns CSS file handle for the supplied CSS class.
	 *
	 * @param string $css_class The CSS class for the icon.
	 *
	 * @return string
	 */
	public static function get_css_handle( $css_class ) {
		$prefix = substr( $css_class, 0, 3 );
		switch ( $prefix ) {
			case 'ion':
				return 'ionicons';
			case 'fas':
			case 'fab':
			case 'far':
				return 'font-awesome-5-all';
			case 'eic':
				return 'elementor-icons';
		}

		return '';
	}

	/**
	 * Registers field settings.
	 *
	 * @param array $field The field to configure.
	 */
	public function render_field_settings( $field ) {
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Preview font size', 'gs-acf' ),
				'instructions' => __( 'The size of the preview', 'gs-acf' ),
				'type'         => 'text',
				'name'         => 'preview_font_size',
				'ui'           => 1,
			)
		);
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Return format', 'gs-acf' ),
				'instructions' => __( 'Which icon format to return', 'gs-acf' ),
				'type'         => 'select',
				'choices'      => array(
					'class'          => 'CSS Class',
					'svg_sprite_url' => 'SVG Sprite URL',
					'svg_url'        => 'SVG URL',
					'svg_path'       => 'SVG File Path',
					'svg_raw'        => 'Raw SVG',
				),
				'name'         => 'return_format',
				'ui'           => 1,
			)
		);
	}

	/**
	 * Renders the ACF field in the admin.
	 *
	 * @param array $field The field to render.
	 */
	public function render_field( $field ) {
		$width                 = (int) $field['preview_font_size'] * 1.2;
		$font_size             = (int) $field['preview_font_size'];
		list( , $css, $style ) = explode( ':', $field['value'] . '::' );
		$css_class             = str_replace( '%', $css, $style );
		if ( $css_class === $style ) {
			$css_class = $style . ' ' . $css;
		}
		$field_id = $field['id'];
		?>
		<!--suppress HtmlFormInputWithoutLabel -->
		<input type="hidden" name="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>"/>
		<div>
			<div class="gs-acf-icon-field" data-acf-field="<?php echo esc_attr( $field_id ); ?>" style="width: <?php echo esc_attr( $width ); ?>px;height:<?php echo esc_attr( $width ); ?>px;font-size:<?php echo esc_attr( $font_size ); ?>px">
				<?php if ( $css_class ) : ?>
					<i class="<?php echo esc_attr( $css_class ); ?>"></i>
				<?php endif ?>
			</div>
			<button style="height:28px" class="gs-acf-icon-field-select button" data-acf-field="<?php echo esc_attr( $field_id ); ?>">Select icon
			</button>
			<button style="height:28px;" class="gs-acf-icon-field-remove button" data-acf-field="<?php echo esc_attr( $field_id ); ?>"><i class="fa fa-trash"></i></button>
		</div>
		<?php
	}

	/**
	 * Registers required CSS and JS for the admin.
	 */
	public function input_admin_enqueue_scripts() {
		if ( get_post_type() === 'acf-field-group' ) {
			return;
		}
		$url     = $this->settings['url'];
		$version = $this->settings['version'];

		wp_register_script(
			'gs-acfe-fields-mappings',
			"{$url}assets/dependencies/mappings.js",
			array(),
			$version,
			true
		);
		wp_register_script(
			'gs-acfe-fields',
			"{$url}assets/js/acf-fields.js",
			array(
				'acf-input',
				'wp-util',
				'jquery-ui-dialog',
				'gs-acfe-fields-mappings',
			),
			$version,
			true
		);
		wp_enqueue_script( 'gs-acfe-fields' );

		wp_register_style(
			'gs-acfe-fields',
			"{$url}assets/css/acf-fields.css",
			array(
				'acf-input',
				'wp-jquery-ui-dialog',
			),
			$version
		);
		wp_enqueue_style( 'gs-acfe-fields' );
		wp_dequeue_style( 'font-awesome-5-all' );
		wp_enqueue_style( 'ionicons' );
		wp_enqueue_style( 'font-awesome-5-all' );
	}

	/**
	 * Prints required JS templates
	 *
	 * @noinspection PhpUnused
	 *
	 * @param array $fields The ACF Fields.
	 */
	public function input_admin_footer( $fields ) {
		?>
		<div id="icon-dialog"></div>
		<?php
		// Template for the media frame: used both in the media grid and in the media modal.
		?>
		<script type="text/html" id="tmpl-icon-library-frame">
			<div class="icon-library-frame-title" id="icon-library-frame-title"></div>
			<div class="icon-library-frame-menu">
				<div class="icon-library-frame-side-menu">
					<h3>Libraries</h3>
					<div class="separator"></div>
					<div class="icon-library-selection">
						<input id="all" type="radio" value="all:" checked="checked" name="library"><label for="all">All
							icons</label>
					</div>
					<div class="icon-library-selection">
						<input id="far" type="radio" value="font-awesome:regular" name="library"><label for="far">Font
							Awesome - Regular</label>
					</div>
					<div class="icon-library-selection">
						<input id="fas" type="radio" value="font-awesome:solid" name="library"><label for="fas">Font
							Awesome - Solid</label>
					</div>
					<div class="icon-library-selection">
						<input id="fab" type="radio" value="font-awesome:brands" name="library"><label for="fab">Font
							Awesome - Brands</label>
					</div>
					<div class="icon-library-selection">
						<input id="eicon" type="radio" value="elementor:regular" name="library"><label for="eicon">Elementor
							Icons</label>
					</div>
					<div class="icon-library-selection">
						<input id="ios" type="radio" value="ionicons:ios" name="library"><label for="ios">Ionicons -
							iOS</label>
					</div>
					<div class="icon-library-selection">
						<input id="md" type="radio" value="ionicons:md" name="library"><label for="md">Ionicons -
							Material </label>
					</div>
				</div>
				<div class="icon-library-frame-content">
					<input type="hidden" name="icon_library" id="icon_library" value="">
					<input type="hidden" name="icon_css" id="icon_css" value="">
					<input type="hidden" name="icon_style" id="icon_style" value="">
					<h2 class="icon-library-frame-views-heading screen-reader-text">Select icon ... </h2>
					<div class="icon-library-frame-toolbar">
						<div class="icon-library-toolbar-primary search-form">
							<label for="icon-search-input" class="screen-reader-text">
								Search Icons
							</label>
							<input type="search" placeholder="Search icons ..." id="icon-search-input" class="search"></div>
					</div>
					<p style="text-align: center;font-size: 1.2em">Found <span class="icon-search-result-total"></span> icons </p>
					<div class="icon-library-list-wrapper">
						<div class="icon-library-list">
						</div>
						<div style="text-align:center;width:100%;margin-top:20px">
							<button class="icon-library-load-more button button-secondary" style="padding:10px;font-size:20px;height: auto;">Load more icons ...
							</button>
						</div>
					</div>
					<div class="icon-library-frame-footer">
						<button class="button button-primary attach-icon-to-acf-field">Insert</button>
					</div>
				</div>
		</script>
		<script type="text/html" id="tmpl-icon-library-icon-font">
			<# _.each(data.fonts, function(meta_datum) {
			style = data.style?meta_datum[data.style]:meta_datum[meta_datum.styles[0]];
			css = style.replace('%',meta_datum.css, style);
			if ( css === style ) {
			css = meta_datum.css;
			} #>
			<div onclick="on_icon_click_fn(this)" class="icon-font" data-font-css="{{ meta_datum.css }}" data-font-library="{{ meta_datum.library }}" data-font-style="{{ style }}" title="{{meta_datum.label}}">
				<div class="icon-font-wrapper">
					<i class="{{ style }} {{ css }}"></i>
					<div>{{ meta_datum.label }}</div>
				</div>
			</div>
			<# }); #>
		</script>
		<?php
	}

	/**
	 *
	 *  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
	 *
	 * @param string $value (mixed) the value which was loaded from the database.
	 * @param int    $post_id (mixed) the $post_id from which the value was loaded.
	 * @param array  $field (array) the field array holding all the field options.
	 *
	 * @return array|false|string|string[] $value (mixed) the modified value.
	 */
	public function format_value( $value, $post_id, $field ) {
		if ( empty( $value ) ) {

			return $value;

		}
		list( $library, $css, $style ) = explode( ':', $value );
		$css_class                     = str_replace( '%', $css, $style );
		if ( $css_class === $style ) {
			$css_class = $style . ' ' . $css;
		}
		switch ( $field['return_format'] ) {
			case 'svg_sprite_url':
				$svg = '';
				if ( 'ionicons' === $library ) {
					$svg = 'ionicons.svg#' . $css_class;
				} elseif ( 'font-awesome' === $library ) {
					$f_type = substr( $style, 0, 3 );
					if ( 'fas' === $f_type ) {
						$svg = 'solid.svg';
					} elseif ( 'far' === $f_type ) {
						$svg = 'regular.svg';
					} else {
						$svg = 'brands.svg';
					}
					$svg = $svg . '#' . $css;
				} elseif ( 'elementor' === $library ) {
					$svg = 'eicons.svg#' . trim( $css_class );
				}

				return sprintf( plugin_dir_url( GS_ACF_ICONS_PLUGIN_FILE__FILE ) . '/assets/dependencies/%s/sprites/%s', $library, $svg );
			case 'svg_url':
				return $this->get_svg_url_path( $library, $css, $css_class );
			case 'svg_path':
				return $this->get_svg_file_path( $library, $css, $css_class );
			case 'svg_raw':
				return file_get_contents( $this->get_svg_file_path( $library, $css, $css_class ) );
			default:
				return $css_class;
		}
	}

	/**
	 * Returns the URL for the SVG file.
	 *
	 * @param string $library Font library to use.
	 * @param string $css The CSS fragment to use.
	 * @param string $css_class The complete CSS class.
	 *
	 * @return string
	 */
	public function get_svg_url_path( $library, $css, $css_class ) {
		$dir       = $this->get_base_dir( $library . '/' );
		$file_path = $dir . $css . '.svg';
		if ( ! file_exists( $file_path ) ) {
			$this->get_svg_file_path( $library, $css, $css_class );
		}
		$dir      = wp_get_upload_dir();
		$base_url = $dir['baseurl'] . '/acf-icons/' . $library . '/';

		return $base_url . $css . '.svg';
	}

	/**
	 * Returns the directory for generated ACF Icon files.
	 *
	 * @param string $path The base path to the icon library.
	 *
	 * @return string
	 */
	public function get_base_dir( $path ) {
		$dir      = wp_get_upload_dir();
		$base_dir = $dir['basedir'];
		if ( ! file_exists( $base_dir . '/acf-icons/' . $path ) ) {
			wp_mkdir_p( $base_dir . '/acf-icons/' . $path );
		}

		return $base_dir . '/acf-icons/' . $path;
	}

	/**
	 * Returns the file path for the SVG file.
	 *
	 * @param string $library Font library to use.
	 * @param string $css The CSS fragment to use.
	 * @param string $css_class The complete CSS class.
	 *
	 * @return string
	 */
	public function get_svg_file_path( $library, $css, $css_class ) {
		$sprites = '';
		if ( 'ionicons' === $library ) {
			$sprites = 'ionicons';
		} elseif ( 'font-awesome' === $library ) {
			$f_type = substr( $css_class, 0, 3 );
			if ( 'fas' === $f_type ) {
				$sprites = 'solid';
			} elseif ( 'far' === $f_type ) {
				$sprites = 'regular';
			} else {
				$sprites = 'brands';
			}
			$css_class = $css;
		} elseif ( 'elementor' === $library ) {
			$css_class = trim( $css_class );
			$sprites   = 'eicons';
		}

		$dir       = $this->get_base_dir( $library . '/' );
		$file_path = $dir . $css . '.svg';
		if ( ! file_exists( $file_path ) && $sprites ) {
			$xml    = simplexml_load_file( sprintf( GS_ACF_ICONS_DIR . '/assets/dependencies/%s/sprites/%s.svg', $library, $sprites ) );
			$symbol = $xml->xpath( "//*[@id=\"$css_class\"]" );
			$svg    = str_replace( 'symbol', 'svg', $symbol[0]->asXML() );
			$svg    = str_replace( '<svg ', '<svg xmlns="http://www.w3.org/2000/svg" ', $svg );
			file_put_contents( $file_path, $svg );
		}

		return $file_path;
	}
}
