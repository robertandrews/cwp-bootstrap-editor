<?php
/*
Plugin Name:    Context WP Editor Bootstrap
Description:    Apply Bootstrap styling to the WordPress post editor.
Text-Domain:    cxt-wp-editor-bootstrap
Version:        1.2
Author:         Robert Andrews
Author URI:     http://www.robertandrews.co.uk
License:        GPL v2 or later
License URI:    https://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Bootstrapify the WordPress TinyMCE post edit box
 * - Add Bootstrap CSS to editor
 * - Apply additional margin to editor
 * This applies Bootstrap styling to post content in the editor.
 * It does not add user-insertable Bootstrap components.
 */

// add_theme_support( 'editor-styles' );




function wpdocs_theme_add_editor_styles() {
    // Remove any styles applied by themes: https://developer.wordpress.org/reference/functions/remove_editor_styles/
    remove_editor_styles();
    // Now we can add our own styles
    add_editor_style( array(
        // CSS file: Use Bootstrap
        'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
        // CSS file: Add custom CSS rules
        plugin_dir_url( __FILE__ ).'editor.css'
    ) );
}
// Fires as an admin screen or script is being initialized
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );

?>