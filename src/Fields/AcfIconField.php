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
    *  @type	function
    *  @date	5/03/2014
    *  @since	5.0.0
    *
    *  @param	n/a
    *  @return	n/a
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
        //$this->add_action('enqueue_block_editor_assets', array($this, 'input_admin_enqueue_scripts'), 10, 0);
        //$elementor_json = file_get_contents(GS_ACFE_DIR . '/assets/dependencies/elementor.json');

        /*
        $mappings = [];
        $elementor_json = file_get_contents(GS_ACFE_DIR . '/assets/dependencies/elementor.json');
        $elementor_data = json_decode($elementor_json, ARRAY_A);
        foreach($elementor_data as $key => $meta_datum) {
            $meta_datum['library'] = 'elementor';
            $meta_datum['styles'] = ['regular'];
            $meta_datum['regular'] = '';
            $meta_datum['css'] = 'eicon-'.$key;
            $meta_datum['key'] = $key . '-eicon';
            $mappings[$key . '-eicon'] = $meta_datum;
        }
        $fontawesome_json = file_get_contents(GS_ACFE_DIR . '/assets/dependencies/font-awesome-5.11.json');
        $fontawesome_data = json_decode($fontawesome_json, ARRAY_A);
        foreach($fontawesome_data as $key => $fa_meta_datum) {
            $meta_datum = [];
            $meta_datum['unicode'] = $fa_meta_datum['unicode'];
            $meta_datum['label'] = $fa_meta_datum['label'];
            $meta_datum['library'] = 'font-awesome';
            $meta_datum['styles'] = $fa_meta_datum['styles'];
            foreach($fa_meta_datum['styles'] as $style) {
                $prefix = 'fas';
                if($style === 'brands')
                    $prefix = 'fab';
                elseif($style === 'regular')
                    $prefix = 'far';
                $meta_datum[$style] = $prefix;
            }
            $meta_datum['css'] = 'fa-'.$key;
            $meta_datum['key'] = $key . '-fa5';
            $mappings[$key . '-fa5'] = $meta_datum;
        }
        $ionicons_json = file_get_contents(GS_ACFE_DIR . '/assets/dependencies/ionicons.json');
        $ionicons_data = json_decode($ionicons_json, ARRAY_A);
        foreach($ionicons_data['icons'] as $io_meta_datum) {
            if(strpos($io_meta_datum['icons'][0],'ios-')===0) {
                $meta_datum = [];
                $key = substr($io_meta_datum['icons'][0],4);
                $meta_datum['unicode'] = '';
                $meta_datum['label'] = ucwords(str_replace('-',' ',$key));
                $meta_datum['library'] = 'ionicons';
                $meta_datum['styles'] = ['ios','md'];
                $meta_datum['ios'] = 'ion-ios-%';
                $meta_datum['md'] = 'ion-md-%';
                $meta_datum['css'] = substr($io_meta_datum['icons'][0],4);
                $meta_datum['key'] = $key . '-ion';
                $mappings[$key . '-ion'] = $meta_datum;
            } else {
                $meta_datum = [];
                $meta_datum['unicode'] = '';
                $key = substr($io_meta_datum['icons'][0],5);
                $meta_datum['label'] = ucwords(str_replace('-',' ',substr($io_meta_datum['icons'][0],4)));
                $meta_datum['library'] = 'ionicons';
                $meta_datum['styles'] = ['logo'];
                $meta_datum['logo'] = 'ion-%';
                $meta_datum['css'] = $io_meta_datum['icons'][0];
                $meta_datum['key'] = $key . '-ion';
                $mappings[$key . '-ion'] = $meta_datum;
            }
        }

        ksort($mappings);
        file_put_contents(GS_ACFE_DIR .'/assets/dependencies/mappings.js', 'let gs_acfe_font_data = ' . json_encode(array_values($mappings)). ';');
        /**/
    }


    /*
    *  render_field_settings()
    *
    *  Create extra settings for your field. These are visible when editing a field
    *
    *  @type	action
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$field (array) the $field being edited
    *  @return	n/a
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
    *  @type	action (admin_enqueue_scripts)
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	n/a
    *  @return	n/a
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
        acf_render_field_setting($field, array(
            'label' => __('Preview font size', 'gs-acf'),
            'instructions' => __('The size of the preview', 'gs-acf'),
            'type' => 'text',
            'name' => 'preview_font_size',
            'ui' => 1,
        ));
        acf_render_field_setting($field, array(
            'label' => __('Return format', 'gs-acf'),
            'instructions' => __('Which icon format to return', 'gs-acf'),
            'type' => 'select',
            'choices' => ['class' => 'CSS Class', 'svg_sprite_url' => 'SVG Sprite URL', 'svg_path' => 'SVG File Path', 'svg_raw' => 'Raw SVG'],
            'name' => 'return_format',
            'ui' => 1,
        ));
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
        <input type="hidden" id="<?= $field_id ?>" name="<?php echo esc_attr($field['name']) ?>"
               value="<?php echo esc_attr($field['value']) ?>"/>
        <div>
            <div id="preview-<?= $field_id ?>" class="gs-ae-acf-icon-field"
                 data-acf-field="<?php echo esc_attr($field_id) ?>"
                 style="width: <?= $width ?>px;height:<?= $width ?>px;font-size:<?= $font_size ?>px">
                <?php if ($css_class): ?>
                    <i class="<?= $css_class ?>"></i>
                <?php endif ?>
            </div>
            <button style="height:28px" class="gs-ae-acf-icon-field-select button"
                    data-acf-field="<?php echo esc_attr($field_id) ?>">Select icon
            </button>
            <button style="height:28px;" class="gs-ae-acf-icon-field-remove button" data-acf-field="<?php echo esc_attr($field_id) ?>"><i class="fa fa-trash"></i></button>
        </div>        <?php
    }

    /*
    *  input_admin_footer()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your render_field() action.
    *
    *  @type	action (admin_footer)
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	n/a
    *  @return	n/a
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
        wp_register_script('gs-acfe-fields', "{$url}assets/js/acf-fields.js", array(
            'acf-input',
            'wp-util',
            'jquery-ui-dialog',
            'gs-acfe-fields-mappings'
        ), $version);
        wp_enqueue_script('gs-acfe-fields');


        // register & include CSS
        wp_register_style('gs-acfe-fields', "{$url}assets/css/acf-fields.css", array(
            'acf-input',
            'wp-jquery-ui-dialog'
        ), $version);
        wp_enqueue_style('gs-acfe-fields');
        wp_dequeue_style('font-awesome-5-all');
        wp_enqueue_style('ionicons');
        wp_enqueue_style('font-awesome-5-all');

        /*
        wp_enqueue_script(
            'font-awesome-4-shim',
            self::get_fa_asset_url( 'v4-shims', 'js' ),
            [],
            ELEMENTOR_VERSION
        );
        wp_enqueue_style(
            'font-awesome-5-all',
            self::get_fa_asset_url( 'all' ),
            [],
            ELEMENTOR_VERSION
        );
        wp_enqueue_style(
            'font-awesome-4-shim',
            self::get_fa_asset_url( 'v4-shims' ),
            [],
            ELEMENTOR_VERSION
        );*/

    }

    /** @noinspection PhpUnused */
    public function input_admin_footer($fields)
    { ?>
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
                                    style="padding:10px;font-size:20px;height: auto;display: none;">Load more icons ...
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
    }/** @noinspection PhpUnusedParameterInspection */
    /** @noinspection PhpUnusedParameterInspection */

    /**
     *  format_value()
     *
     *  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
     *
     * @param    $value (mixed) the value which was loaded from the database
     * @param    $post_id (mixed) the $post_id from which the value was loaded
     * @param    $field (array) the field array holding all the field options
     *
     * @return    $value (mixed) the modified value
     * @since    3.6
     * @date    23/01/13
     *
     */

    public function format_value($value, $post_id, $field)
    {
        // bail early if no value
        if (empty($value)) {

            return $value;

        }
        list($library, $css, $style) = explode(':', $value);
        //					'choices'       => ['class' => 'CSS Class', 'svg_sprite_url' => 'SVG Sprite URL', 'svg_url' => 'SVG URL', 'svg_raw' => 'Raw SVG'],
        $css_class = str_replace('%', $css, $style);
        if ($css_class === $style) {
            $css_class = $style . ' ' . $css;
        }
        switch ($field['return_format']) {
            case 'svg_sprite_url':
                if ('ionicons' === $library) {
                    $svg = 'ionicons2.svg#' . $css_class;
                } elseif ('font-awesome' === $library) {
                    $svg = substr($css_class, 3);
                    if ($style === 'fas') {
                        $svg = "solid/$svg";
                    } else if ($style === 'far') {
                        $svg = "regular/$svg";
                    } else {
                        $svg = "brands/$svg";
                    }
                } elseif ('elementor' === $library) {
                    $svg = 'eicons.svg#' . trim($css_class);
                }

                return sprintf(plugin_dir_url(GS_ACF_ICONS_DIR) . '/assets/dependencies/%s/svgs/%s', $library, $svg);
            case 'svg_path':
                if ('ionicons' === $library) {
                    $svg = substr($css_class, 4);
                } elseif ('font-awesome' === $library) {
                    $f_type = substr($style, 0, 3);
                    if ($f_type === 'fas') {
                        $svg = "solid/$css";
                    } else if ($f_type === 'far') {
                        $svg = "regular/$css";
                    } else {
                        $svg = "brands/$css";
                    }
                } elseif ('elementor' === $library) {
                    $svg = substr($css_class, 7);
                }

                return sprintf(GS_ACF_ICONS_DIR . '/assets/dependencies/%s/svgs/%s.svg', $library, $svg);
            case 'svg_raw':
                if ('ionicons' === $library) {
                    $svg = substr($css_class, 4);
                } elseif ('font-awesome' === $library) {
                    $svg = substr($css_class, 3);
                    if ($style === 'fas') {
                        $svg = "solid/$svg";
                    } else if ($style === 'far') {
                        $svg = "regular/$svg";
                    } else {
                        $svg = "brands/$svg";
                    }
                } elseif ('elementor' === 'library') {
                    $svg = substr($css_class, 7);
                }
                return file_get_contents(sprintf(GS_ACF_ICONS_DIR . '/assets/dependencies/%s/svgs/%s.svg', $library, $svg));
            default:
                return $css_class;
        }
    }

    /**/


    /*
    *  validate_value()
    *
    *  This filter is used to perform validation on the value prior to saving.
    *  All values are validated regardless of the field's required setting. This allows you to validate and return
    *  messages to the user if the value is not correct
    *
    *  @type	filter
    *  @date	11/02/2014
    *  @since	5.0.0
    *
    *  @param	$valid (boolean) validation status based on the value and the field's required setting
    *  @param	$value (mixed) the $_POST value
    *  @param	$field (array) the field array holding all the field options
    *  @param	$input (string) the corresponding input name for $_POST value
    *  @return	$valid
    */

    /*

    function validate_value( $valid, $value, $field, $input ){

        // Basic usage
        if( $value < $field['custom_minimum_setting'] )
        {
            $valid = false;
        }


        // Advanced usage
        if( $value < $field['custom_minimum_setting'] )
        {
            $valid = __('The value is too little!','TEXTDOMAIN'),
        }


        // return
        return $valid;

    }

    */


    /*
    *  delete_value()
    *
    *  This action is fired after a value has been deleted from the db.
    *  Please note that saving a blank value is treated as an update, not a delete
    *
    *  @type	action
    *  @date	6/03/2014
    *  @since	5.0.0
    *
    *  @param	$post_id (mixed) the $post_id from which the value was deleted
    *  @param	$key (string) the $meta_key which the value was deleted
    *  @return	n/a
    */

    /*

    function delete_value( $post_id, $key ) {



    }

    */


    /*
    *  load_field()
    *
    *  This filter is applied to the $field after it is loaded from the database
    *
    *  @type	filter
    *  @date	23/01/2013
    *  @since	3.6.0
    *
    *  @param	$field (array) the field array holding all the field options
    *  @return	$field
    */

    /*

    function load_field( $field ) {
        return $field;
    }

    /**/


    /*
    *  update_field()
    *
    *  This filter is applied to the $field before it is saved to the database
    *
    *  @type	filter
    *  @date	23/01/2013
    *  @since	3.6.0
    *
    *  @param	$field (array) the field array holding all the field options
    *  @return	$field
    */

    /*

    function update_field( $field ) {

        return $field;

    }

    */


    /*
    *  delete_field()
    *
    *  This action is fired after a field is deleted from the database
    *
    *  @type	action
    *  @date	11/02/2014
    *  @since	5.0.0
    *
    *  @param	$field (array) the field array holding all the field options
    *  @return	n/a
    */

    /*

    function delete_field( $field ) {



    }

    */

}
