<?php

require_once './lib/active_core_model.php';

class User extends ActiveCoreModel {
    function __construct() {
        parent::connect_to("user");
        parent::accessor(array("name"));
    }
}