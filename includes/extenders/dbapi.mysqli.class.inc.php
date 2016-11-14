<?php

class DBAPI {
	var $conn;
	var $config;
	var $isConnected;

	function __construct($host = '', $dbase = '', $uid = '', $pwd = '', $pre = NULL, $charset = '', $connection_method = 'SET CHARACTER SET') {
		$this->config['host'] = $host ? $host : $GLOBALS["config"]["db"]['database_server'];
		$this->config['dbase'] = $dbase ? $dbase : $GLOBALS["config"]["db"]['dbase'];
		$this->config['user'] = $uid ? $uid : $GLOBALS["config"]["db"]['database_user'];
		$this->config['pass'] = $pwd ? $pwd : $GLOBALS["config"]["db"]['database_password'];
		$this->config['charset'] = $charset ? $charset : $GLOBALS["config"]["db"]['database_connection_charset'];
		$this->config['connection_method'] = $this->_dbconnectionmethod = (isset($GLOBALS["config"]["db"]['database_connection_method']) ? $GLOBALS["config"]["db"]['database_connection_method'] : $connection_method);
		$this->config['table_prefix'] = ($pre !== NULL) ? $pre : $GLOBALS["config"]["db"]['table_prefix'];
	}

	function connect($host = '', $dbase = '', $uid = '', $pwd = '', $tmp = 0) {
		$uid = $uid ? $uid : $this->config['user'];
		$pwd = $pwd ? $pwd : $this->config['pass'];
		$host = $host ? $host : $this->config['host'];
		$dbase = $dbase ? $dbase : $this->config['dbase'];
		$dbase = trim($dbase, '`'); // remove the `` chars
		$charset = $this->config['charset'];
		$connection_method = $this->config['connection_method'];
		$safe_count = 0;
		do {
			$this->conn = new mysqli($host, $uid, $pwd, $dbase);
			if ($this->conn->connect_error) {
				$this->conn = null;
				sleep(1);
				$safe_count++;
			}
		} while (!$this->conn && $safe_count < 3);
		if (!$this->conn) {
			$this->messageQuit("Failed to create the database connection!");
			exit;
		} else {
			$this->conn->query("{$connection_method} {$charset}");
			$totaltime = $tend - $tstart;
			$this->conn->set_charset($this->config['charset']);
			$this->isConnected = true;
		}
	}

	function disconnect() {
		$this->conn->close();
		$this->conn = null;
		$this->isConnected = false;
	}

	function escape($s, $safecount = 0) {
		$safecount++;
		if (1000 < $safecount) exit("Too many loops '{$safecount}'");
		if (empty ($this->conn) || !is_object($this->conn)) {
			$this->connect();
		}
		if (is_array($s)) {
			if (count($s) === 0) {
				$s = '';
			} else {
				foreach ($s as $i => $v) {
					$s[$i] = $this->escape($v, $safecount);
				}
			}
		} else {
			$s = $this->conn->escape_string($s);
		}
		return $s;
	}

	function query($sql) {
		if (empty ($this->conn) || !is_object($this->conn)) {
			$this->connect();
		}
		if (!($result = $this->conn->query($sql))) {
			$this->messageQuit("Execution of a query to the database failed - ".$this->getLastError(), $sql);
		} else {
			$totaltime = $tend - $tstart;
			return $result;
		}
	}

	function delete($from, $where = '', $orderby = '', $limit = '') {
		if (!$from) {
			$this->messageQuit("Empty \$from parameters in DBAPI::delete().");
		} else {
			$from = $this->replaceFullTableName($from);
			$where = !empty($where) ? (strpos(ltrim($where), "WHERE") !== 0 ? "WHERE {$where}" : $where) : '';
			$orderby = !empty($orderby) ? (strpos(ltrim($orderby), "ORDER BY") !== 0 ? "ORDER BY {$orderby}" : $orderby) : '';
			$limit = !empty($limit) ? (strpos(ltrim($limit), "LIMIT") !== 0 ? "LIMIT {$limit}" : $limit) : '';
			return $this->query("DELETE FROM {$from} {$where} {$orderby} {$limit}");
		}
	}

