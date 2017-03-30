<?php

define('USERNAME', 'crunchbase_crawler');
define('UPDATE_WEBSITE_SH', '/home/crunchbase_crawler/update-website.sh');


if (!(isset($_SERVER['HTTP_USER_AGENT']))) {
  die();
}
if (!file_exists(UPDATE_WEBSITE_SH)) {
  die();
}
/*
if ( !isset($_POST['payload']) ) {
  die();
}*/
if($_GET['hash'] !== 'FDAfdaegda'){
    die();
}
 
$payload = json_decode($_POST['payload']);
$dirname = dirname(__FILE__);
exec( 'sudo -u ' . USERNAME . ' /bin/sh ' . UPDATE_WEBSITE_SH . ' ' . $dirname , $output);
echo '<pre>';
foreach($output as $o){
    echo $o . '<br />';
}
var_dump($payload);

?>
