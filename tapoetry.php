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
function filter_search($query) {
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
function mvc_metadata() {
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

/* Add shortocode, meta-data, admin css, and mvc_* content types for search */
add_shortcode( 'tapoetry-readings', 'tapoetry_readings' );
add_action('wp_head', 'mvc_metadata');
add_action('mvc_admin_init', 'tapoetry_on_mvc_admin_init');
add_filter('pre_get_posts', 'filter_search');
