<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {
 
   //생성자 호출
	function __construct() {
        parent::__construct();

        if(!(isset($_SESSION['isMaster']) && isset($_SESSION['confidentialKey']))){
            if(isset($_SESSION['confidentialKey'])) {
                if(!password_verify($_SESSION['confidentialKey'],'die%^&49@rfk84hapaP7820%3GHmaasw!@')) 
                    die("잘못된 접근입니다"); 
            }else{
                die("직접접근은 허용되지 않습니다");
            }
        }

        $this->load->model('manage/student_model');
        $this->load->model('manage/subject_model');
        $this->load->model('manage/teacher_model');
        $this->load->model('manage/parent_model');
    }

	public function index($unit){    

        $studentNum = $this->student_model->getStudentsNumber();
        $subjects = $this->subject_model->getSujbectCodesByStudentID();

        if($unit=="all")
            $students = $this->student_model->getStudents();
        else if($unit=="element")
            $students = $this->student_model->getStudentsBetweenGrade(1,6);
        else if($unit=="middle")
            $students = $this->student_model->getStudentsBetweenGrade(7,9);
        else if($unit=="high")
            $students = $this->student_model->getStudentsBetweenGrade(10,12);
        else
            $students = $this->student_model->getStudentsByGrade($unit);
        

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/student/master', array( 
            'students' => $students,
            'studentNum' => $studentNum,
            'subjects' => $subjects,
            'subjectText' => $this->subject_model->getSubjectText(),
            'gradeText' => $this->subject_model->getGradeText(),
            'unit' => 10000
        ));
        $this->load->view('manage/common/mobileFooter');
		
    }


    public function detail($id){

        $payments = $this->pay_model->getPaymentsByID($id);

        $this->load->view('common/mobileHeader');
        $this->load->view('student/detail', array(
            'payments' => $payments,
            'id' => $id
        ));
        $this->load->view('common/mobileFooter');
    }

}

?>