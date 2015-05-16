<?php

MvcConfiguration::set(array(
	'admin_controller_capabilities' => array('hosts' => 'administrator',
						 'partners' => 'administrator',
						'poets' => 'edit_others_posts',
						'readings' => 'edit_others_posts'),
	'Debug' => false
));

add_action('mvc_admin_init', 'tapoetry_on_mvc_admin_init');

function tapoetry_on_mvc_admin_init($options) {
	wp_register_style('mvc_admin', mvc_css_url('tapoetry', 'admin'));
	wp_enqueue_style('mvc_admin');
}

?>
