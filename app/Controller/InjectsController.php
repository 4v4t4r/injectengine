<?php
App::uses('AppController', 'Controller');

class InjectsController extends AppController {
	public $uses = array('Team', 'User', 'Hint', 'Inject', 'CompletedInject', 'UsedHint', 'Group', 'Help');

	// ===============[ REGULAR ROUTES
	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireAuthenticated();

		if ( !isset($this->request->params['backend']) ) {
			$this->set('at_injects', true);
		}
	}

	public function index() {
		$this->set('injects', $this->Inject->getAllActiveInjects($this->teaminfo['id'], $this->groupinfo['id']));
	}

	public function hint($id=false) {
		if ( $id === false || !is_numeric($id) ) $this->barf();

		// Get the inject
		$inject = $this->Inject->findByIdAndActiveAndHintsEnabledAndGroupId($id, 1, 1, $this->groupinfo['id']);
		if ( empty($inject) ) $this->barf();

		// Get the hints
		$hints = $this->Hint->getHintsForInject($id);
		if ( empty($hints) ) $this->barf();

		// Get the used hints
		$usedhints = $this->UsedHint->findAllByTeamIdAndInjectId($this->teaminfo['id'], $id);

		// Get the previous inject, if applicable
		$prevInjectCompleted = -1;
		if ( $inject['Inject']['order'] > 0 ) {
			$prevInject = $this->Inject->findByActiveAndOrderAndGroupId(1, $inject['Inject']['order']-1, $this->groupinfo['id']);

			if ( !empty($prevInject) ) {
				$prevInjectComp = $this->CompletedInject->findByTeamIdAndInjectId($this->teaminfo['id'], $prevInject['Inject']['id']);
				$prevInjectCompleted = isset($prevInjectComp['CompletedInject']['time']) ? $prevInjectComp['CompletedInject']['time'] : -1;
			}
		}

		// Merge the used hints + hints
		$unlockedHint = function($id) use($usedhints) {
			foreach ( $usedhints AS $uh ) {
				if ( $uh['UsedHint']['hint_id'] == $id ) return true;
			}

			return false;
		};

		foreach ( $hints AS &$hint ) {
			$hint['Hint']['unlocked'] = $unlockedHint($hint['Hint']['id']);
		}

		$this->layout = 'ajax';

		$this->set('prevInjectCompleted', $prevInjectCompleted);
		$this->set('inject', $inject);
		$this->set('hints', $hints);
	}

	public function takeHint() {
		if ( !$this->request->is('post') ) $this->redirect('/');

		if ( !isset($this->request->data['id']) ) return $this->barf(true);

		// Check the inject
		$inject = $this->Inject->findByIdAndActiveAndHintsEnabledAndGroupId($this->request->data['id'], 1, 1, $this->groupinfo['id']);

		if ( empty($inject) ) return $this->barf(true);

		// Get the next hint
		$hint = $this->Hint->getNextHintForInject($this->request->data['id'], $this->teaminfo['id']);

		if ( empty($hint) ) return $this->ajaxResponse('There are seriously no more hints, stop requesting for them.', 400);
		$hint = current($hint);

		// Mark it as used
		$this->UsedHint->create();
		$this->UsedHint->save(array(
			'user_id' => $this->userinfo['id'],
			'team_id' => $this->teaminfo['id'],
			'inject_id' => $this->request->data['id'],
			'hint_id' => $hint['Hint']['id'],
			'time' => time(),
		));

		// Log it
		$this->logMessage('HINT', 'Hint #'.$hint['Hint']['id'].' for Inject #'.$this->request->data['id'].' was just revealed');

		return $this->ajaxResponse(array(
			'description' => $hint['Hint']['description'],
		));
	}

	public function requestHelp() {
		if ( !$this->request->is('post') ) $this->redirect('/');

		if ( !isset($this->request->data['id']) || !is_numeric($this->request->data['id']) ) return $this->barf(true);

		// Check if they have a previous help request
		$prevRequests = $this->Help->find('all', array(
			'conditions' => array(
				'requested_team_id' => $this->teaminfo['id'],
				'status' => array(1, 2),
			),
		));

		// Status code of 200 so the JS knows what to alert
		if ( !empty($prevRequests) ) return $this->ajaxResponse('You already have a pending help request. A White Team member will be arriving shortly.');

		// Create new help request
		$this->Help->create();
		$this->Help->save(array(
			'inject_id' => $this->request->data['id'],
			'requested_user_id' => $this->userinfo['id'],
			'requested_team_id' => $this->teaminfo['id'],
			'requested_time' => time(),
			'status' => 1,
		));

		return $this->ajaxResponse('Help request received! A White Team member will be arriving shortly.');
	}

	public function submit() {
		if ( !$this->request->is('post') ) $this->redirect('/');

		if ( !isset($this->request->data['id']) ) return $this->barf(true);

		$inject = $this->Inject->findByIdAndActiveAndGroupId($this->request->data['id'], 1, $this->groupinfo['id']);

		if ( empty($inject) ) return $this->barf(true);

		switch ( $inject['Inject']['type'] ) {
			case 1:
				// Flag based inject
				if ( !isset($this->request->data['value']) ) return $this->barf(true);

				if ( $this->request->data['value'] !== $inject['Inject']['flag'] ) {
					// Log it
					$this->logMessage('INJECT', 'Inject #'.$this->request->data['id'].' flag was entered incorrectly - user put "'.htmlentities($this->request->data['value']).'"');

					return $this->ajaxResponse('Incorrect flag!', 400);
				}

				// They solved it...
				$this->CompletedInject->create();
				$this->CompletedInject->save(array(
					'inject_id' => $this->request->data['id'],
					'user_id'   => $this->userinfo['id'],
					'team_id'   => $this->teaminfo['id'],
					'time'      => time(),
				));

				// Log it
				$this->logMessage('INJECT', 'Inject #'.$this->request->data['id'].' was just solved');

				return $this->ajaxResponse('Correct Flag!');
			break;

			case 2:
				return $this->ajaxResponse('Soon...');
			break;

			default:
				return $this->ajaxResponse('Unknown type for this inject!', 500);
			break;
		}
	}

	// ===============[ BACKEND ROUTES
	public function backend_index() {
		$this->Inject->bindModel(array(
			'belongsTo' => array('Group')
		));

		$injects = $this->Inject->find('all');

		foreach ( $injects AS &$inject ) {
			switch ( $inject['Inject']['type'] ) {
				case 1:
					$inject['Inject']['type_name'] = 'Flag';
				break;

				case 2:
					$inject['Inject']['type_name'] = 'Submission';
				break;

				default:
					$inject['Inject']['type_name'] = 'Unknown';
				break;
			}
		}

		$this->set('injects', $injects);
	}

	public function backend_create() {
		if ( $this->request->is('post') ) {
			if ( 
				!isset($this->request->data['title']) OR 
				!isset($this->request->data['description']) OR 
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
				(count($this->request->data) != 11 AND count($this->request->data) != 12)
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

	public function backend_edit($injectid=false) {
		if ( $injectid === false || !is_numeric($injectid) ) $this->barf();

		$inject = $this->Inject->findById($injectid);

		if ( empty($inject) ) $this->barf();

		if ( $this->request->is('post') ) {
			if ( 
				!isset($this->request->data['title']) OR 
				!isset($this->request->data['description']) OR 
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
				(count($this->request->data) != 11 AND count($this->request->data) != 12)
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

	public function backend_toggleStatus($injectid=false) {
		if ( $injectid === false || !is_numeric($injectid) ) $this->barf();

		$inject = $this->Inject->findById($injectid);

		if ( empty($inject) ) $this->barf();

		$this->Inject->id = $injectid;
		$this->Inject->saveField('active', ($inject['Inject']['active'] == 1 ? 0 : 1));

		// Log it
		$this->logMessage('BACKEND_INJECT', 'Inject #'.$injectid.' status was just toggled');

		$this->redirect('/backend/injects');
	}

	public function backend_hints() {
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

		$this->set('injects', $this->Inject->find('all'));
	}

	public function backend_responses() {
		// TODO
	}

	public function backend_getHintInfo($id=false) {
		if ( $id === false || !is_numeric($id) ) return $this->barf(true);

		$this->Hint->bindModel(array(
			'belongsTo' => array('Inject'),
		));

		$hint = $this->Hint->findById($id);

		if ( empty($hint) ) return $this->barf(true);

		return $this->ajaxResponse($hint);
	}
}
