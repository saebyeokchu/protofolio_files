<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    //생성자 호출
	function __construct() {
        parent::__construct();
        $this->load->model('manage/test_model');
        $this->load->model('manage/student_model');
        $this->load->model('manage/subject_model');
    }

    function getBottomMenu() {
        if(isset($_SESSION['isParent'])) $this->load->view('manage/parent/common/bottomMenu');
        else if(isset($_SESSION['isMaster'])) $this->load->view('manage/master/common/bottomMenu');
        else if(isset($_SESSION['isTeacher'])) $this->load->view('manage/teacher/common/bottomMenu');
    }

	public function index(){

        $studentID = $this->uri->segment(4);
        $studentName = $this->uri->segment(5);

        $testList = $this->test_model->getTestListByStudentID($studentID);
        $students = $this->student_model->getStudents();

        $this->load->view('manage/common/mobileHeader');

        //관계자에게만 보여야 하는 boolean
        $isWorkerOrMaster = (isset($_SESSION['isTeacher'])||isset($_SESSION['isMaster']));
        if($isWorkerOrMaster)
                $this->load->view('manage/student/common/topMenu',array(
                    'id'=> $studentID,
                    'name' => $studentName,
                    'subjects' => $this->subject_model->getSubjectText()
                ));
        $this->load->view('manage/test/monthlyTestList',array(
            'id' => $studentID,
            'name' => $studentName,
            'testList' => $testList,
            'isWorkerOrMaster' => $isWorkerOrMaster,
            'subjects' => $this->subject_model->getSubjectText()
        ));
        $this->getBottomMenu();
        $this->load->view('manage/common/mobileFooter');
        
    }

    public function monthlyTest(){

        $studentName = $this->uri->segment(4);
        $testID = $this->uri->segment(5);
        $studentID = $this->uri->segment(6);
        $target = $this->uri->segment(7);

        $evaluation = $this->test_model->getTestdataByID($testID);
        $students = $this->student_model->getStudents();

        $dataArray = array(
            'name' => $studentName,
            'eval' => $evaluation,
            'studentID' => $studentID
            );

        /*if($evaluation->mathDataExist) {
            $mathScoreDatas = $this->test_model-> getMathDataByEvalID($testID,1);
            $dataArray['mathScoreDatas']=$mathScoreDatas;
        }
        if($evaluation->englishDataExist) {
            $englishScoreDatas = $this->test_model-> getMathDataByEvalID($testID,2);
            $dataArray['englishScoreDatas']=$englishScoreDatas;

        }*/

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/test/monthlyTest', $dataArray);
        /*if($target=='parent')   $this->load->view('manage/test/forParent', $dataArray);
        else  {
            if (isset($_SESSION['isMaster'])||isset($_SESSION['isTeacher'])) {
                $this->load->view('manage/test/monthlyTest', $dataArray);
            }else{
                echo "직접접근은 허용되지 않습니다";
            }
        }*/

        $this->getBottomMenu();
        $this->load->view('manage/common/mobileFooter'); 
    }

    public function subject() {

        $subjectID = $this->uri->segment(4);
        $subjectTitle = $this->uri->segment(5);

        $students = $this->subject_model->getStudentsBySubject($subjectID);

        $this->load->view('manage/common/mobileHeader');
        $this->load->view('manage/student/subjectList',array(
            'students' => $students,
            'subjectTitle' => $subjectTitle,
            'subGoToURL' => '/Manage/test/index/'
        ));
        $this->getBottomMenu();
        $this->load->view('manage/common/mobileFooter');

        
    }

    function changeEvalDBOneCol($id) {

        $option = $this->input->post('option');
        $colName = $this->input->post('colName');
        $returnURL = $this->input->post('returnURL');
        $updateVal = $this->input->post('updateVal');
        $tableName = $this->input->post('tableName');

        $result =  $this->test_model->updateOneCol(array(
            'id' => $id,
            'colName' => $colName,
            'updateVal' => $updateVal,
            'tableName' => $tableName
        ));

        if($result) redirect($returnURL);
    }

    function updateScore($id) {

        $firstVal = $this->input->post('firstVal');
        $secondVal = $this->input->post('secondVal');

        $tableName = 'student_eval_score';

        $firstResult =  $this->test_model->updateOneCol(array(
            'id' => $id,
            'colName' => 'name',
            'updateVal' => $firstVal,
            'tableName' => $tableName
        ));
        $secondResult =  $this->test_model->updateOneCol(array(
            'id' => $id,
            'colName' => 'score',
            'updateVal' => $secondVal,
            'tableName' => $tableName
        ));

        echo ($firstResult && $secondResult);

    }

    function deleteScore($evalID) {

        $id = $this->input->post('id');
        $tableName = $this->input->post('tableName');
        $subject = $this->input->post('subject');

        $result =  $this->test_model->deleteScore(array(
            'id' => $id,
            'tableName' => $tableName,
            'evalID' => $evalID,
            'subject' => $subject
        ));

        echo $result;
    }

    function addScore($id){
        $name = $this->input->post('name');
        $score = $this->input->post('score');
        $subject = $this->input->post('subject');

        $result =  $this->test_model->insertScore(array(
            'id' => $id,
            'name' => $name,
            'score' => $score,
            'subject' => $subject
        ));

        echo $result;
    }

    function edit($id) { //진도 업데이트

        $progress = $this->input->post('progress');
        $totalSuggestion = $this->input->post('totalSuggestion');

        /*if($subject == 1 ) $colName="mathProgress";
        else $colName="englishProgress";*/

        $result =  $this->test_model->update(array(
            'id' => $id,
            'progress' => $progress,
            'totalSuggestion' => $totalSuggestion
        ));

        echo $result; 
    }

    function addTest($id) {
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $subject = $this->input->post('subject');

        $dateString = $year."-".$month;

        $result =  $this->test_model->addTest(array(
            'studentID' => $id,
            'evalDate' => $dateString,
            'subject' => $subject
        )); 

        echo $result;
    }

    function deleteTest() {

        $id = $this->input->post('id');

        $result =  $this->test_model->deleteTest(array(
            'id' => $id
        ));

        echo $result;
    }

}

?> 