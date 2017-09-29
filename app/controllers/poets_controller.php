<?php

class PoetsController extends MvcPublicController {

    /* @see HostsController::show() but note use of join table in this case */
	public function show() {
        global $post;

        $object = $this->model->find_by_id($this->params['id'], array(
                'includes' => array('Reading' => array('join_table' => '{prefix}readings_poets'))
        ));

        if (!empty($object)) {
            if ($object->published != 1) {
                $index_url = mvc_public_url( array('controller' => 'poets', 'action' => 'index'));
                header('HTTP/1.x 404 Not Found');
                $this->redirect($index_url);
            }
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
