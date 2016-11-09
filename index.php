<?
$base_path = str_replace('\\','/',dirname(__FILE__)) . '/';

// get start time
$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $tstart = $mtime;
$mstart = memory_get_usage();

$rt = @include_once($base_path.'/includes/config.inc.php');
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
<p>Configuration file cannot be found.</p>
</div>";
	exit;
}



?>