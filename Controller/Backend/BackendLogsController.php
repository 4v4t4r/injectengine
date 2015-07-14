<?php
App::uses('BackendAppController', 'Controller');

class BackendLogsController extends BackendAppController {
	public $uses = array('Log', 'User');
	public $components = array('Paginator');

	public $paginate = array(
		'limit' => 25,
		'order' => array(
			'Log.id' => 'DESC',
		),
	);

	public function index($type=false) {
		$this->Paginator->settings = $this->paginate;

		if ( $type !== false ) {
			$this->Paginator->settings['conditions'] = array(
				'Log.type' => $type,
			);

			$this->set('filtering', true);
			$this->set('filtering_type', $type);
		} else {
			$this->set('filtering', false);
		}

		$this->Log->bindModel(array(
			'belongsTo' => array('User'),
		));
		$this->set('logs', $this->Paginator->paginate('Log'));
	}
}
