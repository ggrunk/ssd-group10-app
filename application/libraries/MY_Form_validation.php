<?php
//StAuth10127: I Ben Caunter, 000816353 certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else.
class MY_Form_validation extends CI_Form_validation {
    public function __construct(){
        parent::__construct();
    }

    // clears the form_validation field data. used after form submissions so that the form is cleared after a create or update
    public function clear_field_data() {
        $_POST = array();
        $this->_field_data = array();
        return $this;
    }
}