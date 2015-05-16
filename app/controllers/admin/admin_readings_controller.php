<?php

class AdminReadingsController extends MvcAdminController {
	
	var $default_search_joins = array('Host', 'Partner', 'Poet');
	var $default_searchable_fields = array('title', 'description', 'Poet.first_name', 'Poet.last_name', 'Partner.name', 'Host.first_name', 'Host.last_name');
	var $default_columns = array(
		'id',
		'title',
		'date' => array('value_method' => 'admin_column_date'),
		'partner' => array('value_method' => 'partner_edit_link'),
		'poet_names' => 'Poets',
	);

	public function add() {
		$this->set_poets();
		$this->set_partners();
		$this->set_hosts();
		$this->create_or_save();
	}

	public function edit() {
		$this->set_poets();
		$this->set_partners();
		$this->set_hosts();
		$this->verify_id_param();
		$this->set_object();
		$this->create_or_save();
	}
	
	private function set_poets() {
		$this->load_model('Poet');
		$poets = $this->Poet->find(array('selects' => array('id', 'last_name', 'first_name')));
		$this->set('poets', $poets);
	}
	
	private function set_partners() {
		$this->load_model('Partner');
		$partners = $this->Partner->find(array('selects' => array('id', 'name')));
		$this->set('partners', $partners);
	}

	private function set_hosts() {
		$this->load_model('Host');
		$hosts = $this->Host->find(array('selects' => array('id', 'first_name', 'last_name')));
		$this->set('hosts', $hosts);
	}
	
	public function admin_column_date($object) {
		return empty($object->date) ? null : date('F jS, Y', strtotime($object->date));
	}
	
	public function partner_edit_link($object) {
		return empty($object->partner) ? null : HtmlHelper::admin_object_link($object->partner, array('action' => 'edit'));
	}


}
