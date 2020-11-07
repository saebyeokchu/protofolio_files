<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

    var $isTeacherLoggedIn = false;
    var $isMasterLoggedIn = false;
    var $isStudentLoggedIn = false;
    var $isParentLoggedIn = false;
 
   //생성자 호출
	function __construct() {
        parent::__construct();

        $this->load->model('manage/student_model');
        $this->load->model('manage/subject_model');
        $this->load->model('manage/teacher_model');
        $this->load->model('manage/parent_model');
        $this->load->model('action/auth_model');

        $this->isTeacherLoggedIn = $this->auth_model->isTeacherLoggedIn();
        $this->isMasterLoggedIn = $this->auth_model->isMasterLoggedIn();
        $this->isStudentLoggedIn = $this->auth_model->isStudentLoggedIn();
        $this->isParentLoggedIn = $this->auth_model->isParentLoggedIn();
    }

    function getBottomMenu() {
        if($this->isParentLoggedIn) $this->load->view('manage/parent/common/bottomMenu');
        else if($this->isMasterLoggedIn) $this->load->view('manage/master/common/bottomMenu');
        else if($this->isTeacherLoggedIn) $this->load->view('manage/teacher/common/bottomMenu');
    }

	public function index()
	{   
        if(!$this->isStudentLoggedIn) redirect('/Action/auth/logout');

        $student=$this->student_model->getStudentPropertyByID('name',$_SESSION['studentID']);

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/student/main',array(
            'name' => $student->name
        ));  
        $this->load->view('manage/common/mobileFooter');
		
    }

    public function info() {

        if($this->isTeacherLoggedIn||$this->isMasterLoggedIn||$this->isStudentLoggedIn) { 
            $studentID = $this->uri->segment(5);
            $studentName = $this->uri->segment(6);

            $student = $this->student_model->getStudentByID($studentID);
            $parent = $this->parent_model->getParentByStudentID($studentID);
 
            $this->load->view('manage/common/mobileHeader');
            if($this->isTeacherLoggedIn||$this->isMasterLoggedIn)
                $this->load->view('manage/student/common/topMenu',array(
                    'id'=> $studentID,
                    'name' => $studentName
                ));
            $this->load->view('manage/student/info',array(
                'student' => $student,
                'parent' => $parent
            ));
            $this->getBottomMenu();
            $this->load->view('manage/common/mobileFooter');
        }else redirect('/Action/auth/logout');

        
    }

    public function myClass() {
        
        if($this->isStudentLoggedIn) { 
            $studentID = $this->uri->segment(5);
            $studentName = $this->uri->segment(6);

            $student = $this->student_model->getStudentByID($studentID);
            $parent = $this->parent_model->getParentByStudentID($studentID);

            $this->load->view('manage/common/mobileHeader');
            $this->load->view('manage/student/myClass',array(
                'student' => $student,
                'parent' => $parent
            ));
            $this->getBottomMenu();
            $this->load->view('manage/common/mobileFooter');
        }else redirect('/Action/auth/logout');

        
    }


    function delete() {

        if($this->isTeacherLoggedIn||$this->isMasterLoggedIn) { //선생님과 마스터만 접근 가능
            $id = $this->input->post('id');

            $result = $this->student_model->delete(array(
                'id' => $id
            ));

            echo $result;
        }
    }

    public function edit($id) {

        if($this->isTeacherLoggedIn||$this->isMasterLoggedIn) { //선생님과 마스터만 접근 가능
            $this->form_validation->set_rules('sname','학생 이름', 'required');
            $this->form_validation->set_rules('pname','학부모 이름', 'required');
            
            if($this->input->post('enterYear')) {
                $thisYear = $this->input->post('enterYear');
                $date = $thisYear."-".$this->input->post('enterMonth')."-".$this->input->post('enterDay');
            }else{
                $date = "1988-01-01";
            }

            if($this->form_validation->run()){
                
                $deleteSubjectIDs=json_decode($this->input->post('deleteSubjectArrStr'));
                $addSubjectIDs=json_decode($this->input->post('addSubjectArrStr'));

                if($addSubjectIDs) {
                    //echo "add 실행됨";
                    foreach($addSubjectIDs as $addSubjectID) {
                        $this->subject_model->addStuIndex($addSubjectID,$id);
                    }
                }

                if($deleteSubjectIDs) {
                    //echo "delete 실행됨";
                    foreach($deleteSubjectIDs as $deleteSubjectID) {
                        $this->subject_model->deleteStuIndex($deleteSubjectID,$id);
                    }
                }

                $result = $this->student_model->update(array(
                    'name' => $this->input->post('sname'), 
                    'grade' => $this->input->post('sgrade'),
                    'fee' => $this->input->post('fee'),
                    'tel' => $this->input->post('stel'),
                    'date' => $date,
                    'id' => $id,
                    'birthday' => $this->input->post('sbirthday')
                ));

                $result = $this->parent_model->isAddOrUpdate(array( //잠깐 업뎃으로 해놈
                    'name' => $this->input->post('pname'), 
                    'tel' => $this->input->post('ptel'),
                    'birthday' => $this->input->post('pbirthday'),
                    'studentID' => $id
                ));

                if($result) {
                    echo '<script> alert("수정완료"); location.href="/Manage/student/student/edit/'.$id.'";</script>';
                } else echo '<script>alert("에러");history.back();</script>';

            }else{
                
                $student = $this->student_model->getStudentByID($id);
                $subjects = $this->subject_model->gets();
                $mySubjects = $this->subject_model->getSubjectsByStudentID($id);
                $teachers = $this->teacher_model->getTeachers();
                $parent = $this->parent_model->getParentByStudentID($id);
                //$parent = $this->parent_model->getParentByStudentID($id);

                $this->load->view('manage/common/mobileHeader');
                $this->load->view('manage/student/common/topMenu',array(
                    'id'=> $id,
                    'name' => $student->name
                ));
                $this->load->view('manage/student/edit',array(
                    'student' => $student,
                    'id' => $id,
                    'levelText' => $this->subject_model->getLevelText(),//과목 데이터 가져오기
                    'subjectText' => $this->subject_model->getSubjectText(),
                    'gradeText' => $this->subject_model->getGradeText(),
                    'subjects' => $subjects,
                    'mySubjects' => $mySubjects,
                    'teachers' => $teachers,
                    'parent' => $parent
                ));
                $this->getBottomMenu();
                $this->load->view('manage/common/mobileFooter');
            }
        }

    }

    public function add() {

        if($this->isTeacherLoggedIn||$this->isMasterLoggedIn) { //선생님과 마스터만 접근 가능
            //$this->load->library('form_validation');
            $this->form_validation->set_rules('sname','학생 이름', 'required');
            $this->form_validation->set_rules('pname','학부모 이름', 'required');
            /*$this->form_validation->set_rules('sgrade','학생 학년', 'required');
            $this->form_validation->set_rules('fee','보호자 이름', 'required');*/
            
            
            if($this->input->post('enterYear')) {
                $thisYear = $this->input->post('enterYear');
                $date = $thisYear."-".$this->input->post('enterMonth')."-".$this->input->post('enterDay');
            }else{
                $date = "1988-01-01";
            }

            if($this->form_validation->run()){

                $studentID = $this->student_model->add(array(
                    'name' => $this->input->post('sname'), 
                    'grade' => $this->input->post('sgrade'),
                    'fee' => $this->input->post('fee'),
                    'tel' => $this->input->post('stel'),
                    'date' => $date,
                    'classification' => $this->input->post('sCF'),
                    'birthday' => $this->input->post('sbirthday')
                ));

                $result = 1;

                $subjectIDs=json_decode($this->input->post('subjectArrayString'));
                if($subjectIDs) {
                    foreach($subjectIDs as $subjectID) { //과목추가
                        $result = $this->subject_model->addStuIndex($subjectID,$studentID);
                    }
                }

                if($studentID) { //학부모 정보넣기

                    $result = $this->parent_model->add(array(
                        'name' => $this->input->post('pname'), 
                        'tel' => $this->input->post('ptel'),
                        'birthday' => $this->input->post('pbirthday'),
                        'studentID' => $studentID
                    ));
                }
                
                if($result) redirect('Manage/student/master/index/all');
                else echo '<script>alert("에러");</script>';

            }else{

                $subjects = $this->subject_model->gets();
                $teachers = $this->teacher_model->getTeachers();
                
                $this->load->view('manage/common/mobileHeader');
                $this->load->view('manage/student/add',array(
                    'levelText' => $this->subject_model->getLevelText(),//과목 데이터 가져오기
                    'subjectText' => $this->subject_model->getSubjectText(),
                    'gradeText' => $this->subject_model->getGradeText(),
                    'subjects' => $subjects,
                    'teachers' => $teachers
                ));
                $this->load->view('manage/common/mobileFooter');
            }
        }
    }


    public function attendance($studentID) {

        $student = $this->student_model->getStudentByID($studentID);
        $attens = $this->student_model->getAttensByStudentID($studentID);

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/student/attendance',array(
            'student'=>$student,
            'attens' => $attens
        ));
        $this->load->view('manage/common/mobileFooter');
    }

    function acceptAttendance() { //출석체크 확인후 자신의 페이지로 넘어가기, stayed logged in

        $studentID = $this->uri->segment(5);

        if(isset($studentID)) {
            $isValidID = $this->student_model->checkStudentExistByID($studentID);
            
            if($isValidID) {
                $result = $this->student_model->create($studentID,1);
                $student = $this->student_model->getStudentByID($studentID); 

                if($result==1) {
                    redirect('Manage/student/student/attendance/'.$studentID);
                }
                
            }else{
                echo "<script>alert('해당하는 학생번호가 존재하지 않습니다');";
                echo "location.href='https://educocos.com/Manage/student/student/acceptAttendance';</script>";
            }
        }else{
            $this->load->view('manage/common/mobileHeader');
            $this->load->view('manage/student/acceptAttendance');
            $this->load->view('manage/common/mobileFooter');
        }
    }
    
    public function subject() {

        $subjectID = $this->uri->segment(5);
        $subjectTitle = $this->uri->segment(6);

        $students = $this->subject_model->getStudentsBySubject($subjectID);

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/student/subjectList',array(
            'students' => $students,
            'subjectTitle' => $subjectTitle,
            'subGoToURL' => '/Manage/student/student/info/'
        ));
        $this->getBottomMenu();
        $this->load->view('manage/common/mobileFooter');

        
    }


  
}

?>