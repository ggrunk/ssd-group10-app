<?php

use Illuminate\Support\Arr;

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();

    $this->TPL['loggedin'] = $this->userauth->validSessionExists();
    $this->userauth->loggedin('home');
  }

  public function index()
  {
    $this->template->show('home', $this->TPL);
  }
  
  public function getSubmissions(){
    $this->load->model('model_db');
    $result = $this->model_db->get_submissions();
    echo json_encode($result);
  }
  
  public function getSubmissionsColumns(){
    $this->load->model('model_db');
    $result = $this->model_db->get_submissions_columns();
    echo json_encode($result);
  }
}