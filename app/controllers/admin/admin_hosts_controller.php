<?php

class AdminHostsController extends MvcAdminController {
	
	var $default_columns = array(
		'id',
		'name',
	);
	var $default_searchable_fields = array('first_name','last_name','description');
	
}

?>
