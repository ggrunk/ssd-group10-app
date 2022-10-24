<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'url'));

    $this->TPL['loggedin'] = $this->userauth->validSessionExists();
    $this->userauth->loggedin('survey');
  }
  
  public function index()
  {
    $this->load->model('model_db');
    $this->TPL['survey_questions'] = $this->model_db->get_survey_questions();
    $this->TPL['survey_courses'] = $this->model_db->get_courses();
    $this->template->show('survey', $this->TPL);
  }

  public function submit()
  {
    $this->load->library('form_validation');
    
    
    $this->load->model('model_db');
    $this->TPL['survey_questions'] = $this->model_db->get_survey_questions();
    
    foreach ($this->TPL['survey_questions'] as $q) {
      $this->formValidationSetRules($q['id'], $q['total']);
    }
    $this->form_validation->set_rules('survey-course', 'Course', 'required');
    $this->form_validation->set_rules('survey-year', 'Year', 'required');
    $this->form_validation->set_rules('survey-term', 'Term', 'required');

    if ($this->form_validation->run() == FALSE) { // Form validation failed
      $this->template->show('survey', $this->TPL);
    } else { // Form validation passed
      $categories=$this->model_db->get_categories();
      $category_totals=[];
      $total=0;
      foreach ($categories as $i => $c) {                               // for each distinct category
        $ids = $this->model_db->get_qids_from_category($c['category']);
        foreach ($ids as $id) {                                             // for each question in that category
          $input = $this->input->post($id['id']);
          $category_totals[$i] += $input;                 // add the totals per category
          $total += $input;                               // add the total
        }
      }
      // var_dump($category_totals);
      $this->model_db->submit_survey($_SESSION['user_id'], $this->input->post('survey-course'), $this->input->post('survey-year'), $this->input->post('survey-term'), $category_totals, $total);
      $this->form_validation->clear_field_data();
      $this->TPL['message']='Form validation passed.';
      $this->template->show('survey_submit', $this->TPL);
    }
  }
  private function formValidationSetRules($id, $total)
  {
    $this->form_validation->set_rules($id, 'question '.$id, 'required', array(
          'required' => 'You must answer %s.'
        ));
  }
}