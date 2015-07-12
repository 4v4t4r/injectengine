<?php
App::uses('AppModel', 'Model');
/**
 * Inject Model
 *
 */
class Inject extends AppModel {

	public function getAllActiveInjects($team_id, $group_id) {
		return $this->find('all', array(
			'fields' => array(
				'Inject.*', 'CompletedInject.*', 'User.username', 'RequestedCheck.*',
			),
			'joins' => array(
				array(
					'table' => 'completed_injects',
					'alias' => 'CompletedInject',
					'type'  => 'LEFT',
					'conditions' => array(
						'CompletedInject.inject_id = Inject.id',
						'CompletedInject.team_id' => $team_id,
					),
				),
				array(
					'table' => 'users',
					'alias' => 'User',
					'type'  => 'LEFT',
					'conditions' => array(
						'CompletedInject.user_id = User.id',
					),
				),
				array(
					'table' => 'requested_checks',
					'alias' => 'RequestedCheck',
					'type'  => 'LEFT',
					'conditions' => array(
						'RequestedCheck.team_id' => $team_id,
						'RequestedCheck.inject_id = Inject.id',
						'RequestedCheck.status' => 0,
					),
				),
			),
			'conditions' => array(
				'Inject.active' => 1,
				'Inject.group_id' => $group_id,
			),
			'order' => 'Inject.order ASC',
		));
	}
}
