<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Consulting extends CI_Controller {

    //생성자 호출
	function __construct() {
        parent::__construct();
        $this->load->model('log_model'); 
    }
    
	public function index()
	{ 
		$this->requestHead();
		$this->load->view('homePage/consulting/send');
		$this->load->view('homePage/common/footer'); 
		
    } 


    public function send(){

		require 'static/include/mail/src/Exception.php';
		require 'static/include/mail/src/PHPMailer.php';
		require 'static/include/mail/src/SMTP.php';

		$name = $this->input->post('name');
		$contact = $this->input->post('contact');
		$subject = $this->input->post('subject');
		$message = $this->input->post('message');

		try {
			
			$mail = new PHPMailer(true);
			$mail->CharSet = "euc-kr";
			$mail->Encoding = "base64";
			$mail->IsSMTP();
			$mail->Host = "smtp.naver.com"; // email 보낼때 사용할 서버를 지정 
			$mail->SMTPAuth = true; // SMTP 인증을 사용함 
			$mail->Port = 465; // email 보낼때 사용할 포트를 지정 
			$mail->SMTPSecure = "ssl"; // SSL을 사용함 
			$mail->Username = $id; // Gmail 계정 
			$mail->Password = $password; // 패스워드 
			$mail->From = $id;
			$mail->FromName = iconv("UTF-8", "EUC-KR", "문의");
			$mail->Subject =  iconv("UTF-8", "EUC-KR", $subject); // 메일 제목 

			$messageText = '<p> 문의자 성함 : '.$name.' </p><p> 연락처 : '.$contact.'</p>-----------------------------------------------<br><p>'.$message.'</p>';
			$mail->MsgHTML($messageText); // 메일 내용 (HTML 형식도 되고 그냥 일반 텍스트도 사용 가능함)
	
			$mail->Send(); 
			
			redirect('HomePage/consulting/success');

		}
			

	}

	public function success() {
		$this->requestHead(); 
		$this->load->view('errors/email/success.html'); 
		$this->load->view('homePage/common/footer');
	}

	public function fail($messageText) {
		$this->requestHead();
		$this->load->view('errors/email/send_fail.php',array(
			'message'=> $messageText
		));
		$this->load->view('homePage/common/footer');
	}
    
    private function requestHead() {
        $this->load->view('homePage/common/header',array(
			'selectedMenuIndex' => 0
		)); 
    }

}
