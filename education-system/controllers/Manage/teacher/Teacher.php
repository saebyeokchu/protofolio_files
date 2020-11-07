<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller { 
  
 
    //생성자 호출
	function __construct() {
        parent::__construct();
 
        $this->load->model('manage/teacher_model');
        $this->load->model('action/log_model');
        $this->load->model('manage/subject_model');

    } 
 
	public function index() //메인페이지
	{ 
		$name = $this->uri->segment(5);
        $classification = $this->uri->segment(6);
        $this->load->view('manage/common/mobileHeader');
        if(isset($_SESSION['isTeacher'])) { //로그인 되어있는지 체크
            $this->load->view('manage/teacher/main',array(
                'name' => $name,
                'classification' => $classification
            ));  
        }else{
            redirect('Action/auth/logout');
        }
        $this->load->view('manage/common/mobileFooter');
		
    }

    public function opinion() {


        $this->form_validation->set_rules('message','메세지', 'required');

        if($this->form_validation->run()==FALSE){  

            $name = $this->uri->segment(3);
            $classification = $this->uri->segment(4);

            $this->load->view('manage/common/mobileHeader');
            $this->load->view('manage/teacher/opinion',array(
                'name' => $name, 
                'classification' => $classification
            )); 
            $this->load->view('manage/common/mobileFooter');

        }else{
            
            $message = $this->input->post('message');

            echo $this->log_model->insertOpinion(array(
                'message' => $message
            ));
            
        }

    }

    public function myWork() {

        $subjects = $this->subject_model->getSubjectsByTeacher();

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('errors/bannerNotice',array(
            'message' => "현재 베타 버전으로 운영되는 기능이며 추후 모든 데이터는 삭제됩니다"
        ));
        $this->load->view('manage/teacher/myWork',array(
            'subjects' => $subjects,
            'gradeText' => $this->subject_model->getGradeText(),
            'levelText' => $this->subject_model->getLevelText(),
            'subjectText' => $this->subject_model->getSubjectText()
        ));
        $this->load->view('manage/common/mobileFooter');

        
    }

    public function info() {

        $teacher = $this->teacher_model->get(array('id'=>$_SESSION['teacherID']));

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/teacher/info',array(
            'teacher' => $teacher
        ));
        $this->load->view('manage/teacher/common/bottomMenu');
        $this->load->view('manage/common/mobileFooter');

        
    }


    function delete() { //master만 접근 가능

        if(isset($_SESSION['isMater'])) {
            $id = $this->input->post('id');

            $result = $this->teacher_model->delete(array(
                'id' => $id 
            ));

            echo $result;
        }else{
            die('직접 접근은 불가능합니다.<a href="/Action/auth">로그인</a>');
        }
    }

    public function add() {

        //set rules
        $this->noticeHead();
        $this->form_validation->set_rules('wname','이름', 'required');

        if($this->form_validation->run()==FALSE){ 
            $this->load->view('worker/add');
        }else{
            $worker_name = $this->input->post('wname');
            $hash=password_hash($this->input->post('birthday'),PASSWORD_BCRYPT);

            $result = $this->worker_model->add( array(
                'name' => $worker_name,
                'type' => $this->input->post('wtype'),
                'pw' => $hash
            ));

            redirect('master/employee');
        }
        
        $this->load->view('common/footer');
    }

    /***************출퇴근 기록****************/
    function managerAuth() {

        $id = $this->input->post('id');
        $password = $this->input->post('password');

        $user = $this->worker_model->getById(array('id'=> $id));
        $result = [];

        //password_verify($password,$user->pw)
        if($id==$user->id && password_verify($password,$user->pw)){
            if($user->classification==0){
                $this->session->set_userdata('isMaster',true);
                $this->session->set_userdata('confidentialKey',password_hash('die%^&49@rfk84hapaP7820%3GHmaasw!@',PASSWORD_BCRYPT));

                $result['is'] = 'master';
                echo json_encode($result);
            }else{
                $this->session->set_userdata('isWorker',true);
                $this->session->set_userdata('workerID',$user->id);
                $this->session->set_userdata('cf',$user->classification);
                $this->session->set_userdata('workerName',$user->name);
                
                $result['is'] = 'worker';
                $result['classification'] = $user->classification;
                $result['name'] = $user->name;
                echo json_encode($result);
            } 
        }else echo 0;

    }

    function auth($array) {

        $user = $this->worker_model->getById(array('id'=> $array['this_id']));
        if($array['this_id']==$user->id && password_verify($array['this_password'],$user->pw)){
            $this->session->set_userdata('isWorker',true);
            $this->session->set_userdata('workerID',$user->id);
            $this->session->set_userdata('cf',$user->classification);
            $this->session->set_userdata('workerName',$user->name);
            redirect('manage_worker/schedule/'.$_SESSION['workerName'].'/'.$_SESSION['cf']);

        }else{ 
            redirect('manage_worker/login');
        }
    } 

    private function noticeHead() {
        $this->load->view('common/header',array(
			'selectedMenuIndex' => 2
		)); 
    }

}

?>