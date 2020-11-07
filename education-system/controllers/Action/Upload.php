<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

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
        $this->load->model('test_model');
    } 

    public function doDelete(){

        $name = $this->input->post('fileName');
        $id = $this->input->post('id');
        $opt = $this->input->post('option');

        $filename = "/cuu225/www/static/file/studentTest/".$name;
        if (file_exists($filename)) {
            $success = unlink($filename);
            if (!$success) {
                echo 0;
            }else{
                if($opt) {
                    $this->test_model->updateOneCol(array(
                        'id' => $id,
                        'colName' => 'imageExist',
                        'updateVal' => '0',
                        'tableName' => 'student_eval_score',
                    ));
                }

                echo 1;
            }
        }
    }

	public function doUpload()
	{

        $newFileName = $_POST['fileName'];
        $scoreID = explode('_',$newFileName);

        $target_dir = "/cuu225/www/static/file/studentTest/";
        $target_file = $target_dir.basename($_FILES['file']["name"]);
        
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $temp = trim($scoreID[0])."_".trim($scoreID[1]).".".$imageFileType;
        $alt_file_name = $target_dir.$temp;

        /*if (file_exists($alt_file_name)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }*/
        
        if ($_FILES['file']["size"] > 1000000) {//1mb
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
            $errorCode = 10;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "pdf" ) {
                echo "Sorry, only JPG, JPEG, PNG, PDF & GIF files are allowed.";
                $uploadOk = 0;
            }

                // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo $errorCode;
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES['file']["tmp_name"], $alt_file_name)) {
                $this->test_model->updateOneCol(array(
                    'id' => $scoreID[1],
                    'colName' => 'imageExist',
                    'updateVal' => $temp,
                    'tableName' => 'student_eval_score',
                ));
                echo 1;
            } else {
                echo 0;
            }
        }

    }

} 