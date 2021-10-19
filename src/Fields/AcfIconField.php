<?php


namespace GooseStudio\AcfIcons\Fields;


use acf_field;

class AcfIconField extends acf_field
{


    /*
    *  __construct
    *
    *  This function will setup the field type data
    *
    *  @type    function
    *  @date    5/03/2014
    *  @since    5.0.0
    *
    *  @param    n/a
    *  @return    n/a
    */

    private $settings;

    public function __construct($settings)
    {
        /*
        *  name (string) Single word, no spaces. Underscores allowed
        */

        $this->name = 'gs-acf-icon';


        /*
        *  label (string) Multiple words, can include spaces, visible when selecting a field type
        */

        $this->label = __('Icon', 'gs-acf');


        /*
        *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
        */

        $this->category = 'content';


        /*
        *  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
        */

        $this->defaults = array(
            'preview_font_size' => 100,
            'return_format' => 'class',
        );


        /*
        *  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
        *  var message = acf._e('FIELD_NAME', 'error');
        */

        $this->l10n = array();


        /*
        *  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
        */

        $this->settings = $settings;


        // do not delete!
        parent::__construct();
    }


    /*
    *  render_field_settings()
    *
    *  Create extra settings for your field. These are visible when editing a field
    *
    *  @type    action
    *  @since    3.6
    *  @date    23/01/13
    *
    *  @param    $field (array) the $field being edited
    *  @return    n/a
    */


