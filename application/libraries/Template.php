<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//StAuth10127: I Ben Caunter, 000816353 certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else.
class Template
{
    function show($view, $args = NULL)
    {
        $CI =& get_instance();

        $CI->load->view('header',$args);
        $CI->load->view('navigation',$args);
        $CI->load->view($view, $args);
        $CI->load->view('footer',$args);
    }
}