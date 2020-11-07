<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {


    //생성자 호출
	function __construct() {
        parent::__construct();
        if(!(isset($_SESSION['isMaster']) && isset($_SESSION['confidentialKey']))){
            if(isset($_SESSION['confidentialKey'])) {
                if(!password_verify($_SESSION['confidentialKey'],'die%^&49@rfk84hapaP7820%3GHmaasw!@')) 
                    die("잘못된 접근입니다"); 
            }else{
                die("직접접근은 허용되지 않습니다. <a href='/Action/auth'>로그인 하러 가기</a>"); 
            }
        }
        $this->load->model('manage/teacher_model');
        $this->load->model('manage/student_model');
        $this->load->model('manage/subject_model');
        $this->load->model('action/log_model');
    } 

	public function index()
	{

        $teachers = $this->teacher_model->getTeachers();

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/master/employee',array(
            'teachers' => $teachers,
            'gradeText' => $this->subject_model->getGradeText(),
            'levelText' => $this->subject_model->getLevelText(),
            'subjectText' => $this->subject_model->getSubjectText(),
        )); 
        $this->load->view('manage/common/mobileFooter');

		
    }

    public function resource()
	{

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/master/resource');
        $this->load->view('manage/common/mobileFooter');
		
    } 

    public function detail($id)
	{
        $teacher = $this->teacher_model->get(array('id'=>$id));

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/master/employee/detail',array(
            'teacher' => $teacher
        ));
        $this->load->view('manage/common/mobileFooter');
		
    } 

    public function addEmployee() { 

        $this->form_validation->set_rules('wname','이름', 'required');

        if($this->form_validation->run()!=FALSE){ 
            $worker_name = $this->input->post('wname');
            $hash=password_hash($this->input->post('birthday'),PASSWORD_BCRYPT);

            $result = $this->teacher_model->add(array(
                'name' => $worker_name,
                'type' => $this->input->post('wtype'),
                'pw' => $hash
            ));

            redirect('Manage/master/master/employee');
        }

    }

    function scheduleData() {

        $id = $this->input->post('id');
        $schedule = $this->teacher_model->getTeacherSchedule(array('id'=>$id));
 
        echo json_encode($schedule);
    }

    public function workerTime() {
        $workerId = $this->uri->segment(3);
        $urlYear = $this->uri->segment(4);
        $urlMonth = $this->uri->segment(5);

        $schedule = $this->worker_model->getWorkerSchedule(array('id'=>$workerId));
        $workers = $this->worker_model->getWorkers();
        
        $this->load->view('common/mobileHeader');
        $this->load->view('master/workerTime',array(
            'schedule' => $schedule,
            'workers' => $workers,
            'workerId' => $workerId,
            'urlYear' => $urlYear,
            'urlMonth' => $urlMonth
        ));
        $this->load->view('common/mobileFooter');
        
    }

    public function showOpinion() {

        $opinions = $this->log_model->getOpinions();

        $this->load->view('common/mobileHeader');
        $this->load->view('master/showOpinion',array(
            'opinions' => $opinions
        ));
        $this->load->view('common/mobileFooter');
    }

} 
 