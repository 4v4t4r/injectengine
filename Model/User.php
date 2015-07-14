<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {
	public $belongsTo = array('Team');

	public function beforeSave($options = array()) {
		if ( !empty($this->data['User']['password']) ) {
			$this->data['User']['password'] = Security::hash($this->data['User']['password'], 'blowfish');
		}
	}

	public function getTeamMembership($id) {
		return $this->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'team_id' => $id,
			),
			'fields' => array(
				'User.id', 'User.username',
			),
		));
	}
}
