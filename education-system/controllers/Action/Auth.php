<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	//생성자 호출
	function __construct() { 
        parent::__construct();
        $this->load->model('manage/teacher_model'); 
        $this->load->model('manage/parent_model');
        $this->load->model('manage/student_model');  
    }
 
	public function index(){

        /*if(get_cookie('rememberID')!==null) redirect('Action/auth/autoLogIn');*/

        $isTempID = $this->uri->segment(4); //등록번호 찾기를 실시한 경우
        if(isset($isTempID)) $this->session->set_flashdata('tempID',$isTempID);

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('errors/bannerNotice',array(
            'message' => '등록번호를 휴대폰번호로 변경하였습니다.'
        ));
        $this->load->view('action/auth/login'); 
        $this->load->view('manage/common/mobileFooter');
		
    }

    private function tcAutoLogIn($id,$is) {

        $user = $this->teacher_model->getById(array('id'=>$id));

        if($is=='master'){
            $this->setMasterSession();
            return '/Manage/master/master';
        }else{
            $this->setTeacherSession($user->id,$user->classification,$user->name);
            return '/Manage/teacher/teacher/index/'.$user->name.'/'.$user->classification;
        } 
    }
    
    private function sAutoLogIn($id){
        $student = $this->student_model->getStudentByID($id);
        $this->setStudentSession($student->id,$student->classification);
        return '/Manage/student/student';
    }

    function autoLogIn() {

        if($this->input->post('key')!=null){
            $key = $this->input->post('key');
            $token = $this->input->post('token');
            $is = $this->input->post('is');
            $id = $this->input->post('id');

            $auth = '';

            switch($is){
                case 'student' : {
                    $auth = $this->student_model->getAuthByID($id);
                    break;
                }
                case 'master' : {
                    break;
                }
                case 'teacher' : {
                    $auth = $this->teacher_model->getByAuthKey($id);
                    break;
                }
                case 'student' : {
                    break;
                }
                
            }

            if($key==$auth->uniqueKey) {
                if($token==$auth->token) { //이제로그인

                    switch($is){
                        case 'student' : {
                            $returnURL = $this->sAutoLogIn($id);
                            break;
                        }
                        case 'master' : {
                            $returnURL = $this->tcAutoLogIn($id,$is);
                            break;
                        }
                        case 'teacher' : {
                            $returnURL = $this->tcAutoLogIn($id,$is);
                            break;
                        }
                        case 'student' : {
                            break;
                        }
                        
                    }
                    echo $returnURL;

                }else{
                    $this->load->view('errors/html/error_login',array(
                        'headMessage' => '이미 로그인된 기기가 있습니다. 자동로그인은 한 기기에서만 가능합니다.',
                        'contentMessage' => '<a href="/Manage/auth/logout"> 다른 기기 로그아웃하고 진행하기 </a>'
                    )); 
                }
            }else{
                $this->load->view('errors/html/error_login',array(
                    'headMessage' => '비정상적인 접근입니다.',
                    'contentMessage' => '<a href="/Manage/auth/logout"> 로그인하러가기 </a>'
                )); 
            }
        }else $this->load->view('errors/html/error_login',array(
            'headMessage' => '비정상적인 접근입니다.',
            'contentMessage' => '<a href="/Manage/auth/logout"> 로그인하러가기 </a>'
        ));
    }

    function isRememberChecked($rememberMeChecked) {
        if($rememberMeChecked=='false') return false;
        else return true;
    }

    function setAutoSession($id,$is,$result) {

        $uniqueKey = password_hash(strrev($id), PASSWORD_DEFAULT); //항상같은 값
        $token = md5(uniqid(rand(), true)); //바뀌는값

        $insertResult = -1;;

        switch($is){
            case 'student' : {
                $insertResult = $this->student_model->addAutoLogIN($uniqueKey,$token,$id);
                break;
            }
            case 'master' : {
                break;
            }
            case 'teacher' : {
                $insertResult = $this->teacher_model->addAutoLogIN($uniqueKey,$token,$id);
                break;
            }
            case 'parent' : {
                break;
            }
            
        }

        if($insertResult==1) {
            $result['rememberMeChecked'] =true;
            $result['key']=$uniqueKey;
            $result['token']=$token;
            $result['id']=$id;
            $result['is']='student';
            $result['dup']= false;
        }else{ //오류코드 20 
            $result['dup']=true; 
        }

        return $result;

    }

    function sProcess() { //학생로그인

        $id = $this->input->post('id');
        $password = $this->input->post('password');
        $result = [];
        //99806487
        //000000 
        
        $student = $this->student_model->getIDByAuth($id,$password);
        $studentID = $student->id;

        $rememberMeChecked = $this->isRememberChecked($this->input->post('rememberMe'));

        if(isset($studentID)) {

            $result['rememberMeChecked'] = false;
            $result['state']=true;
            
            if($rememberMeChecked) $result = $this->setAutoSession($studentID,'student',$result);

            $this->setStudentSession($student->id,$student->classification);
            echo json_encode($result);
        }else{
            $result['state']=false;
            echo json_encode($result);
        }

    }

    function pProcess() { //학부모로그인

        $tel = $this->input->post('id');
        $birthday = $this->input->post('password');
        
        $user = $this->parent_model->getParentIDByAuth($tel,$birthday);

        if($user!=null){ //로그인 성공
            $this->session->set_userdata('LogedIn',true);
            $this->session->set_userdata('isParent',true);
            $this->session->set_userdata('parentID',$user->id); 
            echo '/Manage/parent/cparent/index/'.$user->id;
        }else { //로그인 실패
            echo -1;
        } 
    }
    
    function tcProcess() {//선생님로그인 

        $id = $this->input->post('id');
        $password = $this->input->post('password');

        $user = $this->teacher_model->getTeacherIDByAuth($id);
        $result = [];

        $rememberMeChecked = $this->isRememberChecked($this->input->post('rememberMe'));

        if(password_verify($password,$user->pw)){

            $result['rememberMeChecked'] = false;
            
            if($rememberMeChecked) $result = $this->setAutoSession($user->id,'teacher',$result);

            if($user->classification==0){
                $this->setMasterSession();

                $result['is'] = 'master';
                echo json_encode($result);
            }else{
                $this->setTeacherSession($user->id,$user->classification,$user->name);
                
                $result['is'] = 'teacher';
                $result['classification'] = $user->classification;
                $result['name'] = $user->name;
                echo json_encode($result);
            }
        }else {
            echo 0;
        }
    }

    function setMasterSession() {
            $this->session->set_userdata('isMaster',true);
        $this->session->set_userdata('confidentialKey',password_hash('die%^&49@rfk84hapaP7820%3GHmaasw!@',PASSWORD_BCRYPT));
    }

    function setStudentSession($id,$classification) {
      $this->session->set_userdata('isStudent',true);
        $this->session->set_userdata('studentID',$id);
        $this->session->set_userdata('cf',$classification); 
    }

    function setTeacherSession($id,$classification,$name) {
        $this->session->set_userdata('isTeacher',true);
        $this->session->set_userdata('teacherID',$id);
        $this->session->set_userdata('cf',$classification);
        $this->session->set_userdata('teacherName',$name);

    }

    public function logout() {

        if(isset($_SESSION['isTeacher'])) {
            $this->tLogout();
        }
        else if(isset($_SESSION['isMaster'])) {
            $this->mLogout();
        }
        else if(isset($_SESSION['isStudent'])) {
            $this->sLogout();
        } 
        else if(isset($_SESSION['isParent'])){
            $this->pLogout();
        }else{
            $this->aLogout();
        } 

    } 

    public function sLogout() {
        $this->student_model->deleteAutoAuth($_SESSION['studentID']);
        $this->session->unset_userdata('isStudent');
        $this->session->unset_userdata('studentID');
        $this->aLogout();
    }

    public function tLogout() {

        $this->teacher_model->deleteAutoAuth($_SESSION['teacherID']);

        $this->session->unset_userdata('isTeacher');
        $this->session->unset_userdata('teacherID');
        $this->session->unset_userdata('cf');
        $this->session->unset_userdata('teacherName');
        $this->aLogout();
    }

    public function mLogout() {

        $this->teacher_model->deleteAutoAuth(70600);

        $this->session->unset_userdata('isMaster');
        $this->session->unset_userdata('confidentialKey');
        $this->aLogout();

    }

    public function pLogout() {
        $this->session->unset_userdata('isParent');
        $this->session->unset_userdata('parentID');
        $this->aLogout();
    }

    function aLogout(){ 

        $this->session->unset_userdata('cocosLogedIn');
        redirect('Action/auth');
    }

    public function findRegisterNumber(){

		$this->form_validation->set_rules('name','이름', 'required');
        $this->form_validation->set_rules('phone','전화번호', 'required');
        
        if($this->form_validation->run()){
            $name = $this->input->post('name');
            $phone = $this->input->post('phone');

            $registerNumber = $this->teacher_model->getTeacherRegisterNumber($name,$phone);

            if(isset($registerNumber)){
                echo $registerNumber->id;
            }else{
                echo "0";
            }
        
        }else{
            $this->load->view('manage/common/mobileHeader');
            $this->load->view('action/auth/findRn'); 
            $this->load->view('manage/common/mobileFooter');
        }
    }

    public function resetPassword(){
 
		$this->form_validation->set_rules('registerNumber','이름', 'registerNumber');
        $this->form_validation->set_rules('phone','전화번호', 'required');
        
        if($this->form_validation->run()){
            $registerNumber = $this->input->post('registerNumber');
            $phone = $this->input->post('phone');

            $registerNumber = $this->teacher_model->getTeacherRegisterNumber($name,$phone);

            if(isset($registerNumber)){
                echo $registerNumber->id;
            }else{
                echo "0";
            }
        
        }else{ 
            $this->load->view('manage/common/mobileHeader');
            $this->load->view('action/auth/findPW'); 
            $this->load->view('manage/common/mobileFooter');
        }
    }


	
} 

?> 
 