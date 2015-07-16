<?php
App::uses('ClassRegistry', 'Utility');
App::uses('Security', 'Utility');

class AppSchema extends CakeSchema {

	public $attachments = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'inject_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'data' => array(
			'type' => 'binary',
			'null' => false,
		),
		'active' => array(
			'type' => 'boolean',
			'default' => true,
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $completed_injects = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'user_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'team_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'inject_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'time' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $groups = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'name' => array(
			'type' => 'string',
			'null' => false,
		),
		'backend_access' => array(
			'type' => 'boolean',
			'default' => false,
			'null' => false,
		),
		'dashboard_access' => array(
			'type' => 'boolean',
			'default' => false,
			'null' => false,
		),
		'teamportal_access' => array(
			'type' => 'boolean',
			'default' => false,
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $help = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'inject_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'requested_user_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'requested_team_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'assigned_user_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'time_requested' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),
		'time_started' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),
		'time_finished' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),
		'status' => array(
			'type' => 'integer',
			'length' => 1,
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $injects = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'group_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		// TODO: Change dependency => dependency_id
		'dependency' => array(
			'type' => 'integer',
			'null' => false,
		),
		'time_start' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),
		'time_end' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),
		'active' => array(
			'type' => 'boolean',
			'default' => false,
			'null' => false,
		),
		'title' => array(
			'type' => 'string',
			'null' => false,
		),
		'description' => array(
			'type' => 'text',
			'null' => false,
		),
		'explanation' => array(
			'type' => 'text',
			'null' => false,
		),
		'type' => array(
			'type' => 'integer',
			'length' => 1,
			'null' => false,
		),
		'flag' => array(
			'type' => 'string',
		),
		'hints_enabled' => array(
			'type' => 'boolean',
			'default' => false,
			'null' => false,
		),
		'order' => array(
			'type' => 'integer',
			'default' => 0,
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $logs = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'type' => array(
			'type' => 'string',
			'null' => false,
		),
		'text' => array(
			'type' => 'string',
			'null' => false,
		),
		'time' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),
		'ip_address' => array(
			'type' => 'string',
			'length' => 15,
		),
		'user_id' => array(
			'type' => 'integer',
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $requested_checks = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'inject_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'user_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'team_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'time_requested' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),
		'time_finished' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),
		'status' => array(
			'type' => 'integer',
			'length' => 1,
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $teams = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'group_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'name' => array(
			'type' => 'string',
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $used_hints = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'user_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'team_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'inject_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'hint_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'time' => array(
			'type' => 'integer',
			'length' => 10,
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	public $users = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'key' => 'primary',
		),
		'team_id' => array(
			'type' => 'integer',
			'null' => false,
		),
		'username' => array(
			'type' => 'string',
			'null' => false,
		),
		'password' => array(
			'type' => 'string',
			'null' => false,
		),
		'expires' => array(
			'type' => 'integer',
			'length' => 10,
			'default' => 0,
			'null' => false,
		),
		'active' => array(
			'type' => 'boolean',
			'default' => false,
			'null' => false,
		),

		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	// ================================

	public function before($event = array()) {
		ConnectionManager::getDataSource('default')->cacheSources = false;

		return true;
	}

	public function after($event = array()) {
		if ( !isset($event['create']) ) return;

		switch ( $event['create'] ) {
			case 'groups':
				$this->_create('Group', array(
					'name'              => 'Management',
					'backend_access'    => 1,
					'dashboard_access'  => 1,
					'teamportal_access' => 0,
				));

				$this->_create('Group', array(
					'name'              => 'Blue Teams',
					'backend_access'    => 0,
					'dashboard_access'  => 9,
					'teamportal_access' => 1,
				));
			break;

			case 'teams':
				$this->_create('Team', array(
					'name' => 'White Team',
					'group_id' => 1,
				));
			break;

			case 'users':
				$this->_create('User', array(
					'team_id'  => 1,
					'username' => 'admin',
					'password' => 'admin',
					'expires'  => 0,
					'active'   => 1,
				));
			break;

			case 'logs':
				$this->_create('Log', array(
					'type'       => 'INSTALLATION',
					'text'       => 'InjectEngine was just installed.',
					'time'       => time(),
					'ip_address' => '127.0.0.1',
					'user_id'    => 1,
				));
			break;
		}
	}

	private function _create($tbl, $data) {
		$table = ClassRegistry::init($tbl);

		$table->create();
		$table->save(array($tbl => $data));
	}

}
