<?php

date_default_timezone_set('Asia/Jakarta');

$formula = date('Y/m/d H:i:s') . '#FM#Freshwork#2020@fc9631fFreshwork';
$token = md5($formula);

echo $formula . '-</br>' . $token; 

?>