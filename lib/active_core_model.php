<?php

require_once 'active_cherry.php';

class ActiveCoreModel extends ActiveCherryBase {

    public $instance_property = array();
    private $table_name;

    protected function accessor($property = null) {
        if(is_array($property)) {
            if($this->isAssoc($property)) {
                foreach ($property as $key => $value) {
                    $this->$key = $value;
                    $this->instance_property[] = $key;
                }
            } else {
                foreach ($property as $name) {
                    $this->{$name} = null;
                    $this->instance_property[] = $name;
                }
            }
        } else if($property == "*" || !$property) {
            $this->query("SHOW COLUMNS FROM `".$this->table_name."`");
            while($field_item = $this->get_assoc()) {
                $this->instance_property[] = $field_item["Field"];
            }
        }

        parent::set_child_model_property($this->instance_property);
    }

    function __call($name, $args) {

      if(preg_match("/find_by/", $name)) {
        preg_match("/^find_by_(.+)$/", $name, $find_args);
        $this->find_by(array("field"=>$find_args[1], "value" => $args[0]));
      }
    }

    function find_by($args = array()) {
      return parent::find_by($this, $args);
    }

    protected function connect_to($table_name) {
        $prepare_class_name = strtolower($table_name);
        $last_char = substr($prepare_class_name, -1);

        if($last_char != "s") {
          $prepare_class_name .= "s";
        }

        $this->table_name = $prepare_class_name;

        parent::connect_to($prepare_class_name);
    }

    public function isAssoc($arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function find($element_id) {
        return parent::find($this, $element_id);
    }

    public function all() {
        return parent::all($this);
    }
}
