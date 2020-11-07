<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends CI_Controller {


    //생성자 호출
	function __construct() {
		parent::__construct();
        $this->load->model('pay_model');
        if(!(isset($_SESSION['isMaster']) && isset($_SESSION['confidentialKey']))){
            if(isset($_SESSION['confidentialKey'])) {
                if(!password_verify($_SESSION['confidentialKey'],'die%^&49@rfk84hapaP7820%3GHmaasw!@')) 
                    die("잘못된 접근입니다"); 
            }else{
                die("직접접근은 허용되지 않습니다"); 
            }
        }
    }
    
    public function update() {

        $payID = $this->input->post('id');
        $payText = $this->input->post('payText');
        $amount = $this->input->post('amount');
        $paymentDate = $this->input->post('paymentDate');

		$result = $this->pay_model->updatePayment(array(
			'payID' => $payID,
			'payText' => $payText,
            'amount' => $amount,
            'paymentDate' => $paymentDate
		));

        echo $result;
        
    }

	public function accept()
	{

        $studentID = $this->input->post('id');
		$isLatest = $this->input->post('isLatest');
        $date = $this->input->post('date');
        $option = $this->input->post('option');
        $payDate = $this->input->post('payDate');
        $payText = $this->input->post('payText');
        $amount = $this->input->post('amount');

		$result = $this->pay_model->accept(array(
			'studentID' => $studentID,
			'isLatest' => $isLatest,
            'acceptedDate' => $date,
            'option' => $option,
            'payDate' => $payDate,
            'payText' => $payText,
            'amount' => $amount
		));

		echo $result;
		
    } 

    public function decline() {

		$studentID = $this->input->post('id');
		$isLatest = $this->input->post('isLatest');
		$date = $this->input->post('date');

        $result = $this->pay_model->declinePayment(array(
			'studentID' => $studentID,
			'isLatest' => $isLatest,
			'date' => $date
		));

		echo $result;
    }
    
    public function delete() {

        $paymentID = $this->uri->segment(3);
        $studentID = $this->uri->segment(4);
        $isLatest = $this->uri->segment(5);

        $result = $this->pay_model->deletePayment($paymentID,$isLatest,$studentID);
        
        if($result) redirect('student/detail/'.$studentID);
        else echo '<script>if(alert("삭제하는데 문제가 있습니다."))history.back();</script>';
    }

}

?>