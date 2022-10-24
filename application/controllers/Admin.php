<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();
    
    $this->TPL['loggedin'] = $this->userauth->validSessionExists();
    $this->userauth->loggedin('admin');
  }

  public function delete($id)
  {
    $this->load->model('model_db');
    $this->TPL['result'] = $this->model_db->delete($id);
    $this->TPL['users_table'] = $this->model_db->get_users();
    $this->template->show('admin', $this->TPL);
  }

  public function freeze($id)
  {
    $this->load->model('model_db');
    $this->TPL['result'] = $this->model_db->freeze($id);
    $this->TPL['users_table'] = $this->model_db->get_users();
    $this->template->show('admin', $this->TPL);
  }

  public function create()
  {
    $this->load->library('form_validation');
    $this->formValidationSetRules();
    $this->load->model('model_db');

    if ($this->form_validation->run() == FALSE) {
      $this->TPL['users_table'] = $this->model_db->get_users();
      $this->template->show('admin', $this->TPL);
    } else {
      $this->TPL['result']=$this->model_db->create_user( $this->input->post('username'),
                                                         $this->input->post('password'),
                                                         $this->input->post('accesslevel'));
      $this->TPL['users_table'] = $this->model_db->get_users();
      // $this->form_validation->clear_field_data();
      $this->template->show('admin', $this->TPL);
    }
  }
  

  public function index()
  {
    $this->load->library('form_validation');
    $this->load->model('model_db');
    $this->TPL['users_table'] = $this->model_db->get_users();
    $this->TPL['msg'] = '';

    $this->template->show('admin', $this->TPL);
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
    $this->form_validation->set_rules('accesslevel', 'Access level', 'callback_checkAccessLevel' );
  }

  function checkAccessLevel($accessLevel){
    if ($accessLevel == 'member' || $accessLevel == 'editor' || $accessLevel == 'admin') {
      return true;
    } else {
      $this->form_validation->set_message('checkAccessLevel', 'Access level must be either member, editor or admin.');
      return false;
    }
  }
}