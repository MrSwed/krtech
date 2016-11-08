<?

if (!$config = file_get_contents("config.json")) {
	die("config.json not found");
}

if (!$config = json_decode($config,true)) {
	die("config.json has no config JSON");
}



echo "hello world";

print "<pre>";
print_r($config);


?>