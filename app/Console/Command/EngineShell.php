<?php
App::uses('AppShell', 'Console/Command');

class EngineShell extends AppShell {

	public function startup() {
		$this->stdout->styles('header', array('underline' => true));
	}

	public function install() {
		$this->out('Installing Inject Engine');

		// Run the schema create tool
		$this->dispatchShell('schema', 'create', '--yes', '--quiet');

		// Done!
		$this->out('Installation completed!');
		$this->hr();
		$this->out('<header>Administrator Credentials</header>');
		$this->out('Username: admin');
		$this->out('Password: admin');
	}

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addArgument('install', array(
			'help' => 'Installs the Inject Engine - Sets up the database'
		));

		return $parser;
	}
}
