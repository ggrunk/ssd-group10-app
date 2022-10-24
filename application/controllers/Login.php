<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();

    $this->TPL['loggedin'] = $this->userauth->validSessionExists();
    $this->userauth->loggedin('login');
  }

  public function index()
  {
    
    $this->template->show('login', $this->TPL);
  }

  public function loginuser()
  {
    $this->TPL['msg_red'] =
      $this->userauth->login($this->input->post("username"),
                             $this->input->post("password"));

    $this->template->show('login', $this->TPL);
  }

  public function logout()
  {
    $this->userauth->logout();
  }
  
  
  public function create()
  {
    $this->TPL['msg'] = '';
    $this->template->show('login_create', $this->TPL);
  }
  
  public function createuser()
  {
    $this->load->library('form_validation');
    $this->formValidationSetRules();
    $this->load->model('model_db');

    if ($this->form_validation->run() == FALSE) {
      $this->TPL['msg'] = '';
      $this->template->show('login_create', $this->TPL);
    } else {
      $this->TPL['result']=$this->model_db->create_user( $this->input->post('username'),
                                                         $this->input->post('password'),'member');
      $this->TPL['msg'] = "Couldn't create account, please contact an administrator.";
      if($this->TPL['result'] > 0){
          $this->TPL['msg'] = 'Account successfully created';
      }
      
      $this->form_validation->clear_field_data();
      $this->template->show('login', $this->TPL);
    }
    
    
    // $this->TPL['msg'] =
    //   $this->userauth->login($this->input->post("username"),
    //                          $this->input->post("password"));

    // $this->template->show('login_create', $this->TPL);
  }
  
  private function formValidationSetRules()
  {
    $this->form_validation->set_rules('username', 'Username', 'required|max_length[255]|is_unique[users.username]', array(
          'required' => 'You must provide a %s.',
          'max_length' => '%s is too long (max 255 characters)',
          'is_unique' => 'A user with that username already exists!'
        ) );
    $this->form_validation->set_rules('password', 'Password', 'required|max_length[255]', array(
          'required' => 'You must provide a %s.',
          'max_length' => '%s is too long (max 255 characters)'
        ) );
  }

}