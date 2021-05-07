<?php

$hostname = $_POST['hostname'];
$user = $_POST['user'];
$adapter = $_POST['adapter'];
$ip = $_POST['ip'];
$mac = $_POST['mac'];

$filename = "./reports/".str_replace(":", "", $mac);

echo $hostname.PHP_EOL;
echo $user.PHP_EOL;
echo $adapter.PHP_EOL;
echo $ip.PHP_EOL;
echo $mac.PHP_EOL;

echo $filename;

$test = 1;

$myfile = fopen($filename, "w") or die("Unable to open file!");

fwrite($myfile, $hostname.PHP_EOL);
fwrite($myfile, $user.PHP_EOL);
fwrite($myfile, $adapter.PHP_EOL);
fwrite($myfile, $ip.PHP_EOL);
fwrite($myfile, $mac.PHP_EOL);

fclose($myfile);

?>

