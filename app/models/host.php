<?php

class Host extends MvcModel {
	
	var $display_field = 'name';
	var $has_many = array('Reading');
    var $per_page = 99999; //do not paginate
    var $order = "Host.last_name, Host.first_name";

    var $wp_post = array(
        'post_type' => array(
          'args' => array(
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php',
            'supports' => array('title','editor','thumbnail'),
            'menu_icon' => 'dashicons-groups',
          ),
          'fields' => array(
            'post_title' => 'get_title()',
            'post_excerpt' => '$description',
            'post_content' => '$description',
            'guid' => 'get_guid()',
          )
        )
    );

    public function get_title($object) {
        return $object->first_name . ' ' . $object->last_name;
    }

    /* Set the correct GUID in the custom post type, including seo-friendly appended text */
    static function get_guid($object) {
        $url = get_site_url() . '/' . MvcInflector::pluralize(strtolower($object->__model_name)) . '/' . $object->id . '-' . urlify($object->__name) . '/';
        return $url;
    }

    /* Set a name using first [space] last */
    public function after_find(&$object) {
            $object->name = $object->first_name . ' ' . $object->last_name;
    }

    /* After save, update longitude and latitude in database based on the textual location string */
    public function after_save($object) {
        $this->update_lon_lat($object);
    }

    /* Geocode lon/lat from the object's location text string, save to database */
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
