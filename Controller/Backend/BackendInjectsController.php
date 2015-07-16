<?php
App::uses('BackendAppController', 'Controller');

class BackendInjectsController extends BackendAppController {
	public $uses = array('Team', 'User', 'Hint', 'Inject', 'CompletedInject', 'RequestedCheck', 'UsedHint', 'Group', 'Help');

	public function index() {
		$this->Inject->bindModel(array(
			'belongsTo' => array('Group')
		));

		$injects = $this->Inject->find('all', array(
			'order' => 'Inject.order ASC',
		));

		foreach ( $injects AS &$inject ) {
			switch ( $inject['Inject']['type'] ) {
				case self::INJECT_TYPE_NOTHING:
					$inject['Inject']['type_name'] = 'Nothing';
				break;

				case self::INJECT_TYPE_FLAG:
					$inject['Inject']['type_name'] = 'Flag';
				break;

				case self::INJECT_TYPE_SUBMIT:
					$inject['Inject']['type_name'] = 'Submission';
				break;

				case self::INJECT_TYPE_MANUAL:
					$inject['Inject']['type_name'] = 'Manual';
				break;

				default:
					$inject['Inject']['type_name'] = 'Unknown';
				break;
			}
		}

		$this->set('injects', $injects);
	}

	public function create() {
		if ( $this->request->is('post') ) {
			if ( 
				!isset($this->request->data['title']) OR 
				!isset($this->request->data['description']) OR 
				!isset($this->request->data['group_id']) OR 
				!isset($this->request->data['dependency']) OR 
				!isset($this->request->data['explanation']) OR 
				!isset($this->request->data['time_start']) OR 
				!isset($this->request->data['time_end']) OR 
				!isset($this->request->data['active']) OR 
				!isset($this->request->data['type']) OR 
				!isset($this->request->data['flag']) OR 
				!isset($this->request->data['hints_enabled']) OR 
				!isset($this->request->data['order']) OR 
				!is_numeric($this->request->data['dependency']) OR 
				!is_numeric($this->request->data['group_id']) OR 
				!is_numeric($this->request->data['time_start']) OR 
				!is_numeric($this->request->data['time_end']) OR 
				!is_numeric($this->request->data['active']) OR 
				!is_numeric($this->request->data['type']) OR 
				!is_numeric($this->request->data['hints_enabled']) OR 
				!is_numeric($this->request->data['order']) OR 
				(count($this->request->data) != 12 AND count($this->request->data) != 13)
			) {
				$this->barf();
			}

			// Remove WYSIWYG field
			if ( isset($this->request->data['_wysihtml5_mode']) ) {
				unset($this->request->data['_wysihtml5_mode']);
			}

			// Save it!
			$this->Inject->create();
			$this->Inject->save($this->request->data);

			// Log it
			$this->logMessage('BACKEND_INJECT', 'New inject was just created - #'.$this->Inject->id);

			// Set a nice flash message

			// Redirect home
			$this->redirect('/backend/injects');
		}

		$this->set('groups', $this->Group->find('all'));
		$this->set('injects', $this->Inject->find('all'));
	}

	public function edit($injectid=false) {
		if ( $injectid === false || !is_numeric($injectid) ) $this->barf();

		$inject = $this->Inject->findById($injectid);

		if ( empty($inject) ) $this->barf();

		if ( $this->request->is('post') ) {
			if ( 
				!isset($this->request->data['title']) OR 
				!isset($this->request->data['description']) OR 
				!isset($this->request->data['explanation']) OR 
				!isset($this->request->data['group_id']) OR 
				!isset($this->request->data['dependency']) OR 
				!isset($this->request->data['time_start']) OR 
				!isset($this->request->data['time_end']) OR 
				!isset($this->request->data['active']) OR 
				!isset($this->request->data['type']) OR 
				!isset($this->request->data['flag']) OR 
				!isset($this->request->data['hints_enabled']) OR 
				!isset($this->request->data['order']) OR 
				!is_numeric($this->request->data['dependency']) OR 
				!is_numeric($this->request->data['group_id']) OR 
				!is_numeric($this->request->data['time_start']) OR 
				!is_numeric($this->request->data['time_end']) OR 
				!is_numeric($this->request->data['active']) OR 
				!is_numeric($this->request->data['type']) OR 
				!is_numeric($this->request->data['hints_enabled']) OR 
				!is_numeric($this->request->data['order']) OR 
				(count($this->request->data) != 12 AND count($this->request->data) != 13)
			) {
				$this->barf();
			}

			// Remove WYSIWYG field
			if ( isset($this->request->data['_wysihtml5_mode']) ) {
				unset($this->request->data['_wysihtml5_mode']);
			}

			// Save it!
			$this->Inject->id = $injectid;
			$this->Inject->save($this->request->data);

			// Log it
			$this->logMessage('BACKEND_INJECT', 'Inject #'.$injectid.' was just modified');

			// Set a nice flash message

			// Redirect home
			$this->redirect('/backend/injects');
		}

		$this->set('groups', $this->Group->find('all'));
		$this->set('injects', $this->Inject->find('all', array(
			'fields' => array('Inject.id', 'Inject.title'),
		)));
		$this->set('inject', $inject);
	}

