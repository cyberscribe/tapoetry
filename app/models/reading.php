<?php

class Reading extends MvcModel {
	
	var $display_field = 'name';
	var $order = 'Reading.date DESC';
	var $includes = array('Partner', 'Poet', 'Host');
	var $belongs_to = array('Partner','Host');
    /* Used to specify many-to-many relationship between readings and poets */
	var $has_and_belongs_to_many = array(
		'Poet' => array(
			'join_table' => '{prefix}readings_poets',
		)
	);
    var $per_page = 99999; //do not paginate

    var $wp_post = array(
        'post_type' => array(
          'args' => array(
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php',
            'supports' => array('title','editor','thumbnail'),
            'menu_icon' => 'dashicons-desktop',
          ),
          'fields' => array(
            'post_title' => '$title',
            'post_excerpt' => '$description',
            'post_content' => '$description',
            'guid' => 'get_guid()',
          )
        )
    );

    static function get_guid($object) {
        $url = get_site_url() . '/' . MvcInflector::pluralize(strtolower($object->__model_name)) . '/' . $object->id . '-' . urlify($object->title) . '/';
        return $url;
    }

    /* After find, compose a list of poet names involved for convenience */
	public function after_find(&$object) {
		if (isset($object->poets)) {
			$poet_names = array();
			foreach($object->poets as $poet) {
				$poet_names[] = $poet->name;
			}
			$object->poet_names = implode(', ', $poet_names);
		}
	}
}
