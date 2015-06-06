<?php

class MapController extends MvcPublicController {

    /* Get all poets, partners, and hosts in one array of objects for map view to render in map layout */
    public function index() {
        $poet_model = mvc_model('Poet');
        $poets = $poet_model->find();
        $partner_model = mvc_model('Partner');
        $partners = $partner_model->find();
        $host_model = mvc_model('Host');
        $hosts = $host_model->find();
        $objects = array_merge($poets, $partners, $hosts);
        $this->set('objects', $objects);
        $this->render_view('map', array('layout' => 'map'));
    }

    public function show() {
        $this->set('objects',array());
        $reading_model = mvc_model('Reading');
        if (is_numeric($this->params['id'])) {
            $reading = $reading_model->find_one_by_id($this->params['id']);
            $this->set('objects', array_merge(array( $reading->partner, $reading->host ), $reading->poets ) );
        }
        $this->render_view('map', array('layout' => 'map'));
    }
}
