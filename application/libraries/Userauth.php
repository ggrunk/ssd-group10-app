<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Userauth  { 
	  
    private $login_page = "";   
    private $logout_page = "";
    private $members_page = "";
    private $stats_page = "";
    private $admin_page = "";
     
    
    private $user_id;
    private $username;
    private $accesslevel;
    private $password;

    private $tablename = 'users';

    private $acl;

    /**
    * Turn off notices so we can have session_start run twice
    */
    function __construct() 
    {
      error_reporting(E_ALL & ~E_NOTICE);
      $this->logout_page = base_url() . "index.php?/Home";
      $this->members_page = base_url() . "index.php?/Survey";
      $this->stats_page = base_url() . "index.php?/Stats";
      $this->admin_page = base_url() . "index.php?/Admin";
      $this->login_page = base_url() . "index.php?/Login";
    }

    /**
    * @return string
    * @desc Login handling
    */
    public function login($username,$password) 
    {

      session_start();
        
      // User is already logged in if SESSION variables are good. 
      if ($this->validSessionExists() == true)
      {
        $this->redirect($_SESSION['basepage']);
      }

      // First time users don't get an error message.... 
      if ($_SERVER['REQUEST_METHOD'] == 'GET') return;
        
      // Check login form for well formedness.....if bad, send error message
      if ($this->formHasValidCharacters($username, $password) == false)
      {
         return "Username/password cannot be blank!";
      }
        
      // Verify if user/pass matches database user/pass
      if ($this->userIsInDatabase($username, $password))
      {
        // Check if account is frozen
        if($this->userIsFrozen($this->username) == true){
          return 'New accounts must be enabled by an administrator.';
        }else{
          // We're in!
          $this->writeSession();
          // Redirect authenticated users to the correct landing page
          $this->redirect($_SESSION['basepage']);
        }
      }
      else
      { 
        return 'Invalid username/password!';
      }
    }
	
    /**
    * @return void
    * @desc Validate if user is logged in
    */
    public function loggedin($page) 
    {

      session_start();     
      
      $CI =& get_instance();
      $acl = $CI->config->item('acl');
      // Access Control List checking
   
      // Users who are not logged in are considered public
      if ($this->validSessionExists() == false){
        if(!$acl[$page]['public']){ // If the page is not public, redirect them to the login page
          $this->redirect($this->login_page);
        }
      }

      if($acl[$page][$_SESSION['accesslevel']]){ // Verify the users accesslevel with the accesslevel for the requested page
        
      }else{ // Invalid accesslevel
        // Redirect to correct page based on accesslevel
        if($_SESSION['accesslevel']=='member'){
          $this->redirect($this->members_page);
        }
        if($_SESSION['accesslevel']=='editor'){
          $this->redirect($this->stats_page);
        }
      }
      return true;
    }
	
    /**
    * @return void
    * @desc The user will be logged out.
    */
    public function logout() 
    {
      session_start(); 
      $_SESSION = array();
      session_destroy();
      header("Location: ".$this->logout_page);
    }
    
    /**
    * @return bool
    * @desc Verify if user has got a session and if the user's IP corresonds to the IP in the session.
    */
    public function validSessionExists() 
    {
      session_start();
      if (!isset($_SESSION['username']))
      {
        return false;
      }
      else
      {
        return true;
      }
    }
    
    /**
    * @return void
    * @desc Verify if login form fields were filled out correctly
    */
    public function formHasValidCharacters($username, $password) 
    {
      // check form values for strange characters and length (3-12 characters).
      // if both values have values at this point, then basic requirements met
      if ( (empty($username) == false) && (empty($password) == false) )
      {
        $this->username = $username;
        $this->password = $password;
        return true;
      }
      else
      {
        return false;
      }
    }
	
    /**
    * @return bool
    * @desc Verify username and password with MySQL database.
    */
    public function userIsInDatabase() 
    {
      $CI =& get_instance();
      $user_query = $CI->db->query("SELECT user_id,username,password,accesslevel FROM users"); // Read usernames and passwords from database table
      $user_list = $user_query->result_array();

      foreach ($user_list as $user) {
        if($this->username == $user['username']){ // Verify username 
          if( password_verify($this->password, $user['password']) ){ // Verify password
            $this->user_id = $user['user_id'];
            $this->accesslevel = $user['accesslevel'];
            return true;
          }else{
            return false;
          }
        }
      }
      return false;
    }

    /**
    * @return bool
    * @desc Check if account is frozen with MySQL database.
    */
    public function userIsFrozen($username) 
    {
      $CI =& get_instance();
      $user_query = $CI->db->query("SELECT frozen FROM users WHERE username=?", array($username)); // Read usernames and passwords from database table
      $result = $user_query->result_array();
      if(count($result)>0){
        if($result[0]['frozen']=='Y'){
          return true;
        }else{
          return false;
        }
      }
      return false;
    }
    
    
    /**
    * @return void
    * @param string $page
    * @desc Redirect the browser to the value in $page.
    */
    public function redirect($page) 
    {
        header("Location: ".$page);
        exit();
    }
    
    /**
    * @return void
    * @desc Write username and other data into the session.
    */
    public function writeSession() 
    {
        $_SESSION['username'] = $this->username;
        $_SESSION['user_id'] = $this->user_id;
        $_SESSION['accesslevel'] = $this->accesslevel;
        switch ($_SESSION['accesslevel']) {
          case 'member':
            $_SESSION['basepage'] = $this->members_page;
            break;
          case 'editor':
            $_SESSION['basepage'] = $this->stats_page;
            break;
          case 'admin':
            $_SESSION['basepage'] = $this->admin_page;
            break;
          default:
            $_SESSION['basepage'] = base_url() . "index.php?/Home";
            break;
        }
        
    }
	
    /**
    * @return string
    * @desc Username getter
    */
    public function getUsername() 
    {
        return $_SESSION['username'];
    }
		 
}

