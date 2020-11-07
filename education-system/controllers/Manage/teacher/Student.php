<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
 
   //생성자 호출
	function __construct() {
        parent::__construct();

        if(!isset($_SESSION['isTeacher'])) {
            die("직접접근은 허용되지 않습니다"); 
        }

        $this->load->model('manage/student_model');
        $this->load->model('manage/subject_model');
    }

	public function index()
	{
        
		
    }

    public function attendance(){
 
        $this->form_validation->set_rules('option','출석유형', 'required');

        if($this->form_validation->run()==FALSE){  

            $unit = $this->uri->segment(5);
            $selectedDay = $this->uri->segment(6);

            $attens=$this->student_model->getAttensByTeacher();
            $studentNum = $this->student_model->getStudentsNumberByTeacher();

            if($unit=="all")
                $students = $this->student_model->getStudentsByTeacher();
            else if($unit=="element")
                $students = $this->student_model->getMyStudentsBetweenGrade(1,6);
            else if($unit=="middle")
                $students = $this->student_model->getMyStudentsBetweenGrade(7,9);
            else if($unit=="high")
                $students = $this->student_model->getMyStudentsBetweenGrade(10,12);
            else
                $students = $this->student_model->getMyStudentsByGrade($unit);


            $this->load->view('manage/common/mobileHeader');
            $this->load->view('manage/teacher/studentAttendance',array(
                'attens' => $attens,
                'students' => $students,
                'studentNum' => $studentNum,
                'selectedDay' => $selectedDay
            ));
            $this->load->view('manage/common/mobileFooter'); 
        }else{
            //수정, 등록 한꺼번에 처리
        
            $editArray = $this->input->post('edit');
            $addArray = $this->input->post('add');
            $option = $this->input->post('option');
            $result=0; 

            //수정할 친구들은 아이디가 넘어옴
            if($editArray) {
                foreach ($editArray as $attendID){
                    $result = $this->student_model->editAttendance($attendID,$option);
                    //echo "editArray function work";
                }
            }

            if($addArray) {
                foreach ($addArray as $addData){
                    $dayAndID = explode('nsbp', $addData);
                    $result = $this->student_model->addAttendance($dayAndID[0],$dayAndID[1],$option);
                    //echo "addArray function work";
                }
            }
            
            echo $result;

        }
    }

    public function attendanceDetail() {

        $stuID = $this->uri->segment(4);
        $subjectID = $this->uri->segment(5);

        $tempDate = $this->student_model->getStudentPropertyByID('admissionDate',$stuID);
        $attens = $this->student_model->getAttensByStudentAndSubjectID($stuID,$subjectID);
        $subjectIndex = $this->subject_model->getSubjectIndex($stuID,$subjectID);

        $this->load->view('common/mobileHeader');
        $this->load->view('worker/myWork/detail',array(
            'stuID' => $stuID,
            'start' => $tempDate,
            'subjectID' => $subjectID,
            'attens' => $attens,
            'subjectIndex' => $subjectIndex
        ));
        $this->load->view('common/mobileFooter');
    }

    function cocos_student_list(){

        $students = $this->student_model->getStudents();

        $this->load->view('worker/myWork/cocos_student_list',array(
            'students' => $students
        ));
    }

    public function student_attend(){
        
    }   

}

?> 