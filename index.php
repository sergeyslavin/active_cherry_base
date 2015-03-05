<?php

require_once 'model/user.php';

$user = new User();

echo "<br />";
$res = $user->find_by_name("Vasya");

$user->debug($res);
