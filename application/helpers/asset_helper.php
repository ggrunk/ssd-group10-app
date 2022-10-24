<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//StAuth10127: I Ben Caunter, 000816353 certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else.
if (!function_exists('assetUrl'))
{
    function assetUrl()
    {
        $CI =& get_instance();
        return  $CI->config->item('assetsPath');
    }
}