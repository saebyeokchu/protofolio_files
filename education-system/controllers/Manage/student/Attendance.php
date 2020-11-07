<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller { 
  
 
    //생성자 호출
	function __construct() {
        parent::__construct();

        /*if(!(isset($_SESSION['isStudent']))) 
            die("직접접근은 허용되지 않습니다.<a href='/Action/auth'>로그인 하러가기</a>");*/

        $this->load->model('manage/teacher_model');
        $this->load->model('action/log_model');
        $this->load->model('manage/subject_model');
        $this->load->model('manage/student_model');

    } 

	public function index() {

        $studentID=0;

        if(!isset($_SESSION['studentID'])) $studentID = $this->uri->segment(5);
        else $studentID = $_SESSION['studentID'];

        $studentName='';
        $tempName = $this->uri->segment(6);
        if($tempName != null) $studentName = $this->uri->segment(6);
          
    
        $schedules = $this->student_model->getStudentSchedules(array('id'=> $studentID));

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/student/attendance',array(
            'schedules' => $schedules,
            'studentName' => $studentName,
            'studentID' => $studentID
        ));
        $this->load->view('manage/common/mobileFooter');
        
		
    }

    public function selfCheckIn() {
        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/student/selfCheckIn');
        $this->load->view('manage/common/mobileFooter');
    }

    public function selfCheckInList() {

        $lastFourDigit = $this->input->post('lastFourDigit');

        $studentResult = $this->student_model->selfCheckIn($lastFourDigit);

        if(count($studentResult)>0) echo json_encode($studentResult);
        else echo -1;
        

    }

    public function in() {
 
        $result = $this->student_model->in();

        if($result==1){ //성공하면
            //$this->session->sess_destroy();
            echo "<script>alert(\"입실 완료\");history.back();</script>";
        }else if($result==0){ //실패하면 나한테 메일보내기
            //$this->session->sess_destroy();
            echo "<script>alert(\"문제가 있습니다.해당 문제가 이메일로 전송되었습니다.\");history.back();</script>";
        }else if($result==20){
            echo "<script>alert(\"이미 입실처리가 완료되었습니다.\");history.back();</script>";
        }

    }
    
    public function selfIn() {
 
        $result = $this->student_model->selfIn($this->input->post('id'));

        echo $result;

	}

	public function out() {

        $result = $this->student_model->out(); 
        
        if($result==1){ //성공하면
            //$this->session->sess_destroy();
            echo "<script>alert(\"퇴실 처리 완료!!\");history.back();</script>";
        }else if($result==0){//실패하면 나한테 메일보내기
            //$this->session->sess_destroy();
            echo "<script>alert(\"문제가 있습니다.해당 문제가 이메일로 전송되었습니다.\");history.back();</script>";
        }else if($result==20){
            echo "<script>alert(\"이미 퇴실처리가 완료되었습니다.\");history.back();</script>";
        }
        
    }


}

?>