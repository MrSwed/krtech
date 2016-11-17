<?php
/**
	* DBAPI Extension config file
	* Date: 01.10.13
	* Time: 13:32
	*/

global $config;
if (empty($config["db"]["database_type"])) $config["db"]["database_type"] = 'mysql';

if (!include_once 'includes/extenders/dbapi.'.$config["db"]["database_type"].'.class.inc.php') {
	return false;
} else {
	$this->db = new DBAPI();
	return true;
}