	function select($fields = "*", $from = "", $where = "", $orderby = "", $limit = "") {
		global $modx;
		if (!$from) {
			$this->messageQuit("Empty \$from parameters in DBAPI::select().");
		} else {
			$from = $this->replaceFullTableName($from);
			$where = !empty($where) ? (strpos(ltrim($where), "WHERE") !== 0 ? "WHERE {$where}" : $where) : '';
			$orderby = !empty($orderby) ? (strpos(ltrim($orderby), "ORDER BY") !== 0 ? "ORDER BY {$orderby}" : $orderby) : '';
			$limit = !empty($limit) ? (strpos(ltrim($limit), "LIMIT") !== 0 ? "LIMIT {$limit}" : $limit) : '';
			return $this->query("SELECT {$fields} FROM {$from} {$where} {$orderby} {$limit}");
		}
	}

	function update($fields, $table, $where = "") {
		global $modx;
		if (!$table) {
			$this->messageQuit("Empty \$table parameter in DBAPI::update().");
		} else {
			$table = $this->replaceFullTableName($table);
			if (is_array($fields)) {
				foreach ($fields as $key => $value) {
					if (is_null($value) || strtolower($value) === 'null') {
						$flds = 'NULL';
					} else {
						$flds = "'".$value."'";
					}
					$fields[$key] = "`{$key}` = ".$flds;
				}
				$fields = implode(",", $fields);
			}
			$where = !empty($where) ? (strpos(ltrim($where), "WHERE") !== 0 ? "WHERE {$where}" : $where) : '';
			return $this->query("UPDATE {$table} SET {$fields} {$where}");
		}
	}

	function insert($fields, $intotable, $fromfields = "*", $fromtable = "", $where = "", $limit = "") {
		global $modx;
		if (!$intotable) {
			$this->messageQuit("Empty \$intotable parameters in DBAPI::insert().");
		} else {
			$intotable = $this->replaceFullTableName($intotable);
			if (!is_array($fields)) {
				$this->query("INSERT INTO {$intotable} {$fields}");
			} else {
				if (empty($fromtable)) {
					$fields = "(`".implode("`, `", array_keys($fields))."`) VALUES('".implode("', '", array_values($fields))."')";
					$rt = $this->query("INSERT INTO {$intotable} {$fields}");
				} else {
					$fromtable = $this->replaceFullTableName($fromtable);
					$fields = "(".implode(",", array_keys($fields)).")";
					$where = !empty($where) ? (strpos(ltrim($where), "WHERE") !== 0 ? "WHERE {$where}" : $where) : '';
					$limit = !empty($limit) ? (strpos(ltrim($limit), "LIMIT") !== 0 ? "LIMIT {$limit}" : $limit) : '';
					$rt = $this->query("INSERT INTO {$intotable} {$fields} SELECT {$fromfields} FROM {$fromtable} {$where} {$limit}");
				}
			}
			if (($lid = $this->getInsertId()) === false) $this->messageQuit("Couldn't get last insert key!");
			return $lid;
		}
	}

	function isResult($rs) {
		return is_object($rs);
	}

	function freeResult($rs) {
		$rs->free_result();
	}

	function numFields($rs) {
		return $rs->field_count;
	}

	function fieldName($rs, $col = 0) {
		$field = $rs->fetch_field_direct($col);
		return $field->name;
	}

	function selectDb($name) {
		$this->conn->select_db($name);
	}


	function getInsertId($conn = NULL) {
		if (!is_object($conn)) $conn =& $this->conn;
		return $conn->insert_id;
	}

	function getAffectedRows($conn = NULL) {
		if (!is_object($conn)) $conn =& $this->conn;
		return $conn->affected_rows;
	}

	function getLastError($conn = NULL) {
		if (!is_object($conn)) $conn =& $this->conn;
		return $conn->error;
	}

	function getRecordCount($ds) {
		return (is_object($ds)) ? $ds->num_rows : 0;
	}

