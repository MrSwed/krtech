<?

/*
 * DataHandler class
 * */

class DataHandler {
	var $db; // db object
	var $config = null;
	var $result;
	var $sql;
	var $debug;
	public $extensions = array();

	/**
		* datahandler constructor
		*
		* @return DataHandler
		*/
	function __construct() {
		global $config;
		if (substr(PHP_OS, 0, 3) === 'WIN' && $config["db"]["database_server"] === 'localhost') $config["db"]["database_server"] = '127.0.0.1';
		$this->loadExtension('DBAPI') or die('Could not load DBAPI class.'); // load DBAPI class
		$this->dbConfig = &$this->db->config; // alias for backward compatibility
		
	}


	function loadExtension($extname, $reload = true) {
		$out = false;
		$flag = ($reload || !in_array($extname, $this->extensions));
		if (!$out && $flag) {
			$extname = trim(str_replace(array(
				'..',
				'/',
				'\\'
			), '', strtolower($extname)));
			$filename = "includes/extenders/{$extname}.extenders.inc.php";
			$out = is_file($filename) ? include $filename : false;
		}
		return $out;
	}

	function getServices() {
		$select = $this->db->select("*", $this->dbConfig["table_prefix"]."services");
		$result = [];
		while ($row = $this->db->getRow($select)) $result[$row["id"]] = $row["name"];
		$this->db->freeResult($select);
		return $result;
	}

	function getDiscount($data = []) {
		if (!is_numeric($data["birthday"])) {
			$parsed = date_parse_from_format("Y-m-d", $data["birthday"]); // standart chrome send date
			$data["birthday"] = mktime($parsed['hour'], $parsed['min'], $parsed['sec'], $parsed['month'], $parsed['day'], date('Y'));
		}
		$where = '';
		if (is_array($data)) {
			if ((array)$data["services"]) {
				$services_where = [];
				foreach ($data["services"] as $service) $services_where[] = "`services` regexp '[^\d]?".$service."[^\d]?'";
			}
			$where = // "`services` regexp '[^\d]?(".implode("|", (array)$data["services"]).")[^\d]?'
				(!empty($services_where) ? implode(" and ", $services_where) : "services is null or services ='' ").
				"  and (`birthday_before` is null OR (".$data["birthday"]."-UNIX_TIMESTAMP(now()) <= `birthday_before`) )
			    and (`birthday_after` is null OR (UNIX_TIMESTAMP(now()) - ".$data["birthday"]." <= `birthday_after`) )
			    and (`phone` is null or `phone` = '' ".(!empty($data["phone"]) ? " OR `phone` = 1 OR '".$data["phone"]."' regexp `phone`" : "")." ) 
			    and (`gender` is null ".(!empty($data["gender"]) ? "OR gender = '".$data["gender"]."'" : "").")
			    and (`date_start` = 0 or `date_start` <= UNIX_TIMESTAMP(now()))
			    and (`date_end` = 0 or `date_end` > UNIX_TIMESTAMP(now())) ";
		}
		$select = $this->db->select("concat(MAX(percent),'%') as discount", $this->dbConfig["table_prefix"]."discounts t", $where);
		$result = $this->db->getRow($select);
		$this->db->freeResult($select);
		return $result;
	}

	function error($error, $die = false) {
		$this->error = $error; // todo: logger if need
		if ($die) die($error);
	}

	function dataOut($parameters = []) {
		if (is_string($parameters)) $parameters = ["view" => $parameters]; // default - template name
		$parameters = array_merge(["template" => false,
			                          "x_request_handle" => true,
			                          "data" => $_REQUEST], $parameters);
		if (!empty($parameters["data"]) && !empty($parameters["x_request_handle"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
			header('Content-Type: application/json');
			switch ($parameters["x_request_handle"]) {
				case "getDiscounts" :
					$data = $this->getDiscount($parameters["data"]);
					break;
				default:
					$data = &$parameters["data"];
			}
			echo json_encode($data);
			return true;
		} else if ($parameters["template"]) {
			$templateFile = BASE_PATH.'/view/'.$parameters["template"].'.php'; // todo: check for correct parameter value 
			if (file_exists($templateFile)) {
				if (is_array($parameters["vars"])) foreach($parameters["vars"] as $k => $v) ${$k} = $v; 
				include_once($templateFile);
				return true;
			} else {
				$this->error("$templateFile is missing",true);
			}
		}
		return false;
	}
}

?>