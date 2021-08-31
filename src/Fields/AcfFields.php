<?php


namespace GooseStudio\AcfIcons\Fields;


class AcfFields
{
    public function init()
    {
        add_action('acf/include_field_types', array($this, 'include_fields')); // v5
    }

    public function include_fields()
    {
        $settings = array(
            'version' => GS_ACF_ICONS_VERSION,
            'url' => plugin_dir_url(GS_ACF_ICONS_PLUGIN_FILE__FILE),
            'path' => plugin_dir_path(GS_ACF_ICONS_PLUGIN_FILE__FILE)
        );
        new AcfIconField($settings);
    }
}
