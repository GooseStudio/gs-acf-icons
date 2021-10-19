<?php
/*
Plugin Name: GS ACF Icons
Plugin URI: https://goose.studio/products/acf-icons/
Description: Extends ACF plugin with a new field, Icon.
Version: 0.1.3
Author: Goose Studio
Author URI: https://goose.studio/
Copyright: Andreas Nurbo
Text Domain: gs-acf-icons
License: GPLv3
Domain Path: /lang
*/

/**
 * Copyright (C) 2021  Andreas Nurbo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

use GooseStudio\AcfIcons\AcfIcons;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
if (version_compare(PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION, '5.6', '<')) {
    add_action(
        'admin_notices', function () {
            $message = sprintf('ACF Icons requires at least PHP version 5.6. You currently have %s. ', PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION);
            echo '<div class="error"><p>' . esc_html($message) . '</p></div>';
        }
    );

    return;
}

define('GS_ACF_ICONS_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('GS_ACF_ICONS_PLUGIN_FILE__FILE', __FILE__);
define('GS_ACF_ICONS_DIR', __DIR__);
define('GS_ACF_ICONS_VERSION', '0.1.3');
define('GS_ACF_ICONS_ASSET_VERSION', GS_ACF_ICONS_VERSION . '.1');

require __DIR__ . '/autoloader.php';

(new AcfIcons())->init();

/**
 * Load gettext translate for our text domain.
 *
 * @return void
 * @since  0.1
 */
function gs_acf_icons_load_control_manager()
{
    load_plugin_textdomain('gs-acf-icons');

    $pro = 'advanced-custom-fields-pro/acf.php';
    $free = 'advanced-custom-fields/acf.php';

    if (!gs_acf_icons_is_acf_installed() || (!is_plugin_active($pro) && !is_plugin_active($free))) {
        add_action('admin_notices', 'gs_acf_icons_acf_fail_load');

        return;
    }
}

add_action('plugins_loaded', 'gs_acf_icons_load_control_manager');

function gs_acf_icons_acf_fail_load()
{
    $screen = get_current_screen();
    if (null === $screen) {
        return;
    }
    if (null !== $screen->parent_file && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
        return;
    }

    $plugin = gs_acf_icons_get_acf_version();

    if (!is_plugin_active($plugin) && gs_acf_icons_is_acf_installed()) {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);

        $message = '<p>' . __('ACF Icons is not working because you need to activate the Advanced Custom Fields plugin.', 'gs-acf-icons') . '</p>';
        $message .= '<p>' . sprintf('<a href="%s" id="gs-ae-activate-acf"  class="button-primary">%s</a>', $activation_url, __('Activate Advanced Custom Fields Now', 'gs-acf-icons')) . '</p>';
    } else {
        if (!current_user_can('install_plugins')) {
            return;
        }

        $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=advanced-custom-fields'), 'install-plugin_advanced-custom-fields');

        $message = '<p>' . __('ACF Icons is not working because you need to install the Advanced Custom Fields plugin', 'gs-acf-icons') . '</p>';
        $message .= '<p>' . sprintf('<a href="%s"  id="gs-ae-install-acf"  class="button-primary">%s</a>', $install_url, __('Install Advanced Custom Fields Now', 'gs-acf-icons')) . '</p>';
    }

    echo '<div class="error">' . esc_html($message) . '</div>';
}

function gs_acf_icons_is_acf_installed()
{
    include_once ABSPATH . '/wp-admin/includes/plugin.php';
    $file_path1 = 'advanced-custom-fields-pro/acf.php';
    $file_path2 = 'advanced-custom-fields/acf.php';
    $installed_plugins = get_plugins();

    return isset($installed_plugins[$file_path1]) || isset($installed_plugins[$file_path2]);
}

function gs_acf_icons_get_acf_version()
{
    include_once ABSPATH . '/wp-admin/includes/plugin.php';
    $file_path1 = 'advanced-custom-fields-pro/acf.php';
    $file_path2 = 'advanced-custom-fields/acf.php';
    $installed_plugins = get_plugins();


    if (isset($installed_plugins[$file_path1])) {
        return $file_path1;
    }
    if (isset($installed_plugins[$file_path2])) {
        return $file_path2;
    }

    return '';

}
