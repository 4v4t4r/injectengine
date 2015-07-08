<?php
App::uses('AppModel', 'Model');
/**
 * CompletedInject Model
 *
 */
class CompletedInject extends AppModel {
	public $useTable = 'completed_injects';

	public function getTimelineInfo($id) {
		$this->bindModel(array(
			'belongsTo' => array('User', 'Inject'),
		));

		return $this->find('all', array(
			'conditions' => array(
				'CompletedInject.team_id' => $id,
			),
			'order' => 'CompletedInject.time DESC',
		));
	}
}
