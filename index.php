<?php
/**
 * Plugin Name: WP ACF Markdown Field
 * Plugin URI: https://github.com/freshsystems/wp-acf-markdown-field
 * Description: Adds a Markdown field to Advanced Custom Fields.
 * Author: Fresh Systems
 * Author URI: https://freshsystems.co.uk
 * Version: 1.0.0
 */

namespace Fresh\ACFMarkdownField;

defined( 'ABSPATH' ) or die();

define( 'FRESH_ACF_MARKDOWN_FIELD_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'FRESH_ACF_MARKDOWN_FIELD_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'FRESH_ACF_MARKDOWN_FIELD_PLUGIN_VERSION', '1.0.0' );

/** 
* Include the field type class
* @param int $version Major ACF version. Defaults to false
* @return void
*/
add_action( 'acf/include_field_types', function( $version = false )
{
    /**
     * Plugin PSR-4 Autoload
     * @param string $class The fully-qualified class name.
     * @return void
     */
    spl_autoload_register( function($class) 
    {
        $prefix = __NAMESPACE__;
        $base_dir = FRESH_ACF_MARKDOWN_FIELD_PLUGIN_PATH . '/src/';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        // if the file exists, require it
        if (file_exists($file)) require $file;
    });

    // Init the field:
    new MarkdownField(
        [
            'version' => FRESH_ACF_MARKDOWN_FIELD_PLUGIN_VERSION,
            'url' => FRESH_ACF_MARKDOWN_FIELD_PLUGIN_URL,
            'path' => FRESH_ACF_MARKDOWN_FIELD_PLUGIN_PATH,
        ],
        ( new Helpers() ) 
    );
});
