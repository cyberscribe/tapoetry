<?php

class PartnersController extends MvcPublicController {
	
    /* @see HostsController::show() */
	public function show() {
        global $post;
	
		$object = $this->model->find_by_id($this->params['id'], array(
			'includes' => array('Reading')
		));
		
		if (!empty($object)) {
            if (!isset($this->params['extra']) || strlen($this->params['extra']) == 0) {
                    $extra = urlify( $object->name );
                    $base = $_SERVER['REQUEST_URI'];
                    $base = substr( $base, 0, (strlen($base) - 1) );
                    $this->redirect( $base . '-' . $extra);
            }
            $post = get_post( $object->post_id, OBJECT );
            setup_postdata( $post );
			$this->set('object', $object);
		}

	}
	
}
