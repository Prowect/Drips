<?php

class Installer
{
	protected $installDirectory;
	protected $composerPhar;
	protected $logFile;

	public function __construct($installDirectory)
	{
		$this->installDirectory = $installDirectory;
		$this->composerPhar = __DIR__ . DIRECTORY_SEPARATOR . 'composer.phar';
		$this->logFile = __DIR__ . DIRECTORY_SEPARATOR . 'install.log';
	}

	public function getRequirements()
	{
		$result = array(
			'permissions' => true,
			'phpversion' => true,
			'shell_exec' => true
		);

		if(!is_writable($this->installDirectory)){
			if(!chmod($this->installDirectory, 0777)){
				$result['permissions'] = false;
			}
		}
		if(version_compare(PHP_VERSION, '5.6') < 0){
			$result['phpversion'] = false;
		}
		if(!is_callable('shell_exec')){
			$result['shell_exec'] = false;
		}
		return $result;
	}

	protected function getComposer()
	{
		if(getenv('COMPOSER_HOME') === false && getenv('HOME') === false){
 		   putenv('COMPOSER_HOME='.__DIR__ . DIRECTORY_SEPARATOR .'.composer');
		}

		// install composer
		if(!file_exists($this->composerPhar)){
			$this->log('Downloading composer.phar ...');
			$composerPhar = file_get_contents('https://getcomposer.org/composer.phar');
			if(file_put_contents($this->composerPhar, $composerPhar) !== false){
				$this->log('Successfully downloaded composer.phar');
				if(!chmod($this->composerPhar, 0777)){
					$this->log('Failed to make composer.phar executable!');
				}
			} else {
				$this->log('Failed to download composer.phar');
			}
		}
		
		return $this->composerPhar;
	}

	protected function log($msg)
	{
		return file_put_contents($this->logFile, date('[Y-m-d H:i] ') . $msg . PHP_EOL, FILE_APPEND) !== false;		
	}

	public function install()
	{
		$returnCodes = array();
		chdir($this->installDirectory);
		$composer = $this->getComposer();

		$cmds = array();

		$cmds["Installing dependencies"] = "$composer update";
		if(!file_exists('.env')){
			file_put_contents('.env', file_exists('.env.example') ? file_get_contents('.env.example') : '');
			$cmds["Generate key"] = 'php drips key:generate';
		}

		$migrations = glob(DRIPS_DIRECTORY . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . '*.php');
		if(!empty($migrations)){
			$cmds["Setting up database (migrate)"] = 'php drips migrate';
		}

		foreach($cmds as $desc => $cmd){
			$fp = popen($cmd . ' 2>&1', 'r');
			echo '<p class="info">&lt;========================[ ' . $desc . ' ]========================&gt;</p>';
			$this->log('========================[ ' . $desc . ' ]========================');
			$this->log('[Executing] ' . $cmd);
			while(!feof($fp)){
				$execute = fread($fp, 1024);
				echo $execute;
				$this->log(str_replace(PHP_EOL, '', $execute));
				flush();
			}
			$returnCode = pclose($fp);
			$returnCodes[] = $returnCode;
			if($returnCode != 0){
				$this->log('Execution failed => Error ' . $returnCode);
				echo '<p class="error">Execution failed! (Error ' . $returnCode . ')</p>';
			}
			flush();
		}

		$returnCodes = array_unique($returnCodes);
		if(count($returnCodes) == 1 && array_pop($returnCodes) == 0){
			$this->log('===> Installation successfully completed!');
			echo '<a href="." class="btn" style="float: right;">Continue</a>';
			echo '<p class="success">===&gt; Installation successfully completed!</p>';
		} else {
			$this->log('===> Installation failed!');
			echo '<a href="." class="btn" style="float: right;">Retry</a>';
			echo '<p class="error">===&gt; Installation failed!</p>';
		}
	}
}