	function getRow($ds, $mode = 'assoc') {
		if (is_object($ds)) {
			if ($mode == 'assoc') {
				return $ds->fetch_assoc();
			} elseif ($mode == 'num') {
				return $ds->fetch_row();
			} elseif ($mode == 'object') {
				return $ds->fetch_object();
			} elseif ($mode == 'both') {
				return $ds->fetch_array(MYSQLI_BOTH);
			} else {
				global $modx;
				$this->messageQuit("Unknown get type ($mode) specified for fetchRow - must be empty, 'assoc', 'num' or 'both'.");
			}
		}
	}

	function getColumn($name, $dsq) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		if ($dsq) {
			$col = array();
			while ($row = $this->getRow($dsq)) {
				$col[] = $row[$name];
			}
			return $col;
		}
	}

	function getColumnNames($dsq) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		if ($dsq) {
			$names = array();
			$limit = $this->numFields($dsq);
			for ($i = 0; $i < $limit; $i++) {
				$names[] = $this->fieldName($dsq, $i);
			}
			return $names;
		}
	}

	function getValue($dsq) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		if ($dsq) {
			$r = $this->getRow($dsq, "num");
			return $r[0];
		}
	}

	function getXML($dsq) {
		if (!is_object($dsq)) {
			$dsq = $this->query($dsq);
		}
		$xmldata = "<xml>\r\n<recordset>\r\n";
		while ($row = $this->getRow($dsq, "both")) {
			$xmldata .= "<item>\r\n";
			for ($j = 0; $line = each($row); $j++) {
				if ($j % 2) {
					$xmldata .= "<$line[0]>$line[1]</$line[0]>\r\n";
				}
			}
			$xmldata .= "</item>\r\n";
		}
		$xmldata .= "</recordset>\r\n</xml>";
		return $xmldata;
	}

	function getTableMetaData($table) {
		$metadata = false;
		if (!empty ($table)) {
			$sql = "SHOW FIELDS FROM $table";
			if ($ds = $this->query($sql)) {
				while ($row = $this->getRow($ds)) {
					$fieldName = $row['Field'];
					$metadata[$fieldName] = $row;
				}
			}
		}
		return $metadata;
	}

	function prepareDate($timestamp, $fieldType = 'DATETIME') {
		$date = '';
		if (!$timestamp === false && $timestamp > 0) {
			switch ($fieldType) {
				case 'DATE' :
					$date = date('Y-m-d', $timestamp);
					break;
				case 'TIME' :
					$date = date('H:i:s', $timestamp);
					break;
				case 'YEAR' :
					$date = date('Y', $timestamp);
					break;
				default :
					$date = date('Y-m-d H:i:s', $timestamp);
					break;
			}
		}
		return $date;
	}

	function makeArray($rs = '', $index = false) {
		if (!$rs) return false;
		$rsArray = array();
		$iterator = 0;
		while ($row = $this->getRow($rs)) {
			$returnIndex = $index !== false && isset($row[$index]) ? $row[$index] : $iterator;
			$rsArray[$returnIndex] = $row;
			$iterator++;
		}
		return $rsArray;
	}

	function getVersion() {
		return $this->conn->server_info;
	}

	function replaceFullTableName($str, $force = null) {
		$str = trim($str);
		$dbase = trim($this->config['dbase'], '`');
		$prefix = $this->config['table_prefix'];
		if (!empty($force)) {
			$result = "`{$dbase}`.`{$prefix}{$str}`";
		} elseif (strpos($str, '[+prefix+]') !== false) {
			$result = preg_replace('@\[\+prefix\+\]([0-9a-zA-Z_]+)@', "`{$dbase}`.`{$prefix}$1`", $str);
		} else {
			$result = $str;
		}
		return $result;
	}

	function optimize($table_name) {
		$rs = $this->query("OPTIMIZE TABLE {$table_name}");
		if ($rs) {
			$rs = $this->query("ALTER TABLE {$table_name}");
		}
		return $rs;
	}

	function truncate($table_name) {
		$rs = $this->query("TRUNCATE {$table_name}");
		return $rs;
	}

	function dataSeek($result, $row_number) {
		return mysqli_data_seek($result, $row_number);
	}

	function messageQuit($message) {
		die($message);
	}
}
?>