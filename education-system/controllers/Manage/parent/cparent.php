<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cparent extends CI_Controller {
 
	//생성자 호출
	function __construct() {    
        parent::__construct();
        $this->load->model('manage/parent_model');
    }
 
    public function index($id) 
	{      
        //if student chosen, set the value
        $selectedStudent = $this->uri->segment(6); 
        $isStudentSelected = isset($selectedStudent);
        if($isStudentSelected) $selectedStudent=urldecode($selectedStudent);

        $parent = $this->parent_model->getParent($id);
        $students = $this->parent_model->getMyStudent($id);
 
        $this->load->view('manage/common/mobileHeader');

        if($isStudentSelected)
            $this->load->view('manage/parent/main',array(
                'parent' => $parent,
                'students' => $students,
                'selectedStudent' => $selectedStudent,
                'isStudentSelected' => $isStudentSelected
            ));
        else   
            $this->load->view('manage/parent/main',array(
                'parent' => $parent,
                'students' => $students,
                'isStudentSelected' => $isStudentSelected
            ));
        
        $this->load->view('manage/common/mobileFooter');
		 
    }

}
 
?>  