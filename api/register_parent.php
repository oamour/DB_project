<?php

$value = json_decode(file_get_contents('php://input'));
$file = 'test_reg.txt';
var_dump($value);
file_put_contents($file, "The received name is {$value->name} ", FILE_APPEND | LOCK_EX);
file_put_contents($file, var_dump($value), FILE_APPEND | LOCK_EX);

return json_encode($value);
?>