<?php

require_once 'user.php';

$user = new User();

$result = $user->find(2);

echo $result->name;