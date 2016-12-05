<?php
require_once '../google-api-php-client-2.1.0/vendor/autoload.php';

//identifiant du moteur de recher : 016014982774890444637:tirltd59_os
//API key: AIzaSyAQ1rgsSDdetI6uhC9egwf_OqdDprHwB-g
//		   AIzaSyBQhROXNGtRsvRaDvDQeax-Q5S33-U2yKQ	
$ch = curl_init("https://www.googleapis.com/customsearch/v1?key=AIzaSyAQ1rgsSDdetI6uhC9egwf_OqdDprHwB-g&cx=016014982774890444637:tirltd59_os&q=vin");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, $result);

curl_exec($ch);

echo $result;

?>