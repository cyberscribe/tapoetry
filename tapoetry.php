<?php
/*
Plugin Name: Transatlantic Poetry on Air Events
Plugin URI: http://www.transatlanticpoetry.com/
Description: Enables event, poet, host, and partner content types
Author: Robert Peake
Version: 1.0
Author URI: http://www.robertpeake.com/
*/

/* standard wp-mvc activate/deactivate hooks here*/
register_activation_hook(__FILE__, 'tapoetry_activate');
register_deactivation_hook(__FILE__, 'tapoetry_deactivate');

function tapoetry_activate() {
    /* an extra check that wp-mvc is installed */
    if (!class_exists('MvcConfiguration')) {
        tapoetry_trigger_error_message(__('This plugin depends upon the wp-mvc plugin being installed and activated.','tapoetry'));
    }
    require_once dirname(__FILE__).'/tapoetry_loader.php';
    $loader = new TapoetryLoader();
    $loader->activate();
}

function tapoetry_deactivate() {
    require_once dirname(__FILE__).'/tapoetry_loader.php';
    $loader = new TapoetryLoader();
    $loader->deactivate();
}

/* display error messages independent of context */
function tapoetry_trigger_error_message($message) {
    if(isset($_GET['action']) && $_GET['action'] == 'error_scrape') {
        echo '<div class="wrap"><p style="font-family: \'Open Sans\',sans-serif; font-size: 13px; color: #444;">' . $message .
        exit;
    } else {
        throw new Exception($message);
    }
}

/* add the mvc_* content types to the search */
function tapoetry_filter_search($query) {
    if ($query->is_search) {
        $query->set('post_type', array('post', 'page', 'mvc_poet', 'mvc_host', 'mvc_partner', 'mvc_reading'));
    };
    return $query;
};

/* convert "The Great New Reading" to "the-great-new-reading" for seo-friendly URLs */
function urlify( $string ) {
    $string = str_replace('.', '', $string);
    $string = str_replace('/', '', $string);
    $string = preg_replace('/[\s]+/', '-', $string);
    $string = filter_var( $string, FILTER_SANITIZE_URL);
    $string = strtolower($string);
    return $string;
}

/* Inject OG meta-data to improve social sharing */
function tapoetry_mvc_metadata() {
    global $post;
    if (is_mvc_page()) {
        $cur_url = parse_url($_SERVER["REQUEST_URI"]);
        $this_url_str = MvcRouter::public_url( array('controller' => 'readings', 'action' => 'index') );
        $this_url = parse_url( $this_url_str );
        preg_match('%^'.$this_url['path'].'(\d+)%',$cur_url['path'],$matches);
        if (isset($matches[1])) {
            $id = $matches[1];
            $reading_model = mvc_model('Reading');
            $reading = $reading_model->find_one_by_id($id);
            echo '<meta property="og:title" content="'.htmlspecialchars($reading->title).'">';
            echo '<meta property="og:description" content="'.htmlspecialchars(strip_tags($reading->description)).'">';
            echo '<meta property="og:image" content="'.$reading->banner_url.'">';
            if (isset($reading->video_url) && strlen($reading->video_url) > 0) {
                preg_match('/youtube\.com\/watch\?v=([^&]+)/',$reading->video_url,$matches2);
                if (isset($matches2[1])) {
                    echo '<meta property="og:video" content="http://www.youtube.com/v/'.$matches2[1].'?version=3&amp;autohide=1" />';
                    echo '<meta property="og:video:type" content="application/x-shockwave-flash" />';
                    echo '<meta property="og:video:width" content="1280" />';
                    echo '<meta property="og:video:height" content="720" />';
                }
            }
        }
    }
}

/* A special shortcode to display readings embedded on a page */
function tapoetry_readings( $atts ) {
        extract( shortcode_atts( array(
        'type' => 'upcoming',
        'order' => 'ASC'
                ), $atts, 'tapoetry' ) );
    switch ($atts['type']) {
        default: 
            $model = mvc_model('Reading');
            $objects = $model->find(array('conditions' => array(
                                    'Reading.date >=' => date('Y-m-d'),
                            ),
                            'order' => 'Reading.date '.$atts['order']
                          ));
            $return = '';
            $path = plugin_dir_path( __FILE__ ).'app/views/readings/';
            foreach($objects as $object) {
                if (isset($object->banner_url)) {
                    ob_start();
                    include $path.'_banner.php';
                    $return .= ob_get_contents();
                    ob_end_clean();
                }
            }
        break;
    }
    return $return;    
}

/* Add a bit of CSS to the admin */
function tapoetry_on_mvc_admin_init($options) {
    wp_register_style('mvc_admin', mvc_css_url('tapoetry', 'admin'));
    wp_enqueue_style('mvc_admin');
}
function tapoetry_thumbnail_sepia_size() {
    add_image_size('medium-sepia', 400, 400, true);
}
function tapoetry_add_custom_sizes( $sizes ) {
    $new_sizes = array();
    foreach($sizes as $key => $value) {
        $new_sizes[$key] = $value;
        if ($key == 'medium') { //place this menu item directly after the "Medium" item
            $new_sizes['medium-sepia'] = __( 'Medium Sepia', 'tapoetry' );
        }
    }
    return $new_sizes;
}
function tapoetry_thumbnail_sepia_filter($meta) {
    try {
        $file = wp_upload_dir();
        $file = trailingslashit($file['path']).$meta['sizes']['medium-sepia']['file'];
        list($orig_w, $orig_h, $orig_type) = @getimagesize($file);
        $image = wp_load_image($file);
        /* Signature sepia tint for images */
        imagefilter($image, IMG_FILTER_BRIGHTNESS, -26); // 10% decrease to compensate for tint
        imagefilter($image, IMG_FILTER_CONTRAST, 13); //5% decrease to avoid blow-out
        imagefilter($image, IMG_FILTER_GRAYSCALE); //desaturate
        imagefilter($image, IMG_FILTER_COLORIZE, 51, 39, 5); //tint
        imagefilter($image, IMG_FILTER_BRIGHTNESS, -26); // 10% decrease to compensate for tint
        imagefilter($image, IMG_FILTER_CONTRAST, -13); //5% increase to restore contrast
        switch ($orig_type) {
        case IMAGETYPE_GIF:
            imagegif( $image, $file );
            break;
        case IMAGETYPE_PNG:
            imagepng( $image, $file );
            break;
        case IMAGETYPE_JPEG:
            imagejpeg( $image, $file );
            break;
        }
    } catch (Exception $e) {
        //tapoetry_trigger_error_message($e->getMessage());
    }
    return $meta;
} 

/* Add shortocode for readings */ 
add_shortcode( 'tapoetry-readings', 'tapoetry_readings' );
/* add sepia image type, add meta-data to mvc_* content types, load admin css */
add_action('after_setup_theme','tapoetry_thumbnail_sepia_size');
add_action('wp_head', 'tapoetry_mvc_metadata');
add_action('mvc_admin_init', 'tapoetry_on_mvc_admin_init');
/* add mvc_* content types for search, apply sepia to sepia thumbnail image type and add to Media Library */
add_filter('pre_get_posts', 'tapoetry_filter_search');
add_filter('wp_generate_attachment_metadata','tapoetry_thumbnail_sepia_filter');
add_filter( 'image_size_names_choose', 'tapoetry_add_custom_sizes' );
