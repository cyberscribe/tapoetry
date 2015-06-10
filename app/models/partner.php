<?php

class Partner extends MvcModel {
	
	var $display_field = 'name';
	var $has_many = array('Reading');
    var $per_page = 99999; //do not paginate

    var $wp_post = array(
        'post_type' => array(
          'args' => array(
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php',
            'supports' => array('title','editor','thumbnail'),
            'menu_icon' => 'dashicons-admin-site'
          ),
          'fields' => array(
            'post_title' => '$name',
            'post_excerpt' => '$description',
            'post_content' => '$description',
            'guid' => 'get_guid()',
          )
        )
    );

    static function get_guid($object) {
        $url = get_site_url() . '/' . MvcInflector::pluralize(strtolower($object->__model_name)) . '/' . $object->id . '-' . urlify($object->__name) . '/';
        return $url;
    }

    public function after_save($object) {
        $this->update_lon_lat($object);
    }

    /* @see Host::update_lon_lat */
    private function update_lon_lat($object) {
        $url = sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false', urlencode($object->location));
        $result = file_get_contents($url);
        $geocode = json_decode($result);
        $result_obj = $geocode->results[0];
        $geometry = $result_obj->geometry;
        $location = $geometry->location;
        $lat = $location->lat;
        $lon = $location->lng;
        $this->update( $object->__id, array('lat' => $lat, 'lon' => $lon));
    }

}
