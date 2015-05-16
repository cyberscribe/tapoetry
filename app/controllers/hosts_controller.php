<?php

class HostsController extends MvcPublicController {
	
	public function show() {
        global $post;
	
        /* get the Host, include all Readings they have done */
		$object = $this->model->find_by_id($this->params['id'], array(
			'includes' => array('Reading')
		));
		
		if (!empty($object)) {
            /* If the seo-friendly text has not been appended, redirect to a version with it appended */
            if (!isset($this->params['extra']) || strlen($this->params['extra']) == 0) {
                    $extra = urlify( $object->name );
                    $base = $_SERVER['REQUEST_URI'];
                    $base = substr( $base, 0, (strlen($base) - 1) );
                    $this->redirect( $base . '-' . $extra);
            }
            /* Get corresponding custom content type, and set up post data for template tags */
            $post = get_post( $object->post_id, OBJECT );
            setup_postdata( $post );
			$this->set('object', $object);
		}

	}
	
}
