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
	}

	function config($reload) {
		if ($reload or !$this->config) {
			if (!$config = file_get_contents("config.json")) $this->error("config.json not found");
			if (!$config = json_decode($config, true)) $this->error("config.json has no config JSON");
			$this->config = $config;
		}

		return $this->config;
	}

	function error($error, $die = false) {
		$this->error = $error; // todo: logger if need
		if ($die) die($error);
	}
}

?>