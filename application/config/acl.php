<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['acl'] = array('home' => array(
                                    'public' => true, 
                                    'member' => true, 
                                    'editor' => true, 
                                    'admin' => true),
                        'survey' => array(
                                    'public' => false, 
                                    'member' => true, 
                                    'editor' => false, 
                                    'admin' => true),
                        'stats' => array(
                                    'public' => false, 
                                    'member' => false, 
                                    'editor' => true, 
                                    'admin' => true),
                        'admin' => array(
                                    'public' => false, 
                                    'member' => false, 
                                    'editor' => false, 
                                    'admin' => true),
                        'login' => array(
                                    'public' => true, 
                                    'member' => true, 
                                    'editor' => true, 
                                    'admin' => true)
                      );
