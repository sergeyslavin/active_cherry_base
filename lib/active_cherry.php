<?php

require_once 'config/config.php';

class ActiveCherryBase extends Config {

    private $connection_to_db;
    private $table_name;
    private $select_db;
    private $child_model_property = array();
    private $last_query = null;

    private function connect() {
        $this->connection_to_db = mysql_connect($this->host_name, $this->user_name, $this->user_password) or die("Connection error: ".mysql_error());
        $this->select_db = mysql_select_db($this->db_name, $this->connection_to_db) or die("Could not select ".$this->db_name);
    }

    protected function connect_to($table_name) {
        $this->table_name = $table_name;
        $this->connect();
    }

    protected function query($query_string) {
        if($this->table_name == null) {
            die("Table name should be specified!");
        }

        $this->last_query = mysql_query($query_string);
        if(!$this->last_query) {
            die("Failed query ".mysql_error());
        }

        return $this->last_query;
    }

    function get_assoc($last_query = null) {
        if(!$last_query) {
            $last_query =$this->last_query;
        }

        return mysql_fetch_assoc($last_query);
    }

    protected function set_child_model_property($model_proprty) {
        $this->child_model_property = $model_proprty;
    }

    public function find($instance, $element_id) {
        $query = $this->query("SELECT * FROM ".$this->table_name." WHERE id = '".$element_id."' LIMIT 1");
        $response_object = $this->get_assoc($query);

        if(!empty($this->child_model_property)) {
            foreach ($this->child_model_property as $name) {
                $instance->{$name} = $response_object[$name];
            }
        }
        return $instance;
    }

    public function all($instance) {
        $ret_query_array = array();
        $query = mysql_query("SELECT * FROM ".$this->table_name);

        while($response_list = $this->get_assoc($query)) {
            foreach ($response_list as $key => $value) {
                if(in_array($key, $this->child_model_property)) {
                    $instance->{$key} = $value;
                }
            }

            $ret_query_array[] = clone $instance;
        }

        return $ret_query_array;
    }

    public function find_by($instance, $args) {
      if(count($args) > 0) {
        $query = $this->query("SELECT * FROM `".$this->table_name."` WHERE `".$args["field"]."`='".$args["value"]."' LIMIT 1");

        $response_object = $this->get_assoc($query);

        if(!empty($this->child_model_property)) {
            foreach ($this->child_model_property as $name) {
                $instance->{$name} = $response_object[$name];
            }
        }
      }

      return $instance;
    }

    public function debug($obj) {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }
}
