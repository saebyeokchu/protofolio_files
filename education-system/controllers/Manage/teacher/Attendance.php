<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller { 
  
 
    //생성자 호출
	function __construct() {
        parent::__construct();


        $this->load->model('manage/teacher_model');
        $this->load->model('action/log_model');
        $this->load->model('manage/subject_model');

    } 

    private function checkIfStudent() {
        if(!(isset($_SESSION['isTeacher']))) 
            die("직접접근은 허용되지 않습니다.<a href='/Action/auth'>로그인 하러가기</a>"); 
    }

	public function index() {

        $this->checkIfStudent();
        $urlYear = $this->uri->segment(3);
        $urlMonth = $this->uri->segment(4);
        
        $schedule = $this->teacher_model->getTeacherSchedule(array('id'=> $_SESSION['teacherID']));

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/teacher/schedule',array(
            'schedule' => $schedule,
            'urlYear' => $urlYear,
            'urlMonth' => $urlMonth
        ));
        $this->load->view('manage/common/mobileFooter');
        
		
    }

    public function selfCheckInList() {

        $lastFourDigit = $this->input->post('lastFourDigit');

        $teacherResult = $this->teacher_model->selfCheckIn($lastFourDigit);

        if(count($teacherResult)>0) echo json_encode($teacherResult);
        else echo -1;

    }

    public function in() {
        
        $this->checkIfStudent();
        $result = $this->teacher_model->in();

        if($result==1){ //성공하면
            //$this->session->sess_destroy();
            echo "<script>alert(\"출근처리 완료\");history.back();</script>";
        }else if($result==0){ //실패하면 나한테 메일보내기
            //$this->session->sess_destroy();
            echo "<script>alert(\"문제가 있습니다.해당 문제가 이메일로 전송되었습니다.\");history.back();</script>";
        }else if($result==20){
            echo "<script>alert(\"이미 출근처리가 완료되었습니다.\");history.back();</script>";
        }

	}

	public function out() {

        $this->checkIfStudent();
        $result = $this->teacher_model->out(); 
        
        if($result==1){ //성공하면
            //$this->session->sess_destroy();
            echo "<script>alert(\"퇴근 처리 완료!!\");history.back();</script>";
        }else if($result==0){//실패하면 나한테 메일보내기
            //$this->session->sess_destroy();
            echo "<script>alert(\"문제가 있습니다.해당 문제가 이메일로 전송되었습니다.\");history.back();</script>";
        }else if($result==20){
            echo "<script>alert(\"이미 퇴근처리 완료되었습니다.\");history.back();</script>";
        }else if($result==21){
            echo "<script>alert(\"출근처리 먼저해주세요.\");history.back();</script>";
        }
        
    }

    public function edit() { 

        $day = $this->input->post('day');
        $hour = $this->input->post('hour');
        $minute = $this->input->post('minute');
        $option = $this->input->post('option');
        $before = $this->input->post('before');

        $teacher = $this->teacher_model->getById(array('id' => $_SESSION['teacherID']));

        $content = $teacher->name." 선생님이 ".$day." 날짜의 시간을 ".$before."에서 ".$hour.":".$minute."으로 수정하였습니다";
        
        $this->log_model->insertLog(array(
            'content' => $content
        ));
        $result = $this->teacher_model->editSchedule(array(
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'option' => $option
        ));

        echo $result;
    }

}

?>