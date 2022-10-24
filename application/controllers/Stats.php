<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();

    $this->TPL['loggedin'] = $this->userauth->validSessionExists();
    $this->userauth->loggedin('stats');
  }

  public function index()
  {
    $this->load->model('model_db');
    $this->TPL['survey_courses'] = $this->model_db->get_courses_nonzero();
    $this->TPL['submissions'] = $this->model_db->get_submissions();
    $this->TPL['submissions_columns'] = $this->model_db->get_submissions_columns();
    $this->TPL['base_url'] = base_url();

    $this->TPL['data_submissions'] = $this->model_db->get_all_submissions();
    $this->TPL['data_submitted_courses'] = $this->model_db->get_submitted_courses();
    $this->TPL['data_submitted_users'] = $this->model_db->get_submitted_users();

    

    
    
    $this->template->show('stats', $this->TPL);
  }
}