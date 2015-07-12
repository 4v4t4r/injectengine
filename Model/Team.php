<?php
App::uses('AppModel', 'Model');
/**
 * Team Model
 *
 */
class Team extends AppModel {
	public $belongsTo = array('Group');
}