	public function toggleStatus($injectid=false) {
		if ( $injectid === false || !is_numeric($injectid) ) $this->barf();

		$inject = $this->Inject->findById($injectid);

		if ( empty($inject) ) $this->barf();

		$this->Inject->id = $injectid;
		$this->Inject->saveField('active', ($inject['Inject']['active'] == 1 ? 0 : 1));

		// Log it
		$this->logMessage('BACKEND_INJECT', 'Inject #'.$injectid.' status was just toggled');

		$this->redirect('/backend/injects');
	}

	public function hints() {
		if ( $this->request->is('post') ) {
			if ( 
				!isset($this->request->data['op']) OR 
				!isset($this->request->data['inject_id']) OR 
				!isset($this->request->data['active']) OR 
				!isset($this->request->data['description']) OR 
				!isset($this->request->data['order']) OR 
				!isset($this->request->data['time_wait']) OR 
				!isset($this->request->data['time_available']) OR 
				!is_numeric($this->request->data['op']) OR 
				!is_numeric($this->request->data['inject_id']) OR 
				!is_numeric($this->request->data['active']) OR 
				!is_numeric($this->request->data['order']) OR 
				!is_numeric($this->request->data['time_wait']) OR 
				!is_numeric($this->request->data['time_available']) OR 
				(count($this->request->data) != 7 AND count($this->request->data) != 8)
			) {
				$this->barf();
			}

			switch ( $this->request->data['op'] ) {
				// Create a new one
				case 1:
					// Clean up data
					unset($this->request->data['op']);

					// Create it
					$this->Hint->create();
					$this->Hint->save($this->request->data);

					// Log it
					$this->logMessage('BACKEND_HINT', 'New hint was just created for Inject #'.$this->request->data['inject_id'].' Hint ID: '.$this->Hint->id);
				break;

				// Edit existing
				case 2:
					if ( !isset($this->request->data['id']) || !is_numeric($this->request->data['id']) ) $this->barf();

					$hint = $this->Hint->findByIdAndInjectId($this->request->data['id'], $this->request->data['inject_id']);

					if ( empty($hint) ) $this->barf();

					// Clean up the data
					unset($this->request->data['op']);
					unset($this->request->data['id']);

					// Save it
					$this->Hint->id = $hint['Hint']['id'];
					$this->Hint->save($this->request->data);

					// Log it
					$this->logMessage('BACKEND_HINT', 'Hint #'.$hint['Hint']['id'].' was just modified');
				break;

				default:
					$this->barf();
				break;

				// Give some nice message - TODO

				// We done.
			}
		}

		$this->Inject->bindModel(array(
			'hasMany' => array('Hint'),
		));

		$this->set('injects', $this->Inject->find('all', array(
			'conditions' => array(
				'hints_enabled' => 1,
			),
		)));
	}

	public function responses() {
		// TODO
	}

	public function getHintInfo($id=false) {
		if ( $id === false || !is_numeric($id) ) return $this->barf(true);

		$this->Hint->bindModel(array(
			'belongsTo' => array('Inject'),
		));

		$hint = $this->Hint->findById($id);

		if ( empty($hint) ) return $this->barf(true);

		return $this->ajaxResponse($hint);
	}
}