    public static function get_css_handle($css_class)
    {
        $prefix = substr($css_class, 0, 3);
        switch ($prefix) {
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

    /*
    *  input_admin_enqueue_scripts()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your render_field() action.
    *
    *  @type    action (admin_enqueue_scripts)
    *  @since    3.6
    *  @date    23/01/13
    *
    *  @param    n/a
    *  @return    n/a
    */

    public function render_field_settings($field)
    {

        /*
        *  acf_render_field_setting
        *
        *  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
        *  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
        *
        *  More than one setting can be added by copy/paste the above code.
        *  Please note that you must also have a matching $defaults value for the field name (font_size)
        */
        acf_render_field_setting(
            $field, array(
            'label' => __('Preview font size', 'gs-acf'),
            'instructions' => __('The size of the preview', 'gs-acf'),
            'type' => 'text',
            'name' => 'preview_font_size',
            'ui' => 1,
            )
        );
        acf_render_field_setting(
            $field, array(
            'label' => __('Return format', 'gs-acf'),
            'instructions' => __('Which icon format to return', 'gs-acf'),
            'type' => 'select',
            'choices' => ['class' => 'CSS Class', 'svg_sprite_url' => 'SVG Sprite URL', 'svg_path' => 'SVG File Path', 'svg_raw' => 'Raw SVG'],
            'name' => 'return_format',
            'ui' => 1,
            )
        );
    }

    public function render_field($field)
    {
        $width = (int)$field['preview_font_size'] * 1.2;
        $font_size = (int)$field['preview_font_size'];
        list(, $css, $style) = explode(':', $field['value'] . ':' . ':');
        $css_class = str_replace('%', $css, $style);
        if ($css_class === $style) {
            $css_class = $style . ' ' . $css;
        }
        $field_id = $field['id'];
        ?>
        <!--suppress HtmlFormInputWithoutLabel -->
        <input type="hidden" name="<?php echo esc_attr($field['name']) ?>"
               value="<?php echo esc_attr($field['value']) ?>"/>
        <div>
            <div class="gs-acf-icon-field"
                 data-acf-field="<?php echo esc_attr($field_id) ?>"
                 style="width: <?php echo esc_attr($width) ?>px;height:<?php echo esc_attr($width) ?>px;font-size:<?php echo esc_attr($font_size) ?>px">
                <?php if ($css_class) : ?>
                    <i class="<?php echo esc_attr($css_class) ?>"></i>
                <?php endif ?>
            </div>
            <button style="height:28px" class="gs-acf-icon-field-select button"
                    data-acf-field="<?php echo esc_attr($field_id) ?>">Select icon
            </button>
            <button style="height:28px;" class="gs-acf-icon-field-remove button" data-acf-field="<?php echo esc_attr($field_id) ?>"><i class="fa fa-trash"></i></button>
        </div>        <?php
    }

    /*
    *  input_admin_footer()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your render_field() action.
    *
    *  @type    action (admin_footer)
    *  @since    3.6
    *  @date    23/01/13
    *
    *  @param    n/a
    *  @return    n/a
    */

    public function input_admin_enqueue_scripts()
    {
        if (get_post_type() === 'acf-field-group') {
            return;
        }
        // vars
        $url = $this->settings['url'];
        $version = $this->settings['version'];


        // register & include JS
        wp_register_script('gs-acfe-fields-mappings', "{$url}assets/dependencies/mappings.js", array(), $version);
        wp_register_script(
            'gs-acfe-fields', "{$url}assets/js/acf-fields.js", array(
            'acf-input',
            'wp-util',
            'jquery-ui-dialog',
            'gs-acfe-fields-mappings'
            ), $version
        );
        wp_enqueue_script('gs-acfe-fields');


        // register & include CSS
        wp_register_style(
            'gs-acfe-fields', "{$url}assets/css/acf-fields.css", array(
            'acf-input',
            'wp-jquery-ui-dialog'
            ), $version
        );
        wp_enqueue_style('gs-acfe-fields');
        wp_dequeue_style('font-awesome-5-all');
        wp_enqueue_style('ionicons');
        wp_enqueue_style('font-awesome-5-all');
    }

    /**
     * 
     *
     * @noinspection PhpUnused 
     */
    public function input_admin_footer($fields)
    {
        ?>
        <div id="icon-dialog"></div>
        <?php // Template for the media frame: used both in the media grid and in the media modal.
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
                        <div class="icon-library-toolbar-primary search-form"><label for="icon-search-input"
                                                                                     class="screen-reader-text">Search
                                Icons</label><input type="search" placeholder="Search icons ..." id="icon-search-input"
                                                    class="search"></div>
                    </div>
                    <p style="text-align: center;font-size: 1.2em">Found <span class="icon-search-result-total"></span>
                        icons </p>
                    <div class="icon-library-list-wrapper">
                        <div class="icon-library-list">
                        </div>
                        <div style="text-align:center;width:100%;margin-top:20px">
                            <button class="icon-library-load-more button button-secondary"
                                    style="padding:10px;font-size:20px;height: auto;">Load more icons ...
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
            <div onclick="on_icon_click_fn(this)" class="icon-font" data-font-css="{{ meta_datum.css }}"
                 data-font-library="{{ meta_datum.library }}" data-font-style="{{ style }}"
                 title="{{meta_datum.label}}">
                <div class="icon-font-wrapper">
                    <i class="{{ style }} {{ css }}"></i>
                    <div>{{ meta_datum.label }}</div>
                </div>
            </div>
            <# }); #>
        </script>
        <?php
    }
    /** @noinspection PhpUnusedParameterInspection */
    /** @noinspection PhpUnusedParameterInspection */

    /**
     *  format_value()
     *
     *  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
     *
     * @param $value   (mixed) the value which was loaded from the database
     * @param $post_id (mixed) the $post_id from which the value was loaded
     * @param $field   (array) the field array holding all the field options
     *
     * @return $value (mixed) the modified value
     * @since  3.6
     * @date   23/01/13
     */
    public function format_value($value, $post_id, $field)
    {
        // bail early if no value
        if (empty($value)) {

            return $value;

        }
        list($library, $css, $style) = explode(':', $value);
        //                    'choices'       => ['class' => 'CSS Class', 'svg_sprite_url' => 'SVG Sprite URL', 'svg_url' => 'SVG URL', 'svg_raw' => 'Raw SVG'],
        $css_class = str_replace('%', $css, $style);
        if ($css_class === $style) {
            $css_class = $style . ' ' . $css;
        }
        switch ($field['return_format']) {
        case 'svg_sprite_url':
            if ('ionicons' === $library) {
                $svg = 'ionicons.svg#' . $css_class;
            } elseif ('font-awesome' === $library) {
                $f_type = substr($style, 0, 3);
                if ($f_type === 'fas') {
                     $sprites = 'solid';
                } else if ($f_type === 'far') {
                    $sprites = 'regular';
                } else {
                    $sprites = 'brands';
                }
                    return $this->get_svg_url_path($library, $css, $sprites, $css);

            } elseif ('elementor' === $library) {
                $svg = 'eicons.svg#' . trim($css_class);
            }
            return sprintf(plugin_dir_url(GS_ACF_ICONS_PLUGIN_FILE__FILE) . '/assets/dependencies/%s/sprites/%s', $library, $svg);
        case 'svg_path':
            return $this->get_svg_file_path($library, $css, $css_class);
        case 'svg_raw':
            return file_get_contents($this->get_svg_file_path($library, $css, $css_class));
        default:
            return $css_class;
        }
    }

    public function get_base_dir($path)
    {
        $dir = wp_get_upload_dir();
        $base_dir = $dir['basedir'];
        if(!file_exists($base_dir . '/acf-icons/'.$path)) {
            wp_mkdir_p($base_dir . '/acf-icons/'.$path);
        }
        return $base_dir . '/acf-icons/'.$path;
    }

    /**
     * @param $library
     * @param $css
     * @param $css_class
     *
     * @return string
     */
    public function get_svg_file_path($library, $css, $css_class)
    {
        if ('ionicons' === $library) {
            $sprites = 'ionicons';
        } elseif ('font-awesome' === $library) {
            $f_type = substr($css_class, 0, 3);
            if ($f_type === 'fas') {
                $sprites = 'solid';
            } else if ($f_type === 'far') {
                $sprites = 'regular';
            } else {
                $sprites = 'brands';
            }
        } elseif ('elementor' === $library) {
            $css_class = trim($css_class);
            $sprites = 'eicons';
        }

        $dir       = $this->get_base_dir($library . '/');
        $file_path = $dir . $css . '.svg';
        if (! file_exists($file_path) ) {
            $xml    = simplexml_load_file(sprintf(GS_ACF_ICONS_DIR . '/assets/dependencies/%s/sprites/%s.svg', $library, $sprites));
            $symbol = $xml->xpath("//*[@id=\"$css_class\"]");
            $svg = str_replace('symbol', 'svg', $symbol[0]->asXML());
            $svg = str_replace('<svg ', '<svg xmlns="http://www.w3.org/2000/svg" ', $svg);
            file_put_contents($file_path, $svg);
        }

        return $file_path;
    }

    public function get_svg_url_path( $library, $css, $sprites, $css_class )
    {
        $dir       = $this->get_base_dir($library . '/');
        $file_path = $dir . $css . '.svg';
        if (! file_exists($file_path) ) {
            $this->get_svg_file_path($library, $css, $css_class);
        }
        $dir = wp_get_upload_dir();
        $base_url = $dir['baseurl']. '/acf-icons/'.$library.'/';
        return $base_url . $css . '.svg';
    }
}
