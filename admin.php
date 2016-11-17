<?php

define("BASE_PATH",str_replace('\\','/',dirname(__FILE__)) . '/');

$rt = @include_once(BASE_PATH.'/includes/config.inc.php');
if (is_array($rt->config)) $config = $rt->config;

// Be sure config.inc.php is there and that it contains some important values
if(empty($config)) {
	echo "
<style type=\"text/css\">
*{margin:0;padding:0}
body{margin:50px;background:#eee;}
.install{padding:10px;border:2px solid #f22;border-radius:10px;background:#ffe8b7;margin:0 auto;font:1.5em 'Open Sans',serif;font-weight:300;text-align:center;}
p{ margin:20px 0; }
a{font-size:2em;color:#f22;text-decoration:underline;margin-top: 30px;padding: 5px;}
</style>
<div class=\"install\">
<p>Configuration file cannot be found or an error in JSON format.</p>
</div>";
	exit;
}

$rt = include_once(BASE_PATH.'/includes/datahandler.inc.php');
$system = new DataHandler;

/* View */
$system->dataOut(["x_request_handle" => "handleData",
	                 "template" => "backend",
	                 "vars" => ["form" => ["services" => $system->getServices(),"discounts" => $system->getDiscounts()]]]);

?>