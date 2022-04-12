<?php
/*
Plugin Name:    Context Bootstrapify
Description:    Apply Bootstrap styling to output elements and WordPress post editor.
Text-Domain:    cxt-bootstrapify
Version:        1.1
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
function wpdocs_theme_add_editor_styles() {
    add_editor_style( array(
        'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
        plugin_dir_url( __FILE__ ).'editor.css'
    ) );
}
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );

/**
 * Wrap element in another element.
 * eg. wrap <blockquote> in <figure>
 * Utility function.
 *
 * @param DOMDocument   $dom                Whole DOM object containing the elements to be wrapped
 * @param DOMDocument   $wrapped_element    Element to be wrapped
 * @param string        $new_element_name   Name of the new element to be created
 * @param string        $class              Class to be added to the new element
 * 
 * @author Robert Andrews, inspired by @XzKto, https://stackoverflow.com/a/8428323/1375163
 */
function wrap_element($dom, $wrapped_element, $new_element, $class=null) {
    // Initialise the new wrapper
    $wrapper = $dom->createElement($new_element);
    // Clone our created element
    $wrapper_clone = $wrapper->cloneNode();
    // Replace image with this wrapper div
    $wrapped_element->parentNode->replaceChild($wrapper_clone,$wrapped_element);
    // Append the element to wrapper div
    $wrapper_clone->appendChild($wrapped_element);
    // Add passed class
    if (!empty($class)) {
        $wrapper_clone->setAttribute('class', $class);
    }
}

/**
 * Bootstrapify blockquote elements
 * - Apply .blockquote class to <blockquote> elements
 * - Apply style classes to <blockquote>
 * - Wrap <blockquote> in <figure> - https://getbootstrap.com/docs/5.0/content/typography/#blockquotes
 * - Ignore tweet embeds (iframes fall back to blockquote with class .twitter-tweet)
 *
 * @param DOMDocument   $content            WordPress post content from the_content()
 * 
 * @author Robert Andrews
 */
add_filter( 'the_content', 'bootstrap_blockquote', 30 );
function bootstrap_blockquote( $content ) {
    // Load DOM of post content
    
    // $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
    // $dom = new DOMDocument('1.0', 'utf-8');
    // libxml_use_internal_errors(true);
    // $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    // $content = utf8_decode($content); // https://stackoverflow.com/questions/1269485/how-do-i-tell-domdocument-load-what-encoding-i-want-it-to-use
    $dom = new DOMDocument('1.0', 'iso-8859-1');
    libxml_use_internal_errors(true);
    // $dom->loadHTML($content);
    $dom->loadhtml(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();

    // For every <blockquote> found
    foreach($dom->getElementsByTagName('blockquote') as $blockquote){
        // Except tweet embeds
        $blockquote_class = $blockquote->getAttribute('class');
        if ($blockquote_class != 'twitter-tweet') {
            // Add .blockquote class - class addition contributed by @Gillu13, https://stackoverflow.com/a/63088684/1375163
            $class_to_add = 'blockquote border-start p-4 bg-light';
            $blockquote->setAttribute('class', $class_to_add);
            // Wrap blockquote in <figure>
            wrap_element($dom, $blockquote, 'figure');
        }
    }
    $content = $dom->saveHTML();
    return $content;
}

?>