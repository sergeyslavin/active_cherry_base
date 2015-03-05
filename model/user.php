<?php

require_once './lib/active_core_model.php';

class User extends ActiveCoreModel {
    function __construct() {
        parent::connect_to(__CLASS__);

        $accessors = array("name", "age");

        parent::accessor($accessors);
    }
}
