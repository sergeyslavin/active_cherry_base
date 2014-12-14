<?php

require_once 'action_data_base.php';

class BaseModel extends ActionDataBase {

    public $instance_property = array();
    private $table_name;

	protected function accessor($property) {
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
        } else if($property == "*") {
            $this->query("SHOW COLUMNS FROM `".$this->table_name."`");            
            while($field_item = $this->get_assoc()) {
                $this->instance_property[] = $field_item["Field"];
            }
        }

        parent::set_child_model_property($this->instance_property);		
	}

    protected function connect_to($table_name) {
        $this->table_name = $table_name;
        parent::connect_to($table_name);
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