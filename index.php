<?php

require_once 'model/user.php';

$user = new User();

$result = $user->find(2);

echo $result->name;