<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subject extends CI_Controller {


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
        $this->load->model('manage/subject_model');
        $this->load->model('manage/teacher_model'); 
        
    } 

    public function index()
	{   
        $subjects = $this->subject_model->gets();
        $teachers = $this->teacher_model->getTeachersName();

		$this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/master/subject', array(
            'subjects' => $subjects,
            'gradeText' => $this->subject_model->getGradeText(),
            'levelText' => $this->subject_model->getLevelText(),
            'subjectText' => $this->subject_model->getSubjectText(),
            'teachers' => $teachers
        ));
        $this->load->view('manage/common/mobileFooter');
    }

    function attenData() {

        $id = $this->input->post('id');
        $students = $this->subject_model->getStudentsBySubject($id);

        echo json_encode($students);

    }

    function add() {
		$this->form_validation->set_rules('hTeacherVal','학생 이름', 'required');
        $this->form_validation->set_rules('gradeVal','학생 학년', 'required');
        //$this->form_validation->set_rules('levelVal','보호자 이름', 'required');

        if($this->form_validation->run()){

            $h = $this->input->post('startHour');
            $m = $this->input->post('startMinute');

            if(!isset($m)) $m='0';

            $startTime=$h.":".$m.":00";

            $result = $this->subject_model->add(array(
                'hTeacher' => $this->input->post('hTeacherVal'), 
                'grade' => $this->input->post('gradeVal'),
                'subject' => $this->input->post('subjectVal'),
                //'level' => $this->input->post('levelVal'),
                'limitSize' => $this->input->post('limitSizeVal'),
                'extraText' => $this->input->post('extraTextVal'),
                'startTime' => $startTime
            ));

            if($result) redirect('Manage/master/subject');
            else echo "<script>alert('과목 추가 실패');</script>";
        }

    }

    function addStuIndex() {
        
        $subjectIDs = $this->input->post('subjectIDs');
        $studentID = $this->input->post('studentID');

        if($subjectIDs)
        foreach($subjectIDs as $subjectID) {
            $this->subject_model->addStuIndex($subjectID,$studentID);
        }

        if($result) echo 1;
        else echo 0; 

    }

    

}

?> 