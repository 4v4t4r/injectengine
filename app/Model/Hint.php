<?php
App::uses('AppModel', 'Model');
/**
 * Hint Model
 *
 */
class Hint extends AppModel {

	public function getHintsForInject($id) {
		return $this->find('all', array(
			'conditions' => array(
				'inject_id' => $id,
				'active' => 1,
			),
			'order' => array(
				'Hint.order ASC'
			),
		));
	}

	public function getNextHintForInject($id, $teamid) {
		$query = 'SELECT
			Hint.*
		FROM
			hints AS Hint
		LEFT JOIN
			used_hints AS UsedHints
		ON
			UsedHints.hint_id = Hint.id AND 
			UsedHints.team_id = ?
		WHERE
			Hint.inject_id = ? AND
			UsedHints.id IS NULL
		ORDER BY
			Hint.order ASC
		LIMIT 1';

		return $this->query($query, array($teamid, $id));
	}
}
