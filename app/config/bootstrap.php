<?php

MvcConfiguration::set(array(
    /* Set permission levels for different components */
	'admin_controller_capabilities' => array(
                        'hosts' => 'administrator',
						'partners' => 'administrator',
						'poets' => 'edit_others_posts',
						'readings' => 'edit_others_posts'),
	'Debug' => false //for production
));
