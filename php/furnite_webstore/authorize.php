<?php
$JSONdata= file_get_contents("php://input");
$data = json_decode($JSONdata);
$result = (object) [];
$result->login = 1;
$resultJSON = json_encode($result);
echo $resultJSON;
?>