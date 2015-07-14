<?php
App::uses('AppController', 'Controller');

class InjectsController extends AppController {
	public $uses = array('Team', 'User', 'Hint', 'Inject', 'CompletedInject', 'RequestedCheck', 'UsedHint', 'Group', 'Help');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireAuthenticated();

		$this->set('at_injects', true);
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
			'time_requested' => time(),
			'status' => 1,
		));

		return $this->ajaxResponse('Help request received! A White Team member will be arriving shortly.');
	}

	public function requestCheck() {
		if ( !$this->request->is('post') ) $this->redirect('/');

		if ( !isset($this->request->data['id']) || !is_numeric($this->request->data['id']) ) return $this->barf(true);

		// Check if they have a previous check request for this inject
		$prevRequests = $this->RequestedCheck->find('all', array(
			'conditions' => array(
				'inject_id' => $this->request->data['id'],
				'team_id'   => $this->teaminfo['id'],
				'status'    => 0,
			),
		));

		// Status code of 200 so the JS knows what to alert
		if ( !empty($prevRequests) ) return $this->ajaxResponse('You already have a pending check request for this inject. A White Team member will be arriving shortly.');

		// Create new help request
		$this->RequestedCheck->create();
		$this->RequestedCheck->save(array(
			'inject_id' => $this->request->data['id'],
			'user_id' => $this->userinfo['id'],
			'team_id' => $this->teaminfo['id'],
			'time_requested' => time(),
			'status' => 0,
		));

		return $this->ajaxResponse('Inject check request received! A White Team member will be arriving shortly.');
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

			case 3:
				return $this->ajaxResponse('Please contact a White Team Member to check this inject.', 400);
			break;

			default:
				return $this->ajaxResponse('Unknown type for this inject!', 500);
			break;
		}
	}
}
