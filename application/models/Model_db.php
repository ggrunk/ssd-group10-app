<?php
class Model_db extends CI_Model {
    private $table='users';

    public function get_users(){
        $query = $this->db->query("SELECT user_id,username,accesslevel,frozen FROM users");
        return $query->result_array();
    }
    
    public function get_submissions(){ // , introduction, learning, assessment, instructional, learning_activites, technology, support, usability
        $survey_total = $this->db->query("SELECT SUM(total) AS max FROM survey_questions")->result()[0]->max;
        $query = $this->db->query("SELECT 
                                        users.username AS \"Submitted by\", 
                                        courses.course_code AS \"Course Code\", 
                                        courses.course_name AS \"Course\", 
                                        year AS \"Year\", 
                                        term AS \"Term\", 
                                        total AS \"Total Score (out of " . strval($survey_total) . ")\",
                                        introduction, learning, assessment, instructional, learning_activites, technology, support, usability
                                    FROM submissions
                                    JOIN users on submissions.user_ID = users.user_id
                                    JOIN courses on submissions.course_ID = courses.id");
        return $query->result_array();
    }
	
	public function get_all_submissions(){
        $query = $this->db->query("SELECT *
									FROM submissions
									ORDER BY year, term DESC");
        return $query->result_array();
    }
    
    public function get_submissions_columns(){
        $query = $this->db->query("SELECT COLUMN_NAME 
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = 'submissions'");
        return $query->result_array();
    }
    
    public function get_courses(){
        $query = $this->db->query("SELECT id, course_code, course_name FROM courses ORDER BY course_code ASC");
        //$query = $this->db->query("SELECT DISTINCT(course_code), id, course_name FROM courses GROUP BY course_code ORDER BY course_code ASC");
        return $query->result_array();
    }
    
    public function get_courses_nonzero(){
        //$query = $this->db->query("SELECT id, course_code, course_name FROM courses ORDER BY course_code ASC");
        $query = $this->db->query("SELECT course_code, course_name FROM courses JOIN submissions ON courses.id=submissions.course_ID GROUP BY course_code, course_name  ORDER BY course_code ASC");
        return $query->result_array();
    }
	
	public function get_submitted_courses(){
        $query = $this->db->query("SELECT courses.id, courses.program_name, courses.course_code
                                FROM submissions
                                JOIN courses on courses.id=submissions.course_ID");
        return $query->result_array();
    }
	
	public function get_submitted_users(){
        $query = $this->db->query("SELECT submissions.user_ID, users.username
                                FROM submissions
                                JOIN users on users.user_id=submissions.user_ID");
        return $query->result_array();
    }

    public function get_survey_questions(){
        $query = $this->db->query("SELECT * FROM survey_questions ORDER BY id ASC");
        return $query->result_array();
    }

    public function get_category_maximums(){
        $query = $this->db->query("SELECT category, SUM(total) as max FROM survey_questions GROUP BY category ORDER BY id ASC");
        return $query->result_array();
    }

    public function get_categories(){
        $query = $this->db->query("SELECT category, category_display FROM survey_questions GROUP BY category ORDER BY id ASC");
        return $query->result_array();
    }

    public function get_qids_from_category($category){
        $query = $this->db->query("SELECT id FROM survey_questions WHERE category = ? ORDER BY id ASC", $category);
        return $query->result_array();
    }

    public function submit_survey($userid, $courseid, $year, $term, $category_totals, $total){
        $query = $this->db->query("INSERT INTO submissions(user_ID, course_ID, year, term, introduction,
        learning, assessment, instructional, learning_activites, technology, support,
        usability, total) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)", array(
            $userid, 
            $courseid, 
            $year,
            $term, 
            $category_totals[0], 
            $category_totals[1], 
            $category_totals[2], 
            $category_totals[3], 
            $category_totals[4], 
            $category_totals[5], 
            $category_totals[6], 
            $category_totals[7],
            $total
        ) );
    }

    public function create_user($username, $password, $accesslevel){
        $this->username  = $username;
        $this->password  = password_hash($password, PASSWORD_DEFAULT);
        $this->accesslevel  = $accesslevel;
        
        $query = $this->db->insert('users', $this);
        return $this->db->affected_rows();
    }

    public function freeze($id){
        $query = $this->db->query("SELECT frozen FROM users WHERE user_id=? AND (accesslevel='member' OR accesslevel='editor')", array($id));
        $result = $query->result_array();
        if(count($result)>0){
            if($result[0]['frozen']=='N'){
                $query = $this->db->update('users', array('frozen' => 'Y'), array('user_id' => $id));
                return $this->db->affected_rows();
            }else{
                $query = $this->db->update('users', array('frozen' => 'N'), array('user_id' => $id));
                return $this->db->affected_rows();
            }
        }else{
            return 0;
        }
    }

    public function delete($id){
        $this->db->select('accesslevel');
        $this->db->from('users');
        $this->db->where(array('user_id' => $id));
        if ($this->db->get()->result()[0]->accesslevel !== 'admin'){ // check accesslevel. don't delete if user is an admin
            $this->db->delete('users', array('user_id' => $id));
            return $this->db->affected_rows();
        }else{
            return 0;
        }
    }

}
