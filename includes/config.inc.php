<?

/* 
 * Config handler class
 * JSON used
 * 
 **/

class Config {
	var $error;
	var $file;
	public $config = array();


	function __construct($file = "") {
		$this->file = $file ? $file : "config.json";
		$this->getConfig();
	}

	function getConfig($reload = false) {
		if ($reload or !$this->config) {
			if (!$config = file_get_contents($this->file)) $this->error($this->file." not found");
			if (!$config = json_decode($config, true)) $this->error($this->file." has no config JSON");
			$this->config = $config;
		}

		return $this->config;
	}

	function error($error, $die = false) {
		$this->error = $error; // todo: logger if need
		if ($die) die($error);
	}
}

if (count(get_included_files()) > 1) {
	return new Config();
}

?>