<?php

require_once 'actionModel/base_model.php';

class User extends BaseModel {
	function __construct() {
        parent::connect_to("user");
		parent::accessor(array("name"));
	}
}