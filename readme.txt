=== GS ACF Icons ===
Contributors: andreasnrb, goosestudio
Tags: acf, icons, advanced custom fields
Requires at least: 5.0
Tested up to: 5.8
Requires PHP: 5.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt


== Description ==
The ACF icon plugin adds a new field to ACF that enables users to select an icon from a popup.
It supports both font icons and SVG icons.
You cannot limit to certain icons or add new icon providers as of writing which is version 1.4.

If you are using ACF functions the_field() with you need to enqueue the required CSS.
Use ACFIcon::get_css_handle(get_the_field('your-icon-field-id')) to retrieve the correct CSS handle for the selected icon.

== Installation ==

1. Download the plugin from the link provided in your order email
2. Upload the zip file to your site
3. Activate the plugin

== Frequently Asked Questions ==

= How do I use this? =
There are few methods either use a plugin such as Advanced Elements that displays ACF fields or add new code to
your theme templates that calls the default ACF functions.
If you are using ACF functions the_field() with you need to enqueue the required CSS.
Use ACFIcon::get_css_handle(get_the_field('your-icon-field-id')) to retrieve the correct CSS handle for the selected icon.

== Changelog ==

= 0.1.4 =
* Add usage instructions
* Remove comment artifacts
* Remove EDD integration
